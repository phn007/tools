<?php
class ApiProductComponent
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
      if ( file_exists( $this->path ) )
      {
         return unserialize( file_get_contents( $this->path ) );
      }
      else
      {
         trigger_error( 'My Debug:'. $path . ' Not existing' , $error_type = E_USER_ERROR );
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
         trigger_error( 'My Debug: Product Not Found' , $error_type = E_USER_ERROR );
      }
   }
}
