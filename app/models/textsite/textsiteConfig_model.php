<?php
use webtools\controller;
use webtools\libs\Helper;

class TextsiteConfigModel extends Controller
{
	function create( $config )
	{
		$destination = $this->setDestinationPath( $config );
		Helper::make_dir( $destination );
		file_put_contents( 
			$destination . 'config.php', 
			'<?php' . PHP_EOL . '$cfg = ' . var_export( $config, true) . ';' 
		);
	}
	
	function setDestinationPath( $config )
	{
		return TEXTSITE_PATH . $config['project'] . '/' . $config['server_name'] . '/config/';
	}
}