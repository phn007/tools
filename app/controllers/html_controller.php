<?php
use webtools\controller;
include WT_APP_PATH . 'traits/setupConfig_trait.php';

class HtmlController extends Controller {
	use SetupConfig;

	function build( $function, $params, $options ) {
		//SetupConfig Trait
		$this->initialSetupConfig( $options );
		$siteConfigData = $this->getSiteConfigData();
		$model = $this->model('html');
		
		foreach ( $siteConfigData as $config ) { 
			$model->initialVariables( $config );
			if ( 'productpage' == $function ) $model->productPage();
			if ( 'homepage' == $function ) $model->homePage();
			if ( 'assets' == $function ) $model->assets();
		}
	}
}