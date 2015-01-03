<?php
use webtools\controller;
use webtools\libs\Helper;

class TextsiteCategoriesModel extends Controller
{
	private $projectName;

	function create( $projectName )
	{
		$this->projectName = $projectName;
		$dirs = $this->getProjectDirectories();
		$productDirs = $this->getProductDirectories( $dirs );
		$files = $this->getProductTextFileGroupBySubProject( $productDirs );
		$this->readProductDataAndWriteToTextFile( $files);
	}
	
	function readProductDataAndWriteToTextFile( $files )
	{
		foreach ( $files as $subProjectName => $file )
		{
			foreach ( $file as $path )
			{
				$productFilename = $this->getFilenameFromPath( $path );
				$content = $this->readProductDataFromTextFile( $path );
				$this->writeCategoryTextFile( $subProjectName, $productFilename, $content );
				$this->writeBrandTextFile( $subProjectName, $productFilename, $content );
			}
		}
	}
	
	function writeBrandTextFile( $subProjectName, $productFilename, $content  )
	{
		$path = $this->setTextFilePath( $subProjectName, 'brands' );
		Helper::make_dir( $path );

		foreach ( $content as $keywordSlug => $data )
		{
			$catFilename = $this->getCategoryFilename( $data['brand'] );
			$file = $path . '/' . $catFilename;
			$line = $this->getFileData( $catFilename, $productFilename, $data['brand'], $data['keyword'] );
 			
			$fh = fopen( $file, 'a' );
		    fwrite( $fh, $line );
		    fclose( $fh );
			
			$catType = 'Brands';
			$catName = $data['brand'];
			$this->printWriteCategoryProcess( $productFilename, $catType, $subProjectName, $catName );
		}
	}
	
	function writeCategoryTextFile( $subProjectName, $productFilename, $content  )
	{
		$path = $this->setTextFilePath( $subProjectName, 'categories' );
		Helper::make_dir( $path );

		foreach ( $content as $keywordSlug => $data )
		{
			$catFilename = $this->getCategoryFilename( $data['category'] );
			$file = $path . '/' . $catFilename;
			$line = $this->getFileData( $catFilename, $productFilename, $data['category'], $data['keyword'] );
 			
			$fh = fopen( $file, 'a' );
		    fwrite( $fh, $line );
		    fclose( $fh );
			
			$catType = 'categories';
			$catName = $data['category'];
			$this->printWriteCategoryProcess( $productFilename, $catType, $subProjectName, $catName );
		}
	}
	
	function setTextFilePath( $subProjectName, $catDir )
	{
		return TEXTDB_PATH . $this->projectName . '/' . $subProjectName . '/' . $catDir . '/';
	}
	
	function getCategoryFilename( $catName )
	{
		$catFilename = Helper::clean_string( $catName );
		return $catFilename . '.txt';
	}
	
	function getFileData($cateFilename, $productFilename, $catName, $keyword )
	{
		return $catName . '|' . $productFilename . '|' . $keyword . PHP_EOL;
	}
	
	function readProductDataFromTextFile( $path )
	{
		return unserialize( file_get_contents( $path ) );
	}
	
	function getFilenameFromPath( $path )
	{
		$arr = explode( '/', $path );
		return str_replace( '.txt', '', end( $arr ) );
	}
	
	function getProductTextFileGroupBySubProject( $productDirs )
	{
		foreach ( $productDirs as $path )
		{
			$arr = explode( '/', $path );
			list(,,,$subProjectName ) = $arr;
			$files[$subProjectName] = glob( $path . '/*.txt' );
		}
		return $files;
	}
	
	function getProductDirectories( $dirs )
	{
		foreach ( $dirs as $dir )
			$productDirs[] = $dir . '/products';
		return $productDirs;
	}
	
	function getProjectDirectories()
	{
		$path = TEXTDB_PATH . $this->projectName . '/*';
		$dirs =  glob( $path,  GLOB_ONLYDIR );
		return $dirs;
	}
	
	function printWriteCategoryProcess( $productFilename, $catTitle, $subProjectName, $catName )
	{
		echo $this->projectName . ': ';
		echo $subProjectName . ': ';
		echo $productFilename . ': ';
		echo $catTitle . ': ';
		echo $catName;
		echo "\n";
	}
}