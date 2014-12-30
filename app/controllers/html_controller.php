<?php
use webtools\controller;
include WT_APP_PATH . 'traits/config_trait.php';
include WT_APP_PATH . 'traits/siteInfo_trait.php';

class HtmlController extends Controller {
	
	use SetupConfig;
	use SiteInfomation;

	function build( $function, $params, $options ) {

		//SetupConfig Trait
		$this->initialSetupConfig( $options );
		$siteConfigData = $this->siteConfigData();
		$model = $this->model('html');
		
		foreach ( $siteConfigData as $config ) { 
			$model->initialVariables( $config );

			if ( 'productpage' == $function ) $model->productPage();
			if ( 'homepage' == $function ) $model->homePage();
			if ( 'assets' == $function ) $model->assets();
		}
	}
}