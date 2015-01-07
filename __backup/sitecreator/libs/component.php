<?php
class AppComponent
{

   public function component( $component )
   {
      $path = APP_PATH . 'components/' . $component . '_component.php';

      if ( file_exists( $path ) )
      {
         require_once $path;

         $component = str_replace( '-', '', $component );
         $component_class = $component . 'Component';
         $component_class = "$component_class";
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
