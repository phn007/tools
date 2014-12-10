<?php
class OptionList
{
	function textdb()
	{
		//textdb -c ddb2.ini create product
		//textdb -c ddb2.ini create brand
		//textdb -c ddb2.ini create all
		/*
		controller: textdb
		action:     create
		function/params: $
		*/
		$functions = array( 'product' => array(), 'brand' => array(), 'all' => array() );
		return array( 'create' => $functions );
	}
	
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