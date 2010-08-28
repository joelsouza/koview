<?php defined('SYSPATH') or die('No direct script access.');

class Render_Sass extends Render {

	public $extensions = array('sass');

	public static $sass;

	public static function render($vars=array(), $globals=array(), $file=FALSE, $options=array())
	{
		$sass = self::get_sass();

		$token = Kohana::$profiling ? Profiler::start('sass', 'rendering ' . basename($file)) : FALSE;

		$vars = $vars + $globals;
		
		$token ? Profiler::stop($token) : NULL;

		ob_start();
		$sass->compile(file($file));
		return ob_get_clean();
	}

	public static function get_sass()
	{

		if (isset(self::$sass))
		{
			return self::$sass;
		}

		$token = Kohana::$profiling ? Profiler::start('sass', 'load sass') : FALSE;

		$config = Kohana::config('sass');

		try
		{
			include MODPATH.'koview/vendor/sass/Sass.php';
		}
		catch (Exception $e)
		{
			throw new Kohana_Exception('Could not load Sass class file');
		}

		$sass = new Sass;
		$sass->style = 'oneliner';
		
		self::$sass = $sass;

		$token ? Profiler::stop($token) : null;

		return $sass;
	}

} // End Render_Sass
