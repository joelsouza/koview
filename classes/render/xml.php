<?php defined('SYSPATH') or die('No direct script access.');

class Render_Xml extends Render {

	public $extensions = array('xml');

	public static $less;

	public static function render($vars=array(), $globals=array(), $file=FALSE, $options=array())
	{
		$token = Kohana::$profiling ? Profiler::start('xml', 'rendering ' . basename($file)) : FALSE;
		
		$token ? Profiler::stop($token) : NULL;
		
		unset($vars['_config']);
		$rootNodes = array_keys($vars);
		$firstNode = $rootNodes[0];
		
		$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><".$firstNode." />");

		return self::parse($vars[$firstNode], $xml);
	}
	
	public static function parse($data=array(), $xml=NULL)
	{
		foreach($data as $key => $value)
		{
			if (is_numeric($key))
			{
				$key = "unknownNode_". (string) $key;
			}
			
			if (is_array($value))
			{
				$node = $xml->addChild($key);
				self::parse($value, $node);
			}
			else 
			{
				$value = htmlentities($value);
				$xml->addChild($key,$value);
			}
		}
		
		return $xml->asXML();
	}

} // End Render_Xml
