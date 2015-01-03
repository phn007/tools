<?php

class GetProductModel extends webtools\AppComponent
{

   /**
    * เก็บ Categories, Brands, Product Detail ตาม Merchant
    * @param mixed $merchant_data ตัวแปรต่างๆที่จำเป็นในการดึงข้อมูลสินค้า
    * @return void บันทึกลงฐานข้อมูลเลย
    * -------------------------------------------------------------------------
   */
   function getProductData( $project, $merchant_data )
   {
      $cpn = $this->component( 'prospapi' );


      //ชื่อโปรเจ็ค สำหรับเอาไปแสดงที่หน้าจอขณะ runtime
      $project = ucfirst( $project );

      //Connect Database
      $conn = $cpn->connectDatabase();

      //วนลูปผ่าน merchant data
      foreach ( $merchant_data as $merchant => $data )
      {
         /*
            project => test
            db_name => prosp_shopbop_east_dane
            network => prosperent-api
            clear_db => 1
            num_split => 3000
         */

         $db_name = $data['db_name'];

         //ถ้าถูกกำหนดให้ลบ Database เก่าออกก่อน
         if ( $data['clear_db'] )
         {
            //ลบ database
            $cpn->deleteDatabase( $conn, $db_name );
         }

         //ถ้า merchant ยังไม่มี database ให้ไปดึงข้อมูลสินค้ามา
         if ( ! $cpn->selectDatabase( $conn, $db_name ) )
         {
            //สร้าง database
            if ( $cpn->createDatabase( $conn, $db_name ) )
            {
               $cpn->selectDatabase( $conn, $db_name );
               $cpn->getCategories( $project, $merchant, $conn );
               $cpn->getBrands( $project, $conn, $status = 0 );
               $cpn->getProducts( $project, $conn, $db_name, $status = 0 );
            }
         }
      }//foreach merchant data
   }//function
}//class
