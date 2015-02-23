<?php
/**
* Product detail page
*/
class ProductController extends Controller {
	function index( $params ) {
		$this->currentPage = 'product-page';
		$this->layout = 'layout';
		$this->view = 'index';
		
		$model = $this->model( 'product' );
		$model->defineParameter( $params );

		$model->getProductDetail();
		$this->productDetail = $model->productDetail;

		$model->getSpinContent();
		$this->spinContent = $model->spinContent;

		$model->getRelatedProducts();
		$this->relatedProducts = $model->relatedProducts;

		$model->getNavmenu();
		$this->menu = array( 'url' => $model->menuUrl, 'state' => $model->menuState );

		$this->seoTags = $model->getSeoTags();
	}
}