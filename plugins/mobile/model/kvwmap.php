<?php
// mobile_create_layer_sync
// mobile_create_layer_sync_all
// mobile_delete_images
// mobile_drop_layer_sync
// mobile_drop_layer_sync_all
// mobile_fix_sync_delta
// mobile_get_data_version
// mobile_get_layers 
// mobile_get_stellen
// mobile_list_logs
// mobile_prepare_layer_sync
// mobile_prepare_layer_sync_all
// mobile_reformat_attributes
// mobile_reformat_fk_attributes
// mobile_reformat_layer
// mobile_show_log
// mobile_sync
// mobile_sync_all
// mobile_sync_all_parameter_valide
// mobile_sync_parameter_valide
// mobile_upload_image

/**
 * function create trigger for $layer that write deltas in deltas_all table.
 * @param Layer $layer layer object
 */
$GUI->mobile_create_layer_sync_all = function ($layer) use ($GUI) {
	$sql = "
		--
		-- INSERT Trigger
		--
		CREATE OR REPLACE TRIGGER create_" . $layer->get('maintable') . "_insert_delta_all_trigger
		BEFORE INSERT
		ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
		FOR EACH STATEMENT
		EXECUTE PROCEDURE create_insert_delta();

		CREATE OR REPLACE TRIGGER create_" . $layer->get('maintable') . "_update_delta_all_trigger
		BEFORE UPDATE
		ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
		FOR EACH STATEMENT
		EXECUTE PROCEDURE create_update_delta();

		CREATE OR REPLACE TRIGGER create_" . $layer->get('maintable') . "_delete_delta_all_trigger
		BEFORE DELETE
		ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
		FOR EACH ROW
		EXECUTE PROCEDURE create_delete_delta();
	";
	#echo '<p>Plugin: Mobile, function: mobile_create_layer_sync_all, Create table and trigger for deltas SQL:<br>' . $sql;
	$layer_db = $layer->get_layer_db();
	$ret = $layer_db->execSQL($sql, 4, 0, true);
	if ($ret[0]) {
		$GUI->add_message('error', 'Fehler beim Anlegen der Sync-Trigger!<p>Abbruch in Plugin mobile kvwmap.php  Zeile: ' . __LINE__ . '<br>wegen ' . $ret['msg']);
		return 0;
	}
	$GUI->add_message('info', 'Sync-Trigger für INSERT, UPDATE und DELETE auf Tabelle ' . $layer->get('schema') . '.' . $layer->get('maintable') . ' angelegt.');
};

$GUI->mobile_fix_sync_delta = function() use ($GUI) {
	echo 'Funktion noch nicht fertig implementiert.<br>';
	echo 'Hier soll es möglich sein für ein SQL, welches zu einem Fehler führt eine Alternative zu hinterlegen, die statt desssen ausgeführt wird wenn das SQL noch mal aufgerufen wird durch die Synchronisierung. Die Alternative soll zum Fixen von Sync-Problemen führen ohne dass der Nutzer etwas machen muss außer Auto-Sync wieder einschalten.';
// 	include_once(CLASSPATH . 'PgObject.php');
// 	include_once(CLASSPATH . 'sql.php');
// 	$sql = $GUI->formvars['client_delta'];
// 	$sql = "UPDATE kob.baum
//         SET
//  	      	kronenhabitus_ids = '{1,3,5}', updated_at_client = '2025-08-13T19:39:26', user_name = 'Peter Korduan-Mobil', user_id = 17
//         WHERE
//         	uuid = '6f812418-4af8-45df-8db4-055eaa7874e2'";
// 	// echo 'sql: ' . $sql;
// 	$pg_obj = new PgObject($GUI, 'kob', 'baum');
// 	// Korrigieren der Deltas vom Client
// 	$allowed_columns = $pg_obj->get_attribute_names();
// 	array_splice($allowed_columns, 1, 30);
// 	$sql_obj = new sql($sql);
// 	$not_allowed_columns = $sql_obj->remove_not_allowed_columns($allowed_columns);
// 	echo '<br>not_allowed_columns: ' . implode(', ', $not_allowed_columns);
// #		return print_r($sql_obj_adjusted, true);
// 	$adjusted_sql = $sql_obj->to_sql();
// 	echo '<br>Korrekted SQL:<br>' . $adjusted_sql;
};

/**
 * This function return all stellen the authenticated user is assigned to
 * where sync enabled layer are in
 */
$GUI->mobile_get_stellen = function () use ($GUI) {
	$sql = "
		SELECT DISTINCT
			s.id,
			s.bezeichnung,
			s.epsg_code,
			s.minxmax,
			s.minymax,
			s.maxxmax,
			s.maxymax,
			s.selectable_layer_params
		FROM
			kvwmap.rolle r JOIN
			kvwmap.stelle s ON r.stelle_id = s.ID JOIN
			kvwmap.used_layer ul ON s.ID = ul.stelle_id JOIN
			kvwmap.layer l ON ul.layer_id = l.layer_id
		WHERE
			r.user_id = " . $GUI->user->id . " AND
			l.sync = true
		ORDER BY
			s.bezeichnung
	";
	// echo '<br>SQL zur Abfrage der mobilen Stellen des Nutzers: ' . $sql;
	$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);

	if ($ret[0]) {
		return array(
			"success" => false,
			"err_msg" => "Es konnten keine Stellen mit mobilen Layern abgefragt werden! SQL: " . $sql
		);
	}
	$stellen = array();
	while ($rs = pg_fetch_assoc($ret[1])) {
		$stellen[] = $GUI->mobile_reformat_stelle($rs, $GUI->user->rolle->get_layer_params($rs['selectable_layer_params'], $GUI->pgdatabase));
	}

	return array(
		"success" => true,
		"user_id" => $GUI->user->id,
		"user_name" => $GUI->user->Vorname . ' ' . $GUI->user->Name,
		"stellen" => $stellen
	);
};

/**
 * Frage den Layer mit selected_layer_id und die dazugehörigen Attributdaten ab
 */
$GUI->mobile_get_layers = function () use ($GUI) {
	# ToDo get more than only the layer with selected_layer_id
	$layers = $GUI->Stelle->getLayers('');
	$mobile_layers = array();
	foreach ($layers['ID'] as $layer_id) {
		if ($layer_id != '') {
			# Abfragen der Layerdefinition
			$layerset = $GUI->user->rolle->getLayer($layer_id, false, false);
			if ($layerset and $layerset[0]['connectiontype'] == '6' AND strpos(strtolower($layerset[0]['version']), 'portal') === false) {
				# Abfragen der Privilegien der Attribute
				$privileges = $GUI->Stelle->get_attributes_privileges($layer_id);

				# Abfragen der Attribute des Layers mit selected_layer_id
				$mapDB = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
				$layerdb = $mapDB->getlayerdatabase(
					$layer_id,
					$GUI->Stelle->pgdbhost
				);

				$attributes = $mapDB->read_layer_attributes(
					$layer_id,
					$layerdb,
					null, // Null statt $privileges['attributenames'], weil in kvmobile immer alle attribute vorhanden sein müssen, nicht nur die die sichtbar sind.
					false, // $all_languages
					true, // recursive
					false, // get_default,
					true, // replace
					array('options'), // replace_only
					array() // attribute_kvps
				);

				# Zuordnen der Privilegien und Tooltips zu den Attributen
				for ($j = 0; $j < count($attributes['name']); $j++) {
					$attributes['privileg'][$j] = $attributes['privileg'][$attributes['name'][$j]] = ($privileges == NULL ? 0 : $privileges[$attributes['name'][$j]]);
					#$attributes['tooltip'][$j] = $attributes['tooltip'][$attributes['name'][$j]] = ($privileges == NULL ? 0 : $privileges['tooltip_' . $attributes['name'][$j]]);
				}

				include_once(CLASSPATH . 'DataSource.php');
				$layerset[0]['datasources'] = DataSource::find_by_layer_id($GUI, $layer_id);

				$layer = $GUI->mobile_reformat_layer($layerset[0], $attributes);
				$attributes = $mapDB->add_attribute_values($attributes, $layerdb, array(), true, $GUI->Stelle->ID, true, true);
				$layer['attributes'] = $GUI->mobile_reformat_attributes($attributes);
				$layer['tables'] = $GUI->mobile_reformat_tables($layer['schema_name'], $attributes);

				$classes = $mapDB->read_Classes($layer_id, NULL, false, $layerset[0]['classification']);
				$layer['classes'] = $GUI->mobile_reformat_classes($classes);
				$mobile_layers[] = $layer;
			}
		}
	}

	if (count($mobile_layers) > 0) {
		$sql = "
			SELECT
				COALESCE(max(version), 0) AS last_delta_version
			FROM
				deltas_all
		";
		#echo '<br>SQL zur Abfrage der höchsten deltas Version: ' . $sql;
		$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);
		if ($ret[0]) {
			return array(
				"success" => false,
				"err_msg" => "Die maximale Deltas-Version konnte nicht abgefragt werden! SQL: " . $sql . ' Meldung: ' . $ret['msg']
			);
		}
		$row = pg_fetch_assoc($ret['query']);
		$last_delta_version = $row['last_delta_version'];

		$result = array(
			"success" => true,
			"layers" => $mobile_layers,
			"last_delta_version" => $last_delta_version
		);
	} else {
		$result = array(
			"success" => false,
			"err_msg" => "Es konnten keine Layerdaten für diese Stelle abgefragt werden. Prüfen Sie ob die Stelle_ID korrekt ist und ob Sie die Rechte für den Zugriff auf den Layer in der Stelle haben."
		);
	}
	return $result;
};

$GUI->mobile_get_data_version = function () use ($GUI) {
	include_once(CLASSPATH . 'Layer.php');
	$layer = Layer::find_by_id($GUI, $GUI->formvars['selected_layer_id']);
	$sql = "
		SELECT
			gdi_md5_agg() WITHIN GROUP (ORDER BY tab) AS data_version
		FROM
			(
				" . $layer->get('pfad') . "
			) tab
	";
	$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);
	if ($ret[0]) {
		return array(
			'success' => false,
			'err_msg' => err_msg($GUI->script_name, __LINE__, $sql)
		);
	}
	$rs = pg_fetch_array($ret[1]);
	return array(
		'success' => true,
		'dataVersion' => $rs['data_version']
	);
};

$GUI->mobile_get_data_version = function () use ($GUI) {
	include_once(CLASSPATH . 'Layer.php');
	$layer = Layer::find_by_id($GUI, $GUI->formvars['selected_layer_id']);
	$sql = "
		SELECT
			gdi_md5_agg() WITHIN GROUP (ORDER BY tab) AS data_version
		FROM
			(
				" . $layer->get('pfad') . "
			) tab
	";
	$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);
	if ($ret[0]) {
		return array(
			'success' => false,
			'err_msg' => err_msg($GUI->script_name, __LINE__, $sql)
		);
	}
	$rs = pg_fetch_array($ret[1]);
	return array(
		'success' => true,
		'dataVersion' => $rs['data_version']
	);
};

$GUI->mobile_sync = function () use ($GUI) {
	$deblogdir = LOGPATH . 'kvmobile/';
	$deblogfile = $GUI->user->login_name . '_debug_log.html';
	if (!is_dir($deblogdir)) {
		if (!mkdir($deblogdir, 0770, true)) {
			return array(
				'success' => false,
				'err_msg' => 'Logverzeichnis ' . $deblogdir . ' konnte nicht angelegt werden.'
			);
		}
	}
	$GUI->deblog = new LogFile($deblogdir . $deblogfile, 'html', 'kvmobile Logfile für Nutzer: ' . $GUI->user->Vorname . ' ' . $GUI->user->Name . '(' . $GUI->user->login_name . ')', 'Debug: ' . date("Y-m-d H:i:s"));
	include_once(CLASSPATH . 'synchronisation.php');
	# Prüfe ob folgende Parameter mit gültigen Werten übergeben wurden.
	# $selected_layer_id (existiert und ist in Datenbank?)
	# $client_id sollte vorhanden sein, damit das in die syncs Tabelle eingetragen werden kann.
	# $username muss eigentlich nicht geprüft werden, weil der ja immer da ist nach Anmeldung
	# $client_time muss eigentlich auch nicht, wen interessiert das?
	# $last_client_version sollte 1 oder größer sein. ist das leer oder 0, dann wechseln zu DatenExport_Exportieren oder Exception
	# $client_deltas Da müssen keine Daten vorhanden sein, aber es könnte geprüft werden ob die die da sind vollständig sind, jeweils mindestens
	# sql muss vorhanden sein.
	#		if ($GUI->formvars['selected_layer_id'] != '')

	if (!array_key_exists('client_deltas', $_FILES)) {
		return array(
			'success' => false,
			'err_msg' => ' Es wurde keine Datei mit Änderungsdaten zum Server geschickt.'
		);
	}

	$GUI->formvars['client_deltas'] = json_decode(file_get_contents($_FILES['client_deltas']['tmp_name']));
	unset($GUI->formvars['passwort']);
	unset($GUI->formvars['passwort']);
	$GUI->deblog->write('Client Deltas formvars: ' . print_r($GUI->formvars, true));
	$GUI->deblog->write('Client Deltas file name: ' . $_FILES['client_deltas']['tmp_name']);
	$GUI->deblog->write('File: ' . $_FILES['client_deltas']['tmp_name'] . ' exists? ' . file_exists($_FILES['client_deltas']['tmp_name']) . ', move to /var/www/logs/upload_file.json');
	move_uploaded_file($_FILES['client_deltas']['tmp_name'], '/var/www/logs/upload_file.json');

	// $GUI->deblog->write('Run function mobile_sync_parameter_valide');
	$result = $GUI->mobile_sync_parameter_valide($GUI->formvars);

	if ($result['success']) {
		$GUI->debug->write('mobile_sycn_parameter_valid', 5);
		include_once(CLASSPATH . 'Layer.php');
		$layer = Layer::find_by_id($GUI, $GUI->formvars['selected_layer_id']);
		# Layer DB abfragen $layerdb = new ...
		$mapDB = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
		$layerdb = $mapDB->getlayerdatabase($GUI->formvars['selected_layer_id'], $GUI->Stelle->pgdbhost);
		$result['msg'] = 'Layerdb abgefragt mit layer_id: ' . $GUI->formvars['selected_layer_id'];
		$GUI->debug->write('msg: ' . $result['msg'], 5);
		$sync = new synchro($GUI->Stelle, $GUI->user, $layerdb);
		$result = $sync->sync($GUI->formvars['device_id'], $GUI->formvars['username'], $layerdb->schema, $GUI->formvars['table_name'], $GUI->formvars['client_time'], $GUI->formvars['last_client_version'], $GUI->formvars['client_deltas']);
		$result['version'] = $layer->get('version');
		$GUI->debug->write('sync abgeschlossen.');
	} else {
		$result['err_msg'] = ' Synchronisation auf dem Server abgebrochen wegen folgenden Fehlern: ' . $result['err_msg'];
	}
	return $result;
};

/**
 * Sync all deltas from Client to Server and vice versa.
 */
$GUI->mobile_sync_all = function () use ($GUI) {
	$mobile_log_dir = LOGPATH . 'kvmobile/';
	$mobile_log_file = $GUI->user->login_name . '_debug_log.html';
	$mobile_err_dir = LOGPATH . 'kvmobile/';
	$mobile_err_file = '_error_log.html';
	if (!is_dir($mobile_log_dir)) {
		if (!mkdir($mobile_log_dir, 0770, true)) {
			return array(
				'success' => false,
				'message' => 'Logverzeichnis ' . $mobile_log_dir . ' konnte nicht angelegt werden.'
			);
		}
	}
	$GUI->mobile_log = new LogFile($mobile_log_dir . $mobile_log_file, 'html', 'kvmobile Logfile für Nutzer: ' . $GUI->user->Vorname . ' ' . $GUI->user->Name . '(' . $GUI->user->login_name . ')', date("Y-m-d H:i:s"));
	$GUI->mobile_err = new LogFile($mobile_err_dir . $mobile_err_file, 'html', 'kvmobile Error-log');

	include_once(CLASSPATH . 'synchronisation.php');
	# Prüfe ob folgende Parameter mit gültigen Werten übergeben wurden.
	# $selected_layer_id (existiert und ist in Datenbank?)
	# $client_id sollte vorhanden sein, damit das in die syncs Tabelle eingetragen werden kann.
	# $client_time muss eigentlich auch nicht, wen interessiert das?
	# $last_client_version sollte 1 oder größer sein. ist das leer oder 0, dann wechseln zu DatenExport_Exportieren oder Exception
	# $client_deltas Da müssen keine Daten vorhanden sein, aber es könnte geprüft werden ob die die da sind vollständig sind, jeweils mindestens
	# sql muss vorhanden sein.
	#		if ($GUI->formvars['selected_layer_id'] != '')
	if (!array_key_exists('client_deltas', $_FILES)) {
		$GUI->mobile_err->write('Error ' . date("Y-m-d H:i:s") . ' user_id: ' . $GUI->user->id);
		$msg = 'Es wurde keine Datei mit Änderungsdaten zum Server geschickt.';
		$GUI->mobile_err->write($msg);
		return array(
			'success' => false,
			'message' => $msg
		);
	}

	$client_deltas_json = file_get_contents($_FILES['client_deltas']['tmp_name']);
	$GUI->formvars['client_deltas'] = json_decode($client_deltas_json);

	$GUI->mobile_log->write('Client Deltas formvars: <client_deltas_json>' . $client_deltas_json . '</client_deltas_json>');
	// $GUI->mobile_log->write('Client Deltas file name: ' . $_FILES['client_deltas']['tmp_name']);
	// $GUI->mobile_log->write('File: ' . $_FILES['client_deltas']['tmp_name'] . ' exists? ' . file_exists($_FILES['client_deltas']['tmp_name']) . ', move to /var/www/logs/upload_file.json');
	move_uploaded_file($_FILES['client_deltas']['tmp_name'], '/var/www/logs/upload_file.json');

	// $GUI->mobile_log->write('Run function mobile_sync_parameter_valide');
	$result = $GUI->mobile_sync_all_parameter_valide($GUI->formvars);

	if ($result['success']) {
		$GUI->debug->write('mobile_sycn_all_parameter_valid', 5);
		include_once(CLASSPATH . 'Layer2Stelle.php');
		# Layer DB abfragen $layerdb = new ...
		$mapDB = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
		// In kvmobile müssen derzeit noch alle Layer aus einer Datenbank kommen
		// daher kann die layerdb vom ersten Post-GIS Layer der Stelle entnommen werden
		$sync_layers = Layer2Stelle::find_sync_layers($GUI, $GUI->Stelle->id);
		if (count($sync_layers) == 0) {
			return array(
				'success' => true,
				'last_client_version' => $GUI->formvars['last_delta_version'],
				'deltas' => array(),
				'failedDeltas' => array(),
				'message'	=> 'In der Stelle wurden keine Sync-Layer gefunden. Es wurden keine Änderungen vom Client auf den Server übertragen und es gab auch keine Änderungen vom Server zu holen!'
			);
		}
		$layerdb = $mapDB->getlayerdatabase($sync_layers[0]->get('layer_id'), $GUI->Stelle->pgdbhost);
		$result['message'] = 'Layerdb abgefragt mit layer_id: ' . $sync_layers[0]->get('layer_id');
		$sync = new synchro($GUI->Stelle, $GUI->user, $layerdb);
		$result = $sync->sync_all($GUI->formvars['client_id'], $GUI->user->Vorname . ' ' . $GUI->user->Name, $GUI->formvars['client_time'], $GUI->formvars['last_delta_version'], $GUI->formvars['client_deltas'], $sync_layers);
		if (!$result['success']) {
			$GUI->mobile_log->write($result['message']);
			$GUI->mobile_err->write('Error ' . date("Y-m-d H:i:s") . ' user_id: ' . $GUI->user->id);
			$GUI->mobile_err->write($result['message']);
		}
		else {
			$GUI->mobile_log->write($result['message'] . '<br>sync_all abgeschlossen.');
		}
	} else {
		$GUI->mobile_err->write('Error ' . date("Y-m-d H:i:s") . ' user_id: ' . $GUI->user->id);
		$result['message'] = ' Synchronisation auf dem Server abgebrochen wegen folgenden Fehlern: ' . $result['message'];
		$GUI->mobile_err->write($result['message']);
	}
	return $result;
};

$GUI->mobile_sync_parameter_valide = function ($params) use ($GUI) {
	$result = array(
		"success" => true,
		"msg" => 'Validierung durchgeführt für Parameter: ',
		'err_msg' => ''
	);

	$err_msg = array();

	if (!array_key_exists('client_time', $params) || $params['client_time'] == '') {
		$err_msg[] = 'Der Parameter client_time wurde nicht übergeben oder ist leer.';
	}
	$result['msg'] .= ' client_time';

	if (!array_key_exists('last_client_version', $params) || $params['last_client_version'] == '') {
		$err_msg[] = 'Der Parameter last_client_version wurde nicht übergeben oder ist leer.';
	}
	$result['msg'] .= ' last_client_version';

	if (!array_key_exists('device_id', $params) || $params['device_id'] == '') {
		$err_msg[] = 'Der Parameter device_id wurde nicht übergeben oder ist leer.';
	}
	$result['msg'] .= ' device_id';

	if (!array_key_exists('table_name', $params) || $params['table_name'] == '') {
		$err_msg[] = 'Der Parameter table_name wurde nicht übergeben oder ist leer.';
	}
	$result['msg'] .= ' table_name';

	if (!array_key_exists('selected_layer_id', $params) || $params['selected_layer_id'] == '' || $params['selected_layer_id'] == 0) {
		$err_msg[] = 'Der Parameter selected_layer_id wurde nicht übergeben oder ist leer.';
	}
	$result['msg'] .= ' selected_layer_id';

	if (array_key_exists('client_deltas', $params)) {
		$deltas = $params['client_deltas'];
		if (is_object($deltas)) {
			if (property_exists($deltas, 'rows')) {
				$rows = $deltas->rows;
				if (count($rows) > 0) {
					$first_row = $rows[0];
					if (property_exists($first_row, 'version')) {
						$version = $first_row->version;

						if ($version == '' || $version == 0) {
							$err_msg[] = 'Die Version in der ersten row der Deltas ist ' . $version . ' (leer oder 0)';
						}
					} else {
						$err_msg[] = 'Die erste row enthält kein Schlüssel version: ' . print_r($first_row, true);
					}
					if (property_exists($first_row, 'sql')) {
						$sql = $first_row->sql;
						if ($sql == '') {
							$err_msg[] = 'Das Attribut sql in der ersten row der Deltas ist leer';
						}
					} else {
						$err_msg[] = 'Die erste row enthält kein Schlüssel sql: ' . print_r($first_row, true);
					}
				} else {
					# Wenn Anzahl rows 0 ist, ist das kein Fehler, weil ja ein Client vielleicht nur neue Daten holen will aber nichts schickt.
				}
			} else {
				$err_msg[] = 'Das Objekt der Deltas enthält kein Attribut rows: ' . print_r($deltas, true);
			}
		} else {
			$err_msg[] = 'Die Deltas Variable ist kein Objekt.';
		}
	} else {
		$err_msg[] = 'Die Deltas wurden nicht übertragen.';
	}
	$result['msg'] .= ' deltas';

	if (count($err_msg) > 0) {
		$result['success'] = false;
		$result['err_msg'] = implode("\n", $err_msg);
	}
	return $result;
};

/**
 * Check if the $params are valid for sync_all process
 * @param string[] $params - The parameter to check.
 * They normaly has been sent from client and comes from formvars var of GUI object.
 * @return Any[] $result - An array with success, msg and err_msg. Success false indicates not valid parameter.
 */
$GUI->mobile_sync_all_parameter_valide = function ($params) use ($GUI) {
	$msg ='Validierung durchgeführt für Parameter: ';

	$err_msg = array();

	if (!array_key_exists('client_id', $params) || $params['client_id'] == '') {
		$err_msg[] = 'Der Parameter client_id wurde nicht übergeben oder ist leer.';
	}
	$msg .= ' client_id';

	if (!array_key_exists('client_time', $params) || $params['client_time'] == '') {
		$err_msg[] = 'Der Parameter client_time wurde nicht übergeben oder ist leer.';
	}
	$msg .= ' client_time';

	if (!array_key_exists('last_delta_version', $params) || $params['last_delta_version'] == '') {
		$err_msg[] = 'Der Parameter last_delta_version wurde nicht übergeben oder ist leer.';
	}
	$msg .= ' last_delta_version';

	// if (!array_key_exists('selected_layer_id', $params) || $params['selected_layer_id'] == '' || $params['selected_layer_id'] == 0) {
	// 	$err_msg[] = 'Der Parameter selected_layer_id wurde nicht übergeben oder ist leer.';
	// }
	// $msg .= ' selected_layer_id';

	if (array_key_exists('client_deltas', $params)) {
		$deltas = $params['client_deltas'];
		if (is_object($deltas)) {
			if (property_exists($deltas, 'rows')) {
				$rows = $deltas->rows;
				if (count($rows) > 0) {
					$first_row = $rows[0];
					if (property_exists($first_row, 'version')) {
						$version = $first_row->version;

						if ($version == '' || $version == 0) {
							$err_msg[] = 'Die Version in der ersten row der Deltas ist ' . $version . ' (leer oder 0)';
						}
					} else {
						$err_msg[] = 'Die erste row enthält kein Schlüssel version: ' . print_r($first_row, true);
					}
					if (property_exists($first_row, 'sql')) {
						$sql = $first_row->sql;
						if ($sql == '') {
							$err_msg[] = 'Das Attribut sql in der ersten row der Deltas ist leer';
						}
					} else {
						$err_msg[] = 'Die erste row enthält kein Schlüssel sql: ' . print_r($first_row, true);
					}
					#version, action, uuid, actionTime
					if (property_exists($first_row, 'schema_name')) {
						$schema_name = $first_row->schema_name;
						if ($schema_name == '') {
							$err_msg[] = 'Das Attribut schema_name in der ersten row der Deltas ist leer';
						}
					} else {
						$err_msg[] = 'Die erste row enthält keinen schema_name sql: ' . print_r($first_row, true);
					}
					if (property_exists($first_row, 'table_name')) {
						$table_name = $first_row->table_name;
						if ($table_name == '') {
							$err_msg[] = 'Das Attribut table_name in der ersten row der Deltas ist leer';
						}
					} else {
						$err_msg[] = 'Die erste row enthält keinen table_name sql: ' . print_r($first_row, true);
					}
				} else {
					# Wenn Anzahl rows 0 ist, ist das kein Fehler, weil ja ein Client vielleicht nur neue Daten holen will aber nichts schickt.
				}
			} else {
				$err_msg[] = 'Das Objekt der Deltas enthält kein Attribut rows: ' . print_r($deltas, true);
			}
		} else {
			$err_msg[] = 'Die Deltas Variable ist kein Objekt.';
		}
	} else {
		$err_msg[] = 'Die Deltas wurden nicht übertragen.';
	}
	$msg .= ' deltas';

	if (count($err_msg) > 0) {
		return array(
			'success' => false,
			'err_msg' => implode("\n", $err_msg)
		);
	}
	return array(
		'success' => true,
		'msg' => $msg
	);
};

$GUI->mobile_reformat_stelle = function ($stelle_settings, $layer_params) use ($GUI) {
	$stelle['ID'] = $stelle_settings['id'];
	$stelle['Bezeichnung'] = $stelle_settings['bezeichnung'];
	$stelle['dbname'] = ((POSTGRES_DBNAME and POSTGRES_DBNAME != '') ? POSTGRES_DBNAME : 'kvmobile');
	$projFROM = new projectionObj("init=epsg:" . $stelle_settings['epsg_code']);
	$projTO = new projectionObj("init=epsg:4326");
	$extent = rectObj($stelle_settings['minxmax'], $stelle_settings['minymax'], $stelle_settings['maxxmax'], $stelle_settings['maxymax']);
	$extent->project($projFROM, $projTO);
	$stelle['west'] = round($extent->minx, 5);
	$stelle['south'] = round($extent->miny, 5);
	$stelle['east'] = round($extent->maxx, 5);
	$stelle['north'] = round($extent->maxy, 5);
	$stelle['startCenterLat'] = round($extent->miny + ($extent->maxy - $extent->miny) / 2, 5);
	$stelle['startCenterLon'] = round($extent->minx + ($extent->maxx - $extent->minx) / 2, 5);
	$stelle['layer_params'] = $layer_params;
	return $stelle;
};

$GUI->mobile_reformat_tables = function ($mainschema, $attr) use ($GUI) {
	return array_unique(array_map(
		function ($schema, $table) use ($mainschema) {
			return ($schema ?: $mainschema) . '.' . $table;
		},
		$attr['schema'],
		$attr['table_name']
	));
};

$GUI->mobile_reformat_layer = function ($layerset, $attributes) use ($GUI) {
	$geometry_types = array(
		"Point",
		"Line",
		"Polygon"
	);

	$layer = array(
		"id" => $layerset['layer_id'],
		"title" => $layerset['name'],
		"alias" => $layerset['alias'],
		"id_attribute" => $layerset['oid'],
		"name_attribute" => $layerset['labelitem'],
		"classitem" => $layerset['classitem'],
		"transparency" => $layerset['transparency'],
		"geometry_attribute" => $attributes['the_geom'],
		"geometry_type" => $geometry_types[$layerset['datentyp']],
		"table_name" => $layerset['maintable'],
		"schema_name" => $layerset['schema'],
		"query" => $layerset['pfad'],
		"filter" => $layerset['filter'],
		"document_path" => $layerset['document_path'],
		"vector_tile_url" => $layerset['vector_tile_url'],
		"privileg" => $layerset['privileg'],
		"drawingorder" => $layerset['drawingorder'],
		'legendorder' => $layerset['legendorder'],
		'attribution' => implode(', ', array_map(
			function($datasource) {
				return $datasource->get('name');
			},
			$layerset['datasources']
		)),
		"sync" => $layerset['sync'],
		"version" => $layerset['version']
	);
	# ToDo use $mapDB->getDocument_Path(...) to get the calculated document_path
	return $layer;
};

$GUI->mobile_reformat_attributes = function ($attr) use ($GUI) {
	$attributes = array();
	foreach ($attr['name'] as $key => $value) {
		if ($attr['form_element_type'][$key] == 'Autovervollständigungsfeld') {
			$sql = $attr['options'][$key];
			$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);
			if ($ret[0]) {
				echo err_msg($GUI->script_name, __LINE__, $sql);
				return 0;
			}
			while ($rs = pg_fetch_array($ret[1])) {
				$attr['enum'][$key][$rs['value']] = $rs;
			}
		};

		if ($attr['enum'][$key]) {
			$attr['enums'][$key] = array();
			foreach ($attr['enum'][$key] as $enum_key => $enum) {
				$enum_array = array(
					'value' => $enum_key,
					'output' => $enum['output']
				);
				if ($enum['requires_value']) {
					$enum_array['requires_value'] = $enum['requires_value'];
				}
				$attr['enums'][$key][] = $enum_array;
			}
		} else {
			$attr['enums'][$key] = array();
		}

		$attributes[$key] = array(
			"index" => $attr['indizes'][$value],
			"name" => $value,
			"real_name" => $attr['real_name'][$value],
			"table_name" => $attr['table_name'][$key],
			"schema_name" => $attr['schema'][$key],
			"alias" => $attr['alias'][$key],
			"group" => $attr['group'][$key],
			"tooltip" => $attr['tooltip'][$key],
			"type" => $attr['type'][$key],
			"nullable" => $attr['nullable'][$key],
			"saveable" => $attr['saveable'][$key],
			"form_element_type" => $attr['form_element_type'][$key],
			"arrangement" => $attr['arrangement'][$key],
			"labeling" => $attr['labeling'][$key],
			"privilege" => $attr['privileg'][$key],
			"default" => $attr['default'][$key],
			'visible' => $attr['visible'][$key],
			'vcheck_attribute' => $attr['vcheck_attribute'][$key],
			'vcheck_operator' => $attr['vcheck_operator'][$key],
			'vcheck_value' => $attr['vcheck_value'][$key]
		);
		if ($GUI->formvars['kvmobile_version'] >= '1.13.0') {
			$attributes[$key]['options'] = $attr['options'][$key];
			$attributes[$key]['enums'] = $attr['enums'][$key];
		} else {
			$attributes[$key]['options'] = ($attr['enum'][$key] ? $attr['enums'][$key] : $attr['options'][$key]);
		}

		if ($attr['req_by'] and array_key_exists($key, $attr['req_by']) and $attr['req_by'][$key] != '') {
			$attributes[$key]['required_by'] = $attr['req_by'][$key];
		}
		if ($attr['req'] and array_key_exists($key, $attr['req']) and is_array($attr['req'][$key]) and count($attr['req'][$key]) > 0) {
			$attributes[$key]['requires'] = $attr['req'][$key];
		}
	}
	$attributes = $GUI->mobile_reformat_fk_attributes($attributes);
	return $attributes;
};

$GUI->mobile_reformat_fk_attributes = function ($attributes) use ($GUI) {
	include_once(CLASSPATH . 'LayerAttribute.php');
	$new_attributes = $attributes;
	foreach ($attributes AS $key => $attribute) {
		if ($attribute['form_element_type'] === 'SubFormFK') {
			$attribute_obj = new LayerAttribute($GUI);
			$attribute_options = $attribute_obj->get_options($attribute['options'], 'SubFormFK');
			$fk_attribute = array_filter(
				$attributes,
				function ($attr) use ($attribute_options) {
					$attr["name"] === $attribute_options['fk_name'];
				}
			);
			foreach (array_keys($fk_attribute) as $fk_attr_key) {
				$new_attributes[$fk_attr_key]['options'] = $attribute['options'];
				$new_attributes[$fk_attr_key]['form_element_type'] = 'SubFormFK';
			}
			$new_attributes[$key]['form_element_type'] = 'Text';
			$new_attributes[$key]['fk_options'] = $attribute['options'];
		}
	}
	return $new_attributes;
};

$GUI->mobile_reformat_classes = function ($classes) use ($GUI) {
	return array_map(
		function ($class) {
			return array(
				'id' => $class['class_id'],
				'name' => $class['name'],
				'expression' => $class['Expression'],
				'style' => array(
					'id' => $class['Style'][0]['style_id'],
					'symbol' => $class['Style'][0]['symbol'],
					'stroke' => ($class['Style'][0]['outlinecolor'] == '-1 -1 -1' ? false : true),
					'fillColor' => $class['Style'][0]['color'],
					'fill' => ($class['Style'][0]['color'] == '-1 -1 -1' || $class['Style'][0]['color'] == '' ? false : true),
					'opacity' => $class['Style'][0]['opacity'],
					'color' => $class['Style'][0]['outlinecolor'],
					'weight' => $class['Style'][0]['width'],
					'size' => $class['Style'][0]['size']
				)
			);
		},
		$classes
	);
};

/**
 * Function prepare table for layer synchronisation.
 * It calls the function mobile_create_sync_table
 * if table public.syncs does not exists
 * @param $layerdb Database where the layer table resists
 * @param $sync Switch if Synchronisation ist on or off
 */
$GUI->mobile_prepare_sync_table = function ($layerdb, $sync) use ($GUI) {
	$sql = "
		SELECT EXISTS (
			SELECT 1
			FROM information_schema.tables 
			WHERE 
				table_schema = 'public' AND
				table_name = 'syncs'
		) AS table_exists
	";

	$ret = $layerdb->execSQL($sql, 4, 0);
	if ($ret[0]) {
		echo "<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . "<br>wegen: " . $sql . "<p>";
		return 0;
	}

	$rs = pg_fetch_assoc($ret[1]);
	if ($sync == 1 and $rs['table_exists'] == 'f') {
		$GUI->mobile_create_sync_table($layerdb);
	}
};

/**
 * Function create table for layer synchronisation.
 * @param $layerdb Database where the layer table resists
 * @param $sync Switch if Synchronisation ist on or off
 */
$GUI->mobile_create_sync_table = function ($layerdb) use ($GUI) {
	$sql = "
		CREATE TABLE IF NOT EXISTS public.syncs (
			id serial NOT NULL PRIMARY KEY,
			client_id character varying,
			username character varying,
			schema_name character varying,
			table_name character varying,
			client_time timestamp without time zone,
			pull_from_version integer,
			pull_to_version integer,
			push_from_version integer,
			push_to_version integer
		);
	";
	$ret = $layerdb->execSQL($sql, 4, 0);
	if ($ret[0]) {
		echo "<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . "<br>wegen: " . $sql . "<p>";
		return 0;
	}
};

$GUI->mobile_list_logs = function() use ($GUI) {
	$GUI->main = '../../plugins/mobile/view/list_logs.php';
	$GUI->titel = 'kvmobile Logs';
};

$GUI->mobile_show_log = function($log_file) use ($GUI) {
	$GUI->main = '../../plugins/mobile/view/show_log.php';
	$GUI->titel = 'kvmobile Log: ' . $log_file;
	$GUI->log_file = $log_file;
	$GUI->mobile_log_dir = LOGPATH . 'kvmobile/';
	$GUI->mobile_log_file = $log_file . '_debug_log.html';
	$GUI->mobile_log_path = $GUI->mobile_log_dir . $GUI->mobile_log_file;
	$GUI->mobile_log_content = file_get_contents($GUI->mobile_log_path);
	$parts = explode('<h1>', $GUI->mobile_log_content); // Die einzelnen Meldungen im Log
	array_shift($parts);
	$GUI->mobile_logs = array();
	foreach ($parts AS $part) {
		$parts2 = explode('</h1>', $part);
		$teil_vor_h1 = $parts2[0]; // Teil vor </h1>
		$teil_nach_h1 = $parts2[1];
		$client_deltas = json_decode(get_tag_content($teil_nach_h1, 'client_deltas_json'));
		// preg_match_all('/\+ 1\s*\);\s*(.*?)UPDATE syncs/s', $end_parts[1], $sql_matches);
		// if ($client_deltas) {
		// 	$GUI->mobile_logs[] = array(
		// 		'timestamp' => $end_parts[0],
		// 		'client_deltas' => $sql_matches[1],
		// 		'error' => (count($error_matches[1]) > 0 ? $error_matches[1][0] : '')
		// 	);
		// }
		if (count($client_deltas->rows) > 0) {
			// preg_match_all('/Fehler mit result:(.*?)(?:\s*<html>|$)/s', $end_parts[1], $error_matches);
			$fehler_parts = explode('Fehler bei der Ausführung', $teil_nach_h1);
			$GUI->mobile_logs[] = array(
				'timestamp' => $teil_vor_h1,
				'client_deltas' => $client_deltas->rows,
				'error' => (count($fehler_parts) > 1 ? 'Fehler bei der Ausführung' . $fehler_parts[1] : '')
			);
		}
	}
};

$GUI->mobile_prepare_layer_sync_all = function ($layerdb, $id, $sync) use ($GUI) {
	include_once(CLASSPATH . 'Layer.php');
	$layer = Layer::find($GUI, 'layer_id = ' . $id)[0];

	$sql = "
		SELECT EXISTS (
			SELECT 1
			FROM information_schema.triggers
			WHERE
				trigger_schema = '" . $layer->get('schema') . "' AND
				trigger_name = 'create_" . $layer->get('maintable') . "_delete_delta_all_trigger'
		) AS trigger_exists
	";
	// echo '<p>Plugin: Mobile, function: prepare_layer_sync_all, Query if delete delta_all trigger exists SQL:<br>' . $sql;
	$ret = $layerdb->execSQL($sql, 4, 0);
	if ($ret[0]) {
		echo "<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . "<br>wegen: " . $sql . "<p>";
		return 0;
	}

	$rs = pg_fetch_assoc($ret[1]);
	if ($rs['trigger_exists'] == 't' and $sync == 0) {
		$GUI->mobile_drop_layer_sync_all($layer);
	}

	if ($rs['trigger_exists'] == 'f' and $sync == 1) {
		$GUI->mobile_create_layer_sync_all($layer);
	}
};

$GUI->mobile_prepare_layer_sync = function ($layerdb, $id, $sync) use ($GUI) {
	include_once(CLASSPATH . 'Layer.php');
	$layer = Layer::find($GUI, 'layer_id = ' . $id)[0];

	$sql = "
		SELECT EXISTS (
			SELECT 1
			FROM information_schema.tables 
			WHERE 
				table_schema = '" . $layer->get('schema') . "' AND
				table_name = '" . $layer->get('maintable') . "_deltas'
		) AS table_exists
	";
	// echo '<p>Plugin: Mobile, function: prepare_layer_sync, Query if delta table exists SQL:<br>' . $sql;
	$ret = $layerdb->execSQL($sql, 4, 0);
	if ($ret[0]) {
		echo "<br>Abbruch in " . htmlentities($_SERVER['PHP_SELF']) . " Zeile: " . __LINE__ . "<br>wegen: " . $sql . "<p>";
		return 0;
	}

	$rs = pg_fetch_assoc($ret[1]);
	if ($rs['table_exists'] == 't' and $sync == 0) {
		$GUI->mobile_drop_layer_sync($layerdb, $layer);
	}

	if ($rs['table_exists'] == 'f' and $sync == 1) {
		$GUI->mobile_create_layer_sync($layerdb, $layer);
	}
};

/**
 * Function drop trigger for $layer that wrote changes into deltas_all table.
 * @param Layer $layer layer object
 */
$GUI->mobile_drop_layer_sync_all = function ($layer) use ($GUI) {
	$sql = "
		DROP TRIGGER IF EXISTS create_" . $layer->get('maintable') . "_insert_delta_all_trigger ON " . $layer->get('schema') . "." . $layer->get('maintable') . ";

		DROP TRIGGER IF EXISTS create_" . $layer->get('maintable') . "_update_delta_all_trigger ON " . $layer->get('schema') . "." . $layer->get('maintable') . ";

		DROP TRIGGER IF EXISTS create_" . $layer->get('maintable') . "_delete_delta_all_trigger ON " . $layer->get('schema') . "." . $layer->get('maintable') . ";

		DELETE FROM public.deltas_all WHERE schema_name = '" . $layer->get('schema') . "' AND table_name = '" . $layer->get('maintable') . "';
	";
	#echo '<p>Plugin: Mobile, function: mobile_remove_layer_sync, Drop table and trigger for deltas SQL:<br>' . $sql;
	$layer_db = $layer->get_layer_db();
	$ret = $layer_db->execSQL($sql, 4, 0, true);
	if ($ret[0]) {
		$GUI->add_message('error', 'Fehler beim Löschen der Sync-Trigger und Deltas!<p>Abbruch in Plugin mobile kvwmap.php  Zeile: ' . __LINE__ . '<br>wegen '  . $ret['msg']);
		return 0;
	}
	$GUI->add_message('notice', 'Sync-Trigger und alle Deltas gelöscht.');
};

$GUI->mobile_create_layer_sync = function ($layerdb, $layer) use ($GUI) {
	# create table for deltas
	$sql = "
		--
		-- Deltas Table
		--
		CREATE TABLE " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas (
			version serial NOT NULL,
			sql text,
			created_at timestamp without time zone NOT NULL DEFAULT (now())::timestamp without time zone,
			username character varying,
			CONSTRAINT " . $layer->get('maintable') . "_deltas_pkey PRIMARY KEY (version)
		);

		--
		-- INSERT Trigger
		--
		CREATE OR REPLACE FUNCTION " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_insert_delta()
		RETURNS trigger AS
		$$
			DECLARE
				new_version integer := (SELECT (coalesce(max(version), 1) + 1)::integer FROM " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas);
				_query TEXT;
				_sql TEXT;
				part TEXT;
				search_path_schema TEXT;
				version_column TEXT;
			BEGIN
				_query := current_query();

				--raise notice '_query: %', _query;
				foreach part in array string_to_array(_query, ';')
				loop
					-- replace horizontal tabs, new lines and carriage returns
					part = trim(regexp_replace(part, E'[\\t\\n\\r]+', ' ', 'g'));

					IF strpos(lower(part), 'set search_path') = 1 THEN
					search_path_schema = trim(lower(split_part(split_part(part, '=', 2), ',', 1)));
					--RAISE notice 'schema in search_path %', search_path_schema;
					END IF;

					IF
						strpos(lower(part), 'insert into ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME) = 1 OR
						strpos(lower(part), 'insert into ' || TG_TABLE_SCHEMA || '.\"' || TG_TABLE_NAME || '\"') = 1 OR
						(
							(
								strpos(lower(part), 'insert into ' || TG_TABLE_NAME) = 1 OR
								strpos(lower(part), 'insert into \"' || TG_TABLE_NAME || '\"') = 1
							) AND
							TG_TABLE_SCHEMA = search_path_schema
						)
					THEN
						part := replace(part, '\"' || TG_TABLE_NAME || '\"', TG_TABLE_NAME);
						--RAISE notice 'Anfuehrungsstriche von Tabellennamen entfernt: %', part;

						_sql := part;
					END IF;
				END LOOP;
				--raise notice 'sql nach split by ; und select by update: %', _sql;

				_sql := kvw_replace_line_feeds(_sql);
				--RAISE notice 'sql nach remove line feeds %', _sql;

				_sql := replace(_sql, ' ' || TG_TABLE_NAME || ' ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' ');
				--RAISE notice 'sql nach add schema %', TG_TABLE_SCHEMA || '.';

				-- Frage ab ob es eine Spalte version gibt
				EXECUTE FORMAT('
					SElECT *
					FROM information_schema.columns
					WHERE
						table_schema = %1\$L AND
						table_name = %2\$L AND
						column_name = %3\$L
					', TG_TABLE_SCHEMA, TG_TABLE_NAME, 'version'
				)
				INTO version_column;

				-- Version wird nur angehaengt wenn es die Spalte version gibt
				IF version_column IS NOT NULL THEN
					_sql := kvw_insert_str_before(_sql, ', version', ')');
					--RAISE notice 'sql nach add column version %', _sql;
				END IF;

				_sql := substr(_sql, 1 , strpos(lower(_sql), 'values') - 1) || 'VALUES' || substr(_sql, strpos(lower(_sql), 'values') + 6, length(_sql) - strpos(lower(_sql), 'values') - 5);
				--RAISE notice 'sql nach upper VALUES %', _sql;

				-- Version wird nur angehaengt wenn es die Spalte version gibt
				IF version_column IS NOT NULL THEN
					_sql := substr(_sql, 1, strpos(_sql, 'VALUES') - 1) || regexp_replace(substr(_sql, strpos(_sql, 'VALUES')), '\)+', ', ' || new_version || ')', 'g');
					--RAISE notice 'sql nach add values for version %', _sql;
				END IF;

				IF strpos(lower(_sql), ' returning ') > 0 THEN
					_sql := substr(_sql, 1, strpos(lower(_sql), ' returning ') -1);
					--RAISE notice 'sql nach entfernen von RETURNING uuid %', _sql;
				END IF;

				INSERT INTO " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas (version, sql) VALUES (new_version, _sql);
				RAISE NOTICE 'Neuen Datensatz mit Version % für Synchronisierung eingetragen.', new_version; 

				RETURN NEW;
			END;
		$$
		LANGUAGE plpgsql VOLATILE COST 100;

		CREATE TRIGGER create_" . $layer->get('maintable') . "_insert_delta_trigger
		BEFORE INSERT
		ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
		FOR EACH STATEMENT
		EXECUTE PROCEDURE " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_insert_delta();

		--
		-- UPDATE Trigger
		--
		CREATE OR REPLACE FUNCTION " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_update_delta()
		RETURNS trigger AS
		$$
			DECLARE
				new_version integer := (SELECT (coalesce(max(version), 1) + 1)::integer FROM " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas);
				_query TEXT;
				_sql TEXT;
				part TEXT;
				search_path_schema TEXT;
				version_column TEXT;
			BEGIN
				_query := current_query();

				--raise notice '_query: %', _query;
				foreach part in array string_to_array(_query, ';')
				loop
					--raise notice 'part in loop vor trim und replace: %', part;
					-- replace horizontal tabs, new lines and carriage returns
					part = trim(regexp_replace(part, E'[\\t\\n\\r]+', ' ', 'g'));
					--raise notice 'part in loop nach trim und replace: %', part;
					--raise notice 'suche nach %', 'update ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME;

					IF strpos(lower(part), 'set search_path') = 1 THEN
						search_path_schema = trim(lower(split_part(split_part(part, '=', 2), ',', 1)));
						--RAISE notice 'schema in search_path %', search_path_schema;
					END IF;

					part := replace(part, '\"' || TG_TABLE_NAME || '\"', TG_TABLE_NAME);
					--RAISE notice 'Anfuehrungsstriche von Tabellennamen entfernt: %', part;

					IF strpos(lower(part), 'update ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME) = 1 OR (strpos(lower(part), 'update ' || TG_TABLE_NAME) = 1 AND TG_TABLE_SCHEMA = search_path_schema) THEN
					_sql := part;
					END IF;
				end loop;
				--raise notice 'sql nach split by ; und select by update: %', _sql;

				IF _sql IS NOT NULL THEN
					_sql := kvw_replace_line_feeds(_sql);
					--RAISE notice 'sql nach remove line feeds %', _sql;

					_sql := replace(_sql, ' ' || TG_TABLE_NAME || ' ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' ');
					--RAISE notice 'sql nach remove %', TG_TABLE_SCHEMA || '.';

					-- Frage ab ob es eine Spalte version gibt
					EXECUTE FORMAT('
						SElECT *
						FROM information_schema.columns
						WHERE
							table_schema = %1\$L AND
							table_name = %2\$L AND
							column_name = %3\$L
						', TG_TABLE_SCHEMA, TG_TABLE_NAME, 'version'
					)
					INTO version_column;

					-- Version wird nur angehaengt wenn es die Spalte version gibt
					IF version_column IS NOT NULL THEN
						_sql := kvw_insert_str_after(_sql, 'version = ' || new_version || ', ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' set ');
						--RAISE NOTICE 'sql nach insert version value %', _sql;
					END IF;

					INSERT INTO " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas (version, sql) VALUES (new_version, _sql);
					RAISE NOTICE 'Änderung mit Version % für Synchronisierung eingetragen.', new_version;
				END IF;

				RETURN NEW;
			END;
		$$
		LANGUAGE plpgsql VOLATILE COST 100;

		CREATE TRIGGER create_" . $layer->get('maintable') . "_update_delta_trigger
		BEFORE UPDATE
		ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
		FOR EACH STATEMENT
		EXECUTE PROCEDURE " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_update_delta();

		--
		-- DELETE Trigger
		--
		CREATE OR REPLACE FUNCTION " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_delete_delta()
		RETURNS trigger AS
		$$
			DECLARE
				new_version integer := (SELECT (coalesce(max(version), 1) + 1)::integer FROM " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas);
				_query TEXT;
				_sql TEXT;
				part TEXT;
				search_path_schema TEXT;
			BEGIN
				_query := current_query();

				--RAISE NOTICE 'Current Query unverändert: %', _query;
				foreach part in array string_to_array(_query, ';')
				loop
					-- replace horizontal tabs, new lines and carriage returns
					part := trim(regexp_replace(part, E'[\\t\\n\\r]+', ' ', 'g'));

					IF strpos(lower(part), 'set search_path') = 1 THEN
						search_path_schema := trim(lower(split_part(split_part(part, '=', 2), ',', 1)));
						--RAISE notice 'schema in search_path %', search_path_schema;
					END IF;

					part := replace(part, ' \"' || TG_TABLE_NAME || '\" ', ' ' || TG_TABLE_NAME || ' ');
					--RAISE notice 'Anfuehrungsstriche von Tabellennamen entfernt: %', part;

					IF strpos(lower(part), 'delete from ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME) = 1 OR (strpos(lower(part), 'delete from ' || TG_TABLE_NAME) = 1 AND TG_TABLE_SCHEMA = search_path_schema) THEN
						_sql := part;
					END IF;
				end loop;
				--raise notice 'sql nach split by ; und select by update: %', _sql;

				_sql := replace(_sql, ' ' || TG_TABLE_NAME || ' ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' ');
				--RAISE notice 'sql nach replace tablename by schema and tablename: %', _sql;

				--_sql := split_part(_sql, ' WHERE ', 1) || ' WHERE uuid = ''' || OLD.uuid || '''';
				--RAISE NOTICE 'sql ohne replace where by uuid: %', _sql;

				INSERT INTO " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas (version, sql) VALUES (new_version, _sql);
				--RAISE NOTICE 'Löschung mit Version % für Synchronisierung eingetragen.', new_version;

				RETURN OLD;
			END;
		$$
		LANGUAGE plpgsql VOLATILE COST 100;

		CREATE TRIGGER create_" . $layer->get('maintable') . "_delete_delta_trigger
		BEFORE DELETE
		ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
		FOR EACH ROW
		EXECUTE PROCEDURE " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_delete_delta();
	";
	#echo '<p>Plugin: Mobile, function: mobile_create_layer_sync, Create table and trigger for deltas SQL:<br>' . $sql;
	$ret = $layerdb->execSQL($sql, 4, 0, true);
	if ($ret[0]) {
		$GUI->add_message('error', 'Fehler beim Anlegen der Sync-Tabelle!<p>Abbruch in Plugin mobile kvwmap.php  Zeile: ' . __LINE__ . '<br>wegen ' . $ret['msg']);
		return 0;
	}
	$GUI->add_message('info', 'Sync-Tabelle ' . $layer->get('schema') . '.' . $layer->get('maintable') . '_delta<br>und Trigger für INSERT, UPDATE und DELETE angelegt.');
};

/**
 * Function drop trigger for $layer that wrote changes into schema.maintable_deltas table.
 * @param postgresdb $layerdb postgres database object of layer
 * @param Layer $layer layer object
 */
$GUI->mobile_drop_layer_sync = function ($layerdb, $layer) use ($GUI) {
	$sql = "
		DROP TRIGGER IF EXISTS create_" . $layer->get('maintable') . "_insert_delta_trigger ON " . $layer->get('schema') . "." . $layer->get('maintable') . ";
		DROP FUNCTION IF EXISTS " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_insert_delta();

		DROP TRIGGER IF EXISTS create_" . $layer->get('maintable') . "_update_delta_trigger ON " . $layer->get('schema') . "." . $layer->get('maintable') . ";
		DROP FUNCTION IF EXISTS " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_update_delta();

		DROP TRIGGER IF EXISTS create_" . $layer->get('maintable') . "_delete_delta_trigger ON " . $layer->get('schema') . "." . $layer->get('maintable') . ";
		DROP FUNCTION IF EXISTS " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_delete_delta();

		DROP TABLE IF EXISTS " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas;
	";
	#echo '<p>Plugin: Mobile, function: mobile_remove_layer_sync, Drop table and trigger for deltas SQL:<br>' . $sql;
	$ret = $layerdb->execSQL($sql, 4, 0, true);
	if ($ret[0]) {
		$GUI->add_message('error', 'Fehler beim Löschen der Sync-Tabelle!<p>Abbruch in Plugin mobile kvwmap.php  Zeile: ' . __LINE__ . '<br>wegen '  . $ret['msg']);
		return 0;
	}
	$GUI->t_visible = 5000;
	$GUI->add_message('notice', 'Sync-Tabelle ' . $layer->get('schema') . '.' . $layer->get('maintable') . '_delta und Trigger gelöscht.',);
};

/**
 * Function check if layer is valid for synchronisation
 * - Check for assignments to stelle and functions and access rights
 * - Check for necessary attributes
 * - Check the data statement for function calls not allowed for sqlite
 * @param $layerdb Database where the layer table resists
 * @param $layer_id The ID of the layer that has to be validate for sync
 * @param $sync Switch if Synchronisation ist on or off
 */
$GUI->mobile_validate_layer_sync = function ($layerdb, $layer_id, $sync) use ($GUI) {
	if ($sync == 1) {
		include_once(CLASSPATH . 'synchronisation.php');
		include_once(CLASSPATH . 'Layer.php');
		$layer = Layer::find_by_id($GUI, $layer_id);
		if (!$layer) {
			$GUI->add_message('error', 'Der Layer mit der ID: ' . $layer_id . ' existiert nicht!');
			return false;
		}

		$results = $layer->has_sync_functions(synchro::NECESSARY_FUNCTIONS);
		foreach ($results as $result) {
			$GUI->add_message('warning', $result);
		}

		$results = $layer->has_sync_attributes(synchro::NECESSARY_ATTRIBUTES);
		foreach ($results as $result) {
			$GUI->add_message('warning', $result);
		}

		$results = $layer->has_sync_id(synchro::NECESSARY_ID);
		foreach ($results as $result) {
			$GUI->add_message('warning', $result);
		}

		$results = $layer->get_missing_sublayers($layer_id);
		foreach ($results as $l) {
			$GUI->add_message('error', 'Der im Attribut ' . $l['attribute_name'] . ' verknüpfte Sub-Layer ' . $l['sub_layer_name'] . ' (' . $l['sub_layer_id'] . ') existiert nicht!');
		}

		$results = $layer->get_missing_sub_layers_in_stellen($layer_id);
		foreach ($results as $l) {
			$GUI->add_message('error', 'Der im Attribut ' . $l['attribute_name'] . ' verknüpfte Sub-Layer ' . $l['sub_layer_name'] . ' (' . $l['sub_layer_id'] . ') fehlt in Stelle ' . $l['stelle_bezeichnung'] . ' (' . $l['stelle_id'] . ')!');
		}

		$results = $layer->get_none_synced_sub_layers($layer_id);
		foreach ($results as $l) {
			$GUI->add_message('error', 'Der im Attribut ' . $l['attribute_name'] . ' verknüpfte Sub-Layer ' . $l['sub_layer_name'] . ' (' . $l['sub_layer_id'] . ') ist nicht im sync Modus!');
		}
	}
};

/**
 * ToDo: Use function save_uploaded_file($input_name, $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db)
 * to move the uploaded images to layers document path
 */
/**
 * ToDo: Use function save_uploaded_file($input_name, $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db)
 * to move the uploaded images to layers document path
 */
$GUI->mobile_upload_image = function ($layer_id, $files) use ($GUI) {
	$deblogdir = LOGPATH . 'kvmobile/';
	$deblogfile = $GUI->user->login_name . '_debug_log.html';
	if (!is_dir($deblogdir)) {
		if (!mkdir($deblogdir, 0770, true)) {
			return array(
				'success' => false,
				'err_msg' => 'Logverzeichnis ' . $deblogdir . ' konnte nicht angelegt werden.'
			);
		}
	}
	$GUI->deblog = new LogFile($deblogdir . $deblogfile, 'html', 'kvmobile Logfile für Nutzer: ' . $GUI->user->Vorname . ' ' . $GUI->user->Name . '(' . $GUI->user->login_name . ')', 'Debug: ' . date("Y-m-d H:i:s"));

	$deblogdir = LOGPATH . 'kvmobile/';
	$deblogfile = $GUI->user->login_name . '_debug_log.html';
	if (!is_dir($deblogdir)) {
		if (!mkdir($deblogdir, 0770, true)) {
			return array(
				'success' => false,
				'err_msg' => 'Logverzeichnis ' . $deblogdir . ' konnte nicht angelegt werden.'
			);
		}
	}
	$GUI->deblog = new LogFile($deblogdir . $deblogfile, 'html', 'kvmobile Logfile für Nutzer: ' . $GUI->user->Vorname . ' ' . $GUI->user->Name . '(' . $GUI->user->login_name . ')', 'Debug: ' . date("Y-m-d H:i:s"));

	# Bestimme den Uploadpfad des Layers
	if (intval($layer_id) == 0) {

		$msg = 'Sie müssen eine korrekte Layer_id angeben!';
		$GUI->deblog->write($msg);
		return array(
			"success" => false,
			"msg" => $msg
		);
	}
	$layer = $GUI->Stelle->getLayer($layer_id);
	if (count($layer) == 0) {

		$msg = 'Der Layer mit der ID ' . $layer_id . ' wurde in der Stelle mit ID: ' . $GUI->Stelle->id . ' nicht gefunden!';
		return array(
			"success" => false,
			"msg" => $msg
		);
	}

	$doc_path = ($layer[0]['document_path'] != '' ? trim($layer[0]['document_path']) : CUSTOM_IMAGE_PATH);
	if (substr($doc_path, -1) != '/') {
		$doc_path .= '/';
	}
	if (!is_dir($doc_path)) {
		@mkdir($doc_path, 0777, true);
	}

	if ($files['image'] == '') {

		$msg = 'Es wurde keine Datei hochgeladen!';
		$GUI->deblog->write($msg);
		return array(
			"success" => false,
			"msg" => $msg
		);
	}

	if (file_exists($doc_path . $files['image']['name'])) {

		$msg = 'Datei ' . $doc_path . $files['image']['name'] . 'existiert schon auf dem Server!';
		$GUI->deblog->write($msg);
		return array(
			"success" => true,
			"msg" => $msg
		);
	}

	# Kopiere Temporäre Datei in den Uploadpfad
	if (!move_uploaded_file($files['image']['tmp_name'], $doc_path . $files['image']['name'])) {
		$success = false;
		$msg = 'Konnte hochgeladene Datei: ' . $files['image']['tmp_name'] . ' nicht nach ' . $doc_path . $files['image']['name'] . ' kopieren!';
	}
	else {
		$vorschaubild = $GUI->get_dokument_vorschau($doc_path . $files['image']['name'], $doc_path, '');
		$success = true;
		$msg = 'Datei erfolgreich auf dem Server gespeichert unter: ' . $doc_path . $files['image']['name'];
	}
	$GUI->deblog->write($msg);
	return array(
		"success" => $success,
		"msg" => $msg
	);
};

$GUI->mobile_delete_images = function ($layer_id, $images) use ($GUI) {
	# Bestimme den Uploadpfad des Layers
	if (intval($layer_id) == 0) {
		return array(
			"success" => false,
			"msg" => "Sie müssen eine korrekte selected_layer_id angeben!"
		);
	}
	$layer = $GUI->Stelle->getLayer($layer_id);
	if (count($layer) == 0) {
		return array(
			"success" => false,
			"msg" => "Der Layer mit der ID " . $layer_id . " wurde in der Stelle mit ID: " . $GUI->Stelle->id . " nicht gefunden!"
		);
	}
	$doc_path = $layer[0]['document_path'];

	/*
		Prüfe ob die zu löschende Datei in im document_path liegt, wenn ja lösche, wenn nicht lösche nicht.
		$GUI->deleteDokument(image);
		*/
	if ($images == '') {
		return array(
			"success" => false,
			"msg" => "Es wurden keine Bilder zum Löschen angegeben!"
		);
	}

	$images = explode(',', $images);
	$abgelehnt = array();
	foreach ($images as $image) {
		$image = trim($image);
		if (strpos($image, $doc_path) === false) {
			$msg[] = 'Bild ' . $image . ' nicht im richtigen Pfad: ' . $doc_path;
		} else {
			if (file_exists($image)) {
				unlink($image);
				$msg[] = 'Bild ' . $image . ' erfolgreich gelöscht';
				$fp = pathinfo($image);
				$thumb = $fp['dirname'] . '/' . $fp['filename'] . '_thumb.' . $fp['extension'];
				if (file_exists($thumb)) {
					unlink($thumb);
				}
			} else {
				$msg[] = 'Bild ' . $image . ' nicht oder noch nicht auf dem Server';
			};
		}
	}

	return array(
		"success" => true,
		"msg" => implode(', ', $msg)
	);
};