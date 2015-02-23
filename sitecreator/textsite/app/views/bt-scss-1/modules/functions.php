<?php
include_once 'html.php';

$headerHtml = Html::get( 'main', 'header' );
$footerHtml = Html::get( 'main', 'footer' );
if ( isset( $homePage ) ) {
	$topProductListHtml = Html::get( 'homepage', 'topProductList', $productItems );
	$productList125_1Html = Html::get( 'homepage', 'productList125', $productItems );
	$categoryLinkListHtml = Html::get( 'homepage', 'categoryLinkList', array( $categoryList, $brandList) );
} elseif ( isset( $productPage ) ) {
	$content = array(
		'product-detail' => $productDetail,
		'spin-content' => $spinContent
	);
	$breadcrumbHtml = Html::get( 'productpage', 'breadcrumb', $productDetail );
	$productDetailHtml = Html::get( 'productpage', 'productDetail', $content );
	$relatedProductsHtml = Html::get( 'productpage', 'relatedProducts', $relatedProducts );
	$navmenu = array(
		'url' => $menuUrl,
		'state' => $menuState
	);
	$navmenuHtml = Html::get( 'productpage', 'navmenu', $navmenu );
} elseif ( isset( $catItemsPage ) ) {
	$categoryItemsHtml = Html::get( 'categorypage', 'categoryItems', $categoryItems );
} elseif ( isset( $brandItemsPage ) ) {
	$brandItemsHtml = Html::get( 'brandpage', 'brandItems', $brandItems );
} 
