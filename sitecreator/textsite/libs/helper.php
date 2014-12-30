<?php
class Helper {

	public static function make_dir( $path ) {
		if ( ! file_exists( $path ) ) {
			mkdir( $path, 0777, true );
		}
	}

	public static function clean_string( $string ) {
		$string = preg_replace( "`\[.*\]`U", "", $string );
		$string = preg_replace( '`&(amp;)?#?[a-z0-9]+;`i', '-', $string );
		$string = str_replace( '%', '-percent', $string );
		$string = htmlentities( $string, ENT_COMPAT, 'utf-8' );
		$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i", "\\1", $string );
		$string = preg_replace( array( "`[^a-z0-9ก-๙เ-า]`i", "`[-]+`") , " ", $string );
		$string = strtolower( trim( $string, '-') );
		$string = strtolower( trim( $string ) );
		$string = preg_replace( "/(.*)\/(\w{2})\/(\w{2})(\/.*|$)/", '$1/$3$4', $string );
		$string = str_replace( " ", "-", $string );
		$find   = array( '---', '--' );
		$string = str_replace( $find, '-', $string );
		return $string;
	}
	
	public static function limit_words( $string, $word_limit ) {
		$words = explode( " ", $string );
		return implode( " ", array_splice( $words, 0, $word_limit ) );
	}

	public static function image_size( $img_url, $img_size ) {
		//img_size ( 75x75, 125x125, 250x250, 500x500 )
		$img = explode( '/', $img_url );
		$img[4] = $img_size;
		return implode( '/', $img );
	}
}