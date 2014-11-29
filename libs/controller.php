<?php
namespace webtools;
/**
 * Controller
 */
class Controller
{
   public function model( $model )
	{
		$path = WT_APP_PATH . 'models/' . $model . '_model.php';
		if ( file_exists( $path ) )
		{
			require_once $path;
         $model_class = $model . 'Model';
         $model_class = "$model_class";
			return new $model_class();
		}
      else
      {
         echo "\n";
         echo 'The ' . $model . ' model file not found!';
         echo "\n";
         echo "\n";
         die();
      }
	}
}
