<?php
class TextDatabaseComponent {
	function checkExistTextFilePath( $path ) {
		if ( ! file_exists( $path ) )
			trigger_error( 'My Debug: ' . $path . ' TextFile path does not exist' , $error_type = E_USER_ERROR );
	}
	
	function getContentFromSerializeTextFile( $textFilePath ) {
		$contents  = file_get_contents( $textFilePath );
        return unserialize( $contents );
	}
	
	function getContentFromNormalTextFile( $path ) {
		$fp = fopen( $path, 'rb' );
		while ( !feof( $fp ) ) {
			$chunk = fgets( $fp );
		}
	}
	
	function getRandomTextFilePath( $files ) {
		shuffle( $files );
		return $files[0];
	}
	
	function readTextFileFromDirectory( $path ) { 
		return glob( $path . "*.txt" );
	}
	
	function setProductDirPath() { 
		return CONTENT_PATH . 'products/'; 
	}
	
	function setCategoryDirPath() {
		return CONTENT_PATH . 'categories/';
	}
	
	function setBrandDirPath() {
		return CONTENT_PATH . 'brands/';
	}
	
	function getTextFileList( $path ) {
		$this->checkExistTextFilePath( $path );
		$files = $this->readTextFileFromDirectory( $path );
		return $files;
	}
}