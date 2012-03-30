<?php

include '../application/bootstrap.php';

$configSection = getenv('PLACES_CONFIG') ? getenc('PLACES_CONFIG') : 'general';
$bootstrap = new Bootstrap($configSection);
$bootstrap->runApp();

?>