<?php
trait HostnineInfo {
	function getApiUrl() {
		return 'https://cp.hostnine.com/api/';
	}

	function getApiKey( $accountName ) {
		$apiKeys = array(
			'maxcom' => 'Uq5plhH6UUobqhhU1bTHca5DxUC4euto4',
			'ratzin' => 'M2fXDzAWrbae2kGlXAknS4vdN2RhJV93b',
			'rexce' => '9dG99tk8O9aepZ6PWo7uegfpEDnSdROb6'
		);
		if ( ! array_key_exists( $accountName, $apiKeys ) )
			die( 'Hostnine: account name not found' );
		return $apiKeys[$accountName];
	}
}