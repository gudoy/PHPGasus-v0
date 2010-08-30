<?php

//$confFile = $_SERVER['DOCUMENT_ROOT'] . "config/config.php";
$confFile = dirname(__FILE__) . '/config.php';
				
// Load Conf File
include($confFile);

// Load Application Data
require("appData.php");

// Load Paths & URLs File
require("routes.php");

// Load specific config files
require(_PATH_LIBS . "specific/functions.php");

?>