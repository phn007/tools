<?php
class ApiCategoryModel extends AppComponent
{

   function getUrls( $cat_type )
   {
      if ( 'category' == $cat_type )
      {
         $cat_dir = 'categories';
         $cat_path = HOME_URL . 'category/';
      }
      elseif ( 'brand' == $cat_type )
      {
         $cat_dir = 'brands';
         $cat_path = HOME_URL . 'brand/';
      }

      $cpn = $this->component( 'api-category' );
      $cpn->path = CONTENT_PATH . $cat_dir . '/';
      $cpn->cat_path = $cat_path;

      return $cpn->getCategoryList();
   }


   function getListMeta( $title )
   {
      //Cat Link
      $cat_link = HOME_URL . strtolower( $title ) . FORMAT;

      $cpn = $this->component( 'head' );
      $cpn->author = AUTHOR;
      $cpn->title = $title . '|' . SITE_NAME;
      $cpn->description = $title . ' | ' . SITE_DESC;
      $cpn->link = $cat_link;
      $cpn->property_locale = 'en_US';
      $cpn->property_type = 'website';
      $cpn->property_title = $title . '|' .SITE_NAME;
      $cpn->property_description = $title . ' | ' . SITE_DESC;
      $cpn->property_url = $cat_link;
      $cpn->property_site_name = $title . '|' . SITE_NAME;
      $meta = $cpn->getHead();

      $data = null;
      foreach ( $meta as $met )
      {
         $data .= $met . PHP_EOL;
      }
      return $data;
   }





   function getItems( $cat_name, $page, $cat_type )
   {
      //init var
      $products = false;

      //อ่านข้อมูลสินค้าในไฟล์ตามหมายเลข page ที่ส่งเข้าไป
      if ( 'category' == $cat_type )
      {
         $path = CONTENT_PATH . 'categories/';
      }
      elseif ( 'brand' == $cat_type )
      {
         $path = CONTENT_PATH . 'brands/';
      }

      $cpn = $this->component( 'api-category' );
      $cpn->path = $path . $cat_name . '.txt';
      $cpn->page = $page;
      $cpn->num_per_page = CATEGORY_ITEM_PER_PAGE;

      $categories = $cpn->getCategoryFiles();
      $total_page = $categories['total_page'];
      $cat_list = $categories['cat_list'];


      //ตรวจสอบสินค้าตัวไหนที่ยังไม่มีใน textdatabase  ให้ดึงจาก api มาใหม่
      $cpn->path = CONTENT_PATH . 'products/' . $cat_name . '.txt';;
      $cpn->cat_list = $cat_list;
      $response = $cpn->getProductData();


      //ถ้ามีสินค้าใหม่ให้ Save ลงใน contents/products textdatabase
      if ( $response )
      {
         //รวบรวมข้อมูลที่ต้องใช้
         $new_products = $cpn->dataCollection( $response );

         //Save
         $path = CONTENT_PATH . 'products';
         Helper::make_dir( $path );

         $file_path = $path . '/' . $cat_name . '.txt';
         $cpn->SaveProductData( $file_path, $new_products );
      }

      //อ่านข้อมูลสินค้าใน cat_list จาก products textdatabase
      $cpn->path = CONTENT_PATH . 'products/' . $cat_name . '.txt';
      $cpn->cat_list = $cat_list;
      $cpn->cat_type = $cat_type;
      $products = $cpn->getProductFull();

      $items = array(
         'cat_name' => $cat_name,
         'show_items' => $products,
      );


      //สร้าง Navmenu
      $cpn->total_page = $total_page;
      $cpn->current_page = $page;
      $cpn->cat_type = $cat_type;
      $cpn->cat_name = $cat_name;
      $menu = $cpn->menu();


      $products = array(
         'items' => $items,
         'menu' => $menu
      );

      return $products;
   }

   function getItemMeta( $cat_name, $cat_type, $cat_link )
   {
      $cpn = $this->component( 'head' );
      $cpn->author = AUTHOR;
      $cpn->title = $cat_name . '|' . ucfirst( $cat_type );
      //$cpn->description = SITE_DESC;
      $cpn->link = $cat_link;
      $cpn->property_locale = 'en_US';
      $cpn->property_type = 'website';
      $cpn->property_title = $cat_name . '|' . ucfirst( $cat_type );
      //$cpn->property_description = SITE_DESC;
      $cpn->property_url = $cat_link;
      $cpn->property_site_name = $cat_name . '|' . ucfirst( $cat_type ) . '|' . SITE_NAME;
      $meta = $cpn->getHead();

      $data = null;
      foreach ( $meta as $met )
      {
         $data .= $met . PHP_EOL;
      }
      return $data;
   }
}
