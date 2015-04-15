<?php

use webtools\controller;

class HostController extends Controller {

	/**
	 * --row=2
	 * --row=2-4
	 * php host hostnine terminate delete-accounts
	 * php host hostnine create AllNew
	 */

	function hostnine( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$model = $this->model( 'host' );

		if ( $function == 'terminate' ) {
			$model->hostnineTerminateAccounts( $csvFilename, $options );
		}

		if ( $function == 'create' ) {
			$model->hostnineCreateAccounts( $csvFilename, $options );
		}
	}
}