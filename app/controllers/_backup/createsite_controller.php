<?php

class CreateSiteController extends webtools\Controller
{

   function prospapi( $params )
   {
      //ชื่อโฟลเดอร์เก็บไฟล์ .ini  ที่ user ส่งเข้ามา
      $conf_dir = $params ['conf_dir'];

      //Config Model Object
      $conf_model = $this->model( 'config' );


      //ข้อมูลจากไฟล์  .ini ที่ได้รับการปรับแต่งแล้ว
      $conf_data = $conf_model->setupConfig( $conf_dir );

      /*
       * เก็บข้อมูลสินค้าและสร้าง Textdatabase
       * ----------------------------------------------------------------------
      */
      //วนลูปผ่าน config data ของแต่ละ .ini file
      foreach ( $conf_data as $conf )
      {
         //ข้อมูลที่ต้องใช้ในการเก็บสินค้าและสร้าง Textdatabase
         $merchant_data = $conf['merchant_data'];

         //จำนวน textdatabase ที่ต้องการสร้าง
         $site_number = $conf['site_number'];

         /*
          * เก็บข้อมูลสินค้า
          * -------------------------------------------------------------------
         */
         //GetProduct Object Model
         $prod_model = $this->model( 'getproduct' );
         $prod_model->getProductData( $merchant_data );

         /*
          * สร้าง TextDatabase
          * -------------------------------------------------------------------
         */
         $textdb_model = $this->model( 'textdatabase' );
         $textdb_model->createTextDatabase( $site_number, $merchant_data );

         /*
          * สร้างและกำหนดค่าเพิ่มเติมให้กับ site config สำหรับสร้างไซต์
          * -------------------------------------------------------------------
         */
         $site_config = $conf['site_config'];
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
            $conf_site['display_catitem_per_page'] = 100;

            /*
             * สร้าง Website
             * -------------------------------------------------------------------
            */
            $model = $this->model( 'createsite' );
            //สร้าง htmlsite
            if ( 'htmlsite' == $conf_site['site_type'] )
            {
               $model->createHtmlSite( $conf_site );
            }
            //สร้าง textsite
            elseif ( 'textsite' == $conf_site['site_type'] )
            {
               $model->createTextSite( $conf_site );
            }
         }
      }
   }//function
}//class
