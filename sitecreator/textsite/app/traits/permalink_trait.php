<?php
trait Permalink {	
	function getPermalink( $productFile, $productKey ) {
		$url = array(
			'homeUrl' => rtrim( HOME_URL, '/' ),
			'productFile' => $productFile,
			'productKey' => $productKey . FORMAT,
		);
		return implode( '/', $url );
	}
}

trait CategoryLink {	
	function getCategoryLink( $typeName, $filename ) {
		$url = array(
			'homeUrl' => rtrim( HOME_URL, '/' ),
			'categoryTypeName' => $typeName,
			'categoryFilename' => $filename . FORMAT,
		);
		return implode( '/', $url );
	}	
}

trait GotoLink {
	function getGotoLink( $productFile, $productKey ) {
		$url = array(
			'homeUrl' => rtrim( HOME_URL, '/' ),
			'shop' => 'shop',
			'productFile' => $productFile,
			'productKey' => $productKey
		);
		return implode( '/', $url );
	}
}