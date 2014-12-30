<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_APP_PATH . 'traits/html/homepage_trait.php';
include WT_APP_PATH . 'traits/html/productpage_trait.php';
include WT_APP_PATH . 'traits/html/assets_trait.php';

class HtmlModel extends Controller
{
	use HomePage;
	use ProductPage;
	use Assets;

	private $config;
	private $sourceDir;

	function __set( $name, $value ) {
      	$this->{$name} = $value;
 	}

   	function __get( $name ) {
      	return $this->{$name};
   	}

   	function initialVariables( $config ) {
   		$this->config = $config;
   		$this->sourceDir = TEXTSITE_PATH . $config['project'] . '/' . $config['site_dir_name'] . '/';
   	}

	function productPage() {
		$this->buildProductPageHtml();
	}

	function homePage() {
		$this->buildHomePageHtml();	
	}

	function assets() {
		$this->buildAssets();
	}
}