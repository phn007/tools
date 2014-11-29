<?php
class AllProductsComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }

   function checkPageNumber( $num_total_page, $num_page )
   {
      if ( $num_page >= $num_total_page ) $num_page = $num_total_page;
      if ( $num_page <= 1 ) $num_page = 1;

      return $num_page;
   }


   function checkFileNumber( $files, $file_number )
   {
      $total = count( $files );

      if ( $file_number >= $total ) $file_number = $total;
      if ( $file_number <= 1 ) $file_number = 1;

      return $file_number;
   }


   function readFile( $filename, $num_page = null )
   {
      //อ่านข้อมูลสินค้าใน Current Filename
      $products = unserialize( file_get_contents( $filename ) );

      //แบ่งสินค้าออกเป็น page แต่ละ page มีจำนวนสินค้าเท่ากับ จำนวนที่ถูกกำหนดมา
      $chunks = array_chunk( $products, 10, true );

      $products = null;
      unset( $products );

      //หมายเลข จำนวนเพจทั้งหมด
      $num_total_page = count( $chunks );


      if ( isset( $num_page ) )
         $num_page = $this->checkPageNumber( $num_total_page, $num_page );


      //รายการสินค้าตาม file และ page ที่กำหนด
      if ( isset( $num_page ) )
         $product_list = $chunks[ $num_page - 1 ];
      else
         $product_list = null;

      return array(
         'num_total_page' => $num_total_page,
         'product_list' => $product_list
      );
   }


   function getPrevUrl( $files, $current_file, $current_page )
   {
      //init vars
      $first_state = null;
      $prev_state  = null;

      if ( $current_page <= 1 )
      {
         //กำหนดหมายเลข prev file
         if ( $current_file <= 1 )
         {
            $prev_file = 1;
            $first_state = 'class="disabled"';
            $prev_state = 'class="disabled"';
         }
         else
         {
            $prev_file = $current_file - 1;
         }

         //ดึงชื่อ path ของ prev file
         $filename = $files[ $prev_file -1 ];

         //อ่านข้อมูลของ prev file
         $data = $this->readFile( $filename );

         //หมายเลขเพจสุดท้ายของ prev file
         $last_page = $data['num_total_page'];

         //prev uri
         $prev = $prev_file . '-' . $last_page;
      }
      else
      {
         $prev = $current_file . '-' . ( $current_page - 1 );
      }
      return array(
         'uri' => $prev,
         'first_state' => $first_state,
         'prev_state' => $prev_state,
      );
   }


   function getNextUrl( $current_file,$total_file, $last_page, $current_page )
   {
      $next_state = null;
      $last_state = null;

      if ( $current_page >= $last_page )
      {
         $next = ( $current_file + 1 ) . '-1';

         if ( $current_file == $total_file )
         {
            $next_state = 'class="disabled"';
            $last_state = 'class="disabled"';
         }
      }
      else
      {
         $next = $current_file . '-' . ( $current_page + 1 );
      }
      return array(
         'uri' => $next,
         'next_state' => $next_state,
         'last_state' => $last_state,
      );
   }


   function getLastUrl( $files )
   {
      $total = count( $files );
      $filename = $files[ $total-1 ];

      $data = $this->readFile( $filename );
      $last_page = $data['num_total_page'];

      return array(
         'uri' => $total . '-' . $last_page,
      );
   }


   function getFirstUrl()
   {
      $first_file = 1;
      $first_page = 1;
      return array(
         'uri' => $first_file . '-' . $first_page,
      );
   }

   function getMenu()
   {
      $menu['prev_state']  = null;
      $menu['first_state'] = null;
      $menu['next_state']  = null;
      $menu['last_state']  = null;

      $num_total_file = count( $this->files );


      //Menu First
      $data = $this->getFirstUrl();
      $first_url = $this->url . $data['uri'] . FORMAT;
      $menu['first'] = '<a href="' . $first_url . '">First</a>';

      //Menu Last
      $data = $this->getLastUrl( $this->files );
      $last_url = $this->url . $data['uri'] . FORMAT;
      $menu['last'] = '<a href="' . $last_url . '">Last</a>';

      //Menu Next
      $data = $this->getNextUrl( $this->num_file, $num_total_file, $this->num_total_page, $this->num_page );
      $next_url = $this->url . $data['uri'] . FORMAT;
      $menu['next'] = '<a href="' . $next_url . '">Next</a>';
      $menu['next_state'] = $data['next_state'];
      $menu['last_state'] = $data['last_state'];

      //Menu Previous
      $data = $this->getPrevUrl( $this->files, $this->num_file, $this->num_page );
      $prev_url = $this->url . $data['uri'] . FORMAT;
      $menu['prev'] = '<a href="' . $prev_url . '">Previous</a>';
      $menu['first_state'] = $data['first_state'];
      $menu['prev_state'] = $data['prev_state'];

      return $menu;
   }



   function addPermalink( $products, $filename )
   {
      $arr = explode( '/', $filename );
      $filename = str_replace( '.txt', '', end( $arr ) );

      foreach ( $products as $key => $prod )
      {
         //สร้าง Permalink
         //$product_file = Helper::clean_string( $prod['category'] );
         $product_file = $filename;
         $permalink = Helper::get_permalink( $product_file, $key, null );
         $prod['product_link'] = $permalink;

         //สร้าง Brand Link
         $brand_slug = Helper::clean_string( $prod['brand'] );
         $brand_link = HOME_URL . 'brand/' . $brand_slug . '/page-1' . FORMAT;
         $prod['brand_link'] = $brand_link;

         //สร้าง Category Link
         $cat_slug = Helper::clean_string( $prod['category'] );
         $cat_link = HOME_URL . 'category/' . $cat_slug . '/page-1' . FORMAT;
         $prod['cat_link'] = $cat_link;

         //รวบรวมข้อมูลที่จะต้องส่งกลับไป
         $data[ $key ] = $prod;
      }
      return $data;
   }

}
