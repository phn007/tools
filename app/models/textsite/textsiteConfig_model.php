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
		$this->printResult( $destination );
	}
	
	function setDestinationPath( $config )
	{
		return TEXTSITE_PATH . $config['project'] . '/' . $config['site_dir_name'] . '/config/';
	}
	
	function printResult( $destination )
	{
		echo "Create config file: ";
		echo $destination . 'config.php';
		echo "\n done...";
	}
}