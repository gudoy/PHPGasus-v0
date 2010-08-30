<?php

class_exists('Application') || require(_PATH_LIBS . 'Application.class.php');

class Model extends Application
{
	var $debug 			= false;
	var $db 			= null;
	var $data 			= array();
	var $success 		= false;
	var $errors 		= array();
	var $affectedRows 	= null;
	var $numRows		= null;
	
	public function __construct()
	{
//echo "Model.__construct<br/>";
		
		return $this->connect();
	}

	public function connect()
	{
		// Open a connection on the db server
		$this->db = new SQLite3(_PATH_DB . 'tvmag.db.sqlite');
		
		if 		( $this->db === null )	{ $this->errors[] = 4000; } // Database connection error
		else if ( !$this->db ) 			{ Controller::redirect(_URL_SITE_DOWN); }
		
		return $this;
	}
	
	public function query($query)
	{
//echo "Model.query<br/>";
		
		// Connect to the db
		if ( $this->db === null ) { $this->connect(); } 
		
		// Do the query
		$queryResult = $this->db->query($query);
		
		//if ( (getenv("APP_CONTEXT") == 'local' || getenv("APP_CONTEXT") == 'dev') && $this->debug ) { echo $query . "<br/>"; }
		
		$this->success 					= is_bool($queryResult) && $queryResult === false ? false : true;
		//$this->data['numRows'] 		= is_resource($queryResult) ? mysql_num_rows($queryResult) : null;
		
		if ( $this->success )
		{
			 $this->fetchResults($queryResult);
			 $this->affectedRows 	= $this->db->changes();
			// $this->numRows 		= $queryResult->num_rows();
			 $this->numRows 		= count($this->data);
		}
		else
		{
			$dbErrCode 	= $this->db->lastErrorCode();
			$dbErrMsg 	= $this->db->lastErrorMsg();
			$dbErr		= $dbErrCode . ' :' . $dbErrMsg; 
		}
		
		return $this;
	}

	public function fetchResults($queryResult)
	{
//echo "Model.fetchResults<br/>";
		
		// Fetch the query result set		
		while ($result = $queryResult->fetchArray(SQLITE_ASSOC))
		{
			$this->data[] = $result;
		}
		
		return $this;
	}



}


?>