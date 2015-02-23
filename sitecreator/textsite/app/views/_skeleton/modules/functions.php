<?php
include_once 'html.php';

$header = Html::get( 'main', 'header' );
$footer = Html::get( 'main', 'footer' );

switch ( $currentPage ) {
	case 'home-page':
		$home = homePage( $productItems, $categoryList, $brandList );
		break;
	case 'product-page':
		$product = productPage( $productDetail, $spinContent, $relatedProducts, $menu );
		break;
	case 'category-page':
		$category = categoryPage( $category, $menu, $catType );
		break;
	case 'brand-page':
		$category = categoryPage( $category, $menu, $catType );
		break;
	case 'categories-page':
		$categories = categoriesPage( $categories, $menu, $catType );
		break;
	case 'brands-page':
		$categories = categoriesPage( $categories, $menu, $catType );
		break;
}

/**
 * FUNCTIONS SECTION
 * ----------------------------------------------------------------------------------------------------------
*/
function homePage( $productItems=null, $categoryList=null, $brandList=null ) {
	if ( isset( $productItems ) ) {
		$topProductList = Html::get( 'homepage', 'topProductList', $productItems );
		$productList = Html::get( 'homepage', 'productList', $productItems );
	}
	if ( isset( $categoryList ) && isset( $brandList ) ) {
		$categoryLinkList = Html::get( 'homepage', 'categoryLinkList', array( $categoryList, $brandList ) );
	}
	return array(
		'topProductList' => $topProductList,
		'productList' => $productList,
		'categoryLinkList' => $categoryLinkList
	);
}

function productPage( $productDetail, $spinContent, $relatedProducts, $menu ) {
	$content = array( 'product-detail' => $productDetail, 'spin-content' => $spinContent );
	return array(
		'breadcrumb' => Html::get( 'productpage', 'breadcrumb', $productDetail ),
		'productDetail' => Html::get( 'productpage', 'productDetail', $content ),
		'relatedProducts' => Html::get( 'productpage', 'relatedProducts', $relatedProducts ),
		'menu' => Html::get( 'productpage', 'navmenu', $menu )
	);
}

function categoryPage( $category, $menu, $catType ) {
	return array(
		'items' => Html::get( 'categorypage', 'categoryItems', $category ),
		'menu' => Html::get( 'categorypage', 'menu', $menu ),
		'type' => $catType
	); 
}

function categoriesPage( $categories, $menu, $catType ) {
	return array(
		'links' => Html::get( 'categoriespage', 'categories', $categories ),
		'menu' => Html::get( 'categoriespage', 'menu', $menu ),
		'type' => $catType
	);
}