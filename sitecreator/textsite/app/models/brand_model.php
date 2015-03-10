<?php
include APP_PATH . 'traits/category/brandItems_trait.php';
include APP_PATH . 'traits/link_trait.php';
include APP_PATH . 'traits/category/categoryPaginator_trait.php';
include APP_PATH . 'traits/category/categorySeoTags_trait.php';

class BrandModel extends AppComponent {
	use BrandItems;
	use Permalink, CategoryLink;
	use CategoryPaginator;
	use CategorySeoTags;
	
	function brandItems( $params ) {
		return $this->getBrandItems( $params );
	}

	function pagination( $params ) {
		return $this->getPagination( $params, $this->pageNumberList, 'brand' ); 
	}

	function getSeoTags( $menu, $category, $catType, $params ) {
		$catName = key( $category );
		$menuUrl = $menu['url'];
		return $this->getCategorySeoTags( $menuUrl, $catName, $catType, $params );
	}
}

