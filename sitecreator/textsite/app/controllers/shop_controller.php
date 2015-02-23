<?php
class ShopController extends Controller {
	public function index( $params ) {
		$model = $this->model( 'shop' );
		echo $url = $model->getUrl( $params );

		//Redirect to Merchant
		//header( "location: " . $url );
		exit(0);
	}

}
