<?php
use webtools\controller;
include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigData_trait.php';
include WT_APP_PATH . 'traits/setupConfigV3_trait.php';

/**
 * Example commandline
 * -------------------
 * php html -c bb-prosp.ini build all
 */
class HtmlController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;

	function build( $function, $params, $options ) {
		$initConfigData = $this->initialConfigDataFromCsvFile( $options );

		foreach ( $initConfigData as $dotIniFilename => $csvData ) {
			$this->initialDotINIConfigFile( $dotIniFilename, $options );
			$siteConfigData = $this->getSiteConfigData( $csvData, $options );
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
}