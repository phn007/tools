<?php

class ConfigModel extends webtools\AppComponent
{
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
            echo $conf['domain'][$i] . ": There is no textdatabase file!!!";
            die();
         }

         //ตรวจสอบ site_name
         if ( empty( $conf['site_name'][$i] ) )
         {
            echo $conf['domain'][$i] . ": There is no site_name text!!!";
            die();
         }

         //ตรวจสอบ prefix sid
         if ( empty( $conf['prefix_sid'][$i] ) )
         {
            echo $conf['domain'][$i] . ": There is no frefix_sid!!!";
            die();
         }

         //ตรวจสอบ statcouter code
         if ( empty( $conf['statcounter'][$i] ) )
         {
            echo $conf['domain'][$i] . ": There is no statcounter code!!!";
            die();
         }


         //แยก statcounter code
         $stat = explode( '#', $conf['statcounter'][$i] );
         $sc_project = $stat[0];
         $sc_security = $stat[1];

         //Site Project Name
         $arr = explode( '/', $project_files[$i] );
         $project_dir = trim( end( $arr ) );

         $arr = explode( '/', $conf['domain'][$i] );
         $site_dir = str_replace( '.', '_', end( $arr ) );


         //setup config file
         $data[] = array(
            'site_type' => $conf['site_type'],
            'domain' => $conf['domain'][$i],
            'site_name' => $conf['site_name'][$i],
            'prefix_sid' => $conf['prefix_sid'][$i],
            'sc_project' => $sc_project,
            'sc_security' => $sc_security,
            'web_type' => $conf['web_type'],
            'web_user' => $conf['web_user'],
            'theme_name' => $conf['theme_name'],
            'url_format' => $conf['url_format'],
            'project' =>  $conf['project'],
            'project_dir' => $project_dir,
            'site_dir' => $site_dir,
            'network' =>  $conf['network'],
            'api_key' =>  $conf['api_key'],
            'hostname' =>  $conf['hostname'],
            'site_category' =>  $conf['site_category'],
            'logo_bg_color' => $conf['logo_bg_color'],
            //'project_files' => $project_files[$i] . '/',
         );
      }

      return $data;
   }


   function setupConfig( $conf_dir )
   {
      //ตรวจสอบชื่อโฟลเดอร์ที่ user ส่งเข้ามา
      //ถ้า user ไม่ได้ส่งชื่อโฟล์เดอร์ของไฟล์ config เข้ามาให้ไปใช้โฟลเดอร์ default แทน
      $conf_dir = isset( $conf_dir ) ? $conf_dir : 'default';

      //prospapi component objec
      $cpn = $this->component( 'prospapi' );

      //อ่านชื่อไฟล์ .ini ในโฟลเดอร์
      $conf_files = $cpn->configList( CONFIG_PATH . '/' . $conf_dir . '/*ini' );


      //ตรวจสอบว่ามีไฟล์ config อยู่ในโฟลเดอร์ที่กำหนดหรือเปล่า
      if ( empty( $conf_files ) )
      {
         echo "Config file not found!!!";
         exit( 0 );
      }


      /*
      * วนลูปผ่านรายชื่อไฟล์ .ini เพื่อ setup ค่าตัวแปรที่ต้องใช้ในการทำงานต่อไป
      * -------------------------------------------------------------------------
      */
      foreach ( $conf_files as $conf_name )
      {
         //อ่านค่าจากไฟล์ .ini
         $conf = new Config_Lite( CONFIG_PATH . $conf_dir . '/' . $conf_name );

         //ชื่อโปรเจ็ค
         $project = str_replace( '.ini', '', $conf_name );

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
         $site_number = $conf->get( null, 'site_number' );

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
            'site_number' => $site_number,
            'merchant_data' => $merchant_data,
            'site_config' => $site_config,
         );

      }
      return $data;
   }
}
