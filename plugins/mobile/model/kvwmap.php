<?php
	$GUI = $this;

	/**
	* Frage den Layer mit selected_layer_id und die dazugehörigen Attributdaten ab
	*/
	$this->mobile_get_layers = function() {
		# ToDo get more than only the layer with selected_layer_id
		$layers = $this->Stelle->getLayers('');
		$mobile_layers = array();

		foreach($layers['ID'] AS $layer_id) {

			if ($layer_id != '') {
				# Abfragen der Layerdefinition
				$layerset = $this->user->rolle->getLayer($layer_id);
				if ($layerset and $layerset[0]['connectiontype'] == '6') {
					# Abfragen der Privilegien der Attribute
					$privileges = $this->Stelle->get_attributes_privileges($layer_id);

					# Abfragen der Attribute des Layers mit selected_layer_id
					$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
					$layerdb = $mapDB->getlayerdatabase(
						$layer_id,
						$this->Stelle->pgdbhost
					);
					$layerdb->setClientEncoding();
					$attributes = $mapDB->read_layer_attributes(
						$layer_id,
						$layerdb,
						$privileges['attributenames'],
						false,
						true
					);

					# Zuordnen der Privilegien und Tooltips zu den Attributen
					for ($j = 0; $j < count($attributes['name']); $j++) {
						$attributes['privileg'][$j] = $attributes['privileg'][$attributes['name'][$j]] = ($privileges == NULL ? 0 : $privileges[$attributes['name'][$j]]);
						$attributes['tooltip'][$j] = $attributes['tooltip'][$attributes['name'][$j]] = ($privileges == NULL ? 0 : $privileges['tooltip_' . $attributes['name'][$j]]);
					}

					$layer = $this->mobile_reformat_layer($layerset[0]);
					$layer['attributes'] = $this->mobile_reformat_attributes($attributes);
					$mobile_layers[] = $layer;
				}
			}
		}

		if (count($mobile_layers) > 0) {
			$result = array(
				"success" => true,
				"layers" => $mobile_layers
			);
		}
		else {
			$result = array(
				"success" => false,
				"err_msg" => "Es konnten keine Layerdaten für diese Stelle abgefragt werden. Prüfen Sie ob die Stelle_ID korrekt ist und ob Sie die Rechte für den Zugriff auf den Layer in der Stelle haben."
			);
		}
		return $result;
	};

	$this->mobile_sync = function() {
		include_once(CLASSPATH . 'synchronisation.php');
		# Prüfe ob folgende Parameter mit gültigen Werten übergeben wurden.
		# $selected_layer_id (existiert und ist in mysql-Datenbank?)
		# $client_id sollte vorhanden sein, damit das in die syncs Tabelle eingetragen werden kann.
		# $username muss eigentlich nicht geprüft werden, weil der ja immer da ist nach Anmeldung
		# $client_time muss eigentlich auch nicht, wen interessiert das?
		# $last_client_version sollte 1 oder größer sein. ist das leer oder 0, dann wechseln zu DatenExport_Exportieren oder Exeption
		# $client_deltas Da müssen keine Daten vorhanden sein, aber es könnte geprüft werden ob die die da sind vollständig sind, jeweils mindestens
		# sql muss vorhanden sein.
		#		if ($this->formvars['selected_layer_id'] != '')

		$this->formvars['client_deltas'] = json_decode(file_get_contents($_FILES['client_deltas']['tmp_name']));
		move_uploaded_file($_FILES['file']['tmp_name'], '/var/www/logs/upload_file.json');

		$result = $this->mobile_sync_parameter_valide($this->formvars);
		if ($result['success']) {

			# Layer DB abfragen $layerdb = new ...
			$mapDB = new db_mapObj($this->Stelle->id, $this->user->id);
			$layerdb = $mapDB->getlayerdatabase($this->formvars['selected_layer_id'], $this->Stelle->pgdbhost);
			$result['msg'] = 'Layerdb abgefragt mit layer_id: ' . $this->formvars['selected_layer_id'];
			$sync = new synchro($this->Stelle, $this->user, $layerdb);
			$result = $sync->sync($this->formvars['device_id'], $this->formvars['username'], $layerdb->schema, $this->formvars['table_name'], $this->formvars['client_time'], $this->formvars['last_client_version'], $this->formvars['client_deltas']);
		}
		else {
			$result['err_msg'] = ' Syncronisation auf dem Server abgebrochen wegen folgenden Fehlern: ' . $result['err_msg'];
		}
		return $result;
	};

	$this->mobile_sync_parameter_valide = function($params) {
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
			$err_msg[] = 'Der Parameter client_time wurde nicht übergeben oder ist leer.';
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
			if (property_exists($deltas, 'rows')) {
				$rows = $deltas->rows;
				if (count($rows) > 0) {
					$first_row = $rows[0];
					if (array_key_exists('version', $first_row)) {
						$version = $first_row->version;
						if ($version == '' || $version == 0) {
							$err_msg[] = 'Die Version in der ersten row der Deltas ist ' . $version . ' (leer oder 0)';
						}
					}
					else {
						$err_msg[] = 'Die erste row enthält kein Schlüssel version: ' . print_r($first_row, true);
					}
					if (array_key_exists('sql', $first_row)) {
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
					$err_msg[] = 'Anzahl der rows im Objekt der Deltas ist 0.' . print_r($rows, true);
				}
			}
			else {
				$err_msg[] = 'Das Objekt der Deltas enthält kein Attribut rows: ' . print_r($deltas, true);
			}
		}
		else {
			$err_msg[] = 'Die Deltas wurde nicht übertragen.';
		}
		$result['msg'] .= ' deltas';

		if (count($err_msg) > 0) {
			$result['success'] = false;
			$result['err_msg'] = implode("\n", $err_msg);
		}
		return $result;
	};

	$this->mobile_reformat_layer = function($layerset) {
		$geometry_types = array(
			"Point", "Line", "Polygon"
		);
		$layer = array(
			"id" => $layerset['Layer_ID'],
			"title" => $layerset['Name'],
			"id_attribute" => "id",
			"title_attribute" => "title",
			"geometry_type" => $geometry_types[$layerset['Datentyp']],
			"table_name" => $layerset['maintable'],
			"schema_name" => $layerset['schema']
		);
		return $layer;
	};

	$this->mobile_reformat_attributes = function($attr) {
		$attributes = array();
		foreach($attr['name'] AS $key => $value) {
			$attributes[] = array(
				"index" => $attr['indizes'][$value],
				"name" => $value,
				"real_name" => $attr['real_name'][$value],
				"alias" => $attr['alias'][$value],
				"tooltip" => $attr['tooltip'][$key],
				"type" => $attr['type'][$key],
				"nullable" => $attr['nullable'][$key],
				"form_element_type" => $attr['form_element_type'][$key],
				"options" => $attr['options'][$key],
				"privilege" => $attr['privileg'][$key]
			);
		}
		return $attributes;
	};

	$this->mobile_prepare_layer_sync = function($layerdb, $id, $sync) {
		include_once(CLASSPATH . 'Layer.php');
		$layer = Layer::find($this, 'Layer_ID = ' . $id)[0];

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
		if ($ret[0]) { echo "<br>Abbruch in " . $PHP_SELF . " Zeile: " . __LINE__ . "<br>wegen: " . $sql . "<p>"; return 0; }

		# ToDo create Table syncs if not exists

		$rs = pg_fetch_assoc($ret[1]);
		if ($rs['table_exists'] == 't' and $sync == 0) {
			$this->mobile_drop_layer_sync($layerdb, $layer);
		}

		if ($rs['table_exists'] == 'f' and $sync == 1) {
			$this->mobile_create_layer_sync($layerdb, $layer);
		}
	};

	$this->mobile_drop_layer_sync = function($layerdb, $layer) {
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
			$this->add_message('error', 'Fehler beim Löschen der Sync-Tabelle!<p>Abbruch in Plugin mobile kvwmap.php  Zeile: ' . __LINE__ . '<br>wegen '  . $ret['msg']);
			return 0;
		}
		$this->add_message('notice', 'Sync-Tabelle und Trigger gelöscht.');
	};

	$this->mobile_create_layer_sync = function($layerdb, $layer) {
		# create table for deltas
		$sql = "
			CREATE TABLE " . $layer->get('schema') . "." . $layer->get('maintable') . "_deltas (
				version serial NOT NULL,
				sql text,
				created_at timestamp without time zone NOT NULL DEFAULT (now())::timestamp without time zone,
				username character varying,
				CONSTRAINT " . $layer->get('maintable') . "_deltas_pkey PRIMARY KEY (version)
			)
			WITH (
				OIDS=TRUE
			);

			CREATE OR REPLACE FUNCTION " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_insert_delta()
			RETURNS trigger AS
			$BODY$
				DECLARE
					new_version integer := (SELECT (max(version) + 1)::integer FROM rebus.haltestellen_deltas);
					_query TEXT;
					_sql TEXT;
				BEGIN
					SET datestyle to 'German';
					_query := current_query();

					--raise notice '_query=%', split_part(_query, ';', 1);
					_sql := split_part(_query, ';', 1);
					--raise notice 'sql nach split_part: %', _sql;

					_sql := kvw_replace_line_feeds(_sql);
					--RAISE notice 'sql nach remove line feeds %', _sql;

					_sql := replace(_sql, ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' ', ' ' || TG_TABLE_NAME || ' ');
					--RAISE notice 'sql nach remove %', TG_TABLE_SCHEMA || '.';

					_sql := kvw_insert_str_before(_sql, ', version', ')');
					--RAISE notice 'sql nach add column version %', _sql;

					_sql := substr(_sql, 1 , strpos(lower(_sql), 'values') - 1) || 'VALUES' || substr(_sql, strpos(lower(_sql), 'values') + 6, length(_sql) - strpos(lower(_sql), 'values') - 5);
					--RAISE notice 'sql nach upper VALUES %', _sql;

					_sql := substr(_sql, 1, strpos(_sql, 'VALUES') - 1) || regexp_replace(substr(_sql, strpos(_sql, 'VALUES')), '\)+', ', ' || new_version || ')', 'g');
					--RAISE notice 'sql nach add values for version %', _sql;

					--RAISE notice 'Eintragen des INSERT-Statements mit Version: %', new_version; 
					INSERT INTO rebus.haltestellen_deltas (version, sql) VALUES (new_version, _sql);

					RETURN NEW;
				END;
			$BODY$
			LANGUAGE plpgsql VOLATILE COST 100;

			CREATE TRIGGER create_" . $layer->get('maintable') . "_insert_delta_trigger
			BEFORE INSERT
			ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
			FOR EACH STATEMENT
			EXECUTE PROCEDURE " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_insert_delta();

			CREATE OR REPLACE FUNCTION " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_update_delta()
			RETURNS trigger AS
			$BODY$
				DECLARE
					new_version integer := (SELECT (max(version) + 1)::integer FROM rebus.haltestellen_deltas);
					_query TEXT;
					_sql TEXT;
				BEGIN
					SET datestyle to 'German';
					_query := current_query();

					--raise notice '_query=%', split_part(_query, ';', 1);
					_sql := split_part(_query, ';', 1);
					--raise notice 'sql nach split_part: %', _sql;

					_sql := kvw_replace_line_feeds(_sql);
					--RAISE notice 'sql nach remove line feeds %', _sql;

					_sql := kvw_insert_str_after(_sql, 'version = ' || new_version || ', ', ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' set ');
					--RAISE NOTICE 'sql nach insert version value %', _sql;

					_sql := replace(_sql, ' ' || TG_TABLE_SCHEMA || '.' || TG_TABLE_NAME || ' ', ' ' || TG_TABLE_NAME || ' ');
					--RAISE notice 'sql nach remove table schema %', _sql;

					RAISE notice 'Eintragen des UPDATE-Statements mit Version %', new_version;
					INSERT INTO rebus.haltestellen_deltas (version, sql) VALUES (new_version, _sql);

					RETURN NEW;
				END;
			$BODY$
			LANGUAGE plpgsql VOLATILE COST 100;

			CREATE TRIGGER create_" . $layer->get('maintable') . "_update_delta_trigger
			BEFORE UPDATE
			ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
			FOR EACH STATEMENT
			EXECUTE PROCEDURE " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_update_delta();

			CREATE OR REPLACE FUNCTION " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_delete_delta()
			RETURNS trigger AS
			$BODY$
				DECLARE
					new_version integer := (SELECT nextval('rebus.haltestellen_deltas_version_seq'));
					_query TEXT;
					_sql TEXT;
				BEGIN
					SET datestyle to 'German';
					_query := current_query();

					_sql := (SELECT split_part(_query, ';', (SELECT array_length(regexp_split_to_array(_query, ';'), 1))));
					--raise notice 'sql nach split_part: %', _sql;

					_sql := _sql || ' AND uuid = ' || OLD.uuid;
					--RAISE notice 'sql nach add uuid: %', _sql;

					INSERT INTO rebus.haltestellen_deltas (version, sql, username) VALUES (new_version, _sql, OLD.user_name);

					RETURN OLD;
				END;
			$BODY$
			LANGUAGE plpgsql VOLATILE COST 100;

			CREATE TRIGGER create_" . $layer->get('maintable') . "_delete_delta_trigger
			BEFORE DELETE
			ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
			FOR EACH STATEMENT
			EXECUTE PROCEDURE rebus.create_haltestellen_delete_delta();
		";
		#echo '<p>Plugin: Mobile, function: mobile_create_layer_sync, Create table and trigger for deltas SQL:<br>' . $sql;
		$ret = $layerdb->execSQL($sql, 4, 0, true);
		if ($ret[0]) {
			$this->add_message('error', 'Fehler beim Anlegen der Sync-Tabelle!<p>Abbruch in Plugin mobile kvwmap.php  Zeile: ' . __LINE__ . '<br>wegen ' . $ret['msg']);
			return 0;
		}
		$this->add_message('info', 'Sync-Tabelle ' . $layer->get('schema') . '.' . $layer->get('maintable') . '_delta<br>und Trigger für INSERT, UPDATE und DELETE angelegt.');
	};
?>