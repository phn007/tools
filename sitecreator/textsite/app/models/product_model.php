<?php
include APP_PATH . 'traits/product/productDetail_trait.php';
include APP_PATH . 'traits/product/spinContent_trait.php';
include APP_PATH . 'traits/product/relatedProducts_trait.php';
include APP_PATH . 'traits/product/navmenuProducts_trait.php';
include APP_PATH . 'traits/permalink_trait.php';

/**
* Product Model
*/
class ProductModel extends AppComponent {
	
	use ProductDetail;
	use SpinContent;
	use RelatedProducts;
	use Navmenu;
	use Permalink, CategoryLink, GotoLink;

	private $productFile;
	private $productKey;
	private $dbCom;
	private $productDetail;

	private $spinCom;
	private $wordlib_path;
	private $text_path;

	function __set( $name, $value ) {
      	$this->{$name} = $value;
 	}

   	function __get( $name ) {
      	return $this->{$name};
   	}

	function getProductDetail() {
		$this->productDetail = $this->setProductDetail(); //ProductDetail Traits
	}

	function getSpinContent() {
		return $this->setSpinContent(); //SpinContent Traits
	}

	function getRelatedProducts() {
		return $this->setRelatedProducts();
	}

	function getNavmenu() {
		return $this->setNavmenu(); //Navmenu Traits
	}

	function permalink() {
		return $this->getPermalink( $this->productFile, $this->productKey );
	}	
}