<?php
if ( isset( $homePage ) ) {
	include_once 'homeProducts.php';
	include_once 'homeCategoryList.php';
} elseif ( isset( $productPage ) ) {
	include_once 'socialshare.php';
	include_once 'productDetail.php';
}
