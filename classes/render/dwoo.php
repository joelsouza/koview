<?php defined('SYSPATH') or die('No direct script access.');

class Render_Dwoo extends Render {

	public $extensions = array('dwoo');

	public static $dwoo;

	public static function render($vars=array(), $globals=array(), $file=FALSE, $options=array())
	{
		$dwoo = self::get_dwoo();
		
		$token = Kohana::$profiling ? Profiler::start('dwoo', 'rendering ' . basename($file)) : FALSE;
		
		$vars = $vars + $globals;
		
		$token ? Profiler::stop($token) : NULL;
		
		ob_start();
		$dwoo->output($file, $vars);
		return ob_get_clean();
	}

	public static function get_dwoo()
	{

		if (isset(self::$dwoo))
		{
			return self::$dwoo;
		}

		$token = Kohana::$profiling ? Profiler::start('dwoo', 'load dwoo') : FALSE;

		$config = Kohana::config('dwoo');

		try
		{
			include MODPATH.'koview/vendor/dwoo/lib/dwooAutoload.php';
		}
		catch (Exception $e)
		{
			throw new Kohana_Exception('Could not load Dwoo class file');
		}

		$dwoo = new Dwoo;
		
		foreach ($config->dwoo_config as $key => $value )
		{
			$dwoo->$key = $value;
		}
		
		if ( ! is_writeable($dwoo->compile_dir))
		{
			self::create_dir($dwoo->compile_dir, 'Dwoo compiled template');
		}
		
		$dwoo->setCompileDir($dwoo->compile_dir);
		
		self::$dwoo = $dwoo;

		$token ? Profiler::stop($token) : null;

		return $dwoo;
	}

	private static function create_dir($path, $name=NULL)
	{
		if (file_exists($path))
		{
			if (is_dir($path))
			{
				throw new Kohana_Exception('Could not write to :name directory to :path', array(
					'name' => $name,
					'path' => $path
				));
			}
			else
			{
				throw new Kohana_Exception(':name path is a file', array(
					'name' => $name
				));
			}
		}
		else
		{
			try
			{
				mkdir($path);
			}
			catch (Exception $e)
			{
				throw new Kohana_Exception('Could not create :name directory', array(
					'name' => $name
				));
			}

			if ( ! is_writeable($path))
			{
				throw new Kohana_Exception('Created :name directory but could not write to it', array(
					'name' => $name
				));
			}
		}
	}

} // End Render_Dwoo
