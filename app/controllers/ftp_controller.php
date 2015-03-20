<?php
use webtools\controller;
include WT_APP_PATH . 'traits/setupConfig_trait.php';

class FtpController extends Controller {
	use SetupConfig;

	/**
	 * php ftp -c bb-prosp action upload
	 */
	function action( $function, $params, $options ) {
		$this->initIniConfig( $options );
		$this->initCsvConfig( $options );
		$this->iniStatConfig( $options );
		$siteConfigData = $this->getSiteConfigData();

		$model = $this->model( 'ftp' );
		if ( $function == 'upload' ) $model->upload( $options, $siteConfigData );
	}
}