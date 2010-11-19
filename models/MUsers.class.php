<?php

class MUsers extends Model
{
	public function __construct($application = null)
	{
		$this->resourceName = strtolower(preg_replace('/^M(.*)/','$1', __CLASS__));
		
		return parent::__construct($application);
	}

}
?>