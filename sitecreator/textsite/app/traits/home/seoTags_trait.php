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
			'propertyLocale' => 'en_US',
			'propertyType' => 'website',
			'propertyTitle' => SITE_NAME,
			'propertyDescription' => SITE_DESC,
			'propertyUrl' => HOME_LINK,
			'propertySiteName' => SITE_NAME,
		);
		return $tags;
	}
}