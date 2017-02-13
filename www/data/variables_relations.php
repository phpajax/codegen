<?php
	function reverse($arr){
		return array_map(function($s){
			return str_replace(['{d}', '{e}', '{z}'], ['{z}', '{d}', '{e}'], $s);
		}, $arr);
	}


	$variables_relations = [];
	// content^data



	$variables_relations['integer^integer'] = ['{c} = {d} ++', '{c} = {d} --', '{c} = {d} @& {d}', '{c} = {d} @+ 8', '{c} = pow(2, {d})'];
	$variables_relations['integer^boolean'] = ['{c} = (int) !{d}', '{c} = intval({d})'];
	$variables_relations['integer^string'] = [
		'{c} = strlen({d})', '{c} = intval({d})', '{c} = array_sum(array_map(function($s){return ord($s);}, str_split({d})))', '{c} = ord({d}[0])', '{c} = str_word_count({d})'
	];
	$variables_relations['integer^array'] = ['{c} = count({d})', '{c} = count(array_keys({d}))', '{c} = array_sum(array_values({d}))', '{c} = array_sum(array_map(function($s){return ord($s);}, {d}))'];
	$variables_relations['integer^object'] = ['{c} = count(get_object_vars({d}))', '{c} = {d} @& 1'];


	$variables_relations['string^string'] = [
		'{c} = addslashes({d})', '{c} = bin2hex({d})', '{c} = crc32({d})', '{c} = md5({d})', '{c} = sha1({d})', '{c} = htmlentities({d})', '{c} = trim({d})', '{c} = nl2br({d})',
		'{c} = metaphone({d})', '{c} = soundex({d})', '{c} = str_repeat({d}, 5)', '{c} = str_rot13({d})', '{c} = str_rot13({d})', '{c} = strcasecmp("x", {d})', '{c} = str_ireplace("x", "y", {d})',
		'{c} = str_shuffle({d})', '{c} = strip_tags({d})', '{c} = stripcslashes({d})', '{c} = stripos({d}, "x")', '{c} = strrchr({d}, "x")', '{c} = strstr({d}, "x")', '{c} = strrev({d})',
		'{c} = strtolower({d})', '{c} = strtoupper({d})', '{c} = substr_count({d}, "x")', '{c} = wordwrap({d})', '{c} = chunk_split({d}, 8)'
	];
	$variables_relations['string^integer'] = ['{c} = chr({d})', '{c} = number_format({d}, 2, ",", " ")'];
	$variables_relations['string^boolean'] = ['{c} = strval({d})', '{c} = {d} ? "x" : "y"'];
	$variables_relations['string^array'] = ['{c} = implode(",", {d})', '{c} = strlen(count({d}))'];
	$variables_relations['string^object'] = ['{c} = get_class({d})', '{c} = get_parent_class({d})', '{c} = var_export({d}, true)', '{c} = method_exists({d}, "name") ? {d} -> $name : 0'];


	$variables_relations['boolean^string'] = ['{c} = empty({d})', '{c} = (bool) {d}', '{c} = !(bool) {d}'];
	$variables_relations['boolean^integer'] = ['{c} = {d} @> 1', '{c} = {d} @& 1', '{c} = {d} @+ 1'];
	$variables_relations['boolean^boolean'] =& $variables_relations['boolean^integer'];
	$variables_relations['boolean^array'] = ['{c} = count({d}) @> 1', '{c} = count({d}) @& 1'];
	$variables_relations['boolean^object'] = ['{c} = is_subclass_of({d}, "system")', '{c} = is_a({d}, "system")', '{c} = method_exists({d}, "name")'];


	$variables_relations['array^string'] = ['{c} = str_split(base64_encode({d}))', '{c} = explode(",", {d})', '{c} = str_split({d})', '{c} = parse_str({d})', '{c} = count_chars({d})'];
	$variables_relations['array^integer'] = ['{c} = (array) {d}', '{c} = range(1, {d})'];
	$variables_relations['array^boolean'] = ['{c} = (array) {d}', '{c} = {d} ? [0] : [1]'];
	$variables_relations['array^array'] = ['{c} += {d}', '{c} = {d} @+ {d}', '{c} = {d} @& {d}'];
	$variables_relations['array^object'] = ['{c} = (array) {d}', '{c} = {d} -> $data'];


	$variables_relations['object^string'] = ['{c} = new {d}', '{c} = (object) {d}'];
	$variables_relations['object^integer'] = ['{c} = get_result({d})', '{c} = new ${"obj_" . {d}}'];
	$variables_relations['object^boolean'] = ['{c} = {d} ? new stdClass() : $this', '{c} = (object) {d}'];
	$variables_relations['object^array'] = ['{c} = (object) {d}', '{c} = {d}["value"]'];
	$variables_relations['object^object'] = ['{c} = {d} -> $parent', '{c} = {d} -> $value'];


	$variables_relations['double^double'] =& $variables_relations['integer^integer'];
	$variables_relations['double^integer'] =& $variables_relations['integer^integer'];
	$variables_relations['double^boolean'] =& $variables_relations['integer^boolean'];
	$variables_relations['double^string'] =& $variables_relations['integer^string'];
	$variables_relations['double^array'] =& $variables_relations['integer^array'];
	$variables_relations['double^object'] =& $variables_relations['integer^object'];


	$variables_relations['integer^double'] =& $variables_relations['integer^integer'];
	$variables_relations['string^double'] =& $variables_relations['string^integer'];
	$variables_relations['boolean^double'] =& $variables_relations['boolean^integer'];
	$variables_relations['array^double'] =& $variables_relations['array^integer'];
	$variables_relations['object^double'] =& $variables_relations['object^integer'];





	// content^data^e-data
	$variables_relations['boolean^boolean^boolean'] = ['{c} = {d} @& {e}', '{c} = {d} @+ {e}'];
	$variables_relations['boolean^boolean^integer'] =& $variables_relations['boolean^boolean^boolean'];
	$variables_relations['boolean^boolean^double'] =& $variables_relations['boolean^boolean^integer'];
	$variables_relations['boolean^boolean^array'] = ['{c} = {e}[{d}] ? {d} : false', '{c} = {e}[({d} ? 1 : 0)] ? {d} : false'];
	$variables_relations['boolean^boolean^object'] = ['{c} = {e} ? true : {d}', '{c} = {e} ? {d} : false', '{c} = {e} -> $value == {d}', '{c} = {e} -> $value != {d}'];
	$variables_relations['boolean^boolean^string'] = ['{c} = {e} ? {d} : false', '{c} = strlen({e}) @> 8 ? true : {d}', '{c} = {e} @> {d}'];
	$variables_relations['boolean^integer^boolean'] = ['{c} = !{d} ? true : {e}', '{c} = {d} ? {e} : false'];
	$variables_relations['boolean^integer^integer'] = ['{c} = {d} @+ {e} ? true : false', '{c} = {d} @> {e} ? false : true'];
	$variables_relations['boolean^integer^double'] =& $variables_relations['boolean^integer^integer'];
	$variables_relations['boolean^integer^array'] = ['{c} = count({e}) @> {d}', '{c} = strlen(count({e})) @> {d}'];
	$variables_relations['boolean^integer^object'] = ['{c} = count(get_object_vars({e})) @> {d}', '{c} = count(get_object_vars({e})) @> {d}'];
	$variables_relations['boolean^integer^string'] = ['{c} = strlen({e}) == {d}', '{c} = str_word_count({e}) == {d}', '{c} = strlen({e}) == {d}', '{c} = (bool) {e}[{d}]'];
	$variables_relations['boolean^double^boolean'] =& $variables_relations['boolean^integer^boolean'];
	$variables_relations['boolean^double^integer'] =& $variables_relations['boolean^integer^integer'];
	$variables_relations['boolean^double^double'] =& $variables_relations['boolean^integer^integer'];
	$variables_relations['boolean^double^array'] =& $variables_relations['boolean^integer^array'];
	$variables_relations['boolean^double^object'] =& $variables_relations['boolean^integer^object'];
	$variables_relations['boolean^double^string'] =& $variables_relations['boolean^integer^string'];
	$variables_relations['boolean^array^boolean'] = ['{c} = count({d}) @> 8 ? {e} : false', '{c} = count({d}) @> 2 ? true : {e}'];
	$variables_relations['boolean^array^integer'] = reverse($variables_relations['boolean^integer^array']);
	$variables_relations['boolean^array^double'] =& $variables_relations['boolean^array^integer'];
	$variables_relations['boolean^array^array'] = ['{c} = {d} @> {e}', '{c} = count({d}) @> count({e})', '{c} = count({d}) @> count({e})'];
	$variables_relations['boolean^array^object'] = ['{c} = count({d}) @> count(get_object_vars({e}))', '{c} = get_object_vars({e}) @> {d}', '{c} = {e} -> $data @> {d}'];
	$variables_relations['boolean^array^string'] = ['{c} = array_key_exists({e}, {d})', '{c} = (bool) array_search({e}, {d})', '{c} = in_array({e}, {d})'];
	$variables_relations['boolean^object^boolean'] = reverse($variables_relations['boolean^boolean^object']);
	$variables_relations['boolean^object^integer'] = reverse($variables_relations['boolean^integer^object']);
	$variables_relations['boolean^object^double'] =& $variables_relations['boolean^object^integer'];
	$variables_relations['boolean^object^array'] = reverse($variables_relations['boolean^array^object']);
	$variables_relations['boolean^object^object'] = ['{c} = {d} @> {e}', '{c} = {d} -> $name == {e} -> $name'];
	$variables_relations['boolean^object^string'] = ['{c} = is_subclass_of({d}, {e})', '{c} = is_a({d}, {e})', '{c} = method_exists({d}, {e})'];
	$variables_relations['boolean^string^boolean'] = reverse($variables_relations['boolean^boolean^string']);
	$variables_relations['boolean^string^integer'] = reverse($variables_relations['boolean^integer^string']);
	$variables_relations['boolean^string^double'] =& $variables_relations['boolean^string^integer'];
	$variables_relations['boolean^string^array'] = reverse($variables_relations['boolean^array^string']);
	$variables_relations['boolean^string^object'] = reverse($variables_relations['boolean^object^string']);
	$variables_relations['boolean^string^string'] = ['{c} = {d} @> {e}', '{c} = strlen({d}) @> strlen({e})', '{c} = src32({d}) @> src32({e})'];



	$variables_relations['integer^boolean^boolean'] = ['{c} = {d} @& {e} ? 47 : -1', '{c} = {d} @> {e} ? 1 : 0'];
	$variables_relations['integer^boolean^integer'] = ['{c} = {d} ? {e} : 1', '{c} = {d} @& {e}', '{c} = (int) {d} @> {e}'];
	$variables_relations['integer^boolean^double'] =& $variables_relations['integer^boolean^integer'];
	$variables_relations['integer^boolean^array'] = ['{c} = in_array({d}, {e}) ? 1 : 0', '{c} = {d} ? count({e}) : 0', '{c} = {d} @& {e}'];
	$variables_relations['integer^boolean^object'] = ['{c} = (int) {d} @& {e}', '{c} = (int) {d} @+ {e}'];
	$variables_relations['integer^boolean^string'] = ['{c} = {d} ? strlen({e}) : 0', '{c} = {d} @> {e} ? 1 : 0'];
	$variables_relations['integer^integer^boolean'] = reverse($variables_relations['integer^boolean^integer']);
	$variables_relations['integer^integer^integer'] = ['{c} = {d} @+ {e}', '{c} = {d} @& {e}', '{c} = pow({d}, {e})'];
	$variables_relations['integer^integer^double'] =& $variables_relations['integer^integer^integer'];
	$variables_relations['integer^integer^array'] = ['{c} = {d} @+ count({e})', '{c} = {d} @+ array_sum({e})', '{c} = {d} @+ count({e}) + array_sum({e})'];
	$variables_relations['integer^integer^object'] = ['{c} = {d} @+ count(get_object_vars({e}))', '{c} = {d} @& count(get_object_vars({e}))'];
	$variables_relations['integer^integer^string'] = ['{c} = strlen({d}) @+ {e}', '{c} = intval({e}) @+ {d}', '{c} = array_sum(array_map(function($s){return ord($s);}, str_split({e}))) @+ {d}', '{c} = ord({e}) @+ {d}', '{c} = str_word_count({e}) @+ {d}'];
	$variables_relations['integer^double^boolean'] = reverse($variables_relations['integer^boolean^double']);
	$variables_relations['integer^double^integer'] =& $variables_relations['integer^integer^integer'];
	$variables_relations['integer^double^double'] =& $variables_relations['integer^integer^integer'];
	$variables_relations['integer^double^array'] =& $variables_relations['integer^integer^array'];
	$variables_relations['integer^double^object'] =& $variables_relations['integer^integer^object'];
	$variables_relations['integer^double^string'] =& $variables_relations['integer^integer^string'];
	$variables_relations['integer^array^boolean'] = reverse($variables_relations['integer^boolean^array']);
	$variables_relations['integer^array^integer'] = reverse($variables_relations['integer^integer^array']);
	$variables_relations['integer^array^double'] =& $variables_relations['integer^array^integer'];
	$variables_relations['integer^array^array'] = ['{c} = count({d}) @+ count({e})', '{c} = array_sum({d}) @+ array_sum({e})', '{c} = array_sum(array_keys({d})) @+ array_sum(array_keys({e}))'];
	$variables_relations['integer^array^object'] = ['{c} = array_sum({d}) @+ array_sum((array) {e})', '{c} = count({d}) @+ count((array) {e})'];
	$variables_relations['integer^array^string'] = [
		'{c} = count({d}) @+ count(str_split(base64_encode({e})))', '{c} = count({d}) @+ count(explode(",", {e}))', '{c} = count({d}) @+ strlen({e})', '{c} = count({d}) @+ count(parse_str({e}))', '{c} = count({d}) @+ count_chars({e})'
	];
	$variables_relations['integer^object^boolean'] = reverse($variables_relations['integer^boolean^object']);
	$variables_relations['integer^object^integer'] = ['{c} = count(get_object_vars({d})) @+ {e}'];
	$variables_relations['integer^object^double'] =& $variables_relations['integer^object^integer'];
	$variables_relations['integer^object^array'] = reverse($variables_relations['integer^array^object']);
	$variables_relations['integer^object^object'] = ['{c} = count(get_object_vars({d})) @+ count(get_object_vars({e}))'];
	$variables_relations['integer^object^string'] = ['{c} = crc32(get_class({d}) . {e})', '{c} = strlen(get_parent_class({d}) . {e})', '{c} = strlen(md5({e}) . sha1(get_object_vars({d}))'];
	$variables_relations['integer^string^boolean'] = reverse($variables_relations['integer^boolean^string']);
	$variables_relations['integer^string^integer'] = reverse($variables_relations['integer^integer^string']);
	$variables_relations['integer^string^double'] =& $variables_relations['integer^string^integer'];
	$variables_relations['integer^string^array'] = reverse($variables_relations['integer^array^string']);
	$variables_relations['integer^string^object'] = reverse($variables_relations['integer^object^string']);
	$variables_relations['integer^string^string'] = [
		'{c} = crc32({d}) @+ crc32({e})', '{c} = strlen({d}) @+ strlen({e})', '{c} = intval({d}) @+ intval({e})', '{c} = array_sum(array_map(function($s){return ord($s);}, str_split({d}))) @+ array_sum(array_map(function($s){return ord($s);}, str_split({e})))', '{c} = ord({d}[0]) @+ ord({e}[0])', '{c} = str_word_count({d}) @+ str_word_count({e})'
	];



	$variables_relations['double^boolean^boolean'] =& $variables_relations['integer^boolean^boolean'];
	$variables_relations['double^boolean^integer'] =& $variables_relations['integer^boolean^integer'];
	$variables_relations['double^boolean^double'] =& $variables_relations['integer^boolean^integer'];
	$variables_relations['double^boolean^array'] =& $variables_relations['integer^boolean^array'];
	$variables_relations['double^boolean^object'] =& $variables_relations['integer^boolean^object'];
	$variables_relations['double^boolean^string'] =& $variables_relations['integer^boolean^string'];
	$variables_relations['double^integer^boolean'] =& $variables_relations['integer^integer^boolean'];
	$variables_relations['double^integer^integer'] =& $variables_relations['integer^integer^integer'];
	$variables_relations['double^integer^double'] =& $variables_relations['integer^integer^integer'];
	$variables_relations['double^integer^array'] =& $variables_relations['integer^integer^array'];
	$variables_relations['double^integer^object'] =& $variables_relations['integer^integer^object'];
	$variables_relations['double^integer^string'] =& $variables_relations['integer^integer^string'];
	$variables_relations['double^double^boolean'] =& $variables_relations['integer^integer^boolean'];
	$variables_relations['double^double^integer'] =& $variables_relations['integer^integer^integer'];
	$variables_relations['double^double^double'] =& $variables_relations['integer^integer^integer'];
	$variables_relations['double^double^array'] =& $variables_relations['integer^integer^array'];
	$variables_relations['double^double^object'] =& $variables_relations['integer^integer^object'];
	$variables_relations['double^double^string'] =& $variables_relations['integer^integer^string'];
	$variables_relations['double^array^boolean'] =& $variables_relations['integer^array^boolean'];
	$variables_relations['double^array^integer'] =& $variables_relations['integer^array^integer'];
	$variables_relations['double^array^double'] =& $variables_relations['integer^array^integer'];
	$variables_relations['double^array^array'] =& $variables_relations['integer^array^array'];
	$variables_relations['double^array^object'] =& $variables_relations['integer^array^object'];
	$variables_relations['double^array^string'] =& $variables_relations['integer^array^string'];
	$variables_relations['double^object^boolean'] =& $variables_relations['integer^object^boolean'];
	$variables_relations['double^object^integer'] =& $variables_relations['integer^object^integer'];
	$variables_relations['double^object^double'] =& $variables_relations['integer^object^integer'];
	$variables_relations['double^object^array'] =& $variables_relations['integer^object^array'];
	$variables_relations['double^object^object'] =& $variables_relations['integer^object^object'];
	$variables_relations['double^object^string'] =& $variables_relations['integer^object^string'];
	$variables_relations['double^string^boolean'] =& $variables_relations['integer^string^boolean'];
	$variables_relations['double^string^integer'] =& $variables_relations['integer^string^integer'];
	$variables_relations['double^string^double'] =& $variables_relations['integer^string^integer'];
	$variables_relations['double^string^array'] =& $variables_relations['integer^string^array'];
	$variables_relations['double^string^object'] =& $variables_relations['integer^string^object'];
	$variables_relations['double^string^string'] =& $variables_relations['integer^string^string'];



	$variables_relations['array^boolean^boolean'] = ['{c} = [{d}, {e}]', '{c} = {d} && {e} ? [1] : [0]'];
	$variables_relations['array^boolean^integer'] = ['{c} = [{d}, {e}]', '{c} = {d} ? [{e}] : [{d}]', '{c} = (array) {d} @& {e}'];
	$variables_relations['array^boolean^double'] =& $variables_relations['array^boolean^integer'];
	$variables_relations['array^boolean^array'] = ['{c} = array_push({e}, {d})', '{c} = {d} ? array_reverse({e}) : {e}'];
	$variables_relations['array^boolean^object'] = ['{c} = (array) {d} @+ {array} {e}', '{c} = {d} ? {array} {e} : [0]'];
	$variables_relations['array^boolean^string'] = ['{c} = implode({d}, str_split({e}));', '{c} = array_sum(array_map(function($s){return ord($s);}, {e}) @& {d}', '{c} = [{d}, {e}]', '{c} = (array) ({d} @+ {e})'];
	$variables_relations['array^integer^boolean'] = reverse($variables_relations['array^boolean^integer']);
	$variables_relations['array^integer^integer'] = ['{c} = range({d}, {e})', '{c} = range({d} @+ {e})', '{c} = [{d}, {e}]'];
	$variables_relations['array^integer^double'] =& $variables_relations['array^integer^integer'];
	$variables_relations['array^integer^array'] = ['{c} = array_rand({e}) @& {d}', '{c} = array_slice({e}, {d})', '{c} = {e}[{d}]', '{c} = array_chunk({e}, {d})'];
	$variables_relations['array^integer^object'] = ['{c} = {d} > 8 (array) {e} : [0]'];
	$variables_relations['array^integer^string'] = ['{c} = [{d}, {e}]', '{c} = explode({d}, {e})', '{c} = str_split({e}, {d})', '{c} = (array) {d} @+ strlen({e})'];
	$variables_relations['array^double^boolean'] =& $variables_relations['array^integer^boolean'];
	$variables_relations['array^double^integer'] =& $variables_relations['array^integer^integer'];
	$variables_relations['array^double^double'] =& $variables_relations['array^integer^integer'];
	$variables_relations['array^double^array'] =& $variables_relations['array^integer^array'];
	$variables_relations['array^double^object'] =& $variables_relations['array^integer^object'];
	$variables_relations['array^double^string'] =& $variables_relations['array^integer^string'];
	$variables_relations['array^array^boolean'] = reverse($variables_relations['array^boolean^array']);
	$variables_relations['array^array^integer'] = reverse($variables_relations['array^integer^array']);
	$variables_relations['array^array^double'] =& $variables_relations['array^array^integer'];
	$variables_relations['array^array^array'] = ['{c} = {d} @+ {e}', '{c} = array_diff({d} @+ {e})'];
	$variables_relations['array^array^object'] = ['{c} = {d} @+ (array) {e}'];
	$variables_relations['array^array^string'] = ['{c} = array_push({d}, {e})', '{c} = {d} @+ str_split({e})', '{c} = {d}[{e}]'];
	$variables_relations['array^object^boolean'] = reverse($variables_relations['array^boolean^object']);
	$variables_relations['array^object^integer'] = reverse($variables_relations['array^integer^object']);
	$variables_relations['array^object^double'] =& $variables_relations['array^object^integer'];
	$variables_relations['array^object^array'] = reverse($variables_relations['array^array^object']);
	$variables_relations['array^object^object'] = ['{c} = (array) {d} @+ (array) {e}', '{c} = [{d}, {e}]'];
	$variables_relations['array^object^string'] = ['{c} = (array) {d} -> {e}', '{c} = [{d}, {e}]'];
	$variables_relations['array^string^boolean'] = reverse($variables_relations['array^boolean^string']);
	$variables_relations['array^string^integer'] = reverse($variables_relations['array^integer^string']);
	$variables_relations['array^string^double'] =& $variables_relations['array^string^integer'];
	$variables_relations['array^string^array'] = reverse($variables_relations['array^array^string']);
	$variables_relations['array^string^object'] =& $variables_relations['array^object^string'];
	$variables_relations['array^string^string'] = ['{c} = [{d}, {e}]', '{c} = [{d} @+ {e}]'];



	$variables_relations['object^boolean^boolean'] = ['{c} = (object) ({d} @& {e})'];
	$variables_relations['object^boolean^integer'] = ['{c} = (object) ({d} @& {e})'];
	$variables_relations['object^boolean^double'] =& $variables_relations['object^boolean^integer'];
	$variables_relations['object^boolean^array'] = ['{c} = (object) ({d} @& {e})'];
	$variables_relations['object^boolean^object'] = ['{c} = (object) ({d} @& {e})', '{c} = {e} -> {d} = {e}'];
	$variables_relations['object^boolean^string'] = ['{c} = (object) ({d} @& {e})'];
	$variables_relations['object^integer^boolean'] = ['{c} = (object) ({d} @& {e})'];
	$variables_relations['object^integer^integer'] = ['{c} = (object) ({d} @+ {e})'];
	$variables_relations['object^integer^double'] =& $variables_relations['object^integer^integer'];
	$variables_relations['object^integer^array'] = ['{c} = new {e}[{d}]', '{c} = (object) {e}[{d}]'];
	$variables_relations['object^integer^object'] = ['{c} = (object) ({d} @+ {e})', '{c} = {e} -> {d} = {e}'];
	$variables_relations['object^integer^string'] = ['{c} = (object) ({d} @+ {e})'];
	$variables_relations['object^double^boolean'] =& $variables_relations['object^integer^boolean'];
	$variables_relations['object^double^integer'] =& $variables_relations['object^integer^integer'];
	$variables_relations['object^double^double'] =& $variables_relations['object^integer^integer'];
	$variables_relations['object^double^array'] =& $variables_relations['object^integer^array'];
	$variables_relations['object^double^object'] =& $variables_relations['object^integer^object'];
	$variables_relations['object^double^string'] =& $variables_relations['object^integer^string'];
	$variables_relations['object^array^boolean'] = reverse($variables_relations['object^boolean^array']);
	$variables_relations['object^array^integer'] = reverse($variables_relations['object^integer^array']);
	$variables_relations['object^array^double'] =& $variables_relations['object^array^integer'];
	$variables_relations['object^array^array'] = ['{c} = (object) ({d} @+ {e})'];
	$variables_relations['object^array^object'] = ['{c} = (object) [{d}, {e}]'];
	$variables_relations['object^array^string'] = ['{c} = (object) {d} @+ {e}'];
	$variables_relations['object^object^boolean'] = reverse($variables_relations['object^boolean^object']);
	$variables_relations['object^object^integer'] = reverse($variables_relations['object^integer^object']);
	$variables_relations['object^object^double'] =& $variables_relations['object^object^integer'];
	$variables_relations['object^object^array'] = reverse($variables_relations['object^array^object']);
	$variables_relations['object^object^object'] = ['{c} = (object) [{d}, {e}]'];
	$variables_relations['object^object^string'] = ['{c} = (object) [{d}, {e}]'];
	$variables_relations['object^string^boolean'] = reverse($variables_relations['object^boolean^string']);
	$variables_relations['object^string^integer'] = reverse($variables_relations['object^integer^string']);
	$variables_relations['object^string^double'] =& $variables_relations['object^string^integer'];
	$variables_relations['object^string^array'] = reverse($variables_relations['object^array^string']);
	$variables_relations['object^string^object'] = reverse($variables_relations['object^object^string']);
	$variables_relations['object^string^string'] = ['{c} = (object) [{d}, {e}]'];



	$variables_relations['string^boolean^boolean'] = ['{c} = (string) ({d} @& {e})'];
	$variables_relations['string^boolean^integer'] = ['{c} = (string) ({d} @& {e})'];
	$variables_relations['string^boolean^double'] =& $variables_relations['string^boolean^integer'];
	$variables_relations['string^boolean^array'] = ['{c} = {e}[{d}]', '{c} = (string) ({d} @& {e})', '{c} = implode({d}, {e})'];
	$variables_relations['string^boolean^object'] = ['{c} = (string) ({d} @& {e})'];
	$variables_relations['string^boolean^string'] = ['{c} = (string) ({d} @& {e})'];
	$variables_relations['string^integer^boolean'] = ['{c} = (string) ({d} @& {e})'];
	$variables_relations['string^integer^integer'] = ['{c} = (string) ({d} @+ {e})', '{c} = (string) pow({d}, {e})'];
	$variables_relations['string^integer^double'] =& $variables_relations['string^integer^integer'];
	$variables_relations['string^integer^array'] = ['{c} = (string) ({d} @& {e})'];
	$variables_relations['string^integer^object'] = ['{c} = {e} -> {d}', '{c} = (string) {d} @& {e}', '{c} = var_export({d}, true) . var_export({e}, true)'];
	$variables_relations['string^integer^string'] = ['{c} = {d} @+ {e}', '{c} = str_repeat({e}, {d})'];
	$variables_relations['string^double^boolean'] =& $variables_relations['string^integer^boolean'];
	$variables_relations['string^double^integer'] =& $variables_relations['string^integer^string'];
	$variables_relations['string^double^double'] =& $variables_relations['string^integer^double'];
	$variables_relations['string^double^array'] =& $variables_relations['string^integer^array'];
	$variables_relations['string^double^object'] =& $variables_relations['string^integer^object'];
	$variables_relations['string^double^string'] =& $variables_relations['string^integer^string'];
	$variables_relations['string^array^boolean'] = reverse($variables_relations['string^boolean^array']);
	$variables_relations['string^array^integer'] = reverse($variables_relations['string^integer^array']);
	$variables_relations['string^array^double'] =& $variables_relations['string^array^integer'];
	$variables_relations['string^array^array'] = ['{c} = implode("", {d}) . implode("", {e})'];
	$variables_relations['string^array^object'] = ['{c} = implode("", {d}) . implode("", (array) {e})'];
	$variables_relations['string^array^string'] = ['{c} = implode("", {d}) . {e}'];
	$variables_relations['string^object^boolean'] = reverse($variables_relations['string^boolean^object']);
	$variables_relations['string^object^integer'] = reverse($variables_relations['string^integer^object']);
	$variables_relations['string^object^double'] =& $variables_relations['string^object^integer'];
	$variables_relations['string^object^array'] = reverse($variables_relations['string^array^object']);
	$variables_relations['string^object^object'] = ['{c} = implode("", (array) {d}) . implode("", (array) {e})'];
	$variables_relations['string^object^string'] = ['{c} = implode("", (array) {d}) . {e}'];
	$variables_relations['string^string^boolean'] = reverse($variables_relations['string^boolean^string']);
	$variables_relations['string^string^integer'] = reverse($variables_relations['string^integer^string']);
	$variables_relations['string^string^double'] =& $variables_relations['string^string^integer'];
	$variables_relations['string^string^array'] = reverse($variables_relations['string^array^string']);
	$variables_relations['string^string^object'] = reverse($variables_relations['string^object^string']);
	$variables_relations['string^string^string'] = ['{c} = {d} . {e}', '{c} = str_replace({d}, "", {e})'];
?>