<?php

class HomeComponent extends CacheSite
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }

   function separate_product( $products )
   {
      $splice = array_splice( $products, 0, 3 );

      //slide product and list of the rest
      $data = array(
         'slide_products' => $splice,
         'product_list' => $products
      );
      return $data;
   }

   function categoryListHome()
   {
      $files  = glob( $this->category_path . '*.txt' );
      $file_num = count( $files );

      //validate show category number
      $show_cat_num = $file_num < $this->show_cat_num ? $file_num : $this->show_cat_num ;

      return array_slice( $files, 0, $show_cat_num );
   }


   function brandListHome()
   {
      $files  = glob( $this->brand_path . '*.txt' );
      $file_num = count( $files );

      //validate show category number
      $show_cat_num = $file_num < $this->show_cat_num ? $file_num : $this->show_cat_num ;

      return array_slice( $files, 0, $show_cat_num );
   }


   function getCatnameByPath( $path )
   {
      $product = unserialize( file_get_contents( $path ) );
      $key = key( $product );
      $cat_name = $product[$key]['category'];
      return $cat_name;
   }


   function getBrandnameByPath( $path )
   {
      $brand_name = false;

      $items = file( $path );
      $items = array_map( 'trim', $items );
      $arr = explode( '|', $items[0] );

      if ( isset( $arr[1] ) )
         $brand_name = $arr[1];

      return $brand_name;
   }

   function getBrandUrl( $path )
   {
      $arr = explode( '/', $path );
      $slug = end( $arr );
      $slug = str_replace( '.txt', '', $slug );
      $url = HOME_URL . 'brand/' . $slug . '/page-1.html';
      return $url;
   }

   function getCategoryUrl__( $path )
   {
      $arr = explode( '/', $path );
      $cat_type = null;

      //หมายเลข array ตัวรองสุดท้าย
      $count = count( $arr ) - 2;

      //ชื่อชนิดของ cat: categories หรือ brands
      $type_name = $arr[ $count ];

      if ( 'categories' == $type_name )
         $cat_type = 'category';

      elseif ( 'brands' == $type_name )
         $cat_type = 'brand';

      $slug = end( $arr );
      $slug = str_replace( '.txt', '', $slug );
      $url = HOME_URL . $cat_type . '/' . $slug . '/page-1.html';
      return $url;
   }


   function getCategoryUrl( $path )
   {
      $arr = explode( '/', $path );
      $cat_type = null;

      //หมายเลข array ตัวรองสุดท้าย
      $count = count( $arr ) - 2;

      //ชื่อชนิดของ cat: categories หรือ brands
      $type_name = $arr[ $count ];

      if ( 'categories' == $type_name )
         $cat_type = 'category';

      elseif ( 'brands' == $type_name )
         $cat_type = 'brand';

      $slug = end( $arr );
      $slug = str_replace( '.txt', '', $slug );


      $sl_arr = explode( '_', $slug );

      if ( isset( $sl_arr[1] ) )
      {
         $num = $sl_arr[1];
      }
      else
      {
         $num = null;
      }

      $url = HOME_URL . $cat_type . '/' . $slug . '/page-1.html';
      return array( 'url' => $url, 'num' => $num );
   }




   /*
    * TextSite Section
    * -------------------------------------------------------------------------
   */

   //Random Textdatabase ของ Product ออกมา 1 ไฟล์
   function getProductFile()
   {
      if ( !file_exists( $this->product_path ) )
      {
         trigger_error( 'My Debug: Products Path does not exist' , $error_type = E_USER_ERROR );
      }


      $files = glob( $this->product_path . "*.txt" );
      shuffle( $files );
      return $files[0];
   }

   function textSiteProductList()
   {
      $data = false;

      $c = new CacheSite();
      $cache = $c->get( $this->cache_path, $this->cache_name, $this->cache_time );

      if ( $cache == NULL )
      {
         //Random Textdatabase ของ Product ออกมา 1 ไฟล์
         $product_file = $this->getProductFile();

         //อ่านไฟล์จาก Textdatabase ( products )
         $products  = file_get_contents( $product_file );
         $products  = unserialize( $products );

         //Random Key ของอะเรย์ข้อมูลสินค้าตาม item_number ที่ถูกส่งมาจาก home model
         $keys = array_keys( $products );
         $count = count( $keys );
         $num = $count > $this->item_number ? $this->item_number : $count;

         //ถ้าสินค้าในอะเรย์มีมากกว่า 1
         if ( $num > 1 )
         {
            $rand_number = array_rand( $keys, $num );
            foreach( $rand_number as $number )
            {
               $rand_keys[] = $keys[$number];
            }
            $cache = array( 'product_file' => $product_file, 'rand_keys' => $rand_keys );
         }
         else
         {
            $key = key( $products );
            $rand_keys = array( $key );
            $cache = array( 'product_file' => $product_file, 'rand_keys' => $rand_keys );
         }

         //Save Cache ถ้ามีสินค้าเท่ากับจำนวนที่ถูกกำหนดมา
         if ( $num == $this->item_number )
         {
            $this->set( $this->cache_path, $this->cache_name, $cache );
         }
      }
      else
      {
         //ดึง Path ของไฟล์ออกมาจาก cache
         $product_file = $cache['product_file'];

         //อ่านไฟล์จาก Textdatabase ( products )
         $products  = file_get_contents( $product_file );
         $products  = unserialize( $products );
      }

      //วนลูปผ่าน array keys ของสินค้า
      foreach ( $cache['rand_keys'] as $key )
      {
         //เก็บข้อมูลที่ random มาได้ลงในตัวแปร
         $data[$key] = $products[$key];

         //แยกชื่อไฟล์ออกจาก path ของ ไฟล์ product textdatabase
         $arr = explode( '/', $product_file );
         $textfile = end( $arr );
         $filename = str_replace( '.txt', '', $textfile );

         //สร้าง permalink
         $key_slug = Helper::clean_string( $products[$key]['keyword'] );
         $cat_slug = Helper::clean_string( $products[$key]['category'] );
         $permalink = Helper::get_permalink( $filename, $key_slug, $cat_slug );

         //เพิ่ม permalink เข้าไปในตัวแปร products
         $data[$key]['permalink'] = $permalink;
      }

      return $data;
   }


   /*
    * Htmlsite Section
    * -------------------------------------------------------------------------
   */
   function getProductList( $data )
   {
      if ( $data['num'] === $this->item_number )
      {
         return $data;
      }

      //Random Textdatabase ของ Product ออกมา 1 ไฟล์
      $product_file = $this->getProductFile();

      //อ่านไฟล์จาก Textdatabase ( categories )
      $products  = file_get_contents( $product_file );
      $products  = unserialize( $products );

      //Random Key ของอะเรย์ข้อมูลสินค้าตามจำนวน item_number ที่ถูกส่งมาจาก home model
      $keys = array_keys( $products );
      $count = count( $keys );
      $num = $count > $this->item_number ? $this->item_number : $count;

      $data = array( 'product_file' => $product_file, 'products' => $products, 'num' => $num );
      return $this->getProductList( $data );
   }

   function htmlSiteProductList()
   {
      //initail variable
      $data = false;

      //Random Textdatabase ( categories ) ไฟล์
      //ที่มีข้อมูลสินค้าในไฟล์ไม่ต่ำกว่าจำนวน item number ที่ถูกกำหนดไว้
      $items = $this->getProductList( NULL );
      $product_file = $items['product_file'];
      $products = $items['products'];
      $num = $items['num'];

      //ดึงชื่อสินค้า ( ซึ่งเป็น key index ของ array )
      $keys = array_keys( $products );

      //Random ชื่อสินค้าออกมาตามจำนวน item number
      $rand_number = array_rand( $keys, $num );
      foreach( $rand_number as $number )
      {
         $rand_keys[] = $keys[$number];
      }

      //รวบรวมข้อมูลสินค้าที่ random มาได้แล้วส่งกลับไป
      foreach ( $rand_keys as $key )
      {
         //เก็บข้อมูลที่ random มาได้ลงในตัวแปร
         $data[$key] = $products[$key];

         //แยกชื่อไฟล์ออกจาก path ของ ไฟล์ product textdatabase
         $arr = explode( '/', $product_file );
         $textfile = end( $arr );
         $filename = str_replace( '.txt', '', $textfile );

         //สร้าง permalink
         $key_slug = Helper::clean_string( $products[$key]['keyword'] );
         $cat_slug = Helper::clean_string( $products[$key]['category'] );
         $permalink = Helper::get_permalink( $filename, $key_slug, $cat_slug );

         //เพิ่ม permalink เข้าไปในตัวแปร products
         $data[$key]['permalink'] = $permalink;
      }
      return $data;
   }
}
