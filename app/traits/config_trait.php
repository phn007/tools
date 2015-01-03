<?php
trait SetupConfig
{
	private $configCom;
	
	function merchantData() { 
		return $this->configCom->getMerchantData(); 
	}

	function projectName() { 
		return $this->configCom->projectName; 
	}

	function siteNumber() { 
		return $this->configCom->siteNumber; 
	}

	function siteDirNames() { 
		return $this->configCom->siteDirNames; 
	}
	
	function initialSetupConfig( $options ) {
		$this->configCom = $this->component( 'config' );
		$filename = $this->getConfigFileName( $options );
		$this->configCom->initialVariables( $filename );
	}
	
	function siteConfigData() {
		return $this->configCom->getSiteConfigData();
	}
	
	function getConfigFileName( $options ) {
		$filename = null;
		if ( isset( $options['config'] ) )
			$filename = $options['config'];
		return $filename;
	}
}
