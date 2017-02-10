<?php
	error_reporting(E_ALL);
	include 'classes/codegen.php';

	include 'data/key_words.php';
	include 'data/default_values.php';
	include 'data/variables_relations.php';

	header('Content-type: text/css');

	$codegen = new codegen;
	echo $codegen -> generate();
?>