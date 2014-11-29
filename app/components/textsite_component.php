<?php

class TextsiteComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }


   /**
    * Copy Sourcecode
    * -------------------------------------------------------------------------
   */
   function copydir_exclude( $source, $destination, $exclude )
   {
      //สร้างโฟลเดอร์
      webtools\Helper::make_dir( $destination );

      if ( ! is_dir( $destination ) )
      {
         $oldumask = umask( 0 );
         mkdir( $destination, 01777 ); // so you get the sticky bit set
         umask( $oldumask );
      }

      $dir_handle = @opendir( $source ) or die("Unable to open");

      while ( $file = readdir( $dir_handle ) )
      {
         if ( !in_array( $file, $exclude ) )
         {
            //echo $file . PHP_EOL;
            if ( $file != "." && $file != ".." && ! is_dir( "$source/$file" ) ) //if it is file
               copy( "$source/$file", "$destination/$file" );

            if( $file != "." && $file != ".." && is_dir( "$source/$file" ) ) //if it is folder
               $this->copydir_exclude( "$source/$file", "$destination/$file", $exclude );
         }
      }
      closedir($dir_handle);

      echo $this->site_dir . ' -> ';
      echo 'copied: ' . $destination;
      echo PHP_EOL;
   }



   /**
    * Copy Sourcecode Only
    * -------------------------------------------------------------------------
   */
   function copydir_exclude_only( $source, $destination, $exclude )
   {
      //สร้างโฟลเดอร์
      webtools\Helper::make_dir( $destination );

      if ( ! is_dir( $destination ) )
      {
         $oldumask = umask( 0 );
         mkdir( $destination, 01777 ); // so you get the sticky bit set
         umask( $oldumask );
      }

      $dir_handle = @opendir( $source ) or die("Unable to open");

      while ( $file = readdir( $dir_handle ) )
      {
         if ( !in_array( $file, $exclude ) )
         {
            //echo $file . PHP_EOL;
            if ( $file != "." && $file != ".." && ! is_dir( "$source/$file" ) ) //if it is file
               copy( "$source/$file", "$destination/$file" );

            if( $file != "." && $file != ".." && is_dir( "$source/$file" ) ) //if it is folder
               $this->copydir_exclude_only( "$source/$file", "$destination/$file", $exclude );
         }
      }
      closedir($dir_handle);

      echo 'copied: ' . $destination;
      echo PHP_EOL;
   }



   function copydir_include( $source, $destination, $include )
   {
      //สร้างโฟลเดอร์
      webtools\Helper::make_dir( $destination );

      if ( ! is_dir( $destination ) )
      {
         $oldumask = umask( 0 );
         mkdir( $destination, 01777 ); // so you get the sticky bit set
         umask( $oldumask );
      }

      $dir_handle = @opendir( $source ) or die("Unable to open");

      while ( $file = readdir( $dir_handle ) )
      {
         if ( in_array( $file, $include ) )
         {
            //echo $file . PHP_EOL;
            if ( $file != "." && $file != ".." && ! is_dir( "$source/$file" ) ) //if it is file
               copy( "$source/$file", "$destination/$file" );

            if( $file != "." && $file != ".." && is_dir( "$source/$file" ) ) //if it is folder
               $this->copydir_include( "$source/$file", "$destination/$file", $include );
         }
      }
      closedir( $dir_handle );

      echo 'copied: ' . $destination;
      echo PHP_EOL;
   }


   function copydir( $source, $destination )
   {
      //สร้างโฟลเดอร์
      webtools\Helper::make_dir( $destination );

      if ( ! is_dir( $destination ) )
      {
         $oldumask = umask( 0 );
         mkdir( $destination, 01777 ); // so you get the sticky bit set
         umask( $oldumask );
      }

      $dir_handle = @opendir( $source ) or die("Unable to open");

      while ( $file = readdir( $dir_handle ) )
      {

         //echo $file . PHP_EOL;
         if ( $file != "." && $file != ".." && ! is_dir( "$source/$file" ) ) //if it is file
            copy( "$source/$file", "$destination/$file" );

         if( $file != "." && $file != ".." && is_dir( "$source/$file" ) ) //if it is folder
            $this->copydir( "$source/$file", "$destination/$file");

      }
      closedir($dir_handle);

      echo $this->site_dir . ' -> ';
      echo 'copied: ' . $destination;
      echo PHP_EOL;
   }


   /**
    * Create Htaccess
    * -------------------------------------------------------------------------
   */
   function htaccess( $dest, $cfg )
   {
      if ( isset( $cfg['web_type'] ) )
         $web_type = $cfg['web_type'];

      if ( isset( $cfg['web_user'] ) )
         $web_user = $cfg['web_user'];


      $hta = 'RewriteEngine On' . PHP_EOL;
      #RewriteBase /

      if ( 'temporary' == $web_type )
      {
         if ( empty( $web_user ) )
            die( PHP_EOL . 'Cannot empty web_user variable! ( config file )'. PHP_EOL );

         $hta .= 'RewriteBase /' . $web_user . PHP_EOL;
      }

      $hta .= 'RewriteCond %{REQUEST_FILENAME} !-f' . PHP_EOL;
      $hta .= 'RewriteCond %{REQUEST_FILENAME} !-d' . PHP_EOL;
      $hta .= 'RewriteRule (.*) index.php/$1' . PHP_EOL;



      webtools\Helper::make_dir( $dest );
      $fh = fopen( $dest . '/.htaccess', 'w' );
      fwrite( $fh, $hta );
      fclose( $fh );

      echo $this->site_dir . ' -> ';
      echo 'created: ' . $dest . '/.htaccess';
      echo PHP_EOL;
   }



   /**
    * Create Sitemap
    * -------------------------------------------------------------------------
   */
   function sitemap( $source, $dest, $conf )
   {
      /*
       * กำหนดตัวแปร
       * -----------------------------------------------------------------------
      */
      $sitemap_dir = $dest;
      $product_dir = $source;

      $home_url = $conf['domain'];
      $format = $conf['url_format'];
      $prod_route = $conf['prod_route'];

      date_default_timezone_set ( 'America/Los_Angeles' );
      $freq     = "daily";
      $priority = "0.5";

      //สร้างโฟลเดอร์สำหรับเก็บไฟล์ sitemap
      webtools\Helper::make_dir( $sitemap_dir );

      //สร้างตัวนับลูปของ textdatabase ไฟล์ ( categories )
      $count_file = 1;

      //สร้างตัวนับเพื่อใช้ในการสร้างชื่อ sitemap ไฟล์
      $count_sitemap_file = 1;

      //สร้างตัวนับจำนวนสินค้า/sitemap
      $count_product_per_file = 1;

      //สร้างตัวนับจำนวนสินค้าทั้งหมด
      $count_all_products = 1;



      //ตั้งชื่อไฟล์ sitemap
      $sitemap_name = 'sitemap-' . $count_sitemap_file . '.xml';

      //สร้าง Header ของ xml file แรก
      $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

      /*
      * เปิดไฟล์ xml ใหม่เพื่อเขียน header
      * -----------------------------------------------------------------
      */
      echo $this->site_dir . ' -> create: ';
      echo $sitemap_file = $sitemap_dir . $sitemap_name;
      echo "\n";

      $fh = fopen( $sitemap_file, 'w' );
      fwrite( $fh, $xml );

      /*
       * อ่านชื่อไฟล์จาก categories Folder
       * -----------------------------------------------------------------------
      */
      $files = glob( $product_dir . '*.txt' );

      /*
       * วนลูปผ่านชื่อไฟล์
       * -----------------------------------------------------------------------
      */
      foreach ( $files as $file )
      {
         //ชื่อของ textdatabase file ( categories )
         $filename = explode( '/', $file );
         $filename = end( $filename );

         //อ่านข้อมูลในไฟล์ขึ้นมา
         $str = file_get_contents( $file );
         $items = unserialize( $str );

         /*
          * วนลูปผ่านจำนวนข้อมูลในแต่ละไฟล์
          * --------------------------------------------------------------------
         */
         foreach ( $items as $key => $item )
         {
            /*
             * ตัวนับเพื่อตั้งชื่อ sitemap ไฟล์
             * -----------------------------------------------------------------
            */
            if ( $count_product_per_file > 5000 )
            {
               //ปิด tag sitemap ไฟล์ก่อนหน้า
               $endxml = '</urlset>';
               fwrite( $fh, $endxml );
               fclose( $fh );


               //ถ้านับจำนวนสินค้าครบตามจำนวนที่กำหนด ให้เพิ่มหมายเลขให้ชื่อไฟล์
               $count_sitemap_file++;

               //ตั้งชื่อไฟล์ sitemap ไฟล์ต่อไป
               $sitemap_name = 'sitemap-' . $count_sitemap_file . '.xml';

               //สร้าง Header ของ xml file
               $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL .
                     '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

               /*
                * เปิดไฟล์ xml ใหม่เพื่อเขียน header
                * -----------------------------------------------------------------
               */
               echo $this->site_dir . ' -> created ';
               echo $sitemap_file = $sitemap_dir . $sitemap_name;
               echo "\n";

               $fh = fopen( $sitemap_file, 'w' );
               fwrite( $fh, $xml );

               //แล้วเริ่มนับ จำนวนสินค้า/sitemap ใหม่
               $count_product_per_file = 1;

               /*
                * กำหนดตัวแปรที่ต้องใช้ในการสร้าง url ของสินค้า
                * -----------------------------------------------------------------
               */
               $cat_name = webtools\Helper::clean_string( $item['category'] );
               $lastmod = date ( "Y-m-d" );
               $product_file = str_replace( '.txt', '', $filename );

               $url_data = array(
                  'site_type' => $conf['site_type'],
                  'home_url' => $home_url,
                  'product_file' => $product_file,
                  'product_key' => $key,
                  'category' => $cat_name,
                  'format' => $format,
                  'prod_route' => $prod_route
               );
               //สร้าง url
               $url = Permalink::get( $url_data );

               /*
                * เขียน sitemap url ของสินค้า
                * -----------------------------------------------------------------
               */
               $entry = '<url>' . PHP_EOL .
               '<loc>' . $url . '</loc>'. PHP_EOL .
               '<lastmod>' . $lastmod . '</lastmod>' . PHP_EOL .
               '<changefreq>' . $freq . '</changefreq>' . PHP_EOL .
               '<priority>' . $priority . '</priority>' . PHP_EOL .
               '</url>' . PHP_EOL;

               fwrite( $fh, $entry );
            }
            else
            {
               /*
                * Sitemap ไฟล์แรกต้องมี home url อยู่อันดับแรก
                * -----------------------------------------------------------------
               */
               if ( $count_file == 1 && $count_all_products == 1 )
               {
                  //เขียน sitemap url แรกเป็น home_url
                  $lastmod = date ( "Y-m-d" );
                  $entry = '<url>' . PHP_EOL .
                  '<loc>' . $home_url . '</loc>'. PHP_EOL .
                  '<lastmod>' . $lastmod . '</lastmod>' . PHP_EOL .
                  '</url>' . PHP_EOL;
                  fwrite( $fh, $entry );
               }

               /*
                * กำหนดตัวแปรที่ต้องใช้ในการสร้าง url ของสินค้า
                * -----------------------------------------------------------------
               */
               $cat_name = webtools\Helper::clean_string( $item['category'] );
               $lastmod = date ( "Y-m-d" );
               $product_file = str_replace( '.txt', '', $filename );

               $url_data = array(
                  'site_type' => $conf['site_type'],
                  'home_url' => $home_url,
                  'product_file' => $product_file,
                  'product_key' => $key,
                  'category' => $cat_name,
                  'format' => $format,
                  'prod_route' => $prod_route
               );
               //สร้าง url
               $url = Permalink::get( $url_data );

               /*
                * เขียน sitemap url ของสินค้า
                * -----------------------------------------------------------------
               */
               $entry = '<url>' . PHP_EOL .
               '<loc>' . $url . '</loc>'. PHP_EOL .
               '<lastmod>' . $lastmod . '</lastmod>' . PHP_EOL .
               '<changefreq>' . $freq . '</changefreq>' . PHP_EOL .
               '<priority>' . $priority . '</priority>' . PHP_EOL .
               '</url>' . PHP_EOL;

               fwrite( $fh, $entry );
            }

            //เพิ่มจำนวนตัวนับสินค้า / sitemap
            $count_product_per_file++;

            //เพิ่มจำนวนตัวนับสินค้าทั้งหมด
            $count_all_products++;
         }

         //เพิ่มตัวนับจำนวนไฟล์
         $count_file++;
      }
      //End Loop

      $endxml = '</urlset>';
      fwrite( $fh, $endxml );
      fclose( $fh );

   }

   /**
    * Create Sitemap Index
    * -------------------------------------------------------------------------
   */
   function sitemap_index( $source, $dest, $home_url )
   {
      $home_url = rtrim( $home_url, '/' );

      $path = $source . '*.xml';
      $files  = glob( $path );

      //สร้าง sitemap index ไฟล์
      $sitemap_index_path = $dest . '/sitemap_index.xml';
      $fh = fopen( $sitemap_index_path, 'w' );

      $head_xml =  '<?xml version="1.0" encoding="UTF-8"?>'  . PHP_EOL .
      '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
      fwrite( $fh, $head_xml );

      date_default_timezone_set ( 'America/Los_Angeles' );

      //init var
      //$xml = null;

      $i = 1;
      foreach ( $files as $file ) {

         $arr = explode( '/', $file );
         $filename = end( $arr );
         $url = $home_url . '/sitemap/' . $filename;

         $lastmod = date ( "Y-m-d" );

         $xml = '<sitemap>'  . PHP_EOL;
         $xml .= '<loc>' . $url . '</loc>'  . PHP_EOL;
         $xml .= '<lastmod>' . $lastmod . '</lastmod>'  . PHP_EOL;
         $xml .= '</sitemap>'  . PHP_EOL;
         fwrite( $fh, $xml );
         $xml = null;

         $i++;
      }

      $xml = '</sitemapindex>';
      fwrite( $fh, $xml );

      fclose( $fh );

      echo $this->site_dir . ' -> ';
      echo 'created: ' . $sitemap_index_path;
      echo "\n";
   }


   /**
    * Create Sitemap Index
    * -------------------------------------------------------------------------
   */
   function robots( $dest, $domain )
   {
      $dest = rtrim( $dest, '/' );
      $domain = rtrim( $domain, '/' );

      webtools\Helper::make_dir( $dest );

      $fh = fopen( $dest . '/robots.txt' , 'w' );
      $robot  = 'User-agent: *' . PHP_EOL;
      $robot .= 'Disallow: /shop/' . PHP_EOL;
      $robot .= 'Sitemap: ' . $domain . '/sitemap_index.xml';
      fwrite( $fh, $robot );
      fclose( $fh );

      echo $this->site_dir . ' -> ';
      echo 'created: ' . $dest . '/robots.txt';
      echo PHP_EOL;
   }


   /**
    * Create Logo image
    * ------------------------------------------------------------------------
   */
   function createLogoImage()
   {
      //กำหนดตัวแปร
      $dest = $this->dest;
      $logo_text = $this->logo_text;
      $font_logo_path = $this->font_logo_path;
      $logo_bg_color = $this->logo_bg_color;


      webtools\Helper::make_dir( $dest );

      $img_size = explode( ',', '350,30' );
      $text_color = explode( ',', '255, 255, 255' );
      //$bg_color = explode( ',', '231, 76, 60' );
      $bg_color = explode( ',', $logo_bg_color );
      $font_size = '20';
      $pos = explode( ',', '0, 0, 20' );


     //creates a image handle
     //$img = imagecreate( 500, 200 );
     $img = imagecreate( $img_size[0], $img_size[1] );

     //choose a bg color, u can play with the rgb values
     //$background = imagecolorallocate( $img, 232, 0, 135 );
     $background = imagecolorallocate( $img, $bg_color[0], $bg_color[1], $bg_color[2] );

     //chooses the text colors
     //$text_colour = imagecolorallocate( $img, 255, 255, 255 );
     $text_colour = imagecolorallocate( $img, $text_color[0], $text_color[1], $text_color[2] );

     //sets the thickness/bolness of the line
     imagesetthickness ( $img, 3 );

     //draws a line  params are (imgres,x1,y1,x2,y2,color)
     //imageline( $img, 20, 130, 165, 130, $text_colour );


     // place the font file in the same dir level as the php file
     $font = $font_logo_path . 'arialuni.ttf';



     //this function sets the font size, places to the co-ords
     //imagettftext($img, 100, 0, 11, 120, $text_colour, $font, $text);
     imagettftext ($img, $font_size, $pos[0], $pos[1], $pos[2], $text_colour, $font, $logo_text);

     //places another text with smaller size
     //imagettftext($img, 20, 0, 10, 160, $text_colour, $font, 'Small Text');

     //alerts the browser abt the type of content i.e. png image
     //header( 'Content-type: image/png' );
     //now creates the image
     //imagepng( $img );

     //create and save
     imagepng( $img, $dest . 'logo.png' );

     //destroys used resources
     //imagecolordeallocate( $text_color );
     //imagecolordeallocate( $background );
     imagedestroy( $img );

     echo $this->site_project . " -> Created " . $logo_text . " logo: done...";
     echo "\n";
   }


   function separator( $dest )
   {
      //ที่อยู่ของไฟล์
      $path =  FILES_PATH . 'separator_category.txt';

      //ตรวจสอบว่ามีไฟล์อยู่หรือเปล่า
      if ( file_exists( $path ) )
      {
         //อ่านไฟล์ขึ้นมา
         $files = file( $path );
         $files = array_map( 'trim', $files );

         //แยกข้อมูลแล้วเก็บไว้ในอะเรย์
         foreach ( $files as $file )
         {
            $arr = explode( '|', $file );
            $separator[$arr[0]] = $arr[1];
         }

         $path = $dest . 'separator_category.txt';
         $separator = serialize( $separator );
         file_put_contents( $path, $separator );

         echo $this->site_dir . ' -> ';
         echo "Created -> separator_category.txt done...";
         echo "\n";
      }
      else
      {
         die( $path . ': File not found!!!');
      }
   }


   function textSiteExcludeFiles()
   {
      return array(
         'htmlindex.php',
         'api-category_controller.php',
         'api-home_controller.php',
         'api-product_controller.php',
         'api-category_model.php',
         'api-home_model.php',
         'api-product_model.php',
         'api_component.php',
         'api-category_component.php',
         'api-home_component.php',
         'api-product_component.php',
         'api-relatedproduct_component.php',
      );
   }


   function apiSiteExcludeFiles()
   {
      return array(
         'htmlindex.php',
         'category_controller.php',
         'home_controller.php',
         'product_controller.php',
         'category_model.php',
         'home_model.php',
         'product_model.php',
         'category_component.php',
         'home_component.php',
         'product_component.php',
         'relatedproduct_component.php',
      );
   }
}
