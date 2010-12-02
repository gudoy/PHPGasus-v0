<?php

class CContents extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
        $this->setResource(array('class' => __CLASS__)); 
		
		return parent::__construct();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
	
	public function extendsData()
	{
		parent::extendsData();

		// Do not continue if there's no data (or if data is not a collection)
		if ( empty($this->data) || !is_array($this->data) ){ return $this; }
		
		// Load proper controllers
		$this->requireControllers(array('CMedia'));
		
		// Get list of the contents id of the current collection, indexing them by their contents_id
		$cids 	= $this->values('id');
		$media 	= CMedia::getInstance()->index(array('by' => 'contents_id', 'values' => $cids, 'reindexby' => 'contents_id', 'isUnique' => false));

		foreach ( $this->data as $k => $item )
		{
			// Get the current content id
			$cid = $item['id'];
			
			// Try to get media for this content id
			if ( !empty($media[$cid]) ){ $this->data[$k] = $item + array('media' => $media[$cid]); }
		}
		
		return $this;
	}
}
?>