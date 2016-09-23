<?php
$this->goNotExecutedInPlugins = false;

include(PLUGINS . 'xplankonverter/model/kvwmap.php');
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'MyObject.php');
#include_once(CLASSPATH . 'LayerGroup.php');
include_once(CLASSPATH . 'data_import_export.php');
include(PLUGINS . 'xplankonverter/model/gml_file.php');
include(PLUGINS . 'xplankonverter/model/RP_Plan.php');
include(PLUGINS . 'xplankonverter/model/RP_Bereich.php');
include(PLUGINS . 'xplankonverter/model/konvertierung.php');
include(PLUGINS . 'xplankonverter/model/regel.php');
include(PLUGINS . 'xplankonverter/model/shapefiles.php');
include(PLUGINS . 'xplankonverter/model/validator.php');
include(PLUGINS . 'xplankonverter/model/xplan.php');

switch($this->go){

	case 'show_elements':
		$packages = array();
		$sql	= "
			SELECT
				DISTINCT package
			FROM
				xplan.elements
			ORDER BY
				package
		";
		$result = pg_query($this->pgdatabase->dbConn, $sql);
		$this->packages = pg_fetch_all($result);
		array_unshift($packages, array('package' => 'Alle'));
		$this->main = PLUGINS . 'xplankonverter/view/elements.php';
		$this->output();
		break;
	case 'show_simple_types':
		$this->main = PLUGINS . 'xplankonverter/view/simple_types.php';
		$this->output();
		break;
	case 'show_uml':
		$this->main = PLUGINS . 'xplankonverter/view/uml_diagramms.php';
		$this->output();
		break;

	case 'xplankonverter_konvertierungen_index' : {
		$this->main = '../../plugins/xplankonverter/view/konvertierungen.php';
		$this->output();
	} break;

	/*
	* Anwendungsfall, der aus jeder gml_class einen Layer erzeugt.
	*/
	/*
	case 'xplankonverter_xplan_classes' : {
		# get layerGroupId or create a group if not exists
		$layer_group_id = 12;

		$gml_classes = getGMLClasses();

		foreach($gml_classes AS $gml_class) {
			foreach(array(0 => 'point', 1 => 'line', 2 => 'polygon') AS $geom_type_key => $geom_type_value) {
				# create layer
				$this->formvars['Name'] = $gml_class['name'];
				$this->formvars['Datentyp'] = $shapeFile->get('datatype');
				$this->formvars['Gruppe'] = $layer_group_id;
				$this->formvars['pfad'] = 'Select * from ' . $shapeFile->dataTableName() . ' where 1=1';
				$this->formvars['Data'] = 'the_geom from (select oid, * from ' .
					$shapeFile->dataSchemaName() . '.' . $shapeFile->dataTableName() .
					' where 1=1) as foo using unique oid using srid=' . $shapeFile->get('epsg_code');
				$this->formvars['maintable'] = $shapeFile->dataTableName();
				$this->formvars['schema'] = $shapeFile->dataSchemaName();
				$this->formvars['connection'] = $this->pgdatabase->connect_string;
				$this->formvars['connectiontype'] = '6';
				$this->formvars['filteritem'] = 'oid';
				$this->formvars['tolerance'] = '5';
				$this->formvars['toleranceunits'] = 'pixels';
				$this->formvars['epsg_code'] = $shapeFile->get('epsg_code');
				$this->formvars['querymap'] = '1';
				$this->formvars['queryable'] = '1';
				$this->formvars['transparency'] = '75';
				$this->formvars['postlabelcache'] = '0';
				$this->formvars['allstellen'] = '2300';
				$this->formvars['ows_srs'] = 'EPSG:' . $shapeFile->get('epsg_code') . ' EPSG:25833 EPSG:4326 EPSG:2398';
				$this->formvars['wms_server_version'] = '1.1.0';
				$this->formvars['wms_format'] = 'image/png';
				$this->formvars['wms_connectiontimeout'] = '60';
				$this->formvars['selstellen'] = '1, ' . $this->konvertierung->get('stelle_id') . ', 1, ' . $this->konvertierung->get('stelle_id');
				$this->LayerAnlegen();


				# Ordne layer zur Stelle
				$this->Stellenzuweisung(
					array($shapeFile->get('layer_id')),
					array($this->konvertierung->get('stelle_id'))
				);

				# Füge eine Klasse zum neuen Layer hinzu.
				$this->formvars['class_name'] = 'alle';
				$this->formvars['class_id'] = $this->Layereditor_KlasseHinzufuegen();

				# Füge einen Style zur Klasse hinzu
				$this->add_style();

			}
		}
	#}	end of upload files
	$this->main = '../../plugins/xplankonverter/view/shapefiles.php';


		$this->output();
	} break;
*/
	case 'xplankonverter_shapefiles_index': {
		if ($this->formvars['konvertierung_id'] == '') {
			$this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
			$this->main = 'Hinweis.php';
		}
		else {
			$this->konvertierung = Konvertierung::find_by_id($this, 'id', $this->formvars['konvertierung_id']);
			if (isInStelleAllowed($this->Stelle, $this->konvertierung->get('stelle_id'))) {
				if (isset($_FILES['shape_files']) and $_FILES['shape_files']['name'][0] != '') {
					$upload_path = XPLANKONVERTER_SHAPE_PATH . $this->formvars['konvertierung_id'] . '/';

					# create upload dir if not exists
					if (!is_dir($upload_path)) {
						$old = umask(0);
						mkdir($upload_path, 0770, true);
						umask($old);
					}

					# unzip and copy files to upload folder
					$uploaded_files = xplankonverter_unzip_and_check_and_copy($_FILES['shape_files'], $upload_path);
					# get layerGroupId or create a group if not exists
					$layer_group_id = $this->konvertierung->get('layer_group_id');
					if (empty($layer_group_id))
						$layer_group_id = $this->konvertierung->create_layer_group('Shape');
					foreach($uploaded_files AS $uploaded_file) {
						if ($uploaded_file['extension'] == 'dbf' and $uploaded_file['state'] != 'ignoriert') {

							# delete existing shape file
							$shapeFile = new ShapeFile($this, 'xplankonverter', 'shapefiles');
							$shapeFiles = $shapeFile->find_where("
								filename = '" . $uploaded_file['filename'] . "' AND
								konvertierung_id = '" . $this->konvertierung->get('id') . "' AND
								stelle_id = " . $this->konvertierung->get('stelle_id')
							);
							if (!empty($shapeFiles)) $shapeFile = $shapeFiles[0]; # es kann nur eins geben
							if (!empty($shapeFile->data)) {
								$this->debug('<p>Lösche gefundenes shape file.');
								$shapeFile->deleteLayer();
								$shapeFile->deleteDataTable();
								$shapeFile->delete();
							}
							# create new record in shapefile table
							$shapeFile->create(
								array(
									'filename' => $uploaded_file['filename'],
									'konvertierung_id' => $this->konvertierung->get('id'),
									'stelle_id' => $this->konvertierung->get('stelle_id'),
									'epsg_code' => $this->formvars['epsg_code']
								)
							);

							# Create schema for data table if not exists
							$shapeFile->createDataTableSchema();

							# load into database table
							$created_tables = $shapeFile->loadIntoDataTable();

							# Set datatype for shapefile
							$shapeFile->set('datatype', $created_tables[0]['datatype']);
							$shapeFile->update();

							# create layer
							$this->formvars['Name'] = $shapeFile->get('filename');
							$this->formvars['Datentyp'] = $shapeFile->get('datatype');
							$this->formvars['Gruppe'] = $layer_group_id;
							$this->formvars['pfad'] = 'Select * from ' . $shapeFile->dataTableName() . ' where 1=1';
							$this->formvars['Data'] = 'the_geom from (select oid, * from ' .
								$shapeFile->dataSchemaName() . '.' . $shapeFile->dataTableName() .
								' where 1=1) as foo using unique oid using srid=' . $shapeFile->get('epsg_code');
							$this->formvars['maintable'] = $shapeFile->dataTableName();
							$this->formvars['schema'] = $shapeFile->dataSchemaName();
							$this->formvars['connection'] = $this->pgdatabase->connect_string;
							$this->formvars['connectiontype'] = '6';
							$this->formvars['filteritem'] = 'oid';
							$this->formvars['tolerance'] = '5';
							$this->formvars['toleranceunits'] = 'pixels';
							$this->formvars['epsg_code'] = $shapeFile->get('epsg_code');
							$this->formvars['querymap'] = '1';
							$this->formvars['queryable'] = '1';
							$this->formvars['transparency'] = '75';
							$this->formvars['postlabelcache'] = '0';
							$this->formvars['allstellen'] = '2300';
							$this->formvars['ows_srs'] = 'EPSG:' . $shapeFile->get('epsg_code') . ' EPSG:25833 EPSG:4326 EPSG:2398';
							$this->formvars['wms_server_version'] = '1.1.0';
							$this->formvars['wms_format'] = 'image/png';
							$this->formvars['wms_connectiontimeout'] = '60';
							$this->formvars['selstellen'] = '1, ' . $this->konvertierung->get('stelle_id') . ', 1, ' . $this->konvertierung->get('stelle_id');
							$this->LayerAnlegen();

							# Assign layer_id to shape file record
							$shapeFile->set('layer_id', $this->formvars['selected_layer_id']);
							$shapeFile->update();

							# Ordne layer zur Stelle
							$this->Stellenzuweisung(
								array($shapeFile->get('layer_id')),
								array($this->konvertierung->get('stelle_id'))
							);

							# Füge eine Klasse zum neuen Layer hinzu.
							$this->formvars['class_name'] = 'alle';
							$this->formvars['class_id'] = $this->Layereditor_KlasseHinzufuegen();

							# Füge einen Style zur Klasse hinzu
							$this->add_style();

						}
					}
				} # end of upload files
				$this->main = '../../plugins/xplankonverter/view/shapefiles.php';
			}
		}
		$this->output();
	} break;

	case 'xplankonverter_shapefiles_delete' : {
		if ($this->formvars['shapefile_id'] == '') {
			$this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher ein Shape Datei ausgewählt wurde.';
			$this->main = 'Hinweis.php';
		}
		else {
			$shapefile = new Shapefile($this, 'xplankonverter', 'shapefiles');
			$shapefile->find_by('id', $this->formvars['shapefile_id']);
			if (isInStelleAllowed($this->Stelle, $shapefile->get('stelle_id'))) {
				# Delete the layerdefinition in mysql (rolleneinstellungen, layer, classes, styles, etc.)
				$shapefile->deleteLayer();
				# Delete the postgis data table that hold the data of the shape file
				$shapefile->deleteDataTable();
				# Delete the uploaded shape files itself
				$shapefile->deleteUploadFiles();
				# Delete the record in postgres shapefile table (unregister for konverter)
				$shapefile->delete();
				$this->konvertierung = Konvertierung::find_by_id($this, 'id', $this->formvars['konvertierung_id']);
				$this->main = '../../plugins/xplankonverter/view/shapefiles.php';
			}
		}
		$this->output();
	} break;

  case 'xplankonverter_konvertierung_status': {
    header('Content-Type: application/json');
    $response = array();
    if ($this->formvars['konvertierung_id'] == '') {
      $response['success'] = false;
      $response['msg'] = 'Konvertierung wurde nicht angegeben';
      return;
    }
    if ($this->formvars['status'] == '') {
      $this->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn ein Status angegeben ist.';
      $response['success'] = false;
      $response['msg'] = 'Status wurde nicht angegeben';
      echo json_encode($response);
      return;
    }
    // now get konvertierung
    $this->konvertierung = Konvertierung::find_by_id($this, 'id', $this->formvars['konvertierung_id']);

    // check stelle
    if (!isInStelleAllowed($this->Stelle, $this->konvertierung->get('stelle_id'))) return;

    // check applicability of status for external invokation
    $statusToSet = $this->formvars['status'];
    $isApplicable = false;
    $applicableStates = array(
        Konvertierung::$STATUS['IN_ERSTELLUNG'],
        Konvertierung::$STATUS['IN_KONVERTIERUNG'],
        Konvertierung::$STATUS['IN_GML_ERSTELLUNG']
    );
    array_walk(
      $applicableStates,
      function($pattern) use ($statusToSet,&$isApplicable) {
        $isApplicable |= $statusToSet == $pattern;
      }
    );
    if (!$isApplicable) {
      $this->Hinweis = "Der Status '$statusToSet' kann nicht explizit gesetzt werden.";
      $response['success'] = false;
      $response['msg'] = "Status '$statusToSet' kann nicht explizit gesetzt werden";
      echo json_encode($response);
      return;
    }
    // check validity of status
    $isValid = false;
    $validPredecessorStates = array();
    switch($statusToSet){
      case Konvertierung::$STATUS['IN_ERSTELLUNG']:
        $validPredecessorStates = array(
          Konvertierung::$STATUS['ERSTELLT'],
          Konvertierung::$STATUS['KONVERTIERUNG_OK'],
          Konvertierung::$STATUS['KONVERTIERUNG_ERR'],
          Konvertierung::$STATUS['GML_ERSTELLUNG_OK'],
          Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']
        );
        break;
      case Konvertierung::$STATUS['IN_KONVERTIERUNG']:
        $validPredecessorStates = array(
          Konvertierung::$STATUS['ERSTELLT'],
          Konvertierung::$STATUS['KONVERTIERUNG_OK'],
          Konvertierung::$STATUS['GML_ERSTELLUNG_OK']
        );
        break;
      case Konvertierung::$STATUS['IN_GML_ERSTELLUNG']:
        $validPredecessorStates = array(
          Konvertierung::$STATUS['KONVERTIERUNG_OK'],
          Konvertierung::$STATUS['GML_ERSTELLUNG_OK']
        );
        break;
    }
    $currStatus = $this->konvertierung->get('status');
    $isValid = array_reduce(
        $validPredecessorStates,
        function($isValid,$predStatus) use ($currStatus) {
          return isValid || ($predStatus == $currStatus);
    }, $isValid);
    if (!$isValid) {
      $response['success'] = false;
      $response['msg'] = "Status '$statusToSet' ist kein gueltiger Folgestatus von '$currStatus'";
      echo json_encode($response);
      return;
    }

    // status is valid to be set
    $this->konvertierung->set('status', $statusToSet);
    $this->konvertierung->update();
    $response['success'] = true;
    $response['msg'] = 'Status wurde gesetzt';
    echo json_encode($response);
  } break;

  case 'xplankonverter_konvertierung_validate': {
// TODO: remove
sleep(5);
		$response = array();
		header('Content-Type: application/json');
		if ($this->formvars['konvertierung_id'] == '') {
			$response['success'] = false;
			$response['msg'] = 'Konvertierung wurde nicht angegeben';
			echo json_encode($response);
			return;
		}
		$this->konvertierung = new Konvertierung($this);
		$this->konvertierung->find_by('id', $this->formvars['konvertierung_id']);

		if (!isInStelleAllowed($this->Stelle, $this->konvertierung->get('stelle_id')))
			return;

		// do the validation
		$validator = new Validator();
		$validator->validateKonvertierung(
				$this->konvertierung,
				function() { // Validation successful
					$this->konvertierung->set('status', Konvertierung::$STATUS['KONVERTIERUNG_OK']);
					$this->konvertierung->update();
					$response['success'] = true;
					$response['msg'] = 'Konvertierung erfolgreich ausgeführt.';
					echo json_encode($response);
				},
				function($error) { // Validation failed
					$this->konvertierung->set('status', Konvertierung::$STATUS['KONVERTIERUNG_ERR']);
					$this->konvertierung->update();
					$response['success'] = false;
					$response['msg'] = 'Bei der Validierung ist ein Fehler aufgetreten: '.$error;
					echo json_encode($response);
				}
		);
	} break;

	case 'xplankonverter_regeln_anwenden': {
		include(PLUGINS . 'xplankonverter/model/converter.php');
// TODO: remove
sleep(5);
		$response = array();
		header('Content-Type: application/json');
		if ($this->formvars['konvertierung_id'] == '') {
			$response['success'] = false;
			$response['msg'] = 'Konvertierung wurde nicht angegeben';
			echo json_encode($response);
			return;
		}
		$this->konvertierung = new Konvertierung($this);
		$this->konvertierung->find_by('id', $this->formvars['konvertierung_id']);

		if (!isInStelleAllowed($this->Stelle, $this->konvertierung->get('stelle_id')))
			return;

		// do apply the rule set
		$this->converter = new Converter($this->pgdatabase, $this->pgdatabase);
		$this->converter->gmlfeatures_loeschen($this->formvars['konvertierung_id']);
		$this->converter->regeln_anwenden($this->formvars['konvertierung_id']);

		$response['success'] = true;
		$response['msg'] = 'Regeln erfolgreich angewendet.';
		header('Content-Type: application/json');
		echo json_encode($response);
	} break;

	case 'xplankonverter_gml_generieren' : {
		include(PLUGINS . 'xplankonverter/model/build_gml.php');
		$response = array();
		if ($this->formvars['konvertierung_id'] == '') {
			$response['success'] = false;
			$response['msg'] = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
		}
		else {
			$this->konvertierung = Konvertierung::find_by_id($this, 'id', $this->formvars['konvertierung_id']);
			if (isInStelleAllowed($this->Stelle, $this->konvertierung->get('stelle_id'))) {
				if ($this->konvertierung->get('status') == Konvertierung::$STATUS['KONVERTIERUNG_OK']
				 || $this->konvertierung->get('status') == Konvertierung::$STATUS['IN_GML_ERSTELLUNG']
				 || $this->konvertierung->get('status') == Konvertierung::$STATUS['GML_ERSTELLUNG_OK']) {

					// Status setzen
					$this->konvertierung->set('status', Konvertierung::$STATUS['IN_GML_ERSTELLUNG']);
					$this->konvertierung->update();

					// XPlan-GML ausgeben
					$this->gml_builder = new Gml_builder($this->pgdatabase);
					$plan = RP_Plan::find_by_id($this,'konvertierung_id', $this->konvertierung->get('id'));
					//$bereiche = new RP_Bereich($this);
					//$bereiche->find_by('gehoertzuplan', $this->plan->get('gml_id'));
					//$this->plan->bereiche = $bereiche;

					$this->gml_builder->build_gml($this->konvertierung, $plan);
					$this->gml_builder->save(XPLANKONVERTER_SHAPE_PATH . $this->konvertierung->get('id') . '/xplan_' . $this->konvertierung->get('id') . '.gml');

					// Status setzen
					$this->konvertierung->set('status', Konvertierung::$STATUS['GML_ERSTELLUNG_OK']);
					$this->konvertierung->update();

					// Erzeuge Layergruppe, falls noch nicht vorhanden
					$layer_group_id = $this->konvertierung->create_layer_group('GML');
					// vorhandene Layer dieser Konvertierung löschen
					// Neue Layer erzeugen
					$this->layer_generator_erzeugen($layer_group_id); # Funktion aus kvwmap.php

					$response['success'] = true;
					$response['msg'] = 'GML-Datei erfolgreich erstellt.';
				} else {
					$response['success'] = false;
					$response['msg'] = 'Die ausgewählte Konvertierung muss zuerst ausgeführt werden.';
				}
			}
		}
		header('Content-Type: application/json');
		echo json_encode($response);
	} break;

	case 'xplankonverter_gml_ausliefern' : {
		if ($this->formvars['konvertierung_id'] == '') {
		  echo 'Diese Link kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
		  return;
		}
		$this->konvertierung = Konvertierung::find_by_id($this, 'id', $this->formvars['konvertierung_id']);
		if (!isInStelleAllowed($this->Stelle, $this->konvertierung->get('stelle_id'))) return;

		$filename = XPLANKONVERTER_SHAPE_PATH . $this->formvars['konvertierung_id'] . '/xplan_' . $this->formvars['konvertierung_id'] . '.gml';
		header('Content-Type: text/xml; subtype="gml/3.3"');
    echo fread(fopen($filename, "r"), filesize($filename));
	} break;

	case 'xplankonverter_konvertierung_loeschen' : {
		# Dieser ganze case kann durchgeführt werden durch das Löschen der Konvertierung mit den GLE Funktionen und
		# dem after delete Trigger. (siehe trigger_function handle_konvertierung in control/kvwmap.php)
		$konvertierung = Konvertierung::find_by_id($this, 'id', $this->formvars['konvertierung_id']);

		# Lösche gml-Datei
		$gml_file = new gml_file(XPLANKONVERTER_SHAPE_PATH . $konvertierung->get('id') . '/xplan_' . $konvertierung->get('id') . '.gml');
		if ($gml_file->exists()) {
			$msg = "\nLösche gml file: ". $gml_file->filename;
			$gml_file->delete();
		}

		# Lösche Regeln
		$regeln = $konvertierung->getRegeln();
		foreach($regeln AS $regel) {
			$msg .= "\nLösche Regel ". $regel->get('name') . ' für Klasse ' . $regel->get('class_name');
		}
		# Lösche Layer
		# Lösche gml Layer
		# Lösche gml Layer Gruppe
		# Lösche shape Layer

		# Lösche Shapes
		#$shapeFile->deleteDataTable();
		#$shapeFile->delete();
		# Lösche Shape Layer Gruppe

		# Lösche Bereiche
		# Lösche Plan
		$plan = RP_Plan::find_by_id($this, 'konvertierung_id', $konvertierung->get('id'));
		$msg .= "\nRP Plan " . $plan->get('name') . ' gelöscht.';
		$plan->delete();

		# Lösche Konvertierung
		$konvertierung->delete();


		$response = array(
				'success' => true,
				'msg' => 'Konvertierung erfolgreich gelöscht. ' . $msg
		);
		header('Content-Type: application/json');
		echo json_encode($response);
	} break;

	default : {
		$this->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
	}

}

function isInStelleAllowed($stelle, $requestStelleId) {
	if ($stelle->id == $requestStelleId)
		return true;
	else {
		echo '<br>(Diese Aktion kann nur von der Stelle ' . $stelle->Bezeichnung . ' aus aufgerufen werden.)';
		return false;
	}
}

/*
* extract zip files if necessary, check completeness and copy files to upload folder
*/
function xplankonverter_unzip_and_check_and_copy($shape_files, $dest_dir) {
	# extract zip files if necessary and extract info
	$temp_files = xplankonverter_unzip($shape_files, $dest_dir);
	# group uploaded files to triples according to their basename
	$check = array('shp' => false, 'shx' => false, 'dbf' => false);
	$check_list = array();
	array_walk($temp_files, function($file_item) use ($check, &$check_list){
		if (!$check_list[$file_item['filename']]) $check_list[$file_item['filename']] = $check;
		$check_list[$file_item['filename']][$file_item['extension']] = $file_item;
	});

	$incomplete = array();
	$complete_files = array();
	array_walk($check_list, function($check_list_item) use (&$incomplete, &$complete_files){
		if ($check_list_item['dbf'] && $check_list_item['shx'] && $check_list_item['shp']) {
			$complete_files[] = $check_list_item['dbf'];
			$complete_files[] = $check_list_item['shp'];
			$complete_files[] = $check_list_item['shx'];
		} else $incomplete = $check_list_item;
	});

	# copy temp shape files to destination
	$uploaded_files = array();
	foreach($complete_files AS $file) {
		$uploaded_files[] = xplankonverter_copy_uploaded_shp_file($file, $dest_dir);
	}
	return $uploaded_files;
}
/*
* extract zip files if necessary and copy files to upload folder
*/
function xplankonverter_unzip($shape_files, $dest_dir) {
	$temp_files = array();
	# extract zip files if necessary and copy files to upload folder
	foreach($shape_files['name'] AS $i => $shape_file_name) {
		$path_parts = pathinfo($shape_file_name);

		if (strtolower($path_parts['extension']) == 'zip') {
			# extract files if the extension is zip
			$temp_files = extract_uploaded_zip_file($shape_files['tmp_name'][$i]);
		}
		else {
			# set data from single file
			$path_parts = pathinfo($shape_file_name);
			$temp_files[] = array(
				'basename' => $path_parts['basename'],
				'filename' => $path_parts['filename'],
				'extension' => strtolower($path_parts['extension']),
				'tmp_name' => $shape_files['tmp_name'][$i],
				'unziped' => false
			);
		}
	}
	return $temp_files;
}

/*
* Packt die angegebenen Zip-Datei im sys_temp_dir Verzeichnis aus
* und gibt die ausgepackten Dateien in der Struktur von
* hochgeladenen Dateien aus
*/
function extract_uploaded_zip_file($zip_file) {
	$sys_temp_dir = sys_get_temp_dir();
	$extracted_files = array_map(
		function($extracted_file) {
			$path_parts = pathinfo($extracted_file);
			return array(
				'basename' => $path_parts['basename'],
				'filename' => $path_parts['filename'],
				'extension' => $path_parts['extension'],
				'tmp_name' => sys_get_temp_dir() . '/' . $extracted_file,
				'unziped' => true
			);
		},
		unzip($zip_file, false, false, true)
	);
	return $extracted_files;
}

/*
* Copy files from sys_temp_dir to upload directory and mark if
* files are new, override older or are ignored
*/
function xplankonverter_copy_uploaded_shp_file($file, $dest_dir) {
	$messages = array();
	if (in_array($file['extension'], array('dbf', 'shx', 'shp'))) {
		if (file_exists($dest_dir . $file['basename'])) {
			$file['state'] = 'geändert';
		}
		else {
			$file['state'] = 'neu';
		}
		if ($file['unziped']) {
			rename($file['tmp_name'], $dest_dir . $file['basename']);
		}
		else {
			move_uploaded_file($file['tmp_name'], $dest_dir . $file['basename']);
		}
	}
	else {
		if ($file['unziped']) {
			if (is_dir($file['tmp_name']))
				delete_files($file['tmp_name']);
			else
				unlink($file['tmp_name']);
		}
		$file['state'] = 'ignoriert';
	}
	return $file;
}
?>