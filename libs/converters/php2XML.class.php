<?php

class php2XML
{
	public $eol 				= PHP_EOL;
	//public $eol 				= "";
	public $tab 			= "\t";
	public $currentTabsNb 	= 0;
	
	public function __construct()
	{
		return $this;
	}
	
	public function process($data, $options = null)
	{
		$data = (Array) $data;
		
		$this->o = $options;
		
		// Add XML prolog 
		$this->output = '<?xml version="1.0" encoding="UTF-8" ?>' . $this->eol;
		
		// Start wrappring the data in a main object if option setted
		if ( !empty($o['mainObjectName']) ) { $this->output .= '<' . $o['mainObjectName'] . '>' . $this->eol; }
		
		// Loop over the data
		$this->loop($data, 'root');

		// End wrappring the data in a main object if option setted		
		if ( !empty ($o['mainObjectName']) ) { $this->output .= '</' . $o['mainObjectName'] . '>'; }
		
		return $this->output;
	}
	
	public function loop($obj, $mode = null, $parentName = '', $tabsNb = null)
	{		
		if ( !is_array($obj) ) return $obj;

		$tabsNb = !empty($tabsNb) ? $tabsNb + 1 : $this->currentTabsNb++;
		
		$s = (Object) array('openTagTabs' => 0, 'eolAfterOpenTag' => true, 'tabsBeforeContent' => 0, 'eolAfterContent' => true, 'closeTagTabs' => 0, 'eolAfterCloseTag' => true);
		
		switch($mode)
		{
			case 'root':		$submode = 'items'; break;
			case 'items':		$submode = 'properties'; $s->openTagTabs = 1; $s->eolAfterContent = false; $s->closeTagTabs = 1; break;
			case 'properties': 	$submode = null; $s->openTagTabs = 2; $s->eolAfterOpenTag = false; $s->eolAfterContent = false; break;
			default: 			break;
		}
		
		$count = count($obj);
		$i = 0;
		foreach ($obj as $key => $val)
		{			
			$i++;
			
			$isLast 	= $i === $count;
			$submode 	= null;
			$eltName     = is_numeric($key) || !$this->isValidElement($key)/*|| !empty($parentName)*/ ? Tools::singular($parentName) : $key;
//$this->dump($key);
//$this->dump($parentName);
//if ( is_array($val) ) { $this->dump($eltName); }
			
			// Store the current element
			
			$this->output .= $this->addTabs($tabsNb) . '<' . ( $eltName ) . ( $eltName !== $key ? ' key="' . $key . '"' : '' ) . '>';
			
			$this->output .= $s->eolAfterOpenTag && is_array($val) && !empty($val) ? $this->eol : '';
			//$this->output .= $this->addTabs($s->tabsBeforeContent);
			
/*
if ( is_array($val) )
{
	$this->dump($val);
	$this->dump($parentName);
	$this->dump($key);
}
*/
			
			$this->output .= ( !is_array($val) || empty($val) ? $this->handleValue($val) : $this->loop($val, $submode, $key, $tabsNb) );
			$this->output .= ($s->eolAfterContent && is_array($val) && !empty($val) ? $this->eol : '');
			
			//$this->output .= $this->addTabs($s->closeTagTabs) . '</' . $eltName .  '>' . ($s->eolAfterCloseTag ? $this->eol : '');
			$this->output .= ( !is_array($val) || empty($val) ? '' : $this->addTabs($tabsNb) );
			$this->output .= '</' . ( $eltName ) .  '>' . ($s->eolAfterCloseTag && !$isLast ? $this->eol : '');
			//$this->output .= '</' . ( !empty($parentName) ? $this->singular($parentName) : $eltName ) .  '>' . ($s->eolAfterCloseTag && !$isLast ? $this->eol : '');
		}
	}
	
	
	public function handleValue($val)
	{
		$disp = '';
		
		if 		( is_bool($val) )					{ $disp = $val ? 'true' : 'false'; }
		else if ( is_null($val) )					{ $disp = 'null'; }
		else if ( is_int($val) )					{ $disp = (int) $val; }
		else if ( is_array($val) && empty($val) )	{ $disp = ''; }
		else 										{ $disp = $val; }
		
		return $disp;
	}
	
	
	public function addTabs($howMany = 0)
	{
		$tabs = '';
		
		for ($i=0; $i<$howMany; $i++){ $tabs .= $this->tab; }
		
		return $tabs;
	}
	
	public function isValidElement($name)
	{
//$this->dump('isValidElement:' . $name);
//$this->dump(preg_match('/^[a-zA-Z_][a-zA-Z_0-9]*$/', $name));
		return preg_match('/^[a-zA-Z_][a-zA-Z_0-9]*$/', $name);
	}
}

?>