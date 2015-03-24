<?php
use webtools\controller;
use webtools\libs\Helper;

include WT_BASE_PATH . 'libs/TablePrinter.php'; 
include WT_BASE_PATH . 'libs/csvReader.php'; 
include WT_APP_PATH . 'traits/hostnine/hostnineInfo_trait.php';
include WT_APP_PATH . 'traits/hostnine/resellerCentralQuery_trait.php';

class AccountsModel extends Controller {
	use HostnineInfo;
	use ResellerCentralQuery;

	//get accounts
	use GetAccounts;
	use SortResults;

	//termainate account
	use TerminateAccount;
	use GetCsvData;

	//create account
	use CreateAccount;

	/**
	 * Retrieve a list of accounts.
	 */
	function get( $params, $options ) {
		$account = $params['account'];
		$domain = $params['domain'];
		$apiKey = $this->getApiKey( $account ); //see, HostnineInfo Trait
		$accounts = $this->getAccounts( $domain, $apiKey );	//see, GetAccounts Trait
		$accounts = $this->sortResults( $accounts, $options ); //see, SortResults Trait
		$this->displayResults( $accounts ); //see, SortResults Trait
	}

	/**
	 * Setup a new account.
	 */
	function create( $params , $options ) {
		if ( array_key_exists( 'config', $options ) )
			$this->createAccountFromCsvFile( $options );
		else 
			$this->createAccountFromInputParams( $params );
	}

	/**
	 * Terminate and remove an account's hosting service that is setup within your Reseller Central.
	 */
	function terminate( $params, $options ) {
		if ( array_key_exists( 'config', $options ) )
			$this->terminateAccountFromCsvFile( $options );
		else 
			$this->terminateAccountFromInputParams( $params );	
	}
}//Model Class

/**
 * CREATE ACCOUNT TRAIT SECTION
 * =======================================================================================================================
 */
trait CreateAccount {
	function createAccountFromCsvFile( $options ) {
		$results = $this->getAccountListFromCsvFile( $options['config'] );
		foreach ( $results as $list ) {
			$apiKey = $this->getApiKey( trim( $list['account'] ) );
			$this->createAccount( $apiKey, $list );
		}
	}

	function createAccountFromInputParams( $params ) {
		$apiKey = $this->getApiKey( trim( $params['account'] ) );
		$this->createAccount( $apiKey, $params );
	}

	function createAccount( $apiKey, $list ) {
		$params = $this->getCreateAccountParams( $apiKey, $list );
		$newAccount = $this->resellercentralQuery( 'createAccount', $params ); //see, resellerCentralQuery_trait
		$this->printCreateResult( $list['domain'], $newAccount );
	}

	function getCreateAccountParams( $apiKey, $list ) {
		return array(
			'api_key' => $apiKey, 
			'domain' => trim( $list['domain'] ), 
			'username' => trim( $list['username'] ),
			'password' => trim( $list['password'] ), 
			'location' => trim( $list['location'] ),
			'package' => trim( $list['package'] )
		);
	}

	function printCreateResult( $domain, $newAccount ) {
		if ( $newAccount['success'] == 'true')
			echo $domain. ': Successfully created account.' . "\n";
		else 
			echo 'Error creating account, result: ' . $newAccount['result'] . "\n";
	}
}

/**
 * TERMINATE ACCOUNT TRAIT SECTION
 * =======================================================================================================================
 */
trait TerminateAccount {
	function terminateAccountFromCsvFile( $options  ) {
		$results = $this->getAccountListFromCsvFile( $options['config'] );
		foreach (  $results as $list ) {
			$apiKey = $this->getApiKey( trim( $list['account'] ) );
			$domain = trim( $list['domain'] );
			$this->terminateAccount( $apiKey, $domain );
		}
	}

	function terminateAccountFromInputParams( $params ) {
		$apiKey = $this->getApiKey( $params['account'] );
		$domain = $params['domain'];
		$this->terminateAccount( $apiKey, $domain );
	}

	function terminateAccount( $apiKey, $domain ) {
		$params = $this->getTerminateParams( $apiKey, $domain );
		$terminate = $this->resellercentralQuery( 'terminateAccount', $params ); //see, resellerCentralQuery_trait
		$this->printTerminateResult( $terminate );
	}

	function printTerminateResult( $terminate ) {
		if ( $terminate['success'] != 'true' ) die('Can not be remove ' . $domain . ' domain' );
		echo $terminate['result'];
		echo "\n";	
	}

	function getTerminateParams( $apiKey, $domain ) {
		return array(
			'api_key' => $apiKey,
			'domain' => $domain
		);
	}
}

/**
 * GET CSVFILE TRAIT SECTION
 * =======================================================================================================================
 */
trait GetCsvData {
	function getAccountListFromCsvFile( $filename ) {
		$path = $this->getCsvPath( $filename );
		$csv = new CSVReader();
		$csv->useHeaderAsIndex();
		return $csv->data( $path );
	}

	function getCsvPath( $filename ) {
		$path = WT_BASE_PATH . 'configs/' . $filename;
		if ( !file_exists( $path ) ) die( $filename . ' not found' );
		return $path; 
	}
}

/**
 * GET ACCOUNTS TRAIT SECTION
 * =======================================================================================================================
 */

trait GetAccounts {
	function getAccounts( $domain, $apiKey ) {
		$params = $domain == 'all' ? $this->allAccountParams( $apiKey ) : $this->specificDomainParams( $apiKey, $domain );
		$accounts = $this->resellercentralQuery( 'getAccounts', $params ); //see, resellerCentralQuery_trait
		$this->checkResult( $accounts );
		return $accounts;
	}

	function allAccountParams( $apiKey ) {
		return $params = array( 'api_key' => $apiKey );
	}

	function specificDomainParams( $apiKey, $domain ) {
		return array(
			'api_key' => $apiKey,
			'filters' => '%search:' . $domain . '%status:1',
		);
	}

	function checkResult( $accounts ) {
		if ( $accounts['accounts'] == 0 ) die( 'Domain Not Found' );
	}
}

trait SortResults {
	function sortResults( $accounts, $options ) {
		if ( !array_key_exists( 'sort', $options ) ) 
			return $accounts['sql'];

		if ( array_key_exists( 'descending', $options ) ) 
			return $this->sortDescending( $accounts['sql'], $options['sort'] );

		if ( array_key_exists( 'ascending', $options ) )
			return $this->sortAscending( $accounts['sql'], $options['sort'] );
	}

	function sortDescending( $accounts, $sort ) {
		$this->sort = $sort;
		usort( $accounts, function( $a, $b ) {
			if( $a[$this->sort] == $b[$this->sort] ) return 0;
			return $a[$this->sort] < $b[$this->sort] ? 1: -1;
		});
		return $accounts;
	}

	function sortAscending( $accounts, $sort ) {
		$this->sort = $sort;
		usort( $accounts, function( $a, $b ) {
			if( $a[$this->sort] == $b[$this->sort] ) return 0;
			return $a[$this->sort] > $b[$this->sort] ? 1: -1;
		});
		return $accounts;
	}

	function displayResults( $accounts ) {
		$i = 1;
		$p = new TablePrinter(
			['Number', 
			 'Domain', 
			 'User', 
			 'IP Address', 
			 'Location', 
			 'Quota', 
			 'Bandwidth', 
			 'Total Quota', 
			 'Total BW', 
			 'Status' ]
		);
		foreach ( $accounts as $acc ) {
			$p->addRow( 
				$i, 
				$acc['domain'], 
				$acc['username'], 
				$acc['ip'], 
				$acc['location'], 
				$acc['rquota'], 
				$acc['rbandwidth'], 
				$acc['rtotal_disk'], 
				$acc['rtotal_bw'], 
				$acc['status'] 
			);
			$i++;
		}
		$p->output();
	}
}
