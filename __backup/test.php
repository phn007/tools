<?php
array = array('apple', 'orange', 'pear', 'banana', 'apple', 'pear', 'kiwi', 'kiwi');

$array_duplicate         = array();
$array_unique            = array();
$array_duplicate_cnt     = array();

foreach( $array as $val )
{
	if ( ++$array_duplicate_cnt[$val] > 1 )
	{
		$array_duplicate[] = $val;
	}
	else
	{
		if ( count ( array_keys( $array, $val ) ) == 1 )
		{ 
			$array_unique[]     = $val;
		}
	}
}
