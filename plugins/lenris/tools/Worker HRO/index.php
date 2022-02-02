<?php

	include("settings.php");

	$dbconn = pg_connect('host=' . $host . ' dbname=' . $dbname . ' user=' . $user . ' password=' . $password)
						or die('Could not connect: ' . pg_last_error());

	pg_set_client_encoding($dbconn, UNICODE);

	function execSQL($sql){
		global $dbconn;
		$result = pg_query($dbconn, $sql) or die('Query failed: ' . pg_last_error());
		return $result;
	}

	function LENRIS_get_all_nachweise(){
		ini_set('memory_limit', '8192M');
		set_time_limit(1800);
    global $select;
		$sql = "
			DELETE FROM 
				n_nachweisaenderungen;
			SELECT
				" . $select . "				
      FROM 
				risse a
			ORDER BY id
			";
		$ret = execSQL($sql);
		if ($nachweise = pg_fetch_all($ret)) {
			foreach ($nachweise as $index => $nachweis) {
				$nachweise[$index]['document_last_modified'] = date('Y-m-d H:i:s', @filemtime($nachweis['link_datei']));
			}
			$json = json_encode($nachweise);
			echo $json;
		}
	}
	
	function LENRIS_get_new_nachweise(){
    global $select;
		$sql = "
			SELECT
				" . $select . "
      FROM 
				risse a JOIN n_nachweisaenderungen as b on a.id = b.id_nachweis
			WHERE
				b.db_action = 'INSERT'
			ORDER BY a.id";
		$ret = execSQL($sql);
		if ($nachweise = pg_fetch_all($ret)) {
			foreach ($nachweise as $index => $nachweis) {
				$nachweise[$index]['document_last_modified'] = date('Y-m-d H:i:s', @filemtime($nachweis['link_datei']));
			}
			$json = json_encode($nachweise);
			echo $json;
		}
	}
	
	function LENRIS_get_changed_nachweise(){
    global $select;
		$sql = "
			SELECT DISTINCT
				" . $select . "
      FROM 
				risse a JOIN n_nachweisaenderungen as b on a.id = b.id_nachweis
			WHERE
				b.db_action = 'UPDATE'
			ORDER BY a.id";
		$ret = execSQL($sql);
		if ($nachweise = pg_fetch_all($ret)) {
			foreach ($nachweise as $index => $nachweis) {
				$nachweise[$index]['document_last_modified'] = date('Y-m-d H:i:s', @filemtime($nachweis['link_datei']));
			}
			$json = json_encode($nachweise);
			echo $json;
		}
	}
	
	function LENRIS_get_deleted_nachweise(){
		$sql = "
			SELECT 
				id_nachweis
      FROM 
				n_nachweisaenderungen 
			WHERE
				db_action = 'DELETE'";
		$ret = execSQL($sql);
		if ($nachweise = pg_fetch_all($ret)) {
			$json = json_encode($nachweise);
			echo $json;
		}
	}	
	
	function LENRIS_confirm_new_nachweise($ids){
		$sql = "
			DELETE FROM 
				n_nachweisaenderungen 
			WHERE 
				id_nachweis IN (" . $ids . ") and db_action = 'INSERT'";
		$ret = execSQL($sql);
		$rows = pg_affected_rows($ret);
		echo $rows;
	}
		
	function LENRIS_confirm_changed_nachweise($ids){
		$sql = "
			DELETE FROM 
				n_nachweisaenderungen 
			WHERE 
				id_nachweis IN (" . $ids . ") and db_action = 'UPDATE'";
		$ret = execSQL($sql);
		$sql = "
			SELECT 
				count(*)
			FROM
				n_nachweisaenderungen 
			WHERE 
				id_nachweis IN (" . $ids . ") and db_action = 'UPDATE'";
		$ret = execSQL($sql);
		$rest = pg_fetch_row($ret);
		echo (substr_count($ids, ',') + 1 - $rest[0]);
	}

	function LENRIS_confirm_deleted_nachweise($ids){
		$sql = "
			DELETE FROM 
				n_nachweisaenderungen 
			WHERE 
				id_nachweis IN (" . $ids . ")";		# alle Einträge löschen, da es noch UPDATE-Einträge geben kann
		$ret = execSQL($sql);
		$sql = "
			SELECT 
				count(*)
			FROM
				n_nachweisaenderungen 
			WHERE 
				id_nachweis IN (" . $ids . ") and db_action = 'DELETE'";
		$ret = execSQL($sql);
		$rest = pg_fetch_row($ret[1]);
		echo (substr_count($ids, ',') + 1 - $rest[0]);
	}		

	function LENRIS_get_document($document){
		global $nachweis_dir;
		if (strpos($document, $nachweis_dir) !== false AND strpos($document, '..') === false AND file_exists($document)) {
			readfile($document);
		}
	}

	switch($_GET['go']){
		case 'LENRIS_confirm_new_nachweise' : {
			LENRIS_confirm_new_nachweise($_GET['ids']);
	  } break;
		
		case 'LENRIS_confirm_changed_nachweise' : {
			LENRIS_confirm_changed_nachweise($_GET['ids']);
	  } break;
		
		case 'LENRIS_confirm_deleted_nachweise' : {
			LENRIS_confirm_deleted_nachweise($_GET['ids']);
	  } break;
		
		case 'LENRIS_get_document' : {
			LENRIS_get_document($_GET['document']);
	  } break;
		
		default : {
			$_GET['go']();
		}
	}
	
	pg_close($dbconn);

?>