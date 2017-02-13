<?php
	class codegen{
		/** @var double version */
		var $version = 0.04;
		/** @var string name_separator */
		var $name_separator = '_';
		/** @var array variables */
		var $variables = [];
		/** @var integer count variables */
		var $count_variables = 0;



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
				if(mt_rand(0, 1)){
					$type = mt_rand(0, 1) ? 'string' : 'integer';
				}
				else{
					$type = array_keys($this -> default_values);
					$type = $type[mt_rand(0, count($type) - 1)];
				}
			}

			if($scalar && in_array($type, ['array', 'object'])){
				return $this -> generate_variable($name, $scalar);
			}

			$variable['type'] = $type;


			$variable['value'] = $this -> default_values[$variable['type']][mt_rand(0, count($this -> default_values[$variable['type']]) - 1)];

			return $variable;
		}

		/**
		 * @param array $var1
		 * @param array $var2
		 * @param array $var3
		 * @return string code
		 */
		public function generate_variables_relations(array $var1, array $var2, array $var3 = []){
			$vars = [];
			$key = [];
			foreach([$var1, $var2, $var3] as $v){
				if($v){
					$vars[] = $v;
					$key[] = $v['type'];
				}
			}

			if(count($vars) > 1){
				$key = implode('^', $key);

				if(isset($this -> variables_relations[$key])){
					if(count($this -> variables_relations[$key])){
						$res = $this -> variables_relations[$key][mt_rand(0, count($this -> variables_relations[$key]) - 1)];

						if(!is_string($res)){
							return trigger_error('variables_relations isnt string: ' . $key);
						}


						$res .= ';' . PHP_EOL;

						$res = str_replace('{c}', '$' . $var1['name'], $res);
						$res = str_replace('{d}', '$' . $var2['name'], $res);
						if($var3){
							$res = str_replace('{e}', '$' . $var3['name'], $res);
						}


						$arr1 = ['+', '-', '*']; // @+
						$arr2 = ['&', '|', '%', '^']; // @&
						$arr3 = ['>', '<', '>=', '<=', '!=', '==']; // @>
						$res = str_replace(['@+', '@&', '@>'], [$arr1[mt_rand(0, count($arr1) - 1)], $arr2[mt_rand(0, count($arr2) - 1)], $arr3[mt_rand(0, count($arr3) - 1)]], $res);

						return $res;
					}
				}
				else{
					trigger_error('unknown variables_relation: ' . $key);
				}
			}

			return PHP_EOL . '# relations not found for: ' . $key . ';';
		}


		// functions
		/**
		 * @param int $mode of function
		 * 1 - arguments
		 * 2 - arr
		 * 3 - obj
		 * @return string code
		 */
		public function generate_function($mode = 0){
			$mode = $mode ? $mode : mt_rand(1, 3);
			$code = '';
			$name = $this -> format_name($this -> generate_name('sections') . $this -> name_separator . $this -> generate_name('method') . $this -> name_separator . $this -> generate_name('title'), 2);



			$arguments = [];
			if($mode == 1){ // arguments mode
				$count = mt_rand(1, 4);

				for($i = 0; $i <= $count; $i ++){
					$arguments[] = $this -> generate_variable($this -> generate_name('type', 0, array_merge($arguments, $this -> variables)));
				}
			}
			else if($mode == 2){ // arr mode
				$arguments[] = $this -> generate_variable('arr');
			}
			else if($mode == 3){ // obj mode
				$arguments[] = $this -> generate_variable('obj');
			}



			// function arguments formatting
			$arguments_str = '';
			if($arguments){
				$tmp = [];

				foreach($arguments as $arg){
					$tmp[$arg['name']] = $arg['type'] . ' $' . $arg['name'];

					if($arg['value'] != 300){
						if($arg['type'] == 'string'){
							$arg['value'] = '"' . $arg['value'] . '"';
						}

						$tmp[$arg['name']] .= ' = ' . $arg['value'];
					}
				}

				$arguments_str = implode(', ', $tmp);
				unset($tmp);
			}
			$count_arguments = count($arguments);


			// function variables generate
			$variables = [];
			$var_types = ['result', 'content'];
			$new = mt_rand(1, 5);
			if($new){
				$new = $new < $count_arguments + 1 ? $count_arguments + 1 : $new;
				for($i = 0; $i < $new; $i ++){
					$variables[] = $this -> generate_variable($this -> generate_name($var_types[mt_rand(0, count($var_types) - 1)], 0, array_merge($variables, $this -> variables, $arguments)), true);
				}
			}
			$count_variables = count($variables);



			// class variables inserting
			$properties = [];
			foreach($this -> variables as $var){
				if(mt_rand(0, 1)){
					$var['name'] = 'this -> $' . $var['name'];
					$properties[] = $var;
				}
			}
			if(!count($properties)){
				$properties[] = $this -> variables[mt_rand(0, $this -> count_variables - 1)];
				$properties[0]['name'] = 'this -> $' . $properties[0]['name'];
			}
			$count_properties = count($properties);




			// relations
			/*
			 * arguments >= 1
			 * properties >= 1
			 * variables >= arguments + 1
			 *
			 *
			 * 	$variables <--- ?$arguments(1,2) | ?$properties(5,6) | ?$variables from $stack $ready (10,11)
			 *  $variables ---> ?$properties | ?return
			 */

			$used = $used_names = $last_var = $inp_pool = [];
			$_variables = $variables;
			$_arguments = $arguments;
			$_properties = $properties;


			while(count($_variables)){
				$stack = [];
				$v = array_rand($_variables);


				while(count($stack) <= 1){
					// relation with $_arguments
					if(count($stack) < 2 && mt_rand(0, 1)){
						for($n = count($_arguments) - 1; $n >= 0 ; $n --){
							if(!in_array($_arguments[$n]['name'], $used_names)){
								$stack[] = $_arguments[$n];
								unset($_arguments[$n]);
								break;
							}
						}
					}

					// relation with $_properties
					if(count($stack) < 2 && mt_rand(0, 1)){
						for($n = count($_properties) - 1; $n >= 0; $n --){
							if(!in_array($_properties[$n]['name'], $used_names)){
								$stack[] = $_properties[$n];
								unset($_properties[$n]);
								break;
							}
						}
					}

					// relation with $stack variables
					if(count($stack) < 2 && count($used) && mt_rand(0, 1)){
						for($n = 0; $n < count($used); $n ++){
							$stack[] = $used[$n];
							break;
						}
					}
				}


				shuffle($stack);


				$code .= $this -> generate_variables_relations($_variables[$v], $stack[0], isset($stack[1]) ? $stack[1] : []);

				$used[] = $_variables[$v];
				$used_names[] = $_variables[$v]['name'];

				$used[] = $stack[0];
				$used_names[] = $stack[0]['name'];

				if(isset($stack[1])){
					$used[] = $stack[1];
					$used_names[] = $stack[1]['name'];
				}


				unset($_variables[$v]);


				if(!count($_variables)){ // others
					if(count($_arguments)){
						$_variables[] = $_arguments[count($_arguments) - 1];
						unset($_arguments[count($_arguments) - 1]);
						continue;
					}
					if(count($_properties)){
						$_variables[] = $_properties[count($_properties) - 1];
						unset($_properties[count($_properties) - 1]);
						continue;
					}
				}

				if(!count($_variables) && count($variables)){ // finally
					/*
					 * V1 NV1 RV1
					 * V2
					 * V3 NV2
					 * V4
					 * */

					$inp = $variables;
					$res = [];

					while(count($inp)){
						if(count($inp)){
							$last_var = $v = $this -> generate_variable($this -> generate_name('temp', 0, $inp_pool));

							$z2 = count($inp) - 1;
							$z3 = count($inp) - 2;

							$code .= $this -> generate_variables_relations($v, $inp[$z2], isset($inp[$z3]) ? $inp[$z3] : []);
							$res[] = $v;
							$inp_pool[] = $v;

							unset($inp[$z2]);
							if(isset($inp[$z3])){
								unset($inp[$z3]);
							}
						}

						if(!count($inp) && count($res) > 1){
							$inp = $res;
							$res = [];
						}
					}
				}
			}



			// return last var
			$code .= PHP_EOL . 'return $' . $last_var['name'] . ';';



			// make PHPDOC
			$doc = '';
			for($i = 0; $i < $count_arguments; $i ++){
				$doc .= PHP_EOL . '* @param ' . $arguments[$i]['type'] . ' $' . $arguments[$i]['name'];
			}
			for($i = 0; $i < $count_variables; $i ++){
				$doc .= PHP_EOL . '* @var ' . $variables[$i]['type'] . ' $' . $variables[$i]['name'];
			}
			for($i = 0; $i < count($inp_pool); $i ++){
				$doc .= PHP_EOL . '* @var ' . $inp_pool[$i]['type'] . ' $' . $inp_pool[$i]['name'];
			}
			for($i = 0; $i < $count_properties; $i ++){
				$doc .= PHP_EOL . '* @prop ' . $properties[$i]['type'] . ' $' . $properties[$i]['name'];
			}
			$doc .= PHP_EOL . '* @return ' . $last_var['type'] . ' $' . $last_var['name'];



			// print
			$code = PHP_EOL . '/**' . $doc . PHP_EOL . '*/' . 'function ' . $name . '(' . $arguments_str . '){' . PHP_EOL . $code . PHP_EOL . '}';
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
			$this -> count_variables = count($this -> variables);


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

				if($code[$i] == ';' && $code[$i + 1] == "\n"){
					$fin = "\n";
				}
				if($code[$i] == '{' && $code[$i + 1] == ' '){
					$fin = "\n";
				}
				if($code[$i] == '}' && $code[$i + 1] == ' '){
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

				if(strstr($c, "{\r")){
					$next ++;
				}
				if(strstr($c, "}\r")){
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