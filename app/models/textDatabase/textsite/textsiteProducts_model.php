<?php
use webtools\controller;
use webtools\libs\Helper;

class TextSiteProductsModel extends Controller
{
	private $dbCom;
	private $textDBCom;

	private $projectName;
	private $productNumberPerSite;
	private $countProductNumberPerSite = 0;
	private $countSiteNumber = 1;
	private $filename;

	private $countCategory = array();
	private $categoryId = array();
	private $productDataGroupByCategory = array();

	private $totalGroupByProjectName;
	private $totalCreateProducts = 0;

	//function Main
	function create( $projectName, $merchantData, $siteNumber )
	{
		$this->projectName = $projectName;

		$this->dbCom = $this->component( 'textDatabase/database' );
		$this->textDBCom = $this->component( 'textDatabase/textDB' );

		$countProductData = $this->dbCom->countTotalProducts( $merchantData );
		$totalProducts = $countProductData['totalProducts'];
		$merchantProductNumber = $countProductData['merchantProductNumber'];
		$this->productNumberPerSite = $this->textDBCom->calculateProductNumberPerSite( $totalProducts, $siteNumber );

		foreach ( $merchantData as $merchant => $data )
		{
			$dbName = $data['db_name'];
			$productNumber = $merchantProductNumber[$merchant];
			$sqls = $this->dbCom->createSQLString( $productNumber );
			$this->runThroughMysqlStrings( $dbName, $sqls );
		}

		if ( count( $this->productDataGroupByCategory ) > 0 )
			$this->loopThroughProductPerSiteAndWriteTextDB();
		$this->printConclusionTotal( $totalProducts );
	}

	function runThroughMysqlStrings( $dbName, $sqls )
	{
		foreach ( $sqls as $sql )
		{
			$queryResult = $this->dbCom->getQueryResult( $dbName, $sql );
			$this->runThroughQueryResult( $queryResult );
		}
	}

	function runThroughQueryResult( $queryResult )
	{
		while ( $row = mysqli_fetch_array( $queryResult, MYSQLI_ASSOC ) )
        {
			STATIC $count = 1;
			$this->countProductNumberPerSite++;

			$row = $this->textDBCom->checkEmptyCategoryAndBrand( $row );
			$keywordSlug = $this->textDBCom->getKeywordSlug( $row['keyword'] );
			$catSlug     = $this->textDBCom->getSlug( $row, 'category' );
			$brandSlug   = $this->textDBCom->getSlug( $row, 'brand' );

			$this->initialCategoryIdVariable( $catSlug );
			//$row['category'] = $this->addIdNumberIntoCategory( $row, $catSlug );
			$this->parseProductDataGroupByCategory( $catSlug, $keywordSlug, $row );

			$this->writeSpecificNumOfProductPerCategory( $catSlug );
			$this->writeSpecificNumOfProductPersite();
			$count++;
		}
	}

	function addIdNumberIntoCategory( $row, $catSlug )
	{
		if ( $this->categoryId[$catSlug] !== NULL )
			$row['category'] = $row['category'] . '-' . $this->categoryId[$catSlug];
		return $row['category'];
	}

	function parseProductDataGroupByCategory( $catSlug, $keywordSlug, $row )
	{
		$productData = $this->textDBCom->getProductData( $row );
		$this->productDataGroupByCategory[$catSlug][$keywordSlug] = $productData;
	}

	function writeSpecificNumOfProductPerCategory( $catSlug )
	{
		$this->initialCountCategoryVariable( $catSlug );
		if ( ++$this->countCategory[$catSlug] >= 1000  )
		{
			$this->filename = $this->getTextDBFilename( $catSlug );
			$this->writeTextDatabase( $this->productDataGroupByCategory[$catSlug] );
			$this->categoryId[$catSlug] = $this->categoryId[$catSlug] + 1;
			$this->countCategory[$catSlug] = null;
			$this->productDataGroupByCategory[$catSlug] = null;
			unset( $this->productDataGroupByCategory[$catSlug] );
		}
	}

	function writeSpecificNumOfProductPersite()
	{
		if ( $this->countProductNumberPerSite == $this->productNumberPerSite )
		{
			$this->loopThroughProductPerSiteAndWriteTextDB();
			$this->productDataGroupByCategory = null;
			unset( $this->productDataGroupByCategory );
			$this->countProductNumberPerSite = 0;
			$this->countSiteNumber++;
		}
	}

	function loopThroughProductPerSiteAndWriteTextDB()
	{
		foreach ( $this->productDataGroupByCategory as $catSlug => $data )
		{
			$this->filename = $this->getTextDBFilename( $catSlug );
			$this->writeTextDatabase( $data );
		}
	}

	function countTotalGroupByProjectName( $products )
	{
		$num = count( $products );
		$subProjectName = $this->getSubProjectName();

		if ( ! isset( $this->totalGroupByProjectName[ $subProjectName ] ) )
			$this->totalGroupByProjectName[$subProjectName] = 0;

		$this->totalGroupByProjectName[$subProjectName] += $num;
		$this->totalCreateProducts += $num;
	}

	function writeTextDatabase( $products )
	{
		$this->countTotalGroupByProjectName( $products );
		$path = $this->getTextDbPath();
		Helper::make_dir( $path );

		$file = $path . $this->filename;
		$this->printWriteTotalProduct( $products, $file );
		$products = serialize( $products );
		file_put_contents( $file, $products );
	}

	function getTextDBFilename( $catSlug )
	{
		if ( $this->categoryId[$catSlug]  !== NULL )
			$catSlug = $catSlug . '-' . $this->categoryId[$catSlug] ;
		return $catSlug . '.txt';
	}

	function getSubProjectName()
	{
		return $this->projectName . $this->countSiteNumber;
	}

	function getTextDbPath()
	{
		$subProjectName = $this->getSubProjectName();
		$path = TEXTDB_PATH . $this->projectName . '/' . $subProjectName . '/products/';
		return $path;
	}

	function initialProductDataGroupByCategory( $catSlug, $keywordSlug )
	{
		if ( isset( $this->productDataGroupByCategory[$catSlug][$keywordSlug] ) )
			$this->productDataGroupByCategory[$catSlug][$keywordSlug] = null;
	}

	function initialCountCategoryVariable( $catSlug )
	{
		if ( !isset( $this->countCategory[$catSlug]) )
			$this->countCategory[$catSlug] = null;
	}

	function initialCategoryIdVariable( $catSlug )
	{
		if ( !isset( $this->categoryId[$catSlug]) )
			$this->categoryId[$catSlug] = null;
	}

	function printWriteTotalProduct( $products, $file )
	{
		$num = str_pad( count( $products ), 4, "0", STR_PAD_LEFT );
		echo $num . ': ' . $file . "\n";
	}
	
	function printConclusionTotal( $totalProducts )
	{
		echo "\nTotal Query Products: " . $totalProducts . "\n";
		echo "Total Create Products: " . $this->totalCreateProducts . "\n";
		foreach ( $this->totalGroupByProjectName as $key => $number )
		{
			echo $key . ' => ' . $number;
			echo "\n";
		}
	}	
}
