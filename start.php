<?php
elgg_register_class('FirePHP', dirname(__FILE__) . '/vendors/FirePHPCore/FirePHP.class.php');
elgg_register_class('FB', dirname(__FILE__) . '/vendors/FirePHPCore/fb.php');

function fire_php_boot() {
	ElggFirePHP::init();
	$fp = ElggFirePHP::getInstance();
	$fp->registerHandlers();
}

fire_php_boot();

// function fire_php_shutdown_hook() {
// 	global $START_MICROTIME;
// 	FB::info(microtime(true) - $START_MICROTIME, 'Execution time [s]');
// }

// elgg_register_event_handler('shutdown', 'system', 'fire_php_shutdown_hook', 1000);