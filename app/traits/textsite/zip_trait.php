<?php
include WT_BASE_PATH . 'libs/zip.php';

trait ZipFiles {
	function runZipFiles() {
		$source = $this->getZipSourcePath();
		$destination = $this->getZipDestinationPath();
		chdir( $source );
		zipData( '.', $destination );
		//$this->zipDataByShellCommand( $destination );
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