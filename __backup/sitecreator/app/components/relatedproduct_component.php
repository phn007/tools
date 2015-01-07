<?php
class RelatedProductComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }



   function getProducts( $product_file, $word_limit, $img_size = null )
   {
      //Init Variable
      $data = false;


      //ตั้งค่า path ให้ดึง related product ออกมาตามชื่อไฟล์ใน categories โฟลเดอร์
      //$content_path = $this->content_path . Helper::clean_string( $this->category ) . '.txt';
      $content_path = $this->content_path . $product_file . '.txt';

      if ( file_exists( $content_path ) ) //ถ้า path นี้มีไฟล์อยู่ให้ดึงข้อมูลสินค้าขึ้นมา
      {
         //ดึงข้อมูลสินค้าที่อยู่ในไฟล์ขึ้นมา
         $files = unserialize( file_get_contents( $content_path ) );


         //ตรวจสอบว่าถูกเรียกจาก htmlsite หรือ textsite
         if ( SITE_TYPE == 'htmlsite' )
         {
            //Random สินค้าขึ้นมาเพื่อทำ related products
            $prod_group = $this->noneCache( $files );
         }
         else if ( SITE_TYPE == 'textsite' )
         {
            //Random สินค้าขึ้นมาเพื่อทำ related products
            $prod_group = $this->getDataKeys( $files );
         }

         //ถ้าไม่มีสินค้าให้ return ค่า false กลับไป
         if ( ! $prod_group ) return false;

         //ดึงข้อมูลสินค้าในไฟล์ โดยดึงตาม key ที่อยู่ใน cache
         foreach( $prod_group as $key )
         {
            $products[$key] = $files[$key];
         }


         //เพิ่มข้อมูลที่ต้องใช้เพิ่มเข้าไปในอะเรย์
         foreach ( $products as $product_key => $prod )
         {
            //เปลี่ยนขนาดของรูปตามขนาดที่ถูกกำหนดมา
            $prod['image_url'] = Helper::image_size( $prod['image_url'], $img_size );

            //ตัดคำใน description
            $prod['description'] = Helper::limit_words( $prod['description'], $word_limit );

            //ใช้ cat_slug เป็น product file
            //$product_file = Helper::clean_string( $prod['category'] );

            //Affiliate Link
            if ( 'textsite' == SITE_TYPE )
            {
               //ลิงค์ไปที่ shop controller เพื่อที่จะ redirect ไป merchant
               $prod['goto'] = HOME_URL . 'shop/' . $product_file .'/'. $product_key;
            }
            elseif ( 'htmlsite' == SITE_TYPE )
            {
               $referer = Helper::get_permalink( $product_file, $product_key, null );
               $prod['goto'] = Helper::prosperent_api( $prod['affiliate_url'], $referer );
            }

            //รวบรวมข้อมูลสินค้าส่งกลับไป
            $data[] = $prod;
         }
      }
      return $data;
   }

   function getDataKeys( $files )
   {
      $rand = false;

      //ลบสินค้าหลักออกจากอะเรย์ก่อน
      unset( $files[ $this->key ] );

      //จำนวนสินค้าที่จะต้อง random ออกมา
      $count = count( $files );
      $this->num = $this->num < $count ? $this->num : $count;

      //Random product จาก category file ตามจำนวนที่กำหนด
      if ( $count > 1 )
      {
         $rand = array_rand( $files, $this->num );
      }
      elseif ( $count == 1 )
      {
         $key = key( $files );
         $rand = array( $key );
      }
      return $rand;
   }



   public function getProducts__( $word_limit, $img_size = false )
   {
      //ตั้งค่า path ให้ดึง related product ออกมาตามชื่อไฟล์ใน categories โฟลเดอร์
      $content_path = $this->content_path . Helper::clean_string( $this->category ) . '.txt';

      if ( file_exists( $content_path ) ) //ถ้า path นี้มีไฟล์อยู่ให้ดึงข้อมูลสินค้าขึ้นมา
      {
         //ดึงข้อมูลสินค้าที่อยู่ในไฟล์ขึ้นมา
         $files = file( $content_path );

         //ตรวจสอบว่าถูกเรียกจาก htmlsite หรือ textsite
         if ( SITE_TYPE == 'htmlsite' )
         {
            //Random สินค้าขึ้นมาเพื่อทำ related products
            $prod_group = $this->noneCache( $files );
         }
         else if ( SITE_TYPE == 'textsite' )
         {
            //Random สินค้าขึ้นมาเพื่อทำ related products
            $prod_group = $this->useCache( $files );
         }


         if ( empty( $prod_group ) )
            return false;


         foreach( $prod_group as $key )
         {
            //ดึงข้อมูลสินค้าในไฟล์
            //โดยดึงตาม key ที่อยู่ใน cache
            $item = trim( $files[ $key ] );
            $arr  = explode( '|', $item );

            /*
               arr[0] //file name
               arr[1] //category name
               arr[2] //product name
            */

            $product_file = $arr[0]; //ชื่อไฟล์
            $product_key  = Helper::clean_string( $arr[2] ); //ชื่อสินค้า

            //ตั้งค่า path ให้ดึง product ออกมาตามชื่อไฟล์ใน products โฟล์เดอร์
            $path = $this->product_path . '/' . $product_file . '.txt';

            //ดึงรายการสินค้าที่อยู่ในไฟล์
            $items = Helper::get_serialize_string( $path );

            //ดึงข้อมูลที่อยู่ในอะเรย์ตาม $product_key ออกมา
            if ( array_key_exists( $product_key, $items ) )
            {
               $data = $items[ $product_key ];

               //เปลี่ยนขนาดของรูปตามขนาดที่ถูกกำหนดมา
               $data['image_url'] = Helper::image_size( $data['image_url'], $img_size );

               //ตัดคำใน description
               $data['description'] = Helper::limit_words( $data['description'], $word_limit );

               //ลิงค์ไปที่ shop controller เพื่อที่จะ redirect ไป merchant
               $data['goto'] = HOME_URL . 'shop/' . $product_file .'/'. $product_key;


               //ป้องกันไม่ให้มี product หลักอยู่ใน related product
               //if ( $this->key != $product_key )
               $related_products[] = $data;
            }
         }

         $files = null;
         unset( $files );

         return $related_products;
      }
      else //ถ้า path นี้ไม่มีไฟล์อยู่ให้ return ค่า NULL กลับไป
      {
         //die( '<span style="color:red">' . $this->content_path . ' does not exist</span>' );

         $related_products = NULL;
         return $related_products;
      }
   }



   private function useCache__( $files )
   {
      $c = new CacheSite();

      //ตั้งชื่อ cache ไฟล์ตามชื่อ product file
      $cache_file = $this->file;

      //ตั้งชื่อ cache key ตามชื่อ product name
      $cache_key  = $this->key;

      //ดึงข้อมูลของ cache ขึ้นมา
      $cache = $c->get_cache( $this->path, $cache_file, $cache_key );

      if ( $cache == null )
      {
         $count = count( $files );
         $this->num = $this->num < $count ? $this->num : $count;

         //Random product จาก category file ตามจำนวนที่กำหนด
         $rand   = array_rand( $files, $this->num );

         //ใช้ชื่อสินค้าเป็น array key
         $data = array( $cache_key => $rand );

         //Save  ข้อมูลลงไปที่ cache file
         $c->set_cache( $this->path, $cache_file, $data );

         $cache = $rand;
      }
      return $cache;
   }




   private function noneCache( $files )
   {
      $count = count( $files );
      $this->num = $this->num < $count ? $this->num : $count;

      //Random product จาก category file ตามจำนวนที่กำหนด
      $rand   = array_rand( $files, $this->num );

      return $rand;
   }
}
