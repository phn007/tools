<?php
use webtools\controller;
/**
* Scrape Web Content
*/
class ScrapeController extends Controller {
	
	function merchant( $function, $params, $options ) {
		$model = $this->model( 'scraper' );
		$model->initialVariables(  $function, $params, $options  );
		$urlData = $model->getUrlDataFromCsvFile();

		foreach ( $urlData as $row => $item ) {
			$pageinfo = $model->getPageInfo( $item );
			$model->setPageNumber( $pageinfo );
			$pageStart = $model->pageStart;
			$pageEnd = $model->pageEnd;
			$nextPage = true;
		    $currentPage = $pageStart;

		    do {
		    	$url = $model->definePageUrl( $pageinfo, $currentPage );
		    	$productItems = $model->scrapeProductItemsFromWebPage( $url );
		    	$model->insertToDatabase( $productItems, $row, $currentPage );

		    	$nextPage = $currentPage >= $pageEnd ? false : true;
               	$currentPage++;
		    } while( $nextPage );
		}
	}
}
