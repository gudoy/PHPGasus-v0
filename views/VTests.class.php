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

	public function testmongo()
	{
		$user = CUsers::getInstance()->retrieve(array('by' => 'email', 'values' => 'guyllaume@clicmobile.com'));
		
		try
		{
		    $cnx = new Mongo('localhost');
//var_dump($cnx);

			// List databases
			$dbs = $cnx->listDBs();
			
			// Get last error
			$lastError = $cnx->lastError();
			
			// Select proper db
			$db = $cnx->selectDB('phpgasus'); 
	
//var_dump('db:');		
//var_dump($db);

foreach ($db->users->find() as $user)
{
	var_dump($user);
}

die();

			// Create a collection
			//$db = $cnx;
	
			// Select a collection
			//$users = $db->selectCollection('phpgasus.users'); // or $db->users
			
			// Delete a collection
			//$users->drop();
			
			// Count collection items
			//$db->users->count();
			
			// list collections
			$collections = $db->listCollections();
	
var_dump('collections:');		
var_dump($collections);
			foreach($collections as $collection){ var_dump($collection->getName()); }
die();
			
			$collection = $db->selectCollection('users'); // or $cnx->selectCollection('photomaton', 'users') if selectDB not used
			
			// Select collection
			$results = $collection->find();
	
var_dump('results:');
var_dump($results);
	
			// Count results
			$numrows = $results->count();
var_dump('numrows: ' . $count);
			
			// Handle results (cursor)
			foreach ( $results as $item )
			{
				//$data['users'][] = $item; 
				$data->users[] = $item;
			}
			
var_dump('data->users:');
var_dump($data->users);
//var_dump($data['users'][0]);
			
			// Inset an item
			//$users->insert($user);
			// $newUser = array('email' => 'doyer.guyllaume@gmail.com', 'comments' => array('it','rocks','!');
			//$collection->insert($newUser));
			
			// Get id of inserted id
			//$insertedId = $newUser['_id']->{'$id'}
			
			// Get mongo id if the first item
			$id = $data->users[0]['_id']->{'$id'};
var_dump('first item id: ' . $id);
			 
			// 
			$toDel = $collection->find(array('email' => 'guyllaume@clicmobile.com'));
			
			// Handle results (cursor)
			foreach ( $toDel as $item )
			{
var_dump($item);
			}
	
			// Close the connexion
			//$cnx->close();
		}
		catch (MongoConnectionException $e)
		{
			// TODO: make something more user friendly. redirect to /error/
			die('Database connection error. ' . ( $this->env['type'] === 'prod' ? '' : $e->getMessage() ));
		}
		catch (MongoException $e)
		{
  			die('Error: ' . $e->getMessage());
		}
	}
	
};

?>