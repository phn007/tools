<?php
use webtools\libs\Helper;

class ProspapiComponent extends Database
{

   // main ( new );
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
    * This always returns a myclass
    * @param string $path config file directory path
    * @return mixed
   */
   function configList( $path )
   {
      $files = glob( $path );

      if ( !empty( $files ) )
      {
         foreach ( $files as $file )
         {
            $arr = explode( '/', $file );
            $conf[] = trim( end( $arr ) );
         }
         return $conf;
      }
      else
      {
         echo "Config file not found!";
         exit( 0 );
      }
   }


   /**
    * แยกชื่อ merchant กับจำนวนสินค้าที่จะทำการแบ่งออกมาเก็บไว้ในตัวแปร
    * @param string $string
    * @return mixed
    * -------------------------------------------------------------------------
   */
   function splitMerchantName( $string )
   {
      $arr = explode( '#', $string );
      $merchant  = !empty( $arr[0] ) ? $arr[0] : NULL ;
      $num_split = !empty( $arr[1] ) ? $arr[1] : NULL ;
      return array(
         'merchant' => $merchant,
         'num_split' => $num_split,
      );
   }

   function convertToDbName( $merchant )
   {
      //แปลงชื่อ merchant ให้เป็นชื่อ database
      $db_name = Helper::clean_string( $merchant );
      return 'prosp_' . str_replace( '-', '_', $db_name );
   }



   /**
    * Product
    * -------------------------------------------------------------------------
   */
   function getProducts( $project, $conn, $db_name, $status )
   {
      $sql = "SELECT * FROM brand WHERE status ='" . $status . "'";
      $result = mysqli_query( $conn, $sql );

      //ให้นับลูป foreach
      $i = 1;

      while( $row = mysqli_fetch_array( $result ) )
      {
         $id = $row['id'];
         $merchant = $row['merchant'];
         $category = $row['category'];
         $brand    = $row['brand'];

         $params = array(
         	'api_key' => $this->api_key,
         	'filterMerchant' => $merchant,
         	'filterCategory' => $category,
         	'filterBrand' => $brand,
         	'page' => 1,
         	'limit' => 1000
         );

         $params = http_build_query( $params );
         $url = $this->url . $params;
         $response = $this->get_api_data( $url );

         // Set specified data from response
         if ( isset( $response['data'] ) )
         {
            $data =  $response['data'];
            $this->create_product_table( $conn );

            if ( $data )
            {
               // Loop through the data array
            	foreach ( $data as $key => $value )
               {
            		$this->insert_product_table( $conn, $value );

                  echo $project . ': Product: ' . $id . '-' . $i . ' ' . $merchant . ' => ' . $category . ' => ' . $brand;
                  echo ' => ' . $value['keyword'];
                  echo "\n";
                  $i++;
            	}
               $this->update_status( $conn, $id, 'brand', 1 );
            }
         }
         else
         {
            $this->update_status( $conn, $id, 'brand', 2 );
            echo "Product: " . $id . ". There is no data : " . $merchant . ' => ' . $category . ' => ' . $brand;
            echo "\n";
         }
      }
   }




   /**
    * Brand
    * -------------------------------------------------------------------------
   */
   function getBrands( $project, $conn, $status )
   {
      //Select ข้อมูลจากตาราง category ที่ยังไม่ได้ใช้งานขึ้นมา ( status 0 )
      $sql = "SELECT * FROM category WHERE status ='" . $status . "'";
      $result = mysqli_query( $conn, $sql );

      //ให้รับลูปของ foreach
      $i = 1;

      //วนลูปผ่าน Category result
      while( $row = mysqli_fetch_array( $result ) )
      {
         $id = $row['id'];
         $merchant = $row['merchant'];
         $category = $row['category'];

         $params = array(
         	'api_key' 			=> $this->api_key,
         	'enableFacets' 	=> "brand",
         	'filterMerchant' 	=> $merchant,
         	'filterCategory' 	=> $category,
         	'page' 				=> 1,
         	'limit' 			   => 1
         );

         //สร้าง url parameter
         $params = http_build_query( $params );
         $url = $this->url . $params;

         //ดึงข้อมูล
         $response  = $this->get_api_data( $url );

         //ถ้ามีผลลัพธ์ return กลับมา
         if ( isset( $response['facets']['brand'] ) )
         {
            $data =  $response['facets']['brand'];

            //สร้างตาราง brand
            $this->create_brand_table( $conn );

            foreach ( $data as $brand )
            {
               echo $project . ': Brand: ' . $id . '-' . $i . ' ' . $merchant . ' => ' . $category . ' => ' . $brand['value'];
               echo "\n";

               $brand_data = array( 'merchant' => $merchant, 'category' => $category, 'brand' => $brand['value'] );

               //Insert ข้อมูลลงไปในตาราง brand
               $this->insert_brand_table( $conn, $brand_data );

               $i++;
            }
         }
      }

      echo "\n";
      echo $merchant . ' ( ' . ( $i - 1 ) . ' ) brands';
      echo "\n\n";
   }



   /**
    * เก็บ category สินค้าตาม merchant
    * @param
    * @return
   */
   function getCategories( $project, $merchant, $conn )
   {
      //ให้ค่ากับตัวแปร params ของ curl เพื่อใช้ดึง category ทั้งหมดจาก merchant ที่กำหนด
      $params = array(
         'api_key' => $this->api_key,
         'enableFacets' => "category",
         'filterMerchant' => $merchant,
         'page' => 1,
         'limit' => 1
      );


      //สร้าง url สำหรับดึง ข้อมูลสินค้า
      $params = http_build_query( $params );
      $url = $this->url . $params;


      //ดึงข้อมูลสินค้า
      $response = $this->get_api_data( $url );

      //ถ้ามีผลลัพธ์มีข้อมูลสินค้าส่งกลับมา
      if ( isset( $response['facets']['category'] ) )
      {
         $data =  $response['facets']['category'];

         //สร้างตาราง Category
         $this->create_category_table( $conn );

         //วนลูปผ่านข้อมูลสินค้า
         $i = 1;
         foreach ( $data as $cat )
         {
            //Display
            echo $project . ': Category: ' . $i . '. ' . $merchant . ' => ' . $cat['value'];
            echo "\n";

            //กำหนด data array เพื่อส่งไป insert ข้อมูลใส่ตาราง category
            $cat_data = array(
               'merchant' => $merchant,
               'category' => $cat['value'],
               'status' => 0
            );

            //Insert data to category table
            $this->insert_category_table( $conn, $cat_data );
            $i++;

         }
      }
      else
      {
         //die( "CURL : Empty Categories" );
         //log
      }
      echo "\n";
      echo $merchant . ' ( ' . ( $i - 1 ) . ' ) categories';
      echo "\n\n";
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









   /**
    *
    * PRODUCT DATABASE SECTION
    * --------------------------------------------------------------------------
   */


   function create_category_table( $conn )
   {
      $sql = "CREATE TABLE IF NOT EXISTS `category`
      (
         `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
         `merchant` varchar(150) NOT NULL DEFAULT '',
         `category` varchar(255) NOT NULL DEFAULT '',
         `status` char(1) NOT NULL default '0',
         PRIMARY KEY (`id`)
      )";

      // Execute query
      if ( mysqli_query( $conn, $sql ) )
      {
        //echo "Table category created successfully";
      } else {
        echo "Error creating category table: " . mysqli_error ( $conn );
      }
      echo "\n";

    }


   function insert_category_table( $conn, $data )
   {
      $sql = "INSERT INTO category (
         merchant,
         category,
         status
         ) VALUES (

            '" . mysqli_real_escape_string( $conn, $data['merchant'] ). "',
            '" . mysqli_real_escape_string( $conn, $data['category'] ) . "',
            '" . $data['status'] . "'
         )";

      if ( ! mysqli_query( $conn, $sql ) )
      {
         echo 'Error Inserting into category table: ' . mysqli_error( $conn ) . "\n";
         echo "\n";
      }
   }



   function create_brand_table( $conn )
   {
      $sql = "CREATE TABLE IF NOT EXISTS brand (
         `id` int(11) unsigned NOT NULL auto_increment,
         `merchant` varchar(150) NOT NULL default '',
         `category` varchar(255) NOT NULL default '',
         `brand` varchar(150) NOT NULL default '',
         `status` char(1) NOT NULL default '0',
         PRIMARY KEY  (`id`)
      )";

      if ( ! mysqli_query( $conn, $sql ) ) {
         echo "Error creating brand table: " . mysqli_error ( $conn );
      }
      else
      {
         //echo "Table category created successfully";
      }
      echo "\n";
   }


   function insert_brand_table( $conn, $data )
   {
      $sql = "INSERT INTO brand (
         merchant,
         category,
         brand
         ) VALUES (
            '" . mysqli_real_escape_string( $conn, $data['merchant'] ) . "',
            '" . mysqli_real_escape_string( $conn, $data['category'] ) . "',
            '" . mysqli_real_escape_string( $conn, $data['brand'] ) . "'
         )";


      if ( ! mysqli_query( $conn, $sql ) ) {
         echo 'Error Inserting into brand table: ' . mysqli_error( $conn );
         echo "\n";
      }
   }


   function create_product_table( $conn )
    {

      $sql = "CREATE TABLE IF NOT EXISTS products (
         `id` int(11) unsigned NOT NULL auto_increment,
         `catalogId` varchar(50) NOT NULL default '',
         `productId` varchar(50) NOT NULL default '',
         `affiliate_url` varchar(255) NOT NULL default '',
         `image_url` varchar(255) NOT NULL default '',
         `keyword` varchar(255) NOT NULL default '',
         `description` text NOT NULL default '',
         `category` varchar(255) NOT NULL default '',
         `price` varchar(10) NOT NULL default '',
         `price_sale` varchar(10) NOT NULL default '',
         `currency` varchar(5) NOT NULL default '',
         `merchant` varchar(150) NOT NULL default '',
         `brand` varchar(150) NOT NULL default '',
         `upc` varchar(20) NOT NULL default '',
         `isbn` varchar(20) NOT NULL default '',
         `sales` varchar(20) NOT NULL default '',
         `status` char(1) NOT NULL default '0',
         PRIMARY KEY  (`id`)
      )";

      if ( ! mysqli_query( $conn, $sql ) ) {
         echo 'Error creating table: ' . mysqli_error( $conn ) . "\n";
      }
   }



   function insert_product_table( $conn, $data )
   {
      $sql = "INSERT INTO products (
            catalogId,
            productId,
            affiliate_url,
            image_url,
            keyword,
            description,
            category,
            price,
            price_sale,
            currency,
            merchant,
            brand,
            upc,
            isbn,
            sales
         ) VALUES (
            '" . $data['catalogId'] . "',
            '" . $data['productId'] . "',
            '" . mysqli_real_escape_string( $conn, $data['affiliate_url'] ) . "',
            '" . mysqli_real_escape_string( $conn, $data['image_url'] ) . "',
            '" . mysqli_real_escape_string( $conn, $data['keyword'] ) . "',
            '" . mysqli_real_escape_string( $conn, $data['description'] ) . "',
            '" . mysqli_real_escape_string( $conn, $data['category'] ) . "',
            '" . $data['price'] . "',
            '" . $data['price_sale'] . "',
            '" . $data['currency'] . "',
            '" . mysqli_real_escape_string( $conn, $data['merchant'] ) . "',
            '" . mysqli_real_escape_string( $conn, $data['brand'] ) . "',
            '" . $data['upc'] . "',
            '" . $data['isbn'] . "',
            '" . $data['sales'] . "'
         )";

      if ( ! mysqli_query( $conn, $sql ) )
      {
         echo 'Error Inserting Data: ' . mysqli_error( $conn ) . "\n";
         die();
      }
   }

   function update_status( $conn, $id, $table, $status )
   {
      $sql = "UPDATE " . $table . " SET status='" . $status . "' WHERE id='". $id ."'";
      $result = mysqli_query( $conn, $sql ) or die ('Unable to update check row.' .mysql_error( $conn ));
   }

}
