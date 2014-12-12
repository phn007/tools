<?php

define( 'WT_BASE_PATH', dirname( realpath( __FILE__ ) ) . '/' );
define( 'WT_APP_PATH', WT_BASE_PATH . 'app/' );
define( 'CONSOLE_PATH', WT_BASE_PATH . '_console/');
define( 'FILES_PATH', WT_BASE_PATH . 'files/' );

define( 'DEVELOP_PATH', 'C:\xampp\htdocs/' );
//define( 'DEVELOP_PATH', 'D:\htdocs/' );
//define( 'DEVELOP_PATH', '/var/www/' );

//define( "HOST_PATH", 'C:\xampp\htdocs/' );
define( "HOST_PATH", '/var/www/' );


//Site Generator
//define( "CALL_CREATOR", 'sitegenerator' );
//define( 'SITE_CREATOR_PATH', WT_BASE_PATH . 'sitegenerator/');
define( "CALL_CREATOR", 'sitecreator' );
define( 'SITE_CREATOR_PATH', WT_BASE_PATH . 'sitecreator/');


define( 'TEXTDB_PATH', '../textdb/' );
define( 'TEXTSITE_PATH', '../textsite/' );
//define( 'HTMLSITE_PATH', '../htmlsite/' );
define( 'CONFIG_PATH', 'configs/' );

//TextSpinner
define( 'TEXTSPIN_PATH', FILES_PATH . 'textspinner/');

define( 'EMPTY_BRAND_NAME', 'Default' );
define( 'EMPTY_CATEGORY_NAME', 'Blank' );