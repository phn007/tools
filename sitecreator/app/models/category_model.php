<?php
ini_set('memory_limit', '256M');

class CategoryModel extends AppComponent
{

   function getCategoryItems( $cat_name, $page, $cat_type )
   {
      //กำหนด source directory
      if ( 'category' == $cat_type )
      {
         return $this->categoryItems( $cat_name, $page );
      }
      elseif ( 'brand' == $cat_type )
      {
         return $this->brandItems( $cat_name, $page );
      }
   }


   function brandItems( $brand_name, $page )
   {
      $brand_dir = 'brands';
      $cpn = $this->component( 'category' );

      if ( 'textsite' == SITE_TYPE )
      {
         $brand_file = CONTENT_PATH . $brand_dir . '/' . $brand_name . '.txt';
         $cpn->cat_path = CONTENT_PATH . 'categories/';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $brand_file = TEXTDB_PATH . $brand_dir . '/' . $brand_name . '.txt';
         $cpn->cat_path = TEXTDB_PATH . 'categories/';
      }

      $items = file( $brand_file );
      $items = array_map( 'trim', $items );

      foreach ( $items as $item )
      {
         $arr = explode( '|', $item );
         $keyword    = isset( $arr[0] ) ? $arr[0] : null;
         $brand_name = isset( $arr[1] ) ? $arr[1] : null;
         $cat_name   = isset( $arr[2] ) ? $arr[2] : null;
         $filename   = isset( $arr[3] ) ? $arr[3] : null;

         $key_slug = Helper::clean_string( $keyword );
         $brands[ $key_slug ] = array(
            'brand_name' => $brand_name,
            'cat_name' => $cat_name,
            'filename' => $filename,
         );

      }

      $cpn->brands = $brands;
      $brands = $cpn->getBrandItems();

      //แบ่งสินค้าตามหมายเลขเพจ
      $cpn->page = $page;
      $cpn->per_page = CATEGORY_ITEM_PER_PAGE;
      $list = $cpn->getBrandItemForPage( $brands );
      $item_list = $list['brand_list'];
      $total_page = $list['total_page'];


      //ข้อมูลรายการสินค้าที่จะส่งกลับไป
      $items = array(
         'cat_name' => $brand_name,
         'show_items' => $item_list,
      );

      //สร้าง Navmenu
      $brand_slug = Helper::clean_string( $brand_name );
      $cpn->total_page = $total_page;
      $cpn->current_page = $page;
      $cpn->cat_type = 'brand';
      $cpn->cat_name = $brand_slug;
      $menu = $cpn->menu();

      return array(
         'items' => $items,
         'menu' => $menu
      );
   }



   function categoryItems( $cat_name, $page )
   {
      $cat_type = 'category';
      $cat_dir = 'categories';

      $cpn = $this->component( 'category' );

      if ( 'textsite' == SITE_TYPE )
      {
         $cpn->path = CONTENT_PATH . $cat_dir . '/' . $cat_name . '.txt';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $cpn->path = TEXTDB_PATH . $cat_dir . '/' . $cat_name . '.txt';
      }

      $cpn->per_page = CATEGORY_ITEM_PER_PAGE;
      $cpn->page = $page;

      //อ่านไฟล์ใน categories
      $products = $cpn->getFiles();

      if ( $products )
      {
         $files = $cpn->getCategoryFiles( $products );
         $cat_list = $files['cat_list'];
         $total_page = $files['total_page'];

         //กำหนดรายการสินค้า
         $item_list = $cat_list;

         //เพิ่ม permalink เข้าไปในอะเรย์ของรายการสินค้า
         $item_list = $cpn->addPermalink( $item_list, $cat_name, $cat_type );

         //ข้อมูลรายการสินค้าที่จะส่งกลับไป
         $items = array(
            'cat_name' => $cat_name,
            'show_items' => $item_list,
         );

         //สร้าง Navmenu
         $cpn->total_page = $total_page;
         $cpn->current_page = $page;
         $cpn->cat_type = 'category';
         $cpn->cat_name = $cat_name;
         $menu = $cpn->menu();

         return array(
            'items' => $items,
            'menu' => $menu
         );
      }
      else
      {
         die( "Category file not found" );
      }
   }

   function categoryItems__( $cat_name, $page, $cat_type )
   {
      //กำหนด source directory
      if ( 'category' == $cat_type )
      {
         $cat_dir = 'categories';
      }
      elseif ( 'brand' == $cat_type )
      {
         $cat_dir = 'brands';
      }


      $cpn = $this->component( 'category' );

      if ( 'textsite' == SITE_TYPE )
      {
         $cpn->path = CONTENT_PATH . $cat_dir . '/' . $cat_name . '.txt';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $cpn->path = TEXTDB_PATH . $cat_dir . '/' . $cat_name . '.txt';
      }

      $cpn->per_page = CATEGORY_ITEM_PER_PAGE;
      $cpn->page = $page;

      //อ่านไฟล์ใน categories
      $products = $cpn->getFiles();

      if ( $products )
      {
         $files = $cpn->getCategoryFiles( $products );
         $cat_list = $files['cat_list'];
         $total_page = $files['total_page'];

         //กำหนดรายการสินค้า
         if ( 'category' == $cat_type )
         {
            $item_list = $cat_list;
         }
         elseif ( 'brand' == $cat_type )
         {
            $cpn->cat_list = $cat_list;

            if ( 'textsite' == SITE_TYPE )
            {
               $cpn->path = CONTENT_PATH . 'categories/';
            }
            elseif ( 'htmlsite' == SITE_TYPE )
            {
               $cpn->path = TEXTDB_PATH . 'categories/';
            }
            $item_list = $cpn->getBrandItems();
         }


         //เพิ่ม permalink เข้าไปในอะเรย์ของรายการสินค้า
         $item_list = $cpn->addPermalink( $item_list, $cat_name, $cat_type );

         //ข้อมูลรายการสินค้าที่จะส่งกลับไป
         $items = array(
            'cat_name' => $cat_name,
            'show_items' => $item_list,
         );

         //สร้าง Navmenu
         $cpn->total_page = $total_page;
         $cpn->current_page = $page;
         $cpn->cat_type = $cat_type;
         $cpn->cat_name = $cat_name;
         $menu = $cpn->menu();

         return array(
            'items' => $items,
            'menu' => $menu
         );
      }
      else
      {
         die( $cat_type . " file not found" );
      }
   }

   function getItemMeta( $cat_name, $cat_type, $cat_link )
   {
      $cpn = $this->component( 'head' );
      $cpn->robots = 'noindex, follow';
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


   /*
    * Categoies ( urls list )
    * -------------------------------------------------------------------------
   */
   function getUrls( $cat_type )
   {
      if ( 'category' == $cat_type ) $cat_dir = 'categories';
      elseif ( 'brand' == $cat_type ) $cat_dir = 'brands';

      $cpn = $this->component( 'category' );

      if ( 'textsite' == SITE_TYPE )
      {
         $cpn->path = CONTENT_PATH . $cat_dir . '/';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $cpn->path = TEXTDB_PATH . $cat_dir . '/';
      }


      $cpn->cat_type = $cat_type;

      if ( 'brand' == $cat_type )
      {
         $url_list = $cpn->getBrandList();
      }
      elseif ( 'category' == $cat_type )
      {
         $url_list = $cpn->getList( $cat_type );
      }

      return $url_list;
   }

   function getListMeta( $title )
   {
      //Cat Link
      $cat_link = HOME_URL . strtolower( $title ) . FORMAT;

      $cpn = $this->component( 'head' );
      $cpn->robots = 'noindex, follow';
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
}
