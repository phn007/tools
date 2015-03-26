<?php
use webtools\controller;

include WT_APP_PATH . 'traits/setupConfig_trait.php';
include WT_APP_PATH . 'traits/textsite/siteInfo_trait.php';

/**
 * Create Textsite
 * -------------------
 * php textsite -c bb-prosp create all
 * php textsite -c bb-prosp -r create code
 * php textsite -c bb-prosp create textdb
*/
class TextsiteController extends Controller {
	use SetupConfig;
	use SiteInfomation;
	
	function create( $function, $params, $options ) {
		//SetupConfig Trait
		$this->initIniConfig( $options );
		$this->initCsvConfig( $options );
		$this->iniStatConfig( $options );

		$merchantData   = $this->getMerchantData();
		$siteNumber     = $this->getSiteNumber();
		$projectName    = $this->getProjectName();
		$siteConfigData = $this->getSiteConfigData();
		$siteDirNames   = $this->getSiteDirNames();	

		if ( 'textdbNew' == $function ) {
			$productModel = $this->model( 'textdb/textdbProductsNew' );
			$productModel->create( $projectName, $merchantData, $siteNumber, $siteDirNames );

			$categoryModel = $this->model( 'textdb/textdbCategories' );
			$categoryModel->create( $siteConfigData );
			$categoryListModel = $this->model( 'textdb/categoryListForHomepage' );
			$categoryListModel->create( $siteConfigData );
		}	

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
			//$config['num_cat_item_per_page'] = 48;

			$model->initialTextsite( $config, $options );
			if ( 'code' == $function     	 || 'siteall' == $function || 'all' == $function ) $model->code();
			if ( 'config' == $function   	 || 'siteall' == $function || 'all' == $function ) $model->siteConfig();
			if ( 'htaccess' == $function 	 || 'siteall' == $function || 'all' == $function ) $model->htaccess();
			if ( 'sitemap' == $function  	 || 'siteall' == $function || 'all' == $function ) $model->sitemap();
			if ( 'sitemapindex' == $function || 'siteall' == $function || 'all' == $function ) $model->sitemapIndex();
			if ( 'robots' == $function   	 || 'siteall' == $function || 'all' == $function ) $model->robots();
			//if ( 'logo' == $function 		 || 'siteall' == $function || 'all' == $function ) $model->logo();
			if ( array_key_exists( 'z' , $options ) ) $model->zipFiles();
			if ( 'zip' == $function ) $model->zipFiles();
			if ( 'theme' == $function ) $model->theme();
		}
	}

	/**
	 * Run Server
	 * -------------------
	 * php textsite -c bb-prosp server start
	 */
	function server( $function, $params, $options ) {
		//SetupConfig Trait
		$this->initIniConfig( $options );
		$this->initCsvConfig( $options );

		$siteDirs = $this->getSiteDirNames();
		$domains = $this->getDomains();
		$config = array(
			'project' => $this->getProjectName(),
			'siteDir' => $siteDirs[0],
			'hostname'  => $domains[0]
		);
		$model = $this->model( 'textsite' );
		if ( 'start' == $function ) $model->serverStart( $config );
	}

	/**
	 * Show Config
	 * -------------------
	 * php textsite -c bb-prosp show config
	 */
	function show( $function, $params, $options ) {
		//SetupConfig Trait
		$this->initIniConfig( $options );
		$this->initCsvConfig( $options );
		$this->iniStatConfig( $options );
		if ( 'config' == $function ) {
			foreach ( $this->getSiteConfigData() as $config ) {
				print_r( $config );
				echo "\n";
			}
		}
	}

	/**
	 * Calculate Domain Number
	 * -------------------
	 * php textsite -c bb-prosp calc byproducts productNumberPerDomain
	 * php textsite -c bb-prosp calc byproducts 100000
	 * php textsite -c bb-prosp calc bydomains domainNumberToCals
	 * php textsite -c bb-prosp calc bydomains 10
	 */
	function calc( $function, $params, $options  ) {
		$this->initIniConfig( $options );//SetupConfig Trait
		$merchantData = $this->getMerchantData();
		$model = $this->model( 'textdb/calculateDomainNumber' );
		if ( 'byproducts' == $function ) $model->calcByProducts( $merchantData, $params['number'] );
		if ( 'bydomains' == $function ) $model->calcByDomains( $merchantData, $params['number'] );
	}

	/**
	 * php textsite -c rexce1 db del
	 */
	function DB( $function, $params, $options ) {
		$this->initIniConfig( $options );//SetupConfig Trait
		$dbs = $this->getDatabaseNames();
		$model = $this->model( 'textsite' );
		if ( $function == 'del' ) $model->deleteDatabase( $dbs );
	}

	/**
	 * php textsite -c rexce1 separator check
	 */
	function separator( $function, $params, $options ) {
		$this->initIniConfig( $options );//SetupConfig Trait
		$merchants = $this->getMerchants();
		$model = $this->model( 'textsite' );
		if ( $function == 'check' ) $model->checkSeparator( $merchants );
	}

}//class