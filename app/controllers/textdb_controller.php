<?php
use webtools\controller;
include WT_APP_PATH . 'traits/config_trait.php';

class TextDbController extends Controller
{
	use SetupConfig;
	
	function create( $function, $params, $options )
	{
		//SetupConfig Trait
		$this->initialSetupConfig( $options );
		$merchantData = $this->merchantData();
		$siteNumber   = $this->siteNumber();
		$projectName  = $this->projectName();
		
		
		if ( 'textsite' == $function )
		{
			$productModel = $this->model( 'textDatabase/textsite/textsiteproducts' );
			$productModel->create( $projectName, $merchantData, $siteNumber );
			
			//$categoryModel = $this->model( 'textDatabase/textsite/textsitecategories' );
			//$categoryModel->create( $projectName $merchantData, $siteNumber );
		}
		if ( 'apisite' == $function )
		{
			$model = $this->model( 'textDatabase/apisiteDB' );
			$model->create( $projectName, $merchantData, $siteNumber );
		}	
		if ( 'htmlsite' == $function )
		{
			$model = $this->model( 'textDatabase/htmlsiteDB' );
			$model->create( $projectName, $merchantData, $siteNumber );
		}
				
	}
	
}
