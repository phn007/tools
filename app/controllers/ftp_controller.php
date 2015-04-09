<?php
use webtools\controller;
include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';

class FtpController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;

	/**
	 * php ftp action upload bb-prosp
	 * php ftp action --row=2 upload bb-prosp
	 */
	function action( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$initConfigData = $this->initialConfigDataFromCsvFile( $csvFilename, $options );

		$model = $this->model( 'ftp' );
		foreach ( $initConfigData as $iniFilename => $csvData ) {
			$this->initialDotINIConfigFile( $iniFilename, $csvFilename );
			$configData = $this->getConfigData( $csvData );
			if ( $function == 'upload' ) $model->upload( $configData, $csvData );
		}	
	}
}