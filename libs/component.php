<?php
namespace webtools;
class AppComponent
{

   public function component( $component )
   {
      $path = WT_APP_PATH . 'components/' . $component . '_component.php';

      if ( file_exists( $path ) )
      {
         require_once $path;
         $component_class = $component . 'Component';
         return new $component_class();
      }
      else
      {
         echo "\n";
         echo 'The ' . $component. ' component file not found!';
         echo "\n";
         echo "\n";
         die();
      }
   }
}
