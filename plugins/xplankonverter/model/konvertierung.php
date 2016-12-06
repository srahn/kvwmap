<?php
#############################
# Klasse Konvertierung #
#############################

class Konvertierung extends PgObject {

	static $schema = 'xplankonverter';
	static $tableName = 'konvertierungen';
	static $STATUS = array(
		'IN_ERSTELLUNG'      => 'in Erstellung',
		'ERSTELLT'           => 'erstellt',
		//    'IN_VALIDIERUNG'     => 'in Validierung',
		//    'VALIDIERUNG_ERR'    => 'Validierung fehlgeschlagen',
		//    'VALIDIERUNG_OK'     => 'validiert',
		'IN_KONVERTIERUNG'   => 'in Konvertierung',
		'KONVERTIERUNG_OK'   => 'Konvertierung abgeschlossen',
		'KONVERTIERUNG_ERR'  => 'Konvertierung abgebrochen',
		'IN_GML_ERSTELLUNG'  => 'in GML-Erstellung',
		'GML_ERSTELLUNG_OK'  => 'GML-Erstellung abgeschlossen',
		'GML_ERSTELLUNG_ERR' => 'GML-Erstellung abgebrochen',
    'IN_INSPIRE_GML_ERSTELLUNG'  => 'in INSPIRE_GML-Erstellung',
		'INSPIRE_GML_ERSTELLUNG_OK'  => 'INSPIRE-GML-Erstellung abgeschlossen',
		'INSPIRE_GML_ERSTELLUNG_ERR' => 'INSPIRE-GML-Erstellung abgebrochen'
	);
	static $write_debug = false;

	function Konvertierung($gui) {
		$this->PgObject($gui, Konvertierung::$schema, Konvertierung::$tableName);
	}

	public static	function find_by_id($gui, $by, $id) {
			#echo '<br>find konvertierung by ' . $by . ' = ' . $id;
			$konvertierung = new Konvertierung($gui);
			$konvertierung->find_by($by, $id);
			return $konvertierung;
		}

	function get_input_epgs_codes() {
		$sql = "
			SELECT
				unnest(enum_range(NULL::xplankonverter.epsg_codes)) AS epsg_code
		";
		$input_codes = array();
		$query = pg_query($this->database->dbConn, $sql);
		while ($rs = pg_fetch_assoc($query)) {
			$input_codes[] = $rs['epsg_code'];
		}
		$this->set('input_epgs_codes', $input_codes);
		return $input_codes;
	}

	function get_regeln() {
		#echo '<p>get_regeln';
		$regeln = array();
		$regel = new Regel($this->gui);
		$regeln = $regel->find_where("
			konvertierung_id = {$this->get('id')} AND
			bereich_gml_id IS NULL
		");
		
		$plan = $this->get_plan();

		foreach($this->get_bereiche($plan->get('gml_id')) AS $bereich) {
			$regeln = array_merge(
				$regeln,
				$regel->find_where("bereich_gml_id = '{$bereich->get('gml_id')}'")
			);
		}
		return $regeln;
	}

	function get_plan() {
		if (!$this->plan) {
			$plan = new RP_Plan($this->gui);
			$plan = $plan->find_where('konvertierung_id = ' . $this->get('id'));
			if ($plan > 0)
				$this->plan = $plan[0];
			else
				$this->plan = false;
		}
		return $this->plan;
	}

	function get_bereiche($plan_id) {
		#echo 'get_bereiche';
		$bereich = new RP_Bereich($this->gui);
		return $bereich->find_where("gehoertzuplan = '{$plan_id}'");
	}

	/**
	* Fragt die unique class names ab, die in der Konvertierung verwendet wurden.
	*/
	function get_class_names() {
		$this->debug->show('Konvertierung: get_class_names.', Konvertierung::$write_debug);
		$sql = "
			SELECT DISTINCT
			  lower(r.class_name) AS class_name
			FROM
			  xplankonverter.regeln r LEFT JOIN
			  xplan_gml.rp_bereich b ON r.bereich_gml_id = b.gml_id LEFT JOIN
				xplan_gml.rp_plan p ON b.gehoertzuplan = p.gml_id::text
			WHERE
			  p.konvertierung_id = {$this->get('id')} OR
			  r.konvertierung_id = {$this->get('id')}
		";
		$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
		$result = pg_fetch_all(
			pg_query($this->database->dbConn, $sql)
		);

		if ($result) {
			$class_names = array_map(
				function($row) {
					return 'xplan_gml.' . $row['class_name'];
				},
				$result
			);
		}
		else {
			$class_names = array();
		}
		return $class_names;
	}

	function set_status($new_status = '') {
		#echo '<br>Setze status in Konvertierung.';
		if ($new_status == '') {
			$sql = "
				SELECT DISTINCT
					CASE
						WHEN p.gml_id IS NOT NULL OR r.id IS NOT NULL THEN true
						ELSE false
					END AS plan_or_regel_assigned
				FROM
					xplankonverter.konvertierungen k LEFT JOIN
					xplan_gml.rp_plan p ON k.id = p.konvertierung_id LEFT JOIN
					xplankonverter.regeln r ON k.id = r.konvertierung_id
				WHERE
					k.id = {$this->get('id')}
			";
			#echo '<br>Setze Status mit sql: ' . $sql;
			$query = pg_query($this->database->dbConn, $sql);
			$result = pg_fetch_assoc($query);
			$plan_or_regel_assigned = $result['plan_or_regel_assigned'];

			$new_status = $this->get('status');
			if ($plan_or_regel_assigned == 't') {
				if ($this->get('status') == 'in Erstellung') {
					$new_status = 'erstellt';
				}
			}
			else {
				$new_status = 'in Erstellung';
			}
		}

		$this->set('status', $new_status);
		$this->update();
		/*
			UPDATE
				xplankonverter.konvertierungen
			SET
				status = new_state::xplankonverter.enum_konvertierungsstatus
			WHERE
				id = _konvertierung_id;
		*/
	}

	/**
	* Erzeugt eine Layergruppe vom Typ GML oder Shape und trägt die dazugehörige
	* gml_layer_group_id oder shape_layer_group_id in PG-Tabelle konvertierung ein.
	*
	*/
	function create_layer_group($layer_type) {
		$this->debug->show('Konvertierung create_layer_group layer_type: ' . $layer_type, false);
		$layer_group_id = $this->get(strtolower($layer_type) . '_layer_group_id');
		if (empty($layer_group_id)) {
			$layerGroup = new MyObject($this->gui, 'u_groups');
			$layerGroup->create(array(
				'Gruppenname' => $this->get('bezeichnung') . ' ' . $layer_type
			));
			$this->set(strtolower($layer_type) . '_layer_group_id', $layerGroup->get('id'));
			$this->update();
		}
		return $this->get(strtolower($layer_type) . '_layer_group_id');
	}

	/**
	* Erzeugt eine Layergruppe vom Typ GML oder Shape und trägt die dazugehörige
	* gml_layer_group_id oder shape_layer_group_id in PG-Tabelle konvertierung ein.
	*
	*/
	function delete_layer_group($layer_type) {
		$this->debug->show('delete_layer_group typ: ' . $layer_type, true);
		$layer_group_id = $this->get(strtolower($layer_type) . '_layer_group_id');
		if (!empty($layer_group_id)) {
			$layer_group = new MyObject($this->gui, 'u_groups');
			$layer_group->set('id', $layer_group_id);
			$layer_group->identifier = 'id';
			$layer_group->identifier_type = 'integer';
			return $layer_group->delete();
		}
		else {
			return false;
		}
	}

	/*
	* Diese Funktion löscht alle zuvor für diese Konvertierung angelegten
	* XPlan GML Datensätze, Beziehungen und Validierungsergebnisse
	*/
	function reset_mapping() {
		$this->debug->show('Konvertierung: reset_mapping mit konvertierung_id: ' . $this->get('id'), Konvertierung::$write_debug);
		# Lösche vorhandene Validierungsergebnisse der Konvertierung
		Validierungsergebnis::delete_by_id($this->gui, 'konvertierung_id', $this->get('id'));

		# Lösche vorhandene Datenobjekte der Konvertierung
		foreach($this->get_class_names() AS $class_name) {
			# Lösche Relationen
			$sql = "
				DELETE FROM
					{$class_name}
				WHERE
					konvertierung_id = {$this->get('id')}
			";
			$this->debug->show("Delete Objekte von {$class_name} with konvertierung_id " . $this->get('id') . ' und sql: ' . $sql . ' in reset Mapping.', Konvertierung::$write_debug);
			$query = pg_query($this->database->dbConn, $sql);
		}
	}

	/*
	* Diese Funktion führt das Mapping zwischen den Shape Dateien
	* und den in den Regeln definierten XPlan GML Features durch.
	* Jedes im Mapping erzeugte Feature bekommt eine eindeutige gml_id.
	* Darüber hinaus muss die Zuordnung zum überordneten Objekt
	* abgebildet werden. Das kann zu einem oder mehreren Bereichen
	* in n:m Beziehung sein rp_bereich2rp_object oder zur Konvertierung
	* (gml_id des documentes oder konvertierung_id)
	* Derzeit umgesetzt in index.php xplankonverter_regeln_anwenden
	* $this->converter->regeln_anwenden($this->formvars['konvertierung_id']);
	*/
	function mapping() {
		$regeln = $this->get_regeln();
		$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'regel_existiert');
		$validierung->konvertierung_id = $this->get('id');
		if ($validierung->regel_existiert($regeln)) {
			$success = true;
			$this->get_plan();
			foreach($regeln AS $regel) {
				$result = $regel->convert($this);
				if (!$result) {
					$success = false;
				}
			}
			$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'alle_sql_ausfuehrbar');
			$validierung->konvertierung_id = $this->get('id');
			$validierung->alle_sql_ausfuehrbar($success);
		}
	}
	
	function validierung_erfolgreich() {
		$sql = "
			SELECT DISTINCT
				status
			FROM
				xplankonverter.validierungsergebnisse
			WHERE
				status = 'Fehler' AND
				konvertierung_id = {$this->get('id')}
		";
		return pg_num_rows(pg_query($this->database->dbConn, $sql)) == 0;
	}

	/*
	* Entfernt alles was mit der Konvertierung zusammenhängt und
	* löscht sich am Ende selbst.
	*/
	function destroy() {
		# Lösche gml-Datei
		$gml_file = new gml_file(XPLANKONVERTER_SHAPE_PATH . $this->get('id') . '/xplan_' . $this->get('id') . '.gml');
		if ($gml_file->exists()) {
			$msg = "\nLösche gml file: ". $gml_file->filename;
			$gml_file->delete();
		}

		# Lösche Regeln, die direkt zur Konvertierung gehören
		$regeln = array();
		$regel = new Regel($this->gui);
		$regeln = $regel->find_where("
			konvertierung_id = {$this->get('id')} AND
			bereich_gml_id IS NULL
		");
		foreach($regeln AS $regel) {
			$regel->konvertierung = $regel->get_konvertierung();
			$regel->destroy();
		}

		# Lösche Plan der Konvertierung
		$plan = $this->get_plan();
		if ($plan) {
			$msg .= "\nRP Plan " . $plan->get('name') . ' gelöscht.';
			$plan->destroy();
		}

		# Lösche GML-Layer Gruppe
		$this->delete_layer_group('GML');

		# Lösche Shapes
		#$shapeFile->deleteDataTable();
		#$shapeFile->delete();

		# Lösche Shape Layer Gruppe

		# Lösche Konvertierung
		$this->delete();
	}


}

?>
