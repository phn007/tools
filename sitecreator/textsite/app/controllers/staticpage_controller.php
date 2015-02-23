<?php

class StaticPageController extends controller {
	function about() {
		$this->layout = 'layout';
		$this->view = 'about';
		$this->currentPage = 'about-page';
		$model = $this->model( 'staticpage' );
		$this->content = $model->about();
		$this->seoTags = $model->getSeoTags( 'About' );
	}

	function contact() {
		$this->layout = 'layout';
		$this->view = 'contact';
		$this->currentPage = 'contact-page';
		$model = $this->model( 'staticpage' );
		$this->content = $model->contact();
		$this->seoTags = $model->getSeoTags( 'Contact' );
	}

	function privacy() {
		$this->layout = 'layout';
		$this->view = 'privacy';
		$this->currentPage = 'privacy-page';
		$model = $this->model( 'staticpage' );
		$this->content = $model->privacy();
		$this->seoTags = $model->getSeoTags( 'Privacy-Policy' );
	}
}
