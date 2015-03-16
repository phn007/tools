<?php
use webtools\controller;
include WT_APP_PATH . 'traits/setupConfig_trait.php';

class StatcounterController extends Controller {

	/**
	 * php statcounter -f project-list.csv project add
	 */
	function project( $function, $params, $options ) {
		$model = $this->model( 'statcounter' );
		if ( $function == 'add' ) $model->add( $options );
	}

}

