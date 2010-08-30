<?php

class_exists('View') || require(_PATH_LIBS . 'View.class.php');
    
class VWidgets extends View
{
	public function index()
	{		
		$this->install();
	}
	
	/**
	 * This function generate the lkbo widget
	 * 
	 * @author Guyllaume Doyer <guyllaume@clicmobile.com>
	 * @return null
	 */	
	public function build($options = array())
	{
		class_exists('CWidgets') || require(_PATH_CONTROLLERS . 'CWidgets.class.php');
		
		//$widgetHome_url = _URL;
		$widgetIndexUrl = _URL . 'widgets/tests/';
		
		$o = $options;
		$o['printLogs'] = isset($o['printLogs']) ? $o['printLogs'] : true;
		$logs = '';
		
		// Widget content temp folder
		$path2widgetBuilds 	= _PATH_PUBLIC . 'widget/builds/';
		$tmpFolder 			= $path2widgetBuilds . 'tmp/';
		$path2files			= _PATH_PUBLIC . 'owe/widgets/myApp/';
		
		// If the folder exists, just delete it
		if ( is_dir($tmpFolder) ) { CWidgets::rmdirr($tmpFolder); }
		
		// Create folder
		$tmpFolderCreated = mkdir($tmpFolder, 0775);
		$logs .= "tmp folder created: '" . ( $tmpFolderCreated ? 'OK' : 'NOK' ) . '<br/>';
		
		// Copy widget files into folder (config.xml, index.html, icon.png)
		$which = _APP_CONTEXT === 'prod' ? 'prod' : 'dev';
		
		// Copy config.xml file
		$fileName 			= $path2files . 'config_' . $which .'.xml';
		$configFileAdded 	= copy($fileName, $tmpFolder . 'config.xml');
		$logs .= "config.xml added: '" . ( $configFileAdded ? 'OK' : 'NOK' ) . '<br/>';

		// Copy icon.png file
		$fileName 			= $path2files . 'icon.png';
		$iconFileAdded 	= copy($fileName, $tmpFolder . 'icon.png');
		$logs .= "icon.png added: '" . ( $iconFileAdded ? 'OK' : 'NOK' ) . '<br/>';

		// GET index.html content, do some replacements, and create file
		$fileName 			= $tmpFolder . 'index.html';
		$content 			= file_get_contents($widgetIndexUrl . '?isTabbee=1&opera=true');
		$content 			= str_replace('config.js.php', 'config.js', $content);
		$finalContent 		= str_replace(_URL_PUBLIC, '', $content);
		$fp 				= fopen($fileName, 'w');	
		$indexFileAdded 	= fwrite($fp, $finalContent);
		$logs .= "index.html added: '" . ( $indexFileAdded ? 'OK' : 'NOK' ) . '<br/>';
	
		// Create css folder
		$folderPath 		= $tmpFolder . 'stylesheets/default/';
		$cssFoldersCreated 	= CWidgets::mkdirr($folderPath, 0775);
		//$logs .= "CSS folders created: '" . ( $cssFoldersCreated ? 'OK' : 'NOK' ) . '<br/>';
	
		// Copy all css files
		$folderPath 		= _PATH_STYLESHEETS;
		$cssAdded 			= CWidgets::copyr($folderPath, $tmpFolder . 'stylesheets/default/');
		$logs .= "CSS added: " . ( $cssAdded ? 'OK' : 'NOK' ) . '<br/>';
		
		// Copy all js files
		$folderPath 		= _PATH_JAVASCRIPTS;
		$jsAdded 			= CWidgets::copyr($folderPath, $tmpFolder . 'javascripts');
		$logs .= "JS added: " . ( $cssAdded ? 'OK' : 'NOK' ) . '<br/>';
		
		// Copy all media files
		/*
		$folderPath 		= _PATH_PUBLIC . 'media/';
		$mediaAdded 			= CWidgets::copyr($folderPath, $tmpFolder . 'media');
		$logs .= "Media added: " . ( $mediaAdded ? 'OK' : 'NOK' ) . '<br/>';
		*/
		
		// Copy all media files
		$folderPath 		= _PATH_PUBLIC . 'media/flash/';
		mkdir($tmpFolder . 'media/');
		$mediaAdded 			= CWidgets::copyr($folderPath, $tmpFolder . 'media/flash/');
		$logs .= "Media added: " . ( $mediaAdded ? 'OK' : 'NOK' ) . '<br/>';
		
		// FETCH config.js.php content and create config.js file
		$fileName 			= $tmpFolder . 'javascripts/config/config.js';
		$content 			= file_get_contents(_URL_JAVASCRIPTS . 'config/config.js.php');
		$fp 				= fopen($fileName, 'w');	
		//$jsConfigFileAdded 	= fwrite($fp, utf8_encode($content));
		$jsConfigFileAdded 	= fwrite($fp, $content);
		$logs .= "config.js added: '" . ( $jsConfigFileAdded ? 'OK' : 'NOK' ) . '<br/>';
		
		// Delete zip if already exists
		$widgetFilePath 	= $path2widgetBuilds . _APP_WIDGET_NAME;
		if ( file_exists($widgetFilePath) ) { unlink($widgetFilePath); }
		
		// Create zip from folder
		$zip 				= new ZipArchive;
		$res 				= $zip->open($widgetFilePath, ZipArchive::CREATE);
		$tmpFolder 			= $path2widgetBuilds . 'tmp/';
		$zipCreated 		= CWidgets::addFolderContentToZip($tmpFolder, '', $zip);
		$logs .= "ZIP created: " . ( $zipCreated ? 'OK' : 'NOK' ) . '<br/>';
		
		if ( $o['printLogs'] ) { echo $logs; }
	
		return $this;
	}
	
	public function install()
	{
		// Since that file_exists results are cache, we have to empty this cache before doing any test
		clearstatcache();
		
		if ( !file_exists(_PATH . 'public/widgets/builds/' . _APP_WIDGET_NAME) ){ $this->build(array('printLogs' => false)); }
		
		// Then, render page
		return $this->render(array(
			'view' => array(
				'name' 			=> 'widgetInstall',
				'template' 		=> 'pages/widgets/install.tpl'
			),
		));
	}
	
	public function tests()
	{
		// Then, render page
		return $this->render(array(
			'view' => array(
				'name' 			=> 'widgetInstall',
				'template' 		=> 'pages/widgets/tests.tpl'
			),
		));
	}
}
	
?>