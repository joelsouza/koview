<?php defined('SYSPATH') or die('No direct script access.');

class Render_Mustache extends Render {

	public $extensions = array('mustache');

	public static $mustache;

	public static function render($vars=array(), $globals=array(), $file=FALSE, $options=array())
	{
		$mustache = self::get_mustache();

		$token = Kohana::$profiling ? Profiler::start('mustache', 'rendering ' . basename($file)) : FALSE;

		$vars = $vars + $globals;
		
		$f = fopen($file, 'r');
		$file = fread($f, filesize($file));
		fclose($f);
		
		$result = $mustache->render($file, $vars);

		$token ? Profiler::stop($token) : NULL;

		return $result;
	}
	
	public static function get_mustache()
	{

		if (isset(self::$mustache))
		{
			return self::$mustache;
		}

		$token = Kohana::$profiling ? Profiler::start('smarty', 'load smarty') : FALSE;

		$config = Kohana::config('mustache');

		try
		{
			include MODPATH.'koview/vendor/mustache/Mustache.php';
		}
		catch (Exception $e)
		{
			throw new Kohana_Exception('Could not load Mustache class file');
		}

		$mustache = new Mustache;

		self::$mustache = $mustache;

		$token ? Profiler::stop($token) : null;

		return $mustache;
	}

} // End Render_Mustache
