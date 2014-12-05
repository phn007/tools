<?php
use webtools\AppComponent;

class optionModel extends AppComponent
{
   function xip( $params, $options )
   {

      $data = false;
      $opt_keys = array_keys( $options );
      $cpn = $this->component( 'option' );

      /*
       * Parameters
       * ----------------------------------------------------------------------
      */
      if ( isset( $params['function'] ) )
      {
         if ( 'create' == $params['function'] )
         {
            $data['function'] = $cpn->createxip( $params, $opt_keys );
         }
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
      {
         $data['conf_dir'] = $options['config'];
      }
      else
      {
         echo "Option: is not available";
         exit( 0 );
      }

      //Select Directory or File
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

      //Return Result
      return $data;
   }





   function verify( $params, $options )
   {
      $data = false;

      $opt_keys = array_keys( $options );

      $cpn = $this->component( 'option' );

      /*
       * Parameters
       * ----------------------------------------------------------------------
      */
      if ( isset( $params['function'] ) )
      {
         if ( 'getproduct' == $params['function'] )
         {
            $data['function'] = $cpn->getProduct( $opt_keys );
         }
         elseif ( 'textdb' == $params['function'] )
         {
            $data['function'] = $cpn->createTextDatabase( $opt_keys );
         }
         elseif ( 'create' == $params['function'] )
         {
            $data['function'] = $cpn->createSite( $opt_keys, $params );
         }
         elseif ( 'run' == $params['function'] )
         {
            $data['function'] = $cpn->createAll( $opt_keys, $params );
         }
         elseif ( 'showconfig' == $params['function'] )
         {
            $data['function'] = $cpn->showConfig( $opt_keys );
         }
         elseif ( 'countproducts' == $params['function'] )
         {
            $data['function'] = $cpn->countProducts( $opt_keys );
         }
         elseif ( 'dropdatabase' == $params['function'] )
         {
            $data['function'] = $cpn->dropDatabase( $opt_keys );
         }
         elseif ( 'sourcecode' == $params['function'] )
         {
            $data['function'] = $cpn->sourcecode( $params );
         }
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

      //Return Result
      return $data;
   }
}
