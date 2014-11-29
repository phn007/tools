<?php
class TestController extends webtools\Controller
{
   function spinDesc()
   {
      //ถ้า user ไม่ได้ส่งชื่อโฟล์เดอร์ของไฟล์ config เข้ามาให้ไปใช้โฟลเดอร์ default แทน
      $conf_dir = isset( $params ['conf_dir'] ) ? $params ['conf_dir'] : 'default';

      $batch = $this->model( 'createsite' );
      $data = $batch->getMerchantData( $conf_dir );
      $merchant_data = $data['merchant_data'];

      //single site config
      $conf_single = $data['conf_single'];

      $model = $this->model( 'test' );
      $model->getSiteDescription( $conf_single['site_category'] );
   }
}
