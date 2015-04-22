<?php
use webtools\controller;

include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';

class SiteController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;
	
	/**
	 * --row=2-4
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
	 * Create Zip File
	 * default method option = php
	 * php site --row=2-4 create zip csvfilename 
	 * php site --row=2-4 --method=shell zip csvfilename
	 */
	function create( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$initConfigData = $this->initialConfigDataFromCsvFile( $csvFilename, $options );
		$model = $this->model( 'site' );

		if ( $function == 'textdb' || $function == 'all' ) {
			$module = isset( $options['module'] ) ? $options['module'] : 'allTextDb';
			$row = isset( $options['row'] ) ? $options['row'] : null;
			$model->textDb( $module, $initConfigData, $csvFilename, $row );
		}

		if ( $function == 'textsite' || $function == 'all' ) {
			$zip = null;
			if ( array_key_exists('zip', $options ) ) $zip = 'zip';
				
			$module = isset( $options['module'] ) ? $options['module'] : 'siteall';
			$model->textsite( $module, $initConfigData, $csvFilename, $zip );
		}

		if ( $function == 'zip' ) {
			$model->zipTextsite( $initConfigData, $csvFilename, $options );
		}

		echo "\n";
	}

	/**
	 * php site --row=2-4 ftp upload csvFilename
	 */
	function ftp( $function, $params, $options ) {
		if ( $function == 'upload' ) {

			$csvFilename = $params['csvFilename'];
			$initConfigData = $this->initialConfigDataFromCsvFileForFtp( $csvFilename, $options );

			//php ftp upload csvFilename domain
			foreach ( $initConfigData as $csvData ) {
				$domain = $csvData['domain']; 
				$cmd = 'php ftp upload ' . $csvFilename . ' ' . $domain;
				echo shell_exec( $cmd );
			}
		}
	}
}