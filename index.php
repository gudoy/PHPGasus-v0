<?php

require 'config/includes.php';

class_exists('Application') || require _PATH_LIBS . 'Application.class.php';

$Application = new Application();
$Application->dispatch();

?>