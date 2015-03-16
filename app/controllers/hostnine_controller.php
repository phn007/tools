<?php
use webtools\controller;
include WT_APP_PATH . 'traits/setupConfig_trait.php';

class HostnineController extends Controller {


	/**
	 *  php hostnine accounts get maxcom all
	 *  php hostnine accounts get maxcom domain.com
	 *
	 * options
	 * -f --csvfile
	 * -s --sort domain,rtotal_disk etc...
	 * -a --ascending
	 * -d --descending
	 *
	 * php hostnine accounts terminate maxcom domain.com
	 * php hostnine -f delete-account.csv accounts terminate
	 *
	 * php hostnine -f create-account.csv accouonts create
	 * php hostnine accounts create maxcom domain.com username password "location" package
	 */
	function accounts( $function, $params, $options ) {
		$model = $this->model( 'hostnine/accounts' );
		if ( $function == 'get' ) $model->get( $params, $options );
		if ( $function == 'create' ) $model->create( $params, $options );
		if ( $function == 'terminate' ) $model->terminate( $params, $options );

		// if ( $function == 'view' )

	}

	function myAccount( $function, $params, $options ) {

	}

	/**
	 * php hostnine locations get
	 */
	function locations( $function, $params, $options ) {
		$model = $this->model( 'hostnine/locations' );
		$model->get();
	}

	/**
	 * php hostnine packages get maxcom                                                                                                            
	 */
	function packages( $function, $params, $options ) {
		$model = $this->model( 'hostnine/packages' );
		$model->get( $params['account'] );
	}
}


