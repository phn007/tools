<?php
class ApiRelatedProductComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }


   function getProducts()
   {
      //init variable
      $data = false;

      $content_path = $this->content_path . $this->product_file . '.txt';

      if ( file_exists( $content_path ) ) //ถ้า path นี้มีไฟล์อยู่ให้ดึงข้อมูลสินค้าขึ้นมา
      {
         $files = unserialize( file_get_contents( $content_path ) );

         //ดึงเฉพาะชื่อ product key ออกมา
         $arr_keys = array_keys( $files );

         //ลบ product key ที่เป็นสินค้าหลักออกจากอะเรย์ก่อน
         if ( ( $key = array_search( $this->product_key, $arr_keys ) ) !== false )
         {
            unset( $arr_keys[$key] );
         }

         //ถ้าจำนวนสินค้าน้อยกว่าจำนวนที่กำหนด ให้ random ออกมาเท่ากันจำนวนสินค้าที่มี
         $count = count( $arr_keys );

         if ( $count > 0 )
         {

            $num = $count < $this->num ? $count : $this->num;
            $rand = array_rand( $arr_keys, $num );

            if ( ! is_array( $rand ) )
            {
               $rand = array( $rand );
            }

            foreach ( $rand as $key )
            {
               $rand_keys[] = $arr_keys[ $key ];
            }

            $data = $this->getProductData( $rand_keys, $files );

            return $data;
         }

      }
      return $data;
   }


   function getProductData( $rand_keys, $files )
   {
      //ดึงข้อมูลสินค้าที่ random ได้ออกมา
      foreach ( $rand_keys as $key )
      {
         if ( array_key_exists( $key, $files ) )
         {
            //เปลี่ยนขนาดของรูปตามขนาดที่ถูกกำหนดมา
            $files[$key]['image_url'] = Helper::image_size( $files[$key]['image_url'], $this->img_size );

            //ตัดคำใน description
            $files[$key]['description'] = Helper::limit_words( $files[$key]['description'], $this->word_limit );

            //Create Redirect to merchant url
            $cat_slug = Helper::clean_string( $files[$key]['category'] );
            $keyword = Helper::clean_string( $files[$key]['keyword'] );
            $referer = Helper::get_permalink( $this->product_file, $keyword, $cat_slug );
            $files[$key]['goto'] = Helper::prosperent_api( $files[$key]['affiliate_url'], $referer );

            $data[] = $files[$key];
         }
      }
      return $data;
   }



   //    function productCatalogId()
   //    {
   //
   //       $content_path = $this->content_path . Helper::clean_string( $this->product_file ) . '.txt';
   //
   //       if ( file_exists( $content_path ) ) //ถ้า path นี้มีไฟล์อยู่ให้ดึงข้อมูลสินค้าขึ้นมา
   //       {
   //
   //          //echo $content_path;
   //          $files = unserialize( file_get_contents( $content_path ) );
   //
   //          //ดึงเฉพาะชื่อ product key ออกมา
   //          $arr_keys = array_keys( $files );
   //
   //          //ลบ product key ที่เป็นสินค้าหลักออกจากอะเรย์ก่อน
   //          if ( ( $key = array_search( $this->product_key, $arr_keys ) ) !== false )
   //          {
   //             unset( $arr_keys[$key] );
   //          }
   //
   //          //นับจำนวนอะเรย์ทั้งหมด
   //          $count = count( $arr_keys );
   //
   //          //ถ้าจำนวนสินค้าน้อยกว่าจำนวนที่กำหนด ให้ random ออกมาเท่ากันจำนวนสินค้าที่มี
   //          $num = $count < $this->num ? $count : $this->num;
   //          $rand  = array_rand( $files, $num );
   //
   //          //ดึง catalogId ออกมาจากชื่อสินค้าที่ random ได้
   //          foreach ( $rand as $key )
   //          {
   //             if ( array_key_exists( $key, $files ) )
   //             {
   //                $catalogIds[] = $files[ $key ]['catalogId'];
   //             }
   //          }
   //
   //          return $catalogIds;
   //       }
   //       else
   //       {
   //          trigger_error( 'MY DEBUG: File not fount!!!' , $error_type = E_USER_ERROR );
   //       }
   //    }
   //
   //
   // function productApiData( $catalogIds )
   //    {
   //       //นับจำนวน catalogId ทั้งหมด
   //       $count = count( $catalogIds );
   //
   //       //สร้าง pipe ให้กับ catalogIds
   //       $catalogIds = implode( '|', $catalogIds );
   //
   //       //ดึงข้อมูลสินค้าจาก prosperent api
   //       $api = new ProsperentApi();
   //       $api->filterCatalogId = $catalogIds;
   //       $api->limit = $count;
   //       $response = $api->getResponseDataNonEncode();
   //       return $response;
   //    }
   //
   // public function getProducts_orig( $word_limit, $img_size = false )
   // {
   //
   //    //ตั้งค่า path ให้ดึง related product ออกมาตามชื่อไฟล์ใน categories โฟลเดอร์
   //    $content_path = $this->content_path . Helper::clean_string( $this->category ) . '.txt';
   //
   //    if ( file_exists( $content_path ) ) //ถ้า path นี้มีไฟล์อยู่ให้ดึงข้อมูลสินค้าขึ้นมา
   //    {
   //
   //       //ดึงข้อมูลสินค้าที่อยู่ในไฟล์ขึ้นมา
   //       $files = file( $content_path );
   //
   //       //Random สินค้าขึ้นมาเพื่อทำ related products
   //       $prod_group = $this->useCache( $files );
   //
   //       if ( empty( $prod_group ) )
   //          return false;
   //
   //       foreach( $prod_group as $key )
   //       {
   //          //ดึงข้อมูลสินค้าในไฟล์
   //          //โดยดึงตาม key ที่อยู่ใน cache
   //          $item = trim( $files[ $key ] );
   //          $arr  = explode( '|', $item );
   //
   //          /*
   //             arr[0] //file name
   //             arr[1] //category name
   //             arr[2] //product name
   //          */
   //
   //          $product_file = $arr[0]; //ชื่อไฟล์
   //          $product_key  = Helper::clean_string( $arr[2] ); //ชื่อสินค้า
   //
   //          //ตั้งค่า path ให้ดึง product ออกมาตามชื่อไฟล์ใน products โฟล์เดอร์
   //          $path = $this->product_path . '/' . $product_file . '.txt';
   //
   //          //ดึงรายการสินค้าที่อยู่ในไฟล์
   //          $items = Helper::get_serialize_string( $path );
   //
   //          //ดึงข้อมูลที่อยู่ในอะเรย์ตาม $product_key ออกมา
   //          if ( array_key_exists( $product_key, $items ) )
   //          {
   //             $data = $items[ $product_key ];
   //
   //             //เปลี่ยนขนาดของรูปตามขนาดที่ถูกกำหนดมา
   //             $data['image_url'] = Helper::image_size( $data['image_url'], $img_size );
   //
   //             //ตัดคำใน description
   //             $data['description'] = Helper::limit_words( $data['description'], $word_limit );
   //
   //             //ลิงค์ไปที่ shop controller เพื่อที่จะ redirect ไป merchant
   //             $data['goto'] = HOME_URL . 'shop/' . $product_file .'/'. $product_key;
   //
   //
   //             //ป้องกันไม่ให้มี product หลักอยู่ใน related product
   //             //if ( $this->key != $product_key )
   //             $related_products[] = $data;
   //          }
   //       }
   //
   //       $files = null;
   //       unset( $files );
   //
   //       return $related_products;
   //    }
   //    else //ถ้า path นี้ไม่มีไฟล์อยู่ให้ return ค่า NULL กลับไป
   //    {
   //       //die( '<span style="color:red">' . $this->content_path . ' does not exist</span>' );
   //
   //       $related_products = NULL;
   //       return $related_products;
   //    }
   // }
   //
   //
   //
   //
   // private function useCache( $files )
   // {
   //    $c = new CacheSite();
   //
   //    //ตั้งชื่อ cache ไฟล์ตามชื่อ product file
   //    $cache_file = $this->file;
   //
   //    //ตั้งชื่อ cache key ตามชื่อ product name
   //    $cache_key  = $this->key;
   //
   //    //ดึงข้อมูลของ cache ขึ้นมา
   //    $cache = $c->get_cache( $this->path, $cache_file, $cache_key );
   //
   //    if ( $cache == null )
   //    {
   //       $count = count( $files );
   //       $this->num = $this->num < $count ? $this->num : $count;
   //
   //       //Random product จาก category file ตามจำนวนที่กำหนด
   //       $rand   = array_rand( $files, $this->num );
   //
   //       //ใช้ชื่อสินค้าเป็น array key
   //       $data = array( $cache_key => $rand );
   //
   //       //Save  ข้อมูลลงไปที่ cache file
   //       $c->set_cache( $this->path, $cache_file, $data );
   //
   //       $cache = $rand;
   //    }
   //    return $cache;
   // }
   //
   //
   //
   //
   // private function noneCache( $files )
   // {
   //    $count = count( $files );
   //    $this->num = $this->num < $count ? $this->num : $count;
   //
   //    //Random product จาก category file ตามจำนวนที่กำหนด
   //    $rand   = array_rand( $files, $this->num );
   //
   //    return $rand;
   // }
}
