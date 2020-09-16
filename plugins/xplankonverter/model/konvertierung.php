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
		'ANGABEN_VOLLSTAENDIG'  => 'Angaben vollständig',
		'IN_KONVERTIERUNG'      => 'in Konvertierung',
		'KONVERTIERUNG_OK'      => 'Konvertierung abgeschlossen',
		'KONVERTIERUNG_ERR'     => 'Konvertierung abgebrochen',
		'IN_GML_ERSTELLUNG'     => 'in GML-Erstellung',
		'GML_ERSTELLUNG_OK'     => 'GML-Erstellung abgeschlossen',
		'GML_ERSTELLUNG_ERR'    => 'GML-Erstellung abgebrochen',
		'IN_INSPIRE_GML_ERSTELLUNG'  => 'in INSPIRE-GML-Erstellung',
		'INSPIRE_GML_ERSTELLUNG_OK'  => 'INSPIRE-GML-Erstellung abgeschlossen',
		'INSPIRE_GML_ERSTELLUNG_ERR' => 'INSPIRE-GML-Erstellung abgebrochen'
	);
	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, Konvertierung::$schema, Konvertierung::$tableName);
	}

	public static	function find_by_id($gui, $by, $id, $select = '*') {
		$konvertierung = new Konvertierung($gui);
		$konvertierung->select = $select;
		$konvertierung->find_by($by, $id);
		$konvertierung->debug->show('Found Konvertierung with planart: ' . $konvertierung->get('planart'), Konvertierung::$write_debug);
		if ($konvertierung->get('planart') != '') {
			$konvertierung->get_plan();
		}
		return $konvertierung;
	}

	public static	function find_where_with_plan($gui, $where, $order = '') {
		$konvertierung = new Konvertierung($gui);
		$konvertierungen = $konvertierung->find_where($where, $order);
		array_walk(
			$konvertierungen,
			function($k) {
				if ($k->get('planart') != '') {
					$k->get_plan();
				}
			}
		);
		return $konvertierungen;
	}

	public static function find_by_document($gui, $document) {
		$parts = explode('_', APPLVERSION);
		$dev = (trim(end($parts),'/') == 'dev' ? '_dev' : '');
		$path = pathinfo($_REQUEST['document']);
		$konvertierung = new Konvertierung($gui);
		switch (strToLower($path['extension'])) {
			case 'gml' :
				$konvertierungen = $konvertierung->find_where('id = ' . explode('_', $path['filename'])[1] . ' AND veroeffentlicht');
				if (count($konvertierungen) == 0) return false;
				$konvertierung = $konvertierungen[0];
				$konvertierung->exportfile = '/var/www/data' . $dev . '/upload/xplankonverter/' . $konvertierung->get('id') . '/xplan_gml' . $document;
				$konvertierung->contenttype = 'text/xml';
				return $konvertierung;
			case 'pdf' :
				$sql = "
					SELECT
						*
					FROM
						(
							SELECT
								k.*,
								(unnest(p.externereferenz)).referenzname
							FROM
								xplan_gml.bp_plan p JOIN
								xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
						) sub
					WHERE
						sub.referenzname = '" . $path['basename'] . "' AND
						sub.veroeffentlicht
				";
				#echo '<br>Sql zur Abfrage der Konvertierung: ' . $sql;
				$rows = $konvertierung->getSQLResults($sql)[0];
				if (count($rows) == 0) return false;
				$konvertierung->data = $rows[0];
				$konvertierung->exportfile = '/var/www/data' . $dev . '/xplankonverter/plaene' . $document;
				$konvertierung->contenttype = 'application/pdf';
				return $konvertierung;
			default :
				return false;
		}
	}

	function create($anzeige_name, $epsg_code, $input_epsg_code, $planart, $stelle_id, $user_id) {
		$sql = "
			INSERT INTO " . $this->schema . "." . $this->tableName . " (
				bezeichnung, epsg, input_epsg, output_epsg, planart, stelle_id, user_id
			) VALUES ( 
				'" . $anzeige_name . "',
				'" . $epsg_code . "'::xplankonverter.epsg_codes,
				'" . $input_epsg_code . "'::xplankonverter.epsg_codes,
				'" . $epsg_code . "'::xplankonverter.epsg_codes,
				'" . $planart . "',
				" . $stelle_id . ",
				" . $user_id . "
		)";
		$this->debug->show('Create new konvertierung with sql: ' . $sql, Konvertierung::$write_debug);
		$query = pg_query($this->database->dbConn, $sql);
		$oid = pg_last_oid($query);
		if (empty($oid)) {
			$this->lastquery = $query;
		}
		else {
			$sql = "
				SELECT
					*
				FROM
					" . $this->schema . "." . $this->tableName . "
				WHERE
					oid = " . $oid . "
			";
			$this->debug->show('Query created oid with sql: ' . $sql, Konvertierung::$write_debug);
			$query = pg_query($this->database->dbConn, $sql);
			$row = pg_fetch_assoc($query);
			$this->set($this->identifier, $row[$this->identifier]);
		}
		$this->debug->show('Konvertierung created with ' . $this->identifier . ': '. $this->get($this->identifier), Konvertierung::$write_debug);
		return $this->get($this->identifier);
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
		$this->debug->show('Klasse: ' . $this->plan->umlName, Konvertierung::$write_debug);
		$sql = "
			SELECT
				*
			FROM
				xplan_gml." . $this->plan->tableName . "
			WHERE
				konvertierung_id = " . $this->get('id')
		;
		$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
		$export_class->ogr2ogr_export($sql, '"ESRI Shapefile"', $path . '/' . $this->plan->umlName . '.shp', $this->database);
	}

	function create_export_file($file_type) {
		$path = $this->get_file_path($file_type);
		// -j for removing directory structure in .zip
		exec(ZIP_PATH . ' -j ' . $path . ' ' . $path . '/*');
		
		$exportfile = $path . '.zip';
		return $exportfile;
	}

	function send_export_file($exportfile, $contenttype) {
		header('Content-type: ' . $contenttype);
#		header("Content-disposition:  attachment; filename=" . basename($exportfile));
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

	function get_regeln($plan) {
		$this->debug->show('get_regeln', Konvertierung::$write_debug);

		$regeln = array();
		$regel = new Regel($this->gui);
		$regeln_ohne_bereich = $regel->find_where("
			konvertierung_id = {$this->get('id')} AND
			bereich_gml_id IS NULL
		");
		$this->debug->show(count($regeln_ohne_bereich) . ' Regeln ohne Bereich gefunden.', Konvertierung::$write_debug);

		foreach($this->get_bereiche($plan->get('gml_id')) AS $bereich) {
			$regeln_mit_bereich = $regel->find_where("bereich_gml_id = '{$bereich->get('gml_id')}'");
			$this->debug->show(count($regeln_mit_bereich) . ' Regeln mit Bereich gefunden.', Konvertierung::$write_debug);
			// needs to be merged with regeln to merge multiple bereiche
			$regeln = array_merge(
				$regeln,
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
			$this->debug->show('get_plan with planart: ' . $this->get('planart') . ' for konvertierung: ' . $this->get('id'), Konvertierung::$write_debug);
			$plan = new XP_Plan($this->gui, $this->get('planart'));
			$plan = $plan->find_where('konvertierung_id = ' . $this->get('id'));
			$this->debug->show('found ' . count($plan) . ' Pläne', Konvertierung::$write_debug);
			if (count($plan) > 0) {
				$this->plan = $plan[0];
				$this->debug->show('get_plan assign first plan with planart: ' . $this->plan->planart . ' gml_id: ' . $this->plan->get('gml_id') . ' to Konvertierung.', Konvertierung::$write_debug);
			}
			else {
				$this->plan = false;
			}
		}
		return $this->plan;
	}

	function get_bereiche($plan_id) {
		#echo 'get_bereiche';
		$bereich = new XP_Bereich($this->gui, $this->get('planart'));
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
				xplan_gml." . $this->plan->planartAbk . "_bereich b ON r.bereich_gml_id = b.gml_id LEFT JOIN
				xplan_gml." . $this->plan->planartAbk . "_plan bp ON b.gehoertzuplan = bp.gml_id::text LEFT JOIN
				xplan_gml." . $this->plan->planartAbk . "_plan rp ON r.konvertierung_id = rp.konvertierung_id
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
		$this->debug->show('<br>Setze status in Konvertierung.', Konvertierung::$write_debug);
		if ($new_status == '') {
			$sql = "
				SELECT DISTINCT
					CASE
						WHEN p.gml_id IS NOT NULL OR r.id IS NOT NULL THEN true
						ELSE false
					END AS plan_or_regel_assigned
				FROM
					xplankonverter.konvertierungen k LEFT JOIN
					xplan_gml." . strtolower(substr($this->get('planart'), 0, 2)) . "_plan p ON k.id = p.konvertierung_id LEFT JOIN
					xplankonverter.regeln r ON k.id = r.konvertierung_id
				WHERE
					k.id = {$this->get('id')}
			";
			$this->debug->show('<br>Setze Status mit sql: ' . $sql, Konvertierung::$write_debug);
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
		$this->debug->show('Konvertierung create_layer_group layer_type: ' . $layer_type, Konvertierung::$write_debug);
		$layer_group_id = $this->get(strtolower($layer_type) . '_layer_group_id');
		if (empty($layer_group_id)) {
			$layerGroup = new MyObject($this->gui, 'u_groups');
			if ($layer_type == 'GML') {
				$layerGroup = $layerGroup->find_by('Gruppenname', 'XPlanung');
				$layerGroup->create(array(
					'Gruppenname' => 'XPlanung'
				));
			}
			else {
				$layerGroup->create(array(
					'Gruppenname' => $this->get('bezeichnung') . ' ' . $layer_type
				));
			}
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
		$this->debug->show('delete_layer_group typ: ' . $layer_type, Konvertierung::$write_debug);
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
			;";
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
	* Diese Funktion prüft die Konformitätsbedingungen für Plan und Bereichsobjekte und
	* führt das Mapping zwischen den Shape Dateien
	* und den in den Regeln definierten XPlan GML Features durch.
	* Jedes im Mapping erzeugte Feature bekommt eine eindeutige gml_id.
	* Darüber hinaus muss die Zuordnung zum überordneten Objekt
	* abgebildet werden. Das ist der Bereich oder die Konvertierung über die 
	* gml_id des objektes oder die konvertierung_id.
	* Derzeit umgesetzt in index.php xplankonverter_regeln_anwenden
	* $this->converter->regeln_anwenden($this->formvars['konvertierung_id']);
	*/
	function mapping() {
		$regeln = $this->get_regeln($this->plan);

		// validate Plan

		// validate Bereiche
		$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'detaillierte_requires_bedeutung');
		$validierung->konvertierung_id = $this->get('id');
		$bereiche = $this->plan->get_bereiche();
		foreach($bereiche AS $bereich) {
			$validierung->detaillierte_requires_bedeutung($bereich);
		}

		if(!empty($regeln)){
			$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'regel_existiert');
			$validierung->konvertierung_id = $this->get('id');
			if ($validierung->regel_existiert($regeln)) {
				$success = true;
				foreach($regeln AS $regel) {
					$result = $regel->validate($this);
					if (!$result) {
						$success = false;
					}
					else {
						$result = $regel->convert($this);
						// TODO: Fix this, currently no gids break the result
						/*if (!empty($result)) {
							if($regel->is_source_shape_or_gmlas($regel) != 'gmlas') {
								$regel->rewrite_gml_ids($result);
							}
						}*/
					}
				}
				$alle_sql_ausfuehrbar = true;
				$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'alle_sql_ausfuehrbar');
				$validierung->konvertierung_id = $this->get('id');
				foreach ($regeln AS $regel) {
					if (!$validierung->sql_ausfuehrbar($regel)) {
						$alle_sql_ausfuehrbar = false;
					}
				}
				$validierung->alle_sql_ausfuehrbar($alle_sql_ausfuehrbar);

				if ($alle_sql_ausfuehrbar) {
					# Prüft die Konformitäten der Klassen der Konvertierung für die aktuelle Version
					foreach ($this->get_konformitaetsbedingungen($this->get_version_from_ns_uri(XPLAN_NS_URI)) AS $bedingung) {
						foreach ($bedingung['konformitaet']->validierungen AS $validierung) {
							$validierung->validiere_konformitaet($this->get('id'), $bedingung);
						}
					}

					if (in_array($this->get('planart'), array('BP-Plan', 'FP-Plan', 'SO-Plan'))) {
						# Flächenschlussprüfung
						$this->clearTopology();
						# Create topology of plan objects
						$this->createTopology();
						$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'flaechenschluss_ueberlappungen');
						$validierung->konvertierung_id = $this->get('id');
						$validierung->flaechenschluss_ueberlappungen($this->plan);
						$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'flaechenschluss_luecken');
						$validierung->konvertierung_id = $this->get('id');
						$validierung->flaechenschluss_luecken($this->plan);
					}
				}
			}
		}
	}

	/**
	* Return the xplan version number from the xplan_ns_uri constants as float
	*/
	function get_version_from_ns_uri($uri) {
		return floatval(implode('.', array_slice(explode('/', $uri), -2)));
	}

	/**
	* Return konformitaetsbedingungen der Klassen der Konvertierung in der angegebenen XPlanung-Version
	*/
	function get_konformitaetsbedingungen($version) {
		$sql = "
			SELECT
				name, konvertierung_id, nummer, version_von, version_bis, inhalt, bezeichnung
			FROM
				xplankonverter.konformitaeten_der_konvertierungen
			WHERE
				" . $version . " BETWEEN version_von::float AND coalesce(version_bis::float, 99999) AND
				konvertierung_id = " . $this->get('id') . "
		";
		$this->debug->show('sql to find konformitaetsbedingungen for konvertierung_id: ' . $this->get('id') . ' für XPlanung Version: ' . $version, false);
		$query = pg_query($this->database->dbConn, $sql);
		while ($rs = pg_fetch_assoc($query)) {
			$bedingungen[] = array(
				'class_name' => $rs['name'],
				'konformitaet' => Konformitaetsbedingung::find_by_id($this->gui, $rs['nummer'], $rs['version_von'])
			);
		}
		return $bedingungen;
	}


	/**
	* Clear the topology from all flaechenschlussobjekten of the plan
	*/
	function clearTopology() {
		# Lösche die Topologie der flaechenschlussobjekte des Planes
		$sql ="
			UPDATE xplankonverter.flaechenschlussobjekte
			SET topo = topology.clearTopoGeom(topo)
			WHERE
				konvertierung_id = " . $this->get('id') . "
		";
		#echo '<p>SQL zum Löschen der Topology: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim Löschen der Toplogie!');
			return false;
		}

		# Lösche die flaechenschlussobjekte des Plans in temporärer Tabelle flaechenschlussobjekte
		$sql ="
			DELETE FROM xplankonverter.flaechenschlussobjekte
			WHERE
				konvertierung_id = " . $this->get('id') . "
		";
		#echo '<p>SQL zum Löschne die flaechenschlussobjekte: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim Löschen die flaechenschlussobjekte!');
			return false;
		}
	}

	/**
	* Creates a topology from all flaechenschlussobjekten of the plan
	*/
	function createTopology() {
		# Füge vorhandene Flächenschlussobjekte neu in Tabelle flächenschlussobjekte ein
		$sql = "
			INSERT INTO xplankonverter.flaechenschlussobjekte (gml_id, uuid, konvertierung_id, teilpolygon, teilpolygon_nr)
			SELECT
				gml_id,
				uuid,
				konvertierung_id,
				(st_dump(position)).geom AS teilpolygon,
				(st_dump(position)).path[1] AS teilpolygon_nr
			FROM
				xplan_gml.bp_flaechenschlussobjekt
			WHERE
				flaechenschluss AND
				ebene = 0 OR ebene IS NULL AND
				konvertierung_id = " . $this->get('id') . "
		";
		#echo '<p>SQL zur Erzeugung von Topology: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim Anlegen der Toplogie!');
			return false;
		}

		# Füge ein umschließendes Polygon des raeumlichen Geltungsbereiches zur Tabelle flaechenschlussobjekte hinzu
		$sql = "
			INSERT INTO xplankonverter.flaechenschlussobjekte (gml_id, uuid, konvertierung_id, teilpolygon, teilpolygon_nr)
	 		SELECT
				gml_id,
				'räumlicher Geltungsbereich Plan' AS uuid,
				konvertierung_id,
				(st_dump(position)).geom AS teilpolygon,
				(st_dump(position)).path[1] AS teilpolygon_nr
			FROM
			(
				SELECT
					gml_id,
					konvertierung_id,
					ST_Multi(ST_Difference(ST_SetSrid(ST_Buffer(Box2d(raeumlichergeltungsbereich)::geometry, 1), 25833), raeumlichergeltungsbereich)) AS position
				FROM
					xplan_gml.bp_plan
				WHERE
					konvertierung_id = " . $this->get('id') . "
			) plan
		";
		#echo '<p>SQL Füge ein umschließendes Polygon des raeumlichen Geltungsbereiches zur Tabelle flaechenschlussobjekte hinzu: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim hinzufügen eines umschließenden Polygon des raeumlichen Geltungsbereiches zur Tabelle flaechenschlussobjekte!');
			return false;
		}

		# Berechne die topologie der flaechenschlussobjekte
		$sql = "
			UPDATE
				xplankonverter.flaechenschlussobjekte
			SET
				topo = topology.toTopoGeom(teilpolygon, 'flaechenschluss_topology', 1, 0.002)
			WHERE
				konvertierung_id = " . $this->get('id') . "
		";
		#echo '<p>SQL zur Berechnung der topologie der flaechenschlussobjekte: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler bei der Berechnung der Topologie der flaechenschlussobjekte!');
			return false;
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
		$this->debug->show('Lösche gml-Datei', Konvertierung::$write_debug);
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

		# Lösche zugehörige Postgres gmlas und shape schemas
		$this->destroy_gmlas_and_shape_schemas();
		# TODO Lösche Shape Layer + Gruppe

		# Lösche Plan der Konvertierung
		if ($this->plan) {
			$msg .= "\n " . $this->plan->umlName . ' ' . $this->plan->get('name') . ' gelöscht.';
			$this->plan->destroy();
		}

		# Lösche Konvertierung
		$this->delete();
		$this->debug->show($msg, Konvertierung::$write_debug);
	}

	function destroy_gmlas_and_shape_schemas() {
		# Destroy the xplan_shapes_ + $konvertierung_id if it exists
		# Destroy the xplan_gmlas: + $user_id schema if it exists
		$sql = "
			DROP SCHEMA IF EXISTS xplan_shapes_" .  $this->get('id') . " CASCADE;
			DROP SCHEMA IF EXISTS xplan_gmlas_" .  $this->gui->user->id . " CASCADE;
		";
		pg_query($this->database->dbConn, $sql);
	}

}
?>
