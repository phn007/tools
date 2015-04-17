<?php

use webtools\controller;

class HostController extends Controller {

	/**
	 * Options
	 * --row=2
	 * --row=2-4
	 */

	function hostnine( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$model = $this->model( 'host' );

		//php host hostnine terminate delete-accounts
		if ( $function == 'terminate' ) {
			$model->hostnineTerminateAccounts( $csvFilename, $options );
		}

		//php host hostnine create AllNew
		if ( $function == 'create' ) {
			$model->hostnineCreateAccounts( $csvFilename, $options );
		}

		//php host hostnine modify liverev
		// --quota=2000
		// -- bandwidth=15000
		if ( $function == 'modify' ) {
			$model->hostnineModifyAccounts( $csvFilename, $options );
		}
	}
}