<?php
use webtools\controller;
class ProspApiController extends Controller {

	function get( $function, $params, $options ) {
		$model = $this->model( 'prospapi' );
		$merchants = $model->getMerchantList( $options );
		$model->connectDatabase();

		foreach ( $merchants as $merchant => $data ) {
			$dbName = $data['dbName'];
			$clearStatus = $data['clearDbStatus'];
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