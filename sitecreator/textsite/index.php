<?php
ini_set('display_errors', 1);
error_reporting(~0);

/*
 * BASE PATH
 * ----------------------------------------------------------------------------
*/
define( 'BASE_PATH', dirname( realpath( __FILE__ ) ) . '/' );
$confPath = BASE_PATH . 'config/config.php';
if ( ! file_exists( $confPath ) )
	die( "config file does not exitst" );
include $confPath;
extract( $cfg );

$scArr = explode( '#', $statcounter );
$sc_project = $scArr[0];
$sc_security = $scArr[1];

include 'libs/initvars.php';
include BASE_PATH . 'libs/core.php';