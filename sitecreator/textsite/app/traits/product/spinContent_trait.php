<?php
trait SpinContent {

	function setSpinContent() {
		$c = new Cache();
		$path = 'cache/spin-content';
		$cacheFile = $this->productFile;
		$cacheKey = $this->productKey;
		$cache = $c->get_cache( $path, $cacheFile, $cacheKey ); //Retrieve data from cache

		if ( $cache == null ){
			$this->spinCom = $this->component( 'textspinner' );
			$this->wordlib_path = TEXTSPIN_PATH . 'WordLibrary.csv';
			$this->text_path = TEXTSPIN_PATH . '*.txt';
			$text = $this->spin();
			$cache = array( $cacheKey => $text );
			$c->set_cache( $path, $cacheFile, $cache ); //Save data to cache
		}
		return $this->keywordReplacing( $cache, $this->productDetail['keyword'] );
	}

	function spin() {
		$files = $this->readFilenameListFromTextAds();
		$this->checkIssetFiles( $files );
		$exclude = array( 'spam' );

		foreach ( $files as $path ) {
			$filename = $this->parseFilenameFromPath( $path );
			if ( !in_array( $filename, $exclude ) ) {
				$spinText = $this->spinCom->spinText( $this->wordlib_path, $path );//textspinner Component
				$text[$filename] = trim( $spinText ); 
			}
		}
		return $this->addAccentHtmlTagToAds( $text );
	}

	function parseFilenameFromPath( $path ) {
		$arr = explode( '/', $path ); //กำหนดชื่อไฟล์
		return str_replace( '.txt', '', end( $arr ) );
	}

	function checkIssetFiles( $files ) {
		if ( ! isset( $files ) )
			die( "runSpinContent: file not found!!!" );
	}

	function readFilenameListFromTextAds() {
		$files = glob( $this->text_path ); //อ่านชื่อไฟล์ของ text ads
		sort( $files );
		return $files;
	}

	function addAccentHtmlTagToAds( $text ) {
		$text['ad1'] = $this->addHtmlTag( $text['ad1'] );
		$text['ad2'] = $this->addHtmlTag( $text['ad2'] );
		$text['ad_desc'] = $this->addHtmlTag( $text['ad_desc'] );
		return $text;
	}

	function addHtmlTag( $ads ) {
		$arr = array(
			'<strong>#keyword#</strong>',
			'<em>#keyword#</em>',
			'<u>#keyword#</u>',
		);
		shuffle( $arr );
		return preg_replace('/#keyword#/', $arr[0], $ads, 1 );
	}

	function keywordReplacing( $text, $keyword ) {
		foreach ( $text as $key => $string ) {
			$data[$key] = str_replace( '#keyword#', $keyword, $string );
		}
		return $data;
	}
}