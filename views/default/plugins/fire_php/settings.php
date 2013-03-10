<?php
$options = ElggFirePHP::getInstance()->getDefaultOptions();

foreach ($options as $name => $value) {
	echo '<p>';
	echo '<label>' . elgg_echo('fire_php:settings:'.$name) . '</label> ';
	
	if (in_array($name, array('useNativeJsonEncode', 'includeLineNumbers'))) {
		echo elgg_view('input/dropdown', array(
			'name' => "params[$name]",
			'options_values' => array(
				0 => elgg_echo('option:no'),
				1 => elgg_echo('option:yes'),
			),
			'value' => (int)$value,
		));
	} else {
		echo elgg_view('input/text', array(
			'name' => "params[$name]",
			'value' => $value,
		));
	}
	echo '</p>';
}
