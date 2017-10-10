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

	$this->mobile_update_data = function() {
		if ($this->formvars['selected_layer_id'] != '') {
			# ToDo update data

			$result = array(
				"success" => true
			);
		}
		else {
			$result = array(
				"success" => false,
				"err_msg" => "Es wurde kein Layer zur Aktualisierung angegeben. Geben Sie die ID des Layers im Parameter selected_layer_id an."
			);
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
			"table_name" => $layerset['maintable']
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
			$$
				DECLARE
	  			new_version integer := (SELECT nextval('rebus.haltestellen_deltas_version_seq'));
					_query TEXT;
					_sql TEXT;
				BEGIN
	  			SET datestyle to 'German';
					_query := current_query();
					NEW.version := new_version;

					_sql := (SELECT split_part(_query, ';', (SELECT array_length(regexp_split_to_array(_query, ';'), 1))));
					--raise notice 'sql nach split_part: %', _sql;

					_sql := substr(_sql, 1 , strpos(lower(_sql), 'values') - 1) || 'VALUES' || substr(_sql, strpos(lower(_sql), 'values') + 6, length(_sql) - strpos(lower(_sql), 'values') - 5);
					--RAISE notice 'sql nach lower values %', _sql;

					_sql := (SELECT replace(_sql, ') VALUES', ', version) VALUES'));
					--RAISE notice 'sql nach add column version %', _sql;

					_sql := (SELECT substr(_sql, 1, length(_sql) - 1) || ', ' || NEW.version || ')');
					--RAISE notice 'sql nach add value version %', _sql;

					INSERT INTO rebus.haltestellen_deltas (version, sql, username) VALUES (new_version, _sql, NEW.user_name);
					RETURN NEW;
				END;
			$$
			LANGUAGE plpgsql VOLATILE COST 100;

			CREATE TRIGGER create_" . $layer->get('maintable') . "_insert_delta_trigger
			BEFORE INSERT
			ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
			FOR EACH ROW
			EXECUTE PROCEDURE " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_insert_delta();

			CREATE OR REPLACE FUNCTION " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_update_delta()
			RETURNS trigger AS
			$$
				DECLARE
	  			new_version integer := (SELECT nextval('rebus.haltestellen_deltas_version_seq'));
					_query TEXT;
					_sql TEXT;
				BEGIN
	  			SET datestyle to 'German';
					_query := current_query();
					-- Wie bekomme ich new_version an die geupdateten Datensaetze?

					_sql := (SELECT split_part(_query, ';', (SELECT array_length(regexp_split_to_array(_query, ';'), 1))));
					--raise notice 'sql nach split_part: %', _sql;

					_sql := substr(_sql, 1 , strpos(lower(_sql), 'where') - 1) || 'WHERE' || substr(_sql, strpos(lower(_sql), 'where') + 5, length(_sql) - strpos(lower(_sql), 'where') - 4);
					--RAISE notice 'sql nach uppder where %', _sql;

					_sql := (SELECT replace(_sql, ' WHERE', ', version = ' || new_version || ' WHERE'));
					--RAISE notice 'sql nach add column version %', _sql;
					INSERT INTO rebus.haltestellen_deltas (version, sql, username) VALUES (new_version, _sql, NULL);
					RETURN NEW;
				END;
			$$
			LANGUAGE plpgsql VOLATILE COST 100;

			CREATE TRIGGER create_" . $layer->get('maintable') . "_update_delta_trigger
			BEFORE UPDATE
			ON " . $layer->get('schema') . "." . $layer->get('maintable') . "
			FOR EACH STATEMENT
			EXECUTE PROCEDURE " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_update_delta();

			CREATE OR REPLACE FUNCTION " . $layer->get('schema') . ".create_" . $layer->get('maintable') . "_delete_delta()
			RETURNS trigger AS
			$$
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
			$$
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