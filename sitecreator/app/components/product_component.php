<?php
class ProductComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }


   //อ่านข้อมูลสินค้าจาก product textdatabase
   function getProductFile()
   {
      $path = $this->product_path . $this->product_file . '.txt';
      if ( file_exists( $path ) )
      {
         return unserialize( file_get_contents( $path ) );
      }
      else
      {
         $url = HOME_URL . 'welcome';
         header( "location: " . $url );
         //trigger_error( 'My Debug:'. $path . ' Not existing' , $error_type = E_USER_ERROR );
      }
   }

   function getProducts( $file )
   {
      if ( array_key_exists( $this->product_key, $file ) )
      {
         return $file[ $this->product_key ];
      }
      else
      {
         $url = HOME_URL . 'welcome';
         header( "location: " . $url );

         //trigger_error( 'My Debug: Product Not Found' , $error_type = E_USER_ERROR );
      }
   }
}
