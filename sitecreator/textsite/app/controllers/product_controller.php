<?php
/**
* Product detail page
*/
class ProductController extends Controller {

	function index( $params ) {
		
		$this->productPage = true;
		$this->layout = 'layout';
		$this->view = 'index';
		
		$model = $this->model( 'product' );
		$model->productFile = $params[0];
		$model->productKey  = $params[1];

		$model->getProductDetail();
		$this->productDetail = $model->productDetail;

		$model->getSpinContent();
		$this->spinContent = $model->spinContent;

		$model->getRelatedProducts();
		$this->relatedProducts = $model->relatedProducts;

		$model->getNavmenu();
		$this->menuUrl = $model->menuUrl;
		$this->menuState = $model->menuState;

		$model->permalink();
		$this->permalink = $model->permalink;

		// $model->getSeoTags();
		// $this->seoTags = $model->seoTags;
		$this->seoTags = '';
	}
}