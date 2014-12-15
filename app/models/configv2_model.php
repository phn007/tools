<?php
use webtools\controller;
use webtools\libs\Helper;

class ConfigV2Model extends Controller
{
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
	
	function getProjectName() { return $this->projectName; }
	function getSiteNumber() { return $this->siteNumber; }
	
	function getConfigData( $filename ) { 
		$this->conf = new Config_Lite( CONFIG_PATH . $filename ); 
	}
	
	function getSiteConfigData()
	{
		$siteConfig = $this->conf->get( 'site_config' );
		return $this->setSiteConfigGroupByDomainName( $siteConfig );
	}
	
	function setSiteConfigGroupByDomainName( $siteConfig )
	{
		$data = null;
		$configKeys = array_keys( $siteConfig );
	
		foreach ( $siteConfig['domain'] as $keyNumber => $domain )
		{
			foreach ( $configKeys as $name )
			{
				$data[$domain][$name] = $siteConfig[$name][$keyNumber];
				
				//เพิ่มตัวแปรที่ต้องใช้เข้าไปใน site config
				$data[$domain]['site_type']     = $this->siteType;
				$data[$domain]['project']       = $this->projectName;
				$data[$domain]['network']       = $this->network;
				$data[$domain]['api_key']       = $this->apiKey;
				$data[$domain]['hostname']      = $this->hostname;
				$data[$domain]['site_category'] = $this->siteCategory;
			}
		}
		return $data;
	}
	
	function getMerchantData()
	{
        foreach ( $this->merchants as $merchant  )
        {
            $data[ $merchant ] = array(
               'project' => $this->projectName,
               'db_name' => $this->convertMerchantToDbName( $merchant ),
               'network' => $this->network,
               'clear_db' => $this->clearDbStatus,
            );
        }
		return $data;
	}
	
	function initialVariables( $filename )
	{	
		//อ่านค่าจากไฟล์ .ini
		$this->getConfigData( $filename );
		
		//ชื่อโปรเจ็ค
		$this->projectName = $this->createProjectName( $filename );
		
		//ชื่อ network ( viglink or prosperent-deeplink, prosperent-api)
        $this->network = $this->conf->get( null, 'network' );
        $this->apiKey = $this->conf->get( null, 'api_key' );
		
		//ประเภทของเว็บไซต์ที่จะสร้าง ( textsite, htmlsite );
         $this->siteType = $this->conf->get( null, 'site_type' );
		
		//Category โดยรวมของเว็บไซต์
        $this->siteCategory = $this->conf->get( null, 'site_category' );

        //ชื่อโฮสที่จะขึ้นเว็บ
        $this->hostname = $this->conf->get( null, 'hostname' );

        //ใช้กำหนดว่าจะให้ลบ database ก่อนดึงสินค้ามาใหม่หรือเปล่า
        $this->clearDbStatus = $this->conf->get( null, 'clear_database' );

        //จำนวน Textdatabase ที่ต้องการสร้าง
        $this->domains = $this->conf->get( 'site_config', 'domain' );
        $this->siteNumber = count( $this->domains );

        //รายชื่อ merchant
        $this->merchants = $this->conf->get( 'merchant' );
	}
	
	function convertMerchantToDbName( $merchant )
	{
		//แปลงชื่อ merchant ให้เป็นชื่อ database
      	$dbName = Helper::clean_string( $merchant );
		if ( $this->network == 'viglink' )
			$dbName = 'vl_' . str_replace( '-', '_', $dbName );
		if ( $this->network == 'prosperent-api' )
      		$dbName = 'prosp_' . str_replace( '-', '_', $dbName );
		return $dbName;
	}
	
	function createProjectName( $filename )
    {
      $arr = explode( '/', $filename );
      return str_replace( '.ini', '', end( $arr ) );
    }
	

}