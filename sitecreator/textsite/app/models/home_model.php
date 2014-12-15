<?php
include APP_PATH . 'traits/home/productItems_trait.php';

class HomeModel extends Controller
{
	use ProductItems;
	
	function carousels()
	{
		echo "Carousel";
	}
}