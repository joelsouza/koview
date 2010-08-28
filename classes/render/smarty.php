<?php defined('SYSPATH') or die('No direct script access.');

class Render_Smarty extends Render {

	public $extensions = array('tpl');

	public static $smarty;
	
	public static function render($vars=array(), $globals=array(), $file=FALSE, $options=array())
	{
		$smarty = self::get_smarty();
		
		$token = Kohana::$profiling ? Profiler::start('smarty', 'rendering ' . basename($file)) : FALSE;
	
		// save _tpl_vars in case we have a render within a render
		// - can be caused by variables which are objects with __toString methods
		$old_tpl_vars = $smarty->_tpl_vars;
	
		$smarty->_tpl_vars = $vars + $globals;
	
		// debugging pop-up code removed - too much pain for too little gain
		$result = $smarty->fetch($file);
	
		$smarty->_tpl_vars = $old_tpl_vars;
	
		$token ? Profiler::stop($token) : NULL;
		
		return $result;
	}
	
	public static function get_smarty()
	{
	
		if (isset(self::$smarty))
		{
			return self::$smarty;
		}
	
		$token = Kohana::$profiling ? Profiler::start('smarty', 'load smarty') : FALSE;
	
		$config = Kohana::config('smarty');
	
		try
		{
			include MODPATH.'koview/vendor/smarty/libs/Smarty.class.php';
		}
		catch (Exception $e)
		{
			throw new Kohana_Exception('Could not load Smarty class file');
		}
	
		$smarty = new Smarty;
		
		// Deal with internal config
		$smarty->php_handling = constant($config->php_handling);
	
		// Deal with main config
		foreach ($config->smarty_config as $key => $value )
		{
			$smarty->$key = $value;
		}
	
		// Check if compiled templates dir is writable
		if ( ! is_writeable($smarty->compile_dir))
		{
			self::create_dir($smarty->compile_dir, 'Smarty compiled template');
		}
	
		// Check for smarty caching , check we can write to the cache directory
		if ($smarty->caching AND ! is_writeable($smarty->cache_dir))
		{
			self::create_dir($smarty->cache_dir, 'Smarty cache');
		}
	
		self::$smarty = $smarty;
	
		$token ? Profiler::stop($token) : null;
		
		return $smarty;
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

} // End Render_Smarty
