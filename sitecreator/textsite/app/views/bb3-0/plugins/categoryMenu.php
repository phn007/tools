<?php
class CatMenuList_Plugin {
	use Plugin_GetCatNameList;
	use Permalink;
	use CategoryLink;

	function menuList() {
		$mnListPath = $this->getMenuListFilePath();
		if ( file_exists( $mnListPath ) )
			return $this->getMenuListFromTextFile( $mnListPath );

		$path = $this->getCategoryListForHomepagePath();
		$pathList = $this->getCategoryForHomepageList( $path );
		$catList = $this->getCategoryItems( $pathList );
		$mnList = $this->randomCategoryFromList( $catList );
		$this->saveMenuList( $mnListPath, $mnList );
		return $mnList;

	}

	function saveMenuList( $mnListPath, $mnList ) {
		$mnList = serialize( $mnList );
		file_put_contents( $mnListPath, $mnList );
	}

	function getMenuListFromTextFile( $mnListPath ) {
		return unserialize( file_get_contents( $mnListPath ) );
	}

	function getMenuListFilePath() {
		return CONTENT_PATH . 'menuList.txt';
	}

	function randomCategoryFromList( $catList ) {
		$keys = array_keys( $catList );
		shuffle( $keys );
		$keyList = array_slice( $keys, 0, 4);

		foreach ( $keyList as $key ) {
			$rs[$key] = $catList[ $key ];
		}
		return $rs;
	}

	function getCategoryForHomepageList( $path ) {
		return unserialize( file_get_contents( $path ) );
	}

	function getCategoryListForHomepagePath() {
		return CONTENT_PATH . 'categoryList-for-homepage.txt';
	}
}

trait Plugin_GetCatNameList {
	function getCategoryItems( $pathList ) {
		foreach ( $pathList as $path ) {
			$catSlug = $this->getCategorySlugFromPath( $path );
			$cat = $this->readCategoryNameFromFile( $path, $catSlug );
			$catList[ $cat['catname'] ] = $cat['catlink'];
		}
		return $catList;
	}

	function getCategorySlugFromPath( $path ) {
		$str = str_replace( '.txt', '', $path );
		$arr = explode( '/', $str);
		$filename = end( $arr );
		$arrFilename = explode( '-', $filename );
		$endName = end( $arrFilename );

		if ( is_numeric( $endName ) ) {
			$arrNum = count( $arrFilename );
			$endArrNum = $arrNum - 1;
			unset( $arrFilename[$endArrNum] );
		}

		$filename = implode( '-', $arrFilename );
		return $filename;
	}

	function readCategoryNameFromFile( $path, $catSlug ) {
		$i = 0;
		$items = unserialize( file_get_contents( BASE_PATH . $path ) );
		$keys = array_keys( $items );
		$key = $keys[0];
		$catname = $items[$key]['category'];
		$catlink = $this->getCategoryLink( 'category', $catSlug );

		return array( 'catname' => $catname, 'catlink' => $catlink );
	}
}