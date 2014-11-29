<?php
ini_set('memory_limit', '1024M');

class TextDatabaseModel extends webtools\AppComponent
{

   function createBrand( $project )
   {
      $main_dir = TEXTDB_PATH . $project . '/*';
      $folders  = glob( $main_dir, GLOB_ONLYDIR );

      $cpn = $this->component( 'textdatabase' );

      //Loop ผ่าน Sub Project
      foreach ( $folders as $folder )
      {
         $cat_dir = $folder . '/categories/';
         if ( file_exists( $cat_dir ) )
         {
            $cpn->createBrandDatabase( $folder, $cat_dir );
         }
      }
   }

   function createTextDatabase( $project, $site_number, $merchant_data )
   {
      $cpn = $this->component( 'textdatabase' );
      $conn = $cpn->connectDatabase();

      /*
       * จำนวน Textdabase ที่ต้องการสร้าง
       * ----------------------------------------------------------------------
      */
      //$num_textdb = $site_number;
      $num_db = $site_number;

      $tp = $cpn->totalProductsV2( $conn, $merchant_data );
      $total_products = $tp['total'];
      $mch = $tp['number'];


      //คำนวณหาจำนวนสินค้า/Project
      $product_per_project = ceil( $total_products / $num_db );

      //ตัวนับจำนวนสินค้า/Project
      $count_product_per_project = 0;

      //หมายเลขสำหรับตั้งชื่อ Project
      $num_project = 0;

      //Display on screen
      echo "Product Number/Prodct: " .$product_per_project  . "\n\n";

      foreach ( $merchant_data as $merchant => $data )
      {
         $db_name = $data['db_name'];
         $num_merchant = $mch[ $merchant ];
         $sqls = $cpn->createSQLString( $num_merchant );


         //Query MySQL
         foreach ( $sqls as $sql )
         {
            $result = $cpn->getQueryResultV2(  $conn, $db_name, $sql );

            echo 'Get data from: ' . $merchant;
            echo "\n";

            //วนลูปผ่าน row ของ database เพื่อดึงข้อมูลสินค้าออกมา
            while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) )
            {
               $count_product_per_project ++;

               //รวบรวม Data ที่ต้องใช้
               $c = $cpn->parseTextSiteData( $row );
               $key_slug   = $c['key_slug'];
               $cat_slug   = $c['cat_slug'];
               $cat_name   = $c['cat_name'];
               $brand_slug = $c['brand_slug'];
               $brand_name = $c['brand_name'];
               $products  = $c['products'];

               //Display on screen
               echo 'Parsing: ';
               echo 'Project -> ' . $project . ( $num_project + 1 ) . ': ';
               echo $product_per_project . '/' . $count_product_per_project . ': ';
               echo  $merchant . ' -> ' . $cat_name;
               echo "\n";

               //Categories Data
               $cat_data[ $cat_slug ][ $key_slug ] = $products;

               //แบ่ง Project Group
               if ( $product_per_project == $count_product_per_project )
               {
                  $num_project++;

                  //โฟลเดอร์หลักของ Project สำหรับเก็บ Textdatabase ที่สร้างขึ้นมา
                  $project_path = TEXTDB_PATH . $project . '/' . $project . $num_project . '/';

                  //Save Category Data
                  $cpn->createTextDatabase( $project_path, $cat_data, 'categories' );

                  $count_product_per_project = 0;
                  $cat_data = null;

               }
            }//while
         }//foreach query mysql
      }//foreach merchant

      //Save Last Project
      $num_project++;

      //โฟลเดอร์หลักของ Project สำหรับเก็บ Textdatabase ที่สร้างขึ้นมา
      $project_path = TEXTDB_PATH . $project . '/' . $project . $num_project . '/';

      if ( count( $cat_data ) > 0 )
      {
         //Save Category Data
         $cpn->createTextDatabase( $project_path, $cat_data, 'categories' );
      }

   }

   /**
    * Create Text Database
    * --------------------------------------------------------------------------
   */
   function createTextDatabase__( $project, $site_number, $merchant_data )
   {
      $cpn = $this->component( 'textdatabase' );
      $conn = $cpn->connectDatabase();

      /*
       * จำนวน Textdabase ที่ต้องการสร้าง
       * ----------------------------------------------------------------------
      */
      //$num_textdb = $site_number;
      $num_db = $site_number;

      $total_products = $cpn->totalProducts( $conn, $merchant_data );

      //คำนวณหาจำนวนสินค้า/Project
      $product_per_project = ceil( $total_products / $num_db );

      //ตัวนับจำนวนสินค้า/Project
      $count_product_per_project = 0;

      //หมายเลขสำหรับตั้งชื่อ Project
      $num_project = 0;

      //Display on screen
      echo "Product Number/Prodct: " .$product_per_project  . "\n\n";


      foreach ( $merchant_data as $merchant => $data )
      {
         $db_name = $data['db_name'];
         $result = $cpn->getQueryResult( $conn, $db_name );

         echo 'Get data from: ' . $merchant;
         echo "\n";

         //วนลูปผ่าน row ของ database เพื่อดึงข้อมูลสินค้าออกมา
         while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) )
         {
            $count_product_per_project ++;

            //รวบรวม Data ที่ต้องใช้
            $c = $cpn->parseTextSiteData( $row );
            $key_slug   = $c['key_slug'];
            $cat_slug   = $c['cat_slug'];
            $cat_name   = $c['cat_name'];
            $brand_slug = $c['brand_slug'];
            $brand_name = $c['brand_name'];
            $products  = $c['products'];

            //Display on screen
            echo 'Parsing: ';
            echo 'Project -> ' . $project . ( $num_project + 1 ) . ': ';
            echo $product_per_project . '/' . $count_product_per_project . ': ';
            echo  $merchant . ' -> ' . $cat_name;
            echo "\n";

            //Categories Data
            $cat_data[ $cat_slug ][ $key_slug ] = $products;

            //Brand Data
            $brand_data[ $brand_slug ][ $key_slug ] = array( 'brand' => $brand_name, 'category' => $cat_name );

            //แบ่ง Project Group
            if ( $product_per_project == $count_product_per_project )
            {
               $num_project++;

               //โฟลเดอร์หลักของ Project สำหรับเก็บ Textdatabase ที่สร้างขึ้นมา
               $project_path = TEXTDB_PATH . $project . '/' . $project . $num_project . '/';

               //Save Category Data
               $cpn->createTextDatabase( $project_path, $cat_data, 'categories' );

               //Save Brand Data
               $cpn->createTextDatabase( $project_path, $brand_data, 'brands' );
               echo "\n";

               $count_product_per_project = 0;
               $cat_data = null;
               $brand_data = null;
            }
         }
      }

      //Save Last Project
      $num_project++;

      //โฟลเดอร์หลักของ Project สำหรับเก็บ Textdatabase ที่สร้างขึ้นมา
      $project_path = TEXTDB_PATH . $project . '/' . $project . $num_project . '/';

      if ( count( $cat_data ) > 0 )
      {
         //Save Category Data
         //$cpn->createCategoryTextDatabase( $project_path, $cat_data );
         $cpn->createTextDatabase( $project_path, $cat_data, 'categories' );
      }

      if ( count( $brand_data ) > 0 )
      {
         //Save Brand Data
         //$cpn->createBrandTextDatabase( $project_path, $brand_data );
         $cpn->createTextDatabase( $project_path, $brand_data, 'brands' );
      }
   }




   /**
    * Create Text Database for API Site
    * --------------------------------------------------------------------------
   */
   function createTextDBForApiSite( $project, $site_number, $merchant_data )
   {
      $cpn = $this->component( 'textdatabase' );
      $conn = $cpn->connectDatabase();

      /*
       * จำนวน Textdabase ที่ต้องการสร้าง
       * ----------------------------------------------------------------------
      */
      //$num_textdb = $site_number;
      $num_db = $site_number;

      $total_products = $cpn->totalProducts( $conn, $merchant_data );

      //คำนวณหาจำนวนสินค้า/Project
      $product_per_project = ceil( $total_products / $num_db );

      //ตัวนับจำนวนสินค้า/Project
      $count_product_per_project = 0;

      //หมายเลขสำหรับตั้งชื่อ Project
      $num_project = 0;

      //Display on screen
      echo "Product Number/Prodct: " .$product_per_project  . "\n\n";

      foreach ( $merchant_data as $merchant => $data )
      {
         $db_name = $data['db_name'];
         $result = $cpn->getQueryResult( $conn, $db_name );

         echo 'Get data from: ' . $merchant;
         echo "\n";

         //วนลูปผ่าน row ของ database เพื่อดึงข้อมูลสินค้าออกมา
         while ( $row = mysqli_fetch_array( $result, MYSQLI_ASSOC ) )
         {
            $count_product_per_project ++;

            //รวบรวม Data ที่ต้องใช้
            $c = $cpn->parseData( $row );
            $key_slug   = $c['key_slug'];
            $cat_slug   = $c['cat_slug'];
            $cat_name   = $c['cat_name'];
            $brand_slug = $c['brand_slug'];
            $brand_name = $c['brand_name'];
            $catalogId  = $c['catalogId'];

            //Display on screen
            echo 'Parsing: ';
            echo 'Project -> ' . $project . ( $num_project + 1 ) . ': ';
            echo $product_per_project . '/' . $count_product_per_project . ': ';
            echo  $merchant . ' -> ' . $cat_name;
            echo "\n";

            //Categories Data
            $cat_data[ $cat_slug ][ $key_slug ] = array( 'catalogId' => $catalogId, 'category' => $cat_name );

            //Brand Data
            $brand_data[ $brand_slug ][ $key_slug ] = array( 'catalogId' => $catalogId, 'category' => $brand_name );

            //แบ่ง Project Group
            if ( $product_per_project == $count_product_per_project )
            {
               $num_project++;

               //โฟลเดอร์หลักของ Project สำหรับเก็บ Textdatabase ที่สร้างขึ้นมา
               $project_path = TEXTDB_PATH . $project . '/' . $project . $num_project . '/';

               //Save Category Data
               $cpn->createTextDatabase( $project_path, $cat_data, 'categories' );

               //Save Brand Data
               $cpn->createTextDatabase( $project_path, $brand_data, 'brands' );
               echo "\n";

               $count_product_per_project = 0;
               $cat_data = null;
               $brand_data = null;
            }
         }
      }

      //Save Last Project
      $num_project++;

      //โฟลเดอร์หลักของ Project สำหรับเก็บ Textdatabase ที่สร้างขึ้นมา
      $project_path = TEXTDB_PATH . $project . '/' . $project . $num_project . '/';

      if ( count( $cat_data ) > 0 )
      {
         //Save Category Data
         $cpn->createTextDatabase( $project_path, $cat_data, 'categories' );
      }

      if ( count( $brand_data ) > 0 )
      {
         //Save Brand Data
         $cpn->createTextDatabase( $project_path, $brand_data, 'brands' );
      }
   }


   function dropDatabase( $merchant_data )
   {
      echo "DropDatabase Model\n";
      echo "------------------\n";

      $cpn = $this->component( 'textdatabase' );
      $conn = $cpn->connectDatabase();

      foreach ( $merchant_data as $data )
      {
         $db_name = $data['db_name'];
         $cpn->deleteDatabase( $conn, $db_name );
      }
   }
}//class
