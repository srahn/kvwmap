<?php
class Gml_extractor {

	function __construct($database, $gml_location, $gmlas_schema = 'xplan_gmlas') {
		global $debug;
		$this->debug = $debug;
		$this->pgdatabase = $database;
		$this->gml_location = $gml_location;
		$this->gmlas_schema = $gmlas_schema;
		$this->xsd_location = '/var/www/html/modell/xsd/5.1/XPlanung-Operationen.xsd';
		#$this->docker_gdal_cmd = 'docker exec gdal';
		#TODO consider other options of parsing epsgs from the file (e.g. with ogrinfo) or have an input field on upload 
		$this->input_epsg = '25832';
		$this->epsg = '25832';
		$this->xsd_version = '';
	}

	/**
	 * Import der in $this->gml_location angegebenen GML-Datei in das in $this->gmlas_schema angegebene Schema
	 */
	function import_gml_to_db() {
		global $GUI;
		$result = $this->get_source_srid();
		if (! $result['success']) {
			return array(
				'success' => false,
				'msg' => 'Import der GML in die Datenbank mit ogr2ogr_gmlas fehlgeschlagen Fehler: ' . $result['msg']
			);
		}
		$this->input_epsg = $result['epsg'];
		$this->xsd_location = '/var/www/html/modell/xsd/' . $this->get_xsd_version() . '/XPlanung-Operationen.xsd';

		# TODO should the target EPSG be stelle or rolle specific?
		$this->epsg = $GUI->Stelle->epsg_code;
		$this->build_basic_tables();
		$result = $this->ogr2ogr_gmlas();
		if (!$result['success']) {
			if ($GUI->formvars['format'] != 'json_result') {
				echo 'Laden der Daten mit GML-AS fehlgeschlagen. Bitte kontaktieren Sie Ihren Administrator!';
			}
			return $result;
		}
			#ToDo pk: Hier prüfen ob mindestens ein Plan und ein dazugehöriger Bereich angelegt wurden.
			
		# $tables = $this->get_all_tables_in_schema($this->gmlas_schema);

		# Revert the geom of GML to database specific winding order of vertices (CW/RHR IN DB and Shape, CCW/LHR in GML)
		# NOTE:
		# As lines have to be reverted as well, it cannot be confirmed automatically whether the order is correct.
		# It must be assumed that source data conforms to the GML standard (left hand order) on all geometries
		# For Polygons alone, this could be deduced through an inside/outside check (e.g. with ST_ForceRHR())
		$fachobjekte_tables_and_geometries = $this->get_fachobjekte_geometry_tables_attributes($this->gmlas_schema);
		if (!empty($fachobjekte_tables_and_geometries)) {
			foreach ($fachobjekte_tables_and_geometries as $fachobjekt_table_and_geometry) {
				$this->revert_vertex_order_for_table_with_geom_column_in_schema($fachobjekt_table_and_geometry['table_name'],$fachobjekt_table_and_geometry['column_name'],$this->gmlas_schema);
			}
		}
		
		if(XPLANKONVERTER_AUTOCOMPLETE_FP_BEBAUUNGSFLAECHE) {
			$this->autocomplete_fp_bebauungsflaeche_attributes($this->gmlas_schema);
		}
		
		return array(
			'success' => true,
			'msg' => 'ogr2ogr_gmlas output: ' . $result['msg'],
			'url' => $result['url']
		);
	}

	function get_reftextinhalt_fk_column_name() {
		$version = $this->get_xsd_version();
		if ($version < 5.4) {
			$name = 'reftextinhalt';
		}
		else {
			$name = 'href_bp_textabschnitt';
		}
		// echo '<br>reftextinhalt_fk_column_name für Version ' . $version . ': ' . $name . '_pkid';
		return $name . '_pkid';
	}

	function extract_to_form($classname) {
		$layername = '';
		$tablename = strtolower($classname); #for DB
		
		switch ($classname) {
			case 'BP_Plan' : {
				$layername = 'B-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_BP_PLAENE_LAYER_ID;
			} break;
			case 'FP_Plan' : {
				$layername = 'F-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_FP_PLAENE_LAYER_ID;
			} break;
			case 'RP_Plan' : {
				$layername = 'R-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_RP_PLAENE_LAYER_ID;
			} break;
			case 'SO_Plan' : {
				$layername = 'SO-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_SO_PLAENE_LAYER_ID;
			} break;
			default : {
				#Default = BP_Plan
				$layername = 'B-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_BP_PLAENE_LAYER_ID;
			} break;
		}

		$gml_id = $this->parse_gml_id($classname);
		if(empty($gml_id)){
			$GUI->add_message('warning', 'Es konnte keine Klasse ' . $classname . ' mit Pflichtattribut gml:id gefunden werden');
			return;
		}

		# Mapping of input to output schema
		# Dynamic function
		$formdata = array();
		$fill_form_table = 'fill_form_' . $tablename;
		$formdata = $this->$fill_form_table($gml_id);
		// $rect = ms_newRectObj(); Wird später noch nicht genutzt, deshalb hier auskommentiert.

		# iterate over all attributes as formvars
		foreach ($formdata as $r_key => $r_value) {
			if ($r_key == 'externereferenz') {
				# TODO Das ist die Stelle wo man prüfen kann ob die hochgeladenen Dateien mit den referenzurl übereinstimmen
				$referenzen = json_decode($r_value);
				if (is_array($referenzen) AND count($referenzen) > 0) {
					/*
					$document_url = $GUI->user->rolle->getLayer($GUI->formvars['chosen_layer_id'])[0]['document_url'];
					foreach ($referenzen AS $referenz) {
						$path_parts = pathinfo(basename($referenz->referenzurl));
						$referenz->referenzurl = $document_url . $path_parts['filename'] . '-' . $GUI->formvars['random_number'] . '.' . $path_parts['extension']; 
					}
					*/
					$r_value = str_replace('\/', '/', json_encode($referenzen));
					#echo '<br>fill ' . $r_key . ' with: ' . $r_value;
				}
			}
			$GUI->formvars['attributenames'][] = $r_key;
			$GUI->formvars['values'][] = $r_value;
			# for filling the geometry data
			if ($r_key == 'newpathwkt') {
				$GUI->formvars['newpathwkt'] = $r_value;
				$GUI->formvars['pathwkt'] = $GUI->formvars['newpathwkt'];
			}
			# for drawing the data onto the polygoneditor
			if ($r_key == 'newpath') {
				$GUI->formvars['newpath'] = transformCoordsSVG($r_value);
			}
			if ($r_key == 'oid') {
				$oid = $r_value;
			}
		}
		if (empty($oid)) {
			$oid = $this->trim_gml_prefix_if_exists($gml_id); # workaround for now
		}

		# get extent of geometry for zooming 
		$extent = $this->get_bbox_from_wkt($GUI->formvars['pathwkt']);
		$GUI->formvars = $GUI->formvars + $extent;
		$GUI->formvars['checkbox_names_' . $GUI->formvars['chosen_layer_id']] = 'check;' . $layername . ';' . $tablename . ';' . $oid . '|';
		$GUI->formvars['check;' . $layername .';' . $tablename . ';' . $oid] = 'on';
		$GUI->formvars['attributenames'][] = 'layer_schemaname';
		$GUI->formvars['values'][] = $this->gmlas_schema;
		#print_r($GUI->formvars);
	}

	/*
	* Extracts Plan and sends vars to form
	*/
	function extract_gml_classes($classname) {
		#$this->import_gml_to_db();
		#$this->extract_to_form($classname);
		/**
		 *ToDo das folgende löschen wenn das obige freigeschaltet ist und an den Stellen wo extract_gml_classes aufgerufen wird die anderen beiden aufrufen und diese Funktion dann löschen nach Test.
		 */
		global $GUI;
		
		$result = $this->get_source_srid();
		if (! $result['success']) {
			$GUI->add_message('warning', $result['msg']);
			$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
			$GUI->output();
			exit;
		}
		$this->input_epsg = $result['epsg'];
		$this->xsd_location = '/var/www/html/modell/xsd/' . $this->get_xsd_version() . '/XPlanung-Operationen.xsd';
		# TODO should the target EPSG be stelle or rolle specific?
		$this->epsg = $GUI->Stelle->epsg_code;
		$this->build_basic_tables();
		$gmlas_output = $this->ogr2ogr_gmlas();
		if ($gmlas_output == "Nothing returned from ogr2ogr curl request") {
			echo 'Laden der Daten mit GML-AS fehlgeschlagen. Bitte kontaktieren Sie Ihren Administrator!';
			return;
		}
		# $tables = $this->get_all_tables_in_schema($this->gmlas_schema);

		# Revert the geom of GML to database specific winding order of vertices (CW/RHR IN DB and Shape, CCW/LHR in GML)
		# NOTE:
		# As lines have to be reverted as well, it cannot be confirmed automatically whether the order is correct.
		# It must be assumed that source data conforms to the GML standard (left hand order) on all geometries
		# For Polygons alone, this could be deduced through an inside/outside check (e.g. with ST_ForceRHR())
		$fachobjekte_tables_and_geometries = $this->get_fachobjekte_geometry_tables_attributes($this->gmlas_schema);
		if (!empty($fachobjekte_tables_and_geometries)) {
			foreach ($fachobjekte_tables_and_geometries as $fachobjekt_table_and_geometry) {
				$this->revert_vertex_order_for_table_with_geom_column_in_schema($fachobjekt_table_and_geometry['table_name'],$fachobjekt_table_and_geometry['column_name'],$this->gmlas_schema);
			}
		}

		$layername = '';
		$tablename = strtolower($classname); #for DB
		
		switch ($classname) {
			case 'BP_Plan' : {
				$layername = 'B-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_BP_PLAENE_LAYER_ID;
			} break;
			case 'FP_Plan' : {
				$layername = 'F-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_FP_PLAENE_LAYER_ID;
			} break;
			case 'RP_Plan' : {
				$layername = 'R-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_RP_PLAENE_LAYER_ID;
			} break;
			case 'SO_Plan' : {
				$layername = 'SO-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_SO_PLAENE_LAYER_ID;
			} break;
			default : {
				#Default = BP_Plan
				$layername = 'B-Pläne';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_BP_PLAENE_LAYER_ID;
			} break;
		}

		$gml_id = $this->parse_gml_id($classname);
		if(empty($gml_id)){
			$GUI->add_message('warning', 'Es konnte keine Klasse ' . $classname . ' mit Pflichtattribut gml:id gefunden werden');
			return;
		}

		# Mapping of input to output schema
		# Dynamic function
		$formdata = array();
		$fill_form_table = 'fill_form_' . $tablename;
		$formdata = $this->$fill_form_table($gml_id);
		// $rect = ms_newRectObj(); Wird später noch nicht genutzt, deshalb hier auskommentiert.

		# iterate over all attributes as formvars
		foreach ($formdata as $r_key => $r_value) {
			if ($r_key == 'externereferenz') {
				# TODO Das ist die Stelle wo man prüfen kann ob die hochgeladenen Dateien mit den referenzurl übereinstimmen
				$referenzen = json_decode($r_value);
				if (is_array($referenzen) AND count($referenzen) > 0) {
					/*
					$document_url = $GUI->user->rolle->getLayer($GUI->formvars['chosen_layer_id'])[0]['document_url'];
					foreach ($referenzen AS $referenz) {
						$path_parts = pathinfo(basename($referenz->referenzurl));
						$referenz->referenzurl = $document_url . $path_parts['filename'] . '-' . $GUI->formvars['random_number'] . '.' . $path_parts['extension']; 
					}
					*/
					$r_value = str_replace('\/', '/', json_encode($referenzen));
					#echo '<br>fill ' . $r_key . ' with: ' . $r_value;
				}
			}
			$GUI->formvars['attributenames'][] = $r_key;
			$GUI->formvars['values'][] = $r_value;
			# for filling the geometry data
			if ($r_key == 'newpathwkt') {
				$GUI->formvars['newpathwkt'] = $r_value;
				$GUI->formvars['pathwkt'] = $GUI->formvars['newpathwkt'];
			}
			# for drawing the data onto the polygoneditor
			if ($r_key == 'newpath') {
				$GUI->formvars['newpath'] = transformCoordsSVG($r_value);
			}
			if ($r_key == 'oid') {
				$oid = $r_value;
			}
		}
		if (empty($oid)) {
			$oid = $this->trim_gml_prefix_if_exists($gml_id); # workaround for now
		}

		# get extent of geometry for zooming 
		$extent = $this->get_bbox_from_wkt($GUI->formvars['pathwkt']);
		$GUI->formvars = $GUI->formvars + $extent;
		$GUI->formvars['checkbox_names_' . $GUI->formvars['chosen_layer_id']] = 'check;' . $layername . ';' . $tablename . ';' . $oid . '|';
		$GUI->formvars['check;' . $layername .';' . $tablename . ';' . $oid] = 'on';
		$GUI->formvars['attributenames'][] = 'layer_schemaname';
		$GUI->formvars['values'][] = $this->gmlas_schema;
		 #print_r($GUI->formvars);
	}

	/**
	 * Parse the EPSG of the file $this->gml_location and return the epsg-Value
	 * According to Konformitaetsbedingung 2.1.3.1 there needs to be a standard gml:Envelope in each valid xplan-file.
	 * A fallback value will be provided as conformity currently cannot be validated at the moment of loading (schema could be validated with xsd-validator)
	 * 
	 * NOTE:
	 * Konformitaetsbedingung 2.13.1 currently also still allows a "kurzbezeichnung" akin to ALKIS, e.g "urn:adv:crs:DE_DHDN_3GK3", where DE_DHDN_3GK3 is Gauss-Krueger Streifen 3
	 * This method of CRS will likely become obsolete in XPlanung 6.0, and is also currently not supported with this parser (default value would be used)
	 */
	function get_source_srid() {
		$epsg = $this->input_epsg;
		$lines = file($this->gml_location);
		foreach ($lines as $lineNumber => $line) {
			if (strpos($line, 'Envelope') === false) {
				continue;
			}
			# needs to check for both single and double quotes as both are permitted by XML spec
			if (preg_match('/srsName="([^"]+)"/', $line, $matched_epsg_str)) {
				break; #found it
			}
			if (preg_match('/srsName=\'([^"]+)\'/', $line, $matched_epsg_str)) {
				break; #found it
			}
			// echo 'could not find XPlan srsName within double quotes. checking single quotes:<br>matched_epsg_str: ' . print_r($matched_epsg_str, true);
		}

		// echo $matched_epsg_str[1] . '<br>';

		if (isset($matched_epsg_str[1])) {
			// e.g. for EPSG:25832
			$epsg_elements_array = explode(':',$matched_epsg_str[1]);
			$matched_epsg = array_values(array_slice($epsg_elements_array, -1))[0];
			if (is_numeric($matched_epsg)) {
				# TODO should still be checked if it is a valid EPSG within the konverter or POSTGIS limit, e.g. through a check against the POSTGIS EPSG info
				$epsg = $matched_epsg;
			}
			return array(
				'success' => true,
				'epsg' => $epsg
			);
		}
		else {
			$msg  = 'Konnte das SRS des XPlan-Envelope nicht innerhalb von Doppelten oder einfachen Anführungszeichen finden'; 
			$msg .= 'Bitte stellen Sie sicher, das ein Envelope-Element nach XPlan-Konformitaetsbedingung 2.1.3.1 vorhanden ist, z.B. wie folgt:<br>';
			$msg .= '<pre>' . htmlentities('<gml:boundedBy><gml:Envelope srsName="EPSG:25833">...</gml:Envelope></gml:boundedBy>') . '</pre>...<br>';
			$msg .= 'Ein Fallback SRS mit EPSG ' . $this->fallback_epsg . ' wird benutzt.<br>';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}
	}
	/* 
	* parses current xplan-version from file
	* does not check whether current version is supported
	*/
	function get_xsd_version() {
		//will check if current version is supported or not
		$version = '5.1'; //default
		global $GUI;
		if ($this->gml_location == 'placeholder' OR $this->gml_location == '') {
			$sql = "
				SELECT
					xsd_version
				FROM
					" . $this->gmlas_schema . ".xplanauszug
				LIMIT 1
			";
			// echo '<br>Frage xsd_version ab mit SQL: ' . $sql;
			$ret = $this->pgdatabase->execSQL($sql, 4, 0);
			if ($ret['success'])  {
				$rs = pg_fetch_assoc($ret[1]);
				$version = $rs['xsd_version'];
			}
		}
		else {
			$lines = file($this->gml_location);
			$matched_ns_str;
			$xml_bracket_is_opened = false; // check within auszug with linebreaks
			foreach ($lines AS $lineNumber => $line) {
				if (strpos($line, 'XPlanAuszug') === false AND $xml_bracket_is_opened === false) {
					continue;
				}
				# to also match formatted multiline XPlanAuszug
				$xml_bracket_is_opened = true;
				if (strpos($line, '>') !== false) {
					//echo '<br>Enthält > setze xml bracket is open wieder auf false';
					$xml_bracket_is_opened = false;
				}
				$pattern = '/xmlns:xplan=["\'].*?\/(\d+)[\/\.](\d+)/';
				preg_match($pattern, $line, $matches);
				if (count($matches) > 0) {
					$version = $matches[1] . '.' . $matches[2];
					break;
				}
			}
		}

		// echo '<br>Version: ' . $version;
		$this->xsd_version = $version;
		return $version;
	}

	function save_xsd_version() {
		$sql = "
			UPDATE
				" . $this->gmlas_schema . ".xplanauszug
			SET
				xsd_version = '" . $this->xsd_version . "'
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		return $ret;
	}
	
	/*
	* Returns TRUE OR FALSE, depending on whether the schema exists
	*/
	function check_if_table_exists_in_schema($table, $schema) {
		$sql = "
			SELECT
				EXISTS(
					SELECT
						1
					FROM
						information_schema.tables
					WHERE 
						table_name = '" . $table . "'
					AND
						table_schema = '" . $schema . "'
					AND
						table_catalog = '" . POSTGRES_DBNAME . "'
				)
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_row($ret[1]);
		return ($result[0] === 't');
	}

	/**
	 * Funktion liefert true wenn in angegebener Tabelle mehr als ein Plan liegt, sonst false
	 */
	function get_num_plaene($table_schema, $table_name) {
		$sql = "
			SELECT
				count(*) AS num_plaene
			FROM
				" . $table_schema . '.' . $table_name . "
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		if (!$ret['success']) {
			return 0;
		}
		$rs = pg_fetch_assoc($ret[1]);
		return $rs['num_plaene'];
	}

	/*
	* Returns the gml_id of the first found instance of a class (usually *_plan or *_bereich) inside the gml_file
	*/
	function parse_gml_id($class) {
		# Parse the GML-ID of the file (to avoid duplicates, to identify the file)
		$lines = file($this->gml_location);
		foreach ($lines as $lineNumber => $line) {
			if(strpos($line, $class) === false) {
				continue;
			}
			# needs to check for both single and double quotes as both are permitted by XML spec
			if (preg_match('/id="([^"]+)"/', $line, $matched_gml_id)) {
				break; #found it
			}
			# echo 'could not find XPlan gml-id within double quotes. checking single quotes:<br>';
			if (preg_match('/id=\'([^"]+)\'/', $line, $matched_gml_id)) {
				break; #found it
			}
			$msg  = 'could not find ' . $class . ' gml-id within double quotes or single quotes<br>';
			$msg .= 'Please make sure that ' . $class . ' contains the compulsory gml:id attribute, e.g. in the following style: ';
			$msg .= '<pre>' . htmlentities('<xplan:BP_Plan gml:id="GML_3789d575-433f-11e8-86d4-3f03fdce6d8b"') . '</pre>...<br>';
			$GUI->add_message('error', $msg);
			$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
			$GUI->output();
			exit;
		}
		# print $matched_gml_id[1];
		return $matched_gml_id[1];
	}

	/*
	* ogr2ogr function to load a gml file into a gml-applicationschema db-schema
	*/
	function ogr2ogr_gmlas() {
		# For Logging add: . ' >> /var/www/logs/ogr_' . $gml_id . '.log 2>> /var/www/logs/ogr_' . $gml_id . '.err'
		# escape for passwords with shell 
		#$cmd = $this->docker_gdal_cmd . ' ' . OGR_BINPATH_GDAL . 'ogr2ogr -f "PostgreSQL" PG:"' . $this->pgdatabase->get_connection_string_p() . ' SCHEMAS=' . $this->gmlas_schema .'" GMLAS:' . $this->gml_location . ' -oo REMOVE_UNUSED_LAYERS=YES -oo XSD=' . $this->xsd_location;
		#echo $cmd;
		#exec($cmd, $output, $error_code);
		#echo '<pre>'; print_r($output); echo '</pre>';
		#echo 'Error-Code:' . $error_code;
		
		$gdal_container_connect = 'gdalcmdserver:8080/t/?tool=ogr2ogr&param=';
		$param_1                = urlencode('-f "PostgreSQL" PG:');
		$connection_string      = urlencode('"' . $this->pgdatabase->get_connection_string() . ' SCHEMAS=' . $this->gmlas_schema . '" ');
		$param_2                = urlencode('GMLAS:' . "'" . $this->gml_location . "'" . ' -nlt CONVERT_TO_LINEAR -oo REMOVE_UNUSED_LAYERS=YES -oo XSD=' . $this->xsd_location . ' -s_srs EPSG:' . $this->input_epsg . ' -t_srs EPSG:' . $this->epsg);

		$url = $gdal_container_connect . $param_1 . $connection_string . $param_2;	

		$ch = curl_init();
		#$url = curl_escape($ch, $url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,300);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		$success = strpos($output, 'ERROR') === false;
		$result = json_decode($output);
		if ($success) {
			$this->save_xsd_version();
		}
		return array(
			'success' => $success,
			'msg' => ($success ? $result->stdout : $result->err . $result->stderr),
			'url' => str_replace($this->pgdatabase->get_credentials($this->pgdatabase->connection_id)['password'], 'secret', $url)
		);
	}

	/*
	* Cuts the gml_prefix of a gml_id if exists (required for uuid casts)
	*/
	function trim_gml_prefix_if_exists($gml_id) {
		#Removes prefix 'GML_', 'gml_' or 'Gml_ if it exists'
		$mod_gml_id = $gml_id;
		if(substr($gml_id, 0, strlen('GML_')) == 'GML_') $mod_gml_id = substr($gml_id, strlen('GML_'));
		if(substr($gml_id, 0, strlen('gml_')) == 'gml_') $mod_gml_id = substr($gml_id, strlen('gml_'));
		if(substr($gml_id, 0, strlen('Gml_')) == 'Gml_') $mod_gml_id = substr($gml_id, strlen('Gml_'));
		return $mod_gml_id;
	}

	/**
	 * Returns an array of all tables in a specified schema according to the information_schema
	 */
	function get_all_tables_in_schema($schema) {
		$sql = "
			SELECT
				table_name
			FROM
				information_schema.tables
			WHERE
				table_schema = '" . $schema . "'
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$tables_in_schema = pg_fetch_all_columns($ret[1]);
		//$result = (!empty($result)) ? array_column($result, 'table_name') : array();
		return $tables_in_schema;
	}

	/*
	* Get all existing tables outside of Plan and Bereich with geometry columns
	*/
	function get_fachobjekte_geometry_tables_attributes($schema) {
		$sql = "
			SELECT
				table_name, column_name
			FROM
				information_schema.columns
			WHERE
				table_schema = '" .$schema . "' 
			AND
				udt_name = 'geometry'
			AND
				column_name NOT IN ('raeumlichergeltungsbereich','geltungsbereich')
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_all($ret[1]);
		#$result = (!empty($result)) ? array_column($result, 'table_name') : array();
		return $result;
	}

	/**
	* Reverse vertex order of specific geometry (GML CCW Lefthand, Shape and DB CW Righthand)
	* but not when geometrytype is of type ST_CurvePolygon, because it would make invalid geometry
	*/
	function revert_vertex_order_for_table_with_geom_column_in_schema($table, $geom_column, $schema) {
		$sql = "
		UPDATE " . 
			$schema . "." . $table . " 
		SET " .
			$geom_column . " = ST_Reverse(" . $geom_column . ")
		WHERE
			ST_GeometryType(" . $geom_column . ") NOT LIKE 'ST_CurvePolygon'
		";

		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
	}

	function get_bbox_from_wkt($wkt){
		$sql ="
			SELECT 
				st_xmin(extent) as minx,
				st_xmax(extent) as maxx,
				st_ymin(extent) as miny,
				st_ymax(extent) as maxy
			FROM
				box2D(st_geometryfromtext('".$wkt."')) as extent
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}

	/*
	* Returns an associative array to fill the bp_plan form
	* Force RHR to get database-winding order, order will be reverted again on export.
	*/
	function fill_form_bp_plan($gml_id) {
		$sql = "
			SELECT
				" . 
				"'" . $this->trim_gml_prefix_if_exists($gml_id) . "'::text::uuid AS plan_gml_id,
				gmlas.xplan_name AS name,
				gmlas.nummer AS nummer,
				gmlas.internalid AS internalid,
				gmlas.beschreibung AS beschreibung,
				gmlas.kommentar AS kommentar,
				to_char(gmlas.technherstelldatum, 'DD.MM.YYYY') AS technherstelldatum,
				to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY') AS genehmigungsdatum,
				to_char(gmlas.untergangsdatum, 'DD.MM.YYYY') AS untergangsdatum,
				CASE WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
					array_to_json(ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[])
					ELSE NULL
				END AS aendert,
				CASE WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
					array_to_json(ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[])
					ELSE NULL
				END AS wurdegeaendertvon,
				gmlas.erstellungsmassstab AS erstellungsmassstab,
				gmlas.bezugshoehe AS bezugshoehe,
				st_assvg(ST_ForceRHR(gmlas.raeumlichergeltungsbereich), 0, 8) AS newpath,
				st_astext(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS newpathwkt,
				CASE WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
					array_to_json(ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[])
					ELSE NULL
				END AS verfahrensmerkmale,
				CASE
					WHEN count_externeref > 0
					THEN array_to_json(externeref.externereferenz)
					ELSE NULL
				END AS externereferenz,
				to_char(gmlas.veraenderungssperredatum, 'DD.MM.YYYY') AS veraenderungssperredatum,
				array_to_json(ARRAY[(g.ags,g.rs,g.gemeindename,g.ortsteilname)]::xplan_gml.xp_gemeinde[]) AS gemeinde,
				gmlas.verfahren::xplan_gml.bp_verfahren AS verfahren,
				to_char(gmlas.inkrafttretensdatum, 'DD.MM.YYYY') AS inkrafttretensdatum,
				gmlas.durchfuehrungsvertrag AS durchfuehrungsvertrag,
				gmlas.staedtebaulichervertrag AS staedtebaulichervertrag,
				gmlas.rechtsverordnungsdatum AS rechtsverordnungsdatum,
				gmlas.rechtsstand::xplan_gml.bp_rechtsstand AS rechtsstand,
				gmlas.hoehenbezug AS hoehenbezug,
				to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY') AS aufstellungsbeschlussdatum,
				to_char(gmlas.ausfertigungsdatum, 'DD.MM.YYYY') AS ausfertigungsdatum,
				to_char(gmlas.satzungsbeschlussdatum, 'DD.MM.YYYY') AS satzungsbeschlussdatum,
				gmlas.veraenderungssperre AS veraenderungssperre,
				array_to_json(ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[]) AS auslegungsenddatum,
				(gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml.bp_sonstplanart AS sonstplanart,
				gmlas.gruenordnungsplan AS gruenordnungsplan,
				to_json((pg.name, pg.kennziffer)::xplan_gml.xp_plangeber) AS plangeber,
				array_to_json(ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[]) AS auslegungsstartdatum,
				array_to_json(ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsstartdatum,
				to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY') AS aenderungenbisdatum,
				to_json((gmlas.status_codespace, gmlas.status, NULL)::xplan_gml.bp_status) AS status,
				array_to_json(ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsenddatum,
				array_to_json(gmlas.planart) AS planart,
				gmlas.erschliessungsvertrag AS erschliessungsvertrag,
				to_char(gmlas.versionbaunvodatum, 'DD.MM.YYYY') AS versionbaunvodatum,
				gmlas.versionbaunvotext AS versionbaunvotext,
				to_char(gmlas.versionbaugbdatum, 'DD.MM.YYYY') AS versionbaugbdatum,
				gmlas.versionbaugbtext AS versionbaugbtext,
				to_char(gmlas.versionsonstrechtsgrundlagedatum, 'DD.MM.YYYY') AS versionsonstrechtsgrundlagedatum,
				gmlas.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext
			FROM
				" . $this->gmlas_schema . ".bp_plan gmlas LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_gemeinde gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".xp_gemeinde g ON gemeindelink.xp_gemeinde_pkid = g.ogr_pkid LEFT JOIN
				(
					SELECT
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
								e_sub.typ::xplan_gml.xp_externereferenztyp,
								false
							)::xplankonverter.xp_spezexternereferenzauslegung) AS externereferenz
					FROM
						" . $this->gmlas_schema . ".bp_plan_externereferenz externereferenzlink_sub LEFT JOIN
						" . $this->gmlas_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
					GROUP BY
						externereferenzlink_sub.parent_id
				) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_plangeber pg ON gmlas.plangeber_xp_plangeber_pkid = pg.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_auslegungsstartdatum alsd ON gmlas.id = alsd.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_auslegungsenddatum aled ON gmlas.id = aled.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_traegerbeteiligungsstartdatum tbsd ON gmlas.id = tbsd.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_traegerbeteiligungsenddatum tbed ON gmlas.id = tbed.parent_id
			WHERE
				gmlas.id = '" . $gml_id . "'
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}

	/*
	* Returns an associative array to fill the fp_plan form
	*/
	function fill_form_fp_plan($gml_id) {
		$sql = "
			SELECT
				" . 
				"'" . $this->trim_gml_prefix_if_exists($gml_id) . "'::text::uuid AS plan_gml_id,
				gmlas.xplan_name AS name,
				gmlas.nummer AS nummer,
				gmlas.internalid AS internalid,
				gmlas.beschreibung AS beschreibung,
				gmlas.kommentar AS kommentar,
				to_char(gmlas.technherstelldatum, 'DD.MM.YYYY') AS technherstelldatum,
				to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY') AS genehmigungsdatum,
				to_char(gmlas.untergangsdatum, 'DD.MM.YYYY') AS untergangsdatum,
				CASE WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
					array_to_json(ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[])
					ELSE NULL
				END AS aendert,
				CASE WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
					array_to_json(ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[])
					ELSE NULL
				END AS wurdegeaendertvon,
				gmlas.erstellungsmassstab AS erstellungsmassstab,
				gmlas.bezugshoehe AS bezugshoehe,
				st_assvg(gmlas.raeumlichergeltungsbereich, 0, 8) AS newpath,
				st_astext(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS newpathwkt,
				CASE WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
					array_to_json(ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[])
					ELSE NULL
				END AS verfahrensmerkmale,
				CASE
					WHEN count_externeref > 0
					THEN array_to_json(externeref.externereferenz)
					ELSE NULL
				END AS externereferenz,
				array_to_json(ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[]) AS auslegungsenddatum,
				array_to_json(ARRAY[(g.ags,g.rs,g.gemeindename,g.ortsteilname)]::xplan_gml.xp_gemeinde[]) AS gemeinde,
				to_json((gmlas.status_codespace, gmlas.status, NULL)::xplan_gml.fp_status) AS status,
				gmlas.sachgebiet AS sachgebiet,
				to_json((pg.name, pg.kennziffer)::xplan_gml.xp_plangeber) AS plangeber,
				gmlas.rechtsstand::xplan_gml.fp_rechtsstand AS rechtsstand,
				to_char(gmlas.wirksamkeitsdatum, 'DD.MM.YYYY') AS wirksamkeitsdatum,
				array_to_json(ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[]) AS auslegungsstartdatum,
				array_to_json(ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsstartdatum,
				to_char(gmlas.entwurfsbeschlussdatum, 'DD.MM.YYYY') AS entwurfsbeschlussdatum,
				to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY') AS aenderungenbisdatum,
				array_to_json(ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsenddatum,
				gmlas.verfahren::xplan_gml.fp_verfahren AS verfahren,
				(gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml.fp_sonstplanart AS sonstplanart,
				gmlas.planart::xplan_gml.fp_planart AS planart,
				to_char(gmlas.planbeschlussdatum, 'DD.MM.YYYY') AS planbeschlussdatum,
				to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY') AS aufstellungsbeschlussdatum,
				to_char(gmlas.versionbaunvodatum, 'DD.MM.YYYY') AS versionbaunvodatum,
				gmlas.versionbaunvotext AS versionbaunvotext,
				to_char(gmlas.versionbaugbdatum, 'DD.MM.YYYY') AS versionbaugbdatum,
				gmlas.versionbaugbtext AS versionbaugbtext,
				to_char(gmlas.versionsonstrechtsgrundlagedatum, 'DD.MM.YYYY') AS versionsonstrechtsgrundlagedatum,
				gmlas.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext
			FROM
				" . $this->gmlas_schema . ".fp_plan gmlas LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_gemeinde gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".xp_gemeinde g ON gemeindelink.xp_gemeinde_pkid = g.ogr_pkid LEFT JOIN
				(
					SELECT
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
								e_sub.typ::xplan_gml.xp_externereferenztyp,
								false
							)::xplankonverter.xp_spezexternereferenzauslegung) AS externereferenz
					FROM
						" . $this->gmlas_schema . ".fp_plan_externereferenz externereferenzlink_sub LEFT JOIN
						" . $this->gmlas_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
					GROUP BY
						externereferenzlink_sub.parent_id
				) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_plangeber pg ON gmlas.plangeber_xp_plangeber_pkid = pg.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_auslegungsstartdatum alsd ON gmlas.id = alsd.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_auslegungsenddatum aled ON gmlas.id = aled.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_traegerbeteiligungsstartdatum tbsd ON gmlas.id = tbsd.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_traegerbeteiligungsenddatum tbed ON gmlas.id = tbed.parent_id
			WHERE
				gmlas.id = '" . $gml_id . "'
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}

	/*
	* Returns an associative array to fill the so_plan form
	*/
	function fill_form_so_plan($gml_id) {
		$sql = "
			SELECT
				" . 
				"'" . $this->trim_gml_prefix_if_exists($gml_id) . "'::text::uuid AS plan_gml_id,
				gmlas.xplan_name AS name,
				gmlas.nummer AS nummer,
				gmlas.internalid AS internalid,
				gmlas.beschreibung AS beschreibung,
				gmlas.kommentar AS kommentar,
				to_char(gmlas.technherstelldatum, 'DD.MM.YYYY') AS technherstelldatum,
				to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY') AS genehmigungsdatum,
				to_char(gmlas.untergangsdatum, 'DD.MM.YYYY') AS untergangsdatum,
				CASE WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
					array_to_json(ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[])
					ELSE NULL
				END AS aendert,
				CASE WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
					array_to_json(ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[])
					ELSE NULL
				END AS wurdegeaendertvon,
				gmlas.erstellungsmassstab AS erstellungsmassstab,
				gmlas.bezugshoehe AS bezugshoehe,
				st_assvg(gmlas.raeumlichergeltungsbereich, 0, 8) AS newpath,
				st_astext(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS newpathwkt,
				CASE WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
					array_to_json(ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[])
					ELSE NULL
				END AS verfahrensmerkmale,
				CASE
					WHEN count_externeref > 0
					THEN array_to_json(externeref.externereferenz)
					ELSE NULL
				END AS externereferenz,
				to_json((pg.name, pg.kennziffer)::xplan_gml.xp_plangeber) AS plangeber,
				to_json((gmlas.planart_codespace, gmlas.planart, NULL)::xplan_gml.so_planart) AS planart,
				to_char(gmlas.versionbaugbdatum, 'DD.MM.YYYY') AS versionbaugbdatum,
				gmlas.versionbaugbtext AS versionbaugbtext,
				to_char(gmlas.versionsonstrechtsgrundlagedatum, 'DD.MM.YYYY') AS versionsonstrechtsgrundlagedatum,
				gmlas.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext
				/*ARRAY[(g.ags,g.rs,g.gemeindename,g.ortsteilname)]::xplan_gml.xp_gemeinde[] AS gemeinde*/
			FROM
				" . $this->gmlas_schema . ".so_plan gmlas LEFT JOIN
				/*" . $this->gmlas_schema . ".so_plan_gemeinde gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN*/
				/*" . $this->gmlas_schema . ".xp_gemeinde g ON gemeindelink.xp_gemeinde_pkid = g.ogr_pkid LEFT JOIN*/
				(
					SELECT
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
								e_sub.typ::xplan_gml.xp_externereferenztyp,
								false
							)::xplankonverter.xp_spezexternereferenzauslegung) AS externereferenz
					FROM
						" . $this->gmlas_schema . ".so_plan_externereferenz externereferenzlink_sub LEFT JOIN
						" . $this->gmlas_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
					GROUP BY
						externereferenzlink_sub.parent_id
				) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".xp_plangeber pg ON gmlas.plangeber_xp_plangeber_pkid = pg.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".so_plan_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".so_plan_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".so_plan_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid
			WHERE
				gmlas.id = '" . $gml_id . "'
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}

	/*
	* Returns an associative array to fill the rp_plan form
	*/
	function fill_form_rp_plan($gml_id) {
		$sql = "
			SELECT
				" . 
				"'" . $this->trim_gml_prefix_if_exists($gml_id) . "'::text::uuid AS plan_gml_id,
				gmlas.xplan_name AS name,
				gmlas.nummer AS nummer,
				array_to_json(gmlas.bundesland::xplan_gml.xp_bundeslaender[]) AS bundesland,
				gmlas.internalid AS internalid,
				gmlas.beschreibung AS beschreibung,
				gmlas.kommentar AS kommentar,
				to_char(gmlas.technherstelldatum, 'DD.MM.YYYY') AS technherstelldatum,
				to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY') AS genehmigungsdatum,
				to_char(gmlas.untergangsdatum, 'DD.MM.YYYY') AS untergangsdatum,
				CASE WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
					array_to_json(ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[])
					ELSE NULL
				END AS aendert,
				CASE WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
					array_to_json(ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[])
					ELSE NULL
				END AS wurdegeaendertvon,
				gmlas.erstellungsmassstab AS erstellungsmassstab,
				gmlas.bezugshoehe AS bezugshoehe,
				st_assvg(gmlas.raeumlichergeltungsbereich, 0, 8) AS newpath,
				st_astext(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS newpathwkt,
				CASE WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
					array_to_json(ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[])
					ELSE NULL
				END AS verfahrensmerkmale,
				CASE
					WHEN count_externeref > 0
					THEN array_to_json(externeref.externereferenz)
					ELSE NULL
				END AS externereferenz,
				gmlas.planungsregion AS planungsregion,
				gmlas.teilabschnitt AS teilabschnitt,
				gmlas.amtlicherschluessel AS amtlicherschluessel,
				gmlas.verfahren::xplan_gml.rp_verfahren AS verfahren,
				gmlas.rechtsstand::xplan_gml.rp_rechtsstand AS rechtsstand,
				to_char(gmlas.datumdesinkrafttretens, 'DD.MM.YYYY') AS datumdesinkrafttretens,
				to_char(gmlas.planbeschlussdatum, 'DD.MM.YYYY') AS planbeschlussdatum,
				to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY') AS aufstellungsbeschlussdatum,
				to_char(gmlas.entwurfsbeschlussdatum, 'DD.MM.YYYY') AS entwurfsbeschlussdatum,
				array_to_json(ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[]) AS auslegungsenddatum,
				(gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml.rp_sonstplanart AS sonstplanart,
				array_to_json(ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[]) AS auslegungsstartdatum,
				array_to_json(ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsstartdatum,
				to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY') AS aenderungenbisdatum,
				to_json((gmlas.status_codespace, gmlas.status, NULL)::xplan_gml.rp_status) AS status,
				array_to_json(ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsenddatum,
				gmlas.planart::xplan_gml.rp_art AS planart
			FROM
				" . $this->gmlas_schema . ".rp_plan gmlas LEFT JOIN
				(
					SELECT
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
								e_sub.datum,
								e_sub.typ::xplan_gml.xp_externereferenztyp
							)::xplan_gml.xp_spezexternereferenz) AS externereferenz
					FROM
						" . $this->gmlas_schema . ".rp_plan_externereferenz externereferenzlink_sub LEFT JOIN
						" . $this->gmlas_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
					GROUP BY
						externereferenzlink_sub.parent_id
				) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".rp_plan_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".rp_plan_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".rp_plan_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
				" . $this->gmlas_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
				" . $this->gmlas_schema . ".rp_plan_auslegungsstartdatum alsd ON gmlas.id = alsd.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".rp_plan_auslegungsenddatum aled ON gmlas.id = aled.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".rp_plan_traegerbeteiligungsstartdatum tbsd ON gmlas.id = tbsd.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".rp_plan_traegerbeteiligungsenddatum tbed ON gmlas.id = tbed.parent_id
			WHERE
				gmlas.id = '" . $gml_id . "'
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}

	/*
	* Inserts values of xplan_gmlas_... into xplan_gml bereiche tables, depending on the specific bereich (bp_bereich, fp_bereich etc.)
	*/
	function insert_into_bereich($table, $konvertierung_id, $user_id) {
		# Based on XPlanung 5.0.1
		# For XP_Plan
		$sql = "INSERT INTO xplan_gml." . $table . "(gml_id, nummer, name, bedeutung, detailliertebedeutung, erstellungsmassstab, geltungsbereich, user_id, konvertierung_id, rasterbasis, ";
		switch ($table) {
			case 'so_bereich' : {
			} break;
			case 'rp_bereich' : {
				$sql .= "versionbrog, versionbrogtext, versionlplg, versionlplgtext, geltungsmassstab, ";
			} break;
			default : {
				# Default = BP_Plan and FP_Plan (equivalent Bereich-attribute in XPlanung 5.0.1)
				$sql .= "versionbaunvodatum, versionbaugbtext, versionsonstrechtsgrundlagetext, versionbaunvotext, versionsonstrechtsgrundlagedatum, versionbaugbdatum, ";
			};
		}

		$sql .= "
				gehoertzuplan)
			SELECT
				xplankonverter.gml_id(gmlas.id) AS gml_id,
				gmlas.nummer AS nummer,
				gmlas.xplan_name AS name,
				gmlas.bedeutung::xplan_gml.xp_bedeutungenbereich AS bedeutung,
				gmlas.detailliertebedeutung AS detailliertebedeutung,
				gmlas.erstellungsmassstab AS erstellungsmassstab,
				ST_Multi(ST_ForceRHR(gmlas.geltungsbereich)) AS geltungsbereich,
				" . $user_id . " AS user_id,
				" . $konvertierung_id . " AS konvertierung_id,
				xplankonverter.gml_id(gmlas.rasterbasis_href) AS rasterbasis,";

		switch ($table) {
			case 'so_bereich' : {
			} break;
			case 'rp_bereich' : {
				$sql .= "
				gmlas.versionbrog AS versionbrog,
				gmlas.versionbrogtext AS versionbrogtext,
				gmlas.versionlplg AS versionlplg,
				gmlas.versionlplgtext As versionlplgtext,
				gmlas.geltungsmassstab AS geltungsmassstab,";
			} break;
			default : {
				# Default = BP_Plan and FP_Plan (equivalent Bereich-attribute in XPlanung 5.0.1)
				$sql .= "
				gmlas.versionbaunvodatum AS versionbaunvodatum,
				gmlas.versionbaugbtext AS versionbaugbtext,
				gmlas.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext,
				gmlas.versionbaunvotext AS versionbaunvotext,
				gmlas.versionsonstrechtsgrundlagedatum AS versionsonstrechtsgrundlagedatum,
				gmlas.versionbaugbdatum AS versionbaugbdatum,";
			};
		}
		// string needs to be lowered both to simplify cutting #gml_ in all forms and
		// because uuid (e.g. in gml_id of the associated plan) is always lowercase when cast to text in postgres
		// will take first plan encountered in gmlas-schema if bereich id is not set (or could not be read by ogr)
		$sql .= "
				CASE
					WHEN gmlas.gehoertzuplan_href IS NOT NULL THEN xplankonverter.gml_id(gmlas.gehoertzuplan_href)
					ELSE (SELECT DISTINCT xplankonverter.gml_id(id) FROM " . $this->gmlas_schema . "." . substr($table,0,3) . "plan LIMIT 1)
				END AS gehoertzuplan
			FROM
				" . $this->gmlas_schema . "." . $table . " gmlas
			;";
		# echo $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}

	/**
	 * Inserts values of xplan_gmlas_$konvertierung_id into xplan_gml textabschnitte tables, depending on the specific bereich (xp_textabschnitt, fp_textabschnitt etc.)
	 * Im ersten Schritt wird die Tabelle bp_textabschnitte befüllt und falls vorhanden die Attribute inverszu_texte_xp_plan und inversezu_bp_wohngebaeudeflaeche gesetzt
	 * Dann werden die Assoziationen in die Tabellen mit dem Suffix _zu_bp_textabschnitt geschrieben. (reftextinhalt in bp_objekt_zu_bp_textabschnitt und abweichungtext in bp_[nebenanlagenausschlussflaeche|baugebietsteilflaeche]_zu_bp_textabschnitte)
	 */
	function insert_into_textabschnitt($table, $konvertierung_id, $user_id) {
		# Based on XPlanung 5.0.1
		# currently no inverszu_baugebietsteilflaeche and nebenanlagenausschlussflaeche bp
		$prefix_arr = explode("_", $table, 2);
		$prefix = $prefix_arr[0];
		/*
			Beispielabfrage zur Erzeugung von xp_externereferenz und xp_spezexternereferenztypen as gmlas tabellen
			SELECT
				(
					e_sub.georefurl,
					(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
					e_sub.art::xplan_gml.xp_externereferenzart,
					e_sub.informationssystemurl,
					e_sub.referenzname,
					e_sub.referenzurl,
					(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
					e_sub.beschreibung,
					to_char(e_sub.datum, 'DD.MM.YYYY')
				)::xplan_gml.xp_externereferenz
			FROM
				xplan_gmlas_4986.xp_externereferenz AS e_sub
			SELECT
				(
					e_sub.georefurl,
					(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
					e_sub.art::xplan_gml.xp_externereferenzart,
					e_sub.informationssystemurl,
					e_sub.referenzname,
					e_sub.referenzurl,
					(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
					e_sub.beschreibung,
					to_char(e_sub.datum, 'DD.MM.YYYY'),
					e_sub.typ::xplan_gml.xp_externereferenztyp
				)::xplan_gml.xp_spezexternereferenz
			FROM
				xplan_gmlas_4986.xp_spezexternereferenz AS e_sub;
		*/
		# Verknüpfung reftext-Attribut
		$reftext = ($this->check_if_table_exists_in_schema($prefix . "_textabschnitt_externereferenz", $this->gmlas_schema) ? "CASE
					WHEN count_externeref > 0
					THEN array_to_json(externeref.externereferenz)
					ELSE NULL
				END" : "NULL");
		$reftext_tables = "
				" . ($this->check_if_table_exists_in_schema($prefix . "_textabschnitt_externereferenz", $this->gmlas_schema) ? " LEFT JOIN
				(
					SELECT
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
								to_char(e_sub.datum, 'DD.MM.YYYY')
							)::xplan_gml.xp_externereferenz) AS externereferenz
					FROM
						" . $this->gmlas_schema . "." . $prefix . "_textabschnitt_externereferenz externereferenzlink_sub LEFT JOIN
						" . $this->gmlas_schema . ".xp_externereferenz e_sub ON externereferenzlink_sub.xp_externereferenz_pkid = e_sub.ogr_pkid
					GROUP BY
						externereferenzlink_sub.parent_id
				) externeref ON ta.id = externeref.parent_id" : "");

		if ($this->check_if_table_exists_in_schema($prefix . "_plan_texte", $this->gmlas_schema)) {
			$inverszu_texte_xp_plan_insert = ",
				inverszu_texte_xp_plan";
			$inverszu_texte_xp_plan_select = ",
				CASE WHEN plan_texte.parent_id IS NULL THEN NULL ELSE xplankonverter.gml_id(plan_texte.parent_id) END AS inverszu_texte_xp_plan";
			$inverszu_texte_xp_plan_table = " LEFT JOIN
				" . $this->gmlas_schema . "." . $prefix . "_plan_texte plan_texte ON ta.id = plan_texte.href_" . $prefix . "_textabschnitt_pkid";
		}

		# Textabschnitte zu Fachobjekten, Assoziation: reftextinhalt
		$tables_with_reftextinhalt = array_values(array_filter(
			$this->get_all_tables_in_schema($this->gmlas_schema),
			function ($table_in_schema) {
				return $this->string_ends_with($table_in_schema, "_reftextinhalt");
			}
		));
		// echo '<br>tables_with_reftextinhalt: ' . print_r($tables_with_reftextinhalt, true);

		if ($prefix == 'bp') {
			if ($this->check_if_table_exists_in_schema("bp_wohngebaeudeflaeche_abweichungtext", $this->gmlas_schema)) {
				$inverszu_abweichungtext_bp_wohngebaeudeflaeche_insert = ",
					inverszu_abweichungtext_bp_wohngebaeudeflaeche";
				$inverszu_abweichungtext_bp_wohngebaeudeflaeche_select = ",
					CASE WHEN wgf_at.parent_id IS NULL THEN NULL ELSE xplankonverter.gml_id(wgf_at.parent_id) END AS inverszu_abweichungtext_bp_wohngebaeudeflaeche";
				$inverszu_abweichungtext_bp_wohngebaeudeflaeche_table = " LEFT JOIN
					" . $this->gmlas_schema . ".bp_wohngebaeudeflaeche_abweichungtext AS wgf_at ON ta.id = wgf_at." . $this->get_reftextinhalt_fk_column_name();
			}
			$bp_tables_with_reftextinhalt = array_values(array_filter(
				$tables_with_reftextinhalt,
				function ($table_with_reftextinhalt) {
					return ($this->string_starts_with($table_with_reftextinhalt, "bp_") AND $this->check_if_table_exists_in_schema($table_with_reftextinhalt, $this->gmlas_schema));
				}
			));
			// echo '<br>bp_tables_with_reftextinhalt: ' . print_r($bp_tables_with_reftextinhalt, true);
			if (count($bp_tables_with_reftextinhalt) > 0) {
				$inverszu_reftextinhalt_bp_objekt_insert = ",
				inverszu_reftextinhalt_bp_objekt";
				$inverszu_reftextinhalt_bp_objekt_selects = array();
				$inverszu_reftextinhalt_bp_objekt_tables = "";
				for ($i = 0; $i < count($bp_tables_with_reftextinhalt); $i++) {
					$table_name = $this->gmlas_schema . "." . $bp_tables_with_reftextinhalt[$i];
					$table_alias = $bp_tables_with_reftextinhalt[$i];
					#$sql .= " LEFT JOIN " . $this->gmlas_schema . "." . $all_tables_with_reftextinhalt_suffix[$i] . " ref" . $i. " ON " . "gmlas.id = ref" . $i . ".href_" . $prefix . "_textabschnitt_pkid";
					$inverszu_reftextinhalt_bp_objekt_tables .= " LEFT JOIN
					" . $table_name . " AS " . $table_alias . " ON ta.id = " . $table_alias . "." . $this->get_reftextinhalt_fk_column_name();
					$inverszu_reftextinhalt_bp_objekt_selects[] = "xplankonverter.gml_id(" . $table_alias . ".parent_id)";
				}
				$inverszu_reftextinhalt_bp_objekt_select = ",
				gdi_array_unique(STRING_TO_ARRAY(STRING_AGG(CONCAT_WS(','," . implode(',', $inverszu_reftextinhalt_bp_objekt_selects) . "), ','), ',')) AS inverszu_reftextinhalt_bp_objekt";
			}
		}
		else {
			$inverszu_abweichungtext_bp_wohngebaeudeflaeche_insert = $inverszu_abweichungtext_bp_wohngebaeudeflaeche_select = $inverszu_abweichungtext_bp_wohngebaeudeflaeche_table = "";
		}
		
		# Textabschnitte zum Planobjekt, Assoziation: texte
		$sql = "
			INSERT INTO xplan_gml." . $prefix . "_textabschnitt (
				gml_id,
				schluessel,
				gesetzlichegrundlage,
				text,
				rechtscharakter,
				reftext,
				user_id,
				konvertierung_id" .
				$inverszu_texte_xp_plan_insert .
				$inverszu_abweichungtext_bp_wohngebaeudeflaeche_insert .
				$inverszu_reftextinhalt_bp_objekt_insert. "
			)
			SELECT
				xplankonverter.gml_id(ta.id) AS gml_id,
				ta.schluessel AS schluessel,
				ta.gesetzlichegrundlage AS gesetzlichegrundlage,
				ta.text AS text,
				ta.rechtscharakter::xplan_gml." . $prefix . "_rechtscharakter AS rechtscharakter,
				" . $reftext . "::xplan_gml.xp_externereferenz AS reftext,
				" . $user_id . " AS user_id,
				" . $konvertierung_id . " AS konvertierung_id" .
				$inverszu_texte_xp_plan_select .
				$inverszu_abweichungtext_bp_wohngebaeudeflaeche_select .
				$inverszu_reftextinhalt_bp_objekt_select . "
			FROM
				" . $this->gmlas_schema . "." . $table . " ta" .
				$inverszu_texte_xp_plan_table .
				$inverszu_abweichungtext_bp_wohngebaeudeflaeche_table .
				$inverszu_reftextinhalt_bp_objekt_tables . "
			GROUP BY
				gml_id,
				schluessel,
				gesetzlichegrundlage,
				text,
				rechtscharakter,
				reftext,
				user_id,
				konvertierung_id" .
				$inverszu_texte_xp_plan_insert .
				$inverszu_abweichungtext_bp_wohngebaeudeflaeche_insert . "
		";
		// echo '<br>SQL zum Eintragen der Textabschnitte zum Plan: ' . $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		if (!$ret['success']) {
			return array(
				'success' => false,
				$msg = $ret['msg']
			);
		}
		$selects_reftextinhalt = array();
		if(!empty($table_with_reftextinhalt)) {
			foreach ($tables_with_reftextinhalt AS $table_with_reftextinhalt) {
				$selects_reftextinhalt[] = "
					SELECT
						xplankonverter.gml_id(parent_id) AS " . $prefix . "_objekt_gml_id,
						xplankonverter.gml_id(href_" . $prefix . "_textabschnitt_pkid) AS " . $prefix . "_textabschnitt_gml_id
					FROM
						" . $this->gmlas_schema . "." . $table_with_reftextinhalt['table_name'] . "
				";
			}
		}
		$this->pgdatabase->gui->write_xlog('tables_with_reftextinhalt: ' . print_r($tables_with_reftextinhalt, true));
		$this->pgdatabase->gui->write_xlog('select_reftextinhalte: ' . print_r($selects_reftextinhalt, true));
		if (count($selects_reftextinhalt) > 0) {
			$sql = "
				INSERT INTO xplan_gml." . $prefix . "_objekt_zu_" . $prefix . "_textabschnitt (
					" . $prefix . "_objekt_gml_id,
					" . $prefix . "_textabschnitt_gml_id
				) SELECT * FROM (" . implode(' UNION ', $selects_reftextinhalt) . ") AS reftextinhalte
			";
			#echo '<br>SQL zum Eintragen der Textabschnitte zu den Fachobjekten: ' . $sql; exit;
			$ret = $this->pgdatabase->execSQL($sql, 4, 0);
			if (!$ret['success']) {
				return array(
					'success' => false,
					$msg = $ret['msg']
				);
			}
		}

		# Nur für BP-Pläne Textabschnitte für abweichende Texte zu ausgewählten BP-Objekten, Assoziation: abweichungtext
		if ($prefix == 'bp') {
			$tables_with_abweichungtext = array('bp_baugebietsteilflaeche', 'bp_nebenanlagenausschlussflaeche');
			foreach ($tables_with_abweichungtext AS $table_with_abweichungtext) {
				if ($this->check_if_table_exists_in_schema($table_with_abweichungtext . "_abweichungtext", $this->gmlas_schema)) {
					$sql = "
						INSERT INTO xplan_gml." . $table_with_abweichungtext . "_zu_bp_textabschnitt (
							" . $table_with_abweichungtext . "_gml_id,
							bp_textabschnitt_gml_id
						)
						SELECT
							xplankonverter.gml_id(parent_id) AS " . $table_with_abweichungtext . "_gml_id,
							xplankonverter.gml_id(href_bp_textabschnitt_pkid) AS bp_textabschnitt_gml_id
						FROM
							" . $this->gmlas_schema . "." . $table_with_abweichungtext . "_abweichungtext
					";
					#echo '<br>SQL zum Eintragen der abweichungtext in Tabelle ' . $table_with_abweichungtext . ': ' . $sql;
					$ret = $this->pgdatabase->execSQL($sql, 4, 0);
					if (!$ret['success']) {
						return array(
							'success' => false,
							$msg = $ret['msg']
						);
					}
				}
			}
		}
		return array(
			'success' => true,
			'msg' => 'Textabschnitte erfolgreich eingelesen.'
		);
	}

	/**
	 * Function check if $needle is empty or if $haystack ends with $needle 
	* In php 8.0+ str_ends_with
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	function string_ends_with($haystack, $needle) {
		return substr($haystack, -strlen($needle)) === $needle;
	}

	/**
	 * Function check if the $needle is empty or if $haystack starts with $needle
	 * In php 8.0 str_starts_with
	 * @param string $haystack
	 * @param string $needle
	 * @return boolean
	 */
	function string_starts_with($haystack, $needle) {
		return $needle === "" || strpos($haystack, $needle) === 0;
	}

	/*
	 * This function builds the basic xplan_gmlas tables for the basisobjekte of all schemas (xp, bp, fp, lp, rp, so)
	 */
	function build_basic_tables() {
		# Prepare schema 
		if ($this->pgdatabase->schema_exists($this->gmlas_schema)) {
			$this->pgdatabase->drop_schema($this->gmlas_schema, TRUE);
		}
		$this->pgdatabase->create_schema($this->gmlas_schema);

		# Currently creates 39 tables related to BP/FP/RP/SO-Plan and Bereich
		# does not include praesentationsobjekte, textabschnitt or begruendungabschnitt
		# Based on XPlanung 5.0.1
		# SQL's for tables created through ogr2ogr gmlas complete schema creation
		$sql = "
			CREATE TABLE " . $this->gmlas_schema . ".xplanauszug
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				xsd_version character varying,
				CONSTRAINT xplanauszug_pkey PRIMARY KEY (ogc_fid)
			);

			CREATE TABLE " . $this->gmlas_schema . ".xplanauszug_featuremember
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				owns boolean DEFAULT false,
				href character varying,
				title character varying,
				nilreason character varying,
				abstractfeature_xplanauszug_pkid character varying,
				abstractfeature_abstractdiscretecoverage_pkid character varying,
				abstractfeature_multipointcoverage_pkid character varying,
				abstractfeature_rectifiedgridcoverage_pkid character varying,
				abstractfeature_xp_begruendungabschnitt_pkid character varying,
				abstractfeature_bp_bereich_pkid character varying,
				abstractfeature_fp_bereich_pkid character varying,
				abstractfeature_rp_bereich_pkid character varying,
				abstractfeature_lp_bereich_pkid character varying,
				abstractfeature_so_bereich_pkid character varying,
				abstractfeature_bp_baugebietsteilflaeche_pkid character varying,
				abstractfeature_bp_gemeinbedarfsflaeche_pkid character varying,
				abstractfeature_bp_spielsportanlagenflaeche_pkid character varying,
				abstractfeature_bp_gruenflaeche_pkid character varying,
				abstractfeature_bp_kleintierhaltungflaeche_pkid character varying,
				abstractfeature_bp_waldflaeche_pkid character varying,
				abstractfeature_bp_strassenverkehrsflaeche_pkid character varying,
				abstractfeature_bp_verkehrsflaecbesondererzweckbestimmu_pkid character varying,
				abstractfeature_bp_gewaesserflaeche_pkid character varying,
				abstractfeature_bp_abstandsflaeche_pkid character varying,
				abstractfeature_bp_foerderungsflaeche_pkid character varying,
				abstractfeature_bp_gebaeudeflaeche_pkid character varying,
				abstractfeature_bp_gemeinschaftsanlagenflaeche_pkid character varying,
				abstractfeature_bp_nebenanlagenausschlussflaeche_pkid character varying,
				abstractfeature_bp_nebenanlagenflaeche_pkid character varying,
				abstractfeature_bp_persgruppenbestimmteflaeche_pkid character varying,
				abstractfeature_bp_regelungvergnuegungsstaetten_pkid character varying,
				abstractfeature_bp_speziellebauweise_pkid character varying,
				abstractfeature_bp_ueberbaubaregrundstuecksflaeche_pkid character varying,
				abstractfeature_bp_erhaltungsbereichflaeche_pkid character varying,
				abstractfeature_bp_eingriffsbereich_pkid character varying,
				abstractfeature_bp_freiflaeche_pkid character varying,
				abstractfeature_bp_textlichefestsetzungsflaeche_pkid character varying,
				abstractfeature_bp_veraenderungssperre_pkid character varying,
				abstractfeature_bp_technischemassnahmenflaeche_pkid character varying,
				abstractfeature_bp_abgrabungsflaeche_pkid character varying,
				abstractfeature_bp_aufschuettungsflaeche_pkid character varying,
				abstractfeature_bp_bodenschaetzeflaeche_pkid character varying,
				abstractfeature_bp_rekultivierungsflaeche_pkid character varying,
				abstractfeature_bp_besonderernutzungszweckflaeche_pkid character varying,
				abstractfeature_bp_ausgleichsflaeche_pkid character varying,
				abstractfeature_bp_schutzpflegeentwicklungsflaeche_pkid character varying,
				abstractfeature_bp_kennzeichnungsflaeche_pkid character varying,
				abstractfeature_bp_wasserwirtschaftsflaeche_pkid character varying,
				abstractfeature_bp_gemeinschaftsanlagenzuordnung_pkid character varying,
				abstractfeature_bp_landwirtschaft_pkid character varying,
				abstractfeature_bp_anpflanzungbindungerhaltung_pkid character varying,
				abstractfeature_bp_ausgleichsmassnahme_pkid character varying,
				abstractfeature_bp_schutzpflegeentwicklungsmassnahme_pkid character varying,
				abstractfeature_bp_abstandsmass_pkid character varying,
				abstractfeature_bp_festsetzungnachlandesrecht_pkid character varying,
				abstractfeature_bp_generischesobjekt_pkid character varying,
				abstractfeature_bp_hoehenmass_pkid character varying,
				abstractfeature_bp_unverbindlichevormerkung_pkid character varying,
				abstractfeature_bp_wegerecht_pkid character varying,
				abstractfeature_bp_immissionsschutz_pkid character varying,
				abstractfeature_bp_verentsorgung_pkid character varying,
				abstractfeature_bp_strassenkoerper_pkid character varying,
				abstractfeature_bp_baugrenze_pkid character varying,
				abstractfeature_bp_baulinie_pkid character varying,
				abstractfeature_bp_firstrichtungslinie_pkid character varying,
				abstractfeature_bp_nutzungsartengrenze_pkid character varying,
				abstractfeature_bp_bereichohneeinausfahrtlinie_pkid character varying,
				abstractfeature_bp_einfahrtsbereichlinie_pkid character varying,
				abstractfeature_bp_strassenbegrenzungslinie_pkid character varying,
				abstractfeature_bp_einfahrtpunkt_pkid character varying,
				abstractfeature_fp_bebauungsflaeche_pkid character varying,
				abstractfeature_fp_landwirtschaftsflaeche_pkid character varying,
				abstractfeature_fp_waldflaeche_pkid character varying,
				abstractfeature_fp_nutzungsbeschraenkungsflaeche_pkid character varying,
				abstractfeature_fp_textlichedarstellungsflaeche_pkid character varying,
				abstractfeature_fp_zentralerversorgungsbereich_pkid character varying,
				abstractfeature_fp_keinezentrabwasserbeseitigungflaeche_pkid character varying,
				abstractfeature_fp_ausgleichsflaeche_pkid character varying,
				abstractfeature_fp_vorbehalteflaeche_pkid character varying,
				abstractfeature_fp_abgrabung_pkid character varying,
				abstractfeature_fp_aufschuettung_pkid character varying,
				abstractfeature_fp_bodenschaetze_pkid character varying,
				abstractfeature_fp_anpassungklimawandel_pkid character varying,
				abstractfeature_fp_gemeinbedarf_pkid character varying,
				abstractfeature_fp_spielsportanlage_pkid character varying,
				abstractfeature_fp_gruen_pkid character varying,
				abstractfeature_fp_schutzpflegeentwicklung_pkid character varying,
				abstractfeature_fp_generischesobjekt_pkid character varying,
				abstractfeature_fp_kennzeichnung_pkid character varying,
				abstractfeature_fp_privilegiertesvorhaben_pkid character varying,
				abstractfeature_fp_unverbindlichevormerkung_pkid character varying,
				abstractfeature_fp_verentsorgung_pkid character varying,
				abstractfeature_fp_strassenverkehr_pkid character varying,
				abstractfeature_fp_gewaesser_pkid character varying,
				abstractfeature_fp_wasserwirtschaft_pkid character varying,
				abstractfeature_rp_freiraum_pkid character varying,
				abstractfeature_rp_bodenschutz_pkid character varying,
				abstractfeature_rp_erholung_pkid character varying,
				abstractfeature_rp_erneuerbareenergie_pkid character varying,
				abstractfeature_rp_forstwirtschaft_pkid character varying,
				abstractfeature_rp_gewaesser_pkid character varying,
				abstractfeature_rp_gruenzuggruenzaesur_pkid character varying,
				abstractfeature_rp_hochwasserschutz_pkid character varying,
				abstractfeature_rp_klimaschutz_pkid character varying,
				abstractfeature_rp_kulturlandschaft_pkid character varying,
				abstractfeature_rp_landwirtschaft_pkid character varying,
				abstractfeature_rp_naturlandschaft_pkid character varying,
				abstractfeature_rp_naturschutzrechtlichesschutzgebiet_pkid character varying,
				abstractfeature_rp_radwegwanderweg_pkid character varying,
				abstractfeature_rp_rohstoff_pkid character varying,
				abstractfeature_rp_sonstigerfreiraumschutz_pkid character varying,
				abstractfeature_rp_sportanlage_pkid character varying,
				abstractfeature_rp_wasserschutz_pkid character varying,
				abstractfeature_rp_energieversorgung_pkid character varying,
				abstractfeature_rp_entsorgung_pkid character varying,
				abstractfeature_rp_kommunikation_pkid character varying,
				abstractfeature_rp_laermschutzbauschutz_pkid character varying,
				abstractfeature_rp_verkehr_pkid character varying,
				abstractfeature_rp_luftverkehr_pkid character varying,
				abstractfeature_rp_schienenverkehr_pkid character varying,
				abstractfeature_rp_sonstverkehr_pkid character varying,
				abstractfeature_rp_strassenverkehr_pkid character varying,
				abstractfeature_rp_wasserverkehr_pkid character varying,
				abstractfeature_rp_sonstigeinfrastruktur_pkid character varying,
				abstractfeature_rp_sozialeinfrastruktur_pkid character varying,
				abstractfeature_rp_wasserwirtschaft_pkid character varying,
				abstractfeature_rp_achse_pkid character varying,
				abstractfeature_rp_siedlung_pkid character varying,
				abstractfeature_rp_einzelhandel_pkid character varying,
				abstractfeature_rp_industriegewerbe_pkid character varying,
				abstractfeature_rp_sonstigersiedlungsbereich_pkid character varying,
				abstractfeature_rp_wohnensiedlung_pkid character varying,
				abstractfeature_rp_funktionszuweisung_pkid character varying,
				abstractfeature_rp_raumkategorie_pkid character varying,
				abstractfeature_rp_sperrgebiet_pkid character varying,
				abstractfeature_rp_zentralerort_pkid character varying,
				abstractfeature_rp_generischesobjekt_pkid character varying,
				abstractfeature_rp_grenze_pkid character varying,
				abstractfeature_rp_planungsraum_pkid character varying,
				abstractfeature_lp_allggruenflaeche_pkid character varying,
				abstractfeature_lp_textlichefestsetzungsflaeche_pkid character varying,
				abstractfeature_lp_zubegruenendegrundstueckflaeche_pkid character varying,
				abstractfeature_lp_erholungfreizeit_pkid character varying,
				abstractfeature_lp_anpflanzungbindungerhaltung_pkid character varying,
				abstractfeature_lp_ausgleich_pkid character varying,
				abstractfeature_lp_nutzungserfordernisregelung_pkid character varying,
				abstractfeature_lp_schutzpflegeentwicklung_pkid character varying,
				abstractfeature_lp_zwischennutzung_pkid character varying,
				abstractfeature_lp_biotopverbundflaeche_pkid character varying,
				abstractfeature_lp_bodenschutzrecht_pkid character varying,
				abstractfeature_lp_forstrecht_pkid character varying,
				abstractfeature_lp_schutzobjektinternatrecht_pkid character varying,
				abstractfeature_lp_sonstigesrecht_pkid character varying,
				abstractfeature_lp_wasserrecgemeingebeinschraenaturschu_pkid character varying,
				abstractfeature_lp_wasserrechtschutzgebiet_pkid character varying,
				abstractfeature_lp_wasserrechtsonstige_pkid character varying,
				abstractfeature_lp_wasserrecwirtschafabflusshochwschutz_pkid character varying,
				abstractfeature_lp_generischesobjekt_pkid character varying,
				abstractfeature_lp_landschaftsbild_pkid character varying,
				abstractfeature_lp_nutzungsausschluss_pkid character varying,
				abstractfeature_lp_planerischevertiefung_pkid character varying,
				abstractfeature_lp_abgrenzung_pkid character varying,
				abstractfeature_so_objekt_pkid character varying,
				abstractfeature_so_gebiet_pkid character varying,
				abstractfeature_so_bodenschutzrecht_pkid character varying,
				abstractfeature_so_denkmalschutzrecht_pkid character varying,
				abstractfeature_so_forstrecht_pkid character varying,
				abstractfeature_so_luftverkehrsrecht_pkid character varying,
				abstractfeature_so_schienenverkehrsrecht_pkid character varying,
				abstractfeature_so_sonstigesrecht_pkid character varying,
				abstractfeature_so_strassenverkehrsrecht_pkid character varying,
				abstractfeature_so_wasserrecht_pkid character varying,
				abstractfeature_so_schutzgebietnaturschutzrecht_pkid character varying,
				abstractfeature_so_schutzgebietsonstigesrecht_pkid character varying,
				abstractfeature_so_schutzgebietwasserrecht_pkid character varying,
				abstractfeature_so_linienobjekt_pkid character varying,
				abstractfeature_so_grenze_pkid character varying,
				abstractfeature_bp_plan_pkid character varying,
				abstractfeature_fp_plan_pkid character varying,
				abstractfeature_rp_plan_pkid character varying,
				abstractfeature_lp_plan_pkid character varying,
				abstractfeature_so_plan_pkid character varying,
				abstractfeature_bp_textabschnitt_pkid character varying,
				abstractfeature_fp_textabschnitt_pkid character varying,
				abstractfeature_rp_textabschnitt_pkid character varying,
				abstractfeature_lp_textabschnitt_pkid character varying,
				abstractfeature_so_textabschnitt_pkid character varying,
				abstractfeature_xp_fpo_pkid character varying,
				abstractfeature_xp_lpo_pkid character varying,
				abstractfeature_xp_lto_pkid character varying,
				abstractfeature_xp_pto_pkid character varying,
				abstractfeature_xp_nutzungsschablone_pkid character varying,
				abstractfeature_xp_ppo_pkid character varying,
				abstractfeature_xp_praesentationsobjekt_pkid character varying,
				abstractfeature_xp_rasterdarstellung_pkid character varying,
				abstractfeature_rp_legendenobjekt_pkid character varying,
				CONSTRAINT xplanauszug_featuremember_pkey PRIMARY KEY (ogc_fid)
			);

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				xplan_name character varying NOT NULL,
				nummer character varying,
				internalid character varying,
				beschreibung character varying,
				kommentar character varying,
				technherstelldatum date,
				genehmigungsdatum date,
				untergangsdatum date,
				erstellungsmassstab integer,
				bezugshoehe_uom character varying,
				bezugshoehe double precision,
				plangeber_xp_plangeber_pkid character varying,
				planart character varying[] NOT NULL,
				sonstplanart_codespace character varying,
				sonstplanart character varying,
				verfahren character varying,
				rechtsstand character varying,
				status_codespace character varying,
				status character varying,
				hoehenbezug character varying,
				aenderungenbisdatum date,
				aufstellungsbeschlussdatum date,
				veraenderungssperredatum date,
				satzungsbeschlussdatum date,
				rechtsverordnungsdatum date,
				inkrafttretensdatum date,
				ausfertigungsdatum date,
				veraenderungssperre boolean,
				staedtebaulichervertrag boolean,
				erschliessungsvertrag boolean,
				durchfuehrungsvertrag boolean,
				gruenordnungsplan boolean,
				raeumlichergeltungsbereich geometry(Geometry," . $this->input_epsg . "),
				CONSTRAINT bp_plan_pkey PRIMARY KEY (ogc_fid)
			);

			CREATE TABLE " . $this->gmlas_schema . ".bp_bereich
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				nummer integer NOT NULL,
				xplan_name character varying,
				bedeutung character varying,
				detailliertebedeutung character varying,
				erstellungsmassstab integer,
				rasterbasis_owns boolean DEFAULT false,
				rasterbasis_href character varying,
				rasterbasis_title character varying,
				rasterbasis_nilreason character varying,
				rasterbasis_pkid character varying,
				versionbaunvodatum date,
				versionbaunvotext character varying,
				versionbaugbdatum date,
				versionbaugbtext character varying,
				versionsonstrechtsgrundlagedatum date,
				versionsonstrechtsgrundlagetext character varying,
				gehoertzuplan_owns boolean DEFAULT false,
				gehoertzuplan_href character varying,
				gehoertzuplan_title character varying,
				gehoertzuplan_nilreason character varying,
				gehoertzuplan_pkid character varying,
				geltungsbereich geometry(Geometry," . $this->input_epsg . "),
				CONSTRAINT bp_bereich_pkey PRIMARY KEY (ogc_fid)
			);

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				xplan_name character varying NOT NULL,
				nummer character varying,
				internalid character varying,
				beschreibung character varying,
				kommentar character varying,
				technherstelldatum date,
				genehmigungsdatum date,
				untergangsdatum date,
				erstellungsmassstab integer,
				bezugshoehe_uom character varying,
				bezugshoehe double precision,
				plangeber_xp_plangeber_pkid character varying,
				planart character varying NOT NULL,
				sonstplanart_codespace character varying,
				sonstplanart character varying,
				sachgebiet character varying,
				verfahren character varying,
				rechtsstand character varying,
				status_codespace character varying,
				status character varying,
				aufstellungsbeschlussdatum date,
				aenderungenbisdatum date,
				entwurfsbeschlussdatum date,
				planbeschlussdatum date,
				wirksamkeitsdatum date,
				raeumlichergeltungsbereich geometry(Geometry," . $this->input_epsg . "),
				CONSTRAINT fp_plan_pkey PRIMARY KEY (ogc_fid)
			);

			CREATE TABLE " . $this->gmlas_schema . ".fp_bereich
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				nummer integer NOT NULL,
				xplan_name character varying,
				bedeutung character varying,
				detailliertebedeutung character varying,
				erstellungsmassstab integer,
				rasterbasis_owns boolean DEFAULT false,
				rasterbasis_href character varying,
				rasterbasis_title character varying,
				rasterbasis_nilreason character varying,
				rasterbasis_pkid character varying,
				versionbaunvodatum date,
				versionbaunvotext character varying,
				versionbaugbdatum date,
				versionbaugbtext character varying,
				versionsonstrechtsgrundlagedatum date,
				versionsonstrechtsgrundlagetext character varying,
				gehoertzuplan_owns boolean DEFAULT false,
				gehoertzuplan_href character varying,
				gehoertzuplan_title character varying,
				gehoertzuplan_nilreason character varying,
				gehoertzuplan_pkid character varying,
				geltungsbereich geometry(Geometry," . $this->input_epsg . "),
				CONSTRAINT fp_bereich_pkey PRIMARY KEY (ogc_fid)
			);

			CREATE TABLE " . $this->gmlas_schema . ".so_plan
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				xplan_name character varying NOT NULL,
				nummer character varying,
				internalid character varying,
				beschreibung character varying,
				kommentar character varying,
				technherstelldatum date,
				genehmigungsdatum date,
				untergangsdatum date,
				erstellungsmassstab integer,
				bezugshoehe_uom character varying,
				bezugshoehe double precision,
				planart_codespace character varying,
				planart character varying,
				plangeber_xp_plangeber_pkid character varying,
				raeumlichergeltungsbereich geometry(Geometry," . $this->input_epsg . "),
				CONSTRAINT so_plan_pkey PRIMARY KEY (ogc_fid)
			);

			CREATE TABLE " . $this->gmlas_schema . ".so_bereich
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				nummer integer NOT NULL,
				xplan_name character varying,
				bedeutung character varying,
				detailliertebedeutung character varying,
				erstellungsmassstab integer,
				rasterbasis_owns boolean DEFAULT false,
				rasterbasis_href character varying,
				rasterbasis_title character varying,
				rasterbasis_nilreason character varying,
				rasterbasis_pkid character varying,
				gehoertzuplan_owns boolean DEFAULT false,
				gehoertzuplan_href character varying,
				gehoertzuplan_title character varying,
				gehoertzuplan_nilreason character varying,
				gehoertzuplan_pkid character varying,
				geltungsbereich geometry(Geometry," . $this->input_epsg . "),
				CONSTRAINT so_bereich_pkey PRIMARY KEY (ogc_fid)
			);


			CREATE TABLE " . $this->gmlas_schema . ".rp_plan
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				xplan_name character varying NOT NULL,
				nummer character varying,
				internalid character varying,
				beschreibung character varying,
				kommentar character varying,
				technherstelldatum date,
				genehmigungsdatum date,
				untergangsdatum date,
				erstellungsmassstab integer,
				bezugshoehe_uom character varying,
				bezugshoehe double precision,
				bundesland character varying[] NOT NULL,
				planart character varying NOT NULL,
				sonstplanart_codespace character varying,
				sonstplanart character varying,
				planungsregion integer,
				teilabschnitt integer,
				rechtsstand character varying,
				status_codespace character varying,
				status character varying,
				aufstellungsbeschlussdatum date,
				aenderungenbisdatum date,
				entwurfsbeschlussdatum date,
				planbeschlussdatum date,
				datumdesinkrafttretens date,
				verfahren character varying,
				amtlicherschluessel integer,
				raeumlichergeltungsbereich geometry(Geometry," . $this->input_epsg . "),
				CONSTRAINT rp_plan_pkey PRIMARY KEY (ogc_fid)
			);

			CREATE TABLE " . $this->gmlas_schema . ".rp_bereich
			(
				ogc_fid serial NOT NULL,
				id character varying NOT NULL,
				description character varying,
				descriptionreference_href character varying,
				descriptionreference_title character varying,
				descriptionreference_nilreason character varying,
				identifier_codespace character varying,
				identifier character varying,
				nummer integer NOT NULL,
				xplan_name character varying,
				bedeutung character varying,
				detailliertebedeutung character varying,
				erstellungsmassstab integer,
				rasterbasis_owns boolean DEFAULT false,
				rasterbasis_href character varying,
				rasterbasis_title character varying,
				rasterbasis_nilreason character varying,
				rasterbasis_pkid character varying,
				versionbrog date,
				versionbrogtext character varying,
				versionlplg date,
				versionlplgtext character varying,
				geltungsmassstab integer,
				gehoertzuplan_owns boolean DEFAULT false,
				gehoertzuplan_href character varying,
				gehoertzuplan_title character varying,
				gehoertzuplan_nilreason character varying,
				gehoertzuplan_pkid character varying,
				geltungsbereich geometry(Geometry," . $this->input_epsg . "),
				CONSTRAINT rp_bereich_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_bereich
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				owns boolean DEFAULT false,
				href character varying,
				title character varying,
				nilreason character varying,
				bereich_pkid character varying,
				CONSTRAINT bp_plan_bereich_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_bereich
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				owns boolean DEFAULT false,
				href character varying,
				title character varying,
				nilreason character varying,
				bereich_pkid character varying,
				CONSTRAINT fp_plan_bereich_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_bereich
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				owns boolean DEFAULT false,
				href character varying,
				title character varying,
				nilreason character varying,
				bereich_pkid character varying,
				CONSTRAINT rp_plan_bereich_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".so_plan_bereich
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				owns boolean DEFAULT false,
				href character varying,
				title character varying,
				nilreason character varying,
				bereich_pkid character varying,
				CONSTRAINT so_plan_bereich_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".xp_verbundenerplan
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				planname character varying,
				rechtscharakter character varying NOT NULL,
				nummer character varying,
				verbundenerplan_owns boolean DEFAULT false,
				verbundenerplan_href character varying,
				verbundenerplan_title character varying,
				verbundenerplan_nilreason character varying,
				CONSTRAINT xp_verbundenerplan_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".aendert
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				xp_verbundenerplan_pkid character varying,
				CONSTRAINT aendert_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_aendert_aendert
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT bp_plan_aendert_aendert_pkey PRIMARY KEY (ogc_fid)
			)
			;


			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_aendert_aendert
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT fp_plan_aendert_aendert_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_aendert_aendert
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT rp_plan_aendert_aendert_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".so_plan_aendert_aendert
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT so_plan_aendert_aendert_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".wurdegeaendertvon
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				xp_verbundenerplan_pkid character varying,
				CONSTRAINT wurdegeaendertvon_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_wurdegeaendertvon_wurdegeaendertvon
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT bp_plan_wurdegeaendertvon_wurdegeaendertvon_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_wurdegeaendertvon_wurdegeaendertvon
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT fp_plan_wurdegeaendertvon_wurdegeaendertvon_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_wurdegeaendertvon_wurdegeaendertvon
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT rp_plan_wurdegeaendertvon_wurdegeaendertvon_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".so_plan_wurdegeaendertvon_wurdegeaendertvon
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT so_plan_wurdegeaendertvon_wurdegeaendertvon_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".verfahrensmerkmale
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				xp_verfahrensmerkmal_vermerk character varying,
				xp_verfahrensmerkmal_datum date,
				xp_verfahrensmerkmal_signatur character varying,
				xp_verfahrensmerkmal_signiert boolean,
				CONSTRAINT verfahrensmerkmale_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_verfahrensmerkmale_verfahrensmerkmale
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT bp_plan_verfahrensmerkmale_verfahrensmerkmale_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_verfahrensmerkmale_verfahrensmerkmale
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT fp_plan_verfahrensmerkmale_verfahrensmerkmale_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_verfahrensmerkmale_verfahrensmerkmale
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT rp_plan_verfahrensmerkmale_verfahrensmerkmale_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".so_plan_verfahrensmerkmale_verfahrensmerkmale
			(
				ogc_fid serial NOT NULL,
				occurrence integer NOT NULL,
				parent_pkid character varying NOT NULL,
				child_pkid character varying NOT NULL,
				CONSTRAINT so_plan_verfahrensmerkmale_verfahrensmerkmale_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".xp_spezexternereferenz
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				georefurl character varying,
				georefmimetype_codespace character varying,
				georefmimetype character varying,
				art character varying,
				informationssystemurl character varying,
				referenzname character varying,
				referenzurl character varying,
				referenzmimetype_codespace character varying,
				referenzmimetype character varying,
				beschreibung character varying,
				datum date,
				typ character varying NOT NULL,
				CONSTRAINT xp_spezexternereferenz_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".xp_spezexternereferenzauslegung
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				georefurl character varying,
				georefmimetype_codespace character varying,
				georefmimetype character varying,
				art character varying,
				informationssystemurl character varying,
				referenzname character varying,
				referenzurl character varying,
				referenzmimetype_codespace character varying,
				referenzmimetype character varying,
				beschreibung character varying,
				datum date,
				typ character varying NOT NULL,
				nurzurauslegung boolean,
				CONSTRAINT xp_spezexternereferenzauslegung_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_externereferenz
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				xp_spezexternereferenz_pkid character varying,
				CONSTRAINT bp_plan_externereferenz_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_externereferenz
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				xp_spezexternereferenz_pkid character varying,
				CONSTRAINT fp_plan_externereferenz_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_externereferenz
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				xp_spezexternereferenz_pkid character varying,
				CONSTRAINT rp_plan_externereferenz_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".so_plan_externereferenz
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				xp_spezexternereferenz_pkid character varying,
				CONSTRAINT so_plan_externereferenz_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".xp_plangeber
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				name character varying NOT NULL,
				kennziffer character varying,
				CONSTRAINT xp_plangeber_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".xp_gemeinde
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				ags character varying,
				rs character varying,
				gemeindename character varying,
				ortsteilname character varying,
				CONSTRAINT xp_gemeinde_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_gemeinde
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				xp_gemeinde_pkid character varying,
				CONSTRAINT bp_plan_gemeinde_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_gemeinde
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				xp_gemeinde_pkid character varying,
				CONSTRAINT fp_plan_gemeinde_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_auslegungsenddatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT bp_plan_auslegungsenddatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_auslegungsstartdatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT bp_plan_auslegungsstartdatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_traegerbeteiligungsenddatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT bp_plan_traegerbeteiligungsenddatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".bp_plan_traegerbeteiligungsstartdatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT bp_plan_traegerbeteiligungsstartdatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_auslegungsenddatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT fp_plan_auslegungsenddatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_auslegungsstartdatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT fp_plan_auslegungsstartdatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_traegerbeteiligungsenddatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT fp_plan_traegerbeteiligungsenddatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".fp_plan_traegerbeteiligungsstartdatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT fp_plan_traegerbeteiligungsstartdatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_auslegungsenddatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT rp_plan_auslegungsenddatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_auslegungsstartdatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT rp_plan_auslegungsstartdatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_traegerbeteiligungsenddatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT rp_plan_traegerbeteiligungsenddatum_pkey PRIMARY KEY (ogc_fid)
			)
			;

			CREATE TABLE " . $this->gmlas_schema . ".rp_plan_traegerbeteiligungsstartdatum
			(
				ogc_fid serial NOT NULL,
				ogr_pkid character varying NOT NULL,
				parent_id character varying NOT NULL,
				value date,
				CONSTRAINT rp_plan_traegerbeteiligungsstartdatum_pkey PRIMARY KEY (ogc_fid)
			)
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4,0);
	}

	/*
	 * Finds and inserts all regeln from a specific konvertierung into the xplankonverter.regeln table
	 */
	function insert_all_regeln_into_db($konvertierung_id, $stelle_id, $simplify_fachdaten_geom = null) {
		$geometry_type = ''; # Default
		$bereiche_ids = $this->get_all_bereiche_ids_of_konvertierung($konvertierung_id);
		# TODO Check for each regel if bereich = bereich, and enter it accordingly in rule
		#$bereich_gml_id = 'd48608a2-6f3c-11e8-8ca0-1f2b7a47118e'; #Placeholder
		$log = '<br>Loop over bereiche_ids: ' . print_r($bereiche_ids, true);

		$gmlas_feature_tables = $this->get_gmlas_feature_tables_for_regeln($this->gmlas_schema);
		$log .= '<br>classes: ' . print_r($gmlas_feature_tables, true);
		# Loop over relevant bereiche -> gmlas_feature_tables -> geom
		$bereich_index = 0;
		foreach ($bereiche_ids as $bereich_gml_id) {
			$log .= '<br>bereich_gml_id: -' . $bereich_gml_id . '-';
			# index is used for modifying regel by name
			$bereich_index++;
			foreach ($gmlas_feature_tables as $gmlas_feature_table) {
				$log .= '<br>handle gmlas_feature_table: ' . $gmlas_feature_table;
				if ($this->check_if_table_has_entries_for_bereich($this->gmlas_schema, $gmlas_feature_table, $bereich_gml_id)) {
					# Loop over all geom-types to get a rule for each
					# use only ST_Point, ST_MultiPoint, ST_LineString, ST_MultiLineString, ST_Polygon and ST_MultiPolygon
					$geometry_types = $this->get_geometry_types($gmlas_feature_table, $this->gmlas_schema);
					$log .= '<br>Geometrie-Types: ' . print_r($geometry_types, true);
					foreach ($geometry_types as $geometry_type) {
						$log .= '<br>handle geometry_type: ' . $geometry_type;
						$result = $this->get_gmlas_to_gml_regel($gmlas_feature_table, $bereich_gml_id, $geometry_type, $simplify_fachdaten_geom);
						$log .= $result['log'];
						$sql_regel = str_replace("'", "''", $result['sql']); # Replaces all single commas with 2x single commas to escape them in SQL
						# TODO: pk Hier vorher existierende Regeln der konvertierung des Bereiches löschen damit sie nicht mehrfach drin sind.
						$log .= '<br>gmlas_to_geml_regel: ' . $sql_regel;
						$this->insert_regel_into_db($gmlas_feature_table, $sql_regel, $geometry_type, $konvertierung_id, $stelle_id, $bereich_gml_id, $bereich_index);
					}
				}
			}
		}
		return $log;
	}

	/*
	 * Returns all bereiche of a specific konvertierung
	 * @param integer $konvertierung_id Die ID der Konvertierung für die die ids der Bereiche abgefragt werden.
	 * @return array Ein assoziatives Array mit dem Schlüssel gml_id welches die gml_ids der Bereiche enthält.
	 */
	function get_all_bereiche_ids_of_konvertierung($konvertierung_id) {
		# requests xp_bereich to work for all schemata
		$sql = "
			SELECT 
				gml_id
			FROM
				xplan_gml.xp_bereich
			WHERE
				konvertierung_id = " . $konvertierung_id . ";
			";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$bereiche = pg_fetch_all_columns($ret[1]);
		return $bereiche;
	}

	/**
	 * Returns the SQL of a specified regel for a specific class in a specific bereich with a specific geometry type
	 * @param string $gmlas_feature_table
	 * @param string $bereich_id
	 * @param string $geom_type
	 * @return string SQL der Regel die auf der gmlas_feature_table basiert
	 */
	function get_gmlas_to_gml_regel($gmlas_feature_table, $bereich_id, $geom_type,$simplify_fachdaten_geom = null) {
		#echo '<p>Erzeuge Regel SQL für gmlas feature table: ' . $gmlas_feature_table . ' in Bereich: ' . $bereich_id . ' für Geometrietyp: '. $geom_type, $simplify_fachdaten_geom = null;
		$log = '';
		$simplify_fachdaten_geom = floatval($simplify_fachdaten_geom);
		$gml_class = $gmlas_feature_table; # Is this always the case?
		$gmlas_attributes = $this->get_gmlas_attributes_with_content($this->gmlas_schema, $gmlas_feature_table);
		$log .= '<br>gmlas_attribute with content: <pre>' . print_r($gmlas_attributes, true) . '</pre>';
		$mappings = $this->get_gmlas_to_gml_mappings($gmlas_feature_table);

		$select_sql = [];
		$gml_attributes = [];

		# Loops through all gmlas_attributes, comparing them with the original column (and table)
		# If matches are found, the target attributes are taken and the associated regel is added to the SQL
		$log .= '<br>Loop through gmlas_attributes:';
		foreach ($gmlas_attributes AS $a) {
			$log .= '<br>gmlas_attribute: ' . $a;
			if(!in_array($a, array_column($mappings, 'o_column'))) {
				continue;
			}
			$log .= '<br>Loop through mappings:';
			foreach ($mappings as $mapping) {
				$log .= '<br>Mapping for o_column: ' . $mapping['o_column'];
				if (($mapping['o_column'] == $a) and ($mapping['o_table'] == $gml_class)) {
					$log .= '<br>gmlas_attribute is o_column and gml_class is o_table';
					# gehoertzubereich wird automatisiert bei der Konvertierung in die Regel eingearbeitet 
					# muss deswegen für Attribute nicht verwendet werden, ggf aber für WHERE filter
					if ($mapping['t_column'] == 'gehoertzubereich') {
						continue;
					}

					# fix for varying encountered gml-geometry types that are valid for xplanung (according to konformitaetsbedingungen-document) but can cause problems
					# e.g. in display, in export, for wms/wfs services or are not supported by the konverter
					if ($mapping['t_column'] == 'position') {
						$mapping['regel'] = 'gmlas.position';
						// Konvertiere curves to line
						if (
							($geom_type == 'ST_CurvePolygon') or
							($geom_type == 'ST_MultiSurface') or
							($geom_type == 'ST_CompoundCurve') or
							($geom_type == 'ST_MultiCurve')
						) {
							$mapping['regel'] = 'ST_CurveToLine(' . $mapping['regel'] . ')';
						}
						// Cast to multi-geometries (konverter-convention)
						else if(($geom_type == 'ST_Point') or
							($geom_type == 'ST_LineString') or
							($geom_type == 'ST_Polygon'))
						{
							$mapping['regel'] = 'ST_Multi(gmlas.position)';
						} else {
							$mapping['regel'] = 'gmlas.position';
						}

						// Simplify
						if ($simplify_fachdaten_geom AND $simplify_fachdaten_geom > 0) {
							$mapping['regel'] = 'ST_SimplifyPreserveTopology(' . $mapping['regel'] . ', ' . strval($simplify_fachdaten_geom) . ')';
						}
						$mapping['regel'] .= ' AS position';
					}

					$gml_attributes[] = $mapping['t_column'];
					$select_sql[] = $mapping['regel'];
				}
			}
		}
		$log .= '<br>GML-Attributes für die Regel: ' . implode(', ', $gml_attributes);
		$log .= '<br>Select part of regel SQL nach Anpassung der Geometrietypen: ' . $select_sql;

		// rules that representing the many to many relationship
		$many_to_many_attributes = $this->get_many_to_many_attributes();
		$i = 0;
		foreach ($many_to_many_attributes AS $many_to_many_attribute) {
			if ($regel = $this->get_gmlas_to_gml_regel_for_many_to_many_attributes($gml_class, $many_to_many_attribute, $i++)) {
				$gml_attributes[] = $many_to_many_attribute;
				$select_sql[] = $regel;
			}
		}

		// rules for datatype attributes
		$datatype_attributes = $this->get_datatype_attributes($this->gmlas_schema, $gml_class);
		foreach ($datatype_attributes AS $datatype_attribute) {
			if ($regel = $this->get_gmlas_to_gml_regel_for_datatype_attribute($this->gmlas_schema, $gml_class, $datatype_attribute)) {
				$gml_attributes[] = $datatype_attribute;
				$select_sql[] = $regel;
			}
		}

		// ToDo dont forget attributes from gmlas schema like the 1:n relations zusatzkontingent in bp_baugebietsteilflaeche

		// Add INSERT INTO and FROM
		// Filters only by relevant bereich (in case 2 rules target the same class with different bereich)
		$sql  = "INSERT INTO " . XPLANKONVERTER_CONTENT_SCHEMA . "." . $gml_class . " (" . implode(", ", $gml_attributes) . ")
			SELECT
				" . implode(", ", $select_sql) . "
			FROM
				" . $this->gmlas_schema . '.' . $gmlas_feature_table . " AS gmlas
			WHERE
				gmlas.gehoertzubereich_href ILIKE '%" . $bereich_id . "' AND
				ST_GeometryType(position) = '" . $geom_type . "'
		";
		$log .= '<br>SQL für Regel zur Konvertierung von gmlas table ' . $gmlas_feature_table . ' to gml class ' . $gml_class . ': '. $sql;
		return array(
			'success' => true,
			'sql' => $sql,
			'log' => $log
		);
	}

	/**
	 * Return an array with attributes that represent the many to many relation ship of xp_objekts
	 */
	function get_many_to_many_attributes() {
		return array("wirddargestelltdurch", "dientzurdarstellungvon", "reftextinhalt", "detailliertezweckbestimmung", "zweckbestimmung");
	}

	/**
	 * Function to get rule for gmlas to gml conversion for many to many $attribute of $gml_class in step $i
	 * currently it only considers the attributes:
	 * "wirddargestelltdurch", "dientzurdarstellungvon", "reftextinhalt", "detailliertezweckbestimmung", "zweckbestimmung"
	 * 
	 * It considers that many to many relation tables in gmlas schemas are not directly related to bereich
	 * How to find out if the table is a n:m relation or not?
	 * a) from uml schema and the multiplicity
	 * b) from n:m-relation-table-names, In gmlas relations can be identified by the extension of tablenames
	 * e.g.: bp_baugebietsteilflaeche hat extensions:
	 * - bp_baugebietsteilflaeche_dachgestaltung zeigt auf bp_dachgestaltung
	 * - bp_baugebietsteilflaeche_hoehenangabe_hoehenangabe zeigt auf hoehenangabe
	 *   hoehenangabe können alle XP_Objekte haben
	 * - bp_baugebietsteilflaeche_reftextinhalt zeigt immer auf bp_textabschnitt
	 *   reftextinhalt haben alle XP_objekte (BP_Objekt, FP_Objekt, SO_Objekt, etc.)
	 * - bp_baugebietsteilflaeche_wirddargestelltdurch zeigt auf z.B. xp_ppo, kann aber auch auf andere xp_p Tabellen verweisen.
	 *   Könnte man über die href-Attribute, z.B. href_xp_ppo_pkid herausbekommen
	 * - Dazu kommt, dass gmlas auch noch Kürzungen vornimmt wenn die Namen zu lang werden, z.B.
	 *   bp_verkehrsflaechebesondererzweckbestimmung und die dazugehörige relation Tabelle
	 *   bp_verkehrsflaechebesondererzweckbestimmu_wirddargestelltdurch
	 * Die Frage ist nach welchen Gesichtspunkten gmlas diese Namen vergibt und wie man darauf schließt auf welche und mit welchen Attributen die
	 * Verknüpfungen zu den Tabellen hergestellt werden können. Sicher hängt das vom Schema ab und von den Typen. Das muss man sich ansehen um ein
	 * Muster zu erkennen und die Sache generisch in Regeln umsetzen zu können.
	 * -----
	 * hier bisher behandelt
	 * attributes of normalized gmlas tables, e.g. praesentationsobjekte '_dientzurdarstellungvon', '_wirddargestelltdurch'
	 * TODO generically read all normalized tables
	 * Hier die verschiedenen Fälle von Seiten der Modellierung analysieren und unterschiedliche Regelklassen einführen.
	 * Jede Regelklasse behandelt dann die methode get_select_sql anders.
	 * In einem ersten Schritt werden alle Attribute der xplan_gml-Klasse ermittelt und deren Typ (Klasse) und dann für alle
	 * die get_select_sql aufgerufen. Die sind dann entweder generisch oder aus der gmlas_to_gml mapping_table
	 * zweckbestimmung e.g. for fp_generischesobjekt_zweckbestimmung
	 * @param string $gml_class Name of the class to convert
	 * @param string $attribute Name of attribute to convert
	 * @param integer $i Number of rule in table conversion
	 * @return string The rule for conversion of column in gml_class or empty string if no rule exists
	 */
	function get_gmlas_to_gml_regel_for_many_to_many_attributes($gml_class, $attribute, $i) {
		$regel = '';
		$class_and_attr = $gml_class . "_" . $attribute;
		// class is shortened by ogr if class+attribute are longer than 60 characters, currently only known for the following exception)
		if ($gml_class == "bp_verkehrsflaechebesondererzweckbestimmung" && $attribute == "wirddargestelltdurch") {
			$class_and_attr = "bp_verkehrsflaecbesondererzweckbestimmu_wirddargestelltdurch";
		}

		# check if table exists
		$sql_checkexists_norm_table = "
			SELECT 
				EXISTS (
					SELECT FROM information_schema.tables 
					WHERE table_schema = '" . $this->gmlas_schema . "'
					AND table_name = '" . $class_and_attr . "'
				)
		";
		$ret = $this->pgdatabase->execSQL($sql_checkexists_norm_table, 4, 0);
		$result = pg_fetch_row($ret[1]);
		if ($result[0] === 't') {
			# single ' escaped later
			$norm_1 = "norm_table_" . $i;
			if ($attribute == "wirddargestelltdurch" OR $attribute == "dientzurdarstellungvon" OR $attribute == "reftextinhalt") {
				$regel = "(
					SELECT
						array_agg(replace(lower(href), '#gml_', ''))
					FROM
						" . $this->gmlas_schema . "." . $class_and_attr . " ". $norm_1 . "
					WHERE
						gmlas.id = " .$norm_1 . ".parent_id
				) AS " . $attribute;
			}
			if ($attribute == "detailliertezweckbestimmung" OR $attribute == "zweckbestimmung") {
				$special_datatype = "";
				$sql = "
					SELECT
						udt_name
					FROM
						information_schema.columns
					WHERE
						table_schema = 'xplan_gml'
					AND
						table_name = '" . $gml_class . "'
					AND
						column_name = '" . $attribute . "'
					LIMIT 1
				";
				$ret = $this->pgdatabase->execSQL($sql, 4, 0);
				$result = pg_fetch_row($ret[1]);
				$special_datatype = $result[0];
				if (substr($special_datatype, 0, 1) == "_") {
					// remove leading underscore (for arrays) and add [] brackets at the end
					$special_datatype = ltrim($special_datatype, "_") . "[]";
				}
				if ($special_datatype != "") {
					$norm_2 = "norm_table_" . $i . "_" . $i;
					$norm_3 = "norm_table_" . $i . "_" . $i . "_" . $i;
					$norm_4 = "norm_table_" . $i . "_" . $i . "_" . $i . "_" . $i;
					$regel = "
						CASE
							WHEN (
								SELECT
									TRUE
								FROM
									" . $this->gmlas_schema . "." . $class_and_attr . " " . $norm_2 . "
								WHERE
									" . $norm_2 . ".parent_id = gmlas.id LIMIT 1
							)
							THEN ARRAY[
								(
									(
										SELECT DISTINCT
											codespace
										FROM
											" . $this->gmlas_schema . "." . $class_and_attr . " " . $norm_3 . "
										WHERE
											gmlas.id = " . $norm_3 . ".parent_id
										LIMIT 1
									),
									(
										SELECT
											string_agg(value,',')
										FROM
											" . $this->gmlas_schema . "." . $class_and_attr . " " . $norm_4 . "
										WHERE
											gmlas.id = " . $norm_4 . ".parent_id
									),
									NULL
								)
							]::xplan_gml." . $special_datatype . "[]
							ELSE NULL
						END AS " . $attribute . "
					";
				}
			}
		}
		return $regel;
	}

	/**
	 * Function return the attributes of $gml_class that have a special datatype
	 * It returns only rules for attributes that fullfill the following conditions
	 * - Attribute belongs to the $gml_class table in xplan_gml schema
	 * - Attribute has a stereotype of datatype in xplan_uml.uml_class
	 * - $gml_class table exists in $gmlas_schema
	 * - A table with the name of attributes datatype exists in $gmlas_schema
	 * @param string $gmlas_schema The name of the gmlas_schema.
	 * @param string $gml_class The name of gml_class.
	 * @return array{ name: string, datatype: string, is_array: boolean } $attribute An associative array with attribute name and datatype.
	 */
	function get_datatype_attributes($gmlas_schema, $gml_class) {
		$sql = "
			SELECT
				gml_cols.column_name AS name,
				LOWER(u.name) AS datatype,
				SUBSTRING(gml_cols.udt_name, 1, 1) = '_' AS is_array
			FROM
				information_schema.columns gml_cols JOIN
				xplan_uml.uml_classes u ON gml_cols.udt_name LIKE '%' || LOWER(u.name) JOIN
				xplan_uml.stereotypes s ON u.stereotype_id = s.xmi_id JOIN
				information_schema.tables gml_tabs ON gml_cols.table_name = gml_tabs.table_name JOIN
				information_schema.tables gmlas_tabs ON gml_cols.udt_name = '_' || gmlas_tabs.table_name
			WHERE
				gml_cols.table_schema = 'xplan_gml' AND
				gml_cols.table_name = '" . $gml_class . "' AND
				s.name = 'DataType' AND
				gml_tabs.table_schema = '" . $gmlas_schema . "' AND
				gmlas_tabs.table_schema = '" . $gmlas_schema . "'
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$datatype_attributes = pg_fetch_all($ret[1], PGSQL_ASSOC);
	}

	/**
	 * Function return the rule to convert the datatype attribute of gml_class from gmlas to gml schema
	 * @param string $gml_class The name of the gml_class
	 * @param array{ name: string, datatype: string, is_array_boolean } $attribute An associative array with attribute name and datatype.
	 * @return string $regel The rule to convert from gmlas to gml table for that attribute.
	 */
	function get_gmlas_to_gml_regel_for_datatype_attribute($gmlas_schema, $gml_class, $attribute) {
		$regel = '';
		// Check if the attribte is in gmlas table or in a relation table
		// The filter of column_name in gmlas table consists of:
		// - The first 6 letters of the attribute name,
		// - the first 6 letters of the datatype name and
		// - the last 6 letters of the datatype name, to fit long attribute names that make gmlas eg:
		// laermkontingent type bp_emissionskontingentlaerm must fit laermkonting_bp_emissionskonlaerm_bp_emissionskontlaerm_pkid and
		// laermkontingentgebiet type bp_emissionskontingentlaermgebiet mus fit laermkonti_bp_emissionsklaerm_bp_emissionskolaermgebiet_pkid
		//

		if ($attribute['is_array'] == 'f') {
			// only non-array types can be referenced directly in gmlas table with pkid to the datatype table
			$sql = "
				SELECT
					c.column_name
				FROM
					information_schema.columns c
				WHERE
					c.table_schema = '" . $this->gmlas_schema . "' AND
					c.table_name = '" . $gml_class . "' AND
					c.column_name LIKE
						SUBSTRING('" . $attribute['name'] . "', 1, 6) ||
						'%_' ||
						SUBSTRING('" . $attribute['datatype'] . "', 1, 6) ||
						'%_%' ||
						SUBSTRING('" . $attribute['datatype'] . "', LENGTH('" . $attribute['datatype'] . "') - 6, 7) ||
						'_pkid'
			";
			$ret = $this->pgdatabase->execSQL($sql, 4, 0);
			if (pg_num_rows($ret[1]) > 0) {
				// attribute is direct related with the datatype column
				$rs = pg_fetch_assoc($ret[1]);
				$regel = "(
					SELECT * FROM " . $gmlas_schema . "." . $attribute['datatype'] . " WHERE id = gmlas." . $rs['column_name'] . "
				) AS " . $attribute['name'];
			}
		}
		else {
			// Array types are referenced with a relation table e.g.: xplan_gmlas_5007.bp_baugebietsteilflaeche_dachgestaltung
			// Try to find the relation table
			$sql = "
				SELECT
					t.table_name
				FROM
					information_schema.tables t
				WHERE
					t.table_schema = '" . $this->gmlas_schema . "' AND
					t.table_name LIKE '" . $gml_class . "_" . $attribute['name'] . "%'
			";
			$ret = $this->pgdatabase->execSQL($sql, 4, 0);
			if (pg_num_rows($ret[1]) > 0) {
				$rs = pg_fetch_assoc($ret[1]);
				// parent_id
				/* ToDo: Nur Werte in row aufnehmen die in Typ xplan_gml.bp_dachgestaltung vorkommen und deren Typen setzen. (rekursive, weil da auch wieder zusammengesetzte typen vorkommen können.) 
				SELECT
					--  row(data_tab.*)::xplan_gml.bp_dachgestaltung
  				data_tab.*
				FROM
  				xplan_gmlas_5007.bp_baugebietsteilflaeche_dachgestaltung rel_tab JOIN
  				xplan_gmlas_5007.bp_dachgestaltung data_tab ON rel_tab.bp_dachgestaltung_pkid = data_tab.ogr_pkid
				*/
				$regel = "(
					SELECT array_agg((SELECT * FROM " . $this->gmlas_schema . "." . $rs['table_name'] . " AS rel_tab JOIN " . $this->gmlas_schema . "." . $attribute['datatype'] . " AS data_tab ON rel_tab." . $attribute['datatype'] . "_pkid = data_tab.ogr_pkid WHERE rel_tab.parent_id::text = gmlas.ogc_fid::text))
				)";
			}

			// Aggregate to an array if type is an array
		}

		return $regel;
	}

	/**
	 * Returns all attributes for table $table_name in schema $schema_name
	 * that have not null values
	 * Consider that attributes in gmlas schemas also can be in relation tables.
	 * 
	 * @param string $schema_name
	 * @param string $table_name
	 */
	function get_gmlas_attributes_with_content($schema_name, $table_name) {
		$sql = "
			SELECT
				column_name
			FROM
				information_schema.columns
			WHERE
				table_schema = '" . $schema_name . "' AND
				table_name = '" . $table_name . "'
			ORDER BY ordinal_position;
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$all_attributes = pg_fetch_all_columns($ret[1]);

		# Returns an array of t or f values for all attributes for a table_name
		$sql = "
			SELECT
				" . implode(", ", array_map(
					function($attribut) use ($schema_name, $table_name) {
						return "EXISTS( SELECT " . $attribut . " FROM " . $schema_name . "." . $table_name . " WHERE " . $attribut . " IS NOT NULL ) AS " . $attribut;
					},
					$all_attributes
				)) . "
		";
		#echo '<br>' . $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$attributes_has_content = pg_fetch_array($ret[1]);

		# Compares both arrays and writes all attributes that have content in a new array
		$attributes_with_content = [];
		for ($i = 0; $i < count($all_attributes); $i++) {
			if ($attributes_has_content[$i] == 't') {
				$attributes_with_content[] = $all_attributes[$i];
			}
		}
		return $attributes_with_content;
	}

	/**
	 * Returns all none geometry attributes for table $table_name in schema $schema_name
	 */
	function get_none_geom_attributes_for_class_in_schema($schema_name, $table_name) {
		$sql = "
			SELECT
				column_name
			FROM
				information_schema.columns
			WHERE
				table_schema = '" . $schema_name . "' AND
				table_name = '" . $table_name . "' AND
				udt_name NOT LIKE 'geometry'
			ORDER BY ordinal_position;
		";
		#echo '<br>' . $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$attributes = pg_fetch_all_columns($ret[1]);
		return $attributes;
	}

	/*
	* Returns an array of all geometries used within a class in a schema.
	*/
	function get_geometry_types($class, $schema) {
		# for xplan-objects that are specializations of xp_object, the geometry column is always position
		# would not work on objects that are e.g. specializations of xp_plan or xp_bereich or non xplan-objects
		$geom_column = 'position';
		$sql = "
			SELECT DISTINCT
				ST_GeometryType(" . $geom_column . ")
			FROM
			 	" . $schema . "." . $class . "
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$geom_types = pg_fetch_all_columns($ret[1]);
		return $geom_types;
	}

	/**
	* Returns the attribute mappings between gdal xplan_gmlas and konverter xplan_gml schemas for $class
	*/
	function get_gmlas_to_gml_mappings($class) {
		$sql = "
			SELECT
				o_table,
				o_column,
				t_table,
				t_column,
				t_data_type,
				regel
			FROM
				xplankonverter.mappingtable_gmlas_to_gml
			WHERE
				t_table = '" . $class . "'
		";
		// echo '<br>SQL zur Abfrage der gmlas_to_gml regeln: ' . $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_all($ret[1]);
		return $result;
	}

	/*
	* Inserts a specific regel into the xplankonverter.regeln schema
	*/
	function insert_regel_into_db($class, $regel, $geometry_type, $konvertierung_id, $stelle_id, $bereich_gml_id, $bereich_index) {
		$uml_class = $this->get_uml_classname($class);
		if (in_array($geometry_type, array('ST_Point', 'ST_MultiPoint'))) {
			$enum_geometrie_typ = $name = 'Punkte';
		}
		if (in_array($geometry_type, array('ST_LineString', 'ST_MultiLineString', 'ST_CompoundCurve'))) {
			$enum_geometrie_typ = $name = 'Linien';
		}
		if (in_array($geometry_type, array('ST_Polygon', 'ST_MultiPolygon', 'ST_CurvePolygon', 'ST_MultiSurface'))) {
			$enum_geometrie_typ = 'Flächen';
			$name = 'Flaechen';
		}

		$sql  = "
			INSERT INTO xplankonverter.regeln (
				class_name,
				factory,
				sql,
				geometrietyp,
				name,
				beschreibung,
				konvertierung_id,
				stelle_id,
				bereich_gml_id
			)
			SELECT
				'" . $uml_class . "' AS class_name,
				'sql'::xplankonverter.enum_factory AS factory,
				'" . $regel . "' AS sql,
				'" . $enum_geometrie_typ . "'::xplankonverter.enum_geometrie_typ AS geometrietyp,
				'" . $uml_class . "_" . $bereich_index . "_" .$name . "' AS name,
				'regel created automatically from gmlas extraction' AS beschreibung,
				" . $konvertierung_id . " AS konvertierung_id,
				" . $stelle_id . " AS stelle_id,
				'" . $bereich_gml_id . "' AS bereich_gml_id
		";
		#echo 'INSERT regeln: ' . $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
	}

	/*
	* Returns UML capitalization of classname
	*/
	function get_uml_classname($class) {
		$sql = "
			SELECT DISTINCT
				name
			FROM
				xplan_uml.uml_classes
			WHERE
				LOWER(name) = '" . $class . "'
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$uml_class = pg_fetch_row($ret[1]);
		return $uml_class[0];
	}

	/**
	 * Returns a list of feature tables that can be associated with rules to convert
	 * gmlas data to xplan_gml schema
	 * Feature tables are only those that are in xplan_uml schema defined as stereotype FeatureType.
	 * @param string $schema
	 * @return string[] The list of gmlas feature tables
	 */
	function get_gmlas_feature_tables_for_regeln($schema) {
		# escape underscore e.g. for fp_zentralerversorgungsbereich
		$sql = "
			SELECT
				i.table_name
			FROM
				information_schema.tables i JOIN
				xplan_uml.uml_classes u ON (i.table_name = LOWER(u.name)) JOIN
				xplan_uml.stereotypes s ON (u.stereotype_id = s.xmi_id)
			WHERE
				i.table_schema = '" . $schema . "' AND
				s.name = 'FeatureType' AND
				(
					i.table_name IN('xp_ppo','xp_lpo','xp_fpo','xp_tpo','xp_pto','xp_lto') OR
					i.table_name NOT LIKE 'xp_%'
				) AND
				i.table_name NOT LIKE '%\_plan' AND
				i.table_name NOT LIKE '%\_bereich' AND
				i.table_name NOT LIKE '%\_textabschnitt'
			ORDER BY
				i.table_name;
		";
		#echo 'Abfrage der Classes for regeln: ' . $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$tablenames = pg_fetch_all_columns($ret[1]);
		return $tablenames;
	}

	/**
	 * Returns TRUE or FALSE depending on whether a table has at least 1 row
	 * @param string $schema
	 * @param string $table
	 * @param string $bereich_gml_id
	 * @return boolean
	 */
	function check_if_table_has_entries_for_bereich($schema, $table, $bereich_gml_id) {
		$sql  = "
			SELECT
				count(*) AS num_entries
			FROM
				" . $schema . "." . $table . "
			WHERE
				gehoertzubereich_href ILIKE '%" . $bereich_gml_id . "'
			LIMIT 1
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		// echo '<br>feature has ' . $result['num_entries'] . ' entries.';
		return $result['num_entries'] > 0;
	}
	
	/**
	* autocompletes allgartderbaulnutzung and besondereartderbaulnutzung
	* for fp_bebauungsflaeche with attributes
	* desired feature for QGIS-Plugin-Support (PlanDigital)
	* see Konformitaetsbedingugng 5.3.1.1 and 5.3.1.2 (XPlan 5.4)
	* @param string $schema
	*/
	function autocomplete_fp_bebauungsflaeche_attributes($schema) {
		// table might not exist in all loaded source data
		if($this->check_if_table_exists_in_schema('fp_bebauungsflaeche', $schema)) {
			//besondereartderbaulnutzung should be filled before allgartderbaulnutzung in separate query, as it might require filled attributes
			$sql = "UPDATE
								" . $schema . ".fp_bebauungsflaeche
							SET
								besondereartderbaulnutzung = 
								CASE 
									WHEN
										besondereartderbaulnutzung IS NULL AND sondernutzung IN ('1000','1100','1200','1300','1400')
									THEN
										'2000'
									WHEN
										besondereartderbaulnutzung IS NULL AND sondernutzung IN ('1500','1600','16000','16001','16002','1700','1800',
											'1900','2000','2100','2200','2300','23000','2400','2500','2600','2700','2720','2800','2900','9999')
									THEN
										'2100'
									ELSE
										besondereartderbaulnutzung
							END;";
			$ret = $this->pgdatabase->execSQL($sql, 4, 0);
			
			$sql = "UPDATE
								" . $schema . ".fp_bebauungsflaeche
							SET
								allgartderbaulnutzung = 
								CASE 
									WHEN
										allgartderbaulnutzung IS NULL AND besondereartderbaulnutzung IN ('1000','1100','1200','1300')
									THEN
										'1000'
									WHEN
										allgartderbaulnutzung IS NULL AND besondereartderbaulnutzung IN ('1400','1450','1500','1550','1600')
									THEN
										'2000'
									WHEN
										allgartderbaulnutzung IS NULL AND besondereartderbaulnutzung IN ('1700','1800')
									THEN
										'3000'
									WHEN
										allgartderbaulnutzung IS NULL AND besondereartderbaulnutzung IN ('2000','2100','3000')
									THEN
										'4000'
									ELSE
										allgartderbaulnutzung
							END;";
			$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		}
	}
}
?>
