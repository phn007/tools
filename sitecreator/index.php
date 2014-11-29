<?php
error_reporting( E_ALL );
//error_reporting(0);

define( 'BASE_PATH', dirname( realpath( __FILE__ ) ) . '/' );
define( 'APP_PATH', BASE_PATH . 'app/' );

/*
 * เรียกข้อมูลจากไฟล์ config.ini
 * ----------------------------------------------------------------------------
*/
include '_console/Lite.php';
$conf = new Config_Lite( BASE_PATH . 'config/config.ini' );
$cfg = $conf->get( 'cfg' );
extract( $cfg );

// print_r( $cfg );
// die();

/*
 * Site type ( textsite, htmlsite, apisite )
 * ----------------------------------------------------------------------------
*/
define( 'SITE_TYPE', $site_type );

/*
 * ตัวแปรที่ใช้ในการ Create Page
 * ----------------------------------------------------------------------------
*/
define( 'CONTENT_PATH', 'contents/' );

//Home URL
define( 'HOME_URL', $domain . '/' );

//Home Link ใช้ในการกำหนด url ในส่วนของ Head ( seo )
define( 'HOME_LINK', HOME_URL );

//Category Items
define( 'CATEGORY_ITEM_PER_PAGE', $num_cat_item_per_page );
define( 'EMPTY_BRAND_NAME', 'Default' );
define( 'EMPTY_CATEGORY_NAME', 'Blank' );

//Product
define( 'RELATED_PRODUCT_NUM', 12 );
//Logo
define( 'LOGO_TEXT', $site_name );

//Network
define( 'NETWORK', $network );
define( 'API_KEY', $api_key );
define( 'SID', $prefix_sid );

//Theme
define( 'THEME_NAME', $theme_name );
define( 'THEME_URL', HOME_URL . 'app/views/' . THEME_NAME . '/' );
define( 'AUTHOR', $site_author );
define( 'SITE_NAME', $site_name );
define( 'SITE_DESC', $site_desc );


if ( 'textsite' == SITE_TYPE )
{
   define( 'CSS_PATH', THEME_URL . 'assets/css/' );
   define( 'JS_PATH', THEME_URL . 'assets/js/' );
}
elseif ( 'htmlsite' == SITE_TYPE )
{
   define( 'CSS_PATH', HOME_URL . 'css/' );
   define( 'JS_PATH', HOME_URL . 'js/' );
}

//สำหรับจัดรูปแบบให้ URL
define( 'PROD_ROUTE', $prod_route );
define( 'FORMAT', $url_format );

//Stat Counter
define( 'SC_PROJECT', $sc_project );
define( 'SC_SECURITY', $sc_security );


//TextSpinner
define( 'TEXTSPIN_PATH',  BASE_PATH . 'files/textspinner/');


if ( 'htmlsite' == SITE_TYPE )
{
   define( 'TEXTDB_PATH', '../textdb/' . $project . '/' . $project_dir . '/' );
   define( 'HTMLSITE_PATH', '../textsite/' . $project . '/' . $site_dir . '/' );
}

/*
 * Include file
 * ----------------------------------------------------------------------------
*/
include 'config/permalink.php';
include BASE_PATH . 'libs/core.php';
