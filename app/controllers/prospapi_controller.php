<?php
use webtools\controller;
include WT_APP_PATH . 'traits/config_trait.php';

class ProspApiController extends Controller {


	function get( $function, $params, $options ) {

		//SetupConfig Trait
		$this->initialSetupConfig( $options );
		$siteConfigData = $this->siteConfigData();
				
		foreach ( $siteConfigData as $config ) { 
			print_r( $config );	
		}
	}
}