<?php

// usage: in the shell : "php importer.cronjob.php env=dev"

// If 1st param is like env=*, use * as the environement
$env = !empty($argv[1]) && preg_match('/env=\w*/', $argv[1]) ? preg_replace('/env=(\w*)/', '$1', $argv[1]) : null;

// Force app context
if ( !empty($env) ){ define('_APP_CONTEXT', $env); }

error_reporting( ( $env === 'dev' ? E_ALL ^E_NOTICE : E_ERROR | E_PARSE ) );

require realpath(dirname(__FILE__) . '/../../../') .  '/config/includes.php';

class_exists('Application')     || require(_PATH_LIBS . 'Application.class.php');

class ProductsJob extends Application
{
    public function import($options)
    {        
        $CProducts = new CProducts();
        
        $CProducts->import($options);

        $this->data['success']  = $CProducts->success;
    }   
}

// Required only when the script is called as a cronjob
$ProductsJob = new ProductsJob();
$ProductsJob->import(array('origin' => 'job'));

?>