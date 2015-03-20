<?php
use webtools\controller;
include WT_APP_PATH . 'traits/setupConfig_trait.php';

/**
 * Example commandline
 * -------------------
 * php html -c bb-prosp.ini build all
 */
class HtmlController extends Controller {
	use SetupConfig;

	function build( $function, $params, $options ) {
		$this->initIniConfig( $options );
		$this->initCsvConfig( $options );
		$this->iniStatConfig( $options );
		$siteConfigData = $this->getSiteConfigData(); //SetupConfig Trait
		$model = $this->model('html');
		foreach ( $siteConfigData as $config ) { 
			$model->initialVariables( $config );
			if ( 'all' == $function || 'files' == $function ) $model->files();
			if ( 'all' == $function || 'homepage' == $function ) $model->homePage();
			if ( 'all' == $function || 'categoriespage' == $function ) $model->categoriesPage();
			if ( 'all' == $function || 'brandspage' == $function ) $model->brandsPage();
			if ( 'all' == $function || 'staticpage' == $function ) $model->staticPage();
			if ( 'all' == $function || 'categorypage' == $function ) $model->categoryPage();
			if ( 'all' == $function || 'brandpage' == $function ) $model->brandPage();
			if ( 'all' == $function || 'productpage' == $function ) $model->productPage();
		}
	}
}