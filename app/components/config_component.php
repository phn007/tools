<?php
use webtools\controller;
use webtools\libs\Helper;

class ConfigComponent extends Controller {
	private $conf;
	private $projectName;
	private $network;
	private $apiKey;
	private $siteCategory;
	private $hostname;
	private $clearDbStatus;
	private $domains;
	private $siteNumber;
	private $merchants;
	private $siteType;
	private $siteDirNames;

	function __set( $name, $value ) {
      	$this->{$name} = $value;
 	}

   	function __get( $name ) {
      	return $this->{$name};
   	}
	
	function getConfigData( $filename ) { 
		$this->conf = new Config_Lite( CONFIG_PATH . $filename ); 
	}
	
	function getSiteConfigData() {
		$siteConfig = $this->conf->get( 'site_config' );
		return $this->setSiteConfigGroupByDomainName( $siteConfig );
	}
	
	function setSiteConfigGroupByDomainName( $siteConfig ) {
		$data = null;
		foreach ( $siteConfig['domain'] as $keyNumber => $domain ) {
			$data[$domain] = $this->setConfigGroup( $siteConfig, $keyNumber );
		}
		return $data;
	}

	private function setConfigGroup( $siteConfig, $keyNumber ) {
		$configKeys = array_keys( $siteConfig );
		foreach ( $configKeys as $name ) {
			$data[$name] = $siteConfig[$name][$keyNumber];
			$data['site_type']     = $this->siteType;
			$data['project']       = $this->projectName;
			$data['network']       = $this->network;
			$data['api_key']       = $this->apiKey;
			$data['hostname']      = $this->hostname;
			$data['site_category'] = $this->siteCategory;
		}
		return $data;
	}
	
	function getMerchantData() {
        foreach ( $this->merchants as $merchant ) {
            $data[ $merchant ] = array(
               'project' => $this->projectName,
               'db_name' => $this->convertMerchantToDbName( $merchant ),
               'network' => $this->network,
               'clear_db' => $this->clearDbStatus,
            );
        }
		return $data;
	}
	
	function initialVariables( $filename ) {	
		$this->getConfigData( $filename );
		$this->projectName = $this->createProjectName( $filename );
        $this->network = $this->getNetwork();
        $this->apiKey = $this->getApiKey();
        $this->siteType = $this->getSiteType();
        $this->siteCategory = $this->getSiteCategory();
        $this->hostname = $this->getHostname();
        $this->clearDbStatus = $this->getClearDatabaseStatus();
        $this->domains = $this->getDomains();
        $this->siteNumber = $this->getSiteNumber();
        $this->merchants = $this->getMerchants();
		$this->siteDirNames = $this->getSiteDirNames();
	}

	function convertMerchantToDbName( $merchant ) {
		//แปลงชื่อ merchant ให้เป็นชื่อ database
      	$dbName = Helper::clean_string( $merchant );
		if ( $this->network == 'viglink' )
			$dbName = 'vl_' . str_replace( '-', '_', $dbName );
		if ( $this->network == 'prosperent-api' )
      		$dbName = 'prosp_' . str_replace( '-', '_', $dbName );
		return $dbName;
	}
	
	function createProjectName( $filename ) {
      $arr = explode( '/', $filename );
      return str_replace( '.ini', '', end( $arr ) );
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
		return count( $this->domains );
	}

	function getMerchants() {
		return $this->conf->get( 'merchant' );
	}

	function getSiteDirNames() {
		return $this->conf->get( 'site_config', 'site_dir_name' );
	}
}