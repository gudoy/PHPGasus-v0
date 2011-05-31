<?php

class VCountries extends AdminView
{
    public function __construct(&$application)
    {
        $this->setResource(array('class' => __CLASS__, 'singular' => 'country'));
		$this->filePath 		= dirname(__FILE__);
		
		parent::__construct($application);
		
		return $this;
	}
	
	/*
	public function import($options = array())
	{
		return $this->C->import($options);
	}*/


	/*
	public function frNamesImporter()
	{
		$file = new SplFileObject(_PATH_PUBLIC . 'list_coutries_FR.csv');
		while ( !$file->eof() )
		{
			$row 	= $file->fgetcsv(';');
			$_POST 	= array('name_FR' => $row[1]);
			$this->C->update(array('isApi' => 1, 'conditions' => array('iso3' => $row[0])));
			
			if ( !$this->C->success ){ var_dump($row); }
			
		}
	}*/
	
	/*
	public function flags()
	{
		$folder 	= _PATH_PUBLIC . 'flags_svg/'; 
		$iterator 	= new DirectoryIterator($folder);
		$notFound 	= 0;
		
	    foreach ($iterator as $item)
	    {
	        if ( $item->isFile() )
	        {
	        	$fileName 	= utf8_encode($item->getFilename()); 
	        	$cntryName 	= trim(str_ireplace(
	        		array('.svg','flag_of_', 'the', '_', 'flag'), 
	        		array('', '', '', ' ', ''), 
	        		$fileName
				));
				$cntryName = preg_replace(
					array('/\(.*\)/', '/\s{2,}/', '/\s$/'), array('', ' ',''), $cntryName
				);
				
				$iso3 		= $this->C->retrieve(array('getFields' => 'iso3', 'conditions' => array(array('name','contains',$cntryName))));

				// If the iso3 code has not been found this way
				if ( !$iso3 )
				{
					// Split the country name into words, excluding some articles
					$filter 	= function ($word) { return !in_array($word, array('and','of')); };
					$parts 		= array_filter(explode(' ', $cntryName), $filter);
					
					// Foreach one of those parts, retry to find the related country
					$found = false;
					foreach ( $parts as $word)
					{
						$iso3 		= $this->C->retrieve(array('getFields' => 'iso3', 'conditions' => array(array('name','contains',$word))));
						
						// Do not continue if the country has been found
						if ( $iso3 ) { $found = true; break; }
					}
					
					// Only display not found countries
					if ( !$found )
					{
						var_dump($cntryName);
						$notFound++;
					}
				}
				
 				if ( $iso3 ) { rename($folder . $fileName, $folder . $iso3 . '.svg'); }
	        }
	    }
		
		// End
		echo 'Not Found: ' . $notFound;
	}*/
	
};

?>