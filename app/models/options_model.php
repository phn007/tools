<?php
use webtools\AppComponent;


class OptionsModel extends AppComponent
{
	private $functionList = array();
	
	function __construct()
	{	
		$this->functionList = array(
			'textdb' => array( 
				'parameters' => array( 'product', 'brand' ), 
				'options' => array( 'config' ) 
			),
			'getproduct' => array( 
				'parameters' => array( 'all', 'category', 'brand', 'sitemap' ),
				'options' => array( 'config' )
			),
		);	
	}
	
	function verifyParamsAndOptionVars( $inputParams, $inputOptions )
	{
		//ชื่อฟังก์ชั่นที่ถูกส่งเข้ามาทาง parameter
		$functionName = strtolower( $this->getFunctionName( $inputParams ) );
		
		if ( false === $this->checkFunctionExist( $functionName ) )
		{
			
		}
	}
	
	function checkFunctionExist( $functionName, $functionKeys )
	{	
		if ( ! in_array( $functionName, $functionKeys ) )
			return false;
	}
	
	function checkParametersExist()
	{
		
	}
	
	function getParameters( $functionName, $this->functionList )
	{
		return $this->functionList[ $functionName ]['parameters'];
	}
	
	function getFunctionKeys()
	{
		return array_keys( $this->functionList );
	}
	
	function getOptionKeys( $inputOptions )
	{
		return array_keys( $inputOptions );
	}
	
	function getFunctionName( $inputParams )
	{
		return $inputParams['function'];
	}
	
	function checkHaveConfigFile( $optionKeys )
	{
		if ( ! in_array( 'config', $optionKeys ) )
			return false;
	}	
}
