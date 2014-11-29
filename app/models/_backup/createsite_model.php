<?php

class CreateSiteModel extends webtools\AppComponent
{
   /*
    * *** CREATE SINGLE TEXTSITE SECTION ***
    * -------------------------------------------------------------------------
   */
   function createTextSite( $conf )
   {
      //สร้างไฟล์ config.ini ให้กับ sitegenerator
      $config = new Config_Lite( SITE_CREATOR_PATH . 'config/config.ini');
      $config->write( SITE_CREATOR_PATH . 'config/' .'config.ini', array( 'cfg' => $conf) );

      //Component Object
      $cpn = $this->component( 'textsite' );

      /*
       * Copy TextDatabase
       * ----------------------------------------------------------------------
      */
      //กำหนด path สำหรับเรียก textdatabase
      $source = TEXTDB_PATH . $conf['project'] . '/'. $conf['project_dir'] . '/';

      //กำหนด path สำหรับ create textsite
      $dest = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'] . '/contents';

      //copy textdatabase ไปยัง textsite
      $cpn->copydir( $source, $dest );


      /*
       * Copy Sourcecode
       * ----------------------------------------------------------------------
      */
      //กำหนดไฟล์ที่ไม่ต้องการจะ copy
      $exclude = array(
         'cache',
         'tmp',
         'logo.png',
         '.svn',
         'edit.php',
         'htmlindex.php',
         'appspot-bt-less-red',
         's3-bt-less-red',
         'html_router.php',
         'docs/examples'
      );
      //path ของ sitegerator
      $source = SITE_CREATOR_PATH;

      //path ของ textsite
      $dest = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'];

      //copy sourcecode ที่กำหนดไว้ไปยัง textsite
      $cpn->copydir_exclude( $source, $dest, $exclude );

      /*
       * Create Htaccess file
       * ----------------------------------------------------------------------
      */
      $dest = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'];
      $cpn->htaccess( $dest, $conf );

      /*
       * Create Sitemap xml
       * ----------------------------------------------------------------------
      */
      $source = TEXTDB_PATH . $conf['project'] . '/'. $conf['project_dir'] . '/products/';
      $dest = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'] . '/sitemap/';
      $cpn->sitemap( $source, $dest, $conf);

      /*
       * Create Sitemap Index
       * ----------------------------------------------------------------------
      */
      $source = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'] . '/sitemap/';
      $dest = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'];
      $cpn->sitemap_index( $source, $dest, $conf['domain'] );

      /*
       * Create Robots.txt
       * ----------------------------------------------------------------------
      */
      $dest = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'];
      $cpn->robots( $dest, $conf['domain'] );


      /**
      * Create Logo image
      * ------------------------------------------------------------------------
      */
      $dest = TEXTSITE_PATH . $conf['project'] . '/'. $conf['site_dir'] . '/images/logo';
      $logo_text = $conf['site_name'];
      $font_logo_path = SITE_CREATOR_PATH . 'images/logo/';

      $cpn->site_project = $conf['site_dir'];
      $cpn->createLogoImage( $dest, $logo_text, $font_logo_path );

   }


   /*
    * *** CREATE HTML SITE SECTION ***
    * -------------------------------------------------------------------------
   */

   function createHtmlSite( $conf )
   {
      //save ini file ไปให้ sitegenerator
      $conf['textdb_path'] = TEXTDB_PATH;
      $conf['htmlsite_path'] = HTMLSITE_PATH;

      $config = new Config_Lite( SITE_CREATOR_PATH . 'config/config.ini');
      $config->write( SITE_CREATOR_PATH . 'config/' .'config.ini', array( 'conf' => $conf ) );


      $htmlsite_root = HTMLSITE_PATH . $conf['project'] . '/'. $conf['site_dir'] . '/';
      $textdb_project_path = TEXTDB_PATH . $conf['project'] . '/'. $conf['project_dir'] . '/';


      //htmlsite Component Object
      $cpn = $this->component( 'htmlsite' );

      //ส่งตัวแปรไปให้ htmlsite component
      $cpn->site_project = $conf['project_dir'];
      $cpn->htmlsite_root = $htmlsite_root;
      $cpn->textdb_project_path = $textdb_project_path;
      $cpn->display_catitem_per_page = $conf['display_catitem_per_page'];


      //สร้างโฟลเดอร์หลักของ htmlsite
      webtools\Helper::make_dir( $htmlsite_root );

      /**
      * Create Logo image
      * ------------------------------------------------------------------------
      */
      $dest = $htmlsite_root . '/images/logo';
      $logo_text = $conf['site_name'];
      $font_logo_path = SITE_CREATOR_PATH . 'images/logo/';
      $cpn->createLogoImage( $dest, $logo_text, $font_logo_path );



      /**
      * Copy CSS + JS
      * ------------------------------------------------------------------------
      */
      $source = SITE_CREATOR_PATH . 'app/views/' . $conf['theme_name'] . '/assets/';
      $dest = $htmlsite_root . 'assets';
      $cpn->copydir( $source, $dest );


      /**
      * Social Image
      * ------------------------------------------------------------------------
      */
      $source = SITE_CREATOR_PATH . 'images/social-share/';
      $dest = $htmlsite_root . 'images/social-share/';
      $cpn->copydir( $source, $dest );

      /**
       * Generate Index Html
       * -----------------------------------------------------------------------
      */
      $cpn->createIndexPage();

      /**
       * Generate Default Pages
       * -----------------------------------------------------------------------
      */
      $cpn->createDefaultPage();

      /**
       * Generate Category list page
       * -----------------------------------------------------------------------
      */
      $cpn->createCategoryListPage();

      /**
       * Generate Brand list page
       * -----------------------------------------------------------------------
      */
      $cpn->createBrandListPage();

      /**
       * Generate Category items page
       * -----------------------------------------------------------------------
      */
      $cpn->createCaterotyItemsPage();

      /**
       * Generate Brand items page
       * -----------------------------------------------------------------------
      */
      $cpn->createBrandItemsPage();

      /**
       * Generate Sitemap
       * -----------------------------------------------------------------------
      */
      if ( 's3' == $conf['hostname'])
      {
         $cpn->home_link = $conf['domain'] . '/index.html';
      }
      else
      {
         $cpn->home_link = $conf['domain'];
      }

      $cpn->home_url = $conf['domain'];
      $cpn->prod_route = $conf['prod_route'];
      $cpn->format = $conf['url_format'];

      $source = $textdb_project_path . 'products/';
      $dest = $htmlsite_root . 'sitemap/';
      $cpn->createSitemap( $source, $dest );

      /**
       * Generate Sitemap Index
       * -----------------------------------------------------------------------
      */
      $source = $htmlsite_root . 'sitemap/';
      $dest = $htmlsite_root;
      $cpn->createSitemapIndex( $source, $dest );


      /**
       * Generate Robots.txt
       * -----------------------------------------------------------------------
      */
      $dest = $htmlsite_root;
      $domain = $conf['domain'];
      $cpn->createRobots( $dest, $domain );


      /**
       * Generate Product Page
       * -----------------------------------------------------------------------
      */
      $cpn->createProductPage();
   }
}//class
