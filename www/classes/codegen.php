<?
	class codegen{
		var $version = 0.01;
		/** @var string separator */
		var $name_separator = '_';
		/** @var array current values */
		var $current_args, $current_vars, $current_comment;



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
		 * @return string
		*/
		public function generate_name($type, $case = 0){
			if(isset($this -> key_words[$type])){
				return $this -> format_name($this -> key_words[$type][mt_rand(0, count($this -> key_words[$type]) - 1)], $case);
			}

			return trigger_error('generate_name_error');
		}


		// vars
		/**
		 * @param string $name
		 * @return array $value - variable: [name, type, value]
		 */
		public function generate_variable($name = ''){
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
		 * @return string arguments
		 */
		public function generate_function_arguments(){
			if($this -> current_args){
				$tmp = [];

				foreach($this -> current_args as $arg){
					$tmp[$arg['name']] = $arg['type'] . ' $' . $arg['name'];

					if($arg['value'] != 300){
						$tmp[$arg['name']] .= ' = ' . $arg['value'];
					}
				}

				return implode(', ', $tmp);
			}

			return '';
		}

		/**
		 * @return string code
		 */
		public function generate_function_body(){
			$code = '';

			// inner variables
			$variables = []; // name => type
			$var_types = ['result', 'content'];
			$new = mt_rand(1, 5);
			if($new){
				$new = $new < count($this -> current_args) ? count($this -> current_args) : $new;

				for($i = 0; $i < $new; $i ++){
					$variables[] = $this -> generate_variable($this -> generate_name($var_types[mt_rand(0, count($var_types) - 1)]));

					// declaration of variables
					// $code .= holly
				}
			}




			// $types = array_map(function($n){return gettype($n);}, array_values($args));

			// relations args with variables
			$count_args = count($this -> current_args);
			$count_variables = count($variables);
			$last = 0;


			// make header
			$this -> current_comment .= 'args: ' . $count_args;
			for($i = 0; $i < $count_args; $i ++){
				$this -> current_comment .= PHP_EOL . '$' . $this -> current_args[$i]['name'] . ' (' . $this -> current_args[$i]['type'] . ')';
			}
			$this -> current_comment .= PHP_EOL . PHP_EOL . 'vars: ' . $count_variables;
			for($i = 0; $i < $count_variables; $i ++){
				$this -> current_comment .= PHP_EOL . '$' . $variables[$i]['name'] . ' (' . $variables[$i]['type'] . ')';
			}




			// relations args with variables
			for($i = 0; $i < $count_args; $i ++){
				$code .= $this -> generate_variables_relations($variables[$i], $this -> current_args[$i]);
			}

			// relations variables with variables
			for($i = 1; $i < $count_variables; $i ++, $last = $i - 1){
				$code .= $this -> generate_variables_relations($variables[$i], $variables[$i - 1]);
			}

			// return last var
			$code .= PHP_EOL . 'return $' . $variables[$last]['name'] . ';';


			// $value = mt_rand(0, 1) ? $this -> generate_random_string() : mt_rand(0, 999999);
			// $code = '"return ' . $value . '";';

			return $code;
		}

		/**
		 * @param int $type of function
		 * @return string code
		 */
		public function generate_function($type = 0){
			$name = $this -> format_name($this -> generate_name('sections') . $this -> name_separator . $this -> generate_name('method') . $this -> name_separator . $this -> generate_name('title'), 2);

			$type = $type ? $type : mt_rand(1, 3);
			$this -> current_args = [];
			$this -> current_comment = '';

			if($type == 1){ // args mode
				$count = mt_rand(1, 4);

				for($i = 0; $i <= $count; $i ++){
					$this -> current_args[] = $this -> generate_variable($this -> generate_name('type'));
				}
			}
			else if($type == 2){ // arr mode
				$this -> current_args[] = $this -> generate_variable('arr');
			}
			else if($type == 3){ // obj mode
				$this -> current_args[] = $this -> generate_variable('obj');
			}


			$body = $this -> generate_function_body();
			$args = $this -> generate_function_arguments();


			$code = '/*' . PHP_EOL . $this -> current_comment . PHP_EOL . '*/' . PHP_EOL . PHP_EOL . 'function ' . $name . '(' . $args . '){' . $body . PHP_EOL . '}';

			return $code;
		}

		public function generate_class_method(){

		}


		// classes
		public function generate_class(){
			$name = $this -> format_name($this -> generate_name('globals') . $this -> name_separator . $this -> generate_name('sections'), 2);
			$code = 'class ' . $name . '{';

			// vars of class
			$this -> current_vars = [];
			$new = mt_rand(1, 5);
			for($i = 0; $i < $new; $i ++){
				$this -> current_vars[] = $this -> generate_variable($this -> generate_name('content'));
			}
//var_dump($this -> current_vars); die;

			// declaration of variables
			foreach($this -> current_vars as $k => $v){
			//	$code .= PHP_EOL . 'var $' . $k . ' = ' . $v;
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

				if($code[$i] == ';'){
					$fin = "\n";
				}
				if($code[$i] == '{'){
					$fin = "\n";
				}
				if($code[$i] == '}'){
					$pre = "\n";
					$fin = "\n";
				}
				if($code[$i] == '/' && $code[$i + 1] == '*'){
					$pre = "\n";
				}
				if($code[$i] == '*' && $code[$i - 1] == '/'){
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

			return preg_replace('/\s+$/m', '', $res);
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
			$code = '
			<?
				/*
					generated by codegen v' . $this -> version . '
				*/
			';

			$code .= $this -> generate_function();
			$code .= $this -> generate_class();
			$code .= PHP_EOL . '?>';

			return $this -> code_print_format($code);
		}
	}
?>