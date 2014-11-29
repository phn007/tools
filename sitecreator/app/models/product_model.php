<?php
class ProductModel extends AppComponent
{
   function getProducts( $product_file, $product_key )
   {
      $cpn = $this->component( 'product' );
      $cpn->product_file = $product_file;
      $cpn->product_key = $product_key;

      //Source Path
      if ( 'textsite' == SITE_TYPE )
      {
         $cpn->product_path = CONTENT_PATH . 'categories/';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $cpn->product_path = TEXTDB_PATH . 'categories/';
      }


      //อ่านข้อมูลสินค้า
      $file = $cpn->getProductFile();
      $product = $cpn->getProducts( $file );


      //Create Redirect to Merchant URL
      if ( SITE_TYPE == 'textsite'  )
      {
         $product['goto'] = HOME_URL . 'shop/' . $product_file . '/' . $product_key;
      }

      if ( SITE_TYPE == 'htmlsite' )
      {
         $referer = Helper::get_permalink( $product_file, $product_key, '' );
         $product['goto'] = $product['affiliate_url'];
      }


      return $product;
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
      $prod = $this->component( 'product' );
      $prod->product_file = $product_file;
      $prod->product_key = $product_key;


      //Source Path
      if ( 'textsite' == SITE_TYPE )
      {
         $prod->product_path = CONTENT_PATH . 'categories/';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $prod->product_path = TEXTDB_PATH . 'categories/';
      }

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


   function getRelatedProduct( $product_file, $product_key, $category )
   {
      $cpn = $this->component( 'relatedproduct' );
      $cpn->path = 'cache/related-products';
      $cpn->file = $product_file;
      $cpn->key = $product_key;
      $cpn->category = $category;
      $cpn->content_path = CONTENT_PATH . 'categories/';

      if ( 'textsite' == SITE_TYPE )
      {
         $cpn->content_path = CONTENT_PATH . 'categories/';
      }
      elseif ( 'htmlsite' == SITE_TYPE )
      {
         $cpn->content_path = TEXTDB_PATH . 'categories/';
      }

      $cpn->num = RELATED_PRODUCT_NUM;

      // related_products
      /*
         affiliate_url
         image_url
         keyword
         description
         category
         price
         merchant
         brand
         goto
      */

      /*
         Parameter
         ---------
         1. word_limit ( limit word of description )
         2. img_size ( 75x75, 125x125, 250x250, 500x500 )

      */
      return $cpn->getProducts( $product_file, 10, $img_size = '125x125' );
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
      $product_dir = HTMLSITE_PATH . $cat_slug . '/';

      //สร้างโฟล์เดอร์ไว้เก็บไฟล์ html
      Helper::make_dir( $product_dir );

      //Full path ของ product page
      return $html_path = $product_dir . $html_filename;

   }
}
