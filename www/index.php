<?
	include 'classes/code.php';

	include 'data/key_words.php';
	include 'data/default_values.php';
	include 'data/variables_relations.php';

	header('Content-type: text/css');
	$code = new code;

	echo $code -> generate();
?>