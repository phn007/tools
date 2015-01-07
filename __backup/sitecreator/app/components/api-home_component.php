<?php

class ApiHomeComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }


   function getProductFromCategoryFile()
   {
      //อ่านไฟล์ขึ้นมา
      $files = glob( $this->category_path . '*.txt' );
      shuffle( $files );
      $file = $files[0];

      //อ่านข้อมูลสินค้าออกมา
      $products  = file_get_contents( $file );
      $products  = unserialize( $products );

      //Random
      shuffle( $products );

      //เลือกข้อมูลสินค้าออกมาตามจำนวนที่กำหนด
      array_splice( $products , $this->item_number );

      return $products;
   }


   function getProductDataFromApi( $catalogIds )
   {
      //สร้าง Pipe catalogId
      $catalogIds = implode( '|', $catalogIds );

      //ดึงสข้อมูลสินค้าจาก prosperent api ตามชื่อสินค้าและร้านค้าที่ random มา
      $api = new ProsperentApi();
      $api->filterCatalogId = $catalogIds;
      $api->limit = $this->item_number;
      $response = $api->getResponseDataNonEncode();

      //รวบรวมเฉพาะข้อมูลสินค้าที่ต้องใช้
      return $products = $this->apisiteProductListHome( $response );
   }

   //Cache ข้อมูลสินค้า
   function apisiteProductListHome( $response )
   {
      foreach ( $response as $product )
      {
         //สร้าง permalink
         $key_slug  = Helper::clean_string( $product['keyword'] );
         $cat_name  = Helper::getCategory( $product['merchant'], $product['category'] );
         $cat_slug  = Helper::clean_string( $cat_name );
         $permalink = Helper::get_permalink( '', $key_slug, $cat_slug );

         if ( empty( $product['brand'] ) ) $product['brand'] = EMPTY_BRAND_NAME;
         if ( empty( $product['category'] ) ) $product['category'] = EMPTY_CATEGORY_NAME;

         $data[] = array(
            'catalogId' => $product['catalogId'],
            'affiliate_url' => $product['affiliate_url'],
            'image_url' => $product['image_url'],
            'keyword' => $product['keyword'],
            'description' => $product['description'],
            'category' => $product['category'],
            'price' => $product['price'],
            'merchant' => $product['merchant'],
            'brand' => $product['brand'],
            'permalink' => $permalink,
         );
      }
      return $data;
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

   function getCategoryName( $cat_path )
   {
      $data = file_get_contents( $cat_path );
      $data = unserialize( $data );


      foreach ( $data as $val )
      {
         return  $val['category'];
      }
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


   function categorListHome()
   {
      $files  = glob( $this->category_path . '*.txt' );
      $file_num = count( $files );

      //validate show category number
      $show_cat_num = $file_num < $this->show_cat_num ? $file_num : $this->show_cat_num ;

      return array_slice( $files, 0, $show_cat_num );
   }



   function getCatnameByPathText( $path )
   {
      $files = file( $path );
      $arr = explode( '|', $files[0] );
      $catname = $arr[1];
      return $catname;
   }




   function getCategoryUrlText( $path )
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

}
