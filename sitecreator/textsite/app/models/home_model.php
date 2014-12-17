<?php
include APP_PATH . 'traits/home/productItems_trait.php';
include APP_PATH . 'traits/permalink_trait.php';

class HomeModel extends Component
{
	use ProductItems;
	use Permalink;
	
	private $productItems;
	
	function __get( $name ) { return $this->{$name}; }
	function __construct() {
		$this->homeProducts();
	}
	
	function homeProducts() {
		$this->productItems = $this->productItems(); //ProductItem Trait
	}
}