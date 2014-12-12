<?php
namespace webtools\libs;

 class Router
 {
	public static function dispatchTestNew( $options )
	{
		$controller = $options['controller'];
		$action = $options['action'];
		$functions = $options['function'];
		$params	= $options['params'];
		$options = $options['options'];
		
		
		//โหลด controller ที่ user ส่งเข้ามา
		self::load_controller( $controller );

		//กำหนดรูปแบบชื่อของ Controller
		$class_name = ucfirst( $controller ) . 'Controller';

		//ตรวจสอบว่ามี class อยู่หรือเปล่า
		if ( class_exists( "$class_name" ) )
		{
			$class = "$class_name";
			$tmp_class = new $class();

			//ตรวสอบว่าใน class มี action อยู่หรือเปล่า
			if ( is_callable( array( $tmp_class, $action ) ) )
			{
				$tmp_class->$action( $functions, $params, $options );
			}
			else
			{
				echo "\n";
				echo 'The action ' . $action . ' could not be called from the controller';
				echo "\n";
				echo "\n";
				die();
			}
		}
		else
		{
			echo "\n";
			echo $class_name . " class not found!";
			echo "\n";
			echo "\n";
			die();
		}
		
		
	}
	 
   public static function dispatch( $opts )
   {
      $controller = $opts['controller'];
      $action     = $opts['action'];
      $param      = $opts['param'];
      $option     = $opts['option'];

      //โหลด controller ที่ user ส่งเข้ามา
      self::load_controller( $controller );

      //กำหนดรูปแบบชื่อของ Controller
      $class_name = ucfirst( $controller ) . 'Controller';

      //ตรวจสอบว่ามี class อยู่หรือเปล่า
      if ( class_exists( "$class_name" ) )
      {
         $class = "$class_name";
         $tmp_class = new $class();

         //ตรวสอบว่าใน class มี action อยู่หรือเปล่า
         if ( is_callable( array( $tmp_class, $action ) ) )
         {
            $tmp_class->$action( $param, $option );
         }
         else
         {
            echo "\n";
            echo 'The action ' . $action . ' could not be called from the controller';
            echo "\n";
            echo "\n";
            die();
         }
      }
      else
      {
         echo "\n";
         echo $class_name . " class not found!";
         echo "\n";
         echo "\n";
         die();
      }
   }


   public static function load_controller( $control )
   {
      //define path
	   $controller_path = WT_APP_PATH . 'controllers/' . $control . '_controller.php';

		//check path
		if ( file_exists(  $controller_path ) )
		{
			include $controller_path;
		}
      else
      {
         echo $control . " controller file not found!";
         echo "\n";
         echo "\n";

         die();
      }
   }


}//class
