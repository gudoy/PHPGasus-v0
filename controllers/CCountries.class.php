<?php

class CCountries extends Controller
{
	private static $_instance;
	
	public function __construct()
	{
        $this->setResource(array('class' => __CLASS__, 'singular' => 'country'));
		
		return parent::__construct();
	}
	
	public static function getInstance()
	{
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
	}
	
    public function import($options = array())
    {
        //ini_set('max_execution_time',300);
        //ini_set('memory_limit', '512M');
        
        $o = array_merge(array(
            'origin' 		=> 'http', // 'http' or 'job'
            'nbItems'       => !empty($_GET['nbItems'])                     ? (int) $_GET['nbItems'] : null, 
        ), $options);
        
        $t1             = microtime(true);
        $this->lb       = $o['origin'] === 'job' ? PHP_EOL : '<br/>';
		$srcFilePath 	= _PATH_PUBLIC . 'countryInfo.txt';
        
        // Opens the file and lock it (prevents other process to access it)
        $handle         = fopen($srcFilePath, 'r');
        
        // Do not continue if the file could not be open
        if ( !is_resource($handle) ){ return $this; }
             
        flock($handle, LOCK_EX);
        
        $rowNb                  = 0;
        $imported               = 0;
        $updated                = 0;
        
        // Instanciate proper controllers
        foreach ( array('tasks') as $item)
        {
            $cname  = 'C' . ucfirst($item);
            $$cname = new $cname();
        }

        // Create a task an get its id
        $task 			= array(
            'admin_title'           => $this->resourceName . 'Import_' . strftime('%d-%m-%y-%Hh%M', $_SERVER['REQUEST_TIME']), 
            'type'                  => 'import',
            'subtype' 				=> $this->resourceName, 
            'creation_date'         => $_SERVER['REQUEST_TIME'],
        );
		$_POST 			= $task;
		$tId 			= $CTasks->create(array('isApi' => 1, 'returning' => 'id'));

		$querys = "\n";

        // As long as we find a row to parse
        while ( ($row = fgetcsv($handle, 1000, "\t") ) !== false)
        {
			// Skip comments rows
			if ( (is_string($row) && $row[0] === '#') || (is_array($row) && $row[0][0] === '#') ) { continue; }
			
            // Do not continue if the row is empty
            if ( empty($row) ){ continue; }
			
            $rowNb++;

            // Do not continue over the limit, if specified
            if ( !empty($o['nbItems']) && $rowNb > $o['nbItems'] ) { break; }
			
			$country = array(
				'slug' 					=> Tools::slug($row[4]),
				'name' 					=> $row[4],
				'iso' 					=> $row[0],
				'iso3' 					=> $row[1],
				'iso_numeric' 			=> (int) $row[2],
				'fips_code' 			=> $row[3],
				'capital_name' 			=> $row[5],
				'currency_code' 		=> $row[10],
				'currency_name' 		=> $row[11],
				'area_square_km' 		=> (float) $row[6],
				'population' 			=> (int) $row[7],
				'continent_code' 		=> $row[8],
				'tld' 					=> $row[9],
				'phone_code' 			=> (int) $row[12],
				'postal_code_format' 	=> $row[13],
				'postal_code_regex' 	=> $row[14],
				'spoken_languages' 		=> $row[15],
				'neighbours' 			=> $row[17],
			);
//var_dump($row);
//var_dump($country);
			$_POST = $country;
			$this->create(array('isApi' => 1));
			
			// If the import succeed
			if ( $this->success ) { $imported++; }
			
			else
			{
				// Try to update instead
				$this->update(array('isApi' => 1, 'conditions' => array('iso3' => $row[1])));
				if ( $this->success ) { $updated++; }
			} 
        }

		// Execute the multi querys (require mysqli) 
		//$this->model->update(true, array('manualQuery' => $querys));
		//$updated = $this->model->affectedRows;
        
        $t2     = microtime(true);
        $ptime  = ($t2 - $t1);
        
        // Update the task with the processed items number
        if ( ($imported + $updated) > 0 )
        {
            $task = array(
                'processed_items_nb'    => $imported + $updated,
            );
            $_POST = $task;
            $CTasks->update(array('isApi' => 1, 'by' => 'id', 'values' => $tId));
        }
        
        $this->success = true;
        
		$succesLog = $this->lb;
        $succesLog .= 'TOTAL: processed in: ' . $ptime . 's' . $this->lb;
        $succesLog .= 'TOTAL: ' . $rowNb . ' row(s) found' . $this->lb;
        $succesLog .= 'TOTAL: ' . $imported . ' record(s) inserted' . $this->lb;
        $succesLog .= 'TOTAL: ' . $updated . ' record(s) updated' . $this->lb;
        $succesLog .= 'BATCH END ----' . $this->lb;
        print_r($succesLog);
        
        return $this;
    }
}
?>