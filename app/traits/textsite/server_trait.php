<?php
trait Server {
	function runServer( $siteConfigData ) {
		foreach ( $siteConfigData as $key => $config ) {
			$siteType = $config['site_type'];
			$project  = $config['project'];
			$siteDir  =  $config['site_dir'];
			$hostname = str_replace( 'http://', '', $key );
			$path = dirname( WT_BASE_PATH ) . '/' . $siteType . '/' . $project . '/' . $siteDir;
			shell_exec( 'php -S ' . $hostname . ' -t ' . $path . ' ' . $path . '/r.php' );
			die();
		}
	}
}