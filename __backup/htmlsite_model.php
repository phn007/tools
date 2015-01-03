<?php
class HtmlSiteModel extends webtools\AppComponent
{

   function createHtmlSite( $conf )
   {
      //สร้างไฟล์ config.ini ให้กับ sitegenerator
      $conf_path = SITE_CREATOR_PATH . 'config/config.ini';
      $config = new Config_Lite( $conf_path );
      $config->write( $conf_path, array( 'cfg' => $conf) );

      //กำหนดค่า destination path
      if ( 'develop' == $this->destination['type'] )
      {
         $destination = DEVELOP_PATH . $this->destination['dir'];
      }
      else
      {
         $destination = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'];
      }

      //Textsite Component Object
      $cpn = $this->component( 'textsite' );
      $cpn->site_dir = $conf['site_dir'];

      //sub function
      $option = $this->option;


      /*
       * CSS/JAVASCRIPT
       * ----------------------------------------------------------------------
      */
      if ( $option == 'css' || $option == 'all' )
      {
         $source = SITE_CREATOR_PATH . 'app/views/' . $conf['theme_name'] . '/assets/';
         $dest = $destination . '/css/';
         $cpn->copydir( $source, $destination );
      }


      /*
       * Social Images
       * ----------------------------------------------------------------------
      */
      if ( $option == 'social_img' || $option == 'all' )
      {
         $source = SITE_CREATOR_PATH . 'images/social-share/';
         $dest = $destination . 'images/social-share/';
         $cpn->copydir( $source, $destination );
      }

      /*
       * Create Logo Image
       * ----------------------------------------------------------------------
      */
      if ( $option == 'logo' || $option == 'all' )
      {
         $cpn->dest = $destination . '/images/logo/';
         $cpn->logo_text = $conf['site_name'];
         $cpn->font_logo_path = SITE_CREATOR_PATH . 'images/logo/';
         $cpn->logo_bg_color = $conf['logo_bg_color'];

         $cpn->site_project = $conf['site_dir'];
         $cpn->createLogoImage();
      }

      /*
       * Create Sitemap
       * ----------------------------------------------------------------------
      */
      if ( $option == 'sitemap' || $option == 'all' )
      {
         $source = TEXTDB_PATH . $conf['project'] . '/'. $conf['project_dir'] . '/categories/';
         $dest = $destination . '/sitemap/';
         $cpn->sitemap( $source, $dest, $conf);
      }

      /*
       * Create Sitemap_inex
       * ----------------------------------------------------------------------
      */
      if ( $option == 'sitemap_index' || $option == 'all' )
      {
         $source = $destination . '/sitemap/';
         $cpn->sitemap_index( $source, $destination, $conf['domain'] );
      }

      /*
       * Create Robots
       * ----------------------------------------------------------------------
      */
      if ( $option == 'robots' || $option == 'all' )
      {
         $cpn->robots( $destination, $conf['domain'] );
      }


      /*
      * HTMLSITE COMPONENT PART
      * ----------------------------------------------------------------------
      */
      $html = $this->component( 'htmlsite' );
      $html->site_dir = $conf['site_dir'];

      //Index Page
      if ( $option == 'home' || $option == 'all'  )
      {
         $html->dest = $destination;
         $html->createIndexPage();
      }

      //Default Pages
      if ( $option == 'pages' || $option == 'all'  )
      {
         $html->dest = $destination;
         $html->createDefaultPage();
      }

      //Categories Page
      if ( $option == 'categories' || $option == 'all'  )
      {
         $html->dest = $destination;
         $html->createCategoryListPage();
      }

      //Brands Page
      if ( $option == 'brands' || $option == 'all'  )
      {
         $html->dest = $destination;
         $html->createBrandListPage();
      }

      //Category Items
      if ( $option == 'category' || $option == 'all'  )
      {
         $html->source = TEXTDB_PATH . $conf['project'] . '/' . $conf['project_dir'] . '/';
         $html->dest = $destination;
         $html->item_per_page = $conf['num_cat_item_per_page'];
         $html->createCaterotyItemsPage();
      }

      //Category Items
      if ( $option == 'brand' || $option == 'all'  )
      {
         $html->source = TEXTDB_PATH . $conf['project'] . '/' . $conf['project_dir'] . '/';
         $html->dest = $destination;
         $html->item_per_page = $conf['num_cat_item_per_page'];
         $html->createBrandItemsPage();
      }

      //Product Page
      if ( $option == 'products' || $option == 'all'  )
      {
         $html->source = TEXTDB_PATH . $conf['project'] . '/' . $conf['project_dir'] . '/';
         $html->dest = $destination;
         $html->createProductPage();
      }

   }
}
