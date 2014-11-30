<?php

class OptionComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }

   /*
    * XIP.IO SECTION
    * ----------------------------------------------------------------------
   */
   function createxip( $params, $opt_keys )
   {
      if ( !in_array( 'config', $opt_keys ) )
      {
         echo "Create XIP.IO: require config option!!!";
         exit( 0 );
      }

      if ( !isset( $params['arg'] ) )
      {
         echo "Create xip: require sub function as parameter2!!!";
         exit( 0 );
      }

      return array( 'name' => 'create', 'sub' => $params['arg'] );
   }



   /*
    * SITE SECTION
    * ----------------------------------------------------------------------
   */
   function sourcecode( $params )
   {
      if ( ! isset( $params['arg'] ) )
      {
         echo "Sourcecode: require parameter 2 as destination";
         exit( 0 );
      }
      return array( 'name' => 'sourcecode', 'destination' => $params['arg'] );
   }


   function showConfig( $opt_keys )
   {
      if ( !in_array( 'config', $opt_keys ) )
      {
         echo "Show Config: require config option!!!";
         exit( 0 );
      }
      return array( 'name' => 'showconfig' );
   }


   function countProducts( $opt_keys )
   {
      if ( !in_array( 'config', $opt_keys ) )
      {
         echo "Count Products: require config option!!!";
         exit( 0 );
      }
      return array( 'name' => 'countproducts' );
   }


   function dropDatabase( $opt_keys )
   {
      if ( !in_array( 'config', $opt_keys ) )
      {
         echo "Drop Database: require config option!!!";
         exit( 0 );
      }
      return array( 'name' => 'dropdatabase' );
   }



   function createSite( $opt_keys, $params )
   {
      if ( !in_array( 'config', $opt_keys ) )
      {
         echo "Create Site: require config option!!!";
         exit( 0 );
      }

      if ( !isset( $params['arg'] ) )
      {
         echo "Create site: require sub function as parameter2!!!";
         exit( 0 );
      }

      return array( 'name' => 'createsite', 'sub' => $params['arg'] );
   }


   function createTextDatabase( $opt_keys )
   {
      if ( !in_array( 'config', $opt_keys ) )
      {
         echo "Create TextDatabase: require config option!!!";
         exit( 0 );
      }
      return array( 'name' => 'textdatabase' );
   }


   function getProduct( $opt_keys )
   {
      if ( !in_array( 'config', $opt_keys ) )
      {
         echo "Get Product: require config option!!!";
         exit( 0 );
      }
      return array( 'name' => 'getproduct' );
   }

   function createAll( $opt_keys, $params )
   {
      if ( !in_array( 'config', $opt_keys ) )
      {
         echo "Create Site: require config option!!!";
         exit( 0 );
      }
      return array( 'name' => 'all', 'sub' => 'all');
   }
}
