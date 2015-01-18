<?php
class HomeController extends Controller
{
	function index() {
		$this->layout = 'application';
		$this->view = 'index';
		$this->homePage = true;
		$this->homeMenuState = true;

		$model = $this->model( 'home' );

		if ( ! $model->checkProductContentExist() ) {
			$this->defaultPage = true;
			return false;
		}

		$model->process();
		$this->productItems = $model->productItems;
		$this->categoryList = $model->categoryList;
		$this->brandList = $model->brandList;
		$this->seoTags = $model->seoTags;
	}
}