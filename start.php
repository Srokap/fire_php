<?php
require_once(dirname(__FILE__) . '/vendors/FirePHPCore/FirePHP.class.php');
require_once(dirname(__FILE__) . '/vendors/FirePHPCore/fb.php');

function fire_php_error_handler($errno, $errmsg, $filename, $linenum, $vars) {
	$fp = FirePHP::getInstance();
	$fp->errorHandler($errno, $errmsg, $filename, $linenum, $vars);
	return  _elgg_php_error_handler($errno, $errmsg, $filename, $linenum, $vars);
}

function fire_php_exception_handler($exception) {
	$fp = FirePHP::getInstance();
	$fp->exceptionHandler($exception);
	return _elgg_php_exception_handler($exception);
}

//remove Elgg core handlers
restore_error_handler();
restore_exception_handler();

//initialize FirePHP
FirePHP::init();
$fp = FirePHP::getInstance();
$fp->registerErrorHandler();
$fp->registerExceptionHandler();

//remove FirePHP handlers
restore_error_handler();
restore_exception_handler();

//register plugin final handlers
set_error_handler('fire_php_error_handler');
set_exception_handler('fire_php_exception_handler');
