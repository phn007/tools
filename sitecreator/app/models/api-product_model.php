<?php
class ApiProductModel extends AppComponent
{
   function getProducts( $product_file, $product_key )
   {
      //ตรวจสอบว่ามี cache อยู่หรือเปล่า
      $c = new CacheSite();

      $cache_path = 'contents/products';
      $cache = $c->get_cache( $cache_path, $product_file, $product_key );

      if ( NULL == $cache )
      {
         //ดึงข้อมูลสินค้าใน categories textdatabases

         //สร้าง object ของ api product และกำหนดค่าตัวแปร
         $cpn = $this->component( 'api-product' );
         $cpn->path = 'contents/categories/' . $product_file . '.txt';
         $cpn->product_key = $product_key;

         //อ่านไฟล์ category
         $file = $cpn->getProductFile();

         //อ่านข้อมูลสินค้าในไฟล์ category
         $product = $cpn->getProducts( $file );

         //เอา catalogId ไปดึงข้อมูลสินค้าจาก prosperent api
         $api = $this->component( 'api' );
         $api->filterCatalogId = $product['catalogId'];
         $response = $api->getResponseDataNonEncode();

         //รวบรวมข้อมูลที่ต้องใช้
         $prod = $response[0];
         if ( empty( $prod['brand'] ) ) $prod['brand'] = EMPTY_BRAND_NAME;
         if ( empty( $prod['category'] ) ) $prod['category'] = EMPTY_CATEGORY_NAME;

         $cat_name = Helper::getCategory( $prod['merchant'], $prod['category'] );

         $data[$product_key] = array(
            'affiliate_url' => $prod['affiliate_url'],
            'image_url' => $prod['image_url'],
            'keyword' => $prod['keyword'],
            'description' => $prod['description'],
            'category' => $cat_name,
            'price' => $prod['price'],
            'merchant' => $prod['merchant'],
            'brand' => $prod['brand'],
         );

         //Cache เก็บไว้
         $c->set_cache( $cache_path, $product_file, $data );
         $cache = $data[$product_key];
      }

      //406592-16240-0
      //406592-16240-0

      //Create Redirect to merchant url
      $cat_slug = Helper::clean_string( $cache['category'] );
      $referer = Helper::get_permalink( $product_file, $product_key, $cat_slug );
      $cache['goto'] = Helper::prosperent_api( $cache['affiliate_url'], $referer );
      return $cache;
   }




   function getLinks( $product_file, $product_key, $product )
   {
      $cat_slug = Helper::clean_string( $product['category'] );

      //Permalink
      $permalink = Helper::get_permalink( $product_file, $product_key, $cat_slug );

      //Brand page link
      if ( empty( $product['brand'] ) ) $product['brand'] = $product['merchant'];
      $brand_link = HOME_URL . 'brand/' . Helper::clean_string( $product['brand'] ) . '/page-1.html';

      //Category page link
      $category_link = HOME_URL . 'category/' . Helper::clean_string( $product['category'] ) . '/page-1.html';

      return array(
         'permalink' => $permalink,
         'brand_link' => $brand_link,
         'category_link' => $category_link,
      );
   }

   function getNavmenu( $product_file, $product_key )
   {
      $prod = $this->component( 'api-product' );
      $prod->product_file = $product_file;
      $prod->product_key = $product_key;
      $prod->path = CONTENT_PATH . 'categories/' . $product_file . '.txt';
      $files = $prod->getProductFile( );

      $nav = $this->component( 'productnavmenu' );
      $nav->key   = $product_key;
      $nav->file  = $product_file;

      //Lable
      $nav->lbl_first = 'First';
      $nav->lbl_prev  = 'Previous';
      $nav->lbl_next  = 'Next';
      $nav->lbl_last  = 'Last';
      return $nav->menu( $files );
   }




   function getSpinContent( $keyword, $product_file, $product_key )
   {
      if ( 'textsite' == SITE_TYPE )
      {
         $contents = $this->cacheSpinContent( $product_file, $product_key );
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $contents = $this->spinContent();
      }
      elseif ( 'apisite' == SITE_TYPE )
      {
         $contents = $this->cacheSpinContent( $product_file, $product_key );
      }

      foreach ( $contents as $key => $text )
      {
         $data[$key] = str_replace( '#keyword#', $keyword, $text );
      }
      return $data;
   }



   function cacheSpinContent( $cache_file, $cache_key )
   {
      $c = new CacheSite();

      $path = 'cache/spin-content';
      $cache = $c->get_cache( $path, $cache_file, $cache_key );

      if ( $cache == null )
      {
         $text = $this->spinContent();
         $data = array( $cache_key => $text );

         //Save data to cache
         $c->set_cache( $path, $cache_file, $data );
         $cache = $text;
      }
      return $cache;
   }

   function spinContent()
   {
      $cpn = $this->component('textspinner' );
      $cpn->wordlib_path = BASE_PATH . 'files/textspinner/market-01/WordLibrary.csv';
      $cpn->text_path = BASE_PATH . 'files/textspinner/market-01/*.txt';

      //Spin
      $text = $cpn->runSpinContent();

      //ใส่ตัวหนา, เอียง, ขีดเส้นใต้
      $text['ad1'] = $cpn-> addHtmlTag( $text['ad1'] );
      $text['ad2'] = $cpn-> addHtmlTag( $text['ad2'] );
      $text['ad_desc'] = $cpn-> addHtmlTag( $text['ad_desc'] );

      //สร้าง spam keyword
      $path = BASE_PATH . 'files/textspinner/market-01/spam.txt';
      $spam = $cpn->spamKeyword( $path );
      $text['spam'] = $spam;

      return $text;
   }


   function textForSearch( $title )
   {
      $text = explode( "-", $title );
      # remove blank value in array
      $text = array_filter( $text );
      $text = implode( " ", array_splice( $text, 0, 3 ) );
      return $text;
   }


   function createTags( $keyword )
   {
      $tags = explode( '-', $keyword);
      $tags = array_filter( $tags );

      $i = 1;
      foreach ( $tags as $tag ) {
         $num = strlen ( $tag );

         if ( $num > 1 ) {
            $data[ 'tag' . $i ] = $tag;
            $i++;
         }
      }
      return $data;
   }



   function getRelatedProduct( $product_file, $product_key )
   {
      //อ่านข้อมูลสินค้าจาก contents/products
      $cpn = $this->component( 'api-relatedproduct' );
      $cpn->product_file = $product_file;
      $cpn->product_key = $product_key;
      $cpn->content_path = CONTENT_PATH . 'products/';

      //related products number
      $cpn->num = 12;

      //word_limit ( limit word of description )
      $cpn->word_limit = 10;

      //img_size ( 75x75, 125x125, 250x250, 500x500 )
      $cpn->img_size = '125x125';

      return $cpn->getProducts();
   }



   function getMeta( $product, $spin, $tags, $permalink )
   {
      $cpn = $this->component( 'head' );
      $cpn->keywords = $spin['title1'];
      $cpn->author = AUTHOR;
      $cpn->title = $spin['title1'];
      $cpn->description = trim( strip_tags( $spin['ad1'] ) );
      $cpn->link = $permalink;
      $cpn->property_locale = 'en_US';
      $cpn->property_type = 'article';
      $cpn->property_title = $spin['title1'];
      $cpn->property_description = trim( strip_tags( $spin['ad1'] ) );
      $cpn->property_url = $permalink;
      $cpn->property_site_name = SITE_NAME;

      if ( isset( $tags['tag1'] ) )
         $cpn->property_article_tag1 = $tags['tag1'];

      if ( isset( $tags['tag2'] ) )
         $cpn->property_article_tag2 = $tags['tag2'];

      if ( isset( $tags['tag3'] ) )
         $cpn->property_article_tag3 = $tags['tag3'];

      $cpn->property_article_section1 = $product['brand'];
      $cpn->property_article_section2 = $product['category'];

      $meta = $cpn->getHead();

      $data = null;
      foreach ( $meta as $met )
      {
         $data .= $met . PHP_EOL;
      }
      return $data;
   }


   function setHtmlPath( $permalink, $category )
   {
      //ชื่อของไฟล์ html ที่จะ save
      $html_arr = explode( '/', $permalink );
      $html_filename = end( $html_arr );
      $html_arr = null;
      unset( $html_arr );

      //โฟลเดอร์สำหรับเก็บ Html file
      $cat_slug = Helper::clean_string( $category );
      $product_dir = SITE_STATIC_PATH . $cat_slug . '/';

      //สร้างโฟล์เดอร์ไว้เก็บไฟล์ html
      Helper::make_dir( $product_dir );

      //Full path ของ product page
      $this->html_path = $product_dir . $html_filename;
   }
}
