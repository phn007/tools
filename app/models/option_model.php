<?php
class optionModel extends webtools\AppComponent
{
   function verify( $params, $options )
   {
      $opt_keys = array_keys( $options );


      /*
       * Parameters
       * ----------------------------------------------------------------------
      */
      if ( isset( $params['function'] ) )
      {
         if ( 'getproduct' == $params['function'] )
            $data['function'] = $this->getProduct( $opt_keys );

         elseif ( 'textdb' == $params['function'] )
            $data['function'] = $this->createTextDatabase( $opt_keys );

         elseif ( 'create' == $params['function'] )
            $data['function'] = $this->createSite( $opt_keys, $params );

         elseif ( 'run' == $params['function'] )
            $data['function'] = $this->createAll( $opt_keys, $params );

         elseif ( 'showconfig' == $params['function'] )
            $data['function'] = $this->showConfig( $opt_keys );

         elseif ( 'countproducts' == $params['function'] )
            $data['function'] = $this->countProducts( $opt_keys );

         elseif ( 'dropdatabase' == $params['function'] )
            $data['function'] = $this->dropDatabase( $opt_keys );

         elseif ( 'sourcecode' == $params['function'] )
            $data['function'] = $this->sourcecode( $params );

         elseif ( 'createxip' == $params['function'] )
            $data['function'] = $this->createxip( $params, $opt_keys );

         else
         {
            echo "Function: " . $params['function'] . ' is not available';
            exit( 0 );
         }
      }
      else
      {
         echo "Require function name as paramerter1: dev -h ( help )";
         exit( 0 );
      }

      /*
       * Options
       * ----------------------------------------------------------------------
      */
      if ( in_array( 'config', $opt_keys ) )
         $data['conf_dir'] = $options['config'];

      if ( in_array( 'f', $opt_keys ) )
      {
         $data['conf_dir_type'] = 'file';
      }
      else
      {
         $data['conf_dir_type'] = 'directory';
      }

      if ( in_array( 'dev', $opt_keys ) )
      {
         $data['destination']['type'] = 'develop';
         $data['destination']['dir'] = $options['dev'];
      }
      else
      {
         $data['destination']['type'] = 'site';
      }
      return $data;
   }


   /*
    * FUNCTIONS
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

      return array( 'name' => 'createxip', 'sub' => $params['arg'] );
   }


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
