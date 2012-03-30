<?php

include '../application/bootstrap.php';

$configSection = getenv('PLACES_CONFIG') ? getenc('PLACES_CONFIG') : 'dev';
$bootstrap = new Bootstrap($configSection);
$bootstrap->runApp();

?>