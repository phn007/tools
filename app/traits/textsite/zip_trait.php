<?php
include WT_BASE_PATH . 'libs/zip.php';

trait ZipFiles {
	function runZipFiles( $method ) {
		$source = $this->getZipSourcePath();
		$destination = $this->getZipDestinationPath();
		chdir( $source );
		if ( $method == 'php' )
			zipData( '.', $destination );

		if ( $method == 'shell' )
			$this->zipDataByShellCommand( $destination );

		// echo $source . "\n";
		// echo $destination . "\n";
	}

	function zipDataByShellCommand( $destination ) {
		$command = 'zip -r ' . $destination . ' ' . './*';
		echo shell_exec( $command );
		
	}

	function getZipSourcePath() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'];
	}

	function getZipDestinationPath() {
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['site_dir'] . '.zip';
	}
}