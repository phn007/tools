<?php
trait CheckSeparator {
	function runCheckSeparator( $merchants ) {
 		$path = $this->separatorTextFilePath();
 		$contents = $this->readSeparatorTextFile( $path );
 		$merchantSeparators = $this->createArrayFromTextFileContent( $contents );
 		$result = $this->checkMerchantSeparator( $merchants, $merchantSeparators);
 		$this->printCheckSeparatorResult( $result );
	}

	function printCheckSeparatorResult( $result ) {
		if ( empty( $result ) ) {
			echo "All Merchants already have Separator\n";
		} else {
			echo "List\n";
			echo "----\n";
			foreach ( $result as $merchant ) {
				echo $merchant;
				echo "\n";	
			}
			die();
		}
	}

	function checkMerchantSeparator( $merchants, $merchantSeparators) {
		$data = array();
		foreach ( $merchants as $merchant ) {
			if ( !array_key_exists( $merchant, $merchantSeparators ) ) {
				$data[] = $merchant;
			}
		}
		return $data;
	}

	function createArrayFromTextFileContent( $contents ) {
		foreach ( $contents as $line ) {
			$arr = explode( '|', $line );
			$data[$arr[0]] = $arr[1];
		}
		return $data;
	}	

	function readSeparatorTextFile( $path ) {
		if ( !file_exists( $path ) ) die( 'separator_category.txt does not exitst' );
		$lines = file( $path );
		return array_map( 'trim', $lines );
	}

	function separatorTextFilePath() {
		return FILES_PATH . 'separator_category.txt';
	}
}