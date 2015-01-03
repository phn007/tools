<?php

class DatabaseComponent extends Database
{
	private $conn;
	
	function __construct()
	{
		$this->conn = parent::connectDatabase();
	}
	
	function countTotalProducts( $merchantData )
	{
		$total = 0;
		foreach ( $merchantData as $merchant => $data )
		{
			$num = $this->countProducts( $data['db_name'] );
			$merchantProductNumber[$merchant] = $num; 
			$total = $total + $num;
		}
		return array( 'totalProducts' => $total, 'merchantProductNumber' => $merchantProductNumber );
	}
	
	function countProducts( $dbName )
	{
		$count = null;
		if ( parent::selectDatabase( $this->conn, $dbName ) )
		{
			$count = mysqli_query( $this->conn, "SELECT COUNT(*) AS total FROM products" );
			//mysqli_error( $this->conn );
			$count = mysqli_fetch_object( $count );
			$count = $count->total;
		}
		else
			echo "There is no " . $dbName . ' database' . "\n";
		return $count;
	}
	
	function createSQLString( $productNumber )
	{
		$cols = "id,catalogId,affiliate_url,image_url,keyword,description,category,price,merchant,brand";
		if ( $productNumber > 10000 )
		{
			$round = ceil( $productNumber / 10000 );
			$start = 0;
			$num_limit  = 10000;

			for ( $i = 0; $i < $round; $i++ )
			{
				$limit   = ( $start ) . ', ' . $num_limit;
				$start   = $start + $num_limit;
				$sqls[]  = "SELECT " . $cols . " FROM products LIMIT " . $limit;
			}
		}
		else
		{
			$sqls[]  = "SELECT " . $cols . " FROM products";
		}
		return $sqls;
	}
	
	function getQueryResult( $dbName, $sql )
	{
		if ( ! $this->selectDatabase( $this->conn, $dbName ) )
			die( "Cannot select " . $dbName . ' database' );

		//Query Database
		if ( ! $result = mysqli_query( $this->conn, $sql ) )
			die( "Cannot Query " . $dbName . "Database" );
		return $result;
	}
}