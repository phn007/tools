<?php
class ConfigModel extends webtools\AppComponent
{
   /*
    * CONFIG SECTION
    * -------------------------------------------------------------------------
   */
   function setupConfig( $opt )
   {
      //ตรวจสอบชื่อโฟลเดอร์ที่ user ส่งเข้ามา
      //ถ้า user ไม่ได้ส่งชื่อโฟล์เดอร์ของไฟล์ config เข้ามาให้ไปใช้โฟลเดอร์ default แทน
      if ( 'file' == $opt['conf_dir_type'] )
      {
         $conf_path = CONFIG_PATH . $opt['conf_dir'];
      }
      elseif ( 'directory' == $opt['conf_dir_type'] )
      {
         $conf_path = CONFIG_PATH . $opt['conf_dir'] . '/*.ini';
      }

      //อ่านชื่อไฟล์ .ini ในโฟลเดอร์
      $conf_files = $this->configList( $conf_path );


      //ตรวจสอบว่ามีไฟล์ config อยู่ในโฟลเดอร์ที่กำหนดหรือเปล่า
      if ( empty( $conf_files ) )
      {
         echo "Config file not found!!!";
         exit( 0 );
      }

      $cpn = $this->component( 'prospapi' );
      /*
      * วนลูปผ่านรายชื่อไฟล์ .ini เพื่อ setup ค่าตัวแปรที่ต้องใช้ในการทำงานต่อไป
      * -------------------------------------------------------------------------
      */
      foreach ( $conf_files as $conf_name )
      {

         //อ่านค่าจากไฟล์ .ini
         $conf = new Config_Lite( CONFIG_PATH . $conf_name );

         //ชื่อโปรเจ็ค
         $project = $this->getProjectName( $conf_name );

         //ประเภทของเว็บไซต์ที่จะสร้าง ( textsite, htmlsite );
         $site_type = $conf->get( null, 'site_type' );

         //ชื่อ network ( viglink or prosperent-deeplink, prosperent-api)
         $network = $conf->get( null, 'network' );
         $api_key = $conf->get( null, 'api_key' );

         //Category โดยรวมของเว็บไซต์
         $site_category = $conf->get( null, 'site_category' );

         //ชื่อโฮสที่จะขึ้นเว็บ
         $hostname = $conf->get( null, 'hostname' );

         //ใช้กำหนดว่าจะให้ลบ database ก่อนดึงสินค้ามาใหม่หรือเปล่า
         $clear_database = $conf->get( null, 'clear_database' );

         //จำนวน Textdatabase ที่ต้องการสร้าง
         $domains = $conf->get( 'site_config', 'domain' );
         $site_number = count( $domains );

         //รายชื่อ merchant
         $merchants = $conf->get( 'merchant' );


         /*
          * วนลูปผ่านรายชื่อ merchant เพื่อกำหนดค่าของตัวแปรต่างๆของ merchant ที่ต้องใช้งาน
          * เก็บสินค้า และ สร้าง Textdatabase
          * -------------------------------------------------------------------
         */
         foreach ( $merchants as $merchant  )
         {
            //แปลงชื่อ merchant ให้เป็นชื่อ database
            $db_name = $cpn->convertToDbName( $merchant );

            $merchant_data[ $merchant ] = array(
               'project' => $project,
               'db_name' => $db_name,
               'network' => $network,
               'clear_db' => $clear_database,
            );
         }//foreach merchants


         /*
          * Setup Site config
          * -------------------------------------------------------------------
         */
         $site_config = $conf->get( 'site_config' );

         //เพิ่มตัวแปรที่ต้องใช้เข้าไปใน site config
         $site_config['site_type'] = $site_type;
         $site_config['project'] = $project;
         $site_config['network'] = $network;
         $site_config['api_key'] = $api_key;
         $site_config['hostname'] = $hostname;
         $site_config['site_category'] = $site_category;

         //return data
         $data[] = array(
            'project' => $project,
            'site_number' => $site_number,
            'merchant_data' => $merchant_data,
            'site_config' => $site_config,
         );

      }
      return $data;
   }


   function getProjectName( $conf_name )
   {
      $arr = explode( '/', $conf_name );
      return str_replace( '.ini', '', end( $arr ) );
   }


   function configList( $path )
   {
      $files = glob( $path );

      if ( !empty( $files ) )
      {
         foreach ( $files as $file )
         {
            //$arr = explode( '/', $file );
            //$conf[] = trim( end( $arr ) );
            $c = str_replace( CONFIG_PATH , '', $file );
            $c = ltrim( $c, '/' );
            $conf[] = $c;
         }
         return $conf;
      }
      else
      {
         echo "Config file not found!";
         exit( 0 );
      }
   }


   function setupSiteConfig( $conf )
   {
      //Path ของ TextDatabase
      $textdb_path = TEXTDB_PATH . $conf['project'] . '/';

      //อ่านรายชื่อโฟลเดอร์ของ project ทั้งหมด
      $project_files = glob( $textdb_path . '*', GLOB_ONLYDIR );

      //นับจำนวน domain ที่มีทั้งหมด
      $count = count( $conf['domain'] );

      //ตรวจสอบค่าตัวแปรอะเรย์ต่างๆที่ต้องจับคู่กับ domain
      for ( $i = 0; $i < $count; $i++ )
      {
         //ตรวจสอบ Textdatabase
         if ( empty( $project_files[$i] ) )
         {
            //echo "There is no textdatabase file!!!";
            //die();
            $project_files[$i] = null;
         }

         //ตรวจสอบ site_name
         if ( empty( $conf['site_name'][$i] ) )
         {
            echo "There is no site_name text!!!";
            die();
         }

         //ตรวจสอบ prefix sid
         if ( empty( $conf['prefix_sid'][$i] ) )
         {
            echo "There is no frefix_sid!!!";
            die();
         }

         //ตรวจสอบ statcouter code
         if ( empty( $conf['statcounter'][$i] ) )
         {
            echo "There is no statcounter code!!!";
            die();
         }

         //ตรวจสอบ web_type
         if ( empty( $conf['web_type'][$i] ) )
         {
            echo "There is no web_type!!!";
            die();
         }

         //ตรวจสอบ web_user
         if ( empty( $conf['web_user'][$i] ) )
         {
            echo "There is no web_user!!!";
            die();
         }

         //ตรวจสอบ theme_name
         if ( empty( $conf['theme_name'][$i] ) )
         {
            echo "There is no theme_name!!!";
            die();
         }

         //ตรวจสอบ theme_name
         if ( empty( $conf['logo_bg_color'][$i] ) )
         {
            echo "There is no logo_bg_color!!!";
            die();
         }

         //ตรวจสอบ theme_name
         if ( empty( $conf['url_format'][$i] ) )
         {
            echo "There is no url_format!!!";
            die();
         }



         //แยก statcounter code
         $stat = explode( '#', $conf['statcounter'][$i] );
         $sc_project = $stat[0];
         $sc_security = $stat[1];

         //Site Project Name
         $arr = explode( '/', $project_files[$i] );
         $project_dir = trim( end( $arr ) );


         //Site Directory
         // $arr = explode( '/', $conf['domain'][$i] );
         // $find = array( '.','~' );
         // $replace = array( '_', '' );
         // $site_dir = str_replace( $find, $replace, end( $arr ) );
         $site_dir = $conf['server_name'][$i];

         

         //setup config file
         $data[] = array(
            'site_type' => $conf['site_type'],
            'domain' => $conf['domain'][$i],
            'site_name' => $conf['site_name'][$i],
            'prefix_sid' => $conf['prefix_sid'][$i],
            'sc_project' => $sc_project,
            'sc_security' => $sc_security,
            'web_type' => $conf['web_type'][$i],
            'web_user' => $conf['web_user'][$i],
            'theme_name' => $conf['theme_name'][$i],
            'url_format' => $conf['url_format'][$i],
            'project' =>  $conf['project'],
            'project_dir' => $project_dir,
            'site_dir' => $site_dir,
            'network' =>  $conf['network'],
            'api_key' =>  $conf['api_key'],
            'hostname' =>  $conf['hostname'],
            'site_category' =>  $conf['site_category'],
            'logo_bg_color' => $conf['logo_bg_color'][$i],
         );
      }

      return $data;
   }

   function getSiteAuthor()
   {
      $path = FILES_PATH . 'author_name.txt';

      if ( file_exists( $path ) )
      {
         $file = file( $path );
         shuffle( $file );
         return trim( $file[0] );
      }
      else
      {
         echo "author_name.txt file not found!!!";
         exit( 0 );
      }
   }

   function getSiteDescription( $site_category )
   {
      $cpn = $this->component( 'textspinner' );
      $site_desc = $cpn->spinSiteDescription( $site_category );

      return trim( $site_desc );
   }


   function showConfig( $opt )
   {
      //ตรวจสอบชื่อโฟลเดอร์ที่ user ส่งเข้ามา
      //ถ้า user ไม่ได้ส่งชื่อโฟล์เดอร์ของไฟล์ config เข้ามาให้ไปใช้โฟลเดอร์ default แทน
      if ( 'file' == $opt['conf_dir_type'] )
      {
         $conf_path = CONFIG_PATH . $opt['conf_dir'];
      }
      elseif ( 'directory' == $opt['conf_dir_type'] )
      {
         $conf_path = CONFIG_PATH . $opt['conf_dir'] . '/*.ini';
      }

      //อ่านชื่อไฟล์ .ini ในโฟลเดอร์
      $conf_files = $this->configList( $conf_path );


      //ตรวจสอบว่ามีไฟล์ config อยู่ในโฟลเดอร์ที่กำหนดหรือเปล่า
      if ( empty( $conf_files ) )
      {
         echo "Config file not found!!!";
         exit( 0 );
      }

      $cpn = $this->component( 'prospapi' );
      /*
      * วนลูปผ่านรายชื่อไฟล์ .ini เพื่อ setup ค่าตัวแปรที่ต้องใช้ในการทำงานต่อไป
      * -------------------------------------------------------------------------
      */
      foreach ( $conf_files as $conf_name )
      {
         //อ่านค่าจากไฟล์ .ini
         $conf = new Config_Lite( CONFIG_PATH . $conf_name );

         echo $conf_name;
         echo "\n";
         echo "----------------------------\n";
         echo $conf;
         echo "\n";

      }

   }
}
