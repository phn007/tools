<?php
class SearchController extends Controller {
	function index( $params ) {
		$this->view = 'index';
		$this->layout = 'search';
		$this->currentPage = 'search-page';

		$model = $this->model( 'search' );
		if ( $model->searchProducts( $params[0] ) ) {
			$this->products = $model->getProducts();
			$this->pagination = $model->pagination();	
		} 

		$this->seoTags = "";
	}
}

