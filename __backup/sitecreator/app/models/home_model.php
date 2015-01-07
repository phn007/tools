<?php

class HomeModel extends AppComponent
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
      $cpn = $this->component( 'home' );



      //TextSite ใช้ cache Htmlsite ไม่ต้องการใช้ cache
      if ( 'textsite' == SITE_TYPE )
      {
         //ส่งค่าตัวแปรไปให้ home component
         $cpn->product_path = CONTENT_PATH . 'categories/';
         $cpn->item_number = 15;

         //cache variables
         $cpn->cache_name = 'home-product-file';
         $cpn->cache_path = 'cache/home-products';
         $cpn->cache_time = 300;

         //Random Textdatabase ของ Product ออกมา 1 ไฟล์
         //ดึงข้อมูลสินค้าออกมา  ( cache ไฟล์เป็น array keys)
         $products = $cpn->textSiteProductList();
      }
      //Html site
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         //ส่งค่าตัวแปรไปให้ home component
         $cpn->product_path = TEXTDB_PATH . 'categories/';
         $cpn->item_number = 15;

         $products = $cpn->htmlSiteProductList();
      }

      //แบ่ง products ออกมาทำเป็น slide ที่เหลือแสดงเป็น list items
      $data = $cpn->separate_product( $products );

      return array(
         'slide_products' => $data['slide_products'],
         'product_list' => $data['product_list']
      );
   }


   function categoryList()
   {
      $cpn = $this->component( 'home' );

      if ( 'textsite' == SITE_TYPE )
      {
         $cpn->category_path = CONTENT_PATH . 'categories/';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $cpn->category_path = TEXTDB_PATH .'categories/';
      }

      $cpn->show_cat_num = 100;
      $files = $cpn->categoryListHome();

      foreach ( $files as $path )
      {
         $cat_name = $cpn->getCatnameByPath( $path );
         $c = $cpn->getCategoryUrl( $path );

         $cat_url = $c['url'];

         if ( $c['num'] !== NULL )
         {
            $cat_name = $cat_name . $c['num'];
         }

         $data[] = array(
            'cat_name' => $cat_name,
            'cat_url' => $cat_url,
         );
      }
      return $data;
   }


   function brandList()
   {
      $cpn = $this->component( 'home' );

      if ( 'textsite' == SITE_TYPE )
      {
         $cpn->brand_path = CONTENT_PATH . 'brands/';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $cpn->brand_path = TEXTDB_PATH .'brands/';
      }

      $cpn->show_cat_num = 100;
      $files = $cpn->brandListHome();

      foreach ( $files as $path )
      {
         $brand_name = $cpn->getBrandnameByPath( $path );
         $brand_url = $cpn->getBrandUrl( $path );

         $data[] = array(
            'cat_name' => $brand_name,
            'cat_url' => $brand_url,
         );

      }
      return $data;
   }

   function brandList__()
   {
      $cpn = $this->component( 'home' );

      if ( 'textsite' == SITE_TYPE )
      {
         $cpn->category_path = CONTENT_PATH . 'brands/';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $cpn->category_path = TEXTDB_PATH .'brands/';
      }

      $cpn->show_cat_num = 100;
      $files = $cpn->categoryListHome();

      foreach ( $files as $path )
      {
         $cat_name = $cpn->getBrandnameByPath( $path );
         $c = $cpn->getCategoryUrl( $path );

         $cat_url = $c['url'];

         if ( $c['num'] !== NULL )
         {
            $cat_name = $cat_name . $c['num'];
         }

         $data[] = array(
            'cat_name' => $cat_name,
            'cat_url' => $cat_url,
         );
      }
      return $data;
   }

   function getMeta()
   {
      $cpn = $this->component( 'head' );
      $cpn->author = AUTHOR;
      $cpn->title = SITE_NAME;
      $cpn->description = SITE_DESC;
      $cpn->link = HOME_LINK;
      $cpn->property_locale = 'en_US';
      $cpn->property_type = 'website';
      $cpn->property_title = SITE_NAME;
      $cpn->property_description = SITE_DESC;
      $cpn->property_url = HOME_LINK;
      $cpn->property_site_name = SITE_NAME;
      $meta = $cpn->getHead();

      $data = null;
      foreach ( $meta as $met )
      {
         $data .= $met . PHP_EOL;
      }
      return $data;
   }
}
