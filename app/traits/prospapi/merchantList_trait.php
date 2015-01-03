<?php
trait MerchantList {
	function setMerchantList( $options ) {
		$filename = $this->getConfigFileName( $options );
		$conf = $this->component( 'config' );
		$conf->getConfigData( $filename );
		$conf->network = 'prosperent-api';

		foreach ( $conf->getMerchants() as $merchant ) {
			$data[$merchant] = array( 
				'project' =>  $conf->createProjectName( $filename ), 
				'dbName' => $conf->convertMerchantToDbName( $merchant ),
				'clearDbStatus' => $conf->getClearDatabaseStatus()
			);
		}	
		return $data;	
	}

	function getConfigFileName( $options ) {
		$filename = null;
		if ( isset( $options['config'] ) )
			$filename = $options['config'];
		return $filename;
	}
}