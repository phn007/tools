<?php
class Options
{
   function get( $controller, $action, $options )
   {
      if ( ! isset( $options[1][1] ) )
         $options[1][1] = null;

      $params = array(
         'function' => $options[1][0],
         'arg' => $options[1][1],
      );

      return array(
         'controller' => $controller,
         'action' => $action,
         'param' => $params,
         'option' => $options[0]
      );
   }
}
