<?php
include_once 'html.php';

$header = new Html( 'header', 1 );
$headerHtml = $header->get();

$footer = new Html( 'footer', 1 );
$footerHtml = $footer->get();

if ( $homePage ) {
	$init = new Html('inithome', 1 );
	$initHtml = $init->get();
} elseif ( $productPage ) {

}

