<?php
require 'vendor/autoload.php';


class ProductDbModelTest extends PHPUnit_Framework_TestCase
{
	private $prod;
	private $options = array(
				'controller' => 'textdb',
				'action' => 'product',
				'param' => array( 'function' => 'textdb	', 'arg' => NULL ),
				'option' => array( 'f' => NULL, 'config' => 'ddb2.ini' )
			);
	
	function setup()
	{
		$options = $this->options;
		require 'appindex.php';
		
		$this->prod = new TextDbController();
	}

	
//	function testInitialOption()
//	{
//		$this->prod->product( $this->options['param'], $this->options['option'] );
//		$this->assertEquals( 'ProductTextDb', $this->prod->initialOptions() );
//		
//	}
}

//$this->markTestSkipped();
//$this->assertTrue( TRUE );