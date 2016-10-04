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
		'GML_ERSTELLUNG_ERR' => 'GML-Erstellung abgebrochen'
	);

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
		$plan = new RP_Plan($this->gui);
		$plan = $plan->find_where('konvertierung_id = ' . $this->get('id'));
		return (count($plan) > 0 ? $plan[0] : array());
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
		$sql = "
			SELECT DISTINCT
			  lower(r.class_name) AS class_name
			FROM
			  xplankonverter.regeln r LEFT JOIN
			  xplan_gml.rp_bereich b ON r.bereich_gml_id = b.gml_id
			WHERE
			  b.konvertierung_id = {$this->get('id')} OR
			  r.konvertierung_id = {$this->get('id')}
		";
		#echo '<br>get_class_names in konvertierung: ' . $sql;
		$result = pg_fetch_all(
			pg_query($this->database->dbConn, $sql)
		);

		if ($result) {
			$class_names = array_map(
				function($row) {
					return 'xplan_gml.' . $row['class_name'];
				},
				$class_names
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
		$layer_group_id = $this->get(strtolower($layer_type) . '_layer_group_id');
		if (empty($layer_group_id)) {
			$layerGroup = new MyObject($this->gui->database, 'u_groups');
			$layerGroup->create(array(
				'Gruppenname' => $this->get('bezeichnung') . ' ' . $layer_type
			));
			$this->set(strtolower($layer_type) . '_layer_group_id', $layerGroup->get('id'));
			$this->update();
		}
		return $this->get(strtolower($layer_type) . '_layer_group_id');
	}

	/*
	* Diese Funktion löscht alle zuvor für diese Konvertierung angelegten
	* XPlan GML Datensätze, Beziehungen und Validierungsergebnisse
	*/
	function reset_mapping() {
		# Lösche vorhandene Validierungsergebnisse der Konvertierung
		Validierungsergebnis::delete_by_id($this->gui, 'konvertierung_id', $this->get('id'));

		# Lösche vorhandene Datenobjekte der Konvertierung
		foreach($this->get_class_names() AS $class_name) {
			$sql = "
				DELETE FROM
					{$class_name}
				WHERE
					konvertierung_id = {$this->get('id')}
			";
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
		$validierung->regel_existiert($regeln);

		foreach($regeln AS $regel) {
			$regel->convert($this->get('id'));
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
			$regel->destroy();
		}

		# Lösche Plan der Konvertierung
		# Lösche Plan
		$plan = $konvertierung->get_plan();
		$msg .= "\nRP Plan " . $plan->get('name') . ' gelöscht.';
		$plan->destroy();

		# Lösche GML-Layer Gruppe
		$konvertierung->delete_layer_group('GML');
	
		# Lösche Shapes
		#$shapeFile->deleteDataTable();
		#$shapeFile->delete();

		# Lösche Shape Layer Gruppe
	}


}

?>
