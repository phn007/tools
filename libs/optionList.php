<?php
class OptionList
{
	function textsite()
	{
		/*
		controller = "textsite"
		action     = "create"
		function/params   = $createFunction
		*/
		
		$createFuntions = array(
			'code'      => array( 'dev', 'port'),
			'textdb'    => array(),
			'htaccess'  => array(),
			'logo'      => array(),
			'sitemap'   => array(),
			'sitemap_index' => array(),
		);
		$create = array( 'functions' => $createFuntions );

		/*
		controller = "textsite"
		action     = "textdb"
		function/params   = $textDbFunction
		*/
		$textDbFunction = array( 'create' => array() );
		$textdb = array( 'functions' => $textDbFunction );
		
		$options = array( 'config' );
		return array( 'create' => $create, 'textdb' => $textdb, 'options' => $options );
	}
	
	function apisite()
	{
		
	}
	
	function htmlsite()
	{
		
	}
	
	function scrape()
	{
		
	}
}