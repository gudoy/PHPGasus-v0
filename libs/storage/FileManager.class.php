<?php

class_exists('Application') || require(_PATH_LIBS . 'Application.class.php');

class FileManager extends Application
{
	private static $_instance;
	public $success 	= null;
	public $errors 		= null;
	public $ftp 		= null;
	public $ftpLogged 	= null;
	
	public function _construct()
	{
		return $this;	
	}
	
   public static function getInstance()
   {
		if ( !(self::$_instance instanceof self) ) { self::$_instance = new self(); } 
		
		return self::$_instance;
    }
	
	public function connect()
	{
		// Open the ftp connexion pipe
		$this->ftp 			= ftp_connect(_FTP_HOST, _FTP_PORT);
		
		// Login with proper credentials
		$this->ftpLogged 	= ftp_login($this->ftp, _FTP_USER_NAME, _FTP_USER_PASSWORD);

		return $this;		
	}
	
	public function checkFileType($file, $options = array())
	{
		if ( empty($file) ){ return false; }
		
		// Shortcut for options
		$o = $options;
		
		$knownTypes = array(
			# Application files
			'application/pdf' 					=> 'pdf',
		
			# Misc
			'text/plain' 						=> 'txt',

			# Image files
			'image/jpg' 						=> 'jpg',
			'image/jpeg' 						=> 'jpg',
			'image/png' 						=> 'png',
			'image/gif' 						=> 'gif',
			
			# Archive files
			'application/zip' 					=> 'zip',
			'application/x-zip-compressed' 		=> 'zip',
			'application/x-rar-compressed' 		=> 'rar',
			
			# Audio files
			'audio/mpeg' 						=> 'mp3',
			'application/octet-stream' 			=> 'mp3',
			'audio/x-ms-wma' 					=> 'wma',
			'application/ogg' 					=> 'ogg',
			'audio/x-wav' 						=> 'wav',
			'audio/aac' 						=> 'aac',
			'audio/midi' 						=> 'mid',
			'audio/mp4' 						=> 'mp4',
			'audio/mp4' 						=> 'm4a',
			
			# Video files
			//'application/mp4' 					=> 'mp4',
			'video/mp4' 						=> 'mp4',
			'video/x-msvideo' 					=> 'avi',
			'audio/mpeg' 						=> 'mpg',
			'audio/mpeg' 						=> 'mpeg',
			'video/x-flv' 						=> 'flv',
			'video/3gpp' 						=> '3gp',
			'video/x-m4v' 						=> 'm4v',
			'video/x-ms-asf' 					=> 'asf',
			'video/quicktime' 					=> 'mov',
			'video/quicktime' 					=> 'qt',
			'video/x-ms-wmv' 					=> 'wmv',
			//'video/x-mpeg' 						=> 'mpv',
			'video/mpv' 						=> 'mpv',
		);
				
		/*
		$tmpTypes = is_array($o['allowedTypes']) ? $o['allowedTypes'] : (array) explode(',', str_replace('.', '', trim($o['allowedTypes'])));
	
		return !empty($knownTypes[$file['type']]) ? $knownTypes[$file['type']] : false;
		
		return $return;
		*/
		
		// Try to get the extension of the uploaded file type, using know mime types
		$uploadedFileExt = !empty($knownTypes[$file['type']]) ? $knownTypes[$file['type']] : null;
				
		// Allowed types for the current file
		$allowedTypes = is_array($o['allowedTypes']) ? $o['allowedTypes'] : explode(',', $o['allowedTypes']);
		
		// If the uploaded file extension belong to the authorised one for the current field, return it
		// otherwise return false
		return !empty($uploadedFileExt) && in_array($uploadedFileExt, $allowedTypes) ? $uploadedFileExt : false;
	}
	
	
	public function uploadByFtp($file, $options = array())
	{
		// Shortcut for options
		$o = $options;
		
		$this->success = false;
		
		//if ( !$this->checkFileType($file, $o) ){ $this->errors[] = _('Unauthorised file type'); return; }
		
		$this->connect();
		
		//TODO: check if destination folder exists and if not, create it (recursively)
		
		// If the file already exists, rename current version as *_old_time()
		$folderFiles 		= ftp_nlist($this->ftp, _FTP_ROOT . $o['destFolder']);
		
		// Create the folder if does not exists
		//if ( !$folderFiles ){ ftp_mkdir($this->ftp, _FTP_ROOT . $o['destFolder']); }
		if ( !$folderFiles ){ $this->ftp_mkdir_recursive($o['destFolder']); }
		
		if ( !empty($folderFiles) && is_array($folderFiles) && in_array(_FTP_ROOT . $o['destFolder'] . $o['destName'], $folderFiles) )
		{
			//$suffix = time();
			$suffix = date('Ymd_His');
			ftp_rename($this->ftp, _FTP_ROOT . $o['destFolder'] . $o['destName'], _FTP_ROOT . $o['destFolder'] . $o['destName'] . '_old_' . $suffix);
		}
		
		$this->success 		= ftp_put($this->ftp, _FTP_ROOT . $o['destFolder'] . $o['destName'], $o['filePath'], FTP_BINARY);
		
		if ( $this->success )
		{
			@ftp_chmod($this->ftp, 0644,  _FTP_ROOT . $o['destFolder'] . $o['destName']);
		}
		else
		{
			// TODO: handle errors ???
			//$this->errors[] = '?????';
		}
		
		$this->close();
		
		return $this;
	}
	
	
	public function close()
	{
		// Close the connexion
		ftp_close($this->ftp);
		
		return $this;
	}
	
	
	public function rename($currentFilePath, $newFilePath)
	{
		$cur = $currentFilePath;
		$new = $newFilePath;
		
		
		$this->success = !empty($cur) && !empty($new) ? @ftp_rename($this->ftp, $cur, $new) : false;
		
		//return $this->success;
		return $this;
	}
	
	public function mkdir($newDirName)
	{		
		@ftp_mkdir($this->ftp, $newDirName);
		@ftp_chmod($this->ftp, 0755, $newDirName);
		
		//$this->close();
		return $this;
	}
	
	
	public function rmdir($dirName)
	{
		//$this->connect();
		
		@ftp_rmdir($this->ftp, $dirName);
		
		//$this->close();
		return $this;
	}
	
	
	//public function ftp_mkdir_recursive($ftpconn_id, $mode, $path)
	public function ftp_mkdir_recursive($folderPath)
	{
		$directories = split("/", $folderPath);
		$return = true;
		
		$path 	= '';
		foreach ($directories as $dir)
		{
			if ( @ftp_chdir($this->ftp, _FTP_ROOT . $path . $dir . '/') ){ /* nothing */ }
			else
			{
				@ftp_mkdir($this->ftp, _FTP_ROOT . $path . $dir);
				@ftp_chmod($this->ftp, 0755, _FTP_ROOT . $path . $dir);
			}
			
			$path .= $dir . '/';
		}
	}
	
	
	public function delete($file, $options = null)
	{
		if ( !$this->ftp ){ $this->connect(); }
		
		// TODO: return error/warning????
		if ( !$this->ftp ) { return $this; } 
		
		@ftp_delete($this->ftp, $file);
		
		return $this; 
	}
}

?>