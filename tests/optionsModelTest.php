<?php
require 'vendor/autoload.php';

class OptionsModelTest extends PHPUnit_Framework_TestCase
{
	private $optionModel;
	private $params;
	private $options;
	private $parameter = array(
				'controller' => 'textdb',
				'action' => 'product',
				'param' => array( 'function' => 'textdb', 'arg' =>  ),
				'option' => array( 'f' => NULL, 'config' => 'ddb2.ini' )
			);

	function setup()
	{
		$this->params = $this->parameter['param'];
		$this->options = $this->parameter['option'];
		$this->optionsModel = new OptionsModel();
	}

	function testItCanGetFunctionName()
	{
		$functionName = $this->params['function'];
		$this->assertEquals( $functionName, $this->optionsModel->getFunctionName( $this->params ) );
	}

	function testItCanGetOptionKeys()
	{
		$optionKeys = array_keys( $this->options );
		$this->assertEquals( $optionKeys, $this->optionsModel->getOptionKeys( $this->options ) );
	}

	function testCheckIfMothodExists()
	{
		$functionName = $this->params['function'];
		$this->assertTrue( $this->optionsModel->checkMethodExist( $functionName ) );
	}

	function testVerifyParamsAndOptionsVar()
	{
		$this->assertNULL( $this->optionsModel->verifyParamsAndOptionVars( $this->params, $this->options ) );
	}
}
