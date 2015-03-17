<?php
include WT_BASE_PATH . 'libs/csvReader.php';

use webtools\libs\Helper;
use webtools\libs\Network;

trait SetupConfig {
	use ReadConfigFile;
	use ReadSiteConfig;
	use ReadSiteConfigFromCsv;

	private $iniFilename;
	private $csvFilename;

	function initialSetupConfig( $options ) {
		$this->iniFilename = $this->getConfigFileName( $options );
		$this->readConfigFile();

		$this->csvFilename = $this->getCsvFilename( $options );
		$this->readCsvFile();
	}

	function getCsvFilename( $options ) {
		return $options['config'] . '.csv';
	}

	function getConfigFileName( $options ) {
		return $options['config']  . '.ini';
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
      	$dbName = Network::setDatabaseNameForEachNetwork( $this->getNetwork(), $dbName ); //libs/neworkSupport.class.php
      	return $dbName;
	}

	function getProjectName() {
      $arr = explode( '/', $this->iniFilename );
      return str_replace( '.ini', '', end( $arr ) );
    }
}

trait ReadConfigFile {
	private $conf;

	function readConfigFile() { 
		$this->conf = new Config_Lite( CONFIG_PATH . $this->iniFilename ); 
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

	function getMerchants() {
		return $this->conf->get( 'merchant' );
	}
}

trait ReadSiteConfigFromCsv {
	function readCsvFile() {
		$path = $this->getCsvPath( $this->csvFilename );
		$csv = new CSVReader();
		$csv->useHeaderAsIndex();
		$this->csvData = $csv->data( $path );
	}

	function getCsvPath( $filename ) {
		return WT_BASE_PATH . 'configs/' . $filename;
	}

	function getSiteNumber() {
		return count( $this->getDomains() );
	}

	function getDomains() {
		foreach ( $this->csvData as $data ) {
			$domains[] = $data['domain'];
		}
		return $domains;
	}

	function getSiteDirNames() {
		foreach ( $this->csvData as $data ) {
			$siteDirs[] = $data['site_dir'];
		}
		return $siteDirs;
	}

	/**
	 * Set SiteConfig Group By DomainName
	 */
	function getSiteConfigData() {
		foreach ( $this->csvData as $siteConfig ) {
			$group[$siteConfig['domain']] = $this->setSiteConfigGroup( $siteConfig );
		}
		return $group;
	}

	function setSiteConfigGroup( $siteConfig ) {
		foreach ( $siteConfig as $key => $value ) {
			$data[$key] = $value;
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

trait ReadSiteConfig {
	//SITE CONFIG
	// function getSiteConfigData() {
	// 	$siteConfig = $this->conf->get( 'site_config' );
	// 	return $this->setSiteConfigGroupByDomainName( $siteConfig );
	// }

	// function setSiteConfigGroupByDomainName( $siteConfig ) {
	// 	$data = null;
	// 	foreach ( $siteConfig['domain'] as $keyNumber => $domain )
	// 		$data[$domain] = $this->setConfigGroup( $siteConfig, $keyNumber );
	// 	return $data;
	// }

	// private function setConfigGroup( $siteConfig, $keyNumber ) {
	// 	$configKeys = array_keys( $siteConfig );
	// 	foreach ( $configKeys as $name ) {
	// 		$data[$name] = $siteConfig[$name][$keyNumber];
	// 		$data['site_type']     = $this->getSiteType();
	// 		$data['project']       = $this->getProjectName();
	// 		$data['network']       = $this->getNetwork();
	// 		$data['api_key']       = $this->getApiKey();
	// 		$data['hostname']      = $this->getHostname();
	// 		$data['site_category'] = $this->getSiteCategory();
	// 	}

	// 	return $data;
	// }

	// function getDomains() {
	// 	return $this->conf->get( 'site_config', 'domain' );
	// }

	// function getSiteNumber() {
	// 	return count( $this->getDomains() );
	// }

	// function getSiteDirNames() {
	// 	return $this->conf->get( 'site_config', 'site_dir' );
	// }	
}

