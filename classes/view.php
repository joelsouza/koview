<?php defined('SYSPATH') or die('No direct script access.');

/**
 * View module for KO3
 *
 * Overwrites kohana View class to provide custom render engines
 * to parse and output templates.
 *
 * @package    Koview
 * @author     Birkir R Gudjonsson (birkir dot gudjonsson at gmail dot com)
 * @copyright  (c) 2010 Birkir R Gudjonsson
 * @license    http://kohanaframework.org/license
 */
class View extends Kohana_View {
	
	protected $_renderer = 'php';
	
	/**
	 * Starts profiling, if available, and loads up koview config. Then
	 * load parent constructor to continue the class.
	 *
	 * @param   string  view filename
	 * @param   array   array of values
	 * @return  void
	 */
	public function __construct ($file = NULL, $data = null)
	{
		$token = Kohana::$profiling ? Profiler::start('renderer', 'new koview') : FALSE;
		
		$this->_config = Kohana::config('koview');
	
		parent::__construct($file, $data);
		
		$token ? Profiler::stop($token) : null;
	}
	
	/**
	* Parse a template name and set the renderer and filename.
	*
	* @param   string  filename
	* @return  void
	*/
	public function set_filename($file)
	{
		$pos = strpos($file, ':');
		
		if ($pos === FALSE)
		{
			$this->_renderer = 'php';
		}
		elseif ($pos == 0)
		{
			$this->_renderer = 'php';
			$file = substr($file, 1);
		}
		else
		{
			$this->_renderer = substr($file, 0, $pos);
			$file = substr($file, $pos + 1);
		}
	
		// Allow the parent method to set_filename
		if ($this->_renderer == 'php')
		{
			return parent::set_filename($file);
		}
		
		// REVISIT we should implement this in the renderer
		$this->_set_filename($file);
	
	}
	
	public function _set_filename($file)
	{
		$class_name = 'Render_'.ucfirst($this->_renderer);
		
		$render = new $class_name;
		
		// Find wanted extension and the template file
		if (isset($render->extensions))
		{
			$exts = $render->extensions;
			
			if ($render->feed === TRUE)
			{
				// Does not use template files
				$path = TRUE;
			}
			else
			{
				foreach ($exts as $ext)
				{
					if (($path = Kohana::find_file('views', $file, $ext)) === FALSE)
					{
						break;
					}
				}
				
				if (!isset($path))
				{
					throw new Kohana_View_Exception('The requested view file :file.:ext could not be found', array(
						':file' => $file, ':ext' => $ext,
					));
				}
			}
		}
		else
		{ 
			throw new Kohana_View_Exception('There is no extension set for the :renderer renderer', array(
				':renderer' => $this->_renderer
			));
		}
	
		// Store the file path locally
		$this->_file = $path;
	}
	
	/**
	* Renders the view object to a string. Global and local data are merged
	* and extracted to create local variables within the view file.
	*
	* Note: Global variables with the same key name as local variables will be
	* overwritten by the local variable.
	*
	* @throws   View_Exception
	* @param    view filename
	* @return   void
	*/
	public function render($file = NULL, $options=array())
	{
		if ($this->_renderer == 'php')
		{
			return parent::render($file);
		}
		
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}
	
		if (empty($this->_file))
		{
			throw new Kohana_View_Exception('You must set the file to use within your view before rendering');
		}
		
		$method = 'Render_'.ucfirst($this->_renderer).'::render';
		
		return call_user_func($method, $this->_data, View::$_global_data, $this->_file, $options);
	}

}
