<?php
function find_in_arr( $key, $arr ) {
    foreach ( $arr as $k => $v ) {

    	// echo "key: " . $k . " => value: " . $v;
    	// echo "\n";
        if ( $k == $key ) {
            return $v;
        }
        if ( is_array( $v ) ) {
            $result = find_in_arr( $key, $v );
            if ( $result != false ) {
                return $result;
            }
        }
    }  
    return false;
}

$arr = [
    'name' => 'Php Master',
    'subject' => 'Php',
    'type' => 'Articles',
    'items' => [
        'one' => 'Iteration',
        'two' => 'Recursion(two)',
        'methods' => [
            'factorial' => 'Recursion',
            'fibonacci' => 'Recursion',
        ],
    ],
    'parent' => 'Sitepoint',
];

echo find_in_arr( 'two', $arr );
