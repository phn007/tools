<?php
trait HomePage {

	function buildHomePageHtml() {
		$controller = 'home';
		$action = 'index';
		$projectName = $this->config['project'];
		$siteDirName = $this->config['site_dir_name'];

		$command = 'php '. WT_BASE_PATH . 'buildhtml/app.php ';
		$command .= $projectName . ' ';
		$command .= $siteDirName . ' ';
		$command .= $controller . ' ';
		$command .= $action;
		echo shell_exec( $command );
	}
}