<?php
#############################
# Klasse Konvertierung #
#############################

class Regel extends PgObject {
	
	static $schema = 'xplankonverter';
	static $tableName = 'regeln';

	function Regel($gui) {
		#echo '<br>Create new Object Regel';
		$this->PgObject($gui, Regel::$schema, Regel::$tableName);
		$this->layertypen = array(
			'Punkte',
			'Linien',
			'Flächen'
		);
	}

public static	function find_by_id($gui, $by, $id) {
		#echo '<br>Class Regel function find_by ' . $by . ' ' . $id;
		$regel = new Regel($gui);
		$regel->find_by($by, $id);
		$regel->konvertierung = $regel->get_konvertierung();
		return $regel;
	}

	/*
	* Führt die in der Regel definierten SQL-Statements aus um
	* Daten aus Shapefiles in die Tabellen der XPlan GML Datentabellen
	* zu schreiben. Dabei wird die im sql angegebene Id der Konvertierung
	* für jedes RP_Objekt gesetzt.
	* Wenn die Regel auch eine Bereich Id hat, wird diese in der
	* Tabelle rp_breich2rp_objekt zusammen mit den gml_id's der erzeugten
	* XPlan GML Objekte eingetragen.
	*/
	function convert($konvertierung_id) {
		$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'sql_ausfuehrbar');
		$validierung->konvertierung_id = $konvertierung_id;
		$result = @pg_query(
			$this->database->dbConn,
			$this->get_convert_sql($konvertierung_id)
		);
		$validierung->sql_ausfuehrbar($result, $this->get('id'));

		/*
		foreach(pg_fetch_all($query) AS $object_gml_id) {
			if ($this->get('bereich_gml_id') != '') {
				$sql = "
					INSERT INTO
						gml_classes.rp_object2rp_bereich
					SET
						rp_object_gml_id = '" . $object_gml_id . "',
						rp_bereich_gml_id = '" . $this->get('bereich_gml_id') . "'
				";
			}
		}*/
	}

	function get_convert_sql($konvertierung_id) {
		if ($this->debug) echo '<p>Konvertiere sql: ' . $this->get('sql');
		$sql = strtolower($this->get('sql'));
		if ($this->debug) echo '<p>sql0: ' . $sql;
		$sql = substr_replace(
			$sql,
			'(konvertierung_id, ',
			strpos($sql, '('),
			strlen('(')
		);
		if ($this->debug) echo '<p>sql1: ' . $sql;
		
		$sql = str_replace(
			'select',
			"select {$konvertierung_id},",
			$sql
		);
		if ($this->debug) echo '<p>sql2: ' . $sql;

		if ($this->get('bereiche') != '') {
			$sql = substr_replace(
				$sql,
				' (gehoertzurp_bereich, ',
				strpos($sql, ' ('),
				strlen(' (')
			);
			if ($this->debug) echo '<p>sql3: ' . $sql;
			
			$sql = str_replace(
				'select',
				"select '{$this->get('bereiche')}',",
				$sql
			);
			if ($this->debug) echo '<p>sql4: ' . $sql;
		}

		$sql = "SET search_path=xplan_gml, xplan_shapes_{$konvertierung_id}; {$sql}";

		if ($this->debug) echo '<br>nach sql: ' . $sql;
		return $sql;
	}

	function gml_layer_exists() {
		$layer = new MyObject($this->gui->database, 'layer');
		$layer = $layer->find_where("
			`Gruppe` = " . (empty($this->konvertierung->get('gml_layer_group_id')) ? 0 : $this->konvertierung->get('gml_layer_group_id')) . " AND
			`Name` = '" . $this->get('class_name') . "' AND
			`Datentyp`= " . $this->get_layertyp() . "
		");
		return is_array($layer);
	}

	function get_layertyp() {
		$layertyp = 2; # default Polygon Layer
		if (strpos($this->get('geometrietyp'), 'Punkt') !== false) $layertyp = 0;
		if (strpos($this->get('geometrietyp'), 'Linie') !== false) $layertyp = 1;
		return $layertyp;
	}

	function get_bereich() {
		$bereich = new Bereich($this->gui);
		return $bereich->find_by('gml_id', $this->get('bereich_gml_id'));
	}

	/*
	* Funktion fragt die zur Regel gehöhrende Konvertierung ab
	*/
	function get_konvertierung() {
		$konvertierung_id = $this->get('konvertierung_id');
		if (!empty($this->get('konvertierung_id'))) {
			#echo '<br>Regel gehört direkt zur Konvertierung: ' . $this->get('konvertierung_id');
			$konvertierung = Konvertierung::find_by_id($this->gui, 'id', $this->get('konvertierung_id'));
		}
		else {
			#echo '<br>Regel gehört über einen Bereich und Plan zur Konvertierung.';
			$sql = "
				SELECT
					p.konvertierung_id
				FROM
					xplankonverter.regeln r JOIN
					xplan_gml.rp_bereich b ON r.bereich_gml_id = b.gml_id JOIN
					xplan_gml.rp_plan p ON p.gml_id::text = b.gehoertzuplan
				WHERE
					r.id = {$this->get('id')}
			";
			#echo '<br>SQL zum Abfragen der konvertierung_id der Regel: ' . $sql;
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
		if (!$this->gml_layer_exists()) {
			#echo '<br>Erzeuge Layer ' . $this->get('class_name') . ' in Gruppe' . $this->konvertierung->get('bezeichnung') . ' layertyp ' . $this->layertyp;
			$layertyp = $this->get_layertyp();

			$formvars_before = $this->gui->formvars;
			$this->gui->formvars = array_merge($this->gui->formvars, array(
				'Name' => $this->get('class_name') . ' ' . $this->layertypen[$layertyp],
				'schema' => 'xplan_gml',
				'Datentyp' => $this->layertyp,
				'Gruppe' => $this->konvertierung->get('gml_layer_group_id'),
				'connectiontype' => 6,
				'connection' => $this->gui->pgdatabase->connect_string,
				'epsg_code' => $this->konvertierung->get('output_epsg'),
				'pfad' => "SELECT * FROM " . $this->get('class_name') . " WHERE 1=1",
				'Data' => "geom from (select oid, (position)." . $this->get('geometrietyp') . " AS geom FROM xplan_gml." . strtolower($this->get('class_name')) . ") as foo using unique oid using srid=" . $this->konvertierung->get('output_epsg'),
				'querymap' => 1,
				'queryable' => 1,
				'transparency' => 60,
				'drawingorder' => 100
			));

			$this->gui->LayerAnlegen();

			# id vom Layer abfragen
			$layer_id = $this->gui->formvars['selected_layer_id'];

			$stellen = $this->gui->Stellenzuweisung(
				array($layer_id),
				array($this->gui->Stelle->id)
			);

			# Assign layer_id to Konvertierung
			$this->set('layer_id', $layer_id);
			$this->update();

			$this->gui->formvars = $formvars_before;
		}

	}

	function delete_gml_layer() {
		if (!empty($this->layer_id)) {
			# delete gml layer by konvertierung_id, name and geometrytype
			#echo 'Delete gml layer with layer_id: ' . $this->layer_id;

			# Lösche Layer, wenn von keiner anderen Regel mehr verwendet
			$this->gui->formvars['selected_layer_id'] = $layer_id;
			$this->gui->LayerLoeschen();

			# Lösche Datatypes, wenn von keinem anderen mehr verwendet

			# Lösche Gruppe, wenn kein anderer Layer mehr drin ist
		}
	}

	function destroy() {
		#echo '<br>destroy regel';

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
						k.id = 52
					UNION
					SELECT
						rb.*
					FROM
						xplan_gml.rp_plan p JOIN
						xplan_gml.rp_bereich b ON p.gml_id::text = b.gehoertzuplan JOIN
						xplankonverter.regeln rb ON b.gml_id = rb.bereich_gml_id
					WHERE
						p.konvertierung_id = {$this->konvertierung->get('id')}
				) regeln
			WHERE
				lower(class_name) = (
					SELECT
						lower(class_name)
					from
						xplankonverter.regeln
					WHERE
						id = {$this->get('id')}
				) AND
				id != {$this->get('id')}
		";
		#echo '<br>Gibt es weitere Regeln, die den selben Klassname verwenden?' . $sql;
		if (pg_num_rows(pg_query($this->database->dbConn, $sql)) == 0) {
			# Lösche Layer von class_name mit der entsprechenden Konvertierungs id
			echo '<br>Lösche Layer mit class_name ' . $this->get('class_name') . ' mit der entsprechenden Konvertierungs id.' . $this->konvertierung->get('id');
			##		$this->formvars['selected_layer_id'] = 
			#		$this->LayerLoeschen();
		}
		$this->delete();
	}

}

?>
