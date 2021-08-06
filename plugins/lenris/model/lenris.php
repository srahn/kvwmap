<?php

class LENRIS {
  var $debug;
  var $database;
    
  function __construct($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }
	
	public static function log_error($error){
		echo 'LENRIS Error log: ' . $error . chr(10);
	}
	
	public static function log($msg){
		echo 'LENRIS log: ' . $msg . chr(10);
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
		if (!$ret[0]) {
			$clients = pg_fetch_all($ret[1]);
			return $clients;
		}
		else {
			LENRIS::log_error($ret[1]);
		}
	}
	
	function get_new_nachweise($client){
		$json = file_get_contents($client['url'] . '&go=LENRIS_get_new_nachweise');
		return json_decode($json, true);
	}
	
	function get_changed_nachweise($client){
		$json = file_get_contents($client['url'] . '&go=LENRIS_get_changed_nachweise');
		return json_decode($json, true);
	}
	
	function insert_new_nachweise($client, $nachweise){
		$inserted_nachweise = array();
		foreach ($nachweise as $n) {
			$sql = "
				INSERT INTO
					nachweisverwaltung.n_nachweise
					(flurid, blattnummer, datum, vermstelle, gueltigkeit, link_datei, 
					 format, stammnr, the_geom, fortfuehrung, rissnummer, bemerkungen, 
					 bearbeiter, zeit, erstellungszeit, bemerkungen_intern, geprueft, 
					 art, datum_bis, aenderungsnummer, antragsnummer_alt, rissfuehrer_id, 
					 messungszahlen, bov_ersetzt, zeit_geprueft, freigegeben)
				VALUES
					(" . $n['flurid'] . ", '" . $n['blattnummer'] . "', '" . $n['datum'] . "', '" . $n['vermstelle'] . "', " . $n['gueltigkeit'] . ", '" . $n['link_datei'] . "', 
					'" . $n['format'] . "', '" . $n['stammnr'] . "', '" . $n['the_geom'] . "', " . $n['fortfuehrung'] . ", '" . $n['rissnummer'] . "', '" . $n['bemerkungen'] . "', 
					'" . $n['bearbeiter'] . "', '" . $n['zeit'] . "', '" . $n['erstellungszeit'] . "', '" . $n['bemerkungen_intern'] . "', " . $n['geprueft'] . ", 
					" . $n['art'] . ", " . ($n['datum_bis'] ? "'" . $n['datum_bis'] . "'" : 'NULL') . ", '" . $n['aenderungsnummer'] . "', '" . $n['antragsnummer_alt'] . "', " . ($n['rissfuehrer_id'] ?: 'NULL') . ", 
					" . ($n['messungszahlen'] ?: 'NULL') . ", " . ($n['bov_ersetzt'] ?: 'NULL') . ", " . ($n['zeit_geprueft'] ? "'" . $n['zeit_geprueft'] . "'" : 'NULL') . ", '" . $n['freigegeben'] . "')
				RETURNING id
			";
			$ret = $this->database->execSQL($sql, 4, 0, true);
			if (!$ret[0]) {
				$rs = pg_fetch_assoc($ret[1]);
				$sql = "
					INSERT INTO
						lenris.client_nachweise
						(nachweis_id, client_nachweis_id, client_id, document_last_modified)
					VALUES
						(" . $rs['id'] . ", " . $n['id'] . ", " . $client['client_id'] . ", '" . $n['document_last_modified'] . "')
				";				
				$ret = $this->database->execSQL($sql, 4, 0, true);
				if (!$ret[0]) {
					$sql = "
						INSERT INTO 
							lenris.zu_holende_dokumente
						VALUES
							(" . $client['client_id'] . ", " . $n['id'] . ", '" . $n['link_datei'] . "')
					";
					$ret = $this->database->execSQL($sql, 4, 0, true);
					
					if (!$ret[0]) {
						$inserted_nachweise[] = $n['id'];
					}
				}
				else {
					LENRIS::log_error($ret[1]);
				}
			}
			else {
				LENRIS::log_error($ret[1]);
			}
		}
		$this->confirm_new_nachweise($client, $inserted_nachweise);
	}
	
	function confirm_new_nachweise($client, $inserted_nachweise){
		$response = curl_get_contents($client['url'] . '&go=LENRIS_confirm_new_nachweise&ids=' . implode(',', $inserted_nachweise));
		LENRIS::log('Bestätigung bei ' . $response . ' von ' . count($inserted_nachweise) . ' neuen Nachweisen für Client ' . $client['client_id'] . ' erfolgreich');
	}
	
	function update_changed_nachweise($client, $nachweise){
		foreach ($nachweise as $n) {
			$sql = "
				SELECT 
					* 
				FROM
					lenris.client_nachweise
				WHERE 
					client_id = " . $client['client_id'] . " AND 
					client_nachweis_id = " . $n['id'] . "
			";				
			$ret = $this->database->execSQL($sql, 4, 0, true);
			if (!$ret[0]) {
				$rs = pg_fetch_assoc($ret[1]);
				if ($rs['nachweis_id'] != '') {
					$sql = "
						UPDATE
							nachweisverwaltung.n_nachweise
						SET 
							flurid = " . $n['flurid'] . ", 
							blattnummer = '" . $n['blattnummer'] . "', 
							datum = '" . $n['datum'] . "', 
							vermstelle = '" . $n['vermstelle'] . "', 
							gueltigkeit = " . $n['gueltigkeit'] . ", 
							link_datei = '" . $n['link_datei'] . "', 
							format = '" . $n['format'] . "',
							stammnr = '" . $n['stammnr'] . "', 
							the_geom = '" . $n['the_geom'] . "', 
							fortfuehrung = " . $n['fortfuehrung'] . ", 
							rissnummer = '" . $n['rissnummer'] . "', 
							bemerkungen = '" . $n['bemerkungen'] . "', 
							bearbeiter = '" . $n['bearbeiter'] . "', 
							zeit = '" . $n['zeit'] . "', 
							erstellungszeit = '" . $n['erstellungszeit'] . "', 
							bemerkungen_intern = '" . $n['bemerkungen_intern'] . "', 
							geprueft = " . $n['geprueft'] . ", 
							art = " . $n['art'] . ", 
							datum_bis = " . ($n['datum_bis'] ? "'" . $n['datum_bis'] . "'" : 'NULL') . ", 
							aenderungsnummer = '" . $n['aenderungsnummer'] . "', 
							antragsnummer_alt = '" . $n['antragsnummer_alt'] . "', 
							rissfuehrer_id = " . ($n['rissfuehrer_id'] ?: 'NULL') . ", 
							messungszahlen = " . ($n['messungszahlen'] ?: 'NULL') . ", 
							bov_ersetzt = " . ($n['bov_ersetzt'] ?: 'NULL') . ", 
							zeit_geprueft = " . ($n['zeit_geprueft'] ? "'" . $n['zeit_geprueft'] . "'" : 'NULL') . ", 
							freigegeben = '" . $n['freigegeben'] . "'
						WHERE
							id = " . $rs['nachweis_id'] . "
					";
					$ret = $this->database->execSQL($sql, 4, 0, true);
					if (!$ret[0]) {
						if ($n['document_last_modified'] != $rs['document_last_modified']) {
							$sql = "
								INSERT INTO 
									lenris.zu_holende_dokumente
								VALUES
									(" . $client['client_id'] . ", " . $n['id'] . ", '" . $n['link_datei'] . "')
								ON CONFLICT ON CONSTRAINT pk_zu_holende_dokumente DO UPDATE SET
									dokument = '" . $n['link_datei'] . "'
							";
							$ret = $this->database->execSQL($sql, 4, 0);
							if ($ret[0]){
								LENRIS::log_error($ret[1]);
							}
						}
					}
					else {
						LENRIS::log_error($ret[1]);
					}
				}
				else {
					LENRIS::log_error('Nachweis nicht in Tabelle lenris.client_nachweise gefunden (client_id = ' . $client['client_id'] . ', client_nachweis_id = ' . $n['id'] . ')');
				}
			}
			else {
				LENRIS::log_error($ret[1]);
			}
		}
	}	
	
}

?>