<?php
use webtools\controller;
use webtools\libs\Helper;
include WT_APP_PATH . 'extensions/scraper-class/_simpleHtmlDom.php';
include WT_APP_PATH . 'traits/scraper/database_trait.php';

class scrapeDetailModel extends Controller {
	use SelectProductUrls;
	use ProductDatabase;
	use GetContentFromWebPage;
	use ScrapeProductDetial;

	function getProductDetail( $merchant ) {
		$this->merchantName = $merchant;
		$this->LoadMerchantScraper( $this->merchantName );
		$this->merchantUrl = setMerchantUrl(); //merchantName_scraper.php (ex. nordstrom_scraper.php, zappos_scraper.php)
		
		$productUrls = $this->selectProductUrlsFromDatabase();
		$result = $this->getContentFromWebPage( $productUrls );
		$result = $this->scrapeProductDetail( $result );
	}
}

trait ScrapeProductDetial {
	function scrapeProductDetail( $result ) {
		$data = array();
		$scrape = new ProductDetail(); //merchantName_scraper.php (ex. nordstrom_scraper.php, zappos_scraper.php)
		foreach ( $result as $id => $content ) {
			$html = $this->getHtml( $content );
			$data[$id] = $scrape->get( $html );
			$html->clear();
        	unset( $html );
		}
		return $data;
	}

	function getHtml( $content ) {
		$html = str_get_html( $content );
		if ( !$html ) die( "Empty Html Content: Make Log File" );
		return $html;
	}
}

trait GetContentFromWebPage {
	function getContentFromWebPage( $productUrls ) {

		$options = array(
			CURLOPT_USERAGENT => $this->setUserAgent(),
			CURLOPT_HTTPHEADER => $this->setHeader(),
			CURLOPT_REFERER => $this->merchantUrl
		);
		return MultiCurl::request( $productUrls, $options );
	}

	function setUserAgent() {
		return 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0';
	}

	function setHeader() {
		return array( "Accept-Language: en-US;q=0.6,en;q=0.4" );
	}
}



trait SelectProductUrls {
	function selectProductUrlsFromDatabase() {
		$db = new Database();
		$conn = $db->connectDatabase(); 
		$dbName = $this->databaseName(); //database_trait.php
		$db->selectDatabase( $conn, $dbName );
		$sql = $this->createSql( $dbName );
		return $this->getQueryResult( $conn, $dbName, $sql );
	}

	function getQueryResult( $conn, $dbName, $sql ) {
		$data = array();
		if ( ! $result = mysqli_query( $conn, $sql ) )
			die( "Cannot Query " . $dbName . "Database" );

		while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) ) {
			$data[$row['id']] = $row['affiliate_url'];
		}
		return $data;
	}

	function createSql( $dbName ) {
		return "SELECT id,affiliate_url FROM products WHERE status = 0 LIMIT 1";
	}
}


class MultiCurl {
	static function request( $data, $options = array() ) {
		$ch = array(); // array of curl handles
		$result = array(); // data to be returned
		$mh = curl_multi_init(); // multi handle

		// loop through $data and create curl handles then add them to the multi-handle
		foreach ( $data as $id => $d ) {
			$ch[ $id ] = curl_init();
			$url = ( is_array( $d ) && !empty( $d['url']) ) ? $d['url'] : $d;
			curl_setopt( $ch[ $id ], CURLOPT_URL,            $url );
			curl_setopt( $ch[ $id ], CURLOPT_HEADER,         0 );
			curl_setopt( $ch[ $id ], CURLOPT_RETURNTRANSFER, 1 );

			if ( is_array( $d ) ) { // post?
				if ( ! empty( $d['post'] ) ) {
					curl_setopt( $ch[$id], CURLOPT_POST,       1 );
					curl_setopt( $ch[$id], CURLOPT_POSTFIELDS, $d['post'] );
				}
			}

			if ( ! empty( $options ) ) { // extra options?
				curl_setopt_array( $ch[$id], $options );
			}
			curl_multi_add_handle( $mh, $ch[$id] );
		}

		// execute the handles
		$running = null;
		do {
			curl_multi_exec( $mh, $running );
		} while( $running > 0 );

		// get content and remove handles
		foreach( $ch as $id => $c ) {
			$result[ $id ] = curl_multi_getcontent( $c );
			curl_multi_remove_handle( $mh, $c );
		}
		// all done
		curl_multi_close( $mh );
		return $result;
	}
}