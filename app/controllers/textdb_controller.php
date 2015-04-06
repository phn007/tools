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
	 */

	function create($function, $params, $options) {
		$csvFilename = $params['csvFilename'];
		$iniFilename = $params['iniFilename'];
		$module = $options['module'];

		$initConfigData = $this->initialConfigDataFromCsvFile( $csvFilename, $options );
		$csvData = $initConfigData[$iniFilename];
		$this->initialDotINIConfigFile( $iniFilename, $csvFilename );

		if ( $module == 'product' || $module == 'allTextDb' ) {
			$projectName    = $this->project;
			$merchantData   = $this->getMerchantData( $csvData );
			$siteNumber     = $this->getSiteNumber( $csvData );
			$siteDirNames   = $this->getSiteDirNames( $csvData );	

			$productModel = $this->model( 'textdb/textdbProducts' );
			$productModel->create( $projectName, $dotIniFilename, $merchantData, $siteNumber, $siteDirNames );
		}

		if ( $module == 'category' || $module == 'homepagecat' || $module == 'allTextDb' ) {
			$configData = $this->getConfigData( $csvData );
		}

		if ( $module == 'category' | $module == 'allTextDb' ) {
			$categoryModel = $this->model( 'textdb/textdbCategories' );
			$categoryModel->create( $configData );
		}

		if ( $module == 'homepagecat' || $module == 'allTextDb' ) {
			$categoryListModel = $this->model( 'textdb/categoryListForHomepage' );
			$categoryListModel->create( $configData );
		}
		echo "\n";
	}
}