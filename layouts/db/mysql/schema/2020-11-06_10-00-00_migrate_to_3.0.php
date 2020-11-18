<?php

	$sql = "
		SELECT * 
		FROM information_schema.tables
		WHERE table_schema = '".$this->database->dbName."' 
		AND table_name = 'migrations'
	";
	$this->database->execSQL($sql, 0, 0);

	if($this->database->result->num_rows > 0){
		$success = false;
		$zip_file = LAYOUTPATH.'db/mysql/schema/mysql_migrations_upto_3.0.zip';
		$zip_url = 'https://gdi-service.de/public/kvwmap_resources/mysql_migrations_upto_3.0.zip';
		stream_context_set_default(array(
            'ssl'                => array(
            'verify_peer'        => FALSE,
            'verify_peer_name'   => FALSE
            )));
		file_put_contents($zip_file, file_get_contents($zip_url));
		if(file_exists($zip_file)){
			unzip($zip_file, false, false, true);
			unlink($zip_file);
			$success = true;
		}
		$result[0] = !$success;
	}

?>