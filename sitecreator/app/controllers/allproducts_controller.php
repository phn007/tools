<?php
class AllProductsController extends Controller
{
   function index( $params )
   {
      /*
       * Parameter
       * -----------------------------------------------------------------------
      */
      $parse = explode( '-', str_replace( '.html', '', $params[0] ) );
      $file_number = $parse[0];
      $page_number = $parse[1];

      /*
       * Page Type
       * -----------------------------------------------------------------------
      */
      $this->allproducts_menu_state = 'class="active"';
      $this->allproducts_page = true;

      /*
       * Layout
       * -----------------------------------------------------------------------
      */
      $this->layout = 'layout';
      $this->view = 'index';


      /*
       * Product List/Menu
       * -----------------------------------------------------------------------
      */
      $model = $this->model( 'allproducts' );
      $model->file_number = $file_number;
      $model->page_number = $page_number;
      $data = $model->getProductList();
      $this->products = $data['products'];
      $this->menu = $data['menu'];

      /*
       * Meta Header
       * -----------------------------------------------------------------------
      */
      $permalink = $model->permalink();
      $this->meta = $model->getMeta( $permalink );
   }
}
