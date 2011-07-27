<?php

class VResources extends AdminView
{
    public function __construct(&$application)
	{
        $this->setResource(array('class' => __CLASS__));
		$this->filePath 		= dirname(__FILE__);
		
        parent::__construct($application);
		
		//$this->events->register('onAfterIndex', array('class' => &$this, 'method' => 'handleDatamodelCode'));
		$this->events->register('onCreateSuccess', array('class' => &$this, 'method' => 'createResourceExtra'));
		//$this->events->register('onAfterCreate', array('class' => &$this, 'method' => 'createResourceFiles'));
		
		return $this;
	}
	
	public function retrieve()
	{
		$args = func_get_args();
		
		if ( !empty($args[0]) && $args[0] === 'code' )
		{
			$DataModel = new DataModel();
			$DataModel->parseResources();
			
			//header('Content-Type: plain/text');
			exit($DataModel->generateResources());
		}
		elseif ( !empty($args[0]) && $args[0] === 'file' )
		{
			$DataModel = new DataModel();
			$DataModel->parseResources();
			return $DataModel->buildResources();
		}
		
		call_user_func(array('parent', 'retrieve'), $args);
		//parent::retrieve($args);
	}
	
	
	public function createResourceExtra()
	{
    	// Do not continue if the resource name is not found
    	if ( empty($_POST['resourceName']) ){ return $this; }
		
		$this->tmp['resource'] = array(
			'name' 		=> filter_var($_POST['resourceName'], FILTER_SANITIZE_STRING),
			'singular' 	=> !empty($_POST['resourceName']) ? filter_var($_POST['resourceSingular'], FILTER_SANITIZE_STRING) : Tools::singular($r['name']),
			'table' 	=> !empty($_POST['resourceTable']) ? filter_var($_POST['resourceTable'], FILTER_SANITIZE_STRING) : null,
		);
		
		$this->createResourceTable();
		$this->createResourceFiles();
		
		return;
	}
	
	public function createResourceTable()
	{
		$r = &$this->tmp['resource'];
		
		$name = !empty($r['table']) ? $r['table'] : $r['name'];
		
		$this->controller->model->createTable(array('name' => $name));
		
		return;
	}
	
    public function createResourceFiles()
    {		
		$r = &$this->tmp['resource'];
        
		// Create a zip archive and open it
		$zipFile 	= tempnam('tmp', 'zip');
		$zip 		= new ZipArchive();
		$zip->open($zipFile, ZipArchive::OVERWRITE);
		
		$filesNb = 0;
		foreach ( array('controller','model','view') as $item )
		{
			$firstChar 		= ucfirst($item[0]);
			$fName 			= $firstChar . ucfirst($r['name']); 													// file name
			$fExt 			= '.class.php'; 																		// file extension
			$fFullname 		= $fName . $fExt; 																		// file full name
			$fFolderPath 	= constant('_PATH_' . strtoupper($item . 's')); 										// file folder path
			$fPath 			= $fFolderPath . $fFullname; 															// file final path
			
			// For view, create the admin view too 
			if ( $item === 'view' && !empty($_POST['createAdminView']) )
			{
				$adminViewCtnt = file_get_contents(_PATH_VIEWS . 'admin/_VSamples' . $fExt);
				$adminViewCtnt = preg_replace(
								//array('/VSamples/', '/singular/'),
								array('/VSamples/', '/sample/'),
								//array($fName, $r['singular']), $adminViewCtnt);
								array($fName, $r['singular']), $adminViewCtnt);
								
				$zip->addEmptyDir('views/admin/');
				$zip->addEmptyDir('views/admin/' . $r['name']);
				$zip->addFromString('views/admin/' . $r['name'] . '/' . $fFullname, $adminViewCtnt);
				
				$filesNb++;
			}
			
			if ( empty($_POST['create' . ucfirst($item)]) ){ continue; }
			
			$fCtnt      	= file_get_contents($fFolderPath . '_' . $firstChar . 'Samples' . $fExt); 				// 
			$fCtnt      	= preg_replace(
								//array('/' . $firstChar . 'Samples/', '/singular/'),
								array('/' . $firstChar . 'Samples/', '/sample/'),
								array($fName, $r['singular']), $fCtnt); 												// file content
			
			// If the current file folder is writable, create the file 
			if ( is_writable($fFolderPath) )
			{
		        $created    = file_put_contents($fPath, $fCtnt);	
			}
			// Otherwise throw a warning
			else
			{
				$this->data['warnings'][15010] = $cFilePath;
			}
			
			// Create the proper folder into the archive
			$zip->addEmptyDir($item . 's/');
			
			// Create the proper file into the archive
			$zip->addFromString($item . 's/' . $fFullname, $fCtnt);
			
			$filesNb++;
		}
		
		$zip->close();
		
		// If no file have been create, we do not need to continue
		if ( !$filesNb ){ return $this; }
		
		// Stream the file to the client
		header('Content-Type: application/zip');
		header('Content-Length: ' . filesize($zipFile));
		header('Content-Disposition: attachment; filename="[' . _APP_NAME . ']_' . $r['name'] . '.zip"');
		readfile($zipFile);
		unlink($zipFile);
		
		return;
    }
	
};

?>