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

		foreach($this->get_bereiche() AS $bereich) {
			$regeln = array_merge(
				$regeln,
				$regel->find_where("bereich_gml_id = '{$bereich->get('gml_id')}'")
			);
		}

		return $regeln;
	}

	function get_bereiche() {
		#echo 'get_bereiche in konvertierung';
		$bereich = new RP_Bereich($this->gui);
		return $bereich->find_where('konvertierung_id = ' . $this->get('id'));
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
		Validierungsergebnis::delete_by_id($this->gui, 'konvertierung_id', $this->get('id'));
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
		$class_names = array_map(
			function($row) {
				return 'xplan_gml.' . $row['class_name'];
			},
			pg_fetch_all(
				pg_query($this->database->dbConn, $sql)
			)
		);
		#echo '<br>';
		#var_dump($class_names);
		return $class_names;
	}
}

?>
