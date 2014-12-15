<?php
use webtools\controller;
include WT_APP_PATH . 'traits/config_trait.php';

class WebsiteController extends Controller
{
	use SetupConfig;
	
	function create( $function, $params, $options )
	{
		//SetupConfig Trait
		$this->initialSetupConfig( $options );
		$merchantData = $this->merchantData();
		$siteNumber   = $this->siteNumber();
		$projectName  = $this->projectName();
	}
	
}