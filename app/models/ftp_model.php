<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_BASE_PATH . 'libs/ftpClient.php';

class FtpModel extends Controller {
	use HostConfig;
	private $root = 'public_html';

	function upload( $options, $siteConfigData ) {
		$hostData = $this->getHostConfigData ( $options );
		foreach ( $siteConfigData as $domain => $data ) {
			$ftp = new FTPClient();
			$ftp->host = 'ftp.' . $domain;
			$ftp->user = $hostData[$domain]['username'];
			$ftp->pass = $hostData[$domain]['password'];
			$filename = $data['site_dir'] . '.zip';
			$fileFrom = TEXTSITE_PATH . $data['project'] . '/' . $filename;
			$fileTo = $this->root . '/' . $filename;

			if ( !$ftp->connect() ) die( 'Failed to connect' );
			$ftp->uploadFile( $fileFrom, $fileTo );
			$ftp->changeDir( $this->root );
			$files = $ftp->getDirListing();
			print_r( $files );
			print_r( $ftp->getMessages() );			
		}
	}
}

trait HostConfig {
	function getHostConfigData ( $options ) {
		$hostConfigPath = $this->getHostConfigPath( $options );
		$hostConfigData = $this->getHostDataFromCsvFile( $hostConfigPath );
		foreach ( $hostConfigData as $data ) {
			$hostDataGroup[ $data['domain'] ] = $data;
		}	
		return $hostDataGroup;
	}

	function getHostDataFromCsvFile( $hostConfigPath ) {
		$csv = new CSVReader();
		$csv->useHeaderAsIndex();
		return $csv->data( $hostConfigPath );
	}

	function getHostConfigPath( $options ) {
		return CONFIG_PATH . $options['config'] . '-hostnine.csv';
	}

}