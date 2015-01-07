<?php
class ShopModel extends AppComponent
{

   function getUrl( $product_file, $product_key )
   {
      $cpn = $this->component( 'product' );
      $cpn->product_path = 'contents/categories/';

      $cpn->product_file = $product_file;
      $cpn->product_key  = $product_key ;

      $file = $cpn->getProductFile();
      $data = $cpn->getProducts( $file );

      //Permalink
      $cat_slug = Helper::clean_string( $data['category'] );
      $permalink = Helper::get_permalink( $product_file, $product_key, $cat_slug );

      if ( NETWORK == 'prosperent-api' )
      {
         $url = $this->prosperentAPI( $data['affiliate_url'], $permalink );
      }

      elseif ( NETWORK == 'prosperent-deeplink')
      {
         $url = $this->prosperent_deeplink( $data['affiliate_url'] );
      }
      elseif ( NETWORK == 'viglink' )
      {
         $url = $this->viglink( $data['affiliate_url'] );
      }

      return $url;
   }



   function prosperentAPI( $affiliate_url, $permalink )
   {
      $arr = explode( '/', $affiliate_url );
      $arr[5] = API_KEY;
      $url = implode( '/', $arr );

      //$referer = '&referrer=' . urlencode( $_SERVER['HTTP_REFERER'] );
      $referer = '&referrer=' . urlencode( $permalink );
      $sid     = '&sid=' . urlencode( SID );

      //GO TO MERCHANT
      $url = $url . $referer . $sid;
      return $url;
   }



   function prosperent_deeplink( $affiliate_url )
   {
      die( 'prosperent_deeplink function' );
   }



   function viglink( $affiliate_url )
   {
      $url = 'http://redirect.viglink.com?u=';
      $u   = urlencode( $affiliate_url );
      $key = '&key=' . API_KEY;
      $url = $url . $u . $key;

      return $url;
   }
}
