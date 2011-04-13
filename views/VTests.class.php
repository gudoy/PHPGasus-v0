<?php

class VTests extends View
{
    public function __construct(&$application)
    {
        //$this->setResource(array('class' => __CLASS__));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct($application);
		
		return $this;
	}
	
	public function mysqli()
	{
		$this->db = new mysqli(_DB_HOST, _DB_USER, _DB_PASSWORD, _DB_NAME);
		$queryResult 			= $this->db->query('SELECT * FROM groups');
	
var_dump($this->db);
var_dump($queryResult);
var_dump($this->db->info);
		
		while ($row = $queryResult->fetch_assoc())
		{
			$data[] = $row;
		}
		
var_dump($data);
	}
	
	public function pdo()
	{
//var_dump(PDO::getAvailableDrivers());
		
		$dsn 		= 'mysql:dbname=' . _DB_NAME . ';host=' . _DB_HOST;
		$this->db 	= new PDO($dsn, _DB_USER, _DB_PASSWORD);

		//$this->selectedDb = mysqli_select_db($this->db, _DB_NAME);
		//$queryResult 			= mysqli_query('SELECT * FROM groups', $this->db);
		$queryResult 			= $this->db->query('SELECT * FROM groups');

var_dump($this->db);		
var_dump($queryResult);
var_dump($this->db->info);
		
		//while ($row = mysqli_fetch_array($queryResult, MYSQL_ASSOC))
		$data = $queryResult->fetchAll(PDO::FETCH_ASSOC);
		
var_dump($data);
	}
	
};

?>