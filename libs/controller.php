<?php
namespace webtools;

class Controller
{
	public function model( $model )
	{
		$path = WT_APP_PATH . 'models/' . $model . '_model.php';
		if ( file_exists( $path ) )
		{
			require_once $path;
			
			$arr = explode( '/', $model );
			$className = end( $arr );
			
			$model_class = $className . 'Model';
			$model_class = "$model_class";
			return new $model_class();
		}
		else
		{
			die( "\n" . 'The ' . $model. ' model file not found!' . "\n\n" );
		}
	}
}
