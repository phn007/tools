<?php
class Map extends Object {
	use Render;
	use LayoutAndContent;
	use SetSavePath;

	static function dispatch( $controller, $action, $params ) {
		self::load_controller( $controller );
		$class_name = self::getClassName( $controller );
		$tmp_class = self::createClassObject( $class_name );
		self::callAction( $tmp_class, $action, $params );
		self::render( $controller, $action, $params ); //Render Trait
	}

	function getClassName( $controller ) {
		return ucfirst( $controller ) . 'Controller';
	}

	function load_controller( $name ) {
		$controller_path = APP_PATH . 'controllers/' . $name . '_controller.php';
		if ( ! file_exists(  $controller_path ) ) {
			$msg = "router -> load_controller: ";
			$msg .= "The file " . $name . "_controller.php could not be found at " . $controller_path;
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}
		include_once $controller_path;
	}	

	function createClassObject( $class_name ) {
		if ( ! class_exists( $class_name ) ) {
			$msg = 'My Debug: ' . $class_name . ' Not Found!!!';
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}
		return new $class_name();
	}

	function callAction( $tmp_class, $action, $params ) {
		if ( ! is_callable( array( $tmp_class, $action ) ) ) {
			$msg = "is_callable return false : dispatch function";
			trigger_error( $msg, $error_type = E_USER_ERROR );
		}
		$tmp_class->$action( $params );	
	}
}

trait Render {
	static function render( $controller, $action, $params ) {
		$filePath = self::getFilePath();
		$layoutMainPath = self::getLayoutMainPath(); 
		self::setLayoutAndContent( $controller, $action, $filePath, $layoutMainPath );

	 	extract( self::$user_vars );
		$moduleFunctionPath = self::setModuleFunctionPath();
		include( $moduleFunctionPath );
		$savePath = self::setSavePath( $params );

		ob_start();
			include $filePath;
			$cache  = ob_get_contents();
			file_put_contents( $savePath, $cache );
		ob_end_clean();
		unlink( $filePath );
		echo $savePath;
		echo "\n";
	}

	function setModuleFunctionPath() {
		return APP_PATH  . 'views/' . THEME_NAME . '/modules/functions.php';
	}

	function getFilePath() {
		return BASE_PATH . 'tmp/main_' . time() . '.php';	
	}

	function getLayoutMainPath() {
		return APP_PATH  . 'views/' . THEME_NAME . '/layouts/main.php';
	}
}

trait SetSavePath {
	function setSavePath( $params ) {
		extract( self::$user_vars );
		if ( $currentPage == 'home-page' ) return self::homePage();
		if ( $currentPage == 'product-page' ) return self::productPage( $params );
		if ( $currentPage == 'category-page' ) return self::categoryPage( $params );
		if ( $currentPage == 'brand-page' ) return self::brandPage( $params );
		if ( $currentPage == 'categories-page' ) return self::categoriesPage( $params );
		if ( $currentPage == 'brands-page' ) return self::brandsPage( $params );
		if ( $currentPage == 'about-page' ) return self::staticPage( 'about' );
		if ( $currentPage == 'contact-page' ) return self::staticPage( 'contact' );
		if ( $currentPage == 'privacy-page' ) return self::staticPage( 'privacy-policy' );
	}

	function staticPage( $pageName ) {
		$path = self::setBasePath();
		Helper::make_dir( $path );
		return $path . $pageName . FORMAT;
	}

	function homePage() {
		$path = self::setBasePath();
		Helper::make_dir( $path );
		return $path . 'index' . FORMAT;
	}

	function productPage( $params ) {
		if ( isset( $params[0] ) ) $productFile = $params[0];
		if ( isset( $params[1] ) ) $productKey = $params[1];
		$path = self::setBasePath() . $productFile . '/';
		Helper::make_dir( $path );
		return $path . $productKey . FORMAT;
	}

	function categoryPage( $params ) {
		if ( isset( $params[0] ) ) $catSlug = $params[0];
		if ( isset( $params[1] ) ) $page = $params[1];
		if ( $page == 1 ) {
			Helper::make_dir( self::setBasePath() . 'category/' );
			return self::setBasePath() . 'category/' . $catSlug . FORMAT;
		} 
		$path = self::setBasePath() . 'category/' . $catSlug . '/';
		Helper::make_dir( $path );
		return $path . $page . FORMAT;
	}

	function brandPage( $params ) {
		if ( isset( $params[0] ) ) $brandSlug = $params[0];
		if ( isset( $params[1] ) ) $page = $params[1];
		if ( $page == 1 ) {
			Helper::make_dir( self::setBasePath() . 'brand/' );
			return self::setBasePath() . 'brand/' . $brandSlug . FORMAT;
		} 
		$path = self::setBasePath() . 'brand/' . $brandSlug . '/';
		Helper::make_dir( $path );
		return $path . $page . FORMAT;
	}

	function categoriesPage( $params ) {
		if ( isset( $params[0] ) ) $page = $params[0];
		if ( $page == 1 ) {
			Helper::make_dir( self::setBasePath() );
			return self::setBasePath() . 'categories' . FORMAT;
		}
		$path = self::setBasePath() . 'categories/';
		Helper::make_dir( $path );
		return $path . $page . FORMAT;
	}


	function brandsPage( $params ) {
		if ( isset( $params[0] ) ) $page = $params[0];
		if ( $page == 1 ) {
			Helper::make_dir( self::setBasePath() );
			return self::setBasePath() . 'brands' . FORMAT;
		}
		$path = self::setBasePath() . 'brands/';
		Helper::make_dir( $path );
		return $path . $page . FORMAT;
	}	

	function setBasePath() {
		return BASE_PATH . '_html-site/';
	}	
}

trait LayoutAndContent {
	function setLayoutAndContent( $controller, $action, $filePath, $layoutMainPath  ) {
		$viewPath   = self::viewPath( $controller, $action );
		$layoutPath = self::getLayout( $controller, $action );
		
		$view = self::getViewContent( $viewPath );
		$layout = self::getLayoutContent( $layoutPath );
		$viewLayou = self::combineViewLayout( $view, $layout );		
		$main = self::getMainContent( $layoutMainPath );
		$viewLayoutMain = self::combineViewLayoutMain( $viewLayou, $main );
		
		self::write_file( $filePath, $viewLayoutMain );
	}

	function viewPath( $controller, $action ) {
		if ( empty( self::$user_vars['view'] ) ) {
			$msg = '<span style="color:red">The view file of<strong> ' . $controller . ' controller</strong>';
			$msg .= ' is not defined';
			die( $msg );	
		}
		$view_path = APP_PATH . 'views/' . THEME_NAME . '/' . $controller . '/' . self::$user_vars['view'] . '.php';
		
		if ( ! file_exists( $view_path ) ) {
			$msg = '<span style="color:red">The file <strong>' . $action . '.php</strong>';
			$msg .= ' could not be found at <pre>' . $view_path . '</pre></span>';
			die( $msg );
		}
		return $view_path;
	}

	function getLayout( $controller, $action ) {
		$layout = ( isset( self::$user_vars['layout'] ) ) ? self::$user_vars['layout'] . '.php' : 'layout.php';
		$path = APP_PATH . 'views/' . THEME_NAME . '/layouts/' . $layout;
		if ( ! file_exists( $path ) )
			die( '<span style="color:red">Layout file not found</span>' );
		return $path;
	}	

	function getViewContent( $view_path ) {
		$view = self::get_content( $view_path  );
		if ( $view == null ) die( '<span style="color:red">View file not found</span>' );
		return $view;
	}

	function getLayoutContent( $layout_path ) {
		$layout = self::get_content( $layout_path );
		if ( $layout == null ) die( '<span style="color:red">Layout file not found</span>' );
		return $layout;
	}

	function combineViewLayout( $view, $layout ) {
		$view_layout = str_replace( '[%CONTENT%]', $view, $layout );
		unset( $view, $layout );
		return $view_layout;
	}	

	function getMainContent( $main_path ) {
		//get main content
		$main = file_get_contents( $main_path );
		if ( $main == null ) die( '<span style="color:red">Main file not found</span>' );
		return $main;
	}
	
	function combineViewLayoutMain( $view_layout, $main ) {
		//combine - view + layout + main
		$view_layout_main = str_replace( '[%LAYOUT_CONTENT%]', $view_layout, $main );
		return $view_layout_main;
	}

	function write_file( $filename, $layout_content ) {
		if ( ! file_exists( BASE_PATH . 'tmp' ) )
        	mkdir( BASE_PATH . 'tmp', 0777, true );

		$file = fopen( $filename, 'w' );
		fwrite( $file, $layout_content );
		fclose( $file );
	}

	function get_content( $path ) {
		if ( ! file_exists( $path ) )
			$content = null;
		else
			$content = file_get_contents( $path );
		return $content;
	}
}