<?php
class HomeController extends Controller
{
	function index() {
		//กำหนดค่าตัวแปรให้กับ Theme
		$this->layout = 'application';
		$this->view = 'index';
		$this->homePage = true;
		$this->homeMenuState = 'class="active"';
		
		$model = $this->model( 'home' );
		$this->productItems = $model->productItems;
		$this->categoryList = $model->categoryList;
		$this->brandList = $model->brandList;
		$this->seoTags = $model->seoTags;
	}
}