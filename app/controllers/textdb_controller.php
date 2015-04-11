<?php
use webtools\controller;

include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';

class TextdbController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;

	/**
	 * Create TextDb
	 * php textdb create db rexce group1
	 * php textdb --module=product create db rexce group1
	 * php textdb --module=category create db rexce group1
	 * php textdb --module=homepagecat create db rexce group1
	 *
	 * php controller action function csvFilename configName
	 * php textdb create all rexce group1
	 * php textdb create product rexce group1
	 * php textdb create category rexce group1
	 * php textdb create homepagecat rexce group1	 
	 */

	function create($function, $params, $options) {
		$csvFilename = $params['csvFilename'];
		$iniFilename = $params['iniFilename'];
		//$module = $options['module'];


		// echo $csvFilename;
		// echo "\n";
		// echo $iniFilename;
		// echo "\n";
		// echo $function;
		// echo "\n";
		// die();

		$initConfigData = $this->initialConfigDataFromCsvFile( $csvFilename, $options );
		$csvData = $initConfigData[$iniFilename];
		$this->initialDotINIConfigFile( $iniFilename, $csvFilename );

		if ( $function == 'product' || $function == 'all' ) {
			$projectName    = $this->project;
			$merchantData   = $this->getMerchantData( $csvData );
			$siteNumber     = $this->getSiteNumber( $csvData );
			$siteDirNames   = $this->getSiteDirNames( $csvData );	

			$productModel = $this->model( 'textdb/textdbProducts' );
			$productModel->create( $projectName, $iniFilename, $merchantData, $siteNumber, $siteDirNames );
		}

		if ( $function == 'category' || $function == 'homepagecat' || $function == 'all' ) {
			$configData = $this->getConfigData( $csvData );
		}

		if ( $function == 'category' | $function == 'all' ) {
			$categoryModel = $this->model( 'textdb/textdbCategories' );
			$categoryModel->create( $configData );
		}

		if ( $function == 'homepagecat' || $function == 'all' ) {
			$categoryListModel = $this->model( 'textdb/categoryListForHomepage' );
			$categoryListModel->create( $configData );
		}
		echo "\n";
	}
}