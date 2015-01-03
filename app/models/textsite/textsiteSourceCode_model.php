<?php
use webtools\controller;
use webtools\libs\Helper;
include WT_APP_PATH . 'traits/textsite/sourceCode_trait.php';

class TextSiteSourceCodeModel extends Controller
{
	use SourceCode;
	
	function Code( $config )
	{
		$this->initialSourceCode( $config );
		$this->copyFiles( null, 'textsite','fullMode' );
		
		//$this->copyFiles( 'componentFiles', 'textsite/app/controllers','fullMode' );
		//$this->copyFiles( 'componentFiles', 'textsite/app/components','includeMode' );
		//$this->copyFiles( 'viewFiles', 'textsite/app/views/' . $theme ,'fullMode' );
		//$this->copyFiles( 'imageFiles', 'textsite/images','fullMode' );
	}

	function componentFiles()
	{
		return array(
			'allproducts_component.php',
			'category_component.php',
			'head_component.php',
			'home_component.php',
			'product_component.php',
			'productnavmenu_component.php',
			'relatedproduct_component.php',
			'textspinner_component.php'
		);
	}
	
	function controllerFiles()
	{
		return array();
	}
	
	function extensionFiles() {}
	function modelFiles() {}
	function staticPageFiles() {}
	function viewFiles() { return array(); }
	function libFiles() {}
	function imageFiles() { return Null; }
}