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

	function site() {
		$textSiteAction = 'create';
		$textSiteFunction['functions'] = array( 'textdb' => array('csvFilename') );
		return array( $textSiteAction => $textSiteFunction );
	}

	function textdb() {
		$textdbAction = 'create';
		$textdbFunction['functions'] = array( 'db' => array('csvFilename', 'iniFilename') );
		return array($textdbAction => $textdbFunction);
	}
	
	function textsite() {
		$createAction = 'create';
		$createFunction['functions'] = array(
			'textdb'    => array(),
			'textdbNew' => array(),
			'code'      => array(),
			'htaccess'  => array(),
			'logo'      => array(),
			'sitemap'   => array(),
			'sitemapindex' => array(),
			'config'    => array(),
			'robots'    => array(),
			'siteall'   => array(),
			'all'       => array(),
			'theme'     => array(),
			'zip'       => array()
		);

		$serverAction = 'server';
		$serverFunction['functions'] = array( 'start' => array() );

		$showAction = 'show';
		$showFunction['functions'] = array( 'config' => array() );

		$calcAction = 'calc';
		$calcFunction['functions'] = array( '*' => array('number') );

		$calculateAction = 'calculate';
		$calculateFunction['functions'] = array( '*' => array('number') );

		$delDbAction = 'db';
		$delDbFunction['functions'] = array( 'del' => array() );

		$separatorAction = 'separator';
		$deparatorFunction['functions'] = array( 'check' => array() );

		return array( 
			$createAction => $createFunction, 
			$serverAction => $serverFunction,
			$showAction => $showFunction,
			$calculateAction => $calculateFunction,
			$calcAction => $calculateFunction,
			$delDbAction  => $delDbFunction,
			$separatorAction => $deparatorFunction
		);
	}

	function html() {
		$action = 'build';
		$functions['functions'] = array( 
			'homepage' => array(),
			'productpage' => array(),
			'categorypage' => array(),
			'brandpage' => array(),
			'categoriespage' => array(),
			'brandspage' => array(),
			'staticpage' => array(),
			'files' => array(),
			'all' => array()
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

		$getDetailAction = 'getdetail';
		$getDetailFunctions['functions'] = array( '*' => array( 'merchant' ) );
		return array( 
			$getAction => $getFunctions, 
			$delAction => $delFunction,
			$getDetailAction => $getDetailFunctions
		);
	}

	function hostnine() {
		$accountAction = 'accounts';
		$accountFunction['functions'] = array(
			'get' => array( 'account', 'domain' ),
			'create' => array( 'account', 'domain', 'username', 'password', 'location', 'package' ),
			'view' => array(),
			'terminate' => array( 'account', 'domain' )
		);

		$locationAction = 'locations';
		$locationFunction['functions'] = array( 'get' => array() );

		$packageAction = 'packages';
		$packageFunction['functions'] = array( 'get' => array( 'account' ) );

		return array(
			$accountAction => $accountFunction,
			$locationAction => $locationFunction,
			$packageAction => $packageFunction
		);
	}

	function statcounter() {
		$action = 'project';
		$functions['functions'] = array( 'add' => array() );
		return array( $action => $functions );
	}

	function ftp() {
		$uploadAction = 'action';
		$uploadFunctions['functions'] = array( 'upload' => array() );
		return array( $uploadAction => $uploadFunctions );
	}
}
