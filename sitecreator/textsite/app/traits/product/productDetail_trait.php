<?php
trait ProductDetail {

	function setProductDetail() {
		$this->dbCom = $this->component( 'textdatabase' );
		$productPath = $this->productPath();
		$productItemList = $this->productItemList( $productPath );
		$productItem = $this->productItem( $productItemList );
		$productItem = $this->addGotoLinkIntoProductItem( $productItem );
		return $this->addBrandLinkIntoProductItem( $productItem );
	}

	function addBrandLinkIntoProductItem( $productItem ) {
		$filename = Helper::clean_string( $productItem['brand'] );
		$productItem['brandLink'] = $this->getCategoryLink( 'brand', $filename );
		return $productItem;
	}

	function addGotoLinkIntoProductItem( $productItem ) {
		$productItem['goto'] = $this->getGotoLink( $this->productFile, $this->productKey );
		return $productItem;
 	}

	function productItem( $productItemList ) {
		if ( array_key_exists( $this->productKey, $productItemList ) ) 
			return $productItemList[$this->productKey];
		else
			die( 'Product Key Not Found' . "\n" );
	}

	function productItemList( $productPath ) {
		return $this->dbCom->getContentFromSerializeTextFile( $productPath );
	}

	function productPath() {
		$productDir = $this->dbCom->setProductDirPath();
		return $productDir . $this->productFile . '.txt';
	}
}