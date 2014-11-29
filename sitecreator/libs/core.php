<?php
include 'object.php';
include 'controller.php';
include 'component.php';
include 'widget.php';
include 'cachesite.php';
include 'helper.php';
include 'sammy.php';

//Prosperent API
if ( 'apisite' == SITE_TYPE )
{
   include 'prosperentapi.php';
}

//กำหนด Route
if ( 'textsite' == SITE_TYPE || 'apisite' == SITE_TYPE )
{
   include 'router.php';
   include BASE_PATH . 'config/routes.php';
   $sammy->run();
}
elseif ( 'htmlsite' == SITE_TYPE )
{
   include 'html_router.php';

   //กำหนด Controller
   $controller = $argv[1];

   //กำหนด Action
   $action = $argv[2];

   //กำหนด parameter
   $param1 = isset( $argv[3] ) ? $argv[3] : null;
   $param2 = isset( $argv[4] ) ? $argv[4] : null;
   $params = array( $param1, $param2 );

   //เรียกใช้ Script
   Map::dispatch( $controller, $action, $params );
}
