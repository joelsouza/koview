<?php defined('SYSPATH') or die('No direct script access.');

class Render_Json extends Render {

	public $extensions = array('json');
	
	public static function render($data=array(), $globals=array(), $file=FALSE, $opt=NULL)
	{
		$opt = $data['_config'];
		
		unset($data['_config']);
		
		if (empty($opt['no_header']) AND ( ! empty(Request::$is_ajax) OR ! empty($opt['force_header'])))
		{
			Request::instance()->headers['Content-Type'] = 'application/json';
			$result = json_encode($data);
		}
		elseif (empty($opt['plain']))
		{
			$result = htmlspecialchars(json_encode($data));
		}
		else
		{
			$result = json_encode($data);
		}
		
		return $result;
	}

} // End Render_Json
