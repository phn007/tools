<?php
use webtools\controller;
include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigData_trait.php';
include WT_APP_PATH . 'traits/setupConfigV3_trait.php';

class FtpController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;

	/**
	 * php ftp -c bb-prosp action upload
	 */
	function action( $function, $params, $options ) {
		$initConfigData = $this->initialConfigDataFromCsvFile( $options );

		$model = $this->model( 'ftp' );
		foreach ( $initConfigData as $dotIniFilename => $csvData ) {
			$this->initialDotINIConfigFile( $dotIniFilename, $options );
			$configData = $this->getConfigData( $csvData );
			if ( $function == 'upload' ) $model->upload( $options, $configData, $csvData );
		}	
	}
}