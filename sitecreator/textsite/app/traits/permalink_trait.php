<?php

trait Permalink
{
	private $productFile;
	private $productKeyword;
	
	function getPermalink( $filename, $keySlug ) {
		$this->productFile = $filename;
		$this->productKeyword = $keySlug;
		return $this->setUrlFormat();
	}
	
	function setUrlFormat() {
		$url = array(
			'homeUrl' => rtrim( HOME_URL, '/' ),
			'productFile' => $this->productFile,
			'productKey' => $this->productKeyword . FORMAT,
		);
		return implode( '/', $url );
	}
}


trait CategoryLink {
	private $categoryTypeName;
	private $categoryFilename;
	
	function getCategoryLink( $typeName, $filename ) {
		$this->categoryTypeName = $typeName;
		$this->categoryFilename = $filename;
		return $this->setCategoryLinkFormat();
	}
	
	function setCategoryLinkFormat() {
		$url = array(
			'homeUrl' => rtrim( HOME_URL, '/' ),
			'categoryTypeName' => $this->categoryTypeName,
			'categoryFilename' => $this->categoryFilename . FORMAT,
		);
		return implode( '/', $url );
	}	
}