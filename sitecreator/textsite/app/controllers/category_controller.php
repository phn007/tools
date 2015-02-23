<?php
class CategoryController extends Controller {
    function category( $params ) {
        $this->currentPage = 'category-page';
        $this->layout = 'layout';
        $this->view = 'index';
        $model = $this->model( 'category' );
        $this->category = $model->categoryItems( $params );
        $this->menu = $model->menuPage( $params );
        $this->catType = 'Category';
        $this->seoTags = $model->getSeoTags( $this->menu, $this->category, $this->catType, $params );
    }

    function brand( $params ) {;
        $this->currentPage = 'brand-page';
		    $this->layout = 'layout';
      	$this->view = 'index';
      	$model = $this->model( 'brand' );
      	$this->category = $model->brandItems( $params );
      	$this->menu = $model->menuPage( $params );
        $this->catType = 'Brand';
        $this->seoTags = $model->getSeoTags( $this->menu, $this->category, $this->catType, $params );
	}
}