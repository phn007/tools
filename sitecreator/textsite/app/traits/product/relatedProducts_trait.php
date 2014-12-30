<?php
trait RelatedProducts {
	use GetDataFromCategory;
	use GetRelatedProductDetail;

	function setRelatedProducts() {
		$this->dbCom = $this->component( 'textdatabase' );
		$randContents = $this->getProductDataFromCategoryTextFile(); //GetDataFromCategory Trait
		$relatedProducts = $this->getProductDetailFromProductTextFile( $randContents ); //GetRelatedProductDetail Trait
		return $relatedProducts;
	}
}

trait GetRelatedProductDetail {
	
	function getProductDetailFromProductTextFile( $contents ) {
		if ( empty( $contents ) ) return null;
		foreach ( $contents as $key => $data ) {
			$keySlug = $this->getKeySlug( $key );
			$this->dbCom->checkExistTextFilePath( $data['path'] );
			$contents = $this->readProductContents( $data['path'] );
			$product = $this->getProductContentByKey( $contents, $keySlug );
			$product = $this->addGoToLinkIntoRelatedProductItem( $product, $data['filename'], $key );
			$relatedProducts[] = $product;
		}
		return $relatedProducts;
	}

	function addGoToLinkIntoRelatedProductItem( $product, $productFile, $productKey ) {
		$product['goto'] = $this->getGotoLink( $productFile, $productKey );
		return $product;
	}

	function getProductContentByKey( $contents, $keySlug ) {
		if ( ! array_key_exists( $keySlug, $contents ) )
			die( "RelateProduct: Key not exists" );
		return $contents[$keySlug];
	}

	function readProductContents( $path ) {
		return $this->dbCom->getContentFromSerializeTextFile( $path );
	}

	function getKeySlug( $key ) {
		return Helper::clean_string( $key );
	}
}

trait GetDataFromCategory {
	private $randNumber = 12;

	function getProductDataFromCategoryTextFile() {
		$contentPath = $this->getCategoryContentPath();		
		$contents = $this->readCategoryContent( $contentPath );
		return $this->randomContents( $contents );
	}

	function randomContents( $contents ) {
		if ( empty( $contents ) ) return null;
		$randContents = null;
		$randNumber =$this->checkRandNumberAvailable( $contents );
		$randKeys = array_rand( $contents, $randNumber );
		foreach ( $randKeys as $key ) {
			$arr = explode( '|', $contents[$key] );
			$filename = $arr[1];
			$key = Helper::clean_string( $arr[2] );
			$randContents[$key]['path'] = $this->getProductDir() . $filename . '.txt';
			$randContents[$key]['filename'] = $filename;
		}
		return $randContents;
	}

	function checkRandNumberAvailable( $contents ) {
		$count = count( $contents );
		return $count < $this->randNumber ? $count : $this->randNumber;
	}

	function readCategoryContent( $contentPath ) {
		return $this->dbCom->getContentFromNormalTextFile( $contentPath );
	}

	function getCategoryContentPath() {
		$categoryDir = $this->getCategoryDir();
		$filename = $this->getCategoryFilename();
		return $categoryDir . $filename;
	}

	function getCategoryFilename() {
		return Helper::clean_string( $this->productDetail['category'] ) . '.txt';
	}

	function getCategoryDir() {
		return $this->dbCom->setCategoryDirPath();
	}
	function getProductDir() {
		return $this->dbCom->setProductDirPath();
	}
}