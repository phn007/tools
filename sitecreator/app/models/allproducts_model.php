<?php

class AllProductsModel extends AppComponent
{

   function getProductList()
   {
      //รับค่า parameter
      $num_file = $this->file_number;
      $num_page = $this->page_number;

      //อ่านไฟล์จาก categories โฟลเดอร์ขึ้นมา
      $path  = BASE_PATH . 'contents/categories/*.txt';
      $files = glob( $path );

      //Component Object
      $cpn = $this->component( 'allproducts' );

      //ตรวจสอบหมายเลข file
      $num_file = $cpn->checkFileNumber( $files, $num_file );

      //ดึงชื่อ path ของไฟล์ออกมา
      $filename = $files[ $num_file - 1 ];

      //อ่านข้อมูลสินค้า
      $data = $cpn->readFile( $filename, $num_page );

      //จำนวนเพจทั้งหมดของ current file
      $num_total_page = $data['num_total_page'];

      //สร้าง Menu
      $url = HOME_URL . 'allproducts/';
      $cpn->url = $url;
      $cpn->files = $files;
      $cpn->num_file = $num_file;
      $cpn->num_page = $num_page;
      $cpn->num_total_page = $num_total_page;

      //Menu
      $menu = $cpn->getMenu();

      //Product List
      $product_list = $data['product_list'];

      //Add Permalink to Product list
      $product_list = $cpn->addPermalink( $product_list, $filename );

      return array( 'products' => $product_list, 'menu' => $menu );
   }

   function permalink()
   {
      return HOME_URL . 'allproducts/' . $this->file_number . '-' . $this->page_number . FORMAT;
   }


   function getMeta( $permalink )
   {
      $cpn = $this->component( 'head' );
      $cpn->robots = 'noindex, follow';
      $cpn->author = AUTHOR;
      $cpn->title = $this->file_number . '-' . $this->page_number . ' | All Products';
      //$cpn->description = SITE_DESC;
      $cpn->link = $permalink;
      $cpn->property_locale = 'en_US';
      $cpn->property_type = 'website';
      $cpn->property_title = $this->file_number . '-' . $this->page_number . ' | All Products';
      //$cpn->property_description = SITE_DESC;
      $cpn->property_url =  $permalink;
      $cpn->property_site_name = 'All Products | ' . SITE_NAME;
      $meta = $cpn->getHead();

      $data = null;
      foreach ( $meta as $met )
      {
         $data .= $met . PHP_EOL;
      }
      return $data;
   }





}
