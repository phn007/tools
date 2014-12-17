<?php
trait SetupConfig
{
	private $confModel;
	
	function merchantData() { return $this->confModel->getMerchantData(); }
	function projectName() { return $this->confModel->getProjectName(); }
	function siteNumber() { return $this->confModel->getSiteNumber(); }
	function siteDirNames() { return $this->confModel->getSiteDirNames(); }
	
	function initialSetupConfig( $options )
	{
		$this->confModel = $this->model( 'configV2' );
		$filename = $this->getConfigFileName( $options );
		$this->confModel->initialVariables( $filename );
	}
	
	function siteConfigData()
	{
		return $this->confModel->getSiteConfigData();
	}
	
	function getConfigFileName( $options )
	{
		$filename = null;
		if ( isset( $options['config'] ) )
			$filename = $options['config'];
		return $filename;
	}
}
