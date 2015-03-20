<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_BASE_PATH . 'libs/ftpClient.php';

class FtpModel extends Controller {
	use HostConfig;
	use UploadSiteZip;
	use RunUnzip;

	private $root = 'public_html';

	function upload( $options, $siteConfigData ) {
		$hostData = $this->getHostConfigData ( $options );

		//$end = 2;
		$i = 1;
		foreach ( $siteConfigData as $domain => $data ) {
			$ftp = new FTPClient();
			$this->connectAndLogin( $ftp, $domain, $hostData );
			$this->uploadUnzipScript( $ftp );
			$this->uploadSiteZipFormat( $ftp, $data );
			$this->displayLogMessage( $ftp, $domain );
			$result = $this->runUnzip( $data );
			$this->displayListOfFilesInDirectory( $ftp );
			echo "\n";
			
			$i++;
			//if ( $i == $end ) break;	
		}

		// $j = 1;
		// foreach ( $siteConfigData as $data ) {
		// 	$result = $this->runUnzip( $data );
		// 	print_r( $result );

		// 	$j++;
		// 	//if ( $j == $end ) break;
		// }


	}

	function displayLogMessage( $ftp, $domain ) {
		echo "-------------------------------------\n";
		echo "Uploading Files to " . $domain . "\n";
		echo "-------------------------------------\n";
		foreach ( $ftp->getMessages() as $line ) {
			echo $line;
			echo "\n";
		}	
	}
}

trait RunUnzip {
	function runUnzip( $data ) {
        $url = $data['domain'] . '/unzip.php';
        $filename = $data['site_dir'] . '.zip';
        $openPath = './' . $filename;
        $targetPath = './';
        $postData[] = array( 'open_path' => $openPath, 'target_path' => $targetPath );
        $this->unzipCurl( $url, $postData );
	}

	function unzipCurl( $url, $postData ) {
		$postData = http_build_query( $postData );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postData );
        $result = curl_exec( $ch) ;
        curl_close( $ch );
        return $result;
	}
}

trait UploadSiteZip {
	function connectAndLogin( $ftp, $domain, $hostData ) {
		$ftp->host = 'ftp.' . $domain;
		$ftp->user = $hostData[$domain]['username'];
		$ftp->pass = $hostData[$domain]['password'];
		if ( !$ftp->connect() ) die( 'Failed to connect' );
	}

	function uploadSiteZipFormat( $ftp, $data ) {
		$filename = $data['site_dir'] . '.zip';
		$fileFrom = TEXTSITE_PATH . $data['project'] . '/' . $filename;
		$fileTo = $this->root . '/' . $filename;
		$ftp->uploadFile( $fileFrom, $fileTo );
	}

	function uploadUnzipScript( $ftp ) {
		$filename = 'unzip.php';
		$fileFrom = FILES_PATH . 'scripts/' . $filename;
		$fileTo = $fileTo = $this->root . '/' . $filename;
		$ftp->uploadFile( $fileFrom, $fileTo );
	}

	function displayListOfFilesInDirectory( $ftp ) {
		$ftp->changeDir( $this->root );
		$files = $ftp->getDirListing();

		echo "----------\n";
		echo "Files List\n";
		echo "----------\n";
		foreach ( $files as $file ) {
			echo $file;
			echo "\n";
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