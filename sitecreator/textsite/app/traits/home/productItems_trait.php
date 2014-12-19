<?php
trait ProductItems
{
	private $productPath;
	private $itemNumber = 15;
	private $productData;
	
	function productItems() {
		$cachePath = 'cache/home-products';
		$cacheName = 'home-product-file';
		$cacheTime = 300;
		$c = new Cache();
		$cache = $c->get( $cachePath, $cacheName, $cacheTime );
		
		if ( $cache == NULL ) {
			$products = $this->getProductContents();
			$products = $this->getProductData( $products );
			$cache = $products;
            $c->set( $cachePath, $cacheName, $cache );
		}
		return $this->addPermalinkIntoProductData( $cache );
	}
	
	function addPermalinkIntoProductData( $cache ) {
		$filename = key( $cache );
		$products = $cache[$filename];
		
		foreach ( $products as $key => $product ) {
			$keySlug   = Helper::clean_string( $product['keyword'] );
        	$permalink = $this->getPermalink( $filename, $keySlug ); //Permalink Trait
			$product['permalink'] = $permalink;
			$data[$key] = $product;
		}
		return $data;
	}
	
	function getFilenameFromPath( $productPath ) {
		$arr = explode( '/', $productPath );
        $textFile = end( $arr );
        return str_replace( '.txt', '', $textFile );
	}
	
	function getProductData( $products ) {
		$filename = key( $products );
		$products = $products[$filename];
		$productNames = $this->getProductNameList( $products );
		foreach ( $productNames as $key )
			$data[$filename][$key] = $products[$key];
		return $data;
	}
	
	function getProductNameList( $products ) {	
		$keys = $this->getProductKeys( $products);
		$num  = $this->getProductNumberByItemNumber( $keys ); 
		return $this->randomProductKeys( $keys, $num );	
	}
	
	function randomProductKeys( $keys, $num ) {
		$rand_number = array_rand( $keys, $num );
       	foreach( $rand_number as $number )
		   $rand_keys[] = $keys[$number];
		return $rand_keys;
	}
	
	function getProductNumberByItemNumber( $keys ) {
		$count = count( $keys );
		$num   = $count > $this->itemNumber ? $this->itemNumber : $count;
		return $num;
	}
	
	function getProductKeys( $products ) {
		return array_keys( $products );
	}	
	
	function getProductFilePath() {
		$productDir = $this->dbCom->setProductDirPath();
		$files = $this->dbCom->getTextFileList( $productDir );
		return $this->dbCom->getRandomTextFilePath( $files );
	}
	
	function getProductContents() {
		$productFilePath = $this->getProductFilePath();
		$filename = $this->getFilenameFromPath( $productFilePath );
		$contents = $this->dbCom->getContentFromSerializeTextFile( $productFilePath );
		$data[$filename] = $contents;
		return $data;
	}
}
