<?php
trait SeoTags {
	function getSeoTags() {
		return $this->tagCom->createSeoTags( $this->setSeoTags() );
	}
	
	function setSeoTags() {
		$tags = array(
			'title' => SITE_NAME,
			'description' => SITE_DESC,
			'linkTag' => HOME_LINK,
			'author' => AUTHOR,
			'robots' => 'index, follow'
		);
		return $tags;
	}
}