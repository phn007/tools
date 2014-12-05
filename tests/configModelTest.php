<?php
 
class ConfigModelTest extends PHPUnit_Framework_TestCase
{
    function testAddNumber()
    {
        $x = 1;
        $y = 1;
        
        $conf = new ConfigModel();
        $this->assertEquals( 2, $conf->addNumber( $x, $y ) );
    }
}