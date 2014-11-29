<?php
class ApiHomeModel extends AppComponent
{
   function getProducts()
   {
      //cach ข้อมูลสินค้าที่ได้มา
      $cache_path = 'cache/home-product-list';
      $cache_name = 'home-product-list';
      $cache_time = 300;

      $c = new CacheSite();
      $cache = $c->get( $cache_path, $cache_name, $cache_time );

      //Random ชื่อสินค้าจาก categoies textdatabase file
      $home_cpn = $this->component( 'api-home' );

      if ( $cache == NULL )
      {
         $home_cpn->category_path = CONTENT_PATH . 'categories/';
         $home_cpn->item_number = 15;
         $products = $home_cpn->getProductFromCategoryFile();

         //เก็บเฉพาะค่าของ catalogid ไว้ใน array
         foreach ( $products as $prod ) $catalogIds[] = $prod['catalogId'];

         $products = $home_cpn->getProductDataFromApi( $catalogIds );

         //Cache Products
         $c->set( $cache_path, $cache_name, $products );
         $cache = $products;
      }

      //แยกข้อมูลออกมาเพื่อทำ slide carousel และ item list
      $data = $home_cpn->separate_product( $cache );

      return array(
         'slide_products' => $data['slide_products'],
         'product_list' => $data['product_list']
      );
   }



   function categoryList()
   {
      $cpn = $this->component( 'api-home' );
      $cpn->category_path = CONTENT_PATH . 'categories/';
      $cpn->show_cat_num = 100;

      $files = $cpn->categoryListHome();

      foreach ( $files as $cat_path )
      {
         $cat_name = $cpn->getCategoryName( $cat_path );
         $c = $cpn->getCategoryUrl( $cat_path );

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
      $cpn = $this->component( 'api-home' );
      $cpn->category_path = CONTENT_PATH . 'brands/';
      $cpn->show_cat_num = 100;

      $files = $cpn->categoryListHome();
      foreach ( $files as $cat_path )
      {
         $cat_name = $cpn->getCategoryName( $cat_path );
         $cat_url = $cpn->getCategoryUrl( $cat_path );

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
