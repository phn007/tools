<?php
class ApiProductController extends Controller
{
   function index( $params )
   {
      /*
       * Page Type
       * -----------------------------------------------------------------------
      */
      $this->product_page = true;

      /*
       * Layout
       * -----------------------------------------------------------------------
      */
      $this->layout = 'layout';
      $this->view = 'index';

      /*
       * Define Text database variable
       * -----------------------------------------------------------------------
      */
      $product_file = $params[0];
      $product_key  = $params[1];
      unset( $params );

      /*
       * Product Model Object
       * -----------------------------------------------------------------------
      */
      $model = $this->model( 'api-product' );

      /*
       * Product Data
       * -----------------------------------------------------------------------
      */
      $product = $model->getProducts( $product_file, $product_key );

      //affiliate_url, image_url, keyword, description, category
      //price, merchant, brand
      foreach ( $product as $key => $value ) $this->$key = $value;


      /*
       * Link
       * -----------------------------------------------------------------------
      */
      $links = $model->getLinks( $product_file, $product_key, $product );

      //permalink, brand_link, $category_link
      foreach ( $links as $key => $value ) $this->$key = $value;

      /*
       * Nav Menu
       * -----------------------------------------------------------------------
      */
      $navmenu = $model->getNavMenu( $product_file, $product_key );
      foreach ( $navmenu as $key => $value ) $this->$key = $value;


      /*
       * SpinContent
       * -----------------------------------------------------------------------
      */
      $spin = $model->getSpinContent( $product['keyword'], $product_file, $product_key );
      //ad1, ad2, ad_desc, linkout1, more_info1, more_info2
      //title1, title2, spam
      foreach ( $spin as $key => $value ) $this->$key = $value;


      /*
       * Text For Searching
       * -----------------------------------------------------------------------
      */
      $this->textsearch = $model->textForSearch( $product_key );

      /*
       * Tags  ( for seo optimize )
       * -----------------------------------------------------------------------
      */
      $tags = $model->createTags( $product['keyword'] );

      /*
       * Relate Product
       * -----------------------------------------------------------------------
      */
      $this->related = $model->getRelatedProduct( $product_file, $product_key, $product['category'] );


      /*
       * Meta Header
       * -----------------------------------------------------------------------
      */
      $this->meta = $model->getMeta( $product, $spin, $tags, $links['permalink'] );


      /*
       * HTMLSITE
       * -----------------------------------------------------------------------
      */
      if ( SITE_TYPE == 'htmlsite' )
      {
         $this->html_path = setHtmlPath( $links['permalink'], $product['category'] );
      }


   }
}
