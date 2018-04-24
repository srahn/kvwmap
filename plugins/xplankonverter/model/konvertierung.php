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
		'IN_INSPIRE_GML_ERSTELLUNG'  => 'in INSPIRE-GML-Erstellung',
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

	function create_directories() {
		$directories = array(
			'uploaded_shapes',
			'edited_shapes',
			'xplan_gml',
			'xplan_shapes',
			'inspire_gml'
		);

		foreach ($directories AS $directory) {
			$path = $this->get_file_path($directory);
			$this->debug->show('Check if directory: ' . $path . ' exists.', Konvertierung::$write_debug);
			if (!is_dir($path)) {
				$this->debug->show('Create directory', Konvertierung::$write_debug);
				$old = umask(0);
				mkdir($path, 0770, true);
				umask($old);
			}
		}
	}

	function get_file_path($directory) {
		return XPLANKONVERTER_FILE_PATH . $this->get('id') . '/' . $directory;
	}

	function get_file_name($name) {
		$parts = explode('_', $name);
		return $this->get_file_path($name) . '/' . $parts[0] . '_' . $this->get('id') . '.' . $parts[1];
	}

	function create_xplan_shapes() {
		$path = $this->get_file_path('xplan_shapes');
		$this->debug->show('Erzeuge XPlan-Shape-Files in: ' . $path, Konvertierung::$write_debug);
		$export_class = new data_import_export();

		$this->debug->show('Frage XPlan-Klassen ab:', Konvertierung::$write_debug);
		$class_names = $this->get_class_names();

		$this->debug->show('Erzeuge Shape-Dateien für jede Klasse:', Konvertierung::$write_debug);
		foreach ($class_names AS $class_name) {
			$sql = "
				SELECT
					DISTINCT ST_GeometryType(position)
				FROM
					xplan_gml. " . strtolower($class_name). "
				WHERE
					konvertierung_id = " . $this->get('id') . "
			";
			$result = pg_query($this->database->dbConn, $sql);
			//$result = pg_fetch_assoc($query);
			if(pg_num_rows($result) == 0) {
				continue;
			} else {
				while($row = pg_fetch_array($result)){
					if ($row[0] == 'ST_MultiPoint') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_point = "
							SELECT
								*
							FROM
								xplan_gml. " . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get('id') . "
							AND
								ST_GeometryType(position) = 'ST_MultiPoint'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						$export_class->ogr2ogr_export($sql_point, '"ESRI Shapefile"', $path . '/' . $class_name . '_point.shp', $this->database);
					}
					if ($row[0] == 'ST_MultiLineString') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_line = "
							SELECT
								*
							FROM
								xplan_gml. " . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get('id') . "
							AND
								ST_GeometryType(position) = 'ST_MultiLineString'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						$export_class->ogr2ogr_export($sql_line, '"ESRI Shapefile"', $path . '/' . $class_name . '_line.shp', $this->database);
					}
					if ($row[0] == 'ST_MultiPolygon') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_poly = "
							SELECT
								*
							FROM
								xplan_gml. " . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get('id') . "
							AND
								ST_GeometryType(position) = 'ST_MultiPolygon'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						$export_class->ogr2ogr_export($sql_poly, '"ESRI Shapefile"', $path . '/' . $class_name . '_poly.shp', $this->database);
					}
				}
			}
		}

		// Fuer Plan
		$this->debug->show('Klasse: ' . 'RP_Plan', Konvertierung::$write_debug);
		$sql = "
			SELECT
				*
			FROM
				xplan_gml." . strtolower($this->get('planart')) . "
			WHERE
				konvertierung_id = " . $this->get('id')
		;
		$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
		$export_class->ogr2ogr_export($sql, '"ESRI Shapefile"', $path . '/' . RP_Plan . '.shp', $this->database);
	}

	function create_export_file($file_type) {
		$path = $this->get_file_path($file_type);
		exec(ZIP_PATH . ' ' . $path . ' ' . $path . '/*');
		
		$exportfile = $path . '.zip';
		return $exportfile;
	}

	function send_export_file($exportfile, $contenttype) {
		header('Content-type: ' . $contenttype);
		header("Content-disposition:  attachment; filename=" . basename($exportfile));
		header("Content-Length: " . filesize($exportfile));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		readfile($exportfile);
	}

	function files_exists($dir) {
		$dir = $this->get_file_path($dir);
		$this->debug->show('Prüfe ob Dateien im Verzeichnis : ' . $dir . ' vorhanden sind: ', Konvertierung::$write_debug);
		$result = !(count(glob("$dir/*")) === 0);
		$this->debug->show(($result ? 'ja' : 'nein'), Konvertierung::$write_debug);
		return $result;
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
		$this->debug->show('get_regeln', Konvertierung::$write_debug);

		$regeln = array();
		$regel = new Regel($this->gui);
		$regeln_ohne_bereich = $regel->find_where("
			konvertierung_id = {$this->get('id')} AND
			bereich_gml_id IS NULL
		");
		$this->debug->show(count($regeln_ohne_bereich) . ' Regeln ohne Bereich gefunden.', Konvertierung::$write_debug);

		$plan = $this->get_plan();

		foreach($this->get_bereiche($plan->get('gml_id')) AS $bereich) {
			$regeln_mit_bereich = $regel->find_where("bereich_gml_id = '{$bereich->get('gml_id')}'");
			$this->debug->show(count($regeln_mit_bereich) . ' Regeln mit Bereich gefunden.', Konvertierung::$write_debug);
			$regeln = array_merge(
				$regeln_ohne_bereich,
				$regeln_mit_bereich
			);
		}
		$this->debug->show('Insgesamt ' . count($regeln) . ' Regeln gefunden.', Konvertierung::$write_debug);

		foreach($regeln AS $regel) {
			$regel->konvertierung = $regel->get_konvertierung();
		}

		$this->debug->show(implode(
			'<br>',
			array_map(
				function($regel) {
					return '<br>id: ' . $regel->get('id') . '<br>sql: ' . $regel->get('sql');
				},
				$regeln
			)
		), Konvertierung::$write_debug);
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
				r.class_name
			FROM
				xplankonverter.regeln r LEFT JOIN
				xplan_gml.rp_bereich b ON r.bereich_gml_id = b.gml_id left JOIN
				xplan_gml.rp_plan bp ON b.gehoertzuplan = bp.gml_id::text LEFT JOIN
				xplan_gml.rp_plan rp ON r.konvertierung_id = rp.konvertierung_id
			WHERE
				bp.konvertierung_id = " . $this->get('id') . " OR
				rp.konvertierung_id = " . $this->get('id') . "
		";

		$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
		$result = pg_fetch_all(
			pg_query($this->database->dbConn, $sql)
		);

		if ($result) {
			$class_names = array_map(
				function($row) {
					return $row['class_name'];
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
		$this->debug->show('<br>Setze status in Konvertierung.', false);
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
			$this->debug->show('<br>Setze Status mit sql: ' . $sql, false);
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
		$this->debug->show('delete_layer_group typ: ' . $layer_type, false);
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
					xplan_gml." . strtolower($class_name) . "
				WHERE
					konvertierung_id = $1
			";
			$this->debug->show("Delete Objekte von {$class_name} with konvertierung_id " . $this->get('id') . ' und sql: ' . $sql . ' in reset Mapping.', Konvertierung::$write_debug);
			#echo '<br>sql: ' . $sql;
			$query = pg_query_params($this->database->dbConn, $sql, array($this->get('id')));
		}

		# Lösche vorhandene xplan_shapes Export-Dateien
		$file = XPLANKONVERTER_FILE_PATH . $this->get('id') . '/xplan_shapes.zip';
		if (file_exists($file)) unlink($file);
		array_map('unlink', glob($this->get_file_path('xplan_shapes') . '/*'));
	}

	/*
	* Diese Funktion führt das Mapping zwischen den Shape Dateien
	* und den in den Regeln definierten XPlan GML Features durch.
	* Jedes im Mapping erzeugte Feature bekommt eine eindeutige gml_id.
	* Darüber hinaus muss die Zuordnung zum überordneten Objekt
	* abgebildet werden. Das ist der Bereich oder die Konvertierung über die 
	* gml_id des objektes oder die konvertierung_id.
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
				$result = $regel->validate($this);
				if (!$result) {
					$success = false;
				}
				else {
					$result = $regel->convert($this);
					if (!empty($result)) {
						$regel->rewrite_gml_ids($result);
					}
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
		$gml_file = new gml_file($this->get_file_name('xplan_gml'));
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
