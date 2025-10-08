<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen										 #
###################################################################
# Lizenz																													#
#																																 # 
# Copyright (C) 2004	Peter Korduan															 #
#																																 # 
# This program is free software; you can redistribute it and/or	 #
# modify it under the terms of the GNU General Public License as	# 
# published by the Free Software Foundation; either version 2 of	# 
# the License, or (at your option) any later version.						 # 
#																																 #	 
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of	#
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the		#
# GNU General Public License for more details.										#
#																																 #
# You should have received a copy of the GNU General Public			 #
# License along with this program; if not, write to the Free			#
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,	# 
# MA 02111-1307, USA.																						 # 
#																																 #
# Kontakt:																												#
# peter.korduan@gdi-service.de																		#
# stefan.rahn@gdi-service.de																			#
###################################################################
/*
* Klasse synchro
*
* Methods
* export_layer_tables
* import_layer_tables
* export_layer_table_data
* import_layer_table_data
* sync
* 
*/
include_once(CLASSPATH . 'PgObject.php');
class synchro {
	# ToDo: Weitere notwenige Bedingungen für Synchronisierung:
	# - extension uuid-ossp
	const NECESSARY_ID = 'uuid';

	const NECESSARY_FUNCTIONS = array(
		'mobile_delete_images',
		'mobile_download_image',
		'mobile_upload_image',
		'Daten_Export'
	);

	const NECESSARY_ATTRIBUTES = array(
		'uuid' => array(
			'type' => 'uuid'
		),
		'created_at' => array(
			'type' => 'timestamp without time zone',
			'form_element_type' => 'Time'
		),
		'updated_at_client' => array(
			'type' => 'timestamp without time zone',
			'form_element_type' => 'Time'
		),
		'updated_at_server' => array(
			'type' => 'timestamp without time zone',
			'form_element_type' => 'Time'
		),
		'user_name' => array(
			'type' => 'text',
			'form_element_type' => 'User'
		),
		'status' => array(
			'type' => 'integer',
			'nullable' => false
		),
		'version' => array(
			'type' => 'integer',
			'nullable' => false
		),
		'bilder' => array(
			'optional' => true,
			'pending' => 'bilder_updated_at'
		),
		'bilder_updated_at' => array(
			'optional' => true,
			'pending' => 'bilder'
		)
	);
	public $Stelle;
	public $user;
	public $database;
	public $count;
	public $commands;
	public $trans_id;
	public $result;
	public $newcount;
	public $oldcount;
	public $already_exported_layers;
	public $already_imported_layers;
	public $imagecount;

	function __construct($stelle, $user, $database) {
		$this->Stelle = $stelle;
		$this->user = $user;
		$this->database = $database;
	}

	function export_layer_tables($export_layerset, $formvars) {
		# geht bisher nur mit Layern mit einer Tabelle
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->count = 0;
		for ($i = 0; $i < count($export_layerset); $i++) {
			$this->commands = array();
			$layerdb = $mapDB->getlayerdatabase($export_layerset[$i]['layer_id'], $this->Stelle->pgdbhost);
			$attributes = $mapDB->read_layer_attributes($export_layerset[$i]['layer_id'], $layerdb, NULL);
			$currenttime = date('Y-m-d_H-i-s', time());
			$this->trans_id[$i] = $this->user->id . "_" . $currenttime . "_" . $export_layerset[$i]['layer_id'];
			$where = " WHERE ST_WITHIN(st_transform(" . $attributes['all_table_names'][0] . "." . $attributes['the_geom'] . ", " . $this->user->rolle->epsg_code . "), st_geomfromtext('" . $formvars['newpathwkt'] . "', " . $this->user->rolle->epsg_code . "))";
			if ($this->export_layer_table_data($mapDB, $this->trans_id[$i], $attributes, $layerdb, $export_layerset[$i]['layer_id'], $where, $formvars['leeren'], $formvars['mitbildern'], $formvars['username'], $formvars['passwort'])) {
				$this->commands[] = POSTGRESBINPATH . "psql -U " . $this->database->user . " -f " . SYNC_PATH . $this->trans_id[$i] . ".sql " . $this->database->dbName;
				$this->commands = array_reverse($this->commands);		# Die Reihenfolge der Datenimporte muss umgedreht werden, damit erst die übergeordneten Tabellen eingespielt werden und dann die abhängigen (ansonsten könnte es sein, dass abhängige Tabellen auf Grund eines Delete Cascade-Constraints wieder gelöscht werden)
				foreach ($this->commands as $command) {
					exec($command, $output, $ret);
				}
			}
			if ($ret == 0) {
				$this->result['count'] = $this->count;
			}
		}
	}

	function import_layer_tables($import_layerset, $formvars) {
		# geht bisher nur mit Layern mit einer Tabelle
		$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
		$this->newcount = 0;
		$this->oldcount = 0;
		for ($i = 0; $i < count($import_layerset); $i++) {
			$this->commands = array();
			$layerdb = $mapDB->getlayerdatabase($import_layerset[$i]['layer_id'], $this->Stelle->pgdbhost);
			$attributes = $mapDB->read_layer_attributes($import_layerset[$i]['layer_id'], $layerdb, NULL);
			if ($this->import_layer_table_data($mapDB, $attributes, $layerdb, $import_layerset[$i]['layer_id'], $import_layerset[$i]['name'], $formvars['mitbildern'], $formvars['username'], $formvars['passwort'])) {
				$this->commands[] = POSTGRESBINPATH . "psql -U " . $this->database->user . " -f " . SYNC_PATH . $import_layerset[$i]['layer_id'] . ".sql " . $this->database->dbName;
				$this->commands = array_reverse($this->commands);		# Die Reihenfolge der Datenimporte muss umgedreht werden, damit erst die übergeordneten Tabellen eingespielt werden und dann die abhängigen (ansonsten könnte es sein, dass abhängige Tabellen auf Grund eines Delete Cascade-Constraints wieder gelöscht werden)
				foreach ($this->commands as $command) {
					exec($command, $output, $ret);
				}
			}
			if ($ret == 0) {
				$this->result['newcount'] = $this->newcount;
				$this->result['oldcount'] = $this->oldcount;
			}
		}
	}

	function exec_delta($sql_set, $sql) {
		$res = $this->database->execSQL($sql_set . ' ' . $sql, 0, 1, true);
		$this->err_msg = 'Fehler bei der Ausführung des originalen Deltas. Meldung: ' . $res[1];
		$this->msg = $sql;
		return $res['success'];
	}

	function exec_adjusted_delta($sql_set, $adjusted_sql) {
		$res = $this->database->execSQL($sql_set . ' ' . $adjusted_sql, 0, 1, true);
		$this->err_msg = 'Fehler bei der Ausführung des korrigierten Delta SQL: ' . $adjusted_sql . ' Meldung: ' . $res[1];
		$this->msg = $adjusted_sql;
		return $res['success'];
	}

	function export_layer_table_data($mapDB, $trans_id, $attributes, $layerdb, $layer_id, $where, $truncate, $withimages, $username, $passwort) {
		$this->already_exported_layers[] = $layer_id;
		$sql = "UPDATE " . $attributes['all_table_names'][0] . " SET lock = '" . $trans_id . "|'||oid " . $where . " AND lock IS NULL";
		#echo $sql.'<br>';
		$ret = $layerdb->execSQL($sql, 4, 0);
		$sql = "SELECT * FROM " . $attributes['all_table_names'][0];
		$sql .= $where;
		$sql .= " AND '" . $trans_id . "' = split_part(lock, '|', 1)";			# nur die mit dem entsprechenden Lock abfragen
		#echo $sql;
		$ret = $layerdb->execSQL($sql, 4, 0);
		if (pg_num_rows($ret[1]) > 0) {
			$fp = fopen(SYNC_PATH . $trans_id . ".sql", "a");
			fwrite($fp, "SET datestyle TO 'German';" . chr(10));
			if ($truncate == 'on') {
				fwrite($fp, "DELETE FROM " . $layerdb->schema . "." . $attributes['all_table_names'][0] . ";" . chr(10));
			}
			fwrite($fp, "COPY " . $layerdb->schema . "." . $attributes['all_table_names'][0] . " FROM STDIN WITH DELIMITER AS '~' CSV;" . chr(10));

			# abhängige Layer auch exportieren, hier erstmal die Verknüpfungsattribute für jeden verknüpften Layer zusammen sammeln
			$j = 0;
			for ($i = 0; $i < count($attributes['name']); $i++) {
				if (in_array($attributes['form_element_type'][$i], array('SubFormEmbeddedPK', 'SubFormPK', 'SubFormFK'))) {
					$options = explode(';', $attributes['options'][$i]); // get_options
					$subform = explode(',', $options[0]);
					if (!in_array($subform[0], $this->already_exported_layers)) {
						$subform_layer[$j]['id'] = $subform[0];
						if ($attributes['form_element_type'][$i] == 'SubFormEmbeddedPK') $minus = 1;
						else $minus = 0;
						for ($k = 1; $k < count($subform) - $minus; $k++) {
							$subform_layer[$j]['subform_attribute'][$subform[$k]] = array();
						}
						$j++;
					}
				}
			}
			$count = 0;
			while ($rs = pg_fetch_assoc($ret[1])) {
				$count++;
				$this->count++;
				for ($k = 0; $k < count($rs); $k++) {
					if ($withimages == 'on' and $attributes['form_element_type'][key($rs)] == 'Dokument' and $rs[key($rs)] != '') { # Bilder vom Server holen und auf lokalem Server speichern
						$this->imagecount++;
						$image_string = file_get_contents($attributes['options'][key($rs)] . $rs[key($rs)] . '&username=' . $username . '&passwort=' . $passwort);
						$name_array = explode('.', $rs[key($rs)]);
						$datei_erweiterung = array_pop($name_array);
						$filename = CUSTOM_IMAGE_PATH . rand(10000, 1000000) . '.' . $datei_erweiterung;
						$new_image = fopen($filename, 'w');
						fwrite($new_image, $image_string);
						fclose($new_image);
						$rs[key($rs)] = $filename;
					}
					if ($k > 0) {
						fwrite($fp, '~');
					}
					fwrite($fp, $rs[key($rs)]);
					# abhängige Layer auch exportieren, hier dann die Werte der Verknüpfungsattribute zu einzelnen WHERE-Bedingungen zusammen bauen
					for ($j = 0; $j < count($subform_layer); $j++) {
						if (is_array($subform_layer[$j]['subform_attribute'][key($rs)])) {
							$subform_layer[$j]['where'][$count] .= key($rs) . " = '" . $rs[key($rs)] . "' AND ";
						}
					}
					next($rs);
				}
				fwrite($fp, chr(10));
			}
			fwrite($fp, "\." . chr(10));
			fclose($fp);
			# abhängige Layer auch exportieren, hier wird diese Funktion für jeden verknüpften Layer rekursiv mit der zusammengesetzten WHERE-Bedingung aufgerufen
			for ($j = 0; $j < count($subform_layer); $j++) {
				$subform_layer[$j]['where_gesamt'] = " WHERE (" . implode('1=1 OR ', $subform_layer[$j]['where']) . " 1=1)";
				$attributes = $mapDB->read_layer_attributes($subform_layer[$j]['id'], $layerdb, NULL);
				$currenttime = date('Y-m-d_H-i-s', time());
				$trans_id = $this->user->id . "_" . $currenttime . "_" . $subform_layer[$j]['id'];
				if ($this->export_layer_table_data($mapDB, $trans_id, $attributes, $layerdb, $subform_layer[$j]['id'], $subform_layer[$j]['where_gesamt'], $truncate, $withimages, $username, $passwort)) {
					$this->commands[] = POSTGRESBINPATH . "psql -U " . $this->database->user . " -f " . SYNC_PATH . $trans_id . ".sql " . $this->database->dbName;
				}
			}
			return true;
		}
		return false;
	}

	function import_layer_table_data($mapDB, $attributes, $layerdb, $layer_id, $layername, $withimages, $username, $passwort) {
		$this->already_imported_layers[] = $layer_id;
		$layername = $layername ?? $layer_id;
		# erst alle neuen Datensätze
		$sql = "SELECT * FROM " . $attributes['all_table_names'][0];
		//for($j = 1; $j < count($attributes['all_table_names']); $j++) {
		//$sql.= ", ".$attributes['all_table_names'][$j];
		//}
		$sql .= " WHERE lock IS NULL";
		#echo $sql;
		$ret = $layerdb->execSQL($sql, 4, 0);

		$fp = fopen(SYNC_PATH . $layer_id . ".sql", "w");
		fwrite($fp, "SET datestyle TO 'German';" . chr(10));
		fwrite($fp, "COPY " . $layerdb->schema . "." . $attributes['all_table_names'][0] . " FROM STDIN WITH DELIMITER AS '~' CSV;" . chr(10));
		$i = 0;
		while ($rs = pg_fetch_assoc($ret[1])) {
			$this->newcount++;
			for ($k = 0; $k < count($rs); $k++) {
				if ($withimages == 'on' and $attributes['form_element_type'][key($rs)] == 'Dokument' and $rs[key($rs)] != '') {			# Bilder vom Server holen und auf lokalem Server speichern
					$i++;
					$image_string = file_get_contents($attributes['options'][key($rs)] . $rs[key($rs)] . '&username=' . $username . '&passwort=' . $passwort);
					$name_array = explode('.', $rs[key($rs)]);
					$datei_erweiterung = array_pop($name_array);
					$filename = CUSTOM_IMAGE_PATH . rand(10000, 1000000) . '.' . $datei_erweiterung;
					$new_image = fopen($filename, 'w');
					fwrite($new_image, $image_string);
					fclose($new_image);
					$rs[key($rs)] = $filename . '&original_name=' . $layername . '_' . $i . '.' . $datei_erweiterung;
				}
				if ($k > 0) {
					fwrite($fp, '~');
				}
				fwrite($fp, $rs[key($rs)]);
				next($rs);
			}
			fwrite($fp, chr(10));
		}
		fwrite($fp, "\." . chr(10));

		# dann die bearbeiteten
		$sql = "SELECT * FROM " . $attributes['all_table_names'][0];
		//for($j = 1; $j < count($attributes['all_table_names']); $j++) {
		//	$sql.= ", ".$attributes['all_table_names'][$j];
		//}
		$sql .= " WHERE lock IS NOT NULL";
		#echo $sql;
		$ret = $layerdb->execSQL($sql, 4, 0);
		$fp = fopen(SYNC_PATH . $layer_id . ".sql", "a");
		while ($rs = pg_fetch_assoc($ret[1])) {
			if ($rs['lock'] != 'bereits übertragen') {
				$this->oldcount++;
				$trans_id = $rs['lock'];
				fwrite($fp, "UPDATE " . $layerdb->schema . "." . $attributes['all_table_names'][0] . " SET ");
				for ($k = 0; $k < count($rs); $k++) {
					if ($withimages == 'on' and $attributes['form_element_type'][key($rs)] == 'Dokument' and $rs[key($rs)] != '') {			# Bilder vom Server holen und auf lokalem Server speichern
						$i++;
						$image_string = file_get_contents($attributes['options'][key($rs)] . $rs[key($rs)] . '&username=' . $username . '&passwort=' . $passwort);
						$name_array = explode('.', $rs[key($rs)]);
						$datei_erweiterung = array_pop($name_array);
						$filename = CUSTOM_IMAGE_PATH . rand(10000, 1000000) . '.' . $datei_erweiterung;
						$new_image = fopen($filename, 'w');
						fwrite($new_image, $image_string);
						fclose($new_image);
						$rs[key($rs)] = $filename . '&original_name=' . $layername . '_' . $i . '.' . $datei_erweiterung;
					}
					if ($k > 0) {
						fwrite($fp, ',');
					}
					if (key($rs) == 'lock' or $rs[key($rs)] == '') {
						fwrite($fp, key($rs) . "= NULL");							# lock wieder freigeben
					} else {
						fwrite($fp, key($rs) . "='" . addslashes($rs[key($rs)]) . "'");
					}
					next($rs);
				}
				fwrite($fp, " WHERE lock = '" . $trans_id . "';" . chr(10));
			}
		}
		#die Datensätze in der Spalte lock als bereits übertragen kennzeichnen
		$sql = "UPDATE " . $attributes['all_table_names'][0] . " SET lock = 'bereits übertragen'";
		#echo $sql;
		$ret1 = $layerdb->execSQL($sql, 4, 0);

		# dann die Datensätze löschen, die exportiert wurden aber nicht wieder zurückgespielt
		if ($trans_id != '') {
			$t_id = explode('|', $trans_id);
			fwrite($fp, "DELETE FROM " . $layerdb->schema . "." . $attributes['all_table_names'][0] . " WHERE '" . $t_id[0] . "' = split_part(lock, '|', 1);" . chr(10));
		}
		fclose($fp);

		# abhängige Layer auch importieren
		for ($i = 0; $i < count($attributes['name']); $i++) {
			if (in_array($attributes['form_element_type'][$i], array('SubFormEmbeddedPK', 'SubFormPK', 'SubFormFK'))) {
				$options = explode(';', $attributes['options'][$i]); // get_options
				$subform = explode(',', $options[0]);
				if (!in_array($subform[0], $this->already_imported_layers)) {
					$subform_layer = $subform[0];
					$attributes = $mapDB->read_layer_attributes($subform_layer, $layerdb, NULL);
					if ($this->import_layer_table_data($mapDB, $attributes, $layerdb, $subform_layer, '', $withimages, $username, $passwort)) {
						$this->commands[] = POSTGRESBINPATH . "psql -U " . $this->database->user . " -f " . SYNC_PATH . $subform_layer . ".sql " . $this->database->dbName;
					}
				}
			}
		}
		return true;
	}

	/**
	 * Die Funktion führt folgende Schritte innerhalb einer Transaktion aus:
	 * - Fragt ob die angefragte Syncronisation schon mal abgearbeitet wurde
	 * - Wenn ja:
	 * 	- Legt eine neue Syncronisation in der Datenbank an mit
	 * 		$client_id, $user_name, $pull_from_version, $pull_to_version und $push_from_version.
	 * 	- Trägt alle mitgesendeten Änderungen ($client_deltas) in die Datenbank ein
	 * 	- Trägt die neue letzte Version $push_version_to in die aktuelle Syncronisation ein.
	 * - Ansonsten immer:
	 * 	- Fragt alle Änderungen (deltas) am Layer $layer_id von $pull_version_from bis $pull_version_to ab.
	 * 	- Fragt die Syncronisation ab
	 * 	- liefert die Deltas und Sync-Daten zurück
	 */
	function sync($client_id, $username, $schema_name, $table_name, $client_time, $last_client_version, $client_deltas) {
		$this->database->gui->deblog->write('client_id: ' . $client_id);
		$this->database->gui->deblog->write('username: ' . $username);
		$this->database->gui->deblog->write('schema_name: ' . $schema_name);
		$this->database->gui->deblog->write('table_name: ' . $table_name);
		$this->database->gui->deblog->write('client_time: ' . $client_time);
		$this->database->gui->deblog->write('last_client_version: ' . $last_client_version);
		$this->database->gui->deblog->write('client_deltas: ' . print_r($client_deltas, true));
		$pull_from_version = $last_client_version + 1;
		$client_pushed_deltas = count($client_deltas->rows) > 0;
		$log = '';

		if (!$client_pushed_deltas) {
			# Because client sent no deltas, request for new deltas starting with pull_from_version in server database before creating a synchronization in sync table
			$sql = "
				SELECT
					*
				FROM
					" . $schema_name . "." . $table_name . "_deltas
				WHERE
					version >= " . $pull_from_version . "
			";
			$res = $this->database->execSQL($sql, 0, 1, true);
			if ($res[0]) {
				$result = array(
					'success' => false,
					'err_msg' => 'Fehler bei der Abfrage der Deltas auf dem Server. ' . $res[1]
				);
				return $result;
			}
			if (pg_num_rows($res[1]) == 0) {
				# There are also no new deltas on client side, send a result with no new data and push_to_version = $last_client_version
				$result = array(
					'success' => true,
					'syncData' => array(array(
						'client_id' => $client_id,
						'client_time' => $client_time,
						'schema_name' => $schema_name,
						'table_name' => $table_name,
						'pull_from_version' => null,
						'pull_to_version' => null,
						'push_from_version' => null,
						'push_to_version' => $last_client_version
					)),
					'deltas' => array(),
					'log'	=> 'Es wurden keine Änderungen vom Client auf den Server übertragen und es gab auch keine Änderungen vom Server zu holen!'
				);
				return $result;
			}
		}

		/*
		# Frage ab ob die Syncronisation schon mal abgefragt wurde
		$sql = "
			SELECT
				count(*) num_sync
			FROM
				syncs
			WHERE
				client_id = '" . $client_id . "' AND
				username = '" . $username . "' AND
				schema_name = '" . $schema_name . "' AND
				table_name = '" . $table_name . "' AND
				pull_from_version = " . $pull_from_version . "
		";
		#echo '<br>Sql: ' . $sql;
		$log = $sql;
		$res = $this->database->execSQL($sql, 0, 1, true);
		if ($res[0]) {
			# Liefer error message
			$result = array(
				'success' => false,
				'err_msg' => 'Fehler bei der Syncronisation auf dem Server. Es konnte nicht abgefragt werden ob die angefragte Syncronisation schon einmal auf dem Server durchgeführt wurde. ' . $res[1]
			);
			return $result;
		}


		$rs = pg_fetch_array($res[1]);
		if ($rs['num_sync'] == 0) {
		*/
		if ($pull_from_version > 1) {
			$sql = "
				START TRANSACTION;
			";

			# Lege Datensatz für Synchronisation auf dem Server an und trage ein:
			# gepullt von bis und gepusht von
			$sql .= "
				INSERT INTO syncs (client_id, username, client_time, schema_name, table_name, pull_from_version, pull_to_version, push_from_version)
				VALUES (
					'" . $client_id . "',
					'" . $username . "',
					'" . $client_time . "',
					'" . $schema_name . "',
					'" . $table_name . "',
					" . $pull_from_version  . ",
					(SELECT coalesce(max(version), 1) FROM " . $schema_name . "." . $table_name . "_deltas),
					(SELECT coalesce(max(version), 1) FROM " . $schema_name . "." . $table_name . "_deltas)" . ($client_pushed_deltas ? " + 1" : '') . "
				);
			";

			# Trage gepushte Datensätze ein falls rows vorhanden sind
			$sql .= implode(
				'; ',
				array_map(
					function ($row) {
						return $row->sql;
					},
					$client_deltas->rows
				)
			) . ';';

			# Trage gepusht bis ein
			$sql .= "
				UPDATE syncs
				SET
					push_to_version = (SELECT coalesce(max(version), 1) FROM " . $schema_name . "." . $table_name . "_deltas)
				WHERE
					client_id = '" . $client_id . "' AND
					client_time = '" . $client_time . "' AND
					username = '" . $username . "' AND
					schema_name = '" . $schema_name . "' AND
					table_name = '" . $table_name . "' AND
					pull_from_version = " . $pull_from_version . ";
			";

			# Schließe Transaktion ab
			$sql .= "
				COMMIT;
			";
			$log .= $sql;
			$this->database->gui->deblog->write('Sync with sql: ' . $sql);
			#echo '<br>Sql: ' . $sql;
			$this->database->debug->write("SQL der Transaktion für die Synchronisation: " . $sql, 4);
			$res = $this->database->execSQL($sql, 0, 1, true);
			if ($res[0]) {
				$result = array(
					'success' => false,
					'err_msg' => 'Fehler bei der Synchronisation auf dem Server. ' . $res[1]
				);
				$this->database->gui->deblog->write('Fehler mit result: ' . $res[1]);
				return $result;
			}
		}

		# Frage deltas von pull_from bis pull_to ab
		$sql = "
			SELECT
				*
			FROM
				" . $schema_name . "." . $table_name . "_deltas
			WHERE
				version >= " . $pull_from_version . " AND
				version <= (
					SELECT
						pull_to_version
					FROM
						syncs
					WHERE
						client_id = '" . $client_id . "' AND
						client_time = '" . $client_time . "' AND
						username = '" . $username . "' AND
						schema_name = '" . $schema_name . "' AND
						table_name = '" . $table_name . "' AND
						pull_from_version = " . $pull_from_version . "
				)
			ORDER BY version;
		";
		$log .= $sql;
		$this->database->gui->deblog->write('Frage deltas vom Server ab mit sql: ' . $sql);
		#echo '<br>Sql: ' . $sql;
		$res = $this->database->execSQL($sql, 0, 1, true);
		if ($res[0]) {
			$result = array(
				'success' => false,
				'err_msg' => 'Fehler bei der Synchronisation auf dem Server. ' . $res[1]
			);
			$this->database->gui->deblog->write('Fehler mit result: ' . $res[1]);
			return $result;
		}
		$deltas = array();
		while ($rs = pg_fetch_assoc($res[1])) {
			$deltas[] = $rs;
		}

		# Frage Daten der Syncronisation ab
		$sql = "
			SELECT
				*
			FROM
				syncs
			WHERE
				client_id = '" . $client_id . "' AND
				client_time = '" . $client_time . "' AND
				username = '" . $username . "' AND
				schema_name = '" . $schema_name . "' AND
				table_name = '" . $table_name . "' AND
				pull_from_version = " . $pull_from_version . "
		";
		$log .= $sql;
		$this->database->gui->deblog->write('Frage Daten der Synchronisation ab: ' . $sql);
		#echo '<br>Sql: ' . $sql;
		$res = $this->database->execSQL($sql, 0, 1, true);
		if ($res[0]) {
			# Liefer error message
			$result = array(
				'success' => false,
				'err_msg' => 'Fehler bei der Syncronisation auf dem Server. ' . $res[1]
			);
			$this->database->gui->deblog->write('Fehler bei Abfrage: ' . $res[1]);
			return $result;
		}
		$sync_data = array();
		while ($rs = pg_fetch_assoc($res[1])) {
			$sync_data[] = $rs;
		}

		# Liefer deltas und syncro data ab
		$result = array(
			'success' => true,
			'syncData' => $sync_data,
			'deltas' => $deltas,
			'log'	=> $log
		);
		$this->database->gui->deblog->write('Result von sync: ' . print_r($result, true));
		return $result;
	}

	/**
	 * Die Funktion syncronisiert im Gegensatz zu sync alle sync-Layer der aktuellen Stelle
	 */
	function sync_all($client_id, $username, $client_time, $last_delta_version, $client_deltas, $sync_layers) {
		// $this->database->gui->mobile_log->write('client_id: ' . $client_id);
		// $this->database->gui->mobile_log->write('client_time: ' . $client_time);
		// $this->database->gui->mobile_log->write('username: ' . $username);
		// $this->database->gui->mobile_log->write('client_deltas: ' . print_r($client_deltas, true));
		$log = '';

		// Führe die Deltas einzeln aus wenn die Berechtigung dazu besteht und sammel die fehlerhaften ein
		$failed_deltas = array();
		foreach ($client_deltas->rows as $row) {
			if ($this->sync_allowed($row, $sync_layers)) {
				$later_update = $this->find_later_update($row);
				if (!$later_update['success']) {
					return $later_update;
				}
				if ($row->action == 'update' AND $later_update['exists']) {
					// Frage alle Deltas ab, die jünger sind als das Delta vom Client
					// Wenn welche gefunden wurden, werden diese nachdem die Deltas vom Client ausgeführt auch noch einmal ausgeführt, damit die
					// jüngeren Änderungen nach den Änderungen vom Client ausgeführt werden auch wenn dadurch vielleicht Änderungen vom Client wieder
					// überschrieben werden.
					$sql = "
						SELECT
							*
						FROM
							deltas_all
						WHERE
							version > " . $last_delta_version . " AND
							action = 'update' AND
							action_time > '" . $row->action_time . "' AND
							uuid = '" . $row->uuid . "'
						ORDER BY version;
					";
					$log .= '<br>Abfrage der neuen Detals mit version > ' . $last_delta_version;
					$res = $this->database->execSQL($sql, 0, 1, true);
					if ($res[0]) {
						$failed_deltas[] = $row;
						$result = array(
							'success' => false,
							'last_delta_version' => $last_delta_version,
							'deltas' => $deltas,
							'failed_deltas' => $failed_deltas,
							'message'	=> 'Fehler bei der Abfrage der Deltas die jünger sind als das Delta vom Client: ' . $sql . ' Fehler: ' . $res[1]
						);
						return $result;
					}
					$later_deltas = [];
					while ($rs = pg_fetch_assoc($res[1])) {
						$later_deltas[] = $rs;
					}
				}

				// Ausführen des Deltas vom Client
				$log .= '<br><br>Deltas vom Client: ';
				$sql_set = "
					SET public.client_id='" . $client_id . "';
					SET public.action_time='" . $row->action_time . "';
					SET public.uuid='" . $row->uuid . "';
				";
				$log .= $sql_set . $row->sql;
				#echo '<br>Sql: ' . $sql_set . $sql;
				if (
					!$this->exec_delta($sql_set, $row->sql) AND
					!$this->ignorable_by_uuid($row) AND
					!$this->exec_adjusted_delta($sql_set, $this->adjust_sql($row))
				) {
					$failed_deltas[] = $row;
					$result = array(
						'success' => false,
						'last_delta_version' => $last_delta_version,
						'deltas' => $deltas,
						'failed_deltas' => $failed_deltas,
						'message'	=> 'Fehler bei der Ausführung des Deltas: ' . $row->sql . ' Fehler: ' . $this->err_msg
					);
					return $result;
				}
				else {
					$log .= $this->msg;
				}

				// Ausführen der jüngeren Deltas
				$log .= '<br><br>Ausführen der jüngeren Deltas vom Server.';
				foreach ($later_deltas AS $later_delta) {
					$log .= '<br>' . $later_delta;
					$res = $this->database->execSQL($later_delta['sql'], 0, 1, true);
					if ($res[0]) {
						$result = array(
							'success' => false,
							'last_delta_version' => $last_delta_version,
							'deltas' => $deltas,
							'failed_deltas' => $failed_deltas,
							'message'	=> 'Fehler bei der Ausführung des jüngeren Deltas ' . $later_delta['sql'] . ' Fehler: ' . $res[1]
						);
						return $result;
					}
				}
			}
			else {
				$failed_deltas[] = $row;
				$result = array(
					'success' => false,
					'last_delta_version' => $last_delta_version,
					'deltas' => $deltas,
					'failed_deltas' => $failed_deltas,
					'message'	=> 'Fehler bei Delta: ' . print_r($row, true) . ' darf auf dem Server nicht ausgeführt werden.'
				);
				return $result;
			}
		}

		// Frage deltas größer last_delta_version an ab.
		// aber nicht die inserts mit der client_id dieser Sync-Action
		$log .= '<br><br>Abfrage der Deltas die nach dem Syncen noch reingekommen sind.';
		$sql = "
			SELECT
				*
			FROM
				deltas_all
			WHERE
				version > " . $last_delta_version . " AND
				NOT (action = 'insert' OR COALESCE(client_id, '') = '" . $client_id . "')
			ORDER BY version;
		";
		$log .= $sql;
		#echo 'Deltas aus deltas_all abfragen mit sql: ' . $sql;
		$res = $this->database->execSQL($sql, 0, 1, true);
		$deltas = array();
		$pull_to_version = 0;
		while ($rs = pg_fetch_assoc($res[1])) {
			if (intval($rs['version']) > $pull_to_version) {
				$pull_to_version = intval($rs['version']);
			}
			$deltas[] = $rs;
		}
		$log .= '<br>Deltas die nach dem syncen noch in die Datenbank gekommen sind:<br>' . implode('<br>', array_map(function($delta) { return $delta['sql']; }, $deltas));

		if ($pull_to_version > 0) {
			$last_delta_version = $pull_to_version;
		}
		else {
			$sql = "
				SELECT
					max(version) AS last_delta_version
				FROM
					deltas_all
			";
			$log .= '<br><br>Abfrage der höchsten deltas Version: ' . $sql;
			$ret = $this->database->execSQL($sql, 4, 0);
			if ($ret[0]) {
				return array(
					"success" => false,
					'last_delta_version' => $last_delta_version,
					'deltas' => $deltas,
					'failed_deltas' => $failed_deltas,
					'message' => "Fehler Die maximale Deltas-Version konnte nicht abgefragt werden! SQL: " . $sql . ' Meldung: ' . $ret['msg']
				);
			}
			$rs = pg_fetch_assoc($ret['query']);
			$last_delta_version = ($rs === false ? 0 : $rs['last_delta_version']);
		}

		# Lege Datensatz für Synchronisation auf dem Server an und trage ein:
		# gepullt von bis und gepusht von
		$sql = "
			INSERT INTO syncs_all (client_id, username, client_time, pull_from_version, pull_to_version)
			VALUES (
				'" . $client_id . "',
				'" . $username . "',
				'" . $client_time . "',
				" . ($last_delta_version + 1) . ",
				" . $pull_to_version . "
			);
		";
		$log .= 'Eintragung der Sync-Daten auf dem Server.';
		$res = $this->database->execSQL($sql, 0, 1, true);
		if ($res[0]) {
			$result = array(
				'success' => false,
				'last_delta_version' => $last_delta_version,
				'deltas' => $deltas,
				'failed_deltas' => $failed_deltas,
				'message' =>  'Fehler beim Eintragen des Sync-Vorgangs in syncs_all mit sql: ' . $sql . ' Fehler: ' . $res[1]
			);
			return $result;
		}

		# Liefer deltas und syncro data ab
		$result = array(
			'success' => true,
			'last_delta_version' => $last_delta_version,
			'deltas' => $deltas,
			'failed_deltas' => $failed_deltas,
			'message'	=> $log
		);
		return $result;
	}

	/**
	 * Searches for changes of features with $row->uuid in table that has been done later than $row->action_time
	 * If found some, changes on server has been done after changes on client side.
	 * It returns success = false in case of error.
	 * Returns exists = true if later updates exists. 
	 * @return Array(boolean success = false, string err_msg)
	 * @return Array(boolean success = true, boolean exists)
	 */
	function find_later_update($row) {
		$sql = "
			SELECT
				updated_at_server
			FROM
				" . $row->schema_name . "." . $row->table_name . "
			WHERE
				uuid = '" . $row->uuid . "'::uuid AND
				(
					updated_at_server > '" . $row->action_time . "' OR
					updated_at_client > '" . $row->action_time . "'
				)
		";
		$ret = $this->database->execSQL($sql, 0, 1, true);
		if ($ret[0]) {
			$result = array(
				'success' => false,
				'exists' => false,
				'msg' => 'Fehler beim Abfragen eines späteren Updates. ' . $ret[1]
			);
			$this->database->gui->mobile_log->write($err_msg);
			$this->database->gui->mobile_err->write($err_msg);
			return $result;
		}
		if (pg_num_rows($ret[1]) === 0) {
			return array(
				'success' => true,
				'exists' => false
			);
		} else {
			$rs = pg_fetch_assoc($ret[1]);
			return array(
				'success' => true,
				'exists' => true
			);
		}
	}

	function ignorable_by_uuid($row) {
		$pg_obj = new PgObject($this->database->gui, $row->schema_name, $row->table_name);
		$uuid_exists = $pg_obj->exists("uuid = '" . $row->uuid . "'::uuid");
		if ($row->action === 'insert' AND $uuid_exists) {
			$this->msg = $row->sql . ' SQL ignoriert weil uuid: ' . $row->uuid . ' in Tabelle ' . $row->schema_name. '.' . $row->table_name . ' schon existiert.';
			return true; // Ignorable because feature allready exists.
		}
		if (in_array($row->action, array('update', 'delete')) AND !$uuid_exists) {
			$this->msg = $row->sql . ' SQL ignoriert weil uuid: ' . $row->uuid . ' in Tabelle ' . $row->schema_name. '.' . $row->table_name . ' nicht mehr existiert.';
			return true; // Ignorable because feature not exists.
		}
		$this->err_msg = $row->sql . ' SQL mit uuid: ' . $row->uuid . ' in Tabelle: ' . $row->schema_name. '.' . $row->table_name . ' kann nicht ignoriert werden.';
		return false;
	}

	/**
	 * Function check if loged in user is allowed to execute the $delta in current stelle
	 * Geprüft wird ob es Layer mit passenden insert und delete Rechten in $sync_layers gibt, bei denen schema und maintable des Layers
	 * mit schema_name und table_name des Deltas übereinstimmen.
	 * Ob die Rechte zum Ändern der einzelnen Attribute bestehen wird hier nicht geprüft!
	 * @param Delta $delta - Das Delta welches geprüft werden soll.
	 * @param Layer2Stelle[] $sync_layers - Die zur Stelle gehörenden Layer mit der Eigenschaft sync = '1' und editable = 1, abgefragt mit Layer2Stelle::find_sync_layers(GUI, stelle_id)
	 */
	function sync_allowed($delta, $sync_layers) {
		return count(array_filter(
			$sync_layers,
			function ($layer) use ($delta) {
				$privileg = intval($layer->get('privileg'));
				return (
					$layer->get('schema') == $delta->schema_name and
					$layer->get('maintable') == $delta->table_name
				) and
					(
						($delta->action == 'update') or
						($delta->action == 'insert' and $privileg > 0) or
						($delta->action == 'delete' and $privileg > 1)
					);
			}
		)) > 0;
	}

	/**
	 * This function rewrites thie insert or update string in $row->sql to contain only $allowed_columns that exists in $row->table_name of $row->schema_name.
	 */
	function adjust_sql($row) {
		$pg_obj = new PgObject($this->database->gui, $row->schema_name, $row->table_name);
		$allowed_columns = $pg_obj->get_attribute_names();
		include_once(CLASSPATH . 'sql.php');
		$sql_obj = new sql($row->sql);
		$not_allowed_columns = $sql_obj->remove_not_allowed_columns($allowed_columns);
		$adjusted_sql .= $sql_obj->to_sql();
		return $adjusted_sql;
	}

	function adjust_sql_($delta, $allowed_columns) {
		if ($delta->action === 'insert') {
			// extract columns and values from INSERT sql
			if (preg_match('/INSERT\s+INTO\s+([`"\w\.]+)\s*\(([^)]+)\)/i', $delta->sql, $match_cols)) {
				$columns = array_map('trim', explode(',', $match_cols[2]));
			}

			if (preg_match('/VALUES\s*\(([^)]+)\)/i', $delta->sql, $match_vals)) {
				preg_match_all("/'[^']*'|[^,]+/", $match_vals[1], $values);
				$values = array_map('trim', $values[0]);
			}

			$columns_values_map = array_combine($columns, $values);
			$filtered_columns = [];
			$filtered_values = [];
			foreach ($columns_values_map as $col => $val) {
				if (in_array($col, $allowed_columns, true)) {
					$filtered_columns[] = $col;
					$filtered_values[] = $val;
				}
			}
			$adjusted_sql = sprintf(
				"INSERT INTO %s (%s) VALUES (%s)",
				$delta->schema_name . "." . $delta->table_name,
				implode(', ', $filtered_columns),
				implode(', ', $filtered_values)
			);
		}
		elseif ($delta->action === 'update') {
			// Für Update
			if (preg_match('/UPDATE\s+([`"\w\.]+)\s+SET\s+(.+?)\s+WHERE\s+(.+)/i', $delta->sql, $matches)) {
				$set_part   = $matches[2];
				$where_part = $matches[3];
			}

			$set_pairs = [];
			preg_match_all('/\s*([\w`"]+)\s*=\s*(\'[^\']*\'|[^,]+)\s*/', $set_part, $pairs, PREG_SET_ORDER);
			foreach ($pairs as $pair) {
				$col   = trim($pair[1], '`"');
				$value = trim($pair[2]);
				$set_pairs[$col] = $value;
			}

			$filtered_parts = [];
			foreach ($set_pairs as $col => $val) {
				if (in_array($col, $allowed_columns, true)) {
					$filtered_parts[] = "$col = $val";
				}
			}

			$adjusted_sql = sprintf(
				"UPDATE %s SET %s WHERE %s",
				$delta->schema_name . "." . $delta->table_name,
				implode(', ', $filtered_parts),
				$where_part
			);
		}
		else {
			$adjusted_sql = $delta->sql;
		}

		return $adjusted_sql;
	}

}
