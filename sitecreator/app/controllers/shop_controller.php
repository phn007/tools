<?php
class ShopController extends Controller
{
   public function index( $params )
   {
      $product_file = $params[0];
      $product_key  = $params[1];

      $model = $this->model( 'shop' );
      $url = $model->getUrl( $product_file, $product_key );

      //Redirect to Merchant
      header( "location: " . $url );
   }

}
