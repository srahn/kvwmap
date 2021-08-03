<?php

class LENRIS {
  var $debug;
  var $database;
    
  function __construct($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }
	
	function get_client_information($client_id = NULL){
		$sql = "
			SELECT 
				client_id, 
				bezeichnung, 
				url, 
				nachweis_primary_attribute, 
				nachweis_secondary_attribute, 
				to_json(nachweis_unique_attributes) as nachweis_unique_attributes
			FROM
				lenris.clients
			" . ($client_id != NULL? ' WHERE client_id = ' . $client_id : '');
		$ret = $this->database->execSQL($sql, 4, 0, true);
		$clients = pg_fetch_all($ret[1]);
		return $clients;
	}
}

?>