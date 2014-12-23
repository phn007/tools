<?php
use webtools\controller;
use webtools\libs\Helper;

/**
* Create textSite Htaccess
*/
class TextsiteHtaccessModel extends Controller {
	
	function create( $config ) {
		$projectName = $config['project'];
		$siteDirName = $config['site_dir_name'];
		$webType = $config['web_type'];
		$webUser = $config['web_user'];
		
		$hta = 'RewriteEngine On' . PHP_EOL;
		$hta .= $this->rewriteBase( $webType, $webUser ) . PHP_EOL;
		$hta .= 'RewriteCond %{REQUEST_FILENAME} !-f' . PHP_EOL;
		$hta .= 'RewriteCond %{REQUEST_FILENAME} !-d' . PHP_EOL;
		$hta .= 'RewriteRule (.*) index.php/$1' . PHP_EOL;

		$destination = $this->setDestination( $projectName, $siteDirName );
		Helper::make_dir( $destination );
		$this->writeHtaccessFile( $destination, $hta );
	}
	
	function writeHtaccessFile( $destination, $content ) {
		$fh = fopen( $destination . '/.htaccess', 'w' );
		fwrite( $fh, $content );
		fclose( $fh );
	}
	
	function rewriteBase( $webType, $webUser ) {
		$hta = null;
		if ( 'temporary' == $webType ) {
			if ( empty( $webUser ) )
				die( "Cannot empty web_user variable! ( config file )\n" );
			$hta = 'RewriteBase /' . $webUser;
		}
		return $hta;
	}
	
	function setDestination( $projectName, $siteDirName ) {
		return TEXTSITE_PATH . $projectName . '/' . $siteDirName;
	}
}