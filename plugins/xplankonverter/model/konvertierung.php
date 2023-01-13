<?php
#############################
# Klasse Konvertierung #
#############################

class Konvertierung extends PgObject {

	static $schema = 'xplankonverter';
	static $tableName = 'konvertierungen';
	static $STATUS = array(
		'IN_ERSTELLUNG'			=> 'in Erstellung',
		'ERSTELLT'					 => 'erstellt',
		//		'IN_VALIDIERUNG'		 => 'in Validierung',
		//		'VALIDIERUNG_ERR'		=> 'Validierung fehlgeschlagen',
		//		'VALIDIERUNG_OK'		 => 'validiert',
		'ANGABEN_VOLLSTAENDIG'	=> 'Angaben vollständig',
		'IN_KONVERTIERUNG'			=> 'in Konvertierung',
		'KONVERTIERUNG_OK'			=> 'Konvertierung abgeschlossen',
		'KONVERTIERUNG_ERR'		 => 'Konvertierung abgebrochen',
		'IN_GML_ERSTELLUNG'		 => 'in GML-Erstellung',
		'GML_ERSTELLUNG_OK'		 => 'GML-Erstellung abgeschlossen',
		'GML_ERSTELLUNG_ERR'		=> 'GML-Erstellung abgebrochen',
		'GML_VALIDIERUNG_OK'		=> 'GML-Validierung abgeschlossen',
		'GML_VALIDIERUNG_ERR'	 => 'GML-Validierung mit Fehlern',
		'GEOWEB_SERVICE_ERSTELLT' => 'GeoWeb-Dienst erstellt',
		'IN_INSPIRE_GML_ERSTELLUNG'	=> 'in INSPIRE-GML-Erstellung',
		'INSPIRE_GML_ERSTELLUNG_OK'	=> 'INSPIRE-GML-Erstellung abgeschlossen',
		'INSPIRE_GML_ERSTELLUNG_ERR' => 'INSPIRE-GML-Erstellung abgebrochen'
	);
	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, Konvertierung::$schema, Konvertierung::$tableName);
	}

	/**
		Function create a geoweb service for the plan
		it uses the gui function wmsExportSenden to create a mapfile
		that have the metadata, layers and filter for that plan
		This service is not acitve per default. It can be published by
		calling function publish_service()
	*/
	function create_geoweb_service($xplan_layers) {
		$gui = $this->gui;
		$planartkuerzel = $this->plan->planartAbk[0];
		$ows_onlineresource = URL . 'ows/' . $this->get('stelle_id') . '/' . $planartkuerzel . 'plan/';

		$gui->class_load_level = 2;
		$gui->loadMap('DataBase');

		# Setze Metadaten
		$extent = $this->plan->get_extent();
		$gui->map->extent->setextent($extent['minx'], $extent['miny'], $extent['maxx'], $extent['maxy']);
    $gui->map->setMetaData("ows_extent", implode(' ', $extent));
		$gui->map->setMetaData("ows_onlineresource", $ows_onlineresource);
		$gui->map->setMetaData("ows_service_onlineresource", $ows_onlineresource);
		$gui->map->web->set('header', '../templates/header.html');

		# Filter Layer, die nicht im Dienst zu sehen sein sollen
		# Und setze bei den anderen die Templates
		$layers_with_content = $this->plan->get_layers_with_content($xplan_layers, $this->get($this->identifier));
		$layernames_with_content = array_keys($layers_with_content);
		$layernames_with_content[] = strtoupper($planartkuerzel) . '-Pläne';
		$layernames_with_content[] = strtoupper($this->plan->planartAbk) . '-Bereiche';
		$layernames_with_content[] = 'Geltungsbereiche';
		#echo '<br>pk layernames_with_content: ' . print_r($layernames_with_content, true);
		$layers_to_remove = array();
		for ($i = 0; $i < $gui->map->numlayers; $i++) {
			$layer = $gui->map->getLayer($i);
			if (in_array($layer->name, $layernames_with_content)) {
				$layer->set('header', '../templates/' . $layer->name . '_head.html');
				$layer->set('template', '../templates/' . $layer->name . '_body.html');
			}
			else {
				$gui->map->removeLayer($i);
				$i--;
			}
		}
		return $this->get('stelle_id') . '/zusammenzeichnung.map';
	}

	public static	function find_by_id($gui, $by, $id, $select = '*') {
		$konvertierung = new Konvertierung($gui);
		$konvertierung->select = $select;
		$konvertierung->find_by($by, $id);
		$konvertierung->debug->show('Found Konvertierung with planart: ' . $konvertierung->get('planart'), Konvertierung::$write_debug);
		if ($konvertierung->get('planart') != '') {
			$konvertierung->get_plan();
			$konvertierung->plan->get_center_coord();
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
		$path = pathinfo($document);
		$konvertierung = new Konvertierung($gui);
		switch (strToLower($path['extension'])) {
			case 'gml' :
				$konvertierungen = $konvertierung->find_where('id = ' . explode('_', $path['filename'])[1] . ' AND veroeffentlicht');
				if (count($konvertierungen) == 0) return false;
				$konvertierung = $konvertierungen[0];
				$konvertierung->exportfile = '/var/www/data' . $dev . '/upload/xplankonverter/' . $konvertierung->get($this->identifier) . '/xplan_gml' . $document;
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
								(unnest(p.externereferenz)).referenzurl
							FROM
								xplan_gml.bp_plan p JOIN
								xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
						) sub
					WHERE
						sub.referenzurl LIKE '%" . $path['basename'] . "%' AND
						" . ($_SESSION['angemeldet'] ? 'true' : "sub.veroeffentlicht") . "
				";
				#echo '<br>Sql zur Abfrage der Konvertierung: ' . $sql;
				$rows = $konvertierung->getSQLResults($sql)[0];
				if (count($rows) == 0) return false;
				$konvertierung->data = $rows[0];
				$konvertierung->exportfile = '/var/www/data' . $dev . '/xplankonverter/plaene' . $document;
				$konvertierung->contenttype = 'application/pdf';
				return $konvertierung;
			case 'jpg' :
				$filename = get_name_from_thump($path['basename']);
				$sql = "
					SELECT
						*
					FROM
						(
							SELECT
								k.*,
								(unnest(p.externereferenz)).referenzurl
							FROM
								xplan_gml.bp_plan p JOIN
								xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
						) sub
					WHERE
						right(sub.referenzurl, position('/' in reverse(sub.referenzurl)) - 1) LIKE '" . $filename . "%' AND
						" . ($_SESSION['angemeldet'] ? 'true' : "sub.veroeffentlicht") . "
				";
				#echo '<br>Sql zur Abfrage der Konvertierung: ' . $sql;
				$rows = $konvertierung->getSQLResults($sql)[0];
				if (count($rows) == 0) return false;
				$konvertierung->data = $rows[0];
				$konvertierung->exportfile = '/var/www/data' . $dev . '/xplankonverter/plaene' . $document;
				$konvertierung->contenttype = 'image/jpg';
				return $konvertierung;
			default :
				return false;
		}
	}

	public static function find_zusammenzeichnungen($gui, $planart, $order_by) {
		$zusammenzeichnungen = array();
		$konvertierung = new Konvertierung($gui);
		$sql = "
			SELECT
				k.id
			FROM
				xplankonverter.konvertierungen k JOIN
				xplan_gml.xp_plan p ON k.id = p.konvertierung_id
			WHERE
				p.zusammenzeichnung AND
				k.stelle_id = " . $gui->Stelle->id . " AND
				k.planart = '" . $planart . "'
			ORDER BY " . $order_by . "
		";
		$results = $konvertierung->getSQLResults($sql);
		foreach ($results AS $result) {
			$zusammenzeichnungen[] = Konvertierung::find_by_id($gui, 'id', $result['id']);
		}
		return $zusammenzeichnungen;
	}

	function create($anzeige_name = '', $epsg_code = '', $input_epsg_code = '', $planart = '', $stelle_id = '', $user_id = '') {
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
				" . $user_id . ")
			RETURNING " . $this->identifier . "
		";
		$this->debug->show('Create new konvertierung with sql: ' . $sql, Konvertierung::$write_debug);
		$query = pg_query($this->database->dbConn, $sql);
		$row = pg_fetch_assoc($query);
		$oid = $row[$this->identifier];
		#echo '<br>oid: ' . $oid;
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
					" . $this->identifier . " = " . $oid . "
			";
			$this->debug->show('Query created ' . $this->identifier . ' with sql: ' . $sql, Konvertierung::$write_debug);
			$query = pg_query($this->database->dbConn, $sql);
			$row = pg_fetch_assoc($query);
			$this->set($this->identifier, $row[$this->identifier]);
		}
		$this->debug->show('Konvertierung created with ' . $this->identifier . ': '. $this->get($this->identifier), Konvertierung::$write_debug);
		#echo '<br>return identifier: ' . $this->identifier . ': ' . $this->get($this->identifier);
		return $this->get($this->identifier);
	}

	function create_directories() {
		$directories = array(
			'uploaded_shapes',
			'edited_shapes',
			'uploaded_xml_gml',
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
				mkdir($path, 0777, true);
				umask($old);
			}
		}
	}

	function get_file_path($directory) {
		return XPLANKONVERTER_FILE_PATH . $this->get($this->identifier) . '/' . $directory;
	}

	function get_file_name($name) {
		$parts = explode('_', $name);
		return $this->get_file_path($name) . '/' . $parts[0] . '_' . $this->get($this->identifier) . '.' . $parts[1];
	}

	/*
	* function check if files of file_type
	* exists for download in this konvertierung
	* return true if yes and false if not
	*/
	function files_exists($file_type) {
		$result = false;
		switch ($file_type) {
			case 'uploaded_shape_files' : {
				$result = $this->download_files_exists('uploaded_shapes');
			} break;
			case 'edited_shape_files' : {
				$sql = "
					SELECT
						table_name
					FROM
						information_schema.tables
					WHERE
						table_schema = 'xplan_shapes_" . $this->get($this->identifier) . "'
				";
				#echo 'SQL zum Abfragen des Tabellennamen: ' . $sql; exit;
				$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
				$result = (pg_num_rows(pg_query($this->database->dbConn, $sql)) > 0);
			} break;
			case 'zusammenzeichnung_gml' : {
				$filename = $this->get_file_name('zusammenzeichnung_gml');
				$result = file_exists($filename);
			} break;
			case 'zusammenzeichnung-neu_gml' : {
				$filename = $this->get_file_name('zusammenzeichnung-neu_gml');
				$result = file_exists($filename);
			} break;
			case 'xplan_gml' : {
				$filename = $this->get_file_name('xplan_gml');
				$result = file_exists($filename);
			} break;
			case 'xplan_shape_files' : {
				# Fragt die classen ab, die für die Konvertierung registriert sind und prüfe ob es dazu Daten gibt.
				# Wenn ja, setze result = true und Abbruch der Schleife
				foreach ($this->get_class_names() AS $class_name) {
					$sql = "
						SELECT
							count(*) AS num_objects
						FROM
							xplan_gml. " . strtolower($class_name) . "
						WHERE
							konvertierung_id = " . $this->get($this->identifier) . "
					";
					#echo 'SQL zur Abfrage der Anzahl der Objekte in Tabelle: xplan_gml.' . strtolower($class_name) . ': ' . $sql;
					$rs = pg_fetch_assoc(pg_query($this->database->dbConn, $sql));
					if ($rs['num_objects'] > 0) {
						$result = true;
						break;
					}
				}
			} break;
			case 'inspire_gml' : {
				$filename = $this->get_file_name('inspire_gml');
				$result = file_exists($filename);
			} break;
			default : {
				
			}
		}
		return $result;
	}

	function zusammenzeichnung_exists() {
		return $this->files_exists('zusammenzeichnung_gml');
	}

	function neue_zusammenzeichnung_exists() {
		return $this->files_exists('zusammenzeichnung-neu_gml');
	}

	function create_edited_shapes() {
		$path = $this->get_file_path('edited_shapes');
		$this->debug->show('Erzeuge Edited-Shape-Files in. ' . $path, Konvertierung::$write_debug);
		$export_class = new data_import_export();

		$this->debug->show('Frage Source-Tabellen ab: ', Konvertierung::$write_debug);
		$sql = "
			SELECT
				table_name
			FROM
				information_schema.tables
			WHERE
				table_schema = 'xplan_shapes_" . $this->get($this->identifier) . "'
		";
		#echo 'SQL zum Abfragen des Tabellennamen: ' . $sql; exit;

		$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
		$result = pg_fetch_all(
			pg_query($this->database->dbConn, $sql)
		);

		$class_names = [];
		if ($result) {
			$class_names = array_map(
				function($row) {
					return $row['table_name'];
				},
				$result
			);
		}
		else {
			$class_names = array();
		}

		$this->debug->show('Erzeuge Shape-Dateien für jede Klasse:', Konvertierung::$write_debug);
		foreach ($class_names AS $class_name) {
			$result = pg_query($this->database->dbConn, $sql);
			//$result = pg_fetch_assoc($query);
			if (pg_num_rows($result) == 0) {
				continue;
			}
			else {
				while ($row = pg_fetch_array($result)) {
					$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
					$sql = "
						SELECT
							*
						FROM
							xplan_shapes_" . $this->get($this->identifier) . "." . $class_name . "
					";
					$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
					$export_class->ogr2ogr_export($sql, '"ESRI Shapefile"', $path . '/' . ltrim($class_name, 'shp_') . '.shp', $this->database);
				}
			}
		}
	}

	function create_xplan_shapes() {
		$path = $this->get_file_path('xplan_shapes');

		// Delete existing shapes
		$this->debug->show('Lösche xplan-konforme-shape-Dateien', Konvertierung::$write_debug);
		$files = glob($path);
		foreach ($files as $file){
			if(is_file($file)) {
				unlink($file);
			}
		}
		// Delete existing zip if exists
		$zip_file = $this->get_file_path('') . '/' . 'xplan_shapes.zip';
		if(file_exists($zip_file)) {
			unlink($zip_file);
		}

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
					konvertierung_id = " . $this->get($this->identifier) . "
			";
			$result = pg_query($this->database->dbConn, $sql);
			// Get src srid of table as its not necessarily konvertierung dependent
			$sql_srid = "
				SELECT
					DISTINCT ST_SRID(position)
				FROM
					xplan_gml. " . strtolower($class_name). "
				WHERE
					konvertierung_id = " . $this->get($this->identifier) . "
				LIMIT 1
			";
			$result_srid = pg_query($this->database->dbConn, $sql_srid);
			// fallback input-epsg konvertierung
			$src_srid = !empty(pg_fetch_result($result_srid, 0, 0)) ? pg_fetch_result($result_srid, 0, 0) : $this->get('output_epsg');

			//$result = pg_fetch_assoc($query);
			if (pg_num_rows($result) == 0) {
				continue;
			} else {
				while ($row = pg_fetch_array($result)){
					if ($row[0] == 'ST_MultiPoint') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_point = "
							SELECT
								*
							FROM
								xplan_gml." . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get($this->identifier) . "
							AND
								ST_GeometryType(position) = 'ST_MultiPoint'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						$export_class->ogr2ogr_export($sql_point, '"ESRI Shapefile" -s_srs epsg:' . $src_srid . ' -t_srs epsg:' . $this->get('output_epsg') . ' -nlt MULTIPOINT', $path . '/' . $class_name . '_point.shp', $this->database);
					}
					if ($row[0] == 'ST_MultiLineString') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_line = "
							SELECT
								*
							FROM
								xplan_gml." . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get($this->identifier) . "
							AND
								ST_GeometryType(position) = 'ST_MultiLineString'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						$export_class->ogr2ogr_export($sql_line, '"ESRI Shapefile" -s_srs epsg:' . $src_srid . ' -t_srs epsg:' . $this->get('output_epsg') . ' -nlt MULTILINESTRING ', $path . '/' . $class_name . '_line.shp', $this->database);
					}
					if ($row[0] == 'ST_MultiPolygon') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_poly = "
							SELECT
								*
							FROM
								xplan_gml." . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get($this->identifier) . "
							AND
								ST_GeometryType(position) = 'ST_MultiPolygon'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						$export_class->ogr2ogr_export($sql_poly, '"ESRI Shapefile" -s_srs epsg:' . $src_srid . ' -t_srs epsg:' . $this->get('output_epsg') . ' -nlt MULTIPOLYGON', $path . '/' . $class_name . '_poly.shp', $this->database);
					}
				}
			}
		}

		// Fuer Plan
		$this->debug->show('Klasse: ' . $this->plan->umlName, Konvertierung::$write_debug);
		// As plan has multiple geometries in its source table, it must be checked against UML (which only holds one geom field)
		include(PLUGINS . 'xplankonverter/model/TypeInfo.php');
		$typeInfo = new TypeInfo($this->database);
		$uml_attribs = $typeInfo->getInfo($this->plan->tableName);
		foreach ($uml_attribs as $uml_attrib) {
			$select_string .= $uml_attrib['col_name'] . ',';
		}
		$select_string = rtrim($select_string, ',');

		$sql = "
			SELECT " . 
				$select_string . " 
			FROM
				xplan_gml." . $this->plan->tableName . "
			WHERE
				konvertierung_id = " . $this->get($this->identifier)
		;
		$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);

		$sql_srid = "
				SELECT
					DISTINCT ST_SRID(raeumlichergeltungsbereich)
				FROM
					xplan_gml." . $this->plan->tableName . " 
				WHERE
					konvertierung_id = " . $this->get($this->identifier) . " 
				LIMIT 1
			";
		$result_srid = pg_query($this->database->dbConn, $sql_srid);
		// fallback input-epsg konvertierung
		$src_srid = !empty(pg_fetch_result($result_srid, 0, 0)) ? pg_fetch_result($result_srid, 0,0) : $this->get('output_epsg');
		$export_class->ogr2ogr_export($sql, '"ESRI Shapefile" -s_srs epsg:' . $src_srid . ' -t_srs epsg:' . $this->get('output_epsg') . ' -nlt MULTIPOLYGON', $path . '/' . $this->plan->umlName . '.shp', $this->database);
	}

	function create_export_file($file_type) {
		$path = $this->get_file_path($file_type);
		// -j for removing directory structure in .zip
		$cmd = ZIP_PATH . ' -j ' . $path . ' ' . $path . '/*';
		#echo 'cmd: ' . $cmd;
		exec($cmd);

		$exportfile = $path . '.zip';
		return $exportfile;
	}

	function send_export_file($exportfile, $contenttype) {
		header('Content-type: ' . $contenttype);
		header("Content-disposition:	attachment; filename=" . basename($exportfile));
		header("Content-Length: " . filesize($exportfile));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		readfile($exportfile);
	}

	function download_files_exists($dir) {
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
			konvertierung_id = {$this->get($this->identifier)} AND
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
					return '<br>id: ' . $regel->get($this->identifier) . '<br>sql: ' . $regel->get('sql');
				},
				$regeln
			)
		), Konvertierung::$write_debug);
		return $regeln;
	}

	function get_plan() {
		if (!$this->plan) {
			$this->debug->show('get_plan with planart: ' . $this->get('planart') . ' for konvertierung: ' . $this->get($this->identifier), Konvertierung::$write_debug);
			$plan = new XP_Plan($this->gui, $this->get('planart'));
			$plan = $plan->find_where('konvertierung_id = ' . $this->get($this->identifier));
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

	/**
		ToDo: Berücksichtigen, dass neue Pläne ggf. die gleiche gml_id haben können wie alte. Das gilt vor allem für Zusammenzeichnungen.
		Es muss geklärt werden ob das als Fehler abgelehnt wird oder einfach eine eigene intern vergeben wird wenn es die aus dem importierten gml-Dokument schon in der Datenbank gibt.
	*/
	function create_plaene_from_gmlas($table_schema, $planart) {
		$planartAbk = strtolower(substr($planart, 0, 2));
		$planart_as_text = str_replace("_","-",$planart);
		$sql = "
			INSERT INTO xplankonverter.konvertierungen (bezeichnung, status, stelle_id, user_id, geom_precision, gml_layer_group_id, epsg, output_epsg, input_epsg, planart, veroeffentlicht, beschreibung)
			SELECT
				gmlas.xplan_name AS bezeichnung,
				'erstellt' AS status,
				" . $this->gui->Stelle->id . " AS stelle_id,
				" . $this->gui->user->id . " AS user_id,
				" . $this->gui->user->id . " AS geom_precision,
				null AS gml_layer_group_id,
				" . XPLANKONVERTER_DEFAULT_EPSG . "::text::xplankonverter.epsg_codes AS epsg,
				" . XPLANKONVERTER_DEFAULT_EPSG . "::text::xplankonverter.epsg_codes AS output_epsg,
				" . XPLANKONVERTER_DEFAULT_EPSG . "::text::xplankonverter.epsg_codes AS input_epsg,
				'" . $planart_as_text . "' AS planart,
				false AS veroeffentlicht,
				gmlas.id AS beschreibung
			FROM
				" . $table_schema . "." . strtolower($planart) . " gmlas;
		";

		switch ($planart_as_text) {
			case ('BP-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml." . strtolower($planart) . " (
						gml_id, user_id, konvertierung_id, name, nummer, internalid, beschreibung, kommentar, technherstelldatum, genehmigungsdatum, untergangsdatum, aendert,
						wurdegeaendertvon, erstellungsmassstab, bezugshoehe, raeumlichergeltungsbereich, verfahrensmerkmale, , externereferenz, auslegungsenddatum, gemeinde,
						status, plangeber, rechtsstand, auslegungsstartdatum, traegerbeteiligungsstartdatum, aenderungenbisdatum, traegerbeteiligungsenddatum, verfahren,
						sonstplanart, planart, aufstellungsbeschlussdatum, technischerplanersteller, veraenderungssperre, inkrafttretensdatum, durchfuehrungsvertrag,
						staedtebaulichervertrag, erschliessungsvertrag, rechtsverordnungsdatum, ausfertigungsdatum, satzungsbeschlussdatum, versionbaunvodatum, versionbaunvotext,
						versionbaugbdatum, versionbaugbtext, versionsonstrechtsgrundlagedatum, versionsonstrechtsgrundlagetext, hoehenbezug, gruenordnungsplan
					)
					SELECT
						trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid AS gml_id,
						" . $this->user->id . " AS user_id,
						k.id AS konvertierung_id,
						gmlas.xplan_name AS name,
						gmlas.nummer AS nummer,
						gmlas.internalid AS internalid,
						gmlas.beschreibung AS beschreibung,
						gmlas.kommentar AS kommentar,
						to_char(gmlas.technherstelldatum, 'DD.MM.YYYY')::date AS technherstelldatum,
						to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY')::date AS genehmigungsdatum,
						to_char(gmlas.untergangsdatum, 'DD.MM.YYYY')::date AS untergangsdatum,
						CASE
							WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS aendert,
						CASE
							WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS wurdegeaendertvon,
						gmlas.erstellungsmassstab AS erstellungsmassstab,
						gmlas.bezugshoehe AS bezugshoehe,
						ST_Multi(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS raeumlichergeltungsbereich,
						CASE
							WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
								ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[]
							ELSE NULL
						END AS verfahrensmerkmale,
						CASE
							WHEN count_externeref > 0
							THEN externeref.externereferenz
							ELSE NULL
						END AS externereferenz,
						ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[] AS auslegungsenddatum,
						ARRAY[(g.ags,g.rs,g.gemeindename,g.ortsteilname)]::xplan_gml.xp_gemeinde[] AS gemeinde,
						(gmlas.status_codespace, gmlas.status, NULL)::xplan_gml." . $planartAbk . "_status AS status,
						(pg.name, pg.kennziffer)::xplan_gml.xp_plangeber AS plangeber,
						gmlas.rechtsstand::xplan_gml." . $planartAbk . "_rechtsstand AS rechtsstand,
						ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[] AS auslegungsstartdatum,
						ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[] AS traegerbeteiligungsstartdatum,
						to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY')::date AS aenderungenbisdatum,
						ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[] AS traegerbeteiligungsenddatum,
						gmlas.verfahren::xplan_gml." . $planartAbk . "_verfahren AS verfahren,
						(gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml." . $planartAbk . "_sonstplanart AS sonstplanart,
						gmlas.planart::xplan_gml." . strtolower($planart) . "art AS planart,
						to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY')::date AS aufstellungsbeschlussdatum,
						gmlas.technischerplanersteller AS technischerplanersteller,
						gmlas.veraenderungssperredatum AS traegerbeteiligungsenddatum,
						gmlas.veraenderungssperre AS veraenderungssperre,
						gmlas.inkrafttretensdatum AS inkrafttretensdatum,
						gmlas.durchfuehrungsvertrag AS durchfuehrungsvertrag,
						gmlas.staedtebaulichervertrag AS staedtebaulichervertrag,
						gmlas.erschliessungsvertrag AS erschliessungsvertrag,
						gmlas.rechtsverordnungsdatum AS rechtsverordnungsdatum,
						gmlas.ausfertigungsdatum AS ausfertigungsdatum,
						gmlas.satzungsbeschlussdatum AS satzungsbeschlussdatum,
						gmlas.versionbaunvodatum AS versionbaunvodatum,
						gmlas.versionbaunvotext AS versionbaunvotext,
						gmlas.versionbaugbdatum AS versionbaugbdatum,
						gmlas.versionbaugbtext AS versionbaugbtext, 
						gmlas.versionsonstrechtsgrundlagedatum AS versionsonstrechtsgrundlagedatum,
						gmlas.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext,
						gmlas.hoehenbezug AS hoehenbezug,
						gmlas.gruenordnungsplan AS gruenordnungsplan
					FROM
						" . $table_schema . "." . strtolower($planart) . " gmlas JOIN
						xplankonverter.konvertierungen k ON gmlas.id = k.beschreibung LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_gemeinde gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
						" . $table_schema . ".xp_gemeinde g ON gemeindelink.xp_gemeinde_pkid = g.ogr_pkid LEFT JOIN
						(
							SElECT
								COUNT(*) AS count_externeref,
								externereferenzlink_sub.parent_id,
								array_agg((e_sub.georefurl,
										(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.art::xplan_gml.xp_externereferenzart,
										e_sub.informationssystemurl,
										e_sub.referenzname,
										e_sub.referenzurl,
										(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.beschreibung,
										to_char(e_sub.datum, 'DD.MM.YYYY'),
										e_sub.typ::xplan_gml.xp_externereferenztyp
									)::xplan_gml.xp_spezexternereferenz) AS externereferenz
							FROM
								" . $table_schema . "." . strtolower($planart) . "_externereferenz externereferenzlink_sub LEFT JOIN
								" . $table_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
							GROUP BY
								externereferenzlink_sub.parent_id
						) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
						" . $table_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
						" . $table_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
						" . $table_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_plangeber pg ON gmlas.plangeber_xp_plangeber_pkid = pg.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_auslegungsstartdatum alsd ON gmlas.id = alsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_auslegungsenddatum aled ON gmlas.id = aled.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_traegerbeteiligungsstartdatum tbsd ON gmlas.id = tbsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_traegerbeteiligungsenddatum tbed ON gmlas.id = tbed.parent_id;
				";
				# ToDo weitere relationen von bp_plan_ ... aus gmlas_tmp_41 im Left join einbinden
			} break;

			case ('FP-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml." . strtolower($planart) . " (
						gml_id, konvertierung_id, name, nummer, internalid, beschreibung, kommentar, technherstelldatum, genehmigungsdatum, untergangsdatum, aendert,
						wurdegeaendertvon, erstellungsmassstab, bezugshoehe, raeumlichergeltungsbereich, verfahrensmerkmale, externereferenz,
						auslegungsenddatum, gemeinde, status, sachgebiet, plangeber, rechtsstand, wirksamkeitsdatum, auslegungsstartdatum,
						traegerbeteiligungsstartdatum, entwurfsbeschlussdatum, aenderungenbisdatum, traegerbeteiligungsenddatum, verfahren, sonstplanart,
						planart, planbeschlussdatum, aufstellungsbeschlussdatum
					)
					SELECT
						trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid AS gml_id,
						k.id AS konvertierung_id,
						gmlas.xplan_name AS name,
						gmlas.nummer AS nummer,
						gmlas.internalid AS internalid,
						gmlas.beschreibung AS beschreibung,
						gmlas.kommentar AS kommentar,
						to_char(gmlas.technherstelldatum, 'DD.MM.YYYY')::date AS technherstelldatum,
						to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY')::date AS genehmigungsdatum,
						to_char(gmlas.untergangsdatum, 'DD.MM.YYYY')::date AS untergangsdatum,
						CASE
							WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS aendert,
						CASE
							WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS wurdegeaendertvon,
						gmlas.erstellungsmassstab AS erstellungsmassstab,
						gmlas.bezugshoehe AS bezugshoehe,
						ST_Multi(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS raeumlichergeltungsbereich,
						CASE
							WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
								ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[]
							ELSE NULL
						END AS verfahrensmerkmale,
						CASE
							WHEN count_externeref > 0
							THEN externeref.externereferenz
							ELSE NULL
						END AS externereferenz,
						ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[] AS auslegungsenddatum,
						ARRAY[(g.ags,g.rs,g.gemeindename,g.ortsteilname)]::xplan_gml.xp_gemeinde[] AS gemeinde,
						(gmlas.status_codespace, gmlas.status, NULL)::xplan_gml." . $planartAbk . "_status AS status,
						gmlas.sachgebiet AS sachgebiet,
						(pg.name, pg.kennziffer)::xplan_gml.xp_plangeber AS plangeber,
						gmlas.rechtsstand::xplan_gml." . $planartAbk . "_rechtsstand AS rechtsstand,
						to_char(gmlas.wirksamkeitsdatum, 'DD.MM.YYYY')::date AS wirksamkeitsdatum,
						ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[] AS auslegungsstartdatum,
						ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[] AS traegerbeteiligungsstartdatum,
						to_char(gmlas.entwurfsbeschlussdatum, 'DD.MM.YYYY')::date AS entwurfsbeschlussdatum,
						to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY')::date AS aenderungenbisdatum,
						ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[] AS traegerbeteiligungsenddatum,
						gmlas.verfahren::xplan_gml." . $planartAbk . "_verfahren AS verfahren,
						(gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml." . $planartAbk . "_sonstplanart AS sonstplanart,
						gmlas.planart::xplan_gml." . strtolower($planart) . "art AS planart,
						to_char(gmlas.planbeschlussdatum, 'DD.MM.YYYY')::date AS planbeschlussdatum,
						to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY')::date AS aufstellungsbeschlussdatum
					FROM
						" . $table_schema . "." . strtolower($planart) . " gmlas JOIN
						xplankonverter.konvertierungen k ON gmlas.id = k.beschreibung LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_gemeinde gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
						" . $table_schema . ".xp_gemeinde g ON gemeindelink.xp_gemeinde_pkid = g.ogr_pkid LEFT JOIN
						(
							SElECT
								COUNT(*) AS count_externeref,
								externereferenzlink_sub.parent_id,
								array_agg((e_sub.georefurl,
										(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.art::xplan_gml.xp_externereferenzart,
										e_sub.informationssystemurl,
										e_sub.referenzname,
										e_sub.referenzurl,
										(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.beschreibung,
										to_char(e_sub.datum, 'DD.MM.YYYY'),
										e_sub.typ::xplan_gml.xp_externereferenztyp
									)::xplan_gml.xp_spezexternereferenz) AS externereferenz
							FROM
								" . $table_schema . "." . strtolower($planart) . "_externereferenz externereferenzlink_sub LEFT JOIN
								" . $table_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
							GROUP BY
								externereferenzlink_sub.parent_id
						) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
						" . $table_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
						" . $table_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
						" . $table_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_plangeber pg ON gmlas.plangeber_xp_plangeber_pkid = pg.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_auslegungsstartdatum alsd ON gmlas.id = alsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_auslegungsenddatum aled ON gmlas.id = aled.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_traegerbeteiligungsstartdatum tbsd ON gmlas.id = tbsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($planart) . "_traegerbeteiligungsenddatum tbed ON gmlas.id = tbed.parent_id;
				";
			} break;
		}

		$sql .= "
			INSERT INTO xplan_gml." . $planartAbk . "_bereich (
				gml_id, nummer, name, bedeutung, detailliertebedeutung, erstellungsmassstab, geltungsbereich, user_id, konvertierung_id, rasterbasis,
				versionbaunvodatum, versionbaugbtext, versionsonstrechtsgrundlagetext, versionbaunvotext, versionsonstrechtsgrundlagedatum, versionbaugbdatum, gehoertzuplan
			)
			SELECT
				trim(replace(lower(b.id), 'gml_', ''))::text::uuid AS id,
				b.nummer AS nummer,
				b.xplan_name AS name,
				b.bedeutung::xplan_gml.xp_bedeutungenbereich AS bedeutung,
				b.detailliertebedeutung AS detailliertebedeutung,
				b.erstellungsmassstab AS erstellungsmassstab,
				ST_Multi(ST_ForceRHR(st_transform(b.geltungsbereich, 25832))) AS geltungsbereich,
				" . $this->gui->user->id . " AS user_id,
				k.id AS konvertierung_id,
				trim(replace(lower(b.rasterbasis_href), '#gml_', ''))::text AS rasterbasis,
				b.versionbaunvodatum AS versionbaunvodatum,
				b.versionbaugbtext AS versionbaugbtext,
				b.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext,
				b.versionbaunvotext AS versionbaunvotext,
				b.versionsonstrechtsgrundlagedatum AS versionsonstrechtsgrundlagedatum,
				b.versionbaugbdatum AS versionbaugbdatum,
				trim(replace(lower(b.gehoertzuplan_pkid), 'gml_', ''))::text::uuid	AS gehoertzuplan
			FROM
				" . $table_schema . "." . $planartAbk . "_bereich AS b JOIN
				xplankonverter.konvertierungen k ON b.gehoertzuplan_pkid = k.beschreibung;

			SELECT
				plan.gml_id
			FROM
				" . $table_schema . "." . strtolower($planart) . " gmlas JOIN
				xplan_gml." . strtolower($planart) . " AS plan ON trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid = plan.gml_id;
		";
		#echo '<br>Anweisung zum Anlegen der Pläne und der Bereiche: ' . $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		if (!$ret['success']) {
			return array(
				'success' => false,
				'msg' => $ret['msg']
			);
		}
		return array(
			'success' => true,
			'msg' => pg_num_rows($ret[1]) . ' Pläne angelegt.'
		);
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
				xplan_gml." . $this->plan->planartAbk . "_bereich b ON r.bereich_gml_id::text = b.gml_id::text LEFT JOIN
				xplan_gml." . $this->plan->planartAbk . "_plan bp ON b.gehoertzuplan::text = bp.gml_id::text LEFT JOIN
				xplan_gml." . $this->plan->planartAbk . "_plan rp ON r.konvertierung_id = rp.konvertierung_id
			WHERE
				bp.konvertierung_id = " . $this->get($this->identifier) . " OR
				rp.konvertierung_id = " . $this->get($this->identifier) . "
		";
		#echo 'SQL zur Abfrage der Klassen-Namen, die zur Konvertierung gehören.' . $sql;

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
					k.id = {$this->get($this->identifier)}
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
			$this->set(strtolower($layer_type) . '_layer_group_id', $layerGroup->get($this->identifier));
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
			$layer_group->set($this->identifier, $layer_group_id);
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
		$this->debug->show('Konvertierung: reset_mapping mit konvertierung_id: ' . $this->get($this->identifier), Konvertierung::$write_debug);
		# Lösche vorhandene Validierungsergebnisse der Konvertierung
		Validierungsergebnis::delete_by_id($this->gui, 'konvertierung_id', $this->get($this->identifier));

		# Lösche vorhandene Datenobjekte der Konvertierung
		foreach($this->get_class_names() AS $class_name) {
			# Lösche Relationen
			$sql = "
				DELETE FROM
					xplan_gml." . strtolower($class_name) . "
				WHERE
					konvertierung_id = $1
			;";
			$this->debug->show("Delete Objekte von {$class_name} with konvertierung_id " . $this->get($this->identifier) . ' und sql: ' . $sql . ' in reset Mapping.', Konvertierung::$write_debug);
			#echo '<br>sql: ' . $sql;
			$query = pg_query_params($this->database->dbConn, $sql, array($this->get($this->identifier)));
		}

		# Lösche vorhandene xplan_shapes Export-Dateien
		$file = XPLANKONVERTER_FILE_PATH . $this->get($this->identifier) . '/xplan_shapes.zip';
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
		$validierung = new Validierung($this->gui);
		$planvalidierungen = $validierung->find_where("functionsname LIKE 'plan_attribute_has_value'");
		foreach($planvalidierungen AS $planvalidierung) {
			$planvalidierung->konvertierung_id = $this->get($this->identifier);
			$planvalidierung->plan_attribute_has_value();
		}

		// validate Bereiche
		$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'detaillierte_requires_bedeutung');
		$validierung->konvertierung_id = $this->get($this->identifier);
		$bereiche = $this->plan->get_bereiche();
		foreach ($bereiche AS $bereich) {
			$validierung->detaillierte_requires_bedeutung($bereich);
		}

		// Set die Planvalidierung durchgeführt wird, führt diese Meldung in die Irre und kann weggelassen werden.
		// ToDo: Ggf. wird der Hinweis als Validierungsergebnis gespeichert, dann wird er auch angezeigt.
		#if (count($bereiche) == 0) {
		#	$this->gui->add_message('warning', 'Die Validierung liefert kein Ergebnis, weil zum Plan keine Bereiche hinzugefügt wurden!');
		#}

		if (!empty($regeln)) {
			$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'regel_existiert');
			$validierung->konvertierung_id = $this->get($this->identifier);
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
							if($regel->is_source_shape_or_gmlas($regel,	$this->get($this->identifier)) != 'gmlas') {
								$regel->rewrite_gml_ids($result);
							}
						}*/
					}
				}
				$alle_sql_ausfuehrbar = true;
				$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'alle_sql_ausfuehrbar');
				$validierung->konvertierung_id = $this->get($this->identifier);
				foreach ($regeln AS $regel) {
					if (!$validierung->sql_ausfuehrbar($regel)) {
						$alle_sql_ausfuehrbar = false;
					}
				}
				$validierung->alle_sql_ausfuehrbar($alle_sql_ausfuehrbar);

				if ($alle_sql_ausfuehrbar) {
					# Prüft die Konformitäten der Klassen der Konvertierung für die aktuelle Version
					#echo $this->get_version_from_ns_uri(XPLAN_NS_URI);
					foreach ($this->get_konformitaetsbedingungen($this->get_version_from_ns_uri(XPLAN_NS_URI)) AS $bedingung) {
						foreach ($bedingung['konformitaet']->validierungen AS $validierung) {
							$validierung->validiere_konformitaet($this->get($this->identifier), $bedingung);
						}
					}

					if (in_array($this->get('planart'), array('BP-Plan', 'FP-Plan', 'SO-Plan'))) {
						# Flächenschlussprüfung
						$this->clearTopology();
						# Create topology of plan objects
						$this->createTopology();
						$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'flaechenschluss_ueberlappungen');
						$validierung->konvertierung_id = $this->get($this->identifier);
						$validierung->flaechenschluss_ueberlappungen($this->plan);
						$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'flaechenschluss_luecken');
						$validierung->konvertierung_id = $this->get($this->identifier);
						$validierung->flaechenschluss_luecken($this->plan);
					}
				}
			}
		}
	}

	/**
		This function validate a XPlanGML-File against the XPlanValidator at https://www.xplanungsplattform.de/xplan-validator/
		and write the report in xplankonverter database tables
	*/
	function xplanvalidator($file_type) {
		$msg = array();
		if (!$this->files_exists($file_type)) {
			return array(
				'success' => false,
				'msg' => 'In dem Konvertierungsvorgang gibt es noch keine GML-Datei die validiert werden kann. Bitte erst eine erzeugen.'
			);
		}
		#$gml_file = XPLANKONVERTER_FILE_PATH . $this->get($this->identifier) . '/xplan_gml/xplan_' . $this->get($this->identifier) . '.gml';
		$gml_file = $this->get_file_name($file_type);
		$url =	'https://www.xplanungsplattform.de/xplan-api-validator/xvalidator/api/v1/validate' . '?' .
						'name=xplanvalidator_konvertierung_' . $this->get($this->identifier) . '&' .
						'skipSemantisch=false' . '&' .
						'skipGeometrisch=false' . '&' .
						'skipFlaechenschluss=false' . '&' .
						'skipGeltungsbereich=false';

		$cmd = "curl -X 'POST' '" . $url	. "'-H 'accept: application/json' -H 'X-Filename: " . basename($gml_file) . "' -H 'Content-Type: application/gml+xml' --data-binary @" . $gml_file;
		#echo '<br>Frage Validierung mit folgendem Befehl ab: ' . $cmd;
		exec($cmd, $output, $result_code);
		$result = $output[0];
		#echo '<br>output: ' . $result;
		if (strpos($result, 'HTTP Status 406 – Not Acceptable') !== false) {
			return array(
				'success' => false,
				'msg' => 'Fehler bei der Abfrage beim XPlanValidator!<br>HTTP Status 406 – Not Acceptable<br>Überprüfen Sie Ihre XPlanGML-Datei auf Wohlgeformtheit und Validität.'
			);
		}
		#echo '<br>result_code: : ' . $result_code;
		$msg[] = 'Validierungsergebnis erfolgreich abgefragt.';
		$report = json_decode($result, false);

		$sql = "
			INSERT INTO xplankonverter.xplanvalidator_reports(
				konvertierung_id,
				version,
				filename,
				name,
				box_minx,
				box_miny,
				box_maxx,
				box_maxy,
				box_crs,
				datetime,
				valid,
				externalreferences,
				wmsurl,
				rulesmetadata_version,
				rulesmetadata_source,
				semantisch_valid,
				geometrisch_valid,
				geometrisch_errors,
				geometrisch_warnings,
				syntaktisch_valid,
				syntaktisch_messages
			) VALUES (
				" . $this->get($this->identifier) . ",
				'" . $report->version . "',
				'" . $report->filename . "',
				'" . $report->name . "',
				" . quote_or_null($report->bbox->minX) . ",
				" . quote_or_null($report->bbox->minY) . ",
				" . quote_or_null($report->bbox->maxX) . ",
				" . quote_or_null($report->bbox->maxY) . ",
				'" . $report->bbox->crs . "',
				'" . $report->date . "',
				" . ($report->valid ? 'true' : 'false') . ",
				" . ((is_array($report->externalReferences) AND count($report->externalReferences) > 0) ? "ARRAY['" . pg_escape_string(implode("', '", $report->externalReferences)) . "']" : "NULL") . ",
				'" . $report->wmsUrl . "',
				'" . $report->rulesMetadata->version . "',
				'" . $report->rulesMetadata->source . "',
				" . ($report->validationResult->semantisch->valid ? 'true' : 'false') . ",
				" . ($report->validationResult->geometrisch->valid ? 'true' : 'false') . ",
				" . ((is_array($report->validationResult->geometrisch->errors) AND count($report->validationResult->geometrisch->errors) > 0) ? "ARRAY['" . pg_escape_string(implode("', '", $report->validationResult->geometrisch->errors)) . "']" : "NULL") . ",
				" . ((is_array($report->validationResult->geometrisch->warnings) AND count($report->validationResult->geometrisch->warnings) > 0) ? "ARRAY['" . pg_escape_string(implode("', '", $report->validationResult->geometrisch->warnings)) . "']" : "NULL") . ",
				" . ($report->validationResult->syntaktisch->valid ? 'true' : 'false') . ",
				" . ((is_array($report->validationResult->syntaktisch->messages) AND count($report->validationResult->syntaktisch->messages) > 0) ? "ARRAY['" . pg_escape_string(implode("', '", $report->validationResult->syntaktisch->messages)) . "']" : "NULL") . "
			) RETURNING id
		";
		#echo '<br>SQL to create a validation report: ' . $sql;
		$ret = $this->database->execSQL($sql);
		if (!$ret['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Eintragen der Ergebnisse des XPlan-Validators!<br>' . $ret['msg']
			);
		}
		if (is_array($report->validationResult->geometrisch->errors) AND count($report->validationResult->geometrisch->errors) > 0) {
			$msg[] = '<p>Der XPlanValidator liefert folgende geometrischen Fehlermeldungen:<br>.' . implode("<br>", $report->validationResult->geometrisch->errors);
		}
		if (is_array($report->validationResult->geometrisch->warnings) AND count($report->validationResult->geometrisch->warnings) > 0) {
			$msg[] = '<p>Der XPlanValidator liefert folgende geometrischen Warnungen:<br>.' . implode("<br>", $report->validationResult->geometrisch->warnings);
		}
		if (is_array($report->validationResult->syntaktisch->messages) AND count($report->validationResult->syntaktisch->messages) > 0) {
			$msg[] = '<p>Der XPlanValidator liefert folgende syntaktischen Fehlermeldungen:<br>.' . implode("<br>", $report->validationResult->syntaktisch->messages);
		}
		$rs = pg_fetch_assoc($ret[1]);
		$values = array();
		foreach ($report->validationResult->semantisch->rules AS $rule) {
			$values[] = "(" . $rs['id'] . ",
				'" . $rule->name . "',
				" . ($rule->isValid ? 'true' : 'false') . ",
				'" . $rule->message . "',
				" . ((is_array($rule->invalidFeatures) AND count($rule->invalidFeatures) > 0) ? "ARRAY['" . implode("', '", $rule->invalidFeatures) . "']" : "NULL") . "
			)";
		}
		if (count($values) > 0) {
			$sql = "
				INSERT INTO xplankonverter.xplanvalidator_semantische_results(
					xplanvalidator_report_id,
					name,
					isvalid,
					message,
					invalidefeatures
				)
				VALUES
					" . implode(', ', $values) . "
			";
			#echo '<br>SQL to create the semantic results: ' . $sql; exit;
			$ret = $this->database->execSQL($sql);
			if (!$ret['success']) {
				return array(
					'success' => false,
					'msg' => 'Fehler beim Eintragen der Ergebnisse des XPlan-Validators!<br>' . $ret['msg']
				);
			}
			$msg[] = 'Ergebnisse der semantischen Prüfung erfolgreich in Tabelle validation_results_semantisch eingetragen.';
		}
		return array(
			'success' => true,
			'valid' => ($report->valid ? true : false),
			'msg' => implode('<br>', $msg)
		);
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
				konvertierung_id = " . $this->get($this->identifier) . "
		";
		$this->debug->show('sql to find konformitaetsbedingungen for konvertierung_id: ' . $this->get($this->identifier) . ' für XPlanung Version: ' . $version, false);
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
				konvertierung_id = " . $this->get($this->identifier) . "
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
				konvertierung_id = " . $this->get($this->identifier) . "
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
				konvertierung_id = " . $this->get($this->identifier) . "
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
					konvertierung_id = " . $this->get($this->identifier) . "
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
				konvertierung_id = " . $this->get($this->identifier) . "
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
				konvertierung_id = {$this->get($this->identifier)}
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
			konvertierung_id = {$this->get($this->identifier)} AND
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
		# Destroy the xplan_gmlas: + $konvertierung_id schema if it exists
		$sql = "
			DROP SCHEMA IF EXISTS xplan_shapes_" .	$this->get($this->identifier) . " CASCADE;
			DROP SCHEMA IF EXISTS xplan_gmlas_" .	$this->get($this->identifier) . " CASCADE;
		";
		pg_query($this->database->dbConn, $sql);
	}

}
?>
