<?php
class CategoryComponent extends AppComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }



   function getBrandList()
   {
      //init var
      $data = false;

      $files  = glob( $this->path . '*.txt' );

      foreach ( $files as $file )
      {
         $items = file( $file );
         $items = array_map( 'trim', $items );

         $arr = explode( '|', $items[0] );
         $brand_name = isset( $arr[1] ) ? $arr[1] : null;

         $url = HOME_URL . 'brand/' . Helper::clean_string( $brand_name ) . '/page-1' . FORMAT;
         $data[] = array(
            'cat_name' => $brand_name,
            'url' => $url,
         );
      }

      return $data;
   }



   /**
    * Category URLs List Section
    * -------------------------------------------------------------------------
   */

   public function getList( $cat_type )
   {
      //init var
      $data = false;

      $files  = glob( $this->path . '*.txt' );

      foreach ( $files as $file )
      {
         $product = unserialize( file_get_contents( $file ) );

         $key = key( $product );

         $c = $this->getCategoryUrl( $file );
         $url = $c['url'];

         //cat_type = category/brand
         $cat_name = $product[$key][ $cat_type ];

         if ( $c['num'] !== NULL )
         {
            $cat_name = $cat_name . $c['num'];
         }

         $data[] = array( 'cat_name' => $cat_name, 'url' => $url );
      }
      return $data;
   }


   /*
    * NEW DEVELOP
    * -------------------------------------------------------------------
   */
   /**
   * Category Items Section
   * -------------------------------------------------------------------------
   */

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


   function getFiles()
   {
      if ( file_exists( $this->path ) )
      {
         $files = unserialize( file_get_contents( $this->path ) );
         return $files;
      }
      else
      {
         return false;
      }
   }


   function getBrandItemForPage( $brands )
   {
      //นับจำนวนสินค้า
      $count = count( $brands );

      //คำนวณหาจำนวนหน้าเพจทั้งหมด
      $total_page = ceil( $count / $this->per_page );

      //แบ่งสินค้าออกเป็นกลุ่มๆ ตามจำนวน total page
      $chunks = array_chunk( $brands, $this->per_page, true );

      //เคลียร์ตัวแปร products
      $brands = null;
      unset( $brands );

      //หมายเลข page จาก url
      $page = $this->page;

      //ไม่ให้หมายเลข page น้อยกว่า 1 หรือมากกว่า total page
      if ( $this->page < 1 ) $page = 1;
      if ( $this->page > $total_page ) $page = $total_page;

      $key = $page - 1;
      $brand_list = $chunks[ $key ];

      $chunks = null;
      unset( $chunks );

      return array( 'total_page' => $total_page, 'brand_list' => $brand_list );
   }

   function getCategoryFiles( $products )
   {
      //นับจำนวนสินค้า
      $count = count( $products );

      //คำนวณหาจำนวนหน้าเพจทั้งหมด
      $total_page = ceil( $count / $this->per_page );

      //แบ่งสินค้าออกเป็นกลุ่มๆ ตามจำนวน total page
      $chunks = array_chunk( $products, $this->per_page, true );

      //เคลียร์ตัวแปร products
      $products = null;
      unset( $products );

      //หมายเลข page จาก url
      $page = $this->page;

      //ไม่ให้หมายเลข page น้อยกว่า 1 หรือมากกว่า total page
      if ( $this->page < 1 ) $page = 1;
      if ( $this->page > $total_page ) $page = $total_page;

      $key = $page - 1;
      $cat_list = $chunks[ $key ];

      $chunks = null;
      unset( $chunks );

      return array( 'total_page' => $total_page, 'cat_list' => $cat_list );
   }


   function getBrandItems()
   {
      $data = false;

      foreach( $this->brands as $key => $brand )
      {
         $cat_file = $this->cat_path . $brand['filename'] . '.txt';

         if ( file_exists( $cat_file ) )
         {
            $files = unserialize( file_get_contents( $cat_file ) );

            if ( array_key_exists( $key, $files ) )
            {
               unset( $files[$key]['affiliate_url'] );
               unset( $files[$key]['description'] );

               $product_file = $brand['filename'];
               $product_key = Helper::clean_string( $key );
               $permalink = Helper::get_permalink( $product_file, $product_key, null );
               $title = 'Category: ';
               $cat_title = $brand['cat_name'];
               $cat_url = HOME_URL . 'category/' . $brand['filename'] . '/page-1' . FORMAT;

               $data[$key] = $files[$key];
               $data[$key]['url'] = $permalink;
               $data[$key]['title'] = $title;
               $data[$key]['cat_title'] = $cat_title;
               $data[$key]['cat_url'] = $cat_url;
            }
         }
      }
      return $data;

   }


   function addPermalink( $products, $cat_name, $cat_type )
   {
      foreach ( $products as $key => $prod )
      {
         //สร้าง Permalink
         //$product_file = Helper::clean_string( $prod['category'] );
         $product_file = $cat_name;

         $permalink = Helper::get_permalink( $product_file, $key, null );
         $prod['url'] = $permalink;

         //สร้าง cat url
         if ( 'category' == $cat_type )
         {
            $cat_url = HOME_URL . 'brand/' . Helper::clean_string( $prod['brand'] ) . '/page-1' . FORMAT;
            $title = 'Brand: ';
            $cat_title = $prod['brand'];
         }
         elseif ( 'brand' == $cat_type )
         {
            $cat_url = HOME_URL . 'category/' . Helper::clean_string( $prod['category'] ) .'/page-1' . FORMAT;
            $title = 'Category: ';
            $cat_title = $prod['category'];
         }
         $prod['title'] = $title;
         $prod['cat_title'] = $cat_title;
         $prod['cat_url'] = $cat_url;

         //รวบรวมข้อมูลที่จะต้องส่งกลับไป
         $data[ $key ] = $prod;

      }
      return $data;
   }



   //New Develop
   function menu()
   {
      $first_page = 1;
      $last_page = $this->total_page;

      $next_page = $this->current_page + 1;
      $prev_page = $this->current_page - 1;

      //Category URL
      $url = HOME_URL . $this->cat_type . '/' . $this->cat_name . '/';

      //URL FORMAT
      $first_page_url = $url . 'page-' . $first_page . FORMAT ;
      $last_page_url  = $url . 'page-' . $last_page . FORMAT ;
      $next_page_url  = $url . 'page-' . $next_page . FORMAT ;
      $prev_page_url  = $url . 'page-' . $prev_page . FORMAT ;


      //ป้องกันไม่ให้มีหมายเลขเพจที่เกินกว่าความเป็นจริง
      if ( $prev_page < $first_page )
         $prev_page_url  = $url . 'page-' . $first_page . FORMAT ;

      if ( $next_page > $last_page )
         $next_page_url  = $url . 'page-' . $last_page . FORMAT ;


      $menu['first'] = '<a href="' . $first_page_url . '">First</a>';
      $menu['last']  = '<a href="' . $last_page_url . '">Last</a>';
      $menu['next']  = '<a href="' . $next_page_url . '">Next</a>';
      $menu['prev']  = '<a href="' . $prev_page_url . '">Prev</a>';

      $menu['prev_state']  = ( $prev_page < $first_page ) ? 'class="disabled"' : NULL;
      $menu['first_state'] = ( $prev_page < $first_page ) ? 'class="disabled"' : NULL;
      $menu['next_state']  = ( $next_page > $last_page )  ? 'class="disabled"' : NULL;
      $menu['last_state']  = ( $next_page > $last_page )  ? 'class="disabled"' : NULL;

      return $menu;
   }
}
