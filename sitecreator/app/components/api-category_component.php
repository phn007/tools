<?php
class ApiCategoryComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }

   /*
    * Category list section
    * -------------------------------------------------------------------------
   */
   function getCategoryList()
   {
      //init var
      $data = false;

      //อ่าน path ของ categories ไฟล์ทั้งหมด
      $files  = glob( $this->path . '*.txt' );

      if ( empty ( $files ) )
         die( "Categories: file not found!!!" );


      //วนลูปผ่าน path
      foreach ( $files as $file )
      {
         //อ่านข้อมูลในไฟล์
         $arr = unserialize( file_get_contents( $file ) );

         //อ่าน key ใน array ตัวแรก
         $key = key( $arr );

         //อ่านข้อมูลใน array
         $prod = $arr[$key];

         //ดึง category name ออกมา
         $cat_name = $prod['category'];

         //สร้าง url
         //$cat_slug = Helper::clean_string( $cat_name );
         //$url = $this->cat_path . $cat_slug . '/page-1' . FORMAT;
         $c = $this->getCategoryUrl( $file );

         $url = $c['url'];

         if ( $c['num'] !== NULL )
         {
            $cat_name = $cat_name . $c['num'];
         }

         //รวบรวมข้อมูลที่ต้องการทั้งหมด
         $data[] = array( 'cat_name' => $cat_name, 'url' => $url );
      }
      return $data;
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
    * Category item section
    * -------------------------------------------------------------------------
   */
   function getCategoryFiles()
   {
      //อ่านข้อมูลจาก categories/brands textdatabase
      $arr = unserialize( file_get_contents( $this->path ) );

      if ( empty( $arr ) )
         die( "Categories: Empty Data!!!");


      //นับจำนวนสินค้า
      $count = count( $arr );

      //คำนวณหาจำนวนหน้าเพจทั้งหมด
      $total_page = ceil( $count / $this->num_per_page );

      //แบ่งสินค้าออกเป็นกลุ่มๆ ตามจำนวน total page
      $chunks = array_chunk( $arr, $this->num_per_page, true );

      //เคลียร์แค่ตัวแปร arr
      $arr = null;
      unset( $arr );

      $page = $this->page;

      if ( $this->page < 1 ) $page = 1;
      if ( $this->page > $total_page ) $page = $total_page;




      $key = $page - 1;
      $cat_list = $chunks[ $key ];

      $chunks = null;
      unset( $chunks );

      return array( 'total_page' => $total_page, 'cat_list' => $cat_list );
   }


   function getProductData()
   {
      //init var
      $response = false;

      if ( file_exists( $this->path ) )
      {
         $products = unserialize( file_get_contents( $this->path ) );

         $cat_list_keys = array_keys( $this->cat_list );

         //ต้องตรวจสอบก่อนว่าสินค้าใน cat_list มีอยู่ใน products บ้างหรือเปล่า
         foreach ( $cat_list_keys as $key )
         {
            //ถ้าสินค้าของ key นี้ไม่มีอยู่ใน products textdatabase
            if ( ! array_key_exists( $key, $products ) )
            {
               //รวบรวม catalogId จาก cat_list
               $catalogIds[] = $this->cat_list[ $key ]['catalogId'];
            }
         }

         if ( isset( $catalogIds ) )
         {
            //นับจำนวน catatloId ทั้งหมด
            $count_catalog = count( $catalogIds );
            //สร้าง Pipe catalogId
            $catalogIds = implode( '|', $catalogIds );

            //ดึงข้อมูลจาก API
            $response = $this->getProductFromApi( $catalogIds, $count_catalog );
         }
      }
      else
      {
         //รวบรวม catalogId จาก cat_list
         foreach ( $this->cat_list as $list )
         {
            $catalogIds[] = $list['catalogId'];
         }

         //นับจำนวน catatloId ทั้งหมด
         $count_catalog = count( $catalogIds );

         //สร้าง Pipe catalogId
         $catalogIds = implode( '|', $catalogIds );

         //ดึงข้อมูลจาก API
         $response = $this->getProductFromApi( $catalogIds, $count_catalog );
      }
      return $response;
   }


   function getProductFromApi( $catalogIds, $count_catalog )
   {
      $api = new ProsperentApi();
      $api->filterCatalogId = $catalogIds;
      $api->limit = $count_catalog;
      $response = $api->getResponseDataNonEncode();
      return $response;
   }


   function dataCollection( $response )
   {
      //init var
      $data = false;

      foreach ( $response as $product )
      {
         if ( empty( $product['brand'] ) ) $product['brand'] = EMPTY_BRAND_NAME;
         if ( empty( $product['category'] ) ) $product['category'] = EMPTY_CATEGORY_NAME;

         $cat_name = Helper::getCategory( $product['merchant'], $product['category'] );

         //Key
         $key = Helper::clean_string( $product['keyword'] );

         $data[$key] = array(
            'catalogId' => $product['catalogId'],
            'affiliate_url' => $product['affiliate_url'],
            'image_url' => $product['image_url'],
            'keyword' => $product['keyword'],
            'description' => $product['description'],
            'category' => $cat_name,
            'price' => $product['price'],
            'merchant' => $product['merchant'],
            'brand' => $product['brand'],
         );
      }
      return $data;
   }


   function SaveProductData( $path, $products )
   {
      foreach ( $products as $key => $value )
      {
         $data[$key] = $value;
         Helper::put_serialize_text( $path, $data );

         $data = null;
         unset( $data );
      }
   }


   function getProductFull()
   {
      //init var
      $data = false;

      //อ่านข้อมูลสินค้าทั้งหมดที่มีอยู่ใน products textdatabase
      $products = unserialize( file_get_contents( $this->path ) );

      //รายชื่อสินค้าที่ต้องการแสดง
      $cat_list_keys = array_keys( $this->cat_list );

      //ดึงข้อมูลสินค้าที่ต้องการแสดงจาก products
      foreach ( $cat_list_keys as $key )
      {
         if ( array_key_exists( $key, $products ) )
         {
            //สร้าง permalink
            //$cat_name = Helper::getCategory( $products[$key]['merchant'], $products[$key]['category'] );
            //$cat_slug = Helper::clean_string( $cat_name );

            $cat_slug = Helper::clean_string( $products[$key]['category'] );
            $permalink = Helper::get_permalink( '', $key, $cat_slug );

            //cat title
            if ( 'category' == $this->cat_type )
            {
               $cat_title = $products[$key]['brand'];
               $cat_slug = Helper::clean_string( $products[$key]['brand'] );
               $title = 'Brand: ';
               $cat_url = HOME_URL . 'brand/' . $cat_slug . '/page-1' . FORMAT;;
            }
            elseif ( 'brand' == $this->cat_type )
            {
               $cat_title = $products[$key]['category'] ;
               $title = 'Category: ';
               $cat_url = HOME_URL . 'category/' . $cat_slug . '/page-1' . FORMAT;
            }

            $products[$key]['title'] = $title;
            $products[$key]['cat_title'] = $cat_title;
            $products[$key]['cat_url'] = $cat_url;
            //$products[$key]['category'] = $cat_name;

            //เพิ่ม  index permalink เข้าไปใน array
            $products[$key]['url'] = $permalink;
            $data[] = $products[ $key ];
         }
      }


      return $data;
   }

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
