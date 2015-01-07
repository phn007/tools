<?php 
trait NavmenuProduct {
	use PositionOfFileList;
	use PositionOfProductItem;
	use CheckNextItemAndFile;
	use NavmenuUrl;

	private $menuUrl; //output
	private $menuState = array(); //output

	function setNavmenu() {
		$this->dbCom = $this->component( 'textdatabase' );

		$this->initialNextProductFile();
		$this->initialPrevProductFile();
		$this->initialNavmenuState();

		$this->navGetFilePosition();
		$this->navGetProductItemPosition();
		
		$this->checkNextItem();
		$this->checkPrevItem();
		$this->menuUrl = $this->getNavmenuUrl();
	}

	function initialNextProductFile() {
		$this->nextProductFile = $this->productFile;
	}

	function initialPrevProductFile() {
		$this->prevProductFile = $this->productFile;
	}

	function initialNavmenuState() {
		$this->menuState['first'] = true;
		$this->menuState['prev']  = true;
		$this->menuState['next']  = true;
		$this->menuState['last']  = true;
	}

	function navGetFilePosition() {
		$filePath = $this->navGetProductDir();
		$files = $this->navGetProductFileList( $filePath );
		$this->setFilePosition( $files );
	}

	function navGetProductItemPosition() {
		$productPath = $this->navGetFilePath( $this->productFile );
		$productItems = $this->navGetProductItemList( $productPath );
		$this->setProductItemPosition( $productItems );
	}

	function navGetProductFileList( $path ) {
		$this->dbCom->checkExistTextFilePath( $path );
		return $this->dbCom->readTextFileFromDirectory( $path );
	}

	function navGetProductItemList( $productPath ) {
		$this->dbCom->checkExistTextFilePath( $productPath );
		return $this->dbCom->getContentFromSerializeTextFile( $productPath );
	}

	function navGetProductDir() {
		return $this->dbCom->setProductDirPath();
	}

	function navGetFilePath( $filename ) {
		return $this->navGetProductDir() . $filename . '.txt';
	}
}

trait NavmenuUrl {
	function getNavmenuUrl() {
		return array(
			'firstUrl' => $this->setFistUrl(),
			'lastUrl'  => $this->setLastUrl(),
			'nextUrl'  => $this->setNextUrl(),
			'prevUrl'  => $this->setPreviousUrl()
		);
	}

	function setFistUrl() {
		$productFile = $this->productFile;
		$productKey = $this->firstItem;
		return $this->setUrl( $productFile, $productKey );
	}

	function setLastUrl() {
		$productFile = $this->productFile;
		$productKey = $this->lastItem;
		return $this->setUrl( $productFile, $productKey );
	}

	function setNextUrl() {
		$productFile = $this->nextProductFile;
		$productKey = $this->nextItem;
		return $this->setUrl( $productFile, $productKey );
	}

	function setPreviousUrl() {
		$productFile = $this->prevProductFile;
		$productKey = $this->prevItem;
		return $this->setUrl( $productFile, $productKey );
	}

	function setUrl( $productFile, $productKey ) {
		return $this->getPermalink( $productFile, $productKey ); //permalink_trait.php
	}
}

trait CheckNextItemAndFile {

	function checkNextItem() {
		if ( empty( $this->nextItem ) )
			$this->checkNextFile();
	}

	function checkNextFile() {
		if( $this->nextFile['key'] > $this->lastFile['key'] )
			$this->setNextStop();
		else
			$this->setNextContinue();
	}

	function setNextContinue() {
		$nextFilePath = $this->nextFile['path'];
		$productItemOfNextFile = $this->navGetProductItemList( $nextFilePath ); //NavmenuProduct Trait
		$this->nextItem = $this->getFirstKeyOfProductItem( $productItemOfNextFile ); //PositionOfProductItem Trait
		$this->nextProductFile = $this->nextFile['filename'];
	}

	function setNextStop() {
		$this->nextItem = $this->lastItem;
		$this->menuState['next'] = false;
		$this->menuState['last'] = false;
	}

	function checkPrevItem() {
		if ( empty( $this->prevItem ) )
			$this->checkPrevFile();
	}

	function checkPrevFile() {
		if( $this->prevFile['key'] < $this->firstFile['key'] )
			$this->setPrevStop();
		else
			$this->setPrevContinue();
	}


	function setPrevContinue() {
		$prevFilePath = $this->prevFile['path'];
		$productItemOfPrevFile = $this->navGetProductItemList( $prevFilePath ); //NavmenuProduct Trait
		$this->prevItem = $this->getLastKeyOfProductItem( $productItemOfPrevFile ); //PositionOfProductItem Trait
		$this->prevProductFile = $this->prevFile['filename'];
	}

	function setPrevStop() {
		$this->prevItem = $this->firstItem;
		$this->menuState['first'] = false;
		$this->menuState['prev'] = false;
	}

}

trait PositionOfProductItem {

	function setProductItemPosition( $productItems ) {
		$this->firstItem = $this->getFirstKeyOfProductItem( $productItems );
		$this->lastItem = $this->getLastKeyOfProductItem( $productItems );
		$this->currentItem = $this->getCurrentKeyOfProductItem();
		$this->nextItem = $this->getNextKeyOfProductItem( $productItems );
		$this->prevItem = $this->getPreviousKeyOfProductItem( $productItems );
	}

	function getFirstKeyOfProductItem( $productItems ) {
		reset( $productItems );
      	return key( $productItems );
	}

	function getCurrentKeyOfProductItem() {
		return $this->productKey;
	}

	function getLastKeyOfProductItem( $productItems ) {
		end( $productItems );
      	return key( $productItems );
	}

	function getNextKeyOfProductItem( $productItems ) {
		$this->setNextArray( $productItems, $this->currentItem );
		next( $productItems );
      	return key( $productItems );
	}

	function getPreviousKeyOfProductItem( $productItems ) {
		$this->setPreviousArray( $productItems, $this->currentItem );
		prev( $productItems );
      	return key( $productItems );
	}

	function setNextArray( &$array, $key ) {
		reset( $array );
		while ( key( $array ) !== $key ) {
			if ( next( $array ) === false ) throw new Exception('Invalid key');
		}
	}

	function setPreviousArray( &$array,$key ) {
		end( $array );
		while ( key( $array ) !== $key ) {
			if ( prev( $array ) === false ) throw new Exception('Invalid key');
		}
	}
}

trait PositionOfFileList {

	function setFilePosition( $files ) {
		$this->firstFile = $this->getFirstPositionOfFileList( $files );
		$this->lastFile = $this->getLastPositionOfFileList( $files );
		$this->currentFile = $this->getCurrentPositionOfFileList( $files );
		$this->nextFile = $this->getNexPositionOfFileList( $files );
		$this->prevFile = $this->getPreviousPositionOfFileList( $files );
	}

	function getFirstPositionOfFileList( $files ) {
		$firstKey = 0;
		return $this->getPositionOfFileList( $files, $firstKey );
	}

	function getLastPositionOfFileList( $files ) {
		$lastKey = $this->getLastKey( $files );
		return $this->getPositionOfFileList( $files, $lastKey );
	}

	function getPreviousPositionOfFileList( $files ) {
		$prevKey = $this->getPreviousKey();
		return $this->getPositionOfFileList( $files, $prevKey );
	}

	function getNexPositionOfFileList( $files ) {
		$nextKey = $this->getNextKey();
		return $this->getPositionOfFileList( $files, $nextKey );
	}

	function getPositionOfFileList( $files, $key ) {
		$path = $this->getArrayValueByKey( $key, $files );
		$filename = $this->getFilenameFromCategoryPath( $path );
		return array(
			'key' => $key,
			'filename' => $filename,
			'path' => $path,
		);
	}

	function getCurrentPositionOfFileList( $files ) {
		$path = $this->navGetFilePath( $this->productFile );
		$filename = $this->getFilenameFromCategoryPath( $path );
		$key = $this->findArrayKeyByValue( $path, $files );
		return array(
			'key' => $key,
			'filename' => $filename,
			'path' => $path,
		);
	}

	function countFileNumber( $files ) {
		return count( $files );
	}

	function getLastKey( $files ) {
		$countNumber = $this->countFileNumber( $files );
		return $countNumber - 1;
	}

	function getNextKey() {
		return $this->currentFile['key'] + 1;
	}

	function getPreviousKey() {
		return $this->currentFile['key'] - 1;
	}

	function getArrayValueByKey( $key, $files ) {
		$value = null;
		if ( isset( $files[$key] ) )
			$value = $files[$key];
		return $value;
	}	

	function findArrayKeyByValue( $value, $array ) {
		return array_search( $value, $array );
	}

	function getFilenameFromCategoryPath( $path ) {
		$arr = explode( '/', $path );
		$filename = end( $arr );
		return str_replace( '.txt', '', $filename );
	}
}