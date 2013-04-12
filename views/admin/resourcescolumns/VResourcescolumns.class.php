<?php

class VResourcescolumns extends AdminView
{
    public function __construct(&$application)
    {
        $this->setResource(array('class' => __CLASS__, 'singular' => 'resourcescolumn'));
		
		parent::__construct($application);
		
		return $this;
	}
	
	public function retrieve()
	{
		$args = func_get_args();
		
		if ( !empty($args[0]) && $args[0] === 'code' )
		{
			$DataModel = new DataModel();
			$DataModel->parseColumns();
			
			//header('Content-Type: plain/text');
			exit($DataModel->generateColumns());
		}
		elseif ( !empty($args[0]) && $args[0] === 'file' )
		{
			$DataModel = new DataModel();
			$DataModel->parseColumns();
			return $DataModel->buildColumns();
		}
		
		call_user_func(array('parent', 'retrieve'), $args);
		//parent::retrieve($args);
	}
	
};

?>