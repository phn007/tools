<?php
use webtools\controller;

include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';

class SiteController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;
	
	/**
	 * -z zip files
	 *
	 * Create all functions
	 * php site create all rexce
	 * ========================================
	 * Create TextDb
	 * php site create textdb rexce
	 * php site --module=product create textdb rexce
	 * php site --module=category create textdb rexce
	 * php site --module=homepagecat create textdb rexce
	 * ========================================
	 * Create TextSite
	 * php site create textsite rexce
	 * php site --row=2-4 --module=code create textsite rexce
	 * php site --row=2-4 --module=config create textsite rexce
	 * 
	 *
	 */
	function create( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$initConfigData = $this->initialConfigDataFromCsvFile( $csvFilename, $options );
		$model = $this->model( 'site' );

		if ( $function == 'textdb' || $function == 'all' ) {
			$module = isset( $options['module'] ) ? $options['module'] : 'allTextDb';
			$model->textDb( $module, $initConfigData, $csvFilename );
		}

		if ( array_key_exists('zip', $options ) ) 
			$zip = 'zip';
		else
			$zip = null;

		if ( $function == 'textsite' || $function == 'all' ) {
			$module = isset( $options['module'] ) ? $options['module'] : 'siteall';
			$model->textsite( $module, $initConfigData, $csvFilename, $zip );
		}

		echo "\n";
	}
}