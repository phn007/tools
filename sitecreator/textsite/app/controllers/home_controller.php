<?php
class HomeController extends Controller
{
	function index()
	{
		//กำหนดค่าตัวแปรให้กับ Theme
		$this->layout = 'application';
		$this->view = 'index';
		$this->home_page = true;
		$this->home_menu_state = 'class="active"';
		
		$model = $this->model( 'home' );	
		$this->slideProducts = $model->carousels();
		$this->productList = $model->productList();
		
		exit( 0 );
	}
}