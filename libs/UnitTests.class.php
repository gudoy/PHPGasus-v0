<?php 

class UnitTests extends ApiView
{
	public function __construct()
	{
		parent::__construct();
		
		$this->data['testsParams'] = array(
			'outputFormats' => $this->availableOutputFormats, 
		);
		
		return $this;
	}
	
	public function addTest($options = array())
	{
		$o = $options;
		
		return $this;
	}
	
	public function duplicateTest($testId, $options = array())
	{
		$o = $options;
		
		$baseTest = $this->data['unittests'][$testId];
		
		$this->addTest(array_merge($baseTest, $o));
		
		return $this;
	}
	
	public function launchAll()
	{
		foreach ((array) $this->data['unittests'] as $test)
		{
			$this->launch($test);
		}
		
		return $this;
	}
	
	public function launch()
	{
		$args 	= func_get_args();
		$test 	= !empty($args[0]) && is_numeric($args[0]) && !empty($this->data['unittests'][$args[0]]) 
					? $this->data['unittests'][$args[0]]
					: ( is_array($args[0]) ? $args[0] : null);
		$o		= !empty($args[1]) && is_array($args[1]) ? $args[1] : array();
					
		// Do not continue if the test has not been passed/found
		if ( empty($test) ){ return $this; } 
		
		$type = !empty($test['type']) ? strtolower($test['type']) : null;
		
		switch($type)
		{
			case 'httprequest'	: $this->httpRequestTest($test); break;
			default				: break; // nothing for the moment
		}
		
		return $this;
	}
	
	public function httpRequestTest($test)
	{
		$success = null;
//var_dump($test);
		// Extends default params with passed ones, if present
		$p = array_merge(array(
			'url' 			=> null,
			'method' 		=> 'get',
			'queryParams' 	=> '',
			'inputData' 	=> array(),
			'entityBody' 	=> '',
			//'expected' 		=> array(),
		), !empty($test['params']) ? (array) $test['params'] : array());

//var_dump($p);

		// TODO: throw an error/warning: 'missing param "uri" for unittest[$id]'
		if ( empty($p['url']) ){ return $this; }
		
		$wsURI = $p['url'] . $p['queryParams'];
		
		$res 	= $this->wsCall($wsURI, array('method' => $p['method'], 'data' => $p['inputData']));
		$body 	= $res['body']; 
		
//$this->dump($res);

		/*
		if ( (!$p['expected'] || ($body['statusCode'] && $body['statusCode'] === $p['expected']) ) 
			|| 
		)*/
		foreach ((array) $test['expected'] as $key => $val)
		{
			// Name of the current expectation
			$name = strtolower($key);
			
			if 		( $name === 'statuscode' && (empty($body['statusCode']) || $body['statusCode'] !== $val) )	{ $success = false; break; }
			elseif 	( $name === 'resource' && !isset($body[$val]) )												{ $success = false; break; }
			
			$success = true;
		}
		
		$this->data['unittests'][$test['id']]['result'] = array('success' => $success, 'errors' => null, 'warnings' => null); 
		
		return $this;
	}
	
	public function render()
	{
		return parent::render();
	}
}
