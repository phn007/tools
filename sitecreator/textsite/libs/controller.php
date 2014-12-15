<?php

class Controller extends Object
{
	public function model( $model )
	{
		$path = APP_PATH . 'models/' . $model . '_model.php';
		if ( ! file_exists( $path ) )
			die( "\n" . 'The ' . $model. ' model file not found!' . "\n\n" );
		
		require_once $path;
		$arr = explode( '/', $model );
		$className = end( $arr );
		$model_class = $className . 'Model';
		$model_class = "$model_class";
		return new $model_class();
	}
	
	public function component( $component )
	{
		$path = APP_PATH . 'components/' . $component . '_component.php'; 
		if ( ! file_exists( $path ) )
			die( "\n" . 'The ' . $component. ' component file not found!' . "\n\n" );
		
		require_once $path;
		$arr = explode( '/', $component );
		$classname = end( $arr );
		$component_class = $classname . 'Component';
		$obj = new $component_class();
		return $obj;
	}

}