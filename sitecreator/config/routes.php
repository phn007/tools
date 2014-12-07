<?php

if ( 'textsite' == SITE_TYPE )
{
	include 'configs/routes-textsite.php';
}
elseif ( 'htmlsite' == SITE_TYPE )
{
	include 'configs/routes-htmlsite.php';
}
elseif ( 'apisite' == SITE_TYPE )
{
	include 'configs/routes-apisite.php';
}
