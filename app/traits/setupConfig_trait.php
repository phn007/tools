<?php
use webtools\libs\Helper;
use webtools\libs\Network;

trait SetupConfig {
	use ReadConfigFile;

	private $filename;

	function initialSetupConfig( $options ) {
		$this->filename = $this->getConfigFileName( $options );
		$this->readConfigFile();
	}

	function getConfigFileName( $options ) {
		$filename = null;
		if ( isset( $options['config'] ) )
			$filename = $options['config'];
		return $filename;
	}
	
	function getMerchantData() {
        foreach ( $this->getMerchants() as $merchant ) {
            $data[ $merchant ] = array(
               'project' => $this->getProjectName(),
               'db_name' => $this->convertMerchantToDbName( $merchant ),
               'network' => $this->getNetwork(),
               'clear_db' => $this->getClearDatabaseStatus(),
            );
        }
		return $data;
	}

	function convertMerchantToDbName( $merchant ) {
		//แปลงชื่อ merchant ให้เป็นชื่อ database
      	$dbName = Helper::clean_string( $merchant );
      	$dbName = Network::setDatabaseNameForEachNetwork( $this->getNetwork(), $dbName );
      	return $dbName;
	}

	function getProjectName() {
      $arr = explode( '/', $this->filename );
      return str_replace( '.ini', '', end( $arr ) );
    }
}

trait ReadConfigFile {

	private $conf;

	function readConfigFile() { 
		$this->conf = new Config_Lite( CONFIG_PATH . $this->filename ); 
	}

	function getNetwork() {
		return $this->conf->get( null, 'network' );
	}

	function getApiKey() {
		return $this->conf->get( null, 'api_key' );
	}

	function getSiteType() {
		return $this->conf->get( null, 'site_type' );
	}

	function getSiteCategory() {
		return $this->conf->get( null, 'site_category' );
	}

	function getHostname() {
		return $this->conf->get( null, 'hostname' );
	}

	function getClearDatabaseStatus() {
		return $this->conf->get( null, 'clear_database' );
	}

	function getDomains() {
		return $this->conf->get( 'site_config', 'domain' );
	}

	function getSiteNumber() {
		return count( $this->getDomains() );
	}

	function getMerchants() {
		return $this->conf->get( 'merchant' );
	}

	function getSiteDirNames() {
		return $this->conf->get( 'site_config', 'site_dir' );
	}

	function getSiteConfigData() {
		$siteConfig = $this->conf->get( 'site_config' );
		return $this->setSiteConfigGroupByDomainName( $siteConfig );
	}

	function setSiteConfigGroupByDomainName( $siteConfig ) {
		$data = null;
		foreach ( $siteConfig['domain'] as $keyNumber => $domain )
			$data[$domain] = $this->setConfigGroup( $siteConfig, $keyNumber );
		return $data;
	}

	private function setConfigGroup( $siteConfig, $keyNumber ) {
		$configKeys = array_keys( $siteConfig );
		foreach ( $configKeys as $name ) {
			$data[$name] = $siteConfig[$name][$keyNumber];
			$data['site_type']     = $this->getSiteType();
			$data['project']       = $this->getProjectName();
			$data['network']       = $this->getNetwork();
			$data['api_key']       = $this->getApiKey();
			$data['hostname']      = $this->getHostname();
			$data['site_category'] = $this->getSiteCategory();
		}
		return $data;
	}
}
