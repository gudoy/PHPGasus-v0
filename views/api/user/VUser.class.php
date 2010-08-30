<?php

class_exists('VUsers') || require(_PATH_VIEWS . 'api/users/VUsers.class.php');

class VUser extends VUsers
{	
	public function index($resourceId = null, $options = null)
	{		
		parent::index($resourceId, $options);
	}
}

?>