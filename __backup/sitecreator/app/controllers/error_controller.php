<?php
class ErrorController extends Controller
{
   public function index()
   {
      /*
      * Layout
      * -------------------------------------------------------------------
      */
      $this->layout = 'application';
      $this->view = 'index';

      $model = $this->model( 'error' );
      $this->meta = $model->getMeta();
   }
}
