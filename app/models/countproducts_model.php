<?php

class CountProductsModel extends webtools\AppComponent
{


   function countProducts( $project, $merchant_data )
   {
      $cpn = $this->component( 'textdatabase' );


      //ชื่อโปรเจ็ค สำหรับเอาไปแสดงที่หน้าจอขณะ runtime
      echo $project = ucfirst( $project );
      echo "\n";

      //Connect Database
      $conn = $cpn->connectDatabase();
      $cpn->totalProducts( $conn, $merchant_data );

   }//function
}//class
