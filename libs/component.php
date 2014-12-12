<?php
namespace webtools;


class AppComponent
{
	public function component( $component )
	{
		$path = WT_APP_PATH . 'components/' . $component . '_component.php'; 
		if ( file_exists( $path ) )
		{
			require_once $path;
	
			$arr = explode( '/', $component );
			$classname = end( $arr );
			$component_class = $classname . 'Component';
			$obj = new $component_class();
			return $obj;
		}
		else
		{
			die( "\n" . 'The ' . $component. ' component file not found!' . "\n\n" );
		}
	}
}
