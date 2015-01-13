<?php
include_once 'html.php';

$header = new Html( 'header', 1 );
$headerHtml = $header->get();

$footer = new Html( 'footer', 1 );
$footerHtml = $footer->get();


if ( $homePage ) {
	// $jumbotron = new Html( 'jumbotron', 1 );
	// $jumbotronHtml = $jumbotron->get();

	// $feature = new Html( 'feature', 1 );
	// $featureHtml = $feature->get();

	//$impact = new Html( 'impact', 2 );
	//$impactHtml = $impact->get( $productItems );

	//$cycle = new Html( 'cycle', 1 );
	//$cycleHtml = $cycle->get( $productItems );

	//$masonry = new Html( 'masonry', 1 );
	//$masonryHtml = $masonry->get( $productItems );

	$echojs = new Html( 'echojs', 1 );
	$echojsHtml = $echojs->get( $productItems );
} elseif ( $productPage ) {

}

