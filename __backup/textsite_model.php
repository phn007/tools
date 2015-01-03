<?php
class TextSiteModel extends webtools\AppComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }


   function createTextSite( $conf )
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
       * Copy TextDatabase
       * ----------------------------------------------------------------------
      */
      if ( $option == 'textdb' || $option == 'all' )
      {
         //กำหนด path สำหรับเรียก textdatabase
         $source = TEXTDB_PATH . $conf['project'] . '/'. $conf['project_dir'] . '/';

         if ( ! file_exists( $source ) )
         {
            echo "Text Database Not Found!!!";
            exit( 0 );
         }

         //กำหนด path สำหรับ create textsite
         $dest = $destination . '/contents';

         //copy textdatabase ไปยัง textsite
         $cpn->copydir( $source, $dest );
      }


      /*
       * Copy Sourcecode
       * ----------------------------------------------------------------------
      */
      if ( $option == 'code' || $option == 'all' )
      {
         echo "Generate SourceCode\n";

         //กำหนดไฟล์ที่ไม่จำเป็นต้องใช้สำหรับ textsite
         if ( 'textsite' == $conf['site_type'] )
            $exclude = $cpn->textSiteExcludeFiles();

         //กำหนดไฟล์ที่ไม่จำเป็นต้องใช้สำหรับ apisite
         elseif ( 'apisite' == $conf['site_type'] )
            $exclude = $cpn->apiSiteExcludeFiles();



         //path ของ site creator
         $source = SITE_CREATOR_PATH;

         //copy sourcecode ที่กำหนดไว้ไปยัง textsite
         $cpn->copydir_exclude( $source, $destination, $exclude );
      }


      /*
       * Create Htaccess file
       * ----------------------------------------------------------------------
      */
      if ( $option == 'htaccess' || $option == "all" )
      {
         echo "Create Htaccess file\n";
         $cpn->htaccess( $destination, $conf );
      }

      /*
       * Create Logo Image
       * ----------------------------------------------------------------------
      */
      if ( $option == 'logo' || $option == 'all' )
      {
         echo "Create logo image file\n";
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

      if ( $option == 'sitemap_edit' )
      {
         echo "sitemap edit\n";

         if ( $conf['web_type'] == 'normal')
         {
            $source = HOST_PATH . 'contents/categories/';
            $dest = HOST_PATH . 'sitemap/';
         }
         else
         {
            $source = HOST_PATH . $conf['site_dir'] . '/contents/categories/';
            $dest = HOST_PATH . $conf['site_dir'] . '/sitemap/';
         }

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

      if ( $option == 'sitemap_index_edit' )
      {

         if ( $conf['web_type'] == 'normal')
         {
            $source = HOST_PATH . 'sitemap/';
            $dest = HOST_PATH;
         }
         else
         {
            $source = HOST_PATH . $conf['site_dir'] . '/sitemap/';
            $dest = HOST_PATH . $conf['site_dir'] . '/';

         }

         $cpn->sitemap_index( $source, $dest, $conf['domain'] );
      }

      /*
       * Create Robots
       * ----------------------------------------------------------------------
      */
      if ( $option == 'robots' || $option == 'all' )
      {
         $cpn->robots( $destination, $conf['domain'] );
      }

      if ( $option == 'separator' || $option == 'all' )
      {
         $desc = $destination . '/files/';
         $cpn->separator( $desc );
      }


      /*
       * Copy File to Host
       * ----------------------------------------------------------------------
      */
      if ( $conf['hostname'] == 'linode' )
      {
         if ( $option == 'copytohost' || $option == 'all' )
         //if ( $option == 'copytohost' )
         {
            $cp = "sudo cp -r /home/phan/textsite/" . $conf['project'] . '/' . $conf['web_user'];
            //$cp = "cp -r /root/textsite/" . $conf['project'] . '/' . $conf['web_user'];
            $cp .= " /var/www/";

            shell_exec( $cp );
            echo $cp . "\n";
         }
      }


      if ( $option == 'copytohost' )
      {
         $cp = "sudo cp -r /home/phan/textsite/" . $conf['project'] . '/' . $conf['site_dir'] . '/.';
         $cp .= " /var/www/" . $conf['site_dir'] . '/public_html/';

         shell_exec( $cp );
         echo $cp . "\n";
      }

      if ( $option == 'chmod' )
      {
         $cache = "sudo chmod 777 /var/www/" . $conf['site_dir'] . '/public_html/cache';
         $tmp = "sudo chmod 777 /var/www/" . $conf['site_dir'] . '/public_html/tmp';

         shell_exec( $cache );
         shell_exec( $tmp );

         echo $cache . "\n";
         echo $tmp;
         echo "\n";
      }


      /*
       * CHMOD cache and tmp directory
       * ----------------------------------------------------------------------
      */
      if ( $conf['hostname'] == 'linode' )
      {
         if ( $option == 'chmod' || $option == 'all' )
         {
            $cache = "sudo chmod 777 /var/www/" . $conf['site_dir'] . '/cache';
            $tmp = "sudo chmod 777 /var/www/" . $conf['site_dir'] . '/tmp';

            shell_exec( $cache );
            shell_exec( $tmp );

            echo $cache . "\n";
            echo $tmp;
            echo "\n";
         }
      }

      if ( $conf['hostname'] == 'linode' )
      {
         //if ( $option == 'remove' || $option == 'all' )
         if ( $option == 'remove' )
         {
            $textdb = "sudo rm -rf /home/phan/textdb/" . $conf['project'] . '/' . $conf['project_dir'];
            $textsite = "sudo rm -rf /home/phan/textsite/" . $conf['project'] . '/' . $conf['site_dir'];

            shell_exec( $textdb );
            shell_exec( $textsite );

            echo $textdb . "\n";
            echo $textsite;
            echo "\n";
         }
      }
   }//function
}
