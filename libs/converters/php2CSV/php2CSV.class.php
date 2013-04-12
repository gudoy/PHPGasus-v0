<?php

class php2CSV extends Application
{
	public $output 	= '';
	public $options = array(); 
	
	public function setOptions($options = array())
	{
		$this->options 	= array_merge(array(
			'fixbool' 			=> false, 																				// transform bools to their string representation
			'separator' 		=> !empty($_GET['separator']) && in_array($_GET['separator'], array(',',';','\n','\t')) 
				? $_GET['separator'] 
				: ",",
			'addColumnNames' 	=> true,
			'addComments' 		=> true,
			'comment' 			=> '#',
			//'eol' 				=> PHP_EOL . ($this->request->outputFormat === 'html' ? '<br/>' : ''),
			//'eol' 				=> PHP_EOL . (in_array($this->options['output'], array('html','xhtml')) ? '<br/>' : ''),
			'eol' 				=> PHP_EOL,
		), $options);
		
		return $this; 
	}
	
	public function process($data, $options = array())
	{		
		$this->setOptions($options);
		
//var_dump($data);
//die();
//var_dump($this->options);
//die();
		
		// When the data is just a simple scalar data 
		if ( is_scalar($data) ) { $this->handleScalar($data); }
		// Otherwise
		else 					{ $this->handleComplex($data); }

//var_dump($this->output);

		return $this->output;
	}
	
	public function handleComplex($data)
	{
		// Possible cases:
		// - several resources containing collection: $data = array('users' => $users, 'products' => $products, ...)
		// - only 1 resource : $data = array('users' => $users)
		
		$keys 				= array_keys((array) $data);
		$pattern 			= null;
		$collectionsCount 	= 0;
		
		// Loop over the data
		foreach ($keys as $k)
		{
//var_dump($k);
			$itemsCount = 0; // Init collection items count
			
			if ( !$pattern )
			{
				$isNumIndex 	= is_numeric($k);
				$isResource 	= !$isNumIndex ? DataModel::isResource($k) : false;
				//$pattern 		= !$isNumIndex && $isResource ? 'multiple' : 'single'; // 'single' or 'multiple' resources		
				$pattern 		= !$isNumIndex && is_array($data[$k]) ? 'multiple' : 'single'; // 'single' or 'multiple' resources
				}

//var_dump($pattern);
//var_dump($isResource);
//die();	
			
			// Handle case where the current looped item is a collection
			if ( $pattern === 'multiple' )
			{
				// Get current collection
				$collection = $data[$k];
				
				// Loop over the collection items
				foreach (array_keys($collection) as $itemIndex)
				{
					// Get current item
					$item = $collection[$itemIndex];
					
					if ( is_scalar($item) )
					{
						$this->handleScalar($item);
					}
					else
					{
						if ( $itemsCount === 0 ) { $this->handleColumnsNamesLine($item); }
						
						$this->handleItem($item);
					}

					$this->output .= $this->options['eol'];
					$itemsCount++;
				}
				
				unset($collection);
				$collectionsCount++;
			}
			else if ( $pattern === 'single' )
			{
				// Get current collection
				$item = $data[$k];
				
				if ( is_scalar($item) )
				{
					$this->handleScalar($item);
				}
				else
				{
					if ( $collectionsCount === 0 ) { $this->handleColumnsNamesLine($item); }
					
					$this->handleItem($item);
				}
				
				$this->output .= $this->options['eol'];
				$collectionsCount++;					
			}
		}
	}
	
	public function handleResource()
	{
		
	}
	
	public function handleItem($item)
	{
		$tmpCount = 0;
		foreach ($item as $val)
		{
			if ( $tmpCount !== 0 ) { $this->output .= $this->options['separator']; } 

			$this->handleScalar($val);
			
			$tmpCount++;
		}
	}
	
	public function handleColumnsNamesLine($item)
	{
		// Loop over the item columns
		if ( !$this->options['addComments'] || !$this->options['addColumnNames'] ){ return; }

		// Add a 1st line with column names
		$this->output .= $this->options['comment'] . join($this->options['separator'], array_keys((array) $item)) . $this->options['eol'];
	}
	
	public function handleScalar($data)
	{
		//$this->output .= $this->options['fixbool'] && is_bool($data) ? ($data == true ? 'true' : 'false') : $data;
		$this->output .= $this->options['fixbool'] && is_bool($data) ? ($data == true ? 'true' : 'false') : (is_bool($data) ? (string) (int) $data : $data);
	}
}

?>