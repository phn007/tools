<?php
class HtmlSitemapController extends Controller {
	function index() {
		$this->layout = 'layout';
		$this->view = 'index';
		$this->currentPage = 'html-sitemap-page';
		$this->homeMenuState = true;

		$model = $this->model( 'htmlSitemap' );
		$this->categoryList = $model->sitemapCategoryList();		
		$this->brandList = $model-> sitemapBrandList();
		$this->seoTags = $model->sitemapSeoTags();
	}
}
