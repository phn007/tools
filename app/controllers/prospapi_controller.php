<?php
use webtools\controller;
include WT_APP_PATH . 'traits/setupConfig_trait.php';

/**
 * Example commandline
 * -------------------
 * php prospapi -c bb-prosp get all
 */
class ProspApiController extends Controller {
	use SetupConfig;

	function get( $function, $params, $options ) {
		//setupConfig_trait.php
		$this->initIniConfig( $options );
		$this->initCsvConfig( $options );

		$merchants = $this->getMerchantData();
		$model = $this->model( 'prospapi' );
		$model->connectDatabase();


		die();

		foreach ( $merchants as $merchant => $data ) {
			$dbName = $data['db_name'];
			$clearStatus = $data['clear_db'];
			$model->clearDatabase( $dbName, $clearStatus );
			$model->createDatabase( $dbName );
			$model->project = $data['project'];

			if ( 'category' == $function || 'all' == $function ) 
				$model->getCategories( $merchant );
			if ( 'brand' == $function || 'all' == $function ) 
				$model->getBrands( $merchant );
			if ( 'product' == $function || 'all' == $function ) 
				$model->getProducts();
		}
	}
}