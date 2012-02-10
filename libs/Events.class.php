<?php

Class Events
{
	private $registered = array();
	
	public function __construct()
	{
		return $this;
	}
	
	// Usage: register('onUpdateSuccess', 'AdminUtils.incrementVersionNb')
	// Usage: register('onUpdateSuccess', 'fakeFunction')
	// Usage: register('onUpdateSuccess', array('class' => 'AdminUtils', 'method' => 'incrementVersionNb'))
	// Usage: register('onUpdateSuccess', array('function' => 'fakeFunction', 'arguments' => array('foo','bar','foobar')))
	// Usage: register('onUpdateSuccess', array('class' => &$this, 'method' => 'foobar'))
	public function register()
	{		
		// Get passed arguments
		$args 		= func_get_args();
		
		// Shortcut for event name
		$n 			= !empty($args[0]) && is_string($args[0]) ? $args[0] : null;
		
		// Do not continue if no event name has been passed or if the event has not been registered
		if ( empty($n) ){ return $this; }
		
		// If the 2nd argument is a string, assume it is the callback function name following one of the patterns:
		// classname.methodname
		// functionname
		$callback 	= !empty($args[1]) && is_string($args[1]) ? $args[1] : false;
		
		// If the 2nd argument is a string, try to get the used pattern
		$pattern 	= $callback ? ( strpos($callback, '.') !== false ? 'CM' : 'F' ) : false;
		$tmp 		= $callback ? explode('.', $callback) : null;
		
		$o 			= !empty($args[1]) && is_array($args[1]) ? $args[1] : array();

		// Get the classname/methodname/functionname, depending of the passed args
		$data 		= array(
			'class' 		=> !empty($o['class']) ? $o['class'] : ( $callback && $pattern === 'CM' && !empty($tmp[0]) ? $tmp[0] : '' ),
			'method' 		=> !empty($o['method']) ? $o['method'] : ( $callback && $pattern === 'CM' && !empty($tmp[1]) ? $tmp[1] : '' ),
			'function' 		=> !empty($o['function']) ? $o['function'] : ( $callback && $pattern === 'F' && !empty($callback) ? $callback : '' ),
			'arguments' 	=> !empty($o['arguments']) ? $o['arguments'] : array(),
			'data' 			=> '',
			'source' 		=> array('class' => '', 'method' => '', 'function' => ''),
		);
		
		$this->registered[$n][] = $data;
		
		return $this;
	}
	
	//public function trigger($eventName, $options = array())
	public function trigger()
	{
		// Get passed arguments
		$args 		= func_get_args();
		
		// Shortcut for event name
		$n 			= !empty($args[0]) && is_string($args[0]) ? $args[0] : null;
		
		// Do not continue if no event name has been passed or if the event has not been registered
		if ( empty($n) || empty($this->registered[$n]) ){ return $this; }
		
		$o 			= !empty($args[1]) && is_array($args[1]) ? array_merge($this->registered[$n], $args[1]) : array();
		
		foreach ($this->registered[$n] as $e)
		{			
			// Do not process the current item if the class or the method does not exists
			if ( ( !is_object($e['class']) && 
			     ( !class_exists($e['class']) || !method_exists($e['class'], $e['method']) ) && !function_exists($e['function']) ) ) 
            { continue; }
			
			if ( !empty($e['function']) )
			{
				call_user_func_array($e['function'], $e['arguments']);
			}
			else
			{
				//call_user_func_array(array($o['class'], $o['method']), $o['arguments']);
				//call_user_func_array(array(is_object($e['class']) ? $e['class'] : new $e['class'](null), $e['method']), $e['arguments']);
				call_user_func_array(array(is_object($e['class']) ? $e['class'] : new $e['class'](null), $e['method']), $e['arguments']);	
			}
		}
		
		return $this;
	}
}

?>