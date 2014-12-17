<?php
use webtools\controller;
include WT_APP_PATH . 'traits/config_trait.php';
include WT_APP_PATH . 'traits/siteInfo_trait.php';

class TextsiteController extends Controller
{
	use SetupConfig;
	use SiteInfomation;
	
	function create( $function, $params, $options )
	{
		//SetupConfig Trait
		$this->initialSetupConfig( $options );
		$merchantData   = $this->merchantData();
		$siteNumber     = $this->siteNumber();
		$projectName    = $this->projectName();
		$siteConfigData = $this->siteConfigData();
		$siteDirNames   = $this->siteDirNames();

		if ( 'textdb' == $function )
		{
			$productModel = $this->model( 'textsite/textsitedbproducts' );
			$productModel->create( $projectName, $merchantData, $siteNumber, $siteDirNames );
			
			$categoryModel = $this->model( 'textsite/textsitedbcategories' );
			$categoryModel->create( $projectName );
		}
		
		foreach ( $siteConfigData as $config )
      	{ 
			//SiteInfomation Trait
			$config['site_desc']   = $this->getSiteDescription( $config['site_category'] );
			$config['site_author'] = $this->getSiteAuthor();
			$config['prod_route']  = $this->getProdRoute();
			
			//กำหนดจำนวนสินค้าที่แสดงในหน้า category items
			$config['num_cat_item_per_page'] = 48;
			
			if ( 'code' == $function )
			{
				$codeModel = $this->model( 'textsite/textsiteSourceCode' );
				$codeModel->code( $config );
			}
			elseif ( 'config' == $function )
			{
				$cModel = $this->model( 'textsite/textsiteConfig' );
				$cModel->create( $config );
			}
		}
	}
}