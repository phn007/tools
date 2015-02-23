<?php
class CategoriesController extends Controller {
	function categories( $params ) {
		$this->currentPage = 'categories-page';
		$this->layout = 'layout';
      	$this->view = 'index';
      	$model = $this->model( 'categories' );
      	$this->categories = $model->getCategoryList( $params );
      	$this->menu = $model->getMenu( $params );
      	$this->catType = 'Categories';
      	$this->seoTags = $model->getSeoTags( $this->menu, $this->categories, $this->catType, $params );
	}

	function brands( $params ) {
		$this->currentPage = 'brands-page';
		$this->layout = 'layout';
      	$this->view = 'index';
      	$model = $this->model( 'categories' );
      	$this->categories = $model->getBrandList( $params );
      	$this->menu = $model->getMenu( $params );
      	$this->catType = 'Brands';
      	$this->seoTags = $model->getSeoTags( $this->menu, $this->categories, $this->catType, $params );
	}
}