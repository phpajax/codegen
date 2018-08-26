<?php
	$default_values = [
		'string' => ['', '?', '*', '!!!', 'default', 'nothing', 'not found', '300', 'test', 'hello world', 'example', 'my string', 'str', 'email@site.com', 'some', 'info', 'data string', 'hi there'],
		'integer' => [0, -1, 100, -0, 300, 42, 84, 64, 8, 512, 200, 888, 1024, 777, 4422, 544, 755, 655, 444, 820, 15, 2],
		'double' => ['0.0', '-0.0', 300, 3.14, 1.24, 0.362, 8.1, 2,62, 7,654, 9,22, 11,5, 126,7, 77,43, 824,5],
		'boolean' => ['false', 'true'],
		'array' => ['[]', '[0,2,4,6,8]', '[2,4,5]', '[1,2,3]', '["key" => 0, "value" => 0]', '["data" => []]', '["apple", "babanas", "cocos"]'],
		'object' => ['null', '(object)[]', '(object) ["property" => "some prop"]']
		// 'new stdClass()', 'new ArrayObject', 'new ArrayObject()', 'new stdClass', 'new class{}',
	];
?>