<?php

class Map extends Object
{
	public static function dispatch( $controller, $action, $params )
	{
		//include the matching controller
		self::load_controller( $controller );

		//Run Class/Action
		$class_name = ucfirst( $controller ) . 'Controller';

		if ( class_exists( $class_name ) )
		{
			$tmp_class = new $class_name();

			//run the matching action
			if ( is_callable( array( $tmp_class, $action ) ) )
			{
				$tmp_class->$action( $params );
			}
			else
			{
				$msg = "is_callable return false : dispatch function";
				trigger_error( $msg, $error_type = E_USER_ERROR );
			}
		}
		else
		{
			$msg = 'My Debug: ' . $class_name . ' Not Found!!!';
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}

		/*
		 * View
		 * ----------------------------------------------------------------------------
	    */

		self::render( $controller, $action );
	}

	/*
	 * Helper function
	 * -----------------------------------------------------------------------
	*/

	public static function render( $controller, $action )
	{
		$view_path   = self::view_path( $controller, $action );
		$layout_path = self::get_layout( $controller, $action );
		$main_path   = APP_PATH  . 'views/' . THEME_NAME . '/layouts/main.php';
		$file_main   = BASE_PATH . 'tmp/main_' . time() . '.php';

		/*
		 * get view content
		 * ----------------------------------------------------------------------
		*/
		$view = self::get_content( $view_path  );
		if ( $view == null )
		{
			$msg = 'My Debug: View file not found';
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}

		/*
		 * get layout content
		 * -----------------------------------------------------------------------
		*/
		$layout = self::get_content( $layout_path );
		if ( $layout == null )
		{
			$msg = 'My Debug: Layout file not found';
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}

		/*
		 * combine - view + layout
		 * -----------------------------------------------------------------------
		*/
		$view_layout = str_replace( '[%CONTENT%]', $view, $layout );
		unset( $view, $layout );

		/*
		 * get main content
		 * -----------------------------------------------------------------------
		*/
		$main = file_get_contents( $main_path );
		if ( $main == null )
		{
			$msg = 'My Debug: Main file not found';
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}

		/*
		 * combine - view + layout + main
		 * -----------------------------------------------------------------------
		*/
		$view_layout_main = str_replace( '[%LAYOUT_CONTENT%]', $view_layout, $main );

		/*
		 * write view layout main to text file
		 * -----------------------------------------------------------------------
		*/
		self::write_file( $file_main, $view_layout_main );
		unset( $view_layout_main );

		/*
		 * แสดงผลลัพธ์หน้าเว็บเพจ - ทำการ cache แล้ว save เก็บไว้เป็น html ไฟล์
		 * -----------------------------------------------------------------------
		*/

		//Extract ตัวแปรที่ส่งมาจาก Controller ต่างๆผ่าน Object Class
		extract( self::$user_vars );

		//เริ่มเก็บ cache
		ob_start();

		//เรียก Template ไฟล์ขึ้นมา
		include $file_main;

		//เก็บเนื้อหาเว็บเพจเอาไว้ในตัวแปร
		$cache_file  = ob_get_contents();

		//Save ไว้เป็น html ไฟล์
		file_put_contents( $html_path , $cache_file );

		//ล้างข้อมูล cache
		ob_end_clean();


		//ลบ temporary file main
		unlink( $file_main );

		self::$user_vars = NULL;
		$view = NULL;
		$layout= NULL;
		$main = NULL;
		$cache_file = NULL;
		unset( $cache_file );
		unset( $view );
		unset( $layout );
		unset( $main );



		//Product Controller Variable
		$linkout1 = null;
		$title2 = null;
		$price_review = null;
		$shipping_review = null;

		$goto = null;
		$affiliate_url = null;
		$permalink = null;

		$title1 = null;
		$ad1 = null;
		$ad2 = null;
		$more_info1 = null;

		$category = null;
		$category_link = null;
		$keyword = null;
		$image_url = null;
		$brand = null;
		$brand_link = null;
		$price = null;
		$description = null;
		$social_share = null;


		$related_products = null;
		$prod = null;
		$spam = null;
		$search = null;
		$ad_desc = null;
		$more_info2 = null;


		$link_first = null;
		$link_prev = null;
		$link_next = null;
		$link_last = null;


		unset( $linkout1 );
		unset( $title2 );
		unset( $price_review );
		unset( $shipping_review );

		unset( $goto );
		unset( $affiliate_url );
		unset( $permalink );

		unset( $title1 );
		unset( $ad1 );
		unset( $ad2 );
		unset( $more_info1 );

		unset( $category );
		unset( $category_link );
		unset( $keyword );
		unset( $image_url );
		unset( $brand );
		unset( $brand_link );
		unset( $price );
		unset( $description );
		unset( $social_share );


		unset( $related_products );
		unset( $prod );
		unset( $spam );
		unset( $search );
		unset( $ad_desc );
		unset( $more_info2 );


		unset( $link_first );
		unset( $link_prev );
		unset( $link_next );
		unset( $link_last );



		//unset( $view, $layout, $main, $cache_file );
		//แสดงผลหน้าจอ
		//echo memory_get_peak_usage();
		//echo " : ";
		echo $html_path;
		echo "\n";

		//trigger_error( 'My Debug: RENDER' , $error_type = E_USER_ERROR );
		//trigger_error( 'My Debug: Test Only 1 Product Page' , $error_type = E_USER_ERROR );
	}


	public static function view_path( $controller, $action )
	{

		$controller_path = null;
		//$view_path = APP_PATH . 'views/' . THEME_NAME . '/' . $controller . '/' . $action . '.php';

		if ( !empty( self::$user_vars['view'] ) )
		{
			$view_path = APP_PATH . 'views/' . THEME_NAME . '/' . $controller . '/' . self::$user_vars['view'] . '.php';
		}
		else
		{
			$msg = 'The view file of<strong> ' . $controller . ' controller';
			$msg .= ' is not defined';
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}

		$path = null;
		if ( file_exists( $view_path ) ) {
			$path = $view_path;
		}
		else
		{
			$msg = 'The file <strong>' . $action . '.php';
			$msg .= ' could not be found at ' . $view_path;
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}
		return $path;
	}


	public static function get_layout( $controller, $action )
	{
		$layout = ( isset( self::$user_vars['layout'] ) ) ? self::$user_vars['layout'] . '.php' : 'layout.php';
		$path = APP_PATH . 'views/' . THEME_NAME . '/layouts/' . $layout;

		if ( file_exists( $path ) )
		{
			return $path;
		}
		else
		{
			die( '<span style="color:red">Layout file not found</span>' );
		}
	}


	public static function load_controller( $name )
	{
		//define path
		$controller_path = APP_PATH . 'controllers/' . $name . '_controller.php';

		//check path
		if ( file_exists(  $controller_path ) )
		{
			include_once $controller_path;
		}

		else
		{
			// $msg = '<span style="color:red">The file <strong>' . $name . '_controller.php</strong> could not be found at <pre>';
			// $msg .= $controller_path . '</pre></span>';
			// die( $msg );

			//header( 'location:' . HOME_URL . 'error' );

			$msg = "router -> load_controller: ";
			$msg .= "The file " . $name . "_controller.php could not be found at " . $controller_path;
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}
	}


	public static function get_content( $path )
	{
		if ( file_exists( $path ) )
			$content = file_get_contents( $path );

		else
			$content = null;

		return $content;
	}


	public static function write_file( $filename, $layout_content )
	{

		if ( ! file_exists( BASE_PATH . 'tmp' ) )
      	{
        	mkdir( BASE_PATH . 'tmp', 0777, true );
      	}

		$file = fopen( $filename, 'w' );
		fwrite( $file, $layout_content );
		fclose( $file );
	}

}
