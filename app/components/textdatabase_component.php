<?php

class TextDatabaseComponent extends Database
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }


   function parseTextSiteData( $row )
   {
      //ตรวจสอบและแทนค่าว่าง
      if ( empty( $row['category'] ) ) $row['category'] = EMPTY_CATEGORY_NAME;
      if ( empty( $row['brand'] ) ) $row['brand'] = EMPTY_BRAND_NAME;

      /*
      * Create Categories slug
      * -----------------------------------------------------------------
      */
      $cat_name = webtools\Helper::getCategory( $row['merchant'], $row['category'] );
      $cat_slug = webtools\Helper::clean_string( $cat_name );

      /*
      * Create Brand slug
      * -----------------------------------------------------------------
      */
      $brand_name = $row['brand'];
      $brand_slug = webtools\Helper::clean_string( $brand_name );

      /*
      * Create Keyword slug
      * -----------------------------------------------------------------
      */
      $key_slug = webtools\Helper::clean_string( $row['keyword'] );


      /*
      * Products Data
      * -----------------------------------------------------------------
      */
      $row['category'] = $cat_name;
      $product_data = $this->product_data( $row );

      return array(
         'key_slug' => $key_slug,
         'cat_slug' => $cat_slug,
         'cat_name' => $cat_name,
         'brand_slug' => $brand_slug,
         'brand_name' => $brand_name,
         'products' => $product_data
      );
   }


   function seperator( $merchant )
   {
      //เครื่องหมายสำหรับใช้แยก category
      $path = FILES_PATH . 'seperator_catgory.txt';
      $files = file( $path );

		foreach ( $files as $file ) {
			$arr = explode( '|', $file );
			$data[ $arr[0] ] = $arr[1];
		}
      return $data[ $merchant ];
   }

   /**
    * Convert Product Data Array
    * -------------------------------------------------------------------------
    */
   private function product_data( $row )
   {
		$data = array(
			'affiliate_url' => $row['affiliate_url'],
			'image_url'     => $row['image_url'],
			'keyword'       => $row['keyword'],
			'description'   => $row['description'],
			'category'      => $row['category'],
			'price'         => $row['price'],
			'merchant'      => $row['merchant'],
			'brand'         => $row['brand']
		);
		return $data;
	}


   /**
    * Generate category for api site
    * -------------------------------------------------------------------------
   */
   //NEW DEVELOP --------------------------------------------------------------
   function createSQLString( $num_merchant )
   {
      $cols = "id,catalogId,affiliate_url,image_url,keyword,description,category,price,merchant,brand";

      if ( $num_merchant > 10000 )
      {
         $round = ceil( $num_merchant / 10000 );
         $start = 0;
         $num_limit  = 10000;

         for ( $i = 0; $i < $round; $i++ )
         {
            $limit = ( $start ) . ', ' . $num_limit;
            $start = $start + $num_limit;
            $sqls[]  = "SELECT " . $cols . " FROM products LIMIT " . $limit;
         }
      }
      else
      {
         //Normal
         $sqls[]  = "SELECT " . $cols . " FROM products";
      }

      return $sqls;
   }

   function getQueryResultV2(  $conn, $db_name, $sql )
   {
      if ( ! $this->selectDatabase( $conn, $db_name ) )
      {
         die( "Cannot select " . $db_name . ' database' );
      }

      //Query Database
      if ( ! $result = mysqli_query( $conn, $sql ) )
      {
         die( "Cannot Query " . $db_name . "Database" );
      }
      return $result;

   }

   //for api
   function getQueryResult( $conn, $db_name )
   {
      // Select Database
      if ( ! $this->selectDatabase( $conn, $db_name ) )
      {
         die( "Cannot select " . $db_name . ' database' );
      }

      //SQL Statement
      $cols = "id,catalogId,affiliate_url,image_url,keyword,description,category,price,merchant,brand";
      $sql  = "SELECT " . $cols . " FROM products";

      //Query Database
      if ( ! $result = mysqli_query( $conn, $sql ) )
      {
         die( "Cannot Query " . $db_name . "Database" );
      }
      return $result;
   }

   function totalProductsV2( $conn, $merchant_data )
   {
      $total_products = 0;

      echo "\n------------ Count Total Products --------------\n";

      foreach ( $merchant_data as $merchant => $data )
      {
         $db_name = $data['db_name'];

         //นับจำนวนสินค้า
         if ( $num_products = $this->totalProductCount( $conn, $db_name ) )
         {
            //รวมจำนวนสินค้าทั้งหมด
            $total_products = $total_products + $num_products;

            echo $num_products;
            echo ": ";
            echo $merchant;
            echo "\n";

            $mch[ $merchant ] = $num_products;
         }
      }

      echo "\nTOTAL: " . $total_products . "\n";
      return array( 'total' => $total_products, 'number' => $mch );
   }


   function totalProducts( $conn, $merchant_data )
   {
      $total_products = 0;

      echo "\n------------ Count Total Products --------------\n";

      foreach ( $merchant_data as $merchant => $data )
      {
         $db_name = $data['db_name'];

         //นับจำนวนสินค้า
         if ( $num_products = $this->totalProductCount( $conn, $db_name ) )
         {
            //รวมจำนวนสินค้าทั้งหมด
            $total_products = $total_products + $num_products;

            echo $num_products;
            echo ": ";
            echo $merchant;
            echo "\n";
         }
      }

      echo "\nTOTAL: " . $total_products . "\n";
      return $total_products;
   }


   function parseData( $row )
   {
      //ตรวจสอบและแทนค่าว่าง
      if ( empty( $row['category'] ) ) $row['category'] = EMPTY_CATEGORY_NAME;
      if ( empty( $row['brand'] ) ) $row['brand'] = EMPTY_BRAND_NAME;

      /*
      * Create Categories slug
      * -----------------------------------------------------------------
      */
      $cat_name = webtools\Helper::getCategory( $row['merchant'], $row['category'] );
      $cat_slug = webtools\Helper::clean_string( $cat_name );

      /*
      * Create Brand slug
      * -----------------------------------------------------------------
      */
      $brand_name = $row['brand'];
      $brand_slug = webtools\Helper::clean_string( $brand_name );

      /*
      * Create Keyword slug
      * -----------------------------------------------------------------
      */
      $key_slug = webtools\Helper::clean_string( $row['keyword'] );

      /*
      * Catalog ID
      * -----------------------------------------------------------------
      */
      $catalogId = $row['catalogId'];

      return array(
         'key_slug' => $key_slug,
         'cat_slug' => $cat_slug,
         'cat_name' => $cat_name,
         'brand_slug' => $brand_slug,
         'brand_name' => $brand_name,
         'catalogId' => $catalogId
      );


   }


   function createTextDatabase( $project_path, $data, $dir )
   {
      $total = 0;

      $path = $project_path . $dir .'/';
      webtools\Helper::make_dir( $path );

      foreach( $data as $key => $cat )
      {
         $count = count( $data[ $key ] );
         $total = $total + $count;

         //ถ้ามีสินค้าเกิน 1000 รายการ ให้แบ่งออกเป็น group ละ 1000
         if ( $count > 1000 )
         {
            $chunks = array_chunk( $data[ $key ], 1000, true );

            $i = 0;
            foreach ( $chunks as $chunk )
            {
               if ( $i == 0 )
               {
                  $filename = $key;
               }
               else
               {
                  $filename = $key . '_' . $i;
               }

               $file = $path . $filename . '.txt';
               $cat_data = serialize( $chunk );
               file_put_contents( $file, $cat_data );
               $i++;
            }
         }
         else
         {
            $filename = $key;
            $file = $path . $filename . '.txt';
            $cat_data = serialize( $data[ $key ] );
            file_put_contents( $file, $cat_data );
         }
      }

      echo 'Created ' . ucfirst( $dir ) . ': ' . $total . ' products. ' . $project_path;
      echo "\n";
   }


   function createBrandDatabase( $folder, $cat_dir )
   {
      $brand_data = false;

      $path = $folder . '/brands/';
      webtools\Helper::make_dir( $path );

      $files = glob( $cat_dir . '*.txt' );

      $i = 1;
      foreach ( $files as $file )
      {
         $arr = explode( '/', $file );
         $filename = str_replace( '.txt', '', end( $arr ) );
         $products = unserialize( file_get_contents( $file ) );

         foreach ( $products as $key => $prod )
         {
            $name = $prod['brand'];

            $bnd[$name]['count'] = isset( $bnd[$name]['count'] ) ? $bnd[$name]['count'] : null;
            $bnd[$name]['num']   = isset( $bnd[$name]['num'] ) ? $bnd[$name]['num']: null;

            if ( ++$bnd[$name]['count'] > 800 )
            {
               $bnd[$name]['count'] = 1;
               ++$bnd[$name]['num'];
            }

            $bnd[$name]['name'] = $name;
            $name_slug = webtools\Helper::clean_string( $bnd[$name]['name'] . $bnd[$name]['num'] );
            $file = $path . $name_slug . '.txt';

            $brand_data  = $prod['keyword'] . '|';
            $brand_data .= $bnd[$name]['name'] . $bnd[$name]['num']  . '|';
            $brand_data .= $prod['category'] . '|';
            $brand_data .= $filename . PHP_EOL;

            $b = fopen( $file, 'a' );
		      fwrite( $b, $brand_data );
		      fclose( $b );

            echo "Brand: " . $cat_dir . " -> ";
            echo $i . ' ';
            echo $bnd[$name]['name'] . $bnd[$name]['num'];
            echo "\n";

            $i++;
         }
      }
   }
}//class
