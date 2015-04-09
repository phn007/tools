<?php
use webtools\controller;

include WT_BASE_PATH . 'libs/csvReaderV2.php';
include WT_APP_PATH . 'traits/getCsvConfigDataV2_trait.php';
include WT_APP_PATH . 'traits/setupConfigV4_trait.php';
include WT_APP_PATH . 'traits/textsite/siteInfo_trait.php';

/**
 * Create Textsite
 * -------------------
 * php text create code rexce domain.com
*/
class TextController extends Controller {
	use GetCsvConfigData;
	use SetupConfig;
	use SiteInfomation;
	
	function create( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$domain = $params['domain'];
		$module = $options['module'];

		$csvData = $this->initialConfigDataFromCsvFileForText( $csvFilename, $domain );
		$iniFilename = $csvData['config_file'];

		$this->initialDotINIConfigFile( $iniFilename, $csvFilename );
		$config = $this->getSiteConfigDataForText( $csvFilename, $csvData );

		$config['site_desc']   = $this->getSiteDescription( $config['site_category'] ); //SiteInfomation Trait
		$config['site_author'] = $this->getSiteAuthor();
		$config['prod_route']  = $this->getProdRoute();
		$model = $this->model( 'textsite' );
		$model->initialTextsite( $config, $options );

		if ( $module == 'code' )  		$model->code();
		if ( $module == 'config') 		$model->siteConfig();
		if ( $module == 'htaccess') 	$model->htaccess();
		if ( $module == 'sitemap') 		$model->sitemap();
		if ( $module == 'sitemapindex') $model->sitemapIndex();
		if ( $module == 'robots') $model->robots();
		if ( $module == 'theme') $model->theme();
		if ( $module == 'zip') $model->zipFiles();	
	}

	/**
	 * php text calc type iniFilename number
	 * php text calc byproducts test1 3000
	 * php text calc bydomains test1 5
	 */
	function calc( $function, $params, $options ) {
		$iniFilename = $params['iniFilename'];
		$merchantData = $this->getMerchantForCalcalate( $iniFilename );
		$model = $this->model( 'textdb/calculateDomainNumber' );
		if ( 'byproducts' == $function ) $model->calcByProducts( $merchantData, $params['number'] );
		if ( 'bydomains' == $function ) $model->calcByDomains( $merchantData, $params['number'] );
	}

	/**
	 * php text server start csvFilename domain
	 * php text server start rexce domain.com
	 */
	function server( $function, $params, $options ) {
		$csvFilename = $params['csvFilename'];
		$domain = $params['domain'];
		$csvData = $this->initialConfigDataFromCsvFileForText( $csvFilename, $domain );
		$config = array(
			'project' => $csvFilename,
			'siteDir' => $csvData['site_dir'],
			'hostname'  => 'http://' . $csvData['domain']
		);
		$model = $this->model( 'textsite' );
		if ( 'start' == $function ) $model->serverStart( $config );
	}

	/**
	 * php text separator check iniFilename
	 */
	function separator( $function, $params, $options ) {
		$iniFilename = $params['iniFilename'];
		$merchants = $this->getMerchantForSeparate( $iniFilename );
		$model = $this->model( 'textsite' );
		if ( $function == 'check' ) $model->checkSeparator( $merchants );
	}
}//class