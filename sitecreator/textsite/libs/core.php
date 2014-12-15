<?php
include 'object.php';
include 'controller.php';
//include 'cachesite.php';
include 'helper.php';
include 'sammy.php';
include 'router.php';
include BASE_PATH . 'config/routes.php';
$sammy->run();
