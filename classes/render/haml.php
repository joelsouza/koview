<?php defined('SYSPATH') or die('No direct script access.');

class Render_Haml extends Render {

	public $extensions = array('haml');

	public static $haml;

	public static function render($vars=array(), $globals=array(), $file=FALSE, $options=array())
	{
		$haml = self::get_haml();

		$token = Kohana::$profiling ? Profiler::start('haml', 'rendering ' . basename($file)) : FALSE;

		$vars = $vars + $globals;
		
		$file = $haml->parse($file, APPPATH.'cache/render/haml');
		
		$result = file_get_contents($file);
		
		$token ? Profiler::stop($token) : NULL;

		return $result;
	}

	public static function get_haml()
	{

		if (isset(self::$haml))
		{
			return self::$haml;
		}

		$token = Kohana::$profiling ? Profiler::start('haml', 'load haml') : FALSE;

		$config = Kohana::config('haml');

		try
		{
			include MODPATH.'koview/vendor/haml/HamlParser.php';
		}
		catch (Exception $e)
		{
			throw new Kohana_Exception('Could not load Haml class file');
		}

		$haml = new HamlParser($config);

		self::$haml = $haml;

		$token ? Profiler::stop($token) : null;

		return $haml;
	}

} // End Render_Haml
