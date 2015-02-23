<?php
class ErrorController extends controller {
	use ErrorSeoTags;

	function index() {
		header("HTTP/1.1 404 Not Found");
		$this->currentPage = 'error-page';
		$this->layout = 'layout';
		$this->view = 'index';
		$this->errorPage = true;
		$this->seoTags = $this->getSeoTags();
	}
}

trait ErrorSeoTags {
	function getSeoTags() {
		$tags = $this->setSeoTags();
		$tagCom = $this->component( 'seoTags' );
		return $tagCom->createSeoTags( $tags );
	}

	function setSeoTags() {
		$tags['title'] = 'Page Not Found';
		$tags['author'] = AUTHOR;
		$tags['robots'] = 'noindex, follow';
		return $tags;
	}
}
