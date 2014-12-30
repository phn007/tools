<?php
ini_set('display_errors', 1);
error_reporting(~0);

$confPath = 'config/config.php';
if ( ! file_exists( $confPath ) )
	die( "config file does not exitst" );
include 'config/config.php';
extract( $cfg );

$scArr = explode( '#', $statcounter );
$sc_project = $scArr[0];
$sc_security = $scArr[1];

include 'initvars.php';
include BASE_PATH . 'libs/core.php';