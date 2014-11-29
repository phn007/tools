<?php
class GetPermalink
{
   function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   function __get( $name )
   {
      return $this->{$name};
   }



   function textsite()
   {
      $url = array(
       'home_url' => rtrim( $this->home_url, '/' ),
       'product_file' => $this->product_file,
       'productname' => $this->product_key . $this->format,
      );
      return implode( '/', $url );
   }

   function htmlsite()
   {
      $url = array(
         'home_url' => rtrim( $this->home_url, '/' ),
         'product_file' => $this->product_file,
         'productname' => $this->product_key . $this->format
      );
      return implode( '/', $url );
   }

   function apisite()
   {
      $url = array(
         'home_url' => rtrim( $this->home_url, '/' ),
         'category' => $this->category,
         'productname' => $this->product_key . $this->format
      );
      return implode( '/', $url );
   }
}

class Permalink
{
   public static function get( $data )
   {
      //Get Permalink Object
      $obj = new GetPermalink();

      //Define Variables
      $obj->product_file = $data['product_file'];
      $obj->product_key = $data['product_key'];
      $obj->category = $data['category'];
      $obj->home_url = $data['home_url'];
      $obj->format = $data['format'];
      $obj->prod_route = $data['prod_route'];

      //Call Function
      if ( 'textsite' == $data['site_type'] )
      {
         return $obj->textsite();
      }
      elseif ( 'htmlsite' == $data['site_type']  )
      {
         return $obj->htmlsite();
      }
      elseif ( 'apisite' == $data['site_type']  )
      {
         return $obj->apisite();
      }
   }
}
