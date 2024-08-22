<?php
/*
	* Cases:
	* mobile_create_layer_sync
	* mobile_delete_images
	* mobile_drop_layer_sync
	* mobile_get_layers 
	* mobile_get_stellen
	* mobile_prepare_layer_sync
	* mobile_reformat_attributes
	* mobile_reformat_layer
	* mobile_sync
	* mobile_sync_parameter_valide
	* mobile_upload_image
	* 
	*/

/**
 * This function return all stellen the authenticated user is assigned to
 * where sync enabled layer are in
 */
$GUI->mobile_get_stellen = function () use ($GUI) {
	$sql = "
		SELECT DISTINCT
			s.ID,
			s.Bezeichnung,
			s.epsg_code,
			s.minxmax,
			s.minymax,
			s.maxxmax,
			s.maxymax,
			s.selectable_layer_params
		FROM
			rolle r JOIN
			stelle s ON r.stelle_id = s.ID JOIN
			used_layer ul ON s.ID = ul.Stelle_ID JOIN
			layer l ON ul.Layer_ID = l.Layer_ID
		WHERE
			r.user_id = " . $GUI->user->id . " AND
			l.sync = '1'
		ORDER BY
			s.Bezeichnung
	";
	#echo '<br>SQL zur Abfrage der mobilen Stellen des Nutzers: ' . $sql;
	$ret = $GUI->database->execSQL($sql, 4, 0);

	if ($ret[0]) {
		$result = array(
			"success" => false,
			"err_msg" => "Es konnten keine Stellen mit mobilen Layern abgefragt werden! SQL: " . $sql
		);
	} else {
		$stellen = array();
		while ($rs = $ret['result']->fetch_assoc()) {
			$stellen[] = $GUI->mobile_reformat_stelle($rs, $GUI->user->rolle->get_layer_params($rs['selectable_layer_params'], $GUI->pgdatabase));
		}
		$result = array(
			"success" => true,
			"user_id" => $GUI->user->id,
			"user_name" => $GUI->user->Vorname . ' ' . $GUI->user->Name,
			"stellen" => $stellen
		);
	}
	return $result;
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
			if ($layerset and $layerset[0]['connectiontype'] == '6') {
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
					$privileges['attributenames'],
					false, // all_languages
					true, // recursive
					false, // get_default
					true, // replace
					array('options') // replace_only
				);
				// echo '<p>attributes: ' . print_r($attributes, true);
				# Zuordnen der Privilegien und Tooltips zu den Attributen
				for ($j = 0; $j < count($attributes['name']); $j++) {
					$attributes['privileg'][$j] = $attributes['privileg'][$attributes['name'][$j]] = ($privileges == NULL ? 0 : $privileges[$attributes['name'][$j]]);
					#$attributes['tooltip'][$j] = $attributes['tooltip'][$attributes['name'][$j]] = ($privileges == NULL ? 0 : $privileges['tooltip_' . $attributes['name'][$j]]);
				}

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
		$result = array(
			"success" => true,
			"layers" => $mobile_layers
		);
	} else {
		$result = array(
			"success" => false,
			"err_msg" => "Es konnten keine Layerdaten für diese Stelle abgefragt werden. Prüfen Sie ob die Stelle_ID korrekt ist und ob Sie die Rechte für den Zugriff auf den Layer in der Stelle haben."
		);
	}
	return $result;
};

$GUI->mobile_sync = function () use ($GUI) {
	$GUI->deblog = new LogFile('/var/www/logs/kvmobile_deblog.html', 'html', 'debug_log', 'Debug: ' . date("Y-m-d H:i:s"));
	include_once(CLASSPATH . 'synchronisation.php');
	# Prüfe ob folgende Parameter mit gültigen Werten übergeben wurden.
	# $selected_layer_id (existiert und ist in mysql-Datenbank?)
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
	$GUI->deblog->write('Client Deltas formvars: ' . print_r($GUI->formvars, true));
	$GUI->deblog->write('Client Deltas file name: ' . $_FILES['client_deltas']['tmp_name']);
	$GUI->deblog->write('File: ' . $_FILES['client_deltas']['tmp_name'] . ' exists? ' . file_exists($_FILES['client_deltas']['tmp_name']) . ', move to /var/www/logs/upload_file.json');
	move_uploaded_file($_FILES['client_deltas']['tmp_name'], '/var/www/logs/upload_file.json');
	$GUI->deblog->write('Run function mobile_sync_parameter_valide');

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
		}
		else {
			$result['err_msg'] = ' Synchronisation auf dem Server abgebrochen wegen folgenden Fehlern: ' . $result['err_msg'];
		}
		return $result;
	};

	$GUI->mobile_sync_parameter_valide = function($params) use ($GUI) {
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

	if (!array_key_exists('table_name', $params) || $params['table_name'] == '') {
		$err_msg[] = 'Der Parameter table_name wurde nicht übergeben oder ist leer.';
	}
	$result['msg'] .= ' table_name';

	if (!array_key_exists('device_id', $params) || $params['device_id'] == '') {
		$err_msg[] = 'Der Parameter device_id wurde nicht übergeben oder ist leer.';
	}
	$result['msg'] .= ' device_id';

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
						}
						else {
							$err_msg[] = 'Die erste row enthält kein Schlüssel version: ' . print_r($first_row, true);
						}
						if (property_exists($first_row, 'sql')) {
							$sql = $first_row->sql;
							if ($sql == '') {
								$err_msg[] = 'Das Attribut sql in der ersten row der Deltas ist leer';
							}
						}
						else {
							$err_msg[] = 'Die erste row enthält kein Schlüssel sql: ' . print_r($first_row, true);
						}
					}
					else {
						# Wenn Anzahl rows 0 ist, ist das kein Fehler, weil ja ein Client vielleicht nur neue Daten holen will aber nichts schickt.
					}
				}
				else {
					$err_msg[] = 'Das Objekt der Deltas enthält kein Attribut rows: ' . print_r($deltas, true);
				}
			}
			else {
				$err_msg[] = 'Die Deltas Variable ist kein Objekt.';
			}
		}
		else {
			$err_msg[] = 'Die Deltas wurden nicht übertragen.';
		}
		$result['msg'] .= ' deltas';

	if (count($err_msg) > 0) {
		$result['success'] = false;
		$result['err_msg'] = implode("\n", $err_msg);
	}
	return $result;
};

$GUI->mobile_reformat_stelle = function ($stelle_settings, $layer_params) use ($GUI) {
	$stelle['ID'] = $stelle_settings['ID'];
	$stelle['Bezeichnung'] = $stelle_settings['Bezeichnung'];
	$stelle['dbname'] = ((POSTGRES_DBNAME and POSTGRES_DBNAME != '') ? POSTGRES_DBNAME : 'kvmobile');
	$projFROM = ms_newprojectionobj("init=epsg:" . $stelle_settings['epsg_code']);
	$projTO = ms_newprojectionobj("init=epsg:4326");
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
		function($schema, $table) use ($mainschema) {
			return ($schema ?: $mainschema) . '.' . $table;
		},
		$attr['schema'],
		$attr['table_name']
	));
};

$GUI->mobile_reformat_layer = function ($layerset, $attributes) use ($GUI) {
	$geometry_types = array(
		"Point", "Line", "Polygon"
	);

	$layer = array(
		"id" => $layerset['Layer_ID'],
		"title" => $layerset['Name'],
		"alias" => $layerset['alias'],
		"id_attribute" => $layerset['oid'],
		"name_attribute" => $layerset['labelitem'],
		"classitem" => $layerset['classitem'],
		"transparency" => $layerset['transparency'],
		"geometry_attribute" => $attributes['the_geom'],
		"geometry_type" => $geometry_types[$layerset['Datentyp']],
		"table_name" => $layerset['maintable'],
		"schema_name" => $layerset['schema'],
		"query" => $layerset['pfad'],
		"filter" => $layerset['Filter'],
		"document_path" => $layerset['document_path'],
		"vector_tile_url" => $layerset['vector_tile_url'], 
		"privileg" => $layerset['privileg'],
		"drawingorder" => $layerset['drawingorder'],
		'legendorder' => $layerset['legendorder'],
		"sync" => $layerset['sync'],
		"version" => $layerset['version']
	);
	# ToDo use $mapDB->getDocument_Path(...) to get the calculated document_path
	return $layer;
};

	$GUI->mobile_reformat_attributes = function($attr) use ($GUI) {
		$attributes = array();
		foreach($attr['name'] AS $key => $value) {
			if ($value == 'kartierergruppe_id') {
				#echo '<br>enum: ' . print_r($attr['enum'][$key], true);
			}
			if ($attr['form_element_type'][$key] == 'Autovervollständigungsfeld') {
				$sql = $attr['options'][$key];
				$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);
				if ($ret[0]) { echo err_msg($GUI->script_name, __LINE__, $sql); return 0; }
				while ($rs = pg_fetch_array($ret[1])) {
					$attr['enum'][$key][$rs['value']] = $rs;
				}
			};
			if ($attr['enum'][$key]) {
				$attr['enums'][$key] = array();
				foreach($attr['enum'][$key] AS $enum_key => $enum) {
					$enum_array = array(
						'value' => $enum_key,
						'output' => $enum['output']
					);
					if ($enum['requires_value']) {
						$enum_array['requires_value'] = $enum['requires_value'];
					}
					$attr['enums'][$key][] = $enum_array;
				}
			}
			else {
				$attr['enums'][$key] = array();
			}
			// if ($value == 'sorte_id') {
			// 	echo '<br>options: ' . print_r($attr['options'][$key], true);
			// }
			// if ($attr['enum_value'][$key]) {
			// 	$attr['options'][$key] = array();
			// 	foreach($attr['enum_value'][$key] AS $enum_key => $enum_value) {
			// 		if ($attr['req'][$key]) {
			// 			$attr['options'][$key][] = array(
			// 				'value' => $attr['enum_value'][$key][$enum_key],
			// 				'output' => $attr['enum_output'][$key][$enum_key],
			// 				'requires_value' => $attr['enum_requires_value'][$key][$enum_key]
			// 			);
			// 		}
			// 		else {
			// 			$attr['options'][$key][] = array(
			// 				'value' => $attr['enum_value'][$key][$enum_key],
			// 				'output' => $attr['enum_output'][$key][$enum_key]
			// 			);
			// 		}
			// 	}
			// }

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
			}
			else {
				$attributes[$key]['options'] = ($attr['enum'][$key] ? $attr['enums'][$key] : $attr['options'][$key]);
			}

			if ($attr['req_by'] AND array_key_exists($key, $attr['req_by']) AND $attr['req_by'][$key] != '') {
				$attributes[$key]['required_by'] = $attr['req_by'][$key];
			}
			if ($attr['req'] AND array_key_exists($key, $attr['req']) AND is_array($attr['req'][$key]) AND count($attr['req'][$key]) > 0) {
				$attributes[$key]['requires'] = $attr['req'][$key];
			}
		}
		return $attributes;
	};

$GUI->mobile_reformat_classes = function ($classes) use ($GUI) {
	return array_map(
		function ($class) {
			return array(
				'id' => $class['Class_ID'],
				'name' => $class['Name'],
				'expression' => $class['Expression'],
				'style' => array(
					'id' => $class['Style'][0]['Style_ID'],
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

$GUI->mobile_prepare_layer_sync = function ($layerdb, $id, $sync) use ($GUI) {
	include_once(CLASSPATH . 'Layer.php');
	$layer = Layer::find($GUI, 'Layer_ID = ' . $id)[0];

	$sql = "
			SELECT EXISTS (
				SELECT 1
				FROM information_schema.tables 
				WHERE 
					table_schema = '" . $layer->get('schema') . "' AND
					table_name = '" . $layer->get('maintable') . "_deltas'
			) AS table_exists
		";
	#echo '<p>Plugin: Mobile, function: prepare_layer_sync, Query if delta table exists SQL:<br>' . $sql;
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
	$GUI->add_message('notice', 'Sync-Tabelle und Trigger gelöscht.');
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

/*
	* ToDo: Use function save_uploaded_file($input_name, $doc_path, $doc_url, $options, $attribute_names, $attribute_values, $layer_db)
	* to move the uploaded images to layers document path
	*/
$GUI->mobile_upload_image = function ($layer_id, $files) use ($GUI) {
	# Bestimme den Uploadpfad des Layers
	if (intval($layer_id) == 0) {
		return array(
			"success" => false,
			"msg" => "Sie müssen eine korrekte Layer_id angeben!"
		);
	}
	$layer = $GUI->Stelle->getLayer($layer_id);
	if (count($layer) == 0) {
		return array(
			"success" => false,
			"msg" => "Der Layer mit der ID " . $layer_id . " wurde in der Stelle mit ID: " . $GUI->Stelle->id . " nicht gefunden!"
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
		return array(
			"success" => false,
			"msg" => "Es wurde keine Datei hochgeladen!"
		);
	}

	if (file_exists($doc_path . $files['image']['name'])) {
		return array(
			"success" => true,
			"msg" => "Datei existiert schon auf dem Server!"
		);
	}

	# Kopiere Temporäre Datei in den Uploadpfad
	if (!move_uploaded_file($files['image']['tmp_name'], $doc_path . $files['image']['name'])) {
		$success = false;
		$msg = 'Konnte hochgeladene Datei: ' . $files['image']['tmp_name'] . ' nicht nach ' . $doc_path . $files['image']['name'] . ' kopieren!';
	} else {
		$vorschaubild = $GUI->get_dokument_vorschau($doc_path . $files['image']['name'], $doc_path, '');
		$success = true;
		$msg = 'Datei erfolgreich auf dem Server gespeichert unter: ' . $doc_path . $files['image']['name'];
	}

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
			"msg" => "Sie müssen eine korrekte Layer_id angeben!"
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
?>