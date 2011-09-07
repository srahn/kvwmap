<?php
class Databaserelation {
	var $host = "localhost";
	var $port = "5432";
	var $dbname = "kvwmapsp";
	var $dbschema = "public";
	var $user = "kvwmap";
	var $password = "kvwmap";

	function connect() {
		$this->conn_string = "host=$this->host port=$this->port dbname=$this->dbname user=$this->user password=$this->password";
		$this->conn = pg_connect($this->conn_string);
		if (!$this->conn) {
		  echo "Konnte keine Verbindung zu $database aufbauen.\n";
		  exit;
		}
		return $this->conn;
	}
	
	function findBySQL($sql) {
		$this->connect();

		$query_result = pg_query($this->conn, $sql);
		
		$data_array =  array();
		$i=0;
		while ($data = pg_fetch_object($query_result)) {
		  $data_array[$i] = $data;
		  $i++;
		}
		
		$this->close();

		return $data_array;
	}
	
	function close() {
		pg_close($this->conn);
	}
}

?>