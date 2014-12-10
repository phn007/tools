<?php
require 'vendor/autoload.php';

class OptionTest extends PHPUnit_Framework_TestCase
{
	private $input;
	private $option;
	
	function setup()
	{
		$array[0] = array( 'config' => 'ddb2.ini' );
		$array[1] = array( 'create', 'pages', 1 , 2, 3, 4 );
		$this->input = $array;
		
		$this->options = new Options();
	}
	
	function testItCanGetOptionList()
	{
		$textsiteOptionList = $this->options->optionList->textsite(); 
		print_r( $textsiteOptionList );
		$this->assertNotEmpty( $textsiteOptionList );
	}
	
	
	
	function testItCanGetInputAction()
	{
		$action = 'create';
		$this->assertEquals( $action, $this->options->getInputAction( $this->input ) );
	}
	
	function testItCanGetInputArgs()
	{
		$args = array( 'pages', 1 , 2, 3, 4 );
		$this->assertEquals( $args, $this->options->getInputArgs( $this->input ) );
	}
}