<?php
use webtools\controller;
use webtools\libs\Helper;
include WT_APP_PATH . 'traits/textdb/separateCategory_trait.php';
include WT_APP_PATH . 'traits/textdb/mysqldb_trait.php';

class TextdbProductsModel extends Controller {
	use MySQLDatabase;

	use RunThroughQueryResult;
	use CategorySlug;
	use ProductDataGroupByCategory;
	use SeparateCategoryForProsperentNetwork ;
	use WriteTextDatabase;

	use PrintTextDbProductResult;

	private $projectName;
	private $productNumberPerSite;
	private $countProductNumberPerSite = 0;
	private $countSiteNumber = 1;
	private $filename;
	private $siteDirNames = array();
	private $network;

	private $countCategory = array();
	private $categoryId = array();
	private $productDataGroupByCategory = array();

	private $totalGroupByProjectName;
	private $totalCreateProducts = 0;

	//function Main
	function create( $projectName, $merchantData, $siteNumber, $siteDirNames ) {
		$this->projectName = $projectName;
		$this->siteDirNames = $siteDirNames;

		$this->initialMysqlDatabase(); //MySQLDatabase Trait
		$countProductData = $this->countTotalProducts( $merchantData ); //MySQLDatabase Trait
		$totalProducts = $countProductData['totalProducts'];
		$merchantProductNumber = $countProductData['merchantProductNumber'];
		$this->productNumberPerSite = $this->calculateProductNumberPerSite( $totalProducts, $siteNumber );

		foreach ( $merchantData as $merchant => $data ) {
			$dbName = $data['db_name'];
			$this->network = $data['network'];
			$productNumber = $merchantProductNumber[$merchant];
			$sqls = $this->createSQLString( $productNumber ); //MySQLDatabase Trait
			$this->runThroughMysqlStrings( $dbName, $sqls );
		}

		$this->writeTextDbAtRest();
		$this->printConclusionTotal( $totalProducts ); //PrintTextDbProductResult Trait
	}

	function writeTextDbAtRest() {
		if ( isset( $this->productDataGroupByCategory ) ) {
			if ( count( $this->productDataGroupByCategory ) > 0 )
				$this->loopThroughProductPerSiteAndWriteTextDB(); //WriteTextDatabase Trait
		}
	}

	function calculateProductNumberPerSite( $totalProducts, $siteNumber ) {
		return ceil( $totalProducts / $siteNumber );
	}

	function runThroughMysqlStrings( $dbName, $sqls ) {
		foreach ( $sqls as $sql ) {
			$queryResult = $this->getQueryResult( $dbName, $sql ); //MySQLDatabase Trait
			$this->runThroughQueryResult( $queryResult ); //RunThroughQueryResult Trait
		}
	}

	function addIdNumberIntoCategory( $row, $catSlug ) {
		if ( $this->categoryId[$catSlug] !== NULL )
			$row['category'] = $row['category'] . '-' . $this->categoryId[$catSlug];
		return $row['category'];
	}
}

trait RunThroughQueryResult {
	function runThroughQueryResult( $queryResult ) {
		while ( $row = mysqli_fetch_array( $queryResult, MYSQLI_ASSOC ) ) {
			STATIC $count = 1;
			$this->countProductNumberPerSite++;

			$row = $this->checkEmptyCategoryAndBrand( $row );
			$keywordSlug = $this->getKeywordSlug( $row['keyword'] );
			$catSlug = $this->getCategorySlug( $row ); //CategorySlug Trait
			$brandSlug = $this->getBrandSlug( $row );
			$this->initialCategoryIdVariable( $catSlug );
			$this->parseProductDataGroupByCategory( $catSlug, $keywordSlug, $row ); //ProductDataGroupByCategory
			$this->writeSpecificNumOfProductPerCategory( $catSlug );
			$this->writeSpecificNumOfProductPersite();
			$count++;
		}
	}

	function checkEmptyCategoryAndBrand( $row ) {
      	if ( empty( $row['category'] ) ) $row['category'] = EMPTY_CATEGORY_NAME;
      	if ( empty( $row['brand'] ) ) $row['brand'] = EMPTY_BRAND_NAME;
		return $row;
	}

	function getKeywordSlug( $keyword ) {
		return Helper::clean_string( $keyword );
	}

	function getBrandSlug( $row ) {
		return Helper::clean_string( $row['brand'] );
	}

	function initialCategoryIdVariable( $catSlug ) {
		if ( !isset( $this->categoryId[$catSlug]) )
			$this->categoryId[$catSlug] = null;
	}
}

trait WriteTextDatabase {
	function writeSpecificNumOfProductPerCategory( $catSlug ) {
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

	function writeSpecificNumOfProductPersite() {
		if ( $this->countProductNumberPerSite == $this->productNumberPerSite ) {
			$this->loopThroughProductPerSiteAndWriteTextDB();
			$this->productDataGroupByCategory = null;
			unset( $this->productDataGroupByCategory );
			$this->countProductNumberPerSite = 0;
			$this->countSiteNumber++;
		}
	}

	function loopThroughProductPerSiteAndWriteTextDB() {
		foreach ( $this->productDataGroupByCategory as $catSlug => $data ) {
			$this->filename = $this->getTextDBFilename( $catSlug );
			$this->writeTextDatabase( $data );
		}
	}

	function initialCountCategoryVariable( $catSlug ) {
		if ( !isset( $this->countCategory[$catSlug]) )
			$this->countCategory[$catSlug] = null;
	}

	function getTextDBFilename( $catSlug ) {
		if ( $this->categoryId[$catSlug]  !== NULL )
			$catSlug = $catSlug . '-' . $this->categoryId[$catSlug] ;
		return $catSlug . '.txt';
	}

	function writeTextDatabase( $products ) {
		$this->countTotalGroupByProjectName( $products );
		$path = $this->getTextDbPath();
		Helper::make_dir( $path );

		$file = $path . $this->filename;
		$this->printWriteTotalProduct( $products, $file ); //PrintTextDbProductResult Trait
		$products = serialize( $products );
		file_put_contents( $file, $products );
	}

	function countTotalGroupByProjectName( $products ) {
		$num = count( $products );
		$siteDirName = $this->getSiteDirName();

		if ( ! isset( $this->totalGroupByProjectName[ $siteDirName ] ) )
			$this->totalGroupByProjectName[$siteDirName] = 0;

		$this->totalGroupByProjectName[$siteDirName] += $num;
		$this->totalCreateProducts += $num;
	}

	function getTextDbPath() {
		$siteDirName = $this->getSiteDirName();
		$path = TEXTSITE_PATH . $this->projectName . '/' . $siteDirName . '/contents/products/';
		return $path;
	}

	function getSiteDirName() {
		$siteDirIndex = $this->countSiteNumber -1;
		return $this->siteDirNames[$siteDirIndex];
	}
}


trait CategorySlug {
	function getCategorySlug( $row ) {
		if ( $this->network == 'viglink' )
			$catSlug = $this->getCategorySlugFromViglink( $row );
		if ( $this->network == 'prosperent-api' )
			$catSlug = $this->getCategorySlugFromProsperntApi( $row );
		return $catSlug;
	}

	function getCategorySlugFromViglink( $row ) {
		return Helper::clean_string( $row['category'] );
	}

	function getCategorySlugFromProsperntApi( $row ) {
		$catName = $this->getSeparatedCategory( $row['merchant'], $row['category'] ); //SeparateCategoryForProsperentNetwork Trait
		$catSlug = Helper::clean_string( $catName );
		return $catSlug;
	}
}

trait ProductDataGroupByCategory {
	function parseProductDataGroupByCategory( $catSlug, $keywordSlug, $row ) {
		$productData = $this->getProductData( $row );
		$this->productDataGroupByCategory[$catSlug][$keywordSlug] = $productData;
	}

	function getProductData( $row ) {
		$data = array(
			'affiliate_url' => $row['affiliate_url'],
			'image_url'     => $row['image_url'],
			'keyword'       => $row['keyword'],
			'description'   => $row['description'],
			'category'      => $row['category'],
			'price'         => $row['price'],
			'merchant'      => $row['merchant'],
			'brand'         => $row['brand']
		);
		return $data;
	}
}

trait PrintTextDbProductResult {
	function printWriteTotalProduct( $products, $file ) {
		$num = str_pad( count( $products ), 4, "0", STR_PAD_LEFT );
		echo $num . ': ' . $file . "\n";
	}
	
	function printConclusionTotal( $totalProducts ) {
		echo "\nTotal Query Products: " . $totalProducts . "\n";
		echo "Total Create Products: " . $this->totalCreateProducts . "\n";
		foreach ( $this->totalGroupByProjectName as $key => $number ) {
			echo $key . ' => ' . $number;
			echo "\n";
		}
	}	
}
