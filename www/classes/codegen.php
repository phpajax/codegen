<?
	class codegen{
		/** @var double version */
		var $version = 0.02;
		/** @var string name_separator */
		var $name_separator = '_';
		/** @var array variables */
		var $variables = [];



		function __construct(){
			$this -> key_words = & $GLOBALS['key_words'];
			$this -> default_values = & $GLOBALS['default_values'];
			$this -> variables_relations = & $GLOBALS['variables_relations'];
		}

		// name
		/**
		*	@param string $name
		*	@param int $case = 0
		*
		*	case 1: Var_name
		*	case 2: Var_Name
		*	case 3: VAR_NAME
		*	@return string
		*/
		public function format_name($name, $case = 0){
			$name = str_replace($this -> name_separator, ' ', $name);

			switch($case){
				case 1: $name = ucfirst($name); break;
				case 2: $name = ucwords($name); break;
				case 3: $name = strtoupper($name); break;
			}

			return str_replace(' ', $this -> name_separator, $name);
		}

		/**
		 * @param string $type
		 * @param int $case to format_name()
		 * @param array $vars - to check free name
		 * @return string
		*/
		public function generate_name($type, $case = 0, $vars = null){
			if(isset($this -> key_words[$type])){
				$name = $this -> format_name($this -> key_words[$type][mt_rand(0, count($this -> key_words[$type]) - 1)], $case);
				if($vars){
					foreach($vars as $var){
						if($var['name'] == $name){
							return $this -> generate_name($type, $case, $vars);
						}
					}
				}

				return $name;
			}

			return trigger_error('generate_name_error');
		}


		// vars
		/**
		 * @param string $name
		 * @param boolean $scalar
		 * @return array $value - variable: [name, type, value]
		 */
		public function generate_variable($name = '', $scalar = false){
			$variable = [];
			$variable['name'] = $name;


			if($name == 'arr'){
				$type = 'array';
			}
			else if($name == 'obj'){
				$type = 'object';
			}
			else{
				$type = array_keys($this -> default_values);
				$type = $type[mt_rand(0, count($type) - 1)];
			}

			if($scalar && in_array($type, ['array', 'object'])){
				return $this -> generate_variable($name, $scalar);
			}

			$variable['type'] = $type;


			$variable['value'] = $this -> default_values[$variable['type']][mt_rand(0, count($this -> default_values[$variable['type']]) - 1)];

			return $variable;
		}

		/**
		 * @param array $content_var
		 * @param array $data_var
		 * @return string code
		 */
		public function generate_variables_relations(array $content_var, array $data_var){
			$key = $content_var['type'] . '^' . $data_var['type'];

			if(isset($this -> variables_relations[$key])){
				if(count($this -> variables_relations[$key])){
					return str_replace(['{c}', '{d}'], ['$' . $content_var['name'], '$' . $data_var['name']], $this -> variables_relations[$key][mt_rand(0, count($this -> variables_relations[$key]) - 1)] . ';');
				}
			}
			else{
				trigger_error('uncknown variables_relation: ' . $key);
			}

			return PHP_EOL . '#no relations found for: ' . $key . PHP_EOL . '$' . $content_var['name'] . ' = $' . $data_var['name'] . ';';
		}


		// functions
		/**
		 * @param int $mode of function
		 * 1 - args
		 * 2 - arr
		 * 3 - obj
		 * @return string code
		 */
		public function generate_function($mode = 0){
			$mode = $mode ? $mode : mt_rand(1, 3);
			$code = '';
			$args = [];
			$name = $this -> format_name($this -> generate_name('sections') . $this -> name_separator . $this -> generate_name('method') . $this -> name_separator . $this -> generate_name('title'), 2);



			if($mode == 1){ // args mode
				$count = mt_rand(1, 4);

				for($i = 0; $i <= $count; $i ++){
					$args[] = $this -> generate_variable($this -> generate_name('type'));
				}
			}
			else if($mode == 2){ // arr mode
				$args[] = $this -> generate_variable('arr');
			}
			else if($mode == 3){ // obj mode
				$args[] = $this -> generate_variable('obj');
			}


			// function arguments
			$arguments = '';
			if($args){
				$tmp = [];

				foreach($args as $arg){
					$tmp[$arg['name']] = $arg['type'] . ' $' . $arg['name'];

					if($arg['value'] != 300){
						if($arg['type'] == 'string'){
							$arg['value'] = '"' . $arg['value'] . '"';
						}

						$tmp[$arg['name']] .= ' = ' . $arg['value'];
					}
				}

				$arguments = implode(', ', $tmp);
			}




			// function variables
			$variables = [];
			$var_types = ['result', 'content'];
			$new = mt_rand(1, 5);
			if($new){
				$new = $new < count($args) ? count($args) : $new;

				for($i = 0; $i < $new; $i ++){
					$variables[] = $this -> generate_variable($this -> generate_name($var_types[mt_rand(0, count($var_types) - 1)], 0, $variables), true);
				}
			}

			// class variables inserting
			foreach($this -> variables as $var){
				$var['name'] = 'this -> $' . $var['name'];
				$variables[] = $var;
			}



			// relations args with variables
			$count_args = count($args);
			$count_variables = count($variables);
			$last = 0;


			// make PHPDOC
			$doc = '';
			for($i = 0; $i < $count_args; $i ++){
				$doc .= PHP_EOL . '* @param ' . $args[$i]['type'] . ' $' . $args[$i]['name'];
			}
		//	$doc .= PHP_EOL . PHP_EOL;
			for($i = 0; $i < $count_variables; $i ++){
				$doc .= PHP_EOL . '* @var ' . $variables[$i]['type'] . ' $' . $variables[$i]['name'];
			}
			$doc .= PHP_EOL . '* @return ' . $variables[$count_variables - 1]['type'] . ' $' . $variables[$count_variables - 1]['name'];



			// relations args with variables
			for($i = 0; $i < $count_args; $i ++){
				$code .= $this -> generate_variables_relations($variables[$i], $args[$i]);
			}

			// relations variables with variables
			for($i = 1; $i < $count_variables; $i ++, $last = $i - 1){
				$code .= $this -> generate_variables_relations($variables[$i], $variables[$i - 1]);
			}

			// return last var
			$code .= PHP_EOL . 'return $' . $variables[$last]['name'] . ';';


			// $value = mt_rand(0, 1) ? $this -> generate_random_string() : mt_rand(0, 999999);
			// $code = '"return ' . $value . '";';



			$code = PHP_EOL . '/**' . $doc . PHP_EOL . '*/' . 'function ' . $name . '(' . $arguments . '){' . $code . PHP_EOL . '}';

			return $code;
		}



		// classes
		public function generate_class(){
			$name = $this -> format_name($this -> generate_name('globals') . $this -> name_separator . $this -> generate_name('sections'), 2);

			$code = '/** @class ' . $name . ' generated by codegen v' . $this -> version . ' */';
			$code .= 'class ' . $name . '{';

			// vars of class
			$new = mt_rand(2, 5);
			for($i = 0; $i < $new; $i ++){
				$this -> variables[] = $this -> generate_variable($this -> generate_name('content', 0, $this -> variables));
			}


			$get_quotes = function($var){
				if($var['type'] == 'string'){
					$var['value'] = '"' . $var['value'] . '"';
				}
				else if($var['value'] == 300){
					return null;
				}

				return  ' = ' . $var['value'];
			};
			// declaration of variables
			foreach($this -> variables as $var){
				$code .= PHP_EOL . '/** $var ' . $var['type'] . ' ' . $var['name'] . ' */';
				$code .= 'var $' . $var['name'] . $get_quotes($var) . ';';
			}

			$code .= PHP_EOL . PHP_EOL;

			// methods of class
			$code .= $this -> generate_function(3);
			$new = mt_rand(2, 5);
			for($i = 0; $i < $new; $i ++){
				$code .= $this -> generate_function();
			}

			return $code . '}';
		}


		// other
		/**
		 * @param string $code
		 * @return string code
		 */
		public function code_print_format($code){
			$code = str_replace(["\t", "\r", "\n"], ['', '', "\r"], $code);
			$res = '';
			$tab = $next = 0;


			$length = strlen($code);
			for($i = 0; $i < $length; $i ++){
				$pre = $fin = '';

				if($code[$i] == ';' && $code[$i + 2] != '}'){
					$fin = "\n";
				}
				if($code[$i] == '{'){
					$fin = "\n";
				}
				if($code[$i] == '}' && $code[$i + 1] != ','){
					$fin = "\n";
				}
				if($code[$i] == '/' && $code[$i + 1] == '*'){
					$pre = "\n";
				}
				if($code[$i] == '*' && $code[$i - 1] == '*' && $code[$i - 2] == '/'){
					$fin = "\n";
				}
				if($code[$i] == '*' && $code[$i + 1] == '/'){
					$pre = "\n";
				}
				if($code[$i] == '/' && $code[$i - 1] == '*'){
					$fin = "\n";
				}

				if($code[$i] == "\r"){
					$fin = "\n";
				}

				$res .= $pre . $code[$i] . $fin;
			}
			$code = $res;
			$res = '';


			$code = explode("\n", $code);
			foreach($code as $c){
				$next = $tab;

				if(strstr($c, '<?')){
					$next ++;
				}
				else if(strstr($c, '?>')){
					$tab --;
				}

				if(strstr($c, '/*')){
					$next ++;
				}
				else if(strstr($c, '*/')){
					$next --;
					$tab --;
				}

				if(strstr($c, '{')){
					$next ++;
				}
				else if(strstr($c, '}')){
					$tab --;
					$next --;
				}

				$res .= "\n" . str_repeat("\t", $tab) . $c;

				$tab = $next;
			}

			return $res;
		}
		/**
		 * @param int $length
		 * @return string
		 */
		public function generate_random_string($length = 0){
			$str = '';
			$length = $length ? $length : mt_rand(1, 32);

			for($i = 0; $i < $length; $i ++){
				$str .= chr(mt_rand(38, 126));
			}

			return $str;
		}


		// result
		public function generate(){
			$code = '<?';

			$code .= $this -> generate_class();
			$code .= PHP_EOL . '?>';

			return $this -> code_print_format($code);
		}
	}
?>