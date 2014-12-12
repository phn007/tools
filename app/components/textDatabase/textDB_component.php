<?php
use webtools\libs\Helper;

class TextDBComponent
{
	function getKeywordSlug( $keyword )
	{
		return Helper::clean_string( $keyword );
	}
	
	function getSlug( $row, $catType )
	{
		$catName = $this->getSeparatedCategory( $row['merchant'], $row[$catType] );
     	$catSlug = Helper::clean_string( $catName );
		return $catSlug;
	}
	
	function checkEmptyCategoryAndBrand( $row )
	{
		//ตรวจสอบและแทนค่าว่าง
      	if ( empty( $row['category'] ) ) $row['category'] = EMPTY_CATEGORY_NAME;
      	if ( empty( $row['brand'] ) ) $row['brand'] = EMPTY_BRAND_NAME;
		return $row;
	}
	
	function checkSeparatorCategoryFileExist( $path )
	{
		if ( ! file_exists( $path ) )
			die( 'separator_category.txt: ' . $path . ': File not found!!!' );
	}
	
	function readSeparatorCategoryFile( $path )
	{
		//อ่านไฟล์ขึ้นมา
		$files = file( $path );
		$files = array_map( 'trim', $files );
		return $files;
	}
	
	function parseSeparatorData( $files )
	{
		//แยกข้อมูลแล้วเก็บไว้ในอะเรย์
		foreach ( $files as $file )
		{
			$arr = explode( '|', $file );
			$separators[$arr[0]] = $arr[1];
		}
		return $separators;
	}
	
	function checkMerchantSeparatorExist( $merchant, $separators )
	{
		//ตรวจสอบว่ามีข้อมูลของ merchant ที่ส่งเข้ามาหรือเปล่า
		if ( ! array_key_exists( $merchant, $separators ) )
			die( $merchant . ': There is no separator' );
	}
	
	function separateCategory( $separator, $category )
	{
		$cats = explode( $separator, $category );

		//ลบ array ที่เป็นค่าว่างออก
		$cats = array_filter( $cats );

		//ดึงเอาชื่อ category ตัวสุดท้ายไปใช้งาน
		$catName = end( $cats );
		return $catName;
	}
	
	function getSeparatedCategory( $merchant, $category )
	{
		$path =  FILES_PATH . 'separator_category.txt';
		$this->checkSeparatorCategoryFileExist( $path );
		$files = $this->readSeparatorCategoryFile( $path );
		$separators = $this->parseSeparatorData( $files );
		
		$this->checkMerchantSeparatorExist( $merchant, $separators );
		$separator = $separators[ $merchant ];

		//แยก category
		if ( ! empty( $separator ) )
			$catName = $this->separateCategory( $separator, $category );
		else
			$catName = $category;
		return $catName;
	}
	
	function getProductData( $row )
	{
		$data = array(
			'affiliate_url' => $row['affiliate_url'],
			'image_url'     => $row['image_url'],
			'keyword'       => $row['keyword'],
			'description'   => $row['description'],
			'category'      => $row['category'],
			'price'         => $row['price'],
			'merchant'      => $row['merchant'],
			'brand'         => $row['brand']
		);
		return $data;
	}
}
