<?php 
trait Navmenu {
	use GetPositionOfFileList, GetPositionOfProductKey;

	function setNavmenu() {
		$this->dbCom = $this->component( 'textdatabase' );
		$files = $this->navGetProductFileList();
		$this->setFilePosition( $files );
		$this->setProductKeyPosition( $productItems );
		$this->navGetProductItemList()
		

		echo "<hr>";
		echo "<pre>";
		echo "First: <br>";
		print_r( $this->firstFile );

		echo "Prev: <br>";
		print_r( $this->previousFile );
		
		echo "Current: <br>";
		print_r( $this->currentFile );

		echo "Next: <br>";
		print_r( $this->nextFile );

		echo "Last: <br>";
		print_r( $this->lastFile );
		echo "</pre>";
		die();
	}

	function navGetProductFileList() {
		$path = $this->navGetProductDir();
		$this->dbCom->checkExistTextFilePath( $path );
		return $this->dbCom->readTextFileFromDirectory( $path );
	}

	function navGetProductItemList() {
		
	}

	function navGetProductDir() {
		return $this->dbCom->setProductDirPath();
	}
}

trait GetPositionOfProductKey {

	function setProductKeyPosition() {
		
	}
}

trait GetPositionOfFileList {

	function setFilePosition( $files ) {
		$this->firstFile = $this->getFirstPositionOfFileList( $files );
		$this->lastFile = $this->getLastPositionOfFileList( $files );
		$this->currentFile = $this->getCurrentPositionOfFileList( $files );
		$this->nextFile = $this->getNexPositionOfFileList( $files );
		$this->previousFile = $this->getPreviousPositionOfFileList( $files );
	}

	function getFirstPositionOfFileList( $files ) {
		$firstKey = 0;
		$path = $this->getArrayValueByKey( $firstKey, $files );
		return array(
			'key' => $firstKey,
			'path' => $path,
		);
	}

	function getLastPositionOfFileList( $files ) {
		$lastKey = $this->getLastKey( $files );
		$path = $this->getArrayValueByKey( $lastKey, $files );
		return array(
			'key' => $lastKey,
			'path' => $path,
		);
	}

	function getPreviousPositionOfFileList( $files ) {
		$prevKey = $this->getPreviousKey();
		$path = $this->getArrayValueByKey( $prevKey, $files );
		return array(
			'key' => $prevKey,
			'path' => $path,
		);
	}

	function getNexPositionOfFileList( $files ) {
		$nextKey = $this->getNextKey();
		$path = $this->getArrayValueByKey( $nextKey, $files );
		return array(
			'key' => $nextKey,
			'path' => $path,
		);
	}

	function getCurrentPositionOfFileList( $files ) {
		$path = $this->navGetFilePath( $this->productFile );
		$key = $this->findArrayKeyByValue( $path, $files );
		return array(
			'key' => $key,
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

	function navGetFilePath( $filename ) {
		return $this->navGetProductDir() . $filename . '.txt';
	}
}