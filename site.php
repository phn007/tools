<?php
include 'libs/options.php';

$controller = 'site';
$action = 'createsite';

$opt = new Options();
$options = $opt->get( $controller, $action, $options );

include 'appindex.php';
