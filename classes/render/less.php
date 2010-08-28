<?php defined('SYSPATH') or die('No direct script access.');

class Render_Less extends Render {

	public $extensions = array('less');

	public static $less;

	public static function render($vars=array(), $globals=array(), $file=FALSE, $options=array())
	{
		$less = self::get_less();

		$token = Kohana::$profiling ? Profiler::start('less', 'rendering ' . basename($file)) : FALSE;

		$vars = $vars + $globals;
		
		$token ? Profiler::stop($token) : NULL;

		ob_start();
		echo $less->parse(file_get_contents($file));
		return ob_get_clean();
	}

	public static function get_less()
	{

		if (isset(self::$less))
		{
			return self::$less;
		}

		$token = Kohana::$profiling ? Profiler::start('less', 'load less') : FALSE;

		$config = Kohana::config('less');

		try
		{
			include MODPATH.'koview/vendor/less/lessc.inc.php';
		}
		catch (Exception $e)
		{
			throw new Kohana_Exception('Could not load Less class file');
		}

		$less = new lessc;
		
		self::$less = $less;

		$token ? Profiler::stop($token) : null;

		return $less;
	}

} // End Render_Less
