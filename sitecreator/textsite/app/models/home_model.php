<?php
include APP_PATH . 'traits/home/productItems_trait.php';
include APP_PATH . 'traits/home/categoryList_trait.php';
include APP_PATH . 'traits/home/seoTags_trait.php';
include APP_PATH . 'traits/permalink_trait.php';

class HomeModel extends Component
{
	use ProductItems;
	use CategoryList;
	use Permalink, CategoryLink;
	use SeoTags;
	
	private $dbCom;
	private $productItems;
	private $categoryList;
	private $brandList;
	private $seoTags;
	
	function __get( $name ) { 
		return $this->{$name}; 
	}
	
	function __construct() {
		$this->dbCom = $this->component( 'textdatabase' );
		$this->tagCom = $this->component( 'seoTags' );
		$this->homeProducts();
		$this->homeCategoryList();
		$this->homeBrandList();
		$this->homeSeoTags();
	}
	
	function homeProducts() {
		$this->productItems = $this->productItems(); //ProductItem Trait
	}
	
	function homeCategoryList() {
		$this->categoryList = $this->categoryList( 'category'); //CategoryList Trait
	}
	
	function homeBrandList() {
		$this->brandList = $this->categoryList( 'brand' ); //CategoryList Trait
	}
	
	function homeSeoTags() {
		$this->seoTags = $this->getSeoTags(); //SeoTags Trait
	}
}