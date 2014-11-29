<?php

class ApiCategoryController extends Controller
{

   /*
    * Category ( Items list )
    * -------------------------------------------------------------------------
   */

   function category( $params )
   {

      /*
       * Layout
       * ----------------------------------------------------------------------
      */
      $this->layout = 'layout';
      $this->view = 'items';
      $this->items( $params, 'category' );

   }

   function brand( $params )
   {

      /*
       * Layout
       * ----------------------------------------------------------------------
      */
      $this->layout = 'layout';
      $this->view = 'items';
      $this->items( $params, 'brand' );

   }

   function items( $params, $cat_type )
   {
      /*
       * Define Parameter
       * ----------------------------------------------------------------------
      */
      $cat_name = $params[0];
      $page = $params[1];

      $find = array( 'page-', FORMAT );
      $page = str_replace( $find, '', $page );

      //Cat link
      $cat_link = HOME_URL . $cat_type . '/' . $cat_name . '/' . $params[1];

      /*
       * Category Items
       * ----------------------------------------------------------------------
      */
      $model = $this->model( 'api-category' );
      $data = $model->getItems( $cat_name, $page, $cat_type );
      $this->cat_title = ucfirst( $cat_type );

      $this->item = $data['items'];
      $this->menu = $data['menu'];


      // echo "<pre>";
      // print_r( $this->item['show_items'] );
      // die();


      /*
       * Meta Header
       * ----------------------------------------------------------------------
      */
      $this->meta = $model->getItemMeta( $cat_name, $cat_type, $cat_link );




      /*
       * กำหนดรูปแบบชื่อ html filename และกำหนด path ใช้เก็บ html file
       * ----------------------------------------------------------------------
      */
      if ( 'htmlsite' == SITE_TYPE )
      {
         $filename = 'page-' . $page . '.html';
         $this->html_path = SITE_STATIC_PATH . $cat_type . '/' . $cat_name . '/' . $filename;
      }
   }


   /*
    * Categories ( urls list )
    * -------------------------------------------------------------------------
   */
   function categories()
   {
      $this->cat_menu_state = 'class="active"';

      /*
       * Layout
       * ----------------------------------------------------------------------
      */
      $this->layout = 'layout';
      $this->view = 'list';

      $this->urlList( 'category' );

   }

   function brands()
   {
      $this->brand_menu_state = 'class="active"';

      /*
       * Layout
       * ----------------------------------------------------------------------
      */
      $this->layout = 'layout';
      $this->view = 'list';

      $this->urlList( 'brand' );

   }


   function urlList( $cat_type )
   {
      if ( 'category' == $cat_type ) $title = "Categories";
      elseif ( 'brand' == $cat_type ) $title = "Brands";

      $model = $this->model( 'api-category' );
      $this->cat_list = $model->getUrls( $cat_type );
      $this->cat_title = $title;

      /*
       * Meta Header
       * ----------------------------------------------------------------------
      */
      $this->meta = $model->getListMeta( $title );




      if ( 'htmlsite' == SITE_TYPE )
      {
         $this->html_path   = SITE_STATIC_PATH . strtolower( $title ) . '.html';
      }
   }
}
