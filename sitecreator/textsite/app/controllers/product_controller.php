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
		$this->spinContent = $model->getSpinContent();
		$this->relatedProducts = $model->getRelatedProducts();
		$this->navmenu = $model->getNavmenu();

		// echo "Controller<br>";
		// echo '<pre>';
		// print_r( $this->relatedProducts );
		// die();
		// $this->textsearch = $model->textForSearch();
		$this->permalink = $model->permalink();
		
		$this->seoTags = "";
	}
}