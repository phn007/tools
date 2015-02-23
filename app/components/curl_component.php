<?php
/**
* CURL
*/
class CurlComponent extends CUrlOptions
{
	function getRequest( $options ) {
		$this->init();
		$this->setOptions( $options );
		$result = $this->exec();
		$info = $this->getInfo();
		return array(
			'content' => $result,
			'info' => $info
		);
	}

	function setOptions( $params ) {
		foreach ( $params as $option => $value ) {
			if ( is_callable( array( $this, $option ) ) )
				$this->$option( $value );
		}
	}

	private function init() {
		$this->ch = curl_init();
	}

	private function exec() {
		return curl_exec ( $this->ch );
	}

	private function getInfo() {
		return curl_getinfo( $this->ch );
	}
}

/**
* CURL OPTIONS
*/
class CurlOptions {
	protected $ch;

	protected function url( $url ) {
		curl_setopt( $this->ch, CURLOPT_URL, $url );
	}

	protected function returnTransfer( $value ) {
		curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, $value );
	}

	protected function connectTimeout( $second ) {
		curl_setopt( $this->ch, CURLOPT_CONNECTTIMEOUT, $second );
	}

	protected function timeout( $second ) {
		curl_setopt( $this->ch, CURLOPT_TIMEOUT, $second );
	}

	protected function followLocation( $value ) {
		curl_setopt( $this->ch, CURLOPT_FOLLOWLOCATION, $value );
	}

	protected function userAgent( $userAgent ) {
		curl_setopt( $this->ch, CURLOPT_USERAGENT, $userAgent );
	}

	protected function httpHeader( $header ) {
		curl_setopt( $this->ch, CURLOPT_HTTPHEADER, $header );
	}

	protected function referer( $referer ) {
		curl_setopt( $this->ch, CURLOPT_REFERER, $referer );
	}
}