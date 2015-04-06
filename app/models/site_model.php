<?php
use webtools\controller;
use webtools\libs\Helper;

class SiteModel {
	use TextDbCommandLine;

	function textDbProduct( $module, $initConfigData, $csvFilename ) {
		$this->textDbCommandLine( $module, $initConfigData, $csvFilename );
	}

	function textDbCategory( $module, $initConfigData, $csvFilename ) {
		$this->textDbCommandLine( $module, $initConfigData, $csvFilename );
	}

	function textDbHomepageCategory( $module, $initConfigData, $csvFilename ) {
		$this->textDbCommandLine( $module, $initConfigData, $csvFilename );
	}
}


trait TextDbCommandLine {
	function textDbCommandLine( $module, $initConfigData, $csvFilename ) {
		$groups = array_keys( $initConfigData );
		foreach ( $groups as $iniFilename) {	
			$command = 'php textdb --module='. $module .' create db ' . $csvFilename . ' ' . $iniFilename;
			echo shell_exec( $command );
		}
	}
}