<?php
//textdb -c ddb2.ini create product param1 param2
//textdb -c ddb2.ini create brand
//textdb -c ddb2.ini create all

/*
controller: 	textdb
option: 		-c 
option-value: 	ddb2.ini
action:     	create
function: 		product
params: 		param1 param2
*/

class OptionList {
	
	function textsite() {
		$createAction = 'create';
		$createFunction['functions'] = array(
			'code'      => array(),
			'textdb'    => array(),
			'htaccess'  => array(),
			'logo'      => array(),
			'sitemap'   => array(),
			'sitemap_index' => array(),
			'config'    => array(),
		);
		return array( $createAction => $createFunction );
	}

	function html() {
		$action = 'build';
		$functions['functions'] = array( 
			'homepage' => array(),
			'productpage' => array(),
			'assets' => array()
		);
		return array( $action => $functions );
	}

	function prospapi() {
		$action = 'get';
		$functions['functions'] = array( 
			'category' => array(),
			'brand' => array(),
			'product' => array(),
			'all' => array()
		);
		return array( $action => $functions );
	}

	function scrape() {
		$getAction = 'get';
		$getFunctions['functions'] = array( '*' => array( 'row', 'page' ) );
		
		$delAction = 'del';
		$delFunction['functions'] = array(
			'db' => array( 'merchant' ),
			'tb' => array( 'merchant' )
		);
		return array( $getAction => $getFunctions, $delAction => $delFunction );
	}
}
