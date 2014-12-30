<?php
include APP_PATH . 'traits/product/productDetail_trait.php';
include APP_PATH . 'traits/product/spinContent_trait.php';
include APP_PATH . 'traits/product/relatedProducts_trait.php';
include APP_PATH . 'traits/product/navmenuProducts_trait.php';
include APP_PATH . 'traits/product/productSeoTags_trait.php';
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
	use ProductSeoTags;

	private $productFile;
	private $productKey;
	private $dbCom;
	private $productDetail;

	private $spinCom;
	private $wordlib_path;
	private $text_path;
	private $spinContent;

	private $relatedProducts;
	private $permalink;
	private $seoTags;

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
		$this->spinContent = $this->setSpinContent(); //SpinContent Traits
	}

	function getRelatedProducts() {
		$this->relatedProducts = $this->setRelatedProducts();
	}

	function getNavmenu() {
		$this->setNavmenu(); //Navmenu Traits
	}

	function permalink() {
		$this->permalink = $this->getPermalink( $this->productFile, $this->productKey );
	}

	function getSeoTags() {
		$this->getkeywordTags();
		$this->seoTags = $this->getProductSeoTags();
	}

	function getKeywordTags() {
		$this->tags = createKeywordTags( $this->productKey );
	}
}

function createKeywordTags( $keyword ) {
	$tags = explode( '-', $keyword);
	$tags = array_filter( $tags );
	$i = 1;
	foreach ( $tags as $tag ) {
		$num = strlen ( $tag );
		if ( $num > 1 ) {
			$data[ 'tag' . $i ] = $tag;
			$i++;
		}
	}
	return $data;
}