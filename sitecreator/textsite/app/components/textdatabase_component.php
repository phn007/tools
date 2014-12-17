<?php
class TextDatabaseComponent
{
	
	function getContentFromTextFile( $textFilePath )
	{
		$contents  = file_get_contents( $textFilePath );
        return unserialize( $contents );
	}
	
	function getProductTextFileList()
	{
		$productPath = $this->setProductPath();
		$this->checkExistProductTextFilePath( $productPath );
		$files = $this->getTextFileList( $productPath );
		return $files;
	}
	
	function getRandomTextFile( $files )
	{
		shuffle( $files );
		return $files[0];
	}
	
	function setProductPath() { return CONTENT_PATH . 'products/'; }
	function getTextFileList( $productPath ) { return glob( $productPath . "*.txt" ); }
	
	function checkExistProductTextFilePath( $productPath )
	{
		if ( ! file_exists( $productPath ) )
			trigger_error( 'My Debug: Product TextFile path does not exist' , $error_type = E_USER_ERROR );
	}
	
}