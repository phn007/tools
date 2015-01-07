<?php
use webtools\controller;
use webtools\libs\Helper;

class TextdbCategoriesModel extends Controller {
	use ReadProductDataAndWriteToTextFile;
	private $projectName;
	
	//Main
	function create( $projectName ) {
		$this->projectName = $projectName;
		$dirs = $this->getProjectDirectories();
		$productDirs = $this->getProductDirectories( $dirs );
		$files = $this->getProductTextFileGroupBySiteDirName( $productDirs );
		$this->readProductDataAndWriteToTextFile( $files);
	}

	function getProjectDirectories() {
		$path = TEXTSITE_PATH . $this->projectName . '/*';
		$dirs =  glob( $path,  GLOB_ONLYDIR );
		
		if ( empty( $dirs ) )
			die( 'Project Directory not found!!!' );
		return $dirs;
	}

	function getProductDirectories( $dirs ) {
		foreach ( $dirs as $dir )
			$productDirs[] = $dir . '/contents/products';
		return $productDirs;
	}

	function getProductTextFileGroupBySiteDirName( $productDirs ) {
		foreach ( $productDirs as $path ) {
			$arr = explode( '/', $path );
			list(,,,$siteDirName ) = $arr;
			$files[$siteDirName] = glob( $path . '/*.txt' );
		}
		return $files;
	}
	
	function printWriteCategoryProcess( $productFilename, $catTitle, $siteDirName, $catName ) {
		echo $this->projectName . ': ';
		echo $siteDirName . ': ';
		echo $productFilename . ': ';
		echo $catTitle . ': ';
		echo $catName;
		echo "\n";
	}
}

trait ReadProductDataAndWriteToTextFile {
	function readProductDataAndWriteToTextFile( $files ) {
		foreach ( $files as $siteDirName => $file ) {
			foreach ( $file as $path ) {
				$productFilename = $this->getFilenameFromPath( $path );
				$content = $this->readProductDataFromTextFile( $path );
				$this->writeCategoryTextFile( $siteDirName, $productFilename, $content );
				$this->writeBrandTextFile( $siteDirName, $productFilename, $content );
			}
		}
	}

	function getFilenameFromPath( $path ) {
		$arr = explode( '/', $path );
		return str_replace( '.txt', '', end( $arr ) );
	}

	function readProductDataFromTextFile( $path ) {
		return unserialize( file_get_contents( $path ) );
	}

	function writeCategoryTextFile( $siteDirName, $productFilename, $content  ) {
		$path = $this->setTextFilePath( $siteDirName, 'categories' );
		Helper::make_dir( $path );

		foreach ( $content as $keywordSlug => $data ) {
			$catFilename = $this->getCategoryFilename( $data['category'] );
			$file = $path . '/' . $catFilename;
			$line = $this->getFileData( $catFilename, $productFilename, $data['category'], $data['keyword'] );
 			
			$fh = fopen( $file, 'a' );
		    fwrite( $fh, $line );
		    fclose( $fh );
			
			$catType = 'categories';
			$catName = $data['category'];
			$this->printWriteCategoryProcess( $productFilename, $catType, $siteDirName, $catName );
		}
	}

	function writeBrandTextFile( $siteDirName, $productFilename, $content  ) {
		$path = $this->setTextFilePath( $siteDirName, 'brands' );
		Helper::make_dir( $path );

		foreach ( $content as $keywordSlug => $data ) {
			$catFilename = $this->getCategoryFilename( $data['brand'] );
			$file = $path . '/' . $catFilename;
			$line = $this->getFileData( $catFilename, $productFilename, $data['brand'], $data['keyword'] );
 			
			$fh = fopen( $file, 'a' );
		    fwrite( $fh, $line );
		    fclose( $fh );
			
			$catType = 'Brands';
			$catName = $data['brand'];
			$this->printWriteCategoryProcess( $productFilename, $catType, $siteDirName, $catName );
		}
	}

	function setTextFilePath( $siteDirName, $catDir ) {
		return TEXTSITE_PATH . $this->projectName . '/' . $siteDirName . '/contents/' . $catDir . '/';
	}
	
	function getCategoryFilename( $catName ) {
		$catFilename = Helper::clean_string( $catName );
		return $catFilename . '.txt';
	}
	
	function getFileData($cateFilename, $productFilename, $catName, $keyword ) {
		return $catName . '|' . $productFilename . '|' . $keyword . PHP_EOL;
	}
}
