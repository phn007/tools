<?php

class SiteController extends webtools\Controller
{
   function createsite( $params, $options )
   {
      /*
       * Parameters and Options
       * ----------------------------------------------------------------------
      */
      //จัดการกับ parameter และ options
      $opt_model = $this->model( 'option' );
      $opt_data = $opt_model->verify( $params, $options );

      //ชื่อฟังก์ชั่นที่ต้องทำงาน
      $function = $opt_data['function'];

      //ชนิดของ destination dir ที่จะใช้ ( develop/site )
      $destination = $opt_data['destination'];


      /*
       * Config
       * ----------------------------------------------------------------------
      */
      //ข้อมูลจากไฟล์  .ini ที่ได้รับการปรับแต่งแล้ว
      $conf_model = $this->model( 'config' );
      $conf_data = $conf_model->setupConfig( $opt_data );

      /*
       * Show Config Function
       * ----------------------------------------------------------------------
      */
      if ( 'showconfig' == $function['name'] )
      {
         $conf_model->showConfig( $opt_data );
         exit( 0 );
      }


      /*
       * Drop Database Function
       * ----------------------------------------------------------------------
      */
      if ( 'dropdatabase' == $function['name'] )
      {
         $ddb = $this->model( 'textdatabase' );
         $ddb->dropDatabase( $conf_data[0]['merchant_data'] );
         exit();
      }



      /*
       * วนลูปผ่าน config data ของแต่ละ .ini file
       * ----------------------------------------------------------------------
      */
      foreach ( $conf_data as $conf )
      {
         //ค่า config ต่างๆสำหรับเว็บไซต์
         $site_config = $conf['site_config'];

         //ข้อมูลที่ต้องใช้ในการเก็บสินค้าและสร้าง Textdatabase
         $merchant_data = $conf['merchant_data'];

         //จำนวน textdatabase ที่ต้องการสร้าง
         $site_number = $conf['site_number'];

         //ชื่อโปรเจ็ค
         $project = $conf['project'];



         /*
          * นับจำนวนสินค้าทั้งหมด
          * -------------------------------------------------------------------
         */
         if ( 'countproducts' == $function['name'] )
         {
            $count = $this->model( 'countproducts' );
            $count->countProducts( $project, $merchant_data );
            exit( 0 );
         }


         /*
          * เก็บข้อมูลสินค้า
          * -------------------------------------------------------------------
         */
         if ( 'getproduct' == $function['name'] || 'all' == $function['name'] )
         {
            $prod_model = $this->model( 'getproduct' );
            $prod_model->getProductData( $project, $merchant_data );
         }

         /*
          * สร้าง Text Database
          * -------------------------------------------------------------------
         */
         if ( 'textdatabase' == $function['name'] || 'all' == $function['name'] )
         {
            $textdb_model = $this->model( 'textdatabase' );

            //สร้าง Textdatabase สำหรับ apisite
            if ( 'apisite' == $site_config['site_type'] )
            {
               $textdb_model->createTextDBForApiSite( $project, $site_number, $merchant_data );
            }
            //สร้าง Textdatabase สำหรับ textsite และ htmlsite
            else
            {
               $textdb_model->createTextDatabase( $project, $site_number, $merchant_data );
               $textdb_model->createBrand( $project );
            }
         }

         /*
          * สร้างเว็บไซต์
          * -------------------------------------------------------------------
         */
         if ( 'createsite' == $function['name'] || 'all' == $function['name'] )
         {
            /*
             * สร้างและกำหนดค่าเพิ่มเติมให้กับ site config สำหรับสร้างไซต์
             * -------------------------------------------------------------------
            */
            $site_config = $conf_model->setupSiteConfig( $site_config );

            foreach ( $site_config as $conf_site )
            {
               //Spin site Description
               $site_desc = $conf_model->getSiteDescription( $conf_site['site_category'] );
               $conf_site['site_desc'] = $site_desc;

               //Author name
               $site_author = $conf_model->getSiteAuthor();
               $conf_site['site_author'] = $site_author;

               //Product Route URL
               $prod_route = range( 'a', 'z' );
               shuffle( $prod_route );
               $conf_site['prod_route'] = trim( $prod_route[0] );

               //กำหนดจำนวนสินค้าที่แสดงในหน้า category items
               $conf_site['num_cat_item_per_page'] = 48;

               /*
                * สร้าง Website
                * -------------------------------------------------------------------
               */
               $site_type = $conf_site['site_type'];

               //สร้าง htmlsite
               if ( 'htmlsite' == $site_type )
               {
                  $html = $this->model( 'htmlsite' );
                  $html->destination = $destination;
                  $html->option = $function['sub'];
                  $html->createHtmlSite( $conf_site );
               }
               //สร้าง textsite
               elseif ( 'textsite' == $site_type || 'apisite' == $site_type )
               {
                  //Textsite Model Object
                  $text = $this->model( 'textsite' );
                  $text->destination = $destination;
                  $text->option = $function['sub'];
                  $text->createTextSite( $conf_site );
               }
            }//foreach site config
         }//if create site
      }//foreach data config
   }//createsite function
}
