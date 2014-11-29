<?php

class HtmlSiteComponent
{

   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }



   function createIndexPage()
   {
      webtools\Helper::make_dir( $this->dest );

      echo $this->site_dir;
      echo " Created index: ";

      //home#index
      $controller = 'home';
      $action = 'index';

      $command = 'php ' . CALL_CREATOR . '/index.php ' . $controller . ' ' . $action;
      echo shell_exec( $command );
   }


   function createDefaultPage()
   {
      webtools\Helper::make_dir( $this->dest );

      $pages = array(
         'about',
         'contact',
         'privacy_policy'
      );

      //page#about
      //page#contact
      //page#privacy_policy
      $controller = 'page';

      foreach ( $pages as $page_name )
      {
         $action = $page_name;

         echo $this->site_dir;
         echo " Created " . $page_name . ': ';

         $command = 'php ' . CALL_CREATOR . '/index.php ' . $controller . ' ' . $action;
         echo shell_exec( $command );
      }
   }


   function createCategoryListPage()
   {
      webtools\Helper::make_dir( $this->dest );

      //category#category_list
      $controller = 'category';
      $action = 'categories';

      echo $this->site_dir;
      echo " Created category list: ";

      $command = 'php ' . CALL_CREATOR . '/index.php ' . $controller . ' ' . $action;
      echo shell_exec( $command );
   }


   function createBrandListPage()
   {
      webtools\Helper::make_dir( $this->dest );

      //category#category_list
      $controller = 'category';
      $action = 'brands';

      echo $this->site_dir;
      echo " Created brand list: ";

      $command = 'php ' . CALL_CREATOR . '/index.php ' . $controller . ' ' . $action;
      echo shell_exec( $command );
   }



   private function category_names( $category_path )
   {
      //อ่านรายชื่อไฟล์ทั้งหมดที่อยู่ใน directory
      $files = glob( $category_path . '*.txt' );

      //แยกเอาแต่รายชื่่อของ catgory เก็บไว้ในตัวแปร
      foreach ( $files as $file )
      {
         $arr = explode( '/', $file );
         $name = str_replace( '.txt', '', end( $arr ) );
         $cat_names[] = $name;
      }
      return $cat_names;
   }


   private function category_pages( $cat_name, $category_path )
   {
      $path = $category_path . $cat_name . '.txt';

      //อ่านข้อมูลในไฟล์ขึ้นมา
      //$data = unserialize( file_get_contents( $path ) );

      $data = file( $path );
      $count = count( $data );

      $data = null;
      unset( $data );

      //คำนวณจำนวน page ทั้งหมด
      $total_page = ceil( $count / $this->item_per_page );

      //loop หมายเลข page เก็บเลงตัวแปร array
      for ( $i = 1; $i <= $total_page; $i++ )
      {
         $pages[] = $i;
      }
      return $pages;
   }

   private function category_pages__( $cat_name, $category_path )
   {
      $path = $category_path . $cat_name . '.txt';

      //อ่านข้อมูลในไฟล์ขึ้นมา
      $data = unserialize( file_get_contents( $path ) );
      $count = count( $data );

      $data = null;
      unset( $data );

      //คำนวณจำนวน page ทั้งหมด
      $total_page = ceil( $count / $this->item_per_page );

      //loop หมายเลข page เก็บเลงตัวแปร array
      for ( $i = 1; $i <= $total_page; $i++ )
      {
         $pages[] = $i;
      }
      return $pages;
   }


   function createCaterotyItemsPage()
   {
      //category#category_items, params( number )
      $controller = 'category';
      $action = 'category';

      //อ่านรายชื่อ category ทั้งหมด ( cat_name )
      $category_path = $this->source . 'categories/';

      $cat_names = $this->category_names( $category_path );

      //Loop ผ่าน cat_names
      $i = 1;
      foreach ( $cat_names as $cat_name )
      {
         $dest = $this->dest . '/category/' . $cat_name . '/' ;

         //สร้างโฟลเดอร์ตามชื่อ cat_name
         webtools\Helper::make_dir( $dest );

         //คำนวณหาว่าแต่ละ cat_name มีทั้งหมดกี่หน้า เก็บไว้ในตัวแปร array
         $pages = $this->category_pages( $cat_name, $category_path );

         //Loop ผ่าน $pages เพื่อกำหนด params
         foreach ( $pages as $number )
         {
            //กำหนดค่าให้ตัวแปร params
            $param1 = $cat_name;
            $param2 = $number;

            $command = 'php ' . CALL_CREATOR . '/index.php ';
            $command .= $controller . ' ' . $action . ' ';
            $command .= $param1 . ' ' . $param2;

            echo $this->site_dir;
            echo " Created category items: ";
            echo $i . ' ';

            $i++;
            echo shell_exec( $command );
         }
      }
   }



   function createBrandItemsPage()
   {
      //category#brand_items, params( name, number )
      $controller = 'category';
      $action = 'brand';

      //รายชื่อ category ทั้งหมด ( cat_name )
      $brand_path = $this->source  . 'brands/';
      $brand_names = $this->category_names( $brand_path );

      //Loop ผ่าน  brand_names
      $i = 1;
      foreach ( $brand_names as $brand_name )
      {
         //แต่ละ brand_name มีทั้งหมดกี่หน้า เก็บไว้ในตัวแปร array
         $pages = $this->category_pages( $brand_name, $brand_path );

         //สร้างโฟลเดอร์ตามชื่อ cat_name
         $dest = $this->dest . '/brand/' . $brand_name . '/' ;
         webtools\Helper::make_dir( $dest );

         //Loop ผ่าน $pages เพื่อกำหนด params และเรียกฟังก์ชั่น generate page ขึ้นมาทำงาน
         foreach ( $pages as $number )
         {
            $param1 = $brand_name;
            $param2 = $number;

            $command = 'php ' . CALL_CREATOR . '/index.php ';
            $command .= $controller . ' ' . $action . ' ';
            $command .= $param1 . ' ' . $param2;

            echo $this->site_dir;
            echo " Created brand items: ";
            echo $i . ' ';
            $i++;

            echo shell_exec( $command );
         }

      }//foreacch brand names
   }


   function createProductPage()
   {
      $product_path = $this->source . 'categories/';
      $controller = 'product';
      $action = 'index';

      $files  = glob( $product_path . '*.txt' );

      if ( empty( $files ) )
      {
         $msg = 'My Debug: Empty Text Database Files!!!';
         trigger_error( $msg , $error_type = E_USER_ERROR );
      }


      $i = 1;
      foreach ( $files as $file )
      {
         //Parse Product File Name
         $str = file_get_contents( $file );
         $items = unserialize( $str );

         //Main Variables for use
         $arr = explode( '/', $file );
         $product_file = str_replace( '.txt', '', end( $arr ) );
         $keys = array_keys( $items );

         //clear items variable
         $str = null;
         unset( $str );

         $items = null;
         unset( $items );

         foreach ( $keys as $product_key )
         {
            $param1 = $product_file;
            $param2 = $product_key;

            $command = 'php ' . CALL_CREATOR . '/index.php ';
            $command .= $controller . ' ' . $action . ' ';
            $command .= $param1 . ' ' . $param2;

            echo $this->site_dir;
            echo " Created: product ";
            echo $i . ' ';

            echo shell_exec( $command );

            $i++;
         }
      }
   }
}
