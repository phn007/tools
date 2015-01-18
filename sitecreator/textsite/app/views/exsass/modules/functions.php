<?php
include_once 'html.php';




$header = new Html( 'header', 1 );
$headerHtml = $header->get();



$footer = new Html( 'footer', 1 );
$footerHtml = $footer->get();




if ( $homePage ) {
	// $cycle = new Html( 'cycle', 1 );
	// $cycleHtml = $cycle->get( $productItems );

	//$masonry = new Html( 'masonry', 1 );
	//$masonryHtml = $masonry->get( $productItems );

	//$echojs = new Html( 'echojs', 1 );
	//$echojsHtml = $echojs->get( $productItems );

	$sbtn = new Html( 'sbtn', 1 );
	$sbtnHtml = $sbtn->get();
} elseif ( $productPage ) {

}

