<?php
class widget
{
   public function __construct( $widget )
   {
      $this->loadWidget( $widget );

   }

   public function loadWidget( $widget )
   {
      $path = APP_PATH . 'widgets/' . $widget . '_widget.php';

      if ( file_exists( $path ) )
      {
         require_once $path;
      }
      else
      {
         die( '<span style="color:red">' . $widget_class . ' not found</span>' );
      }
   }
}
