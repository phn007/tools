<?php
use webtools\AppComponent;

class InitialVariabelsModel extends AppComponent
{
	function initialVariables( $params, $options )
	{
		$this->params = $params;
		$this->options = $options;
	}
	
	function initialParamsAndOptions()
	{
		$optionsModel = $this->model( 'options' );
		return $optionsModel->verifyOptionVaribles( $this->action, $this->params, $this->options );
	}
	
	function initialConfig()
	{
		$optionData = $this->initialOptions();
		$configModel = $this->model( 'config' );
		return $configModel->setupConfig( $optionData );
	}
}