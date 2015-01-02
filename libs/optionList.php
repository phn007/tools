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

	function textdb() {
		$function['functions'] = array( 'textsite' => array(), 'apisite' => array(), 'htmlsite' => array() );
		$action = 'create';
		return array( $action => $function );
	}
	
	function textsite() {
		$createFunction['functions'] = array(
			'code'      => array(),
			'textdb'    => array(),
			'htaccess'  => array(),
			'logo'      => array(),
			'sitemap'   => array(),
			'sitemap_index' => array(),
			'config'    => array(),
		);
		$createAction = 'create';
		return array( $createAction => $createFunction );
	}

	function html() {
		$functions['functions'] = array( 
			'homepage' => array(),
			'productpage' => array(),
			'assets' => array()
		);
		$action = 'build';
		return array( $action => $functions );
	}

	function scrape() {
		$functions['functions'] = array( 
			'nordstrom' => array( 'row', 'page' ),
			'zappost' => array( 'row', 'page' )
		);
		$action = 'merchant';
		return array( $action => $functions );
	}

	function prospapi() {
		$functions['functions'] = array( 
			'category' => array(),
			'brand' => array(),
			'product' => array(),
			'all' => array()
		);
		$action = 'get';
		return array( $action => $functions );
		
	}
}
