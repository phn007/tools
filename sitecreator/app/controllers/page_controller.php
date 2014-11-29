<?php
class pageController extends Controller
{
   function render()
   {
      $this->layout = 'application';
      $this->view = 'page';
   }

   function about()
   {
      $model = $this->model( 'page' );
      $this->content = $model->getContent( 'about-us' );
      $this->meta = $model->getMeta( 'About Us', HOME_URL . 'about.html/' );

      //Path สำหรับใช้สร้างโฟลเดอร์ไว้เก็บ html files
      if ( 'htmlsite' == SITE_TYPE )
      {
         $this->html_path   = HTMLSITE_PATH . 'about.html';
      }

      $this->render();
   }

   function contact()
   {
      $model = $this->model( 'page' );
      $this->content = $model->getContent( 'contact-us' );
      $this->meta = $model->getMeta( 'Contact Us', HOME_URL . 'contact.html/' );

      if ( 'htmlsite' == SITE_TYPE )
      {
         $this->html_path   = HTMLSITE_PATH . 'contact.html';
      }

      $this->render();
   }

   function privacy_policy()
   {
      $model = $this->model( 'page' );

      $this->content = $model->getContent( 'privacy-policy' );
      $this->meta = $model->getMeta( 'Privacy Policy', HOME_URL . 'privacy-policy.html/' );

      if ( 'htmlsite' == SITE_TYPE )
      {
         $this->html_path   = HTMLSITE_PATH . 'privacy-policy.html';
      }
         
      $this->render();
   }
}
