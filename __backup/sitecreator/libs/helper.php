<?php

class Helper extends Controller
{
	public static function make_dir( $path )
	{
		if ( ! file_exists( $path ) )
		{
			//echo $path . PHP_EOL;
			mkdir( $path, 0777, true );
			//chmod( $path, 0777);
		}
	}

	public static function log( $msg )
	{
		$path = BASE_PATH . 'log/';
		self::make_dir( $path );

		$file = $path . 'error.txt';
		$fh = fopen( $file, 'a' );
		fwrite( $fh, $msg );
		fclose( $fh );
	}

	public static function getCategory( $merchant, $category )
	{
		$path = BASE_PATH . 'files/separator_category.txt';

		if ( file_exists( $path ) )
		{
			$files = unserialize( file_get_contents( $path ) );

			if ( array_key_exists( $merchant, $files ) )
			{
				$sepa = $files[$merchant];

				if ( ! empty( $sepa ) )
				{
					$arr = explode( $sepa, $category );
					$arr = array_filter( $arr );
					$cat_name = end( $arr );
				}
				else
				{
					$cat_name = $category;
				}
				return $cat_name;
			}
		}
		else
		{
			echo $path . ': file not found!!!';
			die();
		}
	}

	public static function get_permalink( $product_file, $product_key, $category )
	{
		$data = array(
			'product_file' => $product_file,
			'product_key' => $product_key,
			'category' => $category,
			'home_url' => HOME_URL,
			'format' => FORMAT,
			'prod_route' => PROD_ROUTE
		);

		//เรียกใช้ฟังก์ชั่นจาก config/permalink.php
		if ( SITE_TYPE == 'textsite'  )
		{
			$data['site_type'] = 'textsite';
			return Permalink::get( $data );
		}

		if ( SITE_TYPE == 'htmlsite'  )
		{
			$data['site_type'] = 'htmlsite';
			return Permalink::get( $data );
		}

		if ( SITE_TYPE == 'apisite' )
		{
			$data['site_type'] = 'apisite';
			return Permalink::get( $data );
		}
	}


	public static function clean_string( $string )
	{
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



	public static function get_serialize_string( $path )
	{
		if ( file_exists( $path ) )
		{
         $files = file_get_contents( $path );
         $files = unserialize( $files );

         return $files;
        }
		else
		{
			return false;
		}
	}

	/**
	* Save Serialize Text File แบบ Append
	* -------------------------------------------------------------------------
	*/
	public static function put_serialize_text( $path, $data )
	{
		if ( file_exists( $path ) )
		{
			//1. อ่านไฟล์ขึ้นมา
			$str = file_get_contents( $path );

			//2. unserialize array เก่า
			$str = unserialize( $str );

			//2.2 ถ้ามี key ของอะเรย์อยู่แล้วไม่ต้อง save cache

			//ดึงอะเรย์คีย์ออกมาจาก $data
			$keys = array_keys( $data );
			$key = $keys[0];

			//ตรวจสอบว่ามี key อยู่ใน $str ( array  )แล้วหรือเปล่า
			if ( array_key_exists( $key, $str ) )
			{
				return false;
			}

			//3. เพิ่มค่า array ใหม่ต่อท้ายเข้าไปใน array เก่า
			foreach ( $data as $key => $val )
			{
				$str[$key] = $val;
			}

			//4. serialize array กลับเข้าไปใหม่อีกครั้ง
			$str = serialize( $str );

			//5. save
			file_put_contents( $path, $str );
		}
		else
		{
			$str = serialize( $data );
			file_put_contents( $path, $str );
		}
	}



	public static function limit_words( $string, $word_limit )
	{
		$words = explode( " ", $string );
		return implode( " ", array_splice( $words, 0, $word_limit ) );
	}



	public static function image_size( $img_url, $img_size )
	{
		//img_size ( 75x75, 125x125, 250x250, 500x500 )
		$img = explode( '/', $img_url );
		$img[4] = $img_size;
		return implode( '/', $img );
	}




	/**
	 * Footer Linkout
	 * ---------------------------------------------------------------------------
	 */

	public static function linkout( $permalink, $keyword )
	{
		$home_page = array(
			'Alexa' => 'http://www.alexa.com/siteinfo/%home_url%',
		);

		$product_page_redirect = array(
			'USAFA' => 'http://www.usafa.edu/externalLinkDisclaimer.cfm?el=%permalink%',
		);

		$product_page_linkout = array(
			'Youtube' => 'http://www.youtube.com/results?search_query=%search%',
		);

		require_once( BASE_PATH . 'libraries/cache.php' );
		$c = new Cache();
		$path = 'cache/linkout';
		$name = 'linkout';
		$cache = $c->get( $path, $name );

		if ( $cache == NULL )
		{
			$home_page = self::random_linkout( $home_page );
			$product_page_redirect = self::random_linkout( $product_page_redirect );
			$product_page_linkout  = self::random_linkout( $product_page_linkout );


			$data = array(
				'home_page' => $home_page,
				'product_page_redirect' => $product_page_redirect,
				'product_page_linkout' => $product_page_linkout,
			);


			$c->set( $path, $name, $data );
			$cache = $data;

		}

		extract( $cache );


		foreach ( $home_page as $key => $val )
		{
			$home[ $key ] = str_replace( '%home_url%', HOME_URL . 'index.html', $val );
		}


		foreach ( $product_page_redirect as $key => $val )
		{
			$single_redirect[ $key ] = str_replace( '%permalink%', $permalink, $val );
		}


		foreach ( $product_page_linkout as $key => $val )
		{
			$single_out[ $key ] = str_replace( '%search%', urlencode( $keyword ), $val );
		}



		$result = array(
			'home' => $home,
			'single_redirect' => $single_redirect,
			'single_out' => $single_out,
		);

		return $result;

	}

	private static function random_linkout( $arr )
	{
		//Random
		$keys = array_keys( $arr );
		shuffle( $keys );
		$output = array_slice( $keys, 0, 2 );

		foreach ( $output as $key )
		{
			$result[ $key ] = $arr[ $key ];
		}
		return $result;
	}


	/**
	 * Gogo URL - goto merchant
	 * ---------------------------------------------------------------------------
	*/
	public static function prosperent_api( $affiliate_url, $referer )
	{
		// $arr = explode( '/', $affiliate_url );
		// $arr[5] = API_KEY;
		// $url = implode( '/', $arr );
		//SID AND REFERER
		//$sid     = SID . '-' . str_replace( 'http://', '', HOME_URL );
		//$referer = '&referrer=' . urlencode( $_SERVER['HTTP_REFERER'] );

		$referer = '&referrer=' . urlencode( $referer );
		$sid     = '&sid=' . urlencode( SID );

		//GO TO MERCHANT
		$url = $affiliate_url . $referer . $sid;
		return $url;
	}

}
