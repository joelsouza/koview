<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'php_handling' => 'SMARTY_PHP_QUOTE',
	'dwoo_config' => array(
		'compile_dir' => Kohana::$cache_dir . '/render/dwoo',
		'cache_dir' => Kohana::$cache_dir . '/render/dwoo'
	)
);