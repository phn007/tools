<?php
trait CategoryList {
	use CategoryData;

	private $showNumber = 100;
	private $typeName;

	function categoryList( $typeName ) {
		$this->typeName = $typeName;
		$categoryDir = $this->getCategoryDirPath();
		$files = $this->dbCom->getTextFileList( $categoryDir );
		$listNumber = $this->setShowNumber( $files );
		$files = $this->getFileByListNumber( $files, $listNumber );
		
		foreach ( $files as $path ) {
			$categoryList[] = $this->getCategoryData( $path );
		}
		return $categoryList;
	}
	
	function getCategoryDirPath() {
		if ( 'category' == $this->typeName )
			return $this->dbCom->setCategoryDirPath();
		elseif ( 'brand' == $this->typeName )
			return $this->dbCom->setBrandDirPath();
	}
	
	function getFileByListNumber( $files, $listNumber ) {
		return array_slice( $files, 0, $listNumber );
	}
	
	function setShowNumber( $files ) {
		$count = count( $files );
     	return $count < $this->showNumber ? $count : $this->showNumber;
	}
}

trait CategoryData {
	function getCategoryData( $path ) {
		$this->dbCom->checkExistTextFilePath( $path );	
		$files = $this->readCategoryContent( $path );
		$categoryContent = $this->separateCategoryContent( $files );
		
		list( $categoryName ) = $categoryContent;
		$catSlug = Helper::clean_string( $categoryName );
		$categoryLink = $this->getCategoryLink( $this->typeName, $catSlug ); //CategoryLink Trait
		return array( 'categoryName' => $categoryName, 'categorylink' => $categoryLink );
	}

	function readCategoryContent( $path) {
		return file( $path );
	}

	function separateCategoryContent( $files ) {
		return explode( '|', $files[0] );
	}
}