<?php
use webtools\controller;

include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';


class SiteController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;
	
	/**
	 * Create TextDb
	 * php site create textdb rexce
	 * php site create textdb rexce product
	 * php site create textdb rexce category
	 * php site create textdb rexce homepagecat
	 * ========================================
	 * Create TextSite
	 *
	 *
	 */
	function create( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$module = isset( $options['module'] ) ? $options['module'] : 'allTextDb';
		$initConfigData = $this->initialConfigDataFromCsvFile( $csvFilename, $options );
		$model = $this->model( 'site' );
		if ( $function == 'textdb' || $function == 'all' ) {
			if ( $module == 'product' || $module == 'allTextDb' ) 
				$model->textDbProduct( $module, $initConfigData, $csvFilename );
			if ( $module == 'category' || $module == 'allTextDb' ) 
				$model->textDbCategory( $module, $initConfigData, $csvFilename );
			if ( $module == 'homepagecat' || $module == 'allTextDb' ) 
				$model->textDbHomePageCategory( $module, $initConfigData, $csvFilename );
		}

		if ( $function == 'textsite' || $function == 'all' ) {

		}

		echo "\n";
	}
}