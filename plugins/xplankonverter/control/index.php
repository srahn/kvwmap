<?php

include(PLUGINS . 'xplankonverter/model/kvwmap.php');
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'MyObject.php');
include_once(CLASSPATH . 'Layer.php');
include_once(CLASSPATH . 'LayerClass.php');
include_once(CLASSPATH . 'LayerAttribute.php');
include_once(CLASSPATH . 'Style2Class.php');
include_once(CLASSPATH . 'Label2Class.php');
#include_once(CLASSPATH . 'LayerGroup.php');
include_once(CLASSPATH . 'data_import_export.php');
include(PLUGINS . 'xplankonverter/model/gml_file.php');
include(PLUGINS . 'xplankonverter/model/XP_Plan.php');
include(PLUGINS . 'xplankonverter/model/XP_Bereich.php');
include(PLUGINS . 'xplankonverter/model/XP_Object.php');
include(PLUGINS . 'xplankonverter/model/konvertierung.php');
include(PLUGINS . 'xplankonverter/model/regel.php');
include(PLUGINS . 'xplankonverter/model/shapefiles.php');
include(PLUGINS . 'xplankonverter/model/validierung.php');
include(PLUGINS . 'xplankonverter/model/validierungsergebnis.php');
include(PLUGINS . 'xplankonverter/model/xplan.php');
include(PLUGINS . 'xplankonverter/model/extract_gml.php');
include(PLUGINS . 'xplankonverter/model/extract_standard_shp.php');

/**
* Anwendungsfälle
* xplankonverter_download_edited_shapes
* xplankonverter_download_inspire_gml
* xplankonverter_download_uploaded_shapes
* xplankonverter_download_xplan_gml
* xplankonverter_download_xplan_shapes
* xplankonverter_extract_gml_to_form
* xplankonverter_extract_standardshapes_to_regeln
* xplankonverter_gml_generieren
* xplankonverter_go_to_plan
* xplankonverter_inspire_gml_generieren
* xplankonverter_konvertierung
* xplankonverter_konvertierungen_index
* xplankonverter_konvertierung_loeschen
* xplankonverter_konvertierung_status
* xplankonverter_plaene_index
* xplankonverter_regeleditor
* xplankonverter_regeleditor_getshapeattributes
* xplankonverter_regeleditor_getxplanattributes
* xplankonverter_shapefile_loeschen
* xplankonverter_shapefiles_index
* xplankonverter_show_geltungsbereich_upload
* xplankonverter_upload_geltungsbereich
* xplankonverter_upload_xplan_gml
* xplankonverter_validierungsergebnisse
*/

if (stripos($GUI->go, 'xplankonverter_') === 0) {
	function isInStelleAllowed($stelle, $requestStelleId) {
		global $GUI;
		if ($stelle->id == $requestStelleId)
			return true;
		else {
			$GUI->add_message('error', 'Das angefragte Objekt darf nicht in dieser Stelle bearbeitet werden.' . ($requestStelleId != '' ? ' Es gehört zur Stelle mit der ID: ' . $requestStelleId : ''));
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
	* Packt die angegebenen Zip-Dateien im sys_temp_dir Verzeichnis aus
	* und gibt die ausgepackten Dateien in der Struktur von
	* hochgeladenen Dateien aus
	*/
	function extract_uploaded_zip_file($zip_file) {
		$sys_temp_dir = sys_get_temp_dir();
		$extracted_files = array_map(
			function($extracted_file) {
				$path_parts = pathinfo($extracted_file);
				$file = array(
					'basename' => $path_parts['basename'],
					'filename' => $path_parts['filename'],
					'extension' => $path_parts['extension'],
					'tmp_name' => sys_get_temp_dir() . '/' . $extracted_file,
					'unziped' => true
				);
				return $file;
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
		if (
			in_array($file['extension'], array('dbf', 'shx', 'shp')) and # nur Shape files
			substr($file['basename'], 0, 2) != '._' # keine versteckten files
		) {

			$umlaute_mit_diakritic = array('ä','Ä','ü', 'Ü', 'ö', 'Ö');
			$umlaute_ohne_diakritic = array('ä', 'Ä', 'ü', 'Ü', 'ö', 'Ö');
			$file['basename'] = str_replace($umlaute_mit_diakritic, $umlaute_ohne_diakritic, $file['basename']);
			$file['filename'] = str_replace($umlaute_mit_diakritic, $umlaute_ohne_diakritic, $file['filename']);

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

	switch ($GUI->formvars['planart']) {
		case 'BP-Plan' : {
			$GUI->title = 'Bebauungsplan';
			$GUI->plan_class = 'BP_Plan';
			$GUI->plan_layer_id = XPLANKONVERTER_BP_PLAENE_LAYER_ID;
		} break;
		case 'FP-Plan' : {
			$GUI->title = 'Flächennutzungsplan';
			$GUI->plan_class = 'FP_Plan';
			$GUI->plan_layer_id = XPLANKONVERTER_FP_PLAENE_LAYER_ID;
		} break;
		case 'SO-Plan' : {
			$GUI->title = 'Sonstige Plan';
			$GUI->plan_class = 'SO_Plan';
			$GUI->plan_layer_id = XPLANKONVERTER_SO_PLAENE_LAYER_ID;
		} break;
		case 'RP-Plan' : {
			$GUI->title = 'Raumordnungsplan';
			$GUI->plan_class = 'RP_Plan';
			$GUI->plan_layer_id = XPLANKONVERTER_RP_PLAENE_LAYER_ID;
		} break;
		default : {
			$GUI->formvars['planart'] = 'Plan';
			$GUI->plan_class = 'XP_Plan';
			$GUI->title = 'Plan';
			$GUI->plan_layer_id = XPLANKONVERTER_XP_PLAENE_LAYER_ID;
		} break;
	}

	$GUI->plan_table_name = strtolower($GUI->plan_class);
	$GUI->plan_oid_name = $GUI->plan_table_name . '_oid';
}

function go_switch_xplankonverter($go){
	global $GUI;
	switch ($go) {
		case 'xplankonverter_konvertierungen_index' : {
			$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
			$GUI->output();
		} break;

		case 'xplankonverter_plaene_index' : {
			$GUI->title = str_replace('an', 'äne', $GUI->title);
			$GUI->main = '../../plugins/xplankonverter/view/plaene.php';
			$GUI->output();
		} break;

		case 'xplankonverter_plan_edit' : {
			$error = true;
			if ($GUI->formvars['konvertierung_id'] != '') {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if ($GUI->konvertierung->get('id') != '') {
					if (isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
						$GUI->plan = XP_Plan::find_by_id($GUI,'konvertierung_id', $GUI->konvertierung->get('id'), $GUI->konvertierung->get('planart'));
						if ($GUI->plan->get('gml_id') != '') {
							$error = false;
							$GUI->title .= ' bearbeiten';
							$GUI->main = '../../plugins/xplankonverter/view/plan/' . $GUI->formvars['planart'] . '_edit.php';
						}
						else {
							$err_msg = 'Der Plan zur Konvertierung mit dem Namen "' . $GUI->konvertierung->get('bezeichnung') . '" und der ID "' . $GUI->konvertierung->get('id') . '" wurde nicht gefunden!';
						}
					}
				}
				else {
					$err_msg = 'Die Konvertierung mit der ID: ' . $GUI->formvars['konvertierung_id'] . ' wurde nicht gefunden!';
				}
			}
			else {
				$error = false;
				$GUI->title = 'Neuer ' . $GUI->title;
				$GUI->main = '../../plugins/xplankonverter/view/plan/' . $GUI->formvars['planart'] . '_edit.php';
			}
			if ($error) {
				if ($err_msg != '') {
					$GUI->add_message('error', $err_msg);
				}
				$GUI->main = '../../plugins/xplankonverter/view/plaene.php';
			}
			$GUI->output();
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
					$GUI->formvars['Name'] = $gml_class['name'];
					$GUI->formvars['Datentyp'] = $shapeFile->get('datatype');
					$GUI->formvars['Gruppe'] = $layer_group_id;
					$GUI->formvars['pfad'] = 'Select * from ' . $shapeFile->dataTableName() . ' where 1=1';
					$GUI->formvars['Data'] = 'the_geom from (select oid, * from ' .
						$shapeFile->dataSchemaName() . '.' . $shapeFile->dataTableName() .
						' where 1=1) as foo using unique oid using srid=' . $shapeFile->get('epsg_code');
					$GUI->formvars['maintable'] = $shapeFile->dataTableName();
					$GUI->formvars['schema'] = $shapeFile->dataSchemaName();
					$GUI->formvars['connection'] = $GUI->pgdatabase->connect_string;
					$GUI->formvars['connectiontype'] = '6';
					$GUI->formvars['filteritem'] = 'oid';
					$GUI->formvars['tolerance'] = '5';
					$GUI->formvars['toleranceunits'] = 'pixels';
					$GUI->formvars['epsg_code'] = $shapeFile->get('epsg_code');
					$GUI->formvars['querymap'] = '1';
					$GUI->formvars['queryable'] = '1';
					$GUI->formvars['transparency'] = '75';
					$GUI->formvars['postlabelcache'] = '0';
					$GUI->formvars['allstellen'] = '2300';
					$GUI->formvars['ows_srs'] = 'EPSG:' . $shapeFile->get('epsg_code') . ' EPSG:25833 EPSG:4326 EPSG:2398';
					$GUI->formvars['wms_server_version'] = '1.1.0';
					$GUI->formvars['wms_format'] = 'image/png';
					$GUI->formvars['wms_connectiontimeout'] = '60';
					$GUI->formvars['selstellen'] = '1, ' . $GUI->konvertierung->get('stelle_id') . ', 1, ' . $GUI->konvertierung->get('stelle_id');
					$GUI->LayerAnlegen();


					# Ordne layer zur Stelle
					$GUI->Stellenzuweisung(
						array($shapeFile->get('layer_id')),
						array($GUI->konvertierung->get('stelle_id'))
					);

					# Füge eine Klasse zum neuen Layer hinzu.
					$GUI->formvars['class_name'] = 'alle';
					$GUI->formvars['class_id'] = $GUI->Layereditor_KlasseHinzufuegen();

					# Füge einen Style zur Klasse hinzu
					$GUI->add_style();

				}
			}
		#}	end of upload files
		$GUI->main = '../../plugins/xplankonverter/view/shapefiles.php';


			$GUI->output();
		} break;
	*/
		case 'xplankonverter_shapefiles_index': {
			if ($GUI->formvars['konvertierung_id'] == '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
				$GUI->main = 'Hinweis.php';
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if (isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					if (isset($_FILES['shape_files']) and $_FILES['shape_files']['name'][0] != '') {

						$GUI->konvertierung->create_directories();
						$upload_path = $GUI->konvertierung->get_file_path('uploaded_shapes/');

						# unzip and copy files to upload folder
						$uploaded_files = xplankonverter_unzip_and_check_and_copy($_FILES['shape_files'], $upload_path);
						# get layerGroupId or create a group if not exists
						$layer_group_id = $GUI->konvertierung->get('layer_group_id');
						if (empty($layer_group_id))
							$layer_group_id = $GUI->konvertierung->create_layer_group('Shape');
						foreach($uploaded_files AS $uploaded_file) {
							if ($uploaded_file['extension'] == 'dbf' and $uploaded_file['state'] != 'ignoriert') {

								# delete existing shape file
								$shapeFile = new ShapeFile($GUI);
								$shapeFiles = $shapeFile->find_where("
									filename = '" . $uploaded_file['filename'] . "' AND
									konvertierung_id = '" . $GUI->konvertierung->get('id') . "' AND
									stelle_id = " . $GUI->konvertierung->get('stelle_id')
								);

								if (!empty($shapeFiles)) $shapeFile = $shapeFiles[0]; # es kann nur eins geben
								if (!empty($shapeFile->data)) {
									$GUI->debug->show('<p>Lösche gefundenes shape file.', false);
									$shapeFile->deleteLayer();
									$shapeFile->deleteDataTable();
									$shapeFile->delete();
								}

								# create new record in shapefile table
								$shapeFile->create(
									array(
										'filename' => $uploaded_file['filename'],
										'konvertierung_id' => $GUI->konvertierung->get('id'),
										'stelle_id' => $GUI->konvertierung->get('stelle_id'),
										'epsg_code' => $GUI->formvars['epsg_code']
									)
								);

								# Create schema for data table if not exists
								$shapeFile->createDataTableSchema();

								# load into database table
								$result = $shapeFile->loadIntoDataTable();

								if ($result['success']) {
									# add gml_id column if not exists
									if (!$shapeFile->gmlIdColumnExists())
										$shapeFile->addGmlIdColumn();

									# Set datatype for shapefile
									$shapeFile->set('datatype', $result['datatype']);
									$shapeFile->update();

									# create layer
									$GUI->formvars['Name'] = $shapeFile->get('filename');
									$GUI->formvars['Datentyp'] = $shapeFile->get('datatype');
									$GUI->formvars['Gruppe'] = $layer_group_id;
									$GUI->formvars['pfad'] = 'Select * from ' . $shapeFile->dataTableName() . ' where 1=1';
									$GUI->formvars['Data'] = 'the_geom from (select oid, * from ' .
										$shapeFile->dataSchemaName() . '.' . $shapeFile->dataTableName() .
										' where 1=1) as foo using unique oid using srid=' . $shapeFile->get('epsg_code');
									$GUI->formvars['maintable'] = $shapeFile->dataTableName();
									$GUI->formvars['schema'] = $shapeFile->dataSchemaName();
									$GUI->formvars['connection'] = $GUI->pgdatabase->connect_string;
									$GUI->formvars['connectiontype'] = '6';
									$GUI->formvars['filteritem'] = 'oid';
									$GUI->formvars['tolerance'] = '5';
									$GUI->formvars['toleranceunits'] = 'pixels';
									$GUI->formvars['epsg_code'] = $shapeFile->get('epsg_code');
									$GUI->formvars['querymap'] = '1';
									$GUI->formvars['queryable'] = '1';
									$GUI->formvars['transparency'] = '75';
									$GUI->formvars['postlabelcache'] = '0';
									$GUI->formvars['allstellen'] = '2300';
									$GUI->formvars['ows_srs'] = 'EPSG:' . $shapeFile->get('epsg_code') . ' EPSG:25833 EPSG:4326 EPSG:2398';
									$GUI->formvars['wms_server_version'] = '1.1.0';
									$GUI->formvars['wms_format'] = 'image/png';
									$GUI->formvars['wms_connectiontimeout'] = '60';
									$GUI->formvars['selstellen'] = '1, ' . $GUI->konvertierung->get('stelle_id') . ', 1, ' . $GUI->konvertierung->get('stelle_id');
									$GUI->LayerAnlegen();

									# Assign layer_id to shape file record
									$shapeFile->set('layer_id', $GUI->formvars['selected_layer_id']);
									$shapeFile->update();

									# Ordne layer zur Stelle
									$GUI->Stellenzuweisung(
										array($shapeFile->get('layer_id')),
										array($GUI->konvertierung->get('stelle_id'))
									);

									# Füge eine Klasse zum neuen Layer hinzu.
									$GUI->formvars['class_name'] = 'alle';
									$GUI->formvars['class_id'] = $GUI->Layereditor_KlasseHinzufuegen();

									# Füge einen Style zur Klasse hinzu
									$GUI->add_style();
								}
								else {
									$GUI->add_message('error', $result['err_msg']);
								}
							}
						}
					} # end of upload files
					$GUI->main = '../../plugins/xplankonverter/view/shapefiles.php';
				}
			}
			$GUI->output();
		} break;

		case 'xplankonverter_shapefile_loeschen' : {
			if ($GUI->formvars['shapefile_oid'] == '') {
				$response = array(
					'success' => false,
					'type' => 'notice',
					'msg' => 'Diese Seite kann nur aufgerufen werden wenn vorher ein Shape Datei ausgewählt wurde.'
				);
			}
			else {
				$key = 'check;shapefiles;shapefiles;' . $GUI->formvars['shapefile_oid'];
				$GUI->formvars['checkbox_names_' . XPLANKONVERTER_SHAPEFILES_LAYER_ID] = $key;
				$GUI->formvars[$key] = 'on';
				$GUI->formvars['chosen_layer_id'] = XPLANKONVERTER_SHAPEFILES_LAYER_ID;
				$success = $GUI->layer_Datensaetze_loeschen(false);
				$response = array(
					'success' => $success,
					'type' => ($success ? 'notice' : 'error'),
					'msg' => ($success ? 'Shape-Datei erfolgreich gelöscht. ' : $GUI->messages[0]['msg'])
				);
			}
			header('Content-Type: application/json');
			echo json_encode($response);
		} break;

	  case 'xplankonverter_konvertierung_status': {
	    header('Content-Type: application/json');
	    $response = array();
	    if ($GUI->formvars['konvertierung_id'] == '') {
	      $response['success'] = false;
	      $response['msg'] = 'Konvertierung wurde nicht angegeben';
	      return;
	    }
	    if ($GUI->formvars['status'] == '') {
	      $GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn ein Status angegeben ist.';
	      $response['success'] = false;
	      $response['msg'] = 'Status wurde nicht angegeben';
	      echo json_encode($response);
	      return;
	    }
	    // now get konvertierung
	    $GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);

	    // check stelle
	    if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) return;

	    // check applicability of status for external invokation
	    $statusToSet = $GUI->formvars['status'];
	    $isApplicable = false;
	    $applicableStates = array(
	        Konvertierung::$STATUS['IN_ERSTELLUNG'],
	        Konvertierung::$STATUS['IN_KONVERTIERUNG'],
	        Konvertierung::$STATUS['IN_GML_ERSTELLUNG'],
	        Konvertierung::$STATUS['IN_INSPIRE_GML_ERSTELLUNG']
	    );
	    array_walk(
	      $applicableStates,
	      function($pattern) use ($statusToSet,&$isApplicable) {
	        $isApplicable |= $statusToSet == $pattern;
	      }
	    );
	    if (!$isApplicable) {
	      $GUI->Hinweis = "Der Status '$statusToSet' kann nicht explizit gesetzt werden.";
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
	          Konvertierung::$STATUS['GML_ERSTELLUNG_ERR'],
	          Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK'],
	          Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']
	        );
	      break;
	      case Konvertierung::$STATUS['IN_KONVERTIERUNG']:
	        $validPredecessorStates = array(
	          Konvertierung::$STATUS['ERSTELLT'],
	          Konvertierung::$STATUS['KONVERTIERUNG_OK'],
	          Konvertierung::$STATUS['KONVERTIERUNG_ERR'],
	          Konvertierung::$STATUS['GML_ERSTELLUNG_OK'],
	          Konvertierung::$STATUS['GML_ERSTELLUNG_ERR'],
	          Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK'],
	          Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']
	        );
	      break;
	      case Konvertierung::$STATUS['IN_GML_ERSTELLUNG']:
	        $validPredecessorStates = array(
	          Konvertierung::$STATUS['KONVERTIERUNG_OK'],
	          Konvertierung::$STATUS['GML_ERSTELLUNG_OK'],
	          Konvertierung::$STATUS['GML_ERSTELLUNG_ERR'],
	          Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK'],
	          Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']
	        );
	      break;
	      case Konvertierung::$STATUS['IN_INSPIRE_GML_ERSTELLUNG']:
	        $validPredecessorStates = array(
	          Konvertierung::$STATUS['GML_ERSTELLUNG_OK'],
	          Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK'],
	          Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR']
	        );
	      break;
	    }
	    $currStatus = $GUI->konvertierung->get('status');
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
	    $GUI->konvertierung->set('status', $statusToSet);
	    $GUI->konvertierung->update();
	    $response['success'] = true;
	    $response['msg'] = 'Status wurde gesetzt';
	    echo json_encode($response);
	  } break;

		case 'xplankonverter_create_konvertierung_directories' : {
			$konvertierung = new Konvertierung($GUI);
			$konvertierungen = $konvertierung->find_where('1=1');
			Konvertierung::$write_debug = true;
			foreach($konvertierungen AS $k) {
				$k->create_directories();
			}
		} break;

	  case 'xplankonverter_konvertierung': {
			if ($GUI->formvars['konvertierung_id'] == '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
				$GUI->main = 'Hinweis.php';
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					$GUI->Fehlermeldung = "Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
						Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
						Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
						Melden Sie sich mit einem anderen Benutzer an.";
				}
				else {
					$GUI->konvertierung->reset_mapping();
					$GUI->konvertierung->mapping();
	#				$GUI->konvertierung->set_historie();
					$GUI->konvertierung->set_status(
						($GUI->konvertierung->validierung_erfolgreich() ? 'Konvertierung abgeschlossen' : 'Konvertierung abgebrochen')
					);

					# Validierungsergebnisse anzeigen.
					$GUI->main = '../../plugins/xplankonverter/view/validierungsergebnisse.php';
				}
			}
			$GUI->output();
		} break;

		case 'xplankonverter_validierungsergebnisse' : {
			if ($GUI->formvars['konvertierung_id'] == '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
				$GUI->main = 'Hinweis.php';
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					$GUI->Fehlermeldung = "Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
						Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
						Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
						Melden Sie sich mit einem anderen Benutzer an.";
				}
				else {
					# Validierungsergebnisse anzeigen.
					$GUI->main = '../../plugins/xplankonverter/view/validierungsergebnisse.php';
				}
			}
			$GUI->output();
		} break;

		case 'xplankonverter_gml_generieren' : {
			include(PLUGINS . 'xplankonverter/model/build_gml.php');
			include(PLUGINS . 'xplankonverter/model/TypeInfo.php');
			$response = array();
			if ($GUI->formvars['konvertierung_id'] == '') {
				$response['success'] = false;
				$response['msg'] = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if (isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					if ($GUI->konvertierung->get('status') == Konvertierung::$STATUS['KONVERTIERUNG_OK']
					 || $GUI->konvertierung->get('status') == Konvertierung::$STATUS['IN_GML_ERSTELLUNG']
					 || $GUI->konvertierung->get('status') == Konvertierung::$STATUS['GML_ERSTELLUNG_OK']) {
						// Status setzen
						$GUI->konvertierung->set('status', Konvertierung::$STATUS['IN_GML_ERSTELLUNG']);
						$GUI->konvertierung->update();

						// XPlan-GML ausgeben
						$GUI->gml_builder = new Gml_builder($GUI->pgdatabase);
						$plan = XP_Plan::find_by_id($GUI,'konvertierung_id', $GUI->konvertierung->get('id'), $GUI->konvertierung->get('planart'));
						if (!$GUI->gml_builder->build_gml($GUI->konvertierung, $plan)) {
	  					// Status setzen
	  					$GUI->konvertierung->set('status', Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']);
	  					$GUI->konvertierung->update();
	  					// Antwort absenden und case beenden
	  					$response['success'] = false;
	  					$response['msg'] = 'Bei der XPlan-GML-Generierung ist ein Fehler aufgetreten.';
	        		header('Content-Type: application/json');
	        		echo json_encode($response);
	        		break;
						}
						# Creates path if it doesnt exist (e.g. because of gmlas-creation
						if(!file_exists($GUI->konvertierung->get_file_path('xplan_gml'))) {
							echo 'test';
							mkdir($GUI->konvertierung->get_file_path('xplan_gml'), 0777, true);
						}
						$GUI->gml_builder->save($GUI->konvertierung->get_file_name('xplan_gml'));

						// Status setzen
						$GUI->konvertierung->set('status', Konvertierung::$STATUS['GML_ERSTELLUNG_OK']);
						$GUI->konvertierung->update();

						// Erzeuge Layergruppe, falls noch nicht vorhanden
						$layer_group_id = $GUI->konvertierung->create_layer_group('GML');
						// vorhandene Layer dieser Konvertierung löschen
						// Neue Layer von Vorlagen GML kopieren
						/*
						$GUI->formvars['group_id'] = $layer_group_id;
						$GUI->formvars['pg_schema'] = XPLANKONVERTER_CONTENT_SCHEMA;
						$GUI->layer_generator_erzeugen(); # Funktion aus kvwmap.php
						*/
						$response['success'] = true;
						$response['msg'] = 'XPlan-GML-Datei erfolgreich erstellt.';
					} else {
						$response['success'] = false;
						$response['msg'] = 'Die ausgewählte Konvertierung muss zuerst ausgeführt werden.';
					}
				}
			}
			header('Content-Type: application/json');
			echo json_encode($response);
		} break;

		case 'xplankonverter_konvertierung_loeschen' : {
			$GUI->formvars['checkbox_names_' . $GUI->plan_layer_id] = 'check;;' . $GUI->plan_table_name . ';' . $GUI->formvars['plan_oid'];
			$GUI->formvars['check;;' . $GUI->plan_table_name . ';' . $GUI->formvars['plan_oid']] = 'on';
			$GUI->formvars['chosen_layer_id'] = $GUI->plan_layer_id;
			$success = $GUI->layer_Datensaetze_loeschen(false);
			$response = array(
				'success' => $success,
				'type' => ($success ? 'notice' : 'error'),
				'msg' => ($success ? 'Plan und Konvertierung erfolgreich gelöscht. ' : $GUI->messages[0]['msg'])
			);
			header('Content-Type: application/json');
			echo json_encode($response);
		} break;

		case 'xplankonverter_inspire_gml_generieren' : {
			$success = true;
			$konvertierung_id = $GUI->formvars['konvertierung_id'];
			if ($konvertierung_id == '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
				$GUI->main = 'Hinweis.php';
			}

			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
			if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) return;

			# set the paths
			$xsl = PLUGINS . 'xplankonverter/model/xplan2inspire.xsl';
			$fileinput = $GUI->konvertierung->get_file_name('xplan_gml');
			$fileoutput = $GUI->konvertierung->get_file_name('inspire_gml');
			#echo 'test' . $fileinput;

			if (!file_exists($fileinput)) {
				$success = false;
				$msg = 'Die XPlanGML-Datei fehlt. Sie wurde noch nicht erzeugt oder ein Pfad ist falsch.';
				$status =  Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_ERR'];
			}

			if (file_exists($fileoutput)) {
				unlink($fileoutput);
			}

			if ($success) {
		    $GUI->konvertierung->set('status', Konvertierung::$STATUS['IN_INSPIRE_GML_ERSTELLUNG']);
		    $GUI->konvertierung->update();

				# set and run the XSLT-Processor
				$proc = new XsltProcessor;
				$proc->importStylesheet(DOMDocument::load($xsl)); // load script
				$output = $proc->transformToXML(DomDocument::load($fileinput)); // load your file
				file_put_contents($fileoutput, $output, FILE_APPEND | LOCK_EX);
		    $status = Konvertierung::$STATUS['INSPIRE_GML_ERSTELLUNG_OK'];
				$msg = 'Die Konvertierung nach INSPIRE-GML wurde erfolgreich ausgeführt. Sie können die Datei jetzt herunterladen.';
			}

	    $GUI->konvertierung->set('status', $status);
	    $GUI->konvertierung->update();
			$response['success'] = $success;
			$response['msg'] = $msg;

			header('Content-Type: application/json');
			echo json_encode($response);
		} break;

		case 'xplankonverter_regeleditor' : {
			$konvertierung_id = $_REQUEST['konvertierung_id'];
			$bereich_gml_id = $_REQUEST['bereich_gml_id'];
			$class_name = $_REQUEST['class_name'];

			if (empty($konvertierung_id)) {
				if (!empty($bereich_gml_id)) {
					# Hole konvertierung_id über den Bereich
					$bereich = RP_Bereich::find_by_id($GUI, 'gml_id', $bereich_gml_id);
					$plan = $bereich->get_plan();
					$konvertierung_id = $plan->get('konvertierung_id');
				}
			}

			include(PLUGINS . 'xplankonverter/view/regeleditor/index.php');

		} break;

		case 'xplankonverter_regeleditor_getxplanattributes' : {
			$sql = "
			SELECT
				column_name, udt_name, data_type, is_nullable
			FROM
				information_schema.columns
			WHERE
				table_name='" . $GUI->formvars['featuretype'] . "' AND
				table_schema='xplan_gml'
			ORDER BY
				column_name
			";

			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);
			include(PLUGINS . 'xplankonverter/view/regeleditor/getxplanattributes.php');
		} break;

		case 'xplankonverter_regeleditor_getshapeattributes' : {
			// Sets für alle Option in Regeleditor
			$sql = "
				SELECT
					column_name
				FROM
					information_schema.columns
				WHERE
					table_name = '" . $GUI->formvars['shapefile'] . "' AND
					table_schema = 'xplan_shapes_" . $GUI->formvars['konvertierung_id'] . "'
				ORDER BY
					column_name
			";
			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);
			include(PLUGINS . 'xplankonverter/view/regeleditor/getshapeattributes.php');
		} break;

		case 'xplankonverter_regeleditor_getshapeattributes2' : {
			// Sets Wenn dann Option in Regeleditor
			$sql = "
				SELECT
					column_name
				FROM
					information_schema.columns
				WHERE
					table_name = '" . $GUI->formvars['shapefile'] . "' AND
					table_schema = 'xplan_shapes_" . $GUI->formvars['konvertierung_id'] . "'
				ORDER BY
					column_name
			";

			#echo '<br>Sql: ' . $sql;
			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);

			include(PLUGINS . 'xplankonverter/view/regeleditor/getshapeattributes2.php');
		} break;

		case 'xplankonverter_regeleditor_getshapeattributes3' : {
			// Sets WHERE Filter for Shapes in Regeleditor
			$sql = "
				SELECT
					column_name
				FROM
					information_schema.columns
				WHERE
					table_name = '" . $GUI->formvars['shapefile'] . "' AND
					table_schema = 'xplan_shapes_" . $GUI->formvars['konvertierung_id'] . "'
				ORDER BY
					column_name
			";

			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);

			include(PLUGINS . 'xplankonverter/view/regeleditor/getshapeattributes3.php');
		} break;

		case 'xplankonverter_regeleditor_getshapeattributesdistinctvalues' : {
			// Sets DISTINCT value für alle aus Shape
			$sql = "
				SELECT
					DISTINCT " . $GUI->formvars['shapefile_attribut'] . "
				FROM
					xplan_shapes_" . $GUI->formvars['konvertierung_id'] . "." . $GUI->formvars['shapefile'] . "
				ORDER BY
					" . $GUI->formvars['shapefile_attribut'] . "
			";
			#echo '<br>Sql: ' . $sql;
			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);

			include(PLUGINS . 'xplankonverter/view/regeleditor/getshapeattributesdistinctvalues.php');
		} break;

		case 'xplankonverter_regeleditor_getshapeattributesdistinctvalues2' : {
			// Sets DISTINCT value für WHERE Selector
			$sql = "
				SELECT
					DISTINCT " . $GUI->formvars['shapefile_attribut'] . "
				FROM
					xplan_shapes_" . $GUI->formvars['konvertierung_id'] . "." . $GUI->formvars['shapefile'] . "
				ORDER BY
					" . $GUI->formvars['shapefile_attribut'] . "
			";

			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);

			include(PLUGINS . 'xplankonverter/view/regeleditor/getshapeattributesdistinctvalues2.php');
		} break;

		case 'xplankonverter_regeleditor_getxplanenumerationattributes' : {
			//Enumerationsliste Auswahl von Shape
			$sql = "
				SELECT
					udt_name
				FROM
					information_schema.columns
				WHERE
					table_name='" . $GUI->formvars['featuretype'] . "' AND
					column_name = '" . $GUI->formvars['xplanattribut'] . "' AND
					table_schema='xplan_classes'
				ORDER BY
					column_name
			";

			$result = pg_query($GUI->pgdatabase->dbConn, $sql);

			while($row = pg_fetch_row($result)) {
				$enumerationsliste = $row[0];
			}
			# wenn es mit _startet ist es ein array, dann das _ entfernen
			$arrayEnum = bool;
			if(substr($enumerationsliste, 0, 1) === '_') {
				$arrayEnum = true;
			} else {
				$arrayEnum = false;
			}
			$enumerationsliste = ltrim($enumerationsliste, '_');

			$sql = "
				SELECT
					wert,
					beschreibung
				FROM
					xplan_classes. " . $enumerationsliste . "
			";

			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);

			include(PLUGINS . 'xplankonverter/view/regeleditor/getxplanenumerationattributes.php');
		} break;

		case 'xplankonverter_regeleditor_getxplanenumerationattributes2' : {
			//Enumerationsliste Wenn-Dann Auswahl von Shape
			$sql = "
				SELECT
					udt_name
				FROM
					information_schema.columns
				WHERE
					table_name='" . $GUI->formvars['featuretype'] . "' AND
					column_name = '" . $GUI->formvars['xplanattribut'] . "' AND
					table_schema='xplan_classes'
				ORDER BY
					column_name
			";
			#echo '<br>Sql: ' . $sql;

			$result = pg_query($GUI->pgdatabase->dbConn, $sql);

			while($row = pg_fetch_row($result)) {
				$enumerationsliste = $row[0];
			}
			# wenn es mit _startet ist es ein array, dann das _ entfernen
			$arrayEnum = bool;
			if(substr($enumerationsliste, 0, 1) === '_') {
				$arrayEnum = true;
			} else {
				$arrayEnum = false;
			}
			$enumerationsliste = ltrim($enumerationsliste, '_');

			$sql = "
				SELECT
					wert,
					beschreibung
				FROM
					xplan_classes." . $enumerationsliste . "
				";
			#echo '<br>Sql: ' . $sql;
			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);

			include(PLUGINS . 'xplankonverter/view/regeleditor/getxplanenumerationattributes2.php');
		} break;

		#-------------------------------------------------------------------------------------------------------------------------
		# Download cases
		#-------------------------------------------------------------------------------------------------------------------------
		case 'xplankonverter_download_uploaded_shapes' : {
			if ($GUI->xplankonverter_is_case_forbidden()) return;
			if (!$GUI->konvertierung->files_exists('uploaded_shapes')) {
				$GUI->add_message('warning', 'Es sind keine Dateien für den Export vorhanden.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}

			$exportfile = $GUI->konvertierung->create_export_file('uploaded_shapes');
			$GUI->konvertierung->send_export_file($exportfile, 'application/octet-stream');

		} break;

		case 'xplankonverter_download_edited_shapes' : {
			if ($GUI->xplankonverter_is_case_forbidden()) return;

			if (!$GUI->konvertierung->files_exists('edited_shapes')) {
				$GUI->add_message('warning', 'Es sind keine Dateien für den Export vorhanden.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}

			$exportfile = $GUI->konvertierung->create_export_file('edited_shapes');
			$GUI->konvertierung->send_export_file($exportfile, 'application/octet-stream');
		} break;

		case 'xplankonverter_download_xplan_shapes' : {
			if ($GUI->xplankonverter_is_case_forbidden()) return;

			$GUI->konvertierung->create_xplan_shapes();

			if (!$GUI->konvertierung->files_exists('xplan_shapes')) {
				$GUI->add_message('warning', 'Es sind keine Dateien für den Export vorhanden.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}

			$exportfile = $GUI->konvertierung->create_export_file('xplan_shapes');
			$GUI->konvertierung->send_export_file($exportfile, 'application/octet-stream');

		} break;

		case 'xplankonverter_download_xplan_gml' : {
			if ($GUI->xplankonverter_is_case_forbidden()) {
				echo 'Anwendungsfall nicht erlaubt!';
				return;
			}
			$filename = XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/xplan_gml/xplan_' . $GUI->formvars['konvertierung_id'] . '.gml';

			if (!file_exists($filename)) {
				$GUI->add_message('warning', 'Diese Datei ist nicht vorhanden. Prüfen Sie ob die Konvertierung schon korrekt ausgeführt wurde. Wenn ja, wenden Sie sich an den Support.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}
			header('Content-Type: text/xml; subtype="gml/3.3"');
			echo fread(fopen($filename, "r"), filesize($filename));
		} break;

		case 'xplankonverter_download_inspire_gml' : {
			if ($GUI->xplankonverter_is_case_forbidden()) return;

			$filename = XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/inspire_gml/inspire_' . $GUI->formvars['konvertierung_id'] . '.gml';
			header('Content-Type: text/xml; subtype="gml/3.3"');
			echo fread(fopen($filename, "r"), filesize($filename));
		} break;

		case 'xplankonverter_go_to_plan' : {
			# query planart by gml_id
			$sql = "
				SELECT
					k.planart
				FROM
					xplan_gml.xp_plan AS p JOIN
					xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
				WHERE
					p.gml_id = '" . $GUI->formvars['plan_gml_id'] . "'
			";

	    $ret = $GUI->pgdatabase->execSQL($sql,4, 0);

			if ($ret['success']) {
				$rs = pg_fetch_assoc($ret[1]);

				# go to layer search with layer of planart
				switch ($rs['planart']) {
					case 'BP-Plan' : $layer_id = XPLANKONVERTER_BP_PLAENE_LAYER_ID; break;
					case 'FP-Plan' : $layer_id = XPLANKONVERTER_FP_PLAENE_LAYER_ID; break;
					case 'SO-Plan' : $layer_id = XPLANKONVERTER_SO_PLAENE_LAYER_ID; break;
					case 'RP-Plan' : $layer_id = XPLANKONVERTER_RP_PLAENE_LAYER_ID; break;
				}

				$GUI->go = 'Layer-Suche_Suchen';
				$GUI->formvars = array(
					'go' => $GUI->go,
					'selected_layer_id' => $layer_id,
					'operator_plan_gml_id' => '=',
					'value_plan_gml_id' => $GUI->formvars['plan_gml_id'],
				);
			}
			else {
				$GUI->add_message('error', 'Plan konnte nicht gefunden werden. Prüfen Sie bitte die Referenz.');
				$GUI->go = 'get_last_query';
			}

			$GUI->goNotExecutedInPlugins = true;
		} break;

		case 'xplankonverter_show_geltungsbereich_upload' : {
			include('plugins/xplankonverter/view/upload_geltungsbereich.php');
		} break;

		case 'xplankonverter_upload_geltungsbereich' : {
			$upload_file = $_FILES['shape_file'];
			$zip_file = IMAGEPATH . $upload_file['name'];
			$response = array(
				'success' => false
			);
			$importer = new data_import_export();

			if (move_uploaded_file($upload_file['tmp_name'], $zip_file)) {
				# extract zip
				$shape_files = unzip($zip_file, false, false, true);
				# get shape file name
				$first_file = explode('.', $shape_files[0]);
				$shape_file_name = $first_file[0];

				# get EPSG-Code aus prj-Datei
				$epsg = $importer->get_shp_epsg(IMAGEPATH . $shape_file_name, $GUI->pgdatabase);
				if ($epsg == '') {
					$epsg = '25833';
					# ToDo EPSG-Code konnte nicht aus prj-Datei ermittelt werden, Dateiname merken und EPSG-Code nachfragen
				}
				$response['success'] = true;
			}

			# get encoding of dbf file
			$encoding = $importer->getEncoding(IMAGEPATH . $shape_file_name . '.dbf');

			# load shapes to custom schema
			$import_result = $importer->load_shp_into_pgsql($GUI->pgdatabase, IMAGEPATH, $shape_file_name, $epsg, CUSTOM_SHAPE_SCHEMA, 'b' . strtolower(umlaute_umwandeln(substr($shape_file_name, 0, 15))) . rand(1, 1000000), $encoding);

			# return name of import table
			$response['result'] = $import_result[0]['tablename'];

			header('Content-Type: application/json');
			echo json_encode($response);
			return;
		} break;

		case 'xplankonverter_upload_xplan_gml' : {
			$upload_file = $_FILES['gml_file'];
			if ($upload_file != '') {
				Konvertierung::$write_debug = true;
				# TODO check $_FILES['userfile']['type'] == gml or xml else abort (possibly also zip
				# TODO check $_FILES['userfile']['size'] == eg less than 100 MB else abort and message that the file has to be converted piecemail or by an administrator
				$gml_file = IMAGEPATH . $upload_file['name']; # the original filename from the computer of the file-owner
				$response = array(
					'success' => false
				);
				$importer = new data_import_export();

				if (move_uploaded_file($upload_file['tmp_name'], $gml_file . '_' . $GUI->user->id . '.gml')) {
					$response['success'] = true;
				}
				echo $gml_file . '_' . $GUI->user->id . '.gml';

				#$zip_file = IMAGEPATH . $upload_file['name'];
			}
			$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
			$GUI->output();
		} break;

		case 'xplankonverter_extract_gml_to_form' : {
			$GUI->checkCaseAllowed($go);

			if (!isset($_POST['gml_file']) or empty($_POST['gml_file'])) {
				$GUI->add_message('error', 'GML-Datei nicht gefunden. Bitte hochladen.');
				$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
				$GUI->output();
				exit;
			}
			# echo 'File:' . $_POST['gml_file'] . '<br>';
			$gml_location = IMAGEPATH . $_POST['gml_file'] . '_' . $GUI->user->id . '.gml';

			$gml_extractor = new Gml_extractor($GUI->pgdatabase, $gml_location, 'xplan_gmlas_' . $GUI->user->id);
			$gml_extractor->extract_gml_class($GUI->plan_class);

			$GUI->neuer_Layer_Datensatz();
		} break;

		case 'xplankonverter_extract_standardshapes_to_regeln' : {
			//$GUI->checkCaseAllowed($go);
			$bereich_gml_id = $_REQUEST['bereich_gml_id'];
			$konvertierung_id = $_REQUEST['konvertierung_id'];
			$stelle_id = $_REQUEST['stelle_id'];

			$shp_extractor = new Standard_shp_extractor($GUI->pgdatabase,
																									$konvertierung_id,
																									$bereich_gml_id,
																									$stelle_id);
			# Writes all regeln for all standard shapes for the specific konvertierung_id
			# into the xplankonverter.regeln list and associates it with bereich_gml_id
			# Nonstandard shapes should be skipped (if their names differ) or their attributes should be skipped
			# if they differ
			$shp_extractor->create_regeln_for_standard_shps();
		}

		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
}

?>