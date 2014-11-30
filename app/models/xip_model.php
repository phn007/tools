<?php
class createXipModel extends webtools\AppComponent
{
   function create( $conf )
   {
      echo "createXipModel";
      echo "\n";

      //Create Directory Structure
      if ( 'dir' == $this->sub_function )
      {
         echo "Create Directory Structure\n";
         
         foreach ( $conf['site_config']['server_name'] as $name )
         {
            shell_exec( WT_BASE_PATH . 'shell/xip/createdir.sh ' . $name );
         }
      }

      //Create Virtual File
      if ( 'vhost' == $this->sub_function )
      {
         echo "Create VHost file\n";
         foreach ( $conf['site_config']['server_name'] as $name )
         {
            shell_exec( WT_BASE_PATH . 'shell/xip/createVHost.sh ' . $name );
         }
      }

      //Enable the New Virtual Host File
      if ( 'enablevhost' == $this->sub_function )
      {
         echo "Enable the New Virtual Host File\n";
         foreach ( $conf['site_config']['server_name'] as $name )
         {
            shell_exec('sudo a2ensite ' . $name . '.conf' );
         }
      }
   }
}
