<?php

trait Permalink
{
	private $homeUrl;
	private $productFile;
	private $productKeyword;
	
	function getPermalink( $filename, $keySlug )
	{
		$this->homeUrl = $this->setHomeUrl();
		$this->productFile = $filename;
		$this->productKeyword = $keySlug;
		return $this->setUrlFormat();
	}
	
	function setUrlFormat()
	{
		$url = array(
			'homeUrl' => $this->homeUrl,
			'productFile' => $this->productFile,
			'productKey' => $this->productKeyword . FORMAT,
		);
		return implode( '/', $url );
	}
	
	function setHomeUrl()
	{
		return rtrim( HOME_URL, '/' );
	}

}