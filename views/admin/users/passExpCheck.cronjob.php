<?php

// usage: in the shell: 
// php importer.cronjob.php env=dev/ or
// php importer.cronjob.php env=prod

// If 1st param is like env=*, use * as the environement
$env = !empty($argv[1]) && preg_match('/env=\w*/', $argv[1]) ? preg_replace('/env=(\w*)/', '$1', $argv[1]) : null;

// Force app context
if ( !empty($env) ){ define('_APP_CONTEXT', $env); }

// Force errors reporting
error_reporting( ( $env === 'dev' ? E_ALL ^E_NOTICE : E_ERROR | E_PARSE ) );

// Includes required files (conf)
require realpath(dirname(__FILE__) . '/../../../') .  '/config/includes.php';

class_exists('Application') || require(_PATH_LIBS . 'Application.class.php');

$resource 	= 'users';

class passExpCheckJob extends Application
{
    public function check($options)
    {
    	global $resource;
		
    	$cName 	= 'C' . ucfirst($resource);
        $$cName = new $cName();
        $$cName->passwordExpirationsCheck($options);

        $this->data['success']  = $$cName->success;
    }   
}

// Required only when the script is called as a cronjob
$jobName 		= 'passExpCheckJob';
$$jobName 		= new $jobName();
$$jobName->check(array('origin' => 'job'));

?>