<?
	class code{
/*
$the_breath_of_life = @be() or !toBe(); 
смысл жизни 42

use happyness;
use goodMood;

$x = 3 + '15%' + "$25"
	echo 2 + '2e3';
*/
		var $version = 0.01;
		var $name_separator = '_';
		var $current_args, $current_comment;



		function __construct(){
			$this -> key_words = & $GLOBALS['key_words'];
			$this -> default_values = & $GLOBALS['default_values'];
			$this -> variables_relations = & $GLOBALS['variables_relations'];
		}

		public function formate_name($name, $case = 0){
			$name = str_replace($this -> name_separator, ' ', $name);

			switch($case){
				case 1: $name = ucfirst($name); break;
				case 2: $name = ucwords($name); break;
				case 3: $name = strtoupper($name); break;
			}

			return str_replace(' ', $this -> name_separator, $name);
		}
		public function generate_name($type, $case = 0){
			if(isset($this -> key_words[$type])){
				return $this -> formate_name($this -> key_words[$type][mt_rand(0, count($this -> key_words[$type]) - 1)], $case); 
			}

			trigger_error('generate_name_error');
		}


		public function generate_variable_type(){
			$keys = array_keys($this -> default_values);
			return $keys[mt_rand(0, count($keys) - 1)];
		}
		public function generate_variable_default_value($type = 0){
			$type = $type ? $type : $this -> generate_variable_type();

			return $this -> default_values[$type][mt_rand(0, count($this -> default_values[$type]) - 1)];
		}
		public function generate_function_arguments(){
			if($this -> current_args){
				$tmp = [];

				foreach($this -> current_args as $k => $v){
					$tmp[$k] = gettype($v) . ' $' . $k;

					if(is_object($v)){
						$tmp[$k] .= ' = NULL';
					}
					else if($v != 300){
						if(is_array($v)){
							$tmp[$k] .= ' = []';
						}
						else{
							$tmp[$k] .= ' = ' . var_export($v, true);
						}
					}
				}

				return implode(', ', $tmp);
			}

			return '';
		}
		public function generate_random_string($length = 0){
			$str = '';
			$length = $length ? $length : mt_rand(1, 32);

			for($i = 0; $i < $length; $i ++){
				$str .= chr(mt_rand(38, 126));
			}

			return $str;
		}
		public function generate_variables_relations(array $content_var, array $data_var){
			$key = array_values($content_var)[0] . '^' . array_values($data_var)[0];

			if(isset($this -> variables_relations[$key])){
				if(count($this -> variables_relations[$key])){
					return str_replace(['{c}', '{d}'], ['$' . array_keys($content_var)[0], '$' . array_keys($data_var)[0]], $this -> variables_relations[$key][mt_rand(0, count($this -> variables_relations[$key]) - 1)] . ';');
				}
			}
			else{
				trigger_error('uncknown variables_relation: ' . $key);
			}

			return PHP_EOL . '#no relations found for: ' . $key . PHP_EOL . '$' . array_keys($content_var)[0] . ' = $' . array_keys($data_var)[0] . ';';
		}
		public function generate_function_body(){
			$code = '';

			// inner variables
			$variables = []; // name => type
			$var_types = ['result', 'content'];
			$new = mt_rand(1, 5);
			if($new){
				$new = $new < count($this -> current_args) ? count($this -> current_args) : $new;

				for($i = 0; $i < $new; $i ++){
					$variables[] = [$this -> generate_name($var_types[mt_rand(0, count($var_types) - 1)]) => $this -> generate_variable_type()];

					// declaration of variables
					// $code .= holly
				}
			}


			$args = [];
			foreach($this -> current_args as $k => $v){
				$args[] = [$k => gettype($v)];
			}



			// $types = array_map(function($n){return gettype($n);}, array_values($args));

			// relations args with variables
			$count_args = count($args);
			$count_variables = count($variables);
			$last = 0;


			// make header
			$this -> current_comment .= 'args: ' . $count_args;
			for($i = 0; $i < $count_args; $i ++){
				$this -> current_comment .= PHP_EOL . '$' . array_keys($args[$i])[0] . ' (' . array_values($args[$i])[0] . ')';
			}
			$this -> current_comment .= PHP_EOL . PHP_EOL . 'vars: ' . $count_variables;
			for($i = 0; $i < $count_variables; $i ++){
				$this -> current_comment .= PHP_EOL . '$' . array_keys($variables[$i])[0] . ' (' . array_values($variables[$i])[0] . ')';
			}




			// relations args with variables
			for($i = 0; $i < $count_args; $i ++){
				$code .= $this -> generate_variables_relations($variables[$i], $args[$i]);
			}

			// relations variables with variables
			for($i = 1; $i < $count_variables; $i ++, $last = $i - 1){
				$code .= $this -> generate_variables_relations($variables[$i], $variables[$i - 1]);
			}

			// return last var
			$code .= PHP_EOL . 'return $' . array_keys($variables[$last])[0] . ';';


					// $value = mt_rand(0, 1) ? $this -> generate_random_string() : mt_rand(0, 999999);
					// $code = '"return ' . $value . '";';

			return $code;
		}
		public function generate_function($type = 0){
			$name = $this -> formate_name($this -> generate_name('sections') . $this -> name_separator . $this -> generate_name('method') . $this -> name_separator . $this -> generate_name('title'), 2);

			$type = $type ? $type : mt_rand(1, 3);
			$this -> current_args = [];
			$this -> current_comment = '';

			if($type == 1){ // args mode
				$count = mt_rand(1, 4);

				for($i = 0; $i <= $count; $i ++){
					$this -> current_args[$this -> generate_name('type')] = $this -> generate_variable_default_value();
				}
			}
			else if($type == 2){ // arr mode
				$this -> current_args = [$this -> generate_name('type') => []];
			}
			else if($type == 3){ // obj mode
				$this -> current_args = [$this -> generate_name('type') => new stdClass()];
			}



			$body = $this -> generate_function_body();
			$args = $this -> generate_function_arguments();


			$code = '/*' . PHP_EOL . $this -> current_comment . PHP_EOL . '*/' . PHP_EOL . PHP_EOL . 'function ' . $name . '(' . $args . '){' . $body . PHP_EOL . '}';

			return $code;
		}
		public function generate_class_method(){
			
		}
		public function generate_class(){
			
		}
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
		public function generate(){
			$code = '
			<?
				/*
					generated by phpcodegen v' . $this -> version . '
				*/
			';

			$code .= $this -> generate_function();
			$code .= PHP_EOL . '?>';

			return $this -> code_print_format($code);
		}
	}


?>