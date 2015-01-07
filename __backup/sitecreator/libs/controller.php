<?php

//contain's methods for all the user defined controllers

class Controller extends Object
{
	public function model( $model )
	{
		$path = APP_PATH . 'models/' . $model . '_model.php';
		if ( file_exists( $path ) )
		{
			require_once $path;

			$model = str_replace( '-', '', $model );
			$model_class = $model . 'Model';
			$model_class = "$model_class";
			return new $model_class();
		}
		else
		{
			die( $path . ": Model file not found");
		}
	}
}
