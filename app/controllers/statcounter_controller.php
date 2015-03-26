<?php
use webtools\controller;

class StatcounterController extends Controller {
	/**
	 * php statcounter -c project-list project add
	 */
	function project( $function, $params, $options ) {
		$model = $this->model( 'statcounter' );
		if ( $function == 'add' ) $model->add( $options );
	}

}


