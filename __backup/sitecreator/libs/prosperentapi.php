<?php
class ProsperentApi
{
   private $api_key = 'cf462123815f81df78ca0f952cefe520';
   private $url = 'http://api.prosperent.com/api/search?';

   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }

   /**
    * get_api_data
    * -------------------------------------------------------------------------
   */
   function get_api_data( $url )
   {
      $curl = curl_init();

      // Set options
      curl_setopt_array( $curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => $url,
          CURLOPT_CONNECTTIMEOUT => 120,
          CURLOPT_TIMEOUT => 120
      ) );

      // Send the request
      $response = curl_exec( $curl );

      // Close request
      curl_close( $curl );

      // Convert the json response to an array
      $response = json_decode( $response, true );

      return $response;
   }

   function searchRequestParams()
   {
      $params['api_key'] = $this->api_key;

      if ( isset( $this->filterMerchant ) ) $params['filterMerchant'] = $this->filterMerchant;
      if ( isset( $this->filterCategory ) ) $params['filterCategory'] = $this->filterCategory;
      if ( isset( $this->filterBrand) ) $params['filterBrand'] = $this->filterBrand;
      if ( isset( $this->filterKeyword) ) $params['filterKeyword'] = $this->filterKeyword;
      if ( isset( $this->filterKeywords) ) $params['filterKeywords'] = $this->filterKeywords;
      if ( isset( $this->filterCatalogId) ) $params['filterCatalogId'] = $this->filterCatalogId;
      if ( isset( $this->page) ) $params['page'] = $this->page;
      if ( isset( $this->limit) ) $params['limit'] = $this->limit;
      if ( isset( $this->query) ) $params['query'] = $this->query;
      if ( isset( $this->sortBy) ) $params['sortBy'] = $this->sortBy;
      if ( isset( $this->groupBy) ) $params['groupBy'] = $this->groupBy;
      if ( isset( $this->imageSize) ) $params['imageSize'] = $this->imageSize;
      if ( isset( $this->enableFacets) ) $params['enableFacets'] = $this->enableFacets;

      return $params;
   }


   /**
    * Product
    * -------------------------------------------------------------------------
   */

   function getResponseData()
   {
      //รวบรวม parameters ที่ต้องใช้ในการ search product
      $params = $this->searchRequestParams();

      //สร้าง url query
      $params = http_build_query( $params );
      $url = $this->url . $params;

      //ดึงสินค้า
      $response = $this->get_api_data( $url );

      // Set specified data from response
      if ( isset( $response['data'] ) )
      {
         return $response['data'];
      }
      else
      {
         echo "There is no response";
         exit( 0 );
      }
   }//get products

   function getResponseDataNonEncode()
   {
      //รวบรวม parameters ที่ต้องใช้ในการ search product
      $params = $this->searchRequestParams();

      //สร้าง url query
      $str = null;
      foreach ( $params as $key => $val )
      {
         $str .= $key . '=' . $val . '&';
      }
      $str = rtrim( $str, '&' );
      $url = $this->url . $str;

      //ดึงสินค้า
      $response = $this->get_api_data( $url );

      // Set specified data from response
      if ( isset( $response['data'] ) )
      {
         return $response['data'];
      }
      else
      {
         echo "There is no response";
         exit( 0 );
      }
   }//get products
}
