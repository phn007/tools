<?php
trait ProductItems
{
	private $productPath;
	private $itemNumber;
	function get()
	{
		//ส่งค่าตัวแปรไปให้ home component
        $cpn->product_path = CONTENT_PATH . 'categories/';
        $cpn->item_number = 15;
	}
}
