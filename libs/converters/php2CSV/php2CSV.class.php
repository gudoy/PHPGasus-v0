<?php

class php2CSV extends Application
{
	public $eol 				= PHP_EOL;
	public $tab 			= "\t";
	public $currentTabsNb 	= 0;
	public $currentResource = '';
	
	public function __construct()
	{
		return $this;
	}
	
	public function process($data, $options = array())
	{
		$data = (Array) $data;
		
		$this->o = $options;
		
		// Loop over the data
		$this->loop($data);
		
var_dump($this->fields);
		
		//return $this->output;
	}
	
	public function loop($data, $options = array())
	{
		$o = array_merge(array('parent' => ''), $options);
	
		$this->fields = array();
		$this->values = array();
		
		foreach ( $data as $k => $v )
		{
			$isMulti = is_array($v);
var_dump($k);
//var_dump(@$v[0]);
//var_dump($v);
//var_dump($isMulti);

			//if ( $isMulti && !empty($v[0]) && is_numeric(key($v[0])) ){ return $this->loop($v, array('parent' => $k)); }
			//if ( $isMulti && is_numeric($k) ){ return $this->loop($v, array('parent' => $k)); }

			//else if ( $isMulti ){ $this->currentResource = $k; return $this->loop($v, array('parent' => $k)); }
			
			//$this->fields[] = $this->currentResource . ( !empty($this->currentResource) ? '-' : '') . $k;
		};
	}
}

?>