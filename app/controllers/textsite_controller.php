<?php
use webtools\controller;

include WT_APP_PATH . 'traits/setupConfig_trait.php';
include WT_APP_PATH . 'traits/textsite/siteInfo_trait.php';

class TextsiteController extends Controller {
	use SetupConfig;
	use SiteInfomation;
	
	function create( $function, $params, $options ) {
		$this->initialSetupConfig( $options ); //SetupConfig Trait
		$merchantData   = $this->getMerchantData();
		$siteNumber     = $this->getSiteNumber();
		$projectName    = $this->getProjectName();
		$siteConfigData = $this->getSiteConfigData();
		$siteDirNames   = $this->getSiteDirNames();		

		if ( 'textdb' == $function || 'all' == $function ) {
			$productModel = $this->model( 'textdb/textdbProducts' );
			$productModel->create( $projectName, $merchantData, $siteNumber, $siteDirNames );
			$categoryModel = $this->model( 'textdb/textdbCategories' );
			$categoryModel->create( $siteConfigData );
			$categoryListModel = $this->model( 'textdb/categoryListForHomepage' );
			$categoryListModel->create( $siteConfigData );
		}

		$model = $this->model( 'textsite' );
		foreach ( $siteConfigData as $config ) { 
			$config['site_desc']   = $this->getSiteDescription( $config['site_category'] ); //SiteInfomation Trait
			$config['site_author'] = $this->getSiteAuthor();
			$config['prod_route']  = $this->getProdRoute();
			$config['num_cat_item_per_page'] = 48;

			$model->initialTextsite( $config, $options );
			if ( 'code' == $function     	 || 'siteall' == $function || 'all' == $function ) $model->code();
			if ( 'config' == $function   	 || 'siteall' == $function || 'all' == $function ) $model->siteConfig();
			if ( 'htaccess' == $function 	 || 'siteall' == $function || 'all' == $function ) $model->htaccess();
			if ( 'sitemap' == $function  	 || 'siteall' == $function || 'all' == $function ) $model->sitemap();
			if ( 'sitemapindex' == $function || 'siteall' == $function || 'all' == $function ) $model->sitemapIndex();
			if ( 'robots' == $function   	 || 'siteall' == $function || 'all' == $function ) $model->robots();
			//if ( 'logo' == $function 		 || 'siteall' == $function || 'all' == $function ) $model->logo();
			if ( 'theme' == $function ) $model->theme();
		}
	}

	function server( $function, $params, $options ) {
		$this->initialSetupConfig( $options ); //SetupConfig Trait
		$model = $this->model( 'textsite' );
		if ( 'start' == $function ) $model->serverStart( $this->getSiteConfigData() );
	}

	function show( $function, $params, $options ) {
		$this->initialSetupConfig( $options ); //SetupConfig Trait
		if ( 'config' == $function ) {
			foreach ( $this->getSiteConfigData() as $config ) {
				print_r( $config );
				echo "\n";
			}
		}
	}
}//class