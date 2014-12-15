<?php

trait SourceCode
{	
	private $config;
	private $cloneCom;
	private $siteType;
	
	function initialSourceCode( $config )
	{
		$this->config = $config;
		$this->siteType = $config['site_type'];
		$theme = $config['theme_name'];
		$this->cloneCom = $this->component( 'website/Clone' );
	}
	
	function copyFiles( $targetFileFunction, $sourceDir, $mode )
	{
		$source = $this->getSourcePath( $sourceDir );
		$destination = $this->getDestinationPath( $sourceDir );
		$specificFiles = $this->getSpecificFiles( $targetFileFunction, $source );
		$this->cloneCom->runClone( $source, $destination, $specificFiles, $mode );
	}
	
	function getSpecificFiles( $targetFileFunction, $source )
	{
		if ( empty( $targetFileFunction ) )
			return array();
		
		$list = $this->$targetFileFunction();
		if ( empty( $list ) )
			return array();
		
		foreach ( $list as $file )
			$data[] = $source . '/' . $file;
		return $data;
	}

	function getSourcePath( $sourceDir )
	{
		return WT_BASE_PATH . 'sitecreator/' . $sourceDir;
	}
	
	function getDestinationPath()
	{
		return TEXTSITE_PATH . $this->config['project'] . '/' . $this->config['server_name'];
	}
	
}