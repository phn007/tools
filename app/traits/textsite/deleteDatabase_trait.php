<?php
trait DeleteDatabase {
	function runDeleteDatabase( $dbs ) {
		$db = new Database();
		$conn = $db->connectDatabase();
		foreach ( $dbs as $dbName ) {
			$db->deleteDatabase( $conn, $dbName );
		}
	}
}