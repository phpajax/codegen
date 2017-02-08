<?
	$variables_relations = [ // content <- data
		'integer^integer' => [
			'{c} += {d}', '{c} -= {d}', '{c} *= {d}', '{c} /= {d}', '{c} &= {d}', '{c} ^= {d}', '{c} % {d}', '{c} = pow(2, {d})'
		],
		'integer^boolean' => ['{c} += {d}', '{c} = intval({d})'],
		'integer^string' => [
			'{c} = strlen({d})', '{c} = intval({d})', '{c} = array_sum(array_map(function($s){return ord($s);}, str_split({d})))', '{c} = ord({d})', '{c} = str_word_count({d})'
		],   
		'integer^array' => [
			'{c} = count({d})', '{c} = count(array_keys({d}))', '{c} = sum(array_values({d}))', '{c} = array_sum(array_map(function($s){return ord($s);}, {d}))'
		],
		'integer^object' => [
			'{c} = count(get_class_methods({d}))', '{c} = count(get_class_vars({d}))', '{c} = count(get_object_vars({d}))'
		],   

		'string^string' => [
			'{c} = addslashes({d})', '{c} = bin2hex({d})', '{c} = crc32({d})', '{c} = md5({d})', '{c} = sha1({d})', '{c} = htmlentities({d})', '{c} = trim({d})', '{c} = nl2br({d})', '{c} = metaphone({d})', '{c} = soundex({d})', '{c} = str_repeat({d}, 5)', '{c} = str_rot13({d})', '{c} = str_rot13({d})', '{c} = strcasecmp({c}, {d})', '{c} = str_ireplace({c}, {d})', '{c} = str_shuffle({d})', '{c} = strip_tags({d})', '{c} = stripcslashes({d})', '{c} = stripos({d}, {c})', '{c} = strrchr({d}, {c})', '{c} = strstr({d}, {c})', '{c} = strrev({d})', '{c} = strtolower({d})', '{c} = strtoupper({d})', '{c} = {c}({d})', '{c} = substr_count({d}, {c})', '{c} = wordwrap({d})'
		],
		'string^integer' => ['{c} = chr({d})', '{c} = number_format({d}, 2, ",", " ")'],  
		'string^boolean' => ['{c} = strval({d})', '{c} = {d} ? "yes" : "no"'],
		'string^array' => ['{c} = implode(",", {d})'],
		'string^object' => [
			'{c} = get_class({d})', '{c} = get_parent_class({d})', '{c} = var_export({d}, true)', '{c} = method_exists({d}, "name") ? {d} -> $name : -1'
		],

		'boolean^string' => ['{c} = empty({d})', '{c} = (bool) {d}'],
		'boolean^integer' => ['{c} = {d} > 1 ? true : false'],
		'boolean^boolean' => ['{c} = {d} && {c} ? {d} : {c}'],
		'boolean^array' => ['{c} = count({d} > 1 ? true : false)'],
		'boolean^object' => ['{c} = is_subclass_of({d}, "system")', '{c} = is_a({d}, "system")', '{c} = method_exists({d}, "name")'],

		'array^string' => [
			'{c} = chunk_split(base64_encode({d}))', '{c} = explode({d})', '{c} = str_split({d})', '{c} = parse_str({d})', '{c} = count_chars({d})'
		],
		'array^integer' => ['{c} = (array) {d}'],
		'array^boolean' => ['{c} = (array) {d}'],
		'array^array' => ['{c} += {d}', '{c} -= {d}', '{c} = {d} + {d}'],
		'array^object' => ['{c} = (array) {d}'],

		'object^string' => ['{c} = new {d}'],
		'object^integer' => ['{c} -> name = {d}', '{c} -> value += {d}'],
		'object^boolean' => ['{c} -> value = {d}', '{c} -> name = {d}'],
		'object^array' => ['{c} -> name = {d}["name"]', '{c} -> value = {d}["value"]'],
		'object^object' => ['{c} -> name = {d} -> name', '{c} -> value = {d} -> value'],
	];

	$variables_relations['double^double'] = $variables_relations['integer^integer'];
	$variables_relations['double^integer'] = $variables_relations['integer^integer'];
	$variables_relations['double^boolean'] = $variables_relations['integer^boolean'];
	$variables_relations['double^string'] = $variables_relations['integer^string'];
	$variables_relations['double^array'] = $variables_relations['integer^array'];
	$variables_relations['double^object'] = $variables_relations['integer^object'];

	$variables_relations['integer^double'] = $variables_relations['integer^integer'];
	$variables_relations['string^double'] = $variables_relations['string^integer'];
	$variables_relations['boolean^double'] = $variables_relations['boolean^integer'];
	$variables_relations['array^double'] = $variables_relations['array^integer'];
?>