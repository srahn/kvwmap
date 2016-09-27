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
	* XPlan GML Datensätze und Beziehungen im Schema gml_classes
	*/
	function resetMapping() {
		#    $tables = get all table names of xplan gml feature types
		foreach($tables AS $table) {
			$sql = "
				DELETE FROM
			" . $table . "
			WHERE
			konvertierung_id = " . $this->get('id') . "
			";
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
		# finde alle regeln, die direkt der Konvertierung zugeordnet wurden
		$regeln = $this->getRegeln();
		foreach($regeln AS $regel) {
			$regel->convert();
		}
	}

	function getRegeln() {
		$regel = new Regel($this->gui);
		return $regel->find_where('konvertierung_id = ' . $this->get('id'));
	}

}

?>
