<?php

class xipController extends webtools\Controller
{
   function create( $params, $options )
   {
      /*
       * Parameters and Options
       * ----------------------------------------------------------------------
      */
      //จัดการกับ parameter และ options
      $opt_model = $this->model( 'option' );
      $opt_data = $opt_model->xip( $params, $options );


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
       * วนลูปผ่าน config data ของแต่ละ .ini file
       * ----------------------------------------------------------------------
      */
      foreach ( $conf_data as $conf )
      {
         /*
          * Setup site structure for xip.io
          * -------------------------------------------------------------------
         */
         if ( 'create' == $function['name'] )
         {
            $xpi = $this->model( 'xip' );
            $xpi->sub_function = $function['sub'];
            $xpi->create( $conf );
            exit( 0 );
         }
      }
   }
}
