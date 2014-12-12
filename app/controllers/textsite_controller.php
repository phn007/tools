<?php
use webtools\controller;

class TextSiteController extends Controller
{
	function create( $funtions, $params, $options )
	{
		print_r( "Textsite Controller - create");
		echo "\n";
		print_r( $funtions );
		echo "\n";
		print_r( $params );
		print_r( $options );
	}
}