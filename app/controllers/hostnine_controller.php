<?php
use webtools\controller;

class HostnineController extends Controller {


	/**
	 *  php hostnine accounts get maxcom all
	 *  php hostnine accounts get maxcom domain.com
	 *
	 * options
	 * -c --csvfile config
	 * --row row of csv file
	 * -s --sort domain,rtotal_disk etc...
	 * -a --ascending
	 * -d --descending
	 *
	 * php hostnine accounts terminate maxcom domain.com
	 * php hostnine -c delete-account.csv accounts terminate
	 *
	 * php hostnine -c create-account.csv accouonts create
	 * php hostnine accounts create maxcom domain.com username password "location" package
	 */
	function accounts( $function, $params, $options ) {
		$model = $this->model( 'hostnine/accounts' );
		if ( $function == 'get' ) $model->get( $params, $options );
		if ( $function == 'create' ) $model->create( $params, $options );
		if ( $function == 'terminate' ) $model->terminate( $params, $options );
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


