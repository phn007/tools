<?php

class Map extends Object {

	public static $path = null;

	public static function get( $route, $path )
	{
		self::$path = $path;
		Sammy::process( $route, 'GET' );
	}


	public static function pre_dispatch( $uri )
	{
		// $path = explode('/', $uri);
		// $controller = $path[0];
		// $action = ( empty( $path[1] ) ) ? 'index' : $path[1];
		// unset( $path[0], $path[1] );
		//
		// $params = $path ? array_values( $path ) : array();
		// self::$path = $controller . '#' . $action;
		// self::dispatch( $params );



		/*
		 * กำหนดตัวแปรให้กับ page product
		 * ----------------------------------------------------------------------
		*/
		if ( 'textsite' == SITE_TYPE || 'htmlsite' == SITE_TYPE )
		{
			//Controller และ Action
			$controller = 'product';
			$action = 'index';
			self::$path = $controller . '#' . $action;


			//example url : http://domain.com/THFQ001/Figurines/my-daughter-my-most-precious-gift-figurine.html
			//กำหนดค่าให้กับ product file และ product key
			//โดยดูจาก url ที่เรากำหนดไว้ว่า product file และ product key
			//เวลาแยก uri เป็นอะเรย์ แล้วจะตรงกับอะเรย์ตัวที่เท่าไหร่
			// $path = explode('/', $uri);
			// $product_file = $path[0];
			// $product_key  = str_replace( FORMAT , '', $path[2] );
			// $params = array( $product_file, $product_key );


			//http://domain.com/category-slug/keyword-slug.html
			$path = explode('/', $uri);
			$product_file = $path[0];
			$product_key  = str_replace( FORMAT , '', $path[1] );
			$params = array( $product_file, $product_key );


		}
		elseif ( 'apisite' == SITE_TYPE )
		{
			//Controller และ Action
			$controller = 'api-product';
			$action = 'index';
			self::$path = $controller . '#' . $action;

			//http://domain.com/all-accessories/proper-shoulder-bag.html
			$path = explode('/', $uri);
			$product_file = $path[0];
			$product_key  = str_replace( FORMAT , '', $path[1] );
			$params = array( $product_file, $product_key );
		}
		else
		{
			echo "pre dispatch: There is no site type";
			exit( 0 );
		}
		self::dispatch( $params );
	}



	public static function dispatch( $params = false )
	{
		//run when find a matching route
		$path = explode( '#', self::$path );
		$controller = $path[0];
		$action = $path[1];

		// echo "Controller: " . $controller;
		// echo "<br>";
		// echo "Action: " . $action;
		// echo "<br>";

		//include the app controller
		//self::load_controller( 'app' );

		//include the matching controller
		self::load_controller( $controller );

		//Run Class/Action
		$controller = str_replace( '-', '', $controller );
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
				$msg = '<span style="color:red">The action <strong>' . $action . '</strong> could not be called from the controller <strong>';
				$msg .= $class_name . '</strong></span>';
				die( $msg );

				//header( 'location:' . HOME_URL . 'error' );
			}
		}
		else
		{
			$msg = '<span style="color:red">The class <strong>' . $class_name . '</strong> could not be found in <pre>';
			$msg .= APP_PATH . 'controllers/' . $controller . '_controller.php</pre></span>';
			die( $msg );

			//header( 'location:' . HOME_URL . 'error' );
		}

		/*
		 * View
		 * ----------------------------------------------------------------------
	    */
		self::render( $controller, $action );
	}



	public static function load_controller( $name )
	{
		//define path
		$controller_path = APP_PATH . 'controllers/' . $name . '_controller.php';



		//check path
		if ( file_exists(  $controller_path ) )
		{
			include $controller_path;
		}
		else
		{
			$msg = '<span style="color:red">The file <strong>' . $name . '_controller.php</strong> could not be found at <pre>';
			$msg .= $controller_path . '</pre></span>';
			die( $msg );

			//header( 'location:' . HOME_URL . 'error' );
		}
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

		//get view content
		$view = self::get_content( $view_path  );
		if ( $view == null ) die( '<span style="color:red">View file not found</span>' );

		//get layout content
		$layout = self::get_content( $layout_path );
		if ( $layout == null ) die( '<span style="color:red">Layout file not found</span>' );


		//combine - view + layout
		$view_layout = str_replace( '[%CONTENT%]', $view, $layout );
		unset( $view, $layout );


		//get main content
		$main = file_get_contents( $main_path );
		if ( $main == null ) die( '<span style="color:red">Main file not found</span>' );

		//combine - view + layout + main
		$view_layout_main = str_replace( '[%LAYOUT_CONTENT%]', $view_layout, $main );


		//write to text file
		self::write_file( $file_main, $view_layout_main );
		unset( $view_layout_main );


		//Load View
		extract( self::$user_vars );
		//die( "<hr>Before include file main<hr>" );
		include $file_main;

		//delete text file
		unlink( $file_main );

	}


	public static function view_path( $controller, $action )
	{
		$controller_path = null;
		//$view_path = APP_PATH . 'views/' . THEME_NAME . '/' . $controller . '/' . $action . '.php';

		//ถ้าเป็น api home controller ให้เอาคำว่า api ออกก่อน
		$controller = str_replace( 'api', '', $controller );

		if ( !empty( self::$user_vars['view'] ) )
		{
			$view_path = APP_PATH . 'views/' . THEME_NAME . '/' . $controller . '/' . self::$user_vars['view'] . '.php';
		}
		else
		{
			$msg = '<span style="color:red">The view file of<strong> ' . $controller . ' controller</strong>';
			$msg .= ' is not defined';
			die( $msg );
		}
		$path = null;

		if ( file_exists( $view_path ) ) {
			$path = $view_path;
		}
		else
		{
			$msg = '<span style="color:red">The file <strong>' . $action . '.php</strong>';
			$msg .= ' could not be found at <pre>' . $view_path . '</pre></span>';
			die( $msg );
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
