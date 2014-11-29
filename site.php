<?php

class Option
{
   function main( $options )
   {
      if ( ! isset( $options[1][1] ) )
         $options[1][1] = null;

      $controller = 'site';
      $action = 'createsite';
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

$opt = new Option();
$options = $opt->main( $options );

include 'appindex.php';
