<?php
#############################
# Klasse Regel #
#############################

class Regel extends PgObject {
	
	static $schema = 'xplankonverter';
	static $tableName = 'regeln';
	static $write_debug = false;

	function __construct($gui) {
		$gui->debug->show('Create new Object Regel', Regel::$write_debug);
		parent::__construct($gui, Regel::$schema, Regel::$tableName);
		$this->layertypen = array(
			'Punkte',
			'Linien',
			'Flächen'
		);
		$this->layername_postfix = array(
			'points',
			'lines',
			'polygons'
		);
	}

	public static	function find_by_id($gui, $by, $id) {
		$regel = new Regel($gui);
		$regel->find_by($by, $id);
		$regel->konvertierung = $regel->get_konvertierung();
		return $regel;
	}

	public static	function find_by_konvertierung_and_class_name($gui, $konvertierung_id, $class_name) {
		#echo '<br>Finde Regel mit konvertierung_id = ' . $konvertierung_id . " AND class_name LIKE '" . $class_name . "'";
		$regel = new Regel($gui);
		$regeln = $regel->find_where("konvertierung_id = " . $konvertierung_id . " AND class_name LIKE '" . $class_name . "'", $id);
		return $regeln;
	}

	/*
	* Checks which geom column is used in the regel to determine whether the source is shape(the_geom) or gmlas(position)
	*/
	function is_source_shape_or_gmlas($regel,$konvertierung_id) {
		$user_id = $this->gui->user->id;
		$full_table_name = $this->get_shape_table_name();
		$full_table_name_arr = explode('.', $full_table_name);
		$table_name = $full_table_name_arr[1];
		
		$sql = "
			SELECT
			EXISTS(
				SELECT
					column_name
				FROM
					information_schema.columns
				WHERE
					table_schema = 'xplan_shapes_" . $konvertierung_id . "'
				AND
					table_name = '" . $table_name . "'
				AND
					column_name = 'the_geom'
			) AS the_geom,
			EXISTS(
				SELECT
					column_name
				FROM
					information_schema.columns
				WHERE
					table_schema = 'xplan_gmlas_" . $konvertierung_id . "'
				AND
					table_name = '" . $table_name . "'
				AND
					column_name = 'position'
			) AS position";
		$ret = pg_query($this->database->dbConn, $sql);
		$result = pg_fetch_assoc($ret);
		if($result['the_geom'] == 't') {
			$sourcetype = 'shape';
		} elseif ($result['position'] == 't') {
			$sourcetype = 'gmlas';
		} else {
			$sourcetype = ''; #should not happen
		}
		return $sourcetype;
	}

	/*
	* Validiert die in der Regel definierten SQL-Statements
	*/
	function validate($konvertierung) {
		$success = true;
		$konvertierung_id = $konvertierung->get('id');
		# Check geometry column source
		$sourcetype = $this->is_source_shape_or_gmlas($this, $konvertierung_id);
		
		$this->debug->show('Regel validate mit konvertierung_id: ' . $konvertierung_id, Regel::$write_debug);
		$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'sql_vorhanden');
		$validierung->konvertierung_id = $konvertierung_id;

		if ($validierung->sql_vorhanden($this->get('sql'), $this)) {
			# Prüft ob das sql ausführbar ist und legt die Objekte an wenn ja.
			$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'sql_ausfuehrbar');
			$validierung->konvertierung_id = $konvertierung_id;
			$sql_ausfuehrbar = $validierung->sql_ausfuehrbar($this, $konvertierung_id);


			# Prüft ob alle Objekte eine Geometrie haben
			$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'geometrie_vorhanden');
			$validierung->konvertierung_id = $konvertierung_id;
			$validierung->geometrie_vorhanden($this->get('sql'), $this->get('id'), $sourcetype);

			$this->debug->show('<br>bereich_gml_id: ' . $this->get('bereich_gml_id'), Regel::$write_debug);
			if ($sql_ausfuehrbar) {
				$this->debug->show('<br>SQL der Regel: ' . $this->get('name') . ' ausfuehrbar', Regel::$write_debug);


				# Prüft ob die Geometrien der mit get_shape_table_name() ermittelte Ausgangsdatentabelle eine SRID haben
				# und wenn nicht wird der Default-EPSG-Code gesetzt.
				$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'force_geometrie_srid');
				$validierung->konvertierung_id = $konvertierung_id;
				$validierung->force_geometrie_srid($this, $konvertierung);

				# Prüft ob die erzeugten Geometrien valide sind.
				$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'geometrie_isvalid');
				$validierung->konvertierung_id = $konvertierung_id;
				$all_geom_isvalid = $validierung->geometrie_isvalid($this, $konvertierung);

				if ($all_geom_isvalid) {
					# Prüft ob die erzeugten Geometrien im räumlichen Geltungsbereich des Planes liegen.
					$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'geom_within_plan');
					$validierung->konvertierung_id = $konvertierung_id;
					$validierung->geom_within_plan($this, $konvertierung);

					# Prüft ob die erzeugten Geometrien im Geltungsbereich der Bereiche liegen.
					$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'geom_within_bereich');
					$validierung->konvertierung_id = $konvertierung_id;
					$validierung->geom_within_bereich($this, $konvertierung);
				}
			}
			else {
				$this->debug->show('<br>Regel->validate(): SQL der Regel: ' . $this->get('name') . ' nicht ausfuehrbar', Regel::$write_debug);
				$success = false;
			}
		}
		else {
			$success = false;
		}
		$this->debug->show('<br>Die Validierungen sind' . ($success ? '' : ' nicht') . ' erfolgreich verlaufen.', Regel::$write_debug);
		return $success;
	}

	/*
	* Führt die in der Regel definierten SQL-Statements aus um
	* Daten aus Shapefiles in die Tabellen der XPlan GML Datentabellen
	* zu schreiben. Dabei wird die im sql angegebene Id der Konvertierung
	* für jedes XP_Objekt gesetzt.
	* Bei Objekten, die eine gml_id haben wird diese mit übernommen,
	* bei den anderen wird die gid mit übernommen und die erzeugte gml_id
	* zusammen mit der gid im Result mit zurückgeliefert und
	* in der Shape-Tabelle eingetragen
	* @return array Liste der Objekte, für die bei der Konvertierung
	* eine neue gml_id erzeugt wurde
	*/
	function convert($konvertierung) {
		$this->debug->show('convert', Regel::$write_debug);
		$sql = 	$this->get_convert_sql($konvertierung->get('id'));

		# Stringpos needed as gml_id will be transfered with gmlas
		# And there will be no gid but a ogr_fid
		if(strpos($sql, 'gml_id') == false) {

			# Konvertiere Objekte, die eine gml_id haben
			# Die gid muss nicht mit übertragen werden
			pg_query(
				$this->database->dbConn,
				$this->get_convert_sql_with_gml_id($sql)
			);

			# Konvertiere Objekte, die keine gml_id haben
			# Die gid muss mit übertragen werden und zusammen mit der
			# erzeugten gml_id zurückkommen.
			$result = pg_query(
				$this->database->dbConn,
				$this->get_convert_sql_with_gid($sql)
			);

			return (pg_num_rows($result) == 0 ? array() : pg_fetch_all($result));
		} else {
			$ret = $this->database->execSQL($sql);
			if (!$ret['success']) {
				return array();
			}
			/*
			$result = pg_query(
				$this->database->dbConn,
				$sql
			);
			*/
			else {
				return (pg_num_rows($ret['query']) == 0 ? array() : pg_fetch_all($ret['query']));
			}
		}
	}

	function get_shape_table_name() {
		$this->debug->show('<br>Extrahiere Tabellenname der Shape-Datei aus sql: ' . $this->get($sql), Validierung::$write_debug);
		# only search starting from last from to ignore potential earlier subqueries
		$partsfrompos = substr($this->get('sql'),strrpos($this->get('sql'),'FROM'));
	
		$parts1 = explode('FROM', $partsfrompos);
		$parts2 = explode('WHERE', $parts1[1]);
		$parts3 = trim($parts2[0]);
		// Remove alias etc.
		$parts4 = explode(' ', $parts3);
		$shape_table_name = $parts4[0];
		$this->debug->show('<br>Shape table name: ' . $shape_table_name, Validierung::$write_debug);
		return $shape_table_name;
	}

	/*
	* Ergänzt converter sql so, dass die gml_id mit übertragen wird
	* aber nur für Objekte, die eine gml_id haben
	*/
	function get_convert_sql_with_gml_id($sql) {
		# gml_id hinzufügen
		$sql = substr_replace(
			$sql,
			'(gml_id, ',
			strpos($sql, '('),
			strlen('(')
		);
		$sql = str_ireplace(
			'select',
			"SELECT gml_id::uuid,",
			$sql
		);
		$sql = str_ireplace(
			'where',
			"WHERE gml_id IS NOT NULL AND",
			$sql
		);

		$this->debug->show('<b>sql nach gml_id hinzufügen für Objekte mit gml_id</b>:<br>' . $sql, Regel::$write_debug);
		return $sql;
	}

	/*
	* Ergänzt converter sql so, dass gid mit übertragen wird
	* für alle Objekte, die keine gml_id haben.
	*/
	function get_convert_sql_with_gid($sql) {
		# gid hinzufügen
		$sql = substr_replace(
			$sql,
			'(gid, ',
			strpos($sql, '('),
			strlen('(')
		);
		$sql = str_ireplace(
			'select',
			"select gid,",
			$sql
		);
		$sql = str_ireplace(
			'where',
			"WHERE gml_id IS NULL AND",
			$sql
		);

		$sql = str_ireplace(
			'RETURNING',
			'RETURNING gid,',
			$sql
		);

		$this->debug->show('sql nach gid hinzufügen für Objekte ohne gml_id und mit RETURNING gid:<br>' . $sql, Regel::$write_debug);
		return $sql;
	}

	function get_convert_sql($konvertierung_id) {
		$this->debug->show('<br>sql vor Anpassung:<br>' . $this->get('sql'), Regel::$write_debug);
		$sql = $this->get('sql');
		$konvertierung = $this->get_konvertierung();
		$epsg = $konvertierung->get('output_epsg');
		# Sourcetype for geom_column
		$sourcetype = $this->is_source_shape_or_gmlas($regel,$konvertierung->get('id'));
		# Default Shape, position for gmlas
		$geometry_col = ($sourcetype == 'gmlas') ? 'position' : 'the_geom';

		# konvertierung_id hinzufügen
		$sql = substr_replace(
			$sql,
			'(konvertierung_id, ',
			strpos($sql, '('),
			strlen('(')
		);

		# to only replace first insensitive instance of where (to ignore subqueries inside query)
		$sql = substr_replace(
			$sql,
			" select {$konvertierung_id} AS konvertierung_id, ",
			stripos($sql, 'select'),
			strlen("select")
		);

		$this->debug->show('sql nach konvertierung_id hinzufügen:<br>' . $sql, Regel::$write_debug);

		# transformation hinzufügen
		if($geometry_col == 'the_geom') {
			$sql = str_ireplace(
				$geometry_col,
				"st_multi(st_transform(" . $geometry_col . ", {$epsg}))",
				$sql
			);
		} elseif($geometry_col == 'position') {
			# Needs to use gmlas.position, because if position is in source,
			# it is also used in INSERT and possibly WHERE clause, where it should not be replaced
			# TODO What if the schema shortcut is not gmlas?
			$geometry_col_with_schema = 'gmlas.position';
			$sql = str_ireplace(
				$geometry_col_with_schema,
				"st_multi(st_transform(" . $geometry_col_with_schema . ", {$epsg}))",
				$sql
			);
		}
		$this->debug->show('sql nach transformation:<br>' . $sql, Regel::$write_debug);
		
		# nur nicht leere Geometrien übernehmen
		if (strpos(strtolower($sql), 'where') === false) {
			$sql .= ' where ' . $geometry_col . ' IS NOT NULL';
		}
		else {
			#str_ilreplace to only replace last insensitive instance of where (to ignore subqueries inside query)
			$sql = Validierung::str_ilreplace(
				'where',
				' where ' . $geometry_col . ' IS NOT NULL AND ',
				$sql
			);
		}
		$this->debug->show('sql nach nicht leere Geometrien:<br>' . $sql, Regel::$write_debug);

		if ($this->get('bereich_gml_id') != '') {
			$sql = substr_replace(
				$sql,
				' (gehoertzubereich, ',
				strpos($sql, '('),
				strlen('(')
			);
			#to only replace first insensitive instance of where (to ignore subqueries inside query)
			$sql = substr_replace(
				$sql,
				" select '" . $this->get_bereich_gml_id() . "' AS gehoertzubereich, ",
				stripos($sql, 'select'),
				strlen('select')
		);
			
		}
		$this->debug->show('sql nach bereich:<br>' . $sql, Regel::$write_debug);

		# search_path hinzufügen.
		# $sql = "SET search_path=xplan_gml, xplan_shapes_{$konvertierung_id}, public; {$sql};

		# returning hinzufügen
		$sql = "{$sql}
			RETURNING gml_id, gehoertzubereich
		";
		$this->debug->show('sql nach Hinzufügen von RETURNING:<br>' . $sql, Regel::$write_debug);

		$this->debug->show('sql nach Anpassung:<br>' . $sql, Regel::$write_debug);
		return $sql;
	}

	function rewrite_gml_ids($rows) {
		$this->debug->show('<br><b>gml_ids in Shape-Tabellen zurückschreiben.</b>' . $sql, Regel::$write_debug);

		# Erzeugt Funktion, die eine Tabelle mit allen gml_id's und gid's der neu eingetragenen Objekte zurückliefert.
		$sql = "
			CREATE OR REPLACE FUNCTION gml_id_gid_table()
				RETURNS TABLE (gml_id character varying, gid int) AS
			$$
				BEGIN
					RETURN QUERY VALUES
						" . implode(
							', ',
							array_map(
								function($row) {
									return "('" . $row['gml_id'] . "'::character varying, " . $row['gid'] . ")";
								},
								$rows
							)
						) . ";
				END
			$$
			LANGUAGE plpgsql IMMUTABLE;

			UPDATE
				xplan_shapes_". $this->konvertierung->get('id') . '.' . $this->get_shape_table_name() . " AS shape
			SET
				gml_id::text = xplan.gml_id::text
			FROM
				gml_id_gid_table() AS xplan
			WHERE
				shape.gid = xplan.gid;

			DROP FUNCTION gml_id_gid_table();
		";
		$this->debug->show('<br><b>Schreibe gml_ids mit folgendem sql zurück:</b>' . $sql, Regel::$write_debug);
		pg_query(
			$this->database->dbConn,
			$sql
		);
	}

	function gml_layer_exists() {
		$this->debug->show("Prüfe ob Layer: " . $this->get_layername() . " mit Typ: " . $this->get_layertyp() . " in Gruppe: " . $this->konvertierung->get('gml_layer_group_id') . " existiert", Regel::$write_debug);
		$layers = Layer::find($this->gui, "
			`Name` = '{$this->get_layername()}' AND
			`Datentyp`= {$this->get_layertyp()}
		");
		if (count($layers) > 0) {
			$layer_exists = true;
			$this->gml_layer = $layers[0];
			return true;
		}
		else {
			return false;
		}
	}

	function get_layertyp() {
		$layertyp = 2; # default Polygon Layer
		if (strpos($this->get('geometrietyp'), 'Punkt') !== false) $layertyp = 0;
		if (strpos($this->get('geometrietyp'), 'Linie') !== false) $layertyp = 1;
		return $layertyp;
	}

	function get_layername() {
		return strtolower($this->get('class_name')) . '_' . $this->layername_postfix[$this->get_layertyp()];
	}

	function get_bereich() {
		$bereich = new XP_Bereich($this->gui);
		return $bereich->find_by('gml_id', $this->get('bereich_gml_id'));
	}

	/*
	* Diese Funktion liefert die bereich_gml_id der Regel oder falls vorhanden mehrere aus dem Attribut bereiche
	*/
	function get_bereich_gml_id() {
		if (empty($this->get('bereiche')) or $this->get('bereiche') == '')
			$gml_id = $this->get('bereich_gml_id');
		else
			$gml_id = $this->get('bereiche');

		return $gml_id;
	}

	/*
	* Funktion fragt die zur Regel gehöhrende Konvertierung ab
	*/
	function get_konvertierung() {
		$konvertierung_id = $this->get('konvertierung_id');
		if (!empty($this->get('konvertierung_id'))) {
			$this->debug->show('Regel gehört direkt zur Konvertierung: ' . $this->get('konvertierung_id'), false);
			$konvertierung = Konvertierung::find_by_id($this->gui, 'id', $this->get('konvertierung_id'));
		}
		else {
			$regel_id = $this->get('id');
			if (empty($regel_id)) {
				$regel_id = 0; // can't exist, but necessary for int comparison in SQL
			}

			// currently bereich does not (always) hold konvertierung_id and association is not on xp_schema
			$sql = "
				SELECT
					coalesce(bp.konvertierung_id, fp.konvertierung_id, rp.konvertierung_id, sp.konvertierung_id) AS konvertierung_id
				FROM
					xplankonverter.regeln r LEFT JOIN
					xplan_gml.bp_bereich bb ON r.bereich_gml_id::text = bb.gml_id::text LEFT JOIN
					xplan_gml.fp_bereich fb ON r.bereich_gml_id::text = fb.gml_id::text LEFT JOIN
					xplan_gml.rp_bereich rb ON r.bereich_gml_id::text = rb.gml_id::text LEFT JOIN
					xplan_gml.so_bereich sb ON r.bereich_gml_id::text = sb.gml_id::text LEFT JOIN
					xplan_gml.bp_plan bp ON bp.gml_id::text = bb.gehoertzuplan::text LEFT JOIN
					xplan_gml.fp_plan fp ON fp.gml_id::text = fb.gehoertzuplan::text LEFT JOIN
					xplan_gml.rp_plan rp ON rp.gml_id::text = rb.gehoertzuplan::text LEFT JOIN
					xplan_gml.so_plan sp ON sp.gml_id::text = sb.gehoertzuplan::text LEFT JOIN
					xplan_gml.bp_plan bpp ON bpp.konvertierung_id = r.konvertierung_id LEFT JOIN
					xplan_gml.fp_plan fpp ON fpp.konvertierung_id = r.konvertierung_id LEFT JOIN
					xplan_gml.rp_plan rpp ON rpp.konvertierung_id = r.konvertierung_id LEFT JOIN
					xplan_gml.so_plan spp ON spp.konvertierung_id = r.konvertierung_id
				WHERE
					r.id = " . $regel_id . "
			";
			
			$this->debug->show('SQL zum Abfragen der konvertierung_id der Regel: ' . $sql, false);
			$result = pg_query($this->database->dbConn, $sql);
			if (pg_num_rows($result) > 0) {
				$row = pg_fetch_assoc($result);
				$konvertierung = Konvertierung::find_by_id($this->gui, 'id', $row['konvertierung_id']);
			}
			else {
				$konvertierung = null;
			}
		}
		return $konvertierung;
	}

	function create_gml_layer() {
		$this->konvertierung->create_layer_group('GML');
		
		if (!$this->gml_layer_exists()) {
			$layertyp = $this->get_layertyp();
			$this->debug->show('Erzeuge Layer ' . $this->get_layername(), $this->write_debug);
			
			$this->debug->show('<p>Suche nach Templatelayer ' . $this->get_layername() . ' in Obergruppe ' . GML_LAYER_TEMPLATE_GROUP, Regel::$write_debug);
			$template_layer = Layer::find_by_obergruppe_und_name(
				$this->gui,
				GML_LAYER_TEMPLATE_GROUP,
				$this->get_layername()
			);

			if (empty($template_layer)) {
				# ToDo: Kein Template Layer vorhanden, erzeuge einen Dummy
				echo 'Layer ' . $this->get_layername() . ' für gewählte GML-Klasse nicht vorhanden. Bitte anlegen.';
			}
			else {
				$this->debug->show('<p>Kopiere Templatelayer in gml layer gruppe id: ' . $this->konvertierung->get('gml_layer_group_id'), Regel::$write_debug);
				$gml_layer = $template_layer->copy(
					array(
						'Gruppe' => $this->konvertierung->get('gml_layer_group_id'),
						'Data' => str_ireplace('using srid=25832', 'using srid=' . $this->konvertierung->get('output_epsg'), $template_layer->get('Data')),
						'epsg_code' => $this->konvertierung->get('output_epsg')
					)
				);

				$formvars_before = $this->gui->formvars;

				$stellen = $this->gui->addLayersToStellen(
					array($gml_layer->get($gml_layer->identifier)),
					array($this->gui->Stelle->id),
					'(konvertierung_id = ' . $this->konvertierung->get('id') .')'
				);

				# Assign layer_id to Konvertierung
				$this->set('layer_id', $gml_layer->get($gml_layer->identifier));
				$this->update();
			}
			if(isset($formvars_before) &&  !empty($formvars_before)) {
				$this->gui->formvars = $formvars_before;
			}
		}

	}

	function delete_gml_layer() {
		# Frage ab ob es in der Gruppe der gml Layer einen Layer von class_name gibt
		# der ansonsten von keiner anderen Regel verwendet wird und lösche diesen
		$sql = "
			SELECT
				class_name
			FROM
				(
					SELECT
						rk.*
					FROM
						xplankonverter.konvertierungen k join
						xplankonverter.regeln rk on k.id = rk.konvertierung_id
					WHERE
						k.id = {$this->konvertierung->get('id')}
					UNION
					SELECT
						rb.*
					FROM
						xplan_gml.xp_plan p JOIN
						xplan_gml.xp_bereich b ON p.gml_id::text = b.gehoertzuplan::text JOIN
						xplankonverter.regeln rb ON b.gml_id::text = rb.bereich_gml_id::text
					WHERE
						p.konvertierung_id = {$this->konvertierung->get('id')}
				) regeln
			WHERE
				lower(class_name) || '_' || geometrietyp = (
					SELECT
						lower(class_name) || '_' || geometrietyp
					FROM
						xplankonverter.regeln
					WHERE
						id = {$this->get('id')}
				) AND
				id != {$this->get('id')}
		";
		$this->debug->show('Gibt es weitere Regeln, die den selben Klassname verwenden?<br>' . $sql, Regel::$write_debug);
		$result = pg_query($this->database->dbConn, $sql);
		if (pg_num_rows($result) == 0) {
			$this->debug->show('nein, Prüfe ob der Layer existiert.', Regel::$write_debug);
			if ($this->gml_layer_exists()) {
				$this->debug->show("Layer {$this->gml_layer->get('Name')} existiert.", Regel::$write_debug);
				$this->debug->show("Lösche Layer mit ID: " . $this->gml_layer->get('Layer_ID'), Regel::$write_debug);

				$formvars_before = $this->gui->formvars;
				$this->gui->formvars['selected_layer_id'] = $this->gml_layer->get('Layer_ID');
				$this->gui->LayerLoeschen();
				$this->gui->formvars = $formvars_before;
			}
		}
		else {
			$this->debug->show('ja', Regel::$write_debug);
		}
	}

	function destroy() {
		#echo "\ndestroy Regel: " . $this->get($this->identifier);
		$this->debug->show('destroy regel ' . $this->get('name'), Regel::$write_debug);
		#$this->delete_gml_layer();
		$this->delete();
	}

}

?>
