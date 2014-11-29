<?php
class HomeController extends Controller
{
   function index()
   {
      //กำหนดตัวให้กับ Theme
      $this->layout = 'application';
      $this->view = 'index';
      $this->home_page = true;
      $this->home_menu_state = 'class="active"';

      //เริ่มต้นเรียกใช้ home_model
      $model = $this->model( 'home' );

      /*
       * PRODUCTS
       * ดึงรายการสินค้าออกมาสำหรับทำ carousel slide และ Proudct items list
       * -----------------------------------------------------------------------
      */
      $data = $model->getProducts();
      $this->slide_products = $data['slide_products'];
      $this->product_list = $data['product_list'];

      /*
       * Category Link List
       * -----------------------------------------------------------------------
      */
      $this->category_list = $model->categoryList();

      /**
       * Brand Link List
       * -----------------------------------------------------------------------
      */
      $this->brand_list = $model->brandList();

      /*
       * Head
       * -----------------------------------------------------------------------
      */
      $this->meta = $model->getMeta();


      //กำหนด html path สำหรับ htmlsite
      if ( 'htmlsite' == SITE_TYPE )
      {
         $this->html_path   = HTMLSITE_PATH . 'index.html';
      }


      //exit( 0 );
   }
}
