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
	}

	/*
	* Extracts Plan and sends vars to form
	*/
	function extract_gml_class($classname) {
		global $GUI;
		$this->input_epsg = $this->get_source_srid();
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
		$rect = ms_newRectObj();

		# iterate over all attributes as formvars
		foreach ($formdata as $r_key => $r_value) {
			if ($r_key == 'externereferenz') {
				# TODO Das ist die Stelle wo man prüfen kann ob die hochgeladenen Dateien mit den referenzurl übereinstimmen
				$referenzen = json_decode($r_value);
				if (count($referenzen) > 0) {
					$document_url = $GUI->user->rolle->getLayer($GUI->formvars['chosen_layer_id'])[0]['document_url'];
					foreach ($referenzen AS $referenz) {
						$path_parts = pathinfo(basename($referenz->referenzurl));
						$referenz->referenzurl =  $document_url . $path_parts['filename'] . '-' . $GUI->formvars['random_number'] . '.' . $path_parts['extension']; 
					}
					$r_value = str_replace('\/', '/', json_encode($referenzen));
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

	function get_source_srid() {
		# Parse the EPSG of the file
		# According to Konformitaetsbedingung 2.1.3.1 there needs to be a standard gml:Envelope in each valid xplan-file.
		# A fallback value will be provided as conformity currently cannot be validated at the moment of loading (schema could be validated with xsd-validator)
		
		# NOTE:
		# Konformitaetsbedingung 2.13.1 currently also still allows a "kurzbezeichnung" akin to ALKIS, e.g "urn:adv:crs:DE_DHDN_3GK3", where DE_DHDN_3GK3 is Gauss-Krueger Streifen 3
		# This method of CRS will likely become obsolete in XPlanung 6.0, and is also currently not supported with this parser (default value would be used)
		$epsg = $this->input_epsg;
		$lines = file($this->gml_location);
		foreach ($lines as $lineNumber => $line) {
			if(strpos($line, 'Envelope') === false) {
				continue;
			}
			# needs to check for both single and double quotes as both are permitted by XML spec
			if (preg_match('/srsName="([^"]+)"/', $line, $matched_epsg_str)) {
				break; #found it
			}
			if (preg_match('/srsName=\'([^"]+)\'/', $line, $matched_epsg_str)) {
				break; #found it
			}
			if (preg_match('/srsname=\'([^"]+)\'/', $line, $matched_epsg_str)) {
				break; #found it
			}
			#echo 'could not find XPlan srsName within double quotes. checking single quotes:<br>';
		}

		# echo $matched_epsg_str[1] . '<br>';

		if(isset($matched_epsg_str[1])) {
			// e.g. for EPSG:25832
			$epsg_elements_array = explode(':',$matched_epsg_str[1]);
			$matched_epsg = array_values(array_slice($epsg_elements_array, -1))[0];
			if(is_numeric($matched_epsg)) {
				# TODO should still be checked if it is a valid EPSG within the konverter or POSTGIS limit, e.g. through a check against the POSTGIS EPSG info
				$epsg = $matched_epsg;
			}
		}
		else {
			$msg  = 'Konnte das SRS des XPlan-Envelope nicht innerhalb von Doppelten oder einfachen Anführungszeichen finden'; 
			$msg .= 'Bitte stellen Sie sicher, das ein Envelope-Element nach XPlan-Konformitaetsbedingung 2.1.3.1 vorhanden ist, z.B. wie folgt:<br>';
			$msg .= '<pre>' . htmlentities('<gml:boundedBy><gml:Envelope srsName="EPSG:25833">...</gml:Envelope></gml:boundedBy>') . '</pre>...<br>';
			$msg .= 'Ein Fallback SRS mit EPSG ' . $this->fallback_epsg . ' wird benutzt.<br>';
			$GUI->add_message('warning', $msg);
			$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
			$GUI->output();
		}
		// fallback value
		return $epsg;
	}
	/* 
	* parses current xplan-version from file
	* does not check whether current version is supported
	*/
	function get_xsd_version() {
		global $GUI;
		//will check if current version is supported or not
		$version = '5.1'; //default
		$lines = file($this->gml_location);
		$matched_ns_str;
		foreach ($lines as $lineNumber => $line) {
			if(strpos($line, 'XPlanAuszug') === false) {
				continue;
			}
			# needs to check for both single and double quotes as both are permitted by XML spec
			if (preg_match('/xplan="([^"]+)"/', $line, $matched_ns_str)) {
				break; #found it
			}
			else if (preg_match('/xplan=\'([^"]+)\'/', $line, $matched_ns_str)) {
				break; #found it
			}
			else if (preg_match('/xplan=\'([^"]+)\'/', $line, $matched_ns_str)) {
				break; #found it
			}
			else {
				$msg  = 'Konnte XPlanAuszug oder Namespace xplan nicht in Datei finden.<br>';
				$msg .= 'Überprüfen Sie die Validität der XPlanung-Datei';
				#$GUI->add_message('warning', $msg);
				#$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
				#$GUI->output();
				#echo 'Could not find XPlanAuszug. XPlan-file is not valid';
			}
			#echo 'could not find XPlan srsName within double quotes. checking single quotes:<br>';
		}
		#echo $matched_ns_str[1] . '<br>';

		if (preg_match('/5\/1/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.1';
		} else if (preg_match('/5.1/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.1';
		} else if (preg_match('/5\/2/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.2';
		} else if (preg_match('/5.2/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.2';
		} else if (preg_match('/5\/0/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.0';
		} else if (preg_match('/5.0/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.0';
		} else if (preg_match('/5\/3/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.3';
		} else if (preg_match('/5.3/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.3';
		} else if (preg_match('/5\/4/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.4';
		} else if (preg_match('/5.4/', $matched_ns_str[1], $matched_version_str)) {
			$version = '5.4';
		} else {
			$msg  = 'Die XPlan-GML Version der Datei kann nicht identifiziert werden.<br>';
			$msg .= 'Bitte überprüfen Sie, ob die XPlan-Version valide ist und der Namespace in Version 5.1, 5.2, 5.3 oder 5.4 liegt<br>';
			$msg .= 'Es wird eine Fallback-Version 5.1 verwendet.<br>';
			#$GUI->add_message('warning', $msg);
			#$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
			#$GUI->output();
			#echo 'Could not identify a valid XPlan version.<br>Please make sure that the XPlan-version is valid an in namespace version 5.2 or 5.1.<br>A fallback version of ' . $version . ' will be used.<br>';
		}
		#echo 'version:' . $version;
		return $version;
	}

	/*
	* Returns TRUE OR FALSE, depending on whether the schema exists
	*/
	function check_if_schema_exists($schema) {
		$sql = "
			SELECT
				EXISTS(
					SELECT
						1
					FROM
						information_schema.schemata
					WHERE 
						schema_name = '" . $schema . "'
					AND
						catalog_name = '" . POSTGRES_DBNAME . "'
				)
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_row($ret[1]);
		return ($result[0] === 't');
	}
	
	/*
	* Returns TRUE OR FALSE, depending on whether the schema exists
	*/
	function check_if_table_exists_in_schema($table,$schema) {
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
		$param_2                = urlencode('GMLAS:' . $this->gml_location . ' -oo REMOVE_UNUSED_LAYERS=YES -oo XSD=' . $this->xsd_location); 
		
		$url = $gdal_container_connect . $param_1 . $connection_string . $param_2;	
		#echo 'url: ' . $url . '<br><br>';

		$ch = curl_init();
		#$url = curl_escape($ch, $url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,300);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		
		#echo empty($output) ? "Nothing returned from ogr2ogr curl request" : $output;
		return $output;
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

	/*
	* Returns an array of all tables in a specified schema according to the information_schema
	*/
	function get_all_tables_in_schema($schema) {
		$sql = "
			SELECT
				table_name
			FROM
				information_schema.tables
			WHERE
				table_schema = '" .$schema . "'
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_all($ret[1]);
		//$result = (!empty($result)) ? array_column($result, 'table_name') : array();
		return $result;
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

	/*
	* Reverse vertex order of specific geometry (GML CCW Lefthand, Shape and DB CW Righthand)
	*/
	function revert_vertex_order_for_table_with_geom_column_in_schema($table, $geom_column, $schema) {
		$sql = "
			UPDATE " . 
				$schema . "." . $table . " 
			SET " .
				$geom_column . " = ST_Reverse(" . $geom_column . ") 
		;";
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
				st_assvg(st_transform(ST_ForceRHR(gmlas.raeumlichergeltungsbereich),". $this->epsg ."), 0, 8) AS newpath,
				st_astext(st_transform(ST_ForceRHR(gmlas.raeumlichergeltungsbereich),". $this->epsg .")) AS newpathwkt,
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
				to_json((gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml.bp_sonstplanart) AS sonstplanart,
				gmlas.gruenordnungsplan AS gruenordnungsplan,
				to_json((pg.name, pg.kennziffer)::xplan_gml.xp_plangeber) AS plangeber,
				array_to_json(ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[]) AS auslegungsstartdatum,
				array_to_json(ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsstartdatum,
				to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY') AS aenderungenbisdatum,
				to_json((gmlas.status_codespace, gmlas.status, NULL)::xplan_gml.bp_status) AS status,
				array_to_json(ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsenddatum,
				array_to_json(gmlas.planart) AS planart,
				gmlas.erschliessungsvertrag AS erschliessungsvertrag
			FROM
				" . $this->gmlas_schema . ".bp_plan gmlas LEFT JOIN
				" . $this->gmlas_schema . ".bp_plan_gemeinde gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".xp_gemeinde g ON gemeindelink.xp_gemeinde_pkid = g.ogr_pkid LEFT JOIN
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
				gmlas.id ='" . $gml_id . "'
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
				st_assvg(st_transform(gmlas.raeumlichergeltungsbereich,". $this->epsg ."), 0, 8) AS newpath,
				st_astext(st_transform(ST_ForceRHR(gmlas.raeumlichergeltungsbereich),". $this->epsg .")) AS newpathwkt,
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
				to_json((gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml.fp_sonstplanart) AS sonstplanart,
				gmlas.planart::xplan_gml.fp_planart AS planart,
				to_char(gmlas.planbeschlussdatum, 'DD.MM.YYYY') AS planbeschlussdatum,
				to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY') AS aufstellungsbeschlussdatum
			FROM
				" . $this->gmlas_schema . ".fp_plan gmlas LEFT JOIN
				" . $this->gmlas_schema . ".fp_plan_gemeinde gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
				" . $this->gmlas_schema . ".xp_gemeinde g ON gemeindelink.xp_gemeinde_pkid = g.ogr_pkid LEFT JOIN
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
				gmlas.id ='" . $gml_id . "'
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
				st_assvg(st_transform(gmlas.raeumlichergeltungsbereich,". $this->epsg ."), 0, 8) AS newpath,
				st_astext(st_transform(ST_ForceRHR(gmlas.raeumlichergeltungsbereich),". $this->epsg .")) AS newpathwkt,
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
				to_json((gmlas.planart_codespace, gmlas.planart, NULL)::xplan_gml.so_planart) AS planart
				/*array_to_json(ARRAY[(g.ags,g.rs,g.gemeindename,g.ortsteilname)]::xplan_gml.xp_gemeinde[]) AS gemeinde*/
			FROM
				" . $this->gmlas_schema . ".so_plan gmlas LEFT JOIN
				/*" . $this->gmlas_schema . ".so_plan_gemeinde gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN*/
				/*" . $this->gmlas_schema . ".xp_gemeinde g ON gemeindelink.xp_gemeinde_pkid = g.ogr_pkid LEFT JOIN*/
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
				gmlas.id ='" . $gml_id . "'
			;";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}

	/*
	* Returns an associative array to fill the bp_plan form
	*/
	function fill_form_rp_plan($gml_id) {
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
				st_assvg(st_transform(gmlas.raeumlichergeltungsbereich,". $this->epsg ."), 0, 8) AS newpath,
				st_astext(st_transform(ST_ForceRHR(gmlas.raeumlichergeltungsbereich),". $this->epsg .")) AS newpathwkt,
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
				to_json((gmlas.sonstplanart_codespace, gmlas.sonstplanart)::xplan_gml.rp_sonstplanart) AS sonstplanart,
				array_to_json(ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[]) AS auslegungsstartdatum,
				array_to_json(ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsstartdatum,
				to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY') AS aenderungenbisdatum,
				to_json((gmlas.status_codespace, gmlas.status)::xplan_gml.rp_status) AS status,
				array_to_json(ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[]) AS traegerbeteiligungsenddatum,
				gmlas.planart::xplan_gml.rp_art AS planart
			FROM
				" . $this->gmlas_schema . ".rp_plan gmlas LEFT JOIN
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
				trim(leading 'Gml_' FROM (trim(leading 'GML_' FROM gmlas.id)))::text::uuid AS gml_id,
				gmlas.nummer AS nummer,
				gmlas.xplan_name AS name,
				gmlas.bedeutung::xplan_gml.xp_bedeutungenbereich AS bedeutung,
				gmlas.detailliertebedeutung AS detailliertebedeutung,
				gmlas.erstellungsmassstab AS erstellungsmassstab,
				ST_Multi(ST_ForceRHR(st_transform(gmlas.geltungsbereich,". $this->epsg ."))) AS geltungsbereich,
				" . $user_id . " AS user_id,
				" . $konvertierung_id . " AS konvertierung_id,
				trim(leading '#gml_' FROM lower(gmlas.rasterbasis_href)) AS rasterbasis,";

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
		// because uuid (e.g. in gml_id of the associated plan) is always lowercase when cast to text
		// will take first plan encountered in gmlas-schema if bereich id is not set (or could not be read by ogr)
		$sql .= "
				CASE
					WHEN gmlas.gehoertzuplan_href IS NOT NULL THEN trim(leading '#gml_' FROM lower(gmlas.gehoertzuplan_href))::uuid
					ELSE trim(leading '#gml_' FROM lower((SELECT DISTINCT id FROM " . $this->gmlas_schema . "." . substr($table,0,3) . "plan LIMIT 1)))::uuid
				END AS gehoertzuplan
			FROM
				" . $this->gmlas_schema . "." . $table . " gmlas
			;";
		# echo $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}

	/*
	* Inserts values of xplan_gmlas_... into xplan_gml textabschnitte tables, depending on the specific bereich (xp_textabschnitt, fp_textabschnitt etc.)
	*/
	function insert_into_textabschnitt($table, $konvertierung_id, $user_id) {
		# Based on XPlanung 5.0.1
		# currently no inverszu_baugebietsteilflaeche and nebenanlagenausschlussflaeche bp
		$prefix_arr = explode("_", $table, 2);
		$prefix = $prefix_arr[0];
		
		$all_tables_in_schema = $this->get_all_tables_in_schema($this->gmlas_schema);
		$all_tables_with_reftextinhalt_suffix = [];
		#print_r($all_tables_in_schema);
		foreach($all_tables_in_schema as $table_in_schema) {
			if($this->string_ends_with($table_in_schema['table_name'], "_reftextinhalt")) {
				array_push($all_tables_with_reftextinhalt_suffix, $table_in_schema['table_name']);
			}
		}
		
		$sql = "INSERT INTO xplan_gml." . $table . "(gml_id, schluessel, gesetzlichegrundlage, text, reftext, user_id, konvertierung_id, inverszu_texte_xp_plan, rechtscharakter";
		$sql .= ", inverszu_reftextinhalt_" . $prefix . "_objekt";
		// bp_special attributes xplan 5.0.1
		if($prefix == 'bp') {
			$sql .= ", inverszu_abweichungtext_bp_baugebietsteilflaeche, inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche";
		}
		$sql .= ")";
		$sql .= "
			SELECT
				trim(leading 'Gml_' FROM (trim(leading 'GML_' FROM gmlas.id)))::text::uuid AS gml_id,
				gmlas.schluessel AS schluessel,
				gmlas.gesetzlichegrundlage AS gesetzlichegrundlage,
				gmlas.text AS text, ";
		if($this->check_if_table_exists_in_schema("fp_textabschnitt_externereferenz", $this->gmlas_schema)) {
			$sql .= "
				CASE
					WHEN count_externeref > 0
					THEN array_to_json(externeref.externereferenz)
					ELSE NULL
				END AS reftext,";
		} ELSE {
			$sql .= 'NULL AS reftext,';
		}
		$sql .= $user_id . " AS user_id,
						" . $konvertierung_id . " AS konvertierung_id,
						NULL AS inverszu_texte_xp_plan,
						gmlas.rechtscharakter::xplan_gml." . $prefix . "_rechtscharakter AS rechtscharakter,
						NULL AS inverszu_reftextinhalt_" . $prefix . "_objekt";
		if($prefix == "bp") {
			$sql .= "
			,NULL AS inverszu_abweichungtext_bp_baugebietsteilflaeche,
			NULL AS inverszu_abweichungtext_bp_nebenanlagenausschlussflaeche";
		}
						
		$sql .= "
			FROM
				" . $this->gmlas_schema . "." . $table . " gmlas ";
		if($this->check_if_table_exists_in_schema("fp_textabschnitt_externereferenz", $this->gmlas_schema)) {
			$sql .= "	LEFT JOIN
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
						" . $this->gmlas_schema . "." . $prefix . "_textabschnitt_externereferenz externereferenzlink_sub ";

			$sql .=	" LEFT JOIN
						" . $this->gmlas_schema . ".xp_externereferenz e_sub ON externereferenzlink_sub.xp_externereferenz_pkid = e_sub.ogr_pkid
					GROUP BY
						externereferenzlink_sub.parent_id
				) externeref ON gmlas.id = externeref.parent_id 
				";
		}
		for($i = 0;$i < count($all_tables_with_reftextinhalt_suffix);$i++) {
			$sql .= " LEFT JOIN " . $this->gmlas_schema . "." . $all_tables_with_reftextinhalt_suffix[$i] . " ref" . $i. " ON " . "gmlas.id = ref" . $i . ".reftextinhalt_pkid";
		}
		$sql .= ";";
		# echo $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_assoc($ret[1]);
		return $result;
	}
	
		/* string ends with 
		* in php 8.0+ str_ends_with
		*/
	function string_ends_with( $haystack, $needle ) {
		return substr($haystack, -strlen($needle))===$needle;
	}

	/*
	* Create a specific schema
	*/
	function create_schema($schema) {
		$sql = "CREATE SCHEMA " . $schema . ";";
		$ret = $this->pgdatabase->execSQL($sql, 4,0);
	}

	/*
	* Drops a specific schema
	*/
	function drop_schema($schema) {
		$sql = "DROP SCHEMA IF EXISTS " .$schema . " CASCADE;";
		$ret = $this->pgdatabase->execSQL($sql, 4,0);
	}

	/*
	* This function builds the basic xplan_gmlas tables for the basisobjekte of all schemas (xp, bp, fp, lp, rp, so)
	*/
	function build_basic_tables() {
		# Prepare schema 
		if ($this->check_if_schema_exists($this->gmlas_schema)) {
			$this->drop_schema($this->gmlas_schema);
		}
		$this->create_schema($this->gmlas_schema);

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
	function insert_all_regeln_into_db($konvertierung_id, $stelle_id) {
		$geometry_type = ''; # Default
		$bereiche_ids = $this->get_all_bereiche_ids_of_konvertierung($konvertierung_id);
		
		# TODO Check for each regel if bereich = bereich, and enter it accordingly in rule
		#$bereich_gml_id = 'd48608a2-6f3c-11e8-8ca0-1f2b7a47118e'; #Placeholder

		$classes = $this->get_possible_classes_for_regeln($this->gmlas_schema);
		
		# Loop over relevant bereiche -> classes -> geom
		$bereich_index = 0;
		foreach($bereiche_ids as $bereich_gml_id) {
			# index is used for modifying regel by name
			$bereich_index++;
			foreach($classes as $c) {
				if($this->check_if_table_has_entries_for_bereich($this->gmlas_schema, $c, $bereich_gml_id)) {
					# Loop over all geom-types to get a rule for each
					# use only ST_Point, ST_MultiPoint, ST_LineString, ST_MultiLineString, ST_Polygon and ST_MultiPolygon
					$geometry_types = $this->get_geometry_types_of_class_in_schema($c, $this->gmlas_schema);
					foreach($geometry_types as $g) {
						$sql_regel = $this->get_gmlas_to_gml_regel_for_class_in_bereich_with_geom($c, $bereich_gml_id, $g);
						$sql_regel = str_replace("'", "''", $sql_regel); # Replaces all single commas with 2x single commas to escape them in SQL
						$this->insert_regel_into_db($c, $sql_regel, $g, $konvertierung_id, $stelle_id, $bereich_gml_id, $bereich_index);
					}
				}
			}
		}
	}

	/*
	* Returns all bereiche of a specific konvertierung
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

	/*
	* Returns the SQL of a specified regel for a specific class in a specific bereich with a specific geometry type
	*/
	function get_gmlas_to_gml_regel_for_class_in_bereich_with_geom($gmlas_class, $bereich_id, $geom_type) {
		$gml_class = $gmlas_class; # Is this always the case?
		$gmlas_attributes = $this->get_attributes_with_values_for_class_in_schema($gmlas_class, $this->gmlas_schema);
		# echo '<pre>'; print_r($gmlas_attributes); echo '</pre>';
		$mapping_table = $this->get_gmlas_to_gml_mapping_table($gmlas_class);

		$select_sql = '';
		$gml_attributes = [];

		# Loops through all attributes, comparing them with the mapping table original column (and table)
		# If matches are found, the target attributes are taken and the associated regel is added to the SQL
		foreach($gmlas_attributes AS $a) {
			if(!in_array($a, array_column($mapping_table, 'o_column'))) {
				continue;
			}

			foreach($mapping_table as $mapping) {
				if(($mapping['o_column'] == $a) and ($mapping['o_table'] == $gml_class)) {
					# gehoertzubereich wird automatisiert bei der Konvertierung in die Regel eingearbeitet 
					# muss deswegen für Attribute nicht verwendet werden, ggf aber für WHERE filter
					if($mapping['t_column'] == 'gehoertzubereich') {
						continue;
					}

					# fix for varying encountered gml-geometry types that are valid for xplanung (according to konformitaetsbedingungen-document) but can cause problems
					# e.g. in display, in export, for wms/wfs services or are not supported by the konverter
					if($mapping['t_column'] == 'position') {
						if(($geom_type == 'ST_CurvePolygon') or
							($geom_type == 'ST_MultiSurface') or
							($geom_type == 'ST_CompoundCurve') or
							($geom_type == 'ST_MultiCurve'))
						{
							$mapping['regel'] = 'ST_CurveToLine(gmlas.position) AS position';
						}
						// Cast to multi-geometries (konverter-convention)
						else if(($geom_type == 'ST_Point') or
							($geom_type == 'ST_LineString') or
							($geom_type == 'ST_Polygon'))
						{
							$mapping['regel'] = 'ST_Multi(gmlas.position) AS position';
						} else {
							$mapping['regel'] = 'gmlas.position AS position';
						}
					}
					$gml_attributes[] = $mapping['t_column'];
					$select_sql .= $mapping['regel'];
					$select_sql .= ",";
				}
			}
		}

		# attributes of normalized gmlas tables, e.g. praesentationsobjekte '_dientzurdarstellungvon', '_wirddargestelltdurch'
		# TODO generically read all normalized tables
		# zweckbestimmung e.g. for fp_generischesobjekt_zweckbestimmung
		$norm_attributes = array("wirddargestelltdurch","dientzurdarstellungvon","reftextinhalt","detailliertezweckbestimmung","zweckbestimmung");
		$i = 0;
		foreach($norm_attributes AS $n_a) {
			$i++;
			# check if table exists
				$sql_checkexists_norm_table = "
					SELECT 
						EXISTS (
						 SELECT FROM information_schema.tables 
						 WHERE table_schema = '" . $this->gmlas_schema . "'
						 AND table_name = '" . $gml_class . '_' . $n_a . "'
						);";
			$ret = $this->pgdatabase->execSQL($sql_checkexists_norm_table, 4, 0);
			$result = pg_fetch_row($ret[1]);
			if($result[0] === 't') {
				# single ' escaped later
				$norm_1 = "norm_table_" . $i;
				if($n_a == "wirddargestelltdurch" or $n_a == "dientzurdarstellungvon" or $n_a == "reftextinhalt") {
						$select_sql .= "(SELECT string_agg(href,',') FROM " . $this->gmlas_schema . "." . $gml_class . "_" . $n_a . " ". $norm_1 . " WHERE gmlas.id = " .$norm_1 . ".parent_id) AS " . $n_a . ",";
					$gml_attributes[] = $n_a;
				}
				if($n_a == "detailliertezweckbestimmung" or $n_a == "zweckbestimmung") {
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
							column_name = '" . $n_a . "'
						LIMIT 1
					";
					$ret = $this->pgdatabase->execSQL($sql, 4, 0);
					$result = pg_fetch_row($ret[1]);
					$special_datatype = $result[0];
					if(substr($special_datatype,0,1) == "_") {
						// remove leading underscore (for arrays) and add [] brackets at the end
						$special_datatype = ltrim($special_datatype, "_") . "[]";
					}
					if($special_datatype != "") {
						$norm_2 = "norm_table_" . $i . "_" . $i;
						$norm_3 = "norm_table_" . $i . "_" . $i . "_" . $i;
						$norm_4 = "norm_table_" . $i . "_" . $i . "_" . $i . "_" . $i;
						$select_sql .= "CASE WHEN (SELECT TRUE FROM " . $this->gmlas_schema . "." . $gml_class . "_" . $n_a . " " . $norm_2 . " WHERE " . $norm_2 . ".parent_id = gmlas.id LIMIT 1) THEN ARRAY[((SELECT DISTINCT codespace FROM " . $this->gmlas_schema . "." . $gml_class . "_" . $n_a . " " . $norm_3 . " WHERE gmlas.id = " . $norm_3 . ".parent_id LIMIT 1),";
						$select_sql .= "(SELECT string_agg(value,',') FROM " . $this->gmlas_schema . "." . $gml_class . "_" . $n_a . " " . $norm_4 . " WHERE gmlas.id = " . $norm_4 . ".parent_id),NULL)]::xplan_gml." . $special_datatype . " ELSE NULL END AS " . $n_a . ",";
						$gml_attributes[] = $n_a;
					}
				}
			}
		}


		// Add INSERT INTO and FROM
		$sql  = 'INSERT INTO ' . XPLANKONVERTER_CONTENT_SCHEMA . '.' . $gml_class . '(';
		$sql .= implode(",", $gml_attributes);
		$sql .= ')';
		$sql .= ' SELECT ';
		$sql .= $select_sql;

		# Remove last comma
		if(substr($sql, -1, 1) == ",") {
			$sql = substr($sql, 0, -1);
		}
		$sql .= ' FROM ' . $this->gmlas_schema . '.' . $gmlas_class . ' gmlas';
		#Filters only by relevant bereich (in case 2 rules target the same class with different bereich)
		$sql .= " WHERE gmlas.gehoertzubereich_href ILIKE '%" . $bereich_id . "'";
		$sql .= " AND ST_GeometryType(position) = '" . $geom_type . "'";
		return $sql;
	}

	/*
	* Returns all attributes for a specific class in a specific schema in an array
	*/
	function get_attributes_with_values_for_class_in_schema($class, $schema) {
		# This function selects all attributes that have values in a specific class and schema
		$sql = "
			SELECT
				column_name
			FROM
				information_schema.columns
			WHERE
				table_schema = '" . $schema . "'
			AND
				table_name = '" . $class . "'
			ORDER BY ordinal_position;
		";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$all_attributes = pg_fetch_all_columns($ret[1]);

		# Returns an array of t or f values for all attributes for a class
		$sql = "SELECT ";
		foreach($all_attributes AS $a) {
			$sql .= "EXISTS(SELECT " . $a;
			$sql .= " FROM " . $schema . "." . $class;
			$sql .= " WHERE " . $a . " IS NOT NULL) AS " . $a . ",";
		}
		if(substr($sql, -1, 1) == ",") {
			$sql = substr($sql, 0, -1);
		}
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$attributes_exist = pg_fetch_array($ret[1]);

		# Compares both arrays and writes all existing values in a new array
		$attributes = [];
		for($i = 0; $i < count($all_attributes); $i++) {
			if($attributes_exist[$i] == 't') {
				$attributes[] = $all_attributes[$i];
			}
		}
		return $attributes;
	}

	/*
	* Returns an array of all geometries used within a class in a schema.
	*/
	function get_geometry_types_of_class_in_schema($class, $schema) {
		# for xplan-objects that are specializations of xp_object, the geometry column is always position
		# would not work on objects that are e.g. specializations of xp_plan or xp_bereich or non xplan-objects
		$geom_column = 'position';
		$sql = "SELECT DISTINCT(ST_GeometryType(" . $geom_column . ")) FROM " . $schema . "." . $class;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$geom_types = pg_fetch_all_columns($ret[1]);
		return $geom_types;
	}

	/*
	* Returns a mapping table that should cover all mappable classes between gdal xplan_gmlas and the konverter xplan_gml schemas
	*/
	function get_gmlas_to_gml_mapping_table($class) {
		$sql = "SELECT o_table, o_column, t_table, t_column, t_data_type, regel FROM xplankonverter.mappingtable_gmlas_to_gml WHERE t_table = '" . $class . "';";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_all($ret[1]);
		return $result;
	}

	/*
	* Inserts a specific regel into the xplankonverter.regeln schema
	*/
	function insert_regel_into_db($class, $regel, $geometry_type, $konvertierung_id, $stelle_id, $bereich_gml_id, $bereich_index) {
		$uml_class = $this->get_uml_classname($class);

		$sql  = "INSERT INTO xplankonverter.regeln";
		$sql .= "(class_name, factory, sql, geometrietyp, name, beschreibung, konvertierung_id, stelle_id, bereich_gml_id)";
		$sql .= " SELECT ";
		$sql .= "'" . $uml_class . "' AS class_name, ";
		$sql .= "'sql'::xplankonverter.enum_factory AS factory, ";
		$sql .= "'" . $regel . "' AS sql, ";
		switch($geometry_type) {
			case "ST_Point":
			case "ST_MultiPoint":
				$sql .= " 'Punkte'::xplankonverter.enum_geometrie_typ AS geometrietyp, ";
				$sql .= "'" . $uml_class . "_" . $bereich_index . "_" . "Punkte' AS name, ";
				break;
			case "ST_LineString":
			case "ST_MultiLineString":
			case "ST_CompoundCurve":
				$sql .= " 'Linien'::xplankonverter.enum_geometrie_typ AS geometrietyp, ";
				$sql .= "'" . $uml_class . "_" . $bereich_index . "_" . "Linien' AS name, ";
				break;
			case "ST_Polygon":
			case "ST_MultiPolygon":
			case "ST_CurvePolygon":
			case "ST_MultiSurface":
				$sql .= " 'Flächen'::xplankonverter.enum_geometrie_typ AS geometrietyp, ";
				$sql .= "'" . $uml_class . "_" . $bereich_index . "_" . "Flaechen' AS name, ";
				break;
		}
		$sql .= "'regel created automatically from gmlas extraction' AS beschreibung, ";
		$sql .= $konvertierung_id . " AS konvertierung_id, ";
		$sql .= $stelle_id . " AS stelle_id, ";
		$sql .= "'" . $bereich_gml_id . "' AS bereich_gml_id ";

		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
	}

	/*
	* Returns UML capitalization of classname
	*/
	function get_uml_classname($class) {
		$sql = "SELECT DISTINCT name FROM xplan_uml.uml_classes WHERE LOWER(name) = '" . $class . "'";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$uml_class = pg_fetch_row($ret[1]);
		return $uml_class[0];
	}

	/*
	* Returns a possible list of filtered tables that can be associated with rules
	*/
	function get_possible_classes_for_regeln($schema) {
		# escape underscore e.g. for fp_zentralerversorgungsbereich
		$sql = "
			SELECT
				i.table_name
			FROM
				information_schema.tables i INNER JOIN xplan_uml.uml_classes u ON (i.table_name = LOWER(u.name))
			WHERE
				i.table_schema = '" . $schema . "' AND
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
		$ret = $this->pgdatabase->execSQL($sql,4, 0);
		$classes = pg_fetch_all_columns($ret[1]);
		return $classes;
	}

	/*
	* Returns TRUE or FALSE depending on whether a table has at least 1 row
	*/
	function check_if_table_has_entries_for_bereich($schema, $table, $bereich_gml_id) {
		$sql  = "SELECT TRUE FROM " . $schema . "." . $table;
		$sql .= " WHERE gehoertzubereich_href ILIKE '%" . $bereich_gml_id . "' LIMIT 1;";
		$ret = $this->pgdatabase->execSQL($sql,4, 0);
		$result = pg_fetch_row($ret[1]);
		return $result[0] ? true : false; 
	}
}
?>
