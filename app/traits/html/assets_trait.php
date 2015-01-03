<?php
trait Assets {

	function buildAssets() {
		$cloneCom = $this->component( 'clone' );
		$source = $this->setSourcePath();
		$destination = $this->setDestinationPath();
		$specificFiles = array();
		$mode = 'fullMode';
		$cloneCom->runClone( $source, $destination, $specificFiles, $mode );
	}

	function setSourcePath() {
		return $this->sourceDir . 'app/views/' . $this->config['theme_name'] . '/assets/';
	}

	function setDestinationPath() {
		return $this->sourceDir . 'build/app/views/' . $this->config['theme_name'] . '/assets/';;
	}
}