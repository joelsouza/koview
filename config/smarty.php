<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
	'php_handling' => 'SMARTY_PHP_QUOTE',
	'smarty_config' => array(
		'compile_dir' => Kohana::$cache_dir . '/render/smarty',
		'cache_dir' => Kohana::$cache_dir . '/render/smarty',
		'template_dir' => array(
			APPPATH.'views',
		),
		'plugins_dir' => array(
			APPPATH.'plugins/smarty',
			MODPATH.'plugins/smarty',
			'plugins',
		),
		'config_dir' => APPPATH.'render/config',
		'compile_check' => TRUE,
		'error_reporting' => error_reporting(),
		'debugging' => FALSE,
		'debugging_ctrl' => 'NONE',
		'caching' => 0,
		'security' => FALSE,
		'left_delimiter' => '{',
		'right_delimiter' => '}',
		'use_sub_dirs' => FALSE,
		'default_modifiers' => array(),
	)
);