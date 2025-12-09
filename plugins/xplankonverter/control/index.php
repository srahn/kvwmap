<?php
// Anwendungsfälle
// xplankonverter_check_class_completenesses
// xplankonverter_create_geoweb_service
// xplankonverter_create_metadata
// xplankonverter_create_plaene_from_gmlas
// xplankonverter_download_alte_zusammenzeichnung
// xplankonverter_download_edited_shapes
// xplankonverter_download_inspire_gml
// xplankonverter_download_uploaded_shapes
// xplankonverter_download_uploaded_xplan_gml
// xplankonverter_download_archivdatei
// xplankonverter_download_xplan_gml
// xplankonverter_download_xplan_shapes
// xplankonverter_download_files_query
// xplankonverter_extract_gml_to_form
// xplankonverter_download_xlog
// xplankonverter_extract_standardshapes_to_regeln
// xplankonverter_gml_generieren
// xplankonverter_go_to_plan
// xplankonverter_import_zusammenzeichnung
// xplankonverter_inspire_gml_generieren
// xplankonverter_konvertierung
// xplankonverter_konvertierung_anzeigen
// xplankonverter_konvertierungen_index
// xplankonverter_konvertierung_loeschen
// xplankonverter_konvertierung_status
// xplankonverter_konvertierung_veroffentlichen
// xplankonverter_konvertierung_veroffentlichungsdatum
// xplankonverter_plaene_index
// xplankonverter_regeleditor
// xplankonverter_regeleditor_getshapeattributes
// xplankonverter_regeleditor_getshapeattributes2
// xplankonverter_regeleditor_getshapeattributes3
// xplankonverter_regeleditor_getshapeattributesdistinctvalues
// xplankonverter_regeleditor_getshapeattributesdistinctvalues2
// xplankonverter_regeleditor_getxplanenumerationattributes
// xplankonverter_regeleditor_getxplanenumerationattributes2
// xplankonverter_reindex_gml_ids
// xplankonverter_replace_zusammenzeichnung
// xplankonverter_shapefile_loeschen
// xplankonverter_shapefiles_index
// xplankonverter_show_geltungsbereich_upload
// xplankonverter_upload_geltungsbereich
// xplankonverter_upload_xplan_gml
// xplankonverter_upload_zusammenzeichnung
// xplankonverter_validator_report
// xplankonverter_validierungsergebnisse
// xplankonverter_xplanvalidator
// xplankonverter_zusammenzeichnung

// neuer_Layer_Datensatz must be included for after-triggers in model/kvwmap (which itself includes other classes)
if (strpos($go, '_') !== false AND (strpos($go, 'xplankonverter') !== false || strpos($go, 'neuer_Layer_Datensatz') !== false)) {
	include(PLUGINS . 'xplankonverter/model/kvwmap.php');
	include_once(CLASSPATH . 'PgObject.php');
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
	include(PLUGINS . 'xplankonverter/model/konformitaetsbedingungen.php');
	include(PLUGINS . 'xplankonverter/model/validierungsergebnis.php');
	include(PLUGINS . 'xplankonverter/model/xplan.php');
	include(PLUGINS . 'xplankonverter/model/extract_gml.php');
	include(PLUGINS . 'xplankonverter/model/extract_standard_shp.php');
	include_once(PLUGINS . 'metadata/model/MetaDataCreator.php');
	include_once(PLUGINS . 'metadata/model/metadaten.php');
}

function isInStelleAllowed($stelle, $requestStelleId) {
	global $GUI;
	if ($stelle->id == $requestStelleId) {
		return true;
	}
	else {
		$GUI->add_message('error', 'Das angefragte Objekt darf nicht in dieser Stelle bearbeitet werden.' . ($requestStelleId != '' ? ' Es gehört zur Stelle mit der ID: ' . $requestStelleId : ''));
		return false;
	}
}

if (stripos($GUI->go, 'xplankonverter_') === 0) {
	$GUI->sanitize([
		'konvertierung_id' => 'int'
	]);
	$GUI->formvars['konvertierung_id'] = trim($GUI->formvars['konvertierung_id']);
	$xplankonverter_file_path = XPLANKONVERTER_FILE_PATH . ($GUI->formvars['konvertierung_id'] != '' ? $GUI->formvars['konvertierung_id'] . '/' : '');
	$konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
	if ($konvertierung->data == NULL) {
		$GUI->konvertierung = new Konvertierung($GUI, $GUI->formvars['planart']);
	}
	else {
		if ($konvertierung->get('stelle_id') != Rolle::$stelle_ID) {
			$GUI->add_message('warning', 'Die Konvertierung mit der id ' . $GUI->formvars['konvertierung_id'] . ' gehört nicht zu dieser Stelle!');
			// some functions can also be run from administration, e.g. updating multiple services or metadata
			if (stripos($GUI->go, 'xplankonverter_create_geoweb_service') !== 0 && stripos($GUI->go, 'xplankonverter_create_metadata')  !== 0) {
				$GUI->formvars['konvertierung_id'] = '';
			}
		}
		if (!file_exists($xplankonverter_file_path)) {
			mkdir($xplankonverter_file_path, 0777);
			$GUI->add_message('warning', 'Der Dateipfad ' . $xplankonverter_file_path . ' für die Konvertierung ' . $GUI->formvars['konvertierung_id'] . ' fehlte und musste neu angelegt werden.');
			// return false;
		}
		$GUI->konvertierung = $konvertierung;
	}
	$xplankonverter_logfile = $xplankonverter_file_path . 'xplankonverter.log';
	$GUI->xlog = new LogFile(
		$xplankonverter_logfile,
		'text',
		'xplankonverter',
		'',
		true
	);

	/**
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

	/**
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

	/**
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

	function send_error($msg, $send_notification = XPLANKONVERTER_SEND_NOTIFICATION, $create_ticket = XPLANKONVERTER_CREATE_TICKET) {
		global $GUI;
		$msg_zusatz = '';

		if (
			// empty as ticketbox can be false or ticket box might not exist(variable is not set) for most users
			empty($GUI->formvars['suppress_ticket_and_notification']) AND
			$create_ticket
		) {
			$ticket = create_ticket($msg);
			$msg_zusatz .= "\n\nEs wurde ein Ticket angelegt (" . URL . APPLVERSION . "index.php?go=Layer-Suche_Suchen&selected_layer_id=258&value_id=" . $ticket->get_id() . "&operator_id==) Falls der Fehler durch das Testportal PlanDigital verursacht wurde und nicht in der XPlanGML begründet liegt, werden Sie über den Stand der Behebung informiert.";
		}
		if (
			empty($GUI->formvars['suppress_ticket_and_notification']) AND
			$send_notification
		) {
			# if konvertierung vorhanden
			if ($GUI->konvertierung) {
				$result = $GUI->konvertierung->send_notification("Beim Hochladen {$GUI->konvertierung->config['genitiv']} {$GUI->konvertierung->get('bezeichnung')} id: {$GUI->konvertierung->get_id()} durch den Nutzer {$GUI->user->Vorname} {$GUI->user->Name} in der Stelle {$GUI->Stelle->Bezeichnung} ist ein Fehler aufgetreten.\n {$msg} {$msg_zusatz}");
			}
			else {
				$result = $GUI->xplankonverter_send_notification('Bei der Ausführung des Anwendungsfalls ' . $GUI->go . " durch den Nutzer {$GUI->user->Vorname} {$GUI->user->Name} in der Stelle {$GUI->Stelle->Bezeichnung} ist ein Fehler aufgetreten.\n" . $msg . $msg_zusatz);
			}
			if ($result['success']) {
				$msg_zusatz .= "\n\nDie Mitarbeiter des ARL und der Administrator des PlanDigital-Portals wurden darüber per E-Mail informiert.";
			}
		}

		$GUI->debug->write('send_error: ' . $msg . '<br>' . $msg_zusatz . '<br>' . $result['msg'], 4, false);
		$GUI->write_xlog($msg . '<br>' . $msg_zusatz . '<br>' . $result['msg']);

		header('Content-Type: application/json');
		echo json_encode(array(
			'success' => false,
			'msg' => $msg
		));
	}

	function create_ticket($msg) {
		global $GUI;
		# Erzeuge ein neues Ticket der Kategorie Fehler mit Auftragsart Fehlerkorrektur.
		$pgObj = new PgObject($GUI, 'feedback', 'tickets');
		$ticket = $pgObj->create(array(
			'titel' => 'Fehler beim Upload ' . $GUI->konvertierung->config['genitiv'] . ' ' . ($GUI->konvertierung ? ' ' . $GUI->konvertierung->get_id() : '') . ' in Stelle ' . $GUI->Stelle->id,
			'anfrage' => 'Beim Hochladen ' . $GUI->konvertierung->config['genitiv'] . ' ' . ($GUI->konvertierung ? $GUI->konvertierung->get('bezeichnung') : '') . ($GUI->konvertierung ? ' id: ' . $GUI->konvertierung->get_id() : '') . " ist ein Fehler aufgetreten.\n" . pg_escape_string($msg),
			'kategorie_id' => 3, # Planuploadfehler
			'status_id' => 1, # erstellt
			'dringlichkeit' => 3, # dringlich
			'created_at' => date('Y-m-d H:i:s'), # current date
			'created_from' => $GUI->user->Vorname . ' ' . $GUI->user->Name, # angemeldeter Nutzer
			'created_from_id' => $GUI->user->id,
			'auftragsart_id' => 5, # Fehlerkorrektur
			'verantwortlich_id' => 5, # Support GDI-Service
			'stelle_id' => $GUI->Stelle->id
		));

		return $ticket;
	}

	/*
		ToDo 1 pk:
		Die nachfolgenden Variablen von GUI wurden schon in der konvertierung config gesetzt.
		Diese im nachfolgenden Quellcode durch $GUI->konvertierung->config ersetzten.
		siehe auch ToDo 2 pk in konvertierung.php
	*/
	if (!in_array($GUI->formvars['planart'], array('BP-Plan', 'FP-Plan', 'SO-Plan', 'RP-Plan'))) {
		$GUI->formvars['planart'] = 'Plan';
	}
	$GUI->title = $GUI->konvertierung->config['title'];
	$GUI->plan_title = $GUI->konvertierung->config['plan_title'];
	$GUI->plan_class = $GUI->konvertierung->config['plan_class'];
	$GUI->plan_abk = $GUI->konvertierung->config['plan_abk'];
	$GUI->plan_abk_plural = $GUI->konvertierung->config['plan_abk_plural'];
	$GUI->plan_layer_id = $GUI->konvertierung->config['plan_layer_id'];
	$GUI->plan_table_name = $GUI->konvertierung->config['plan_table_name'];
	$GUI->plan_oid_name = $GUI->konvertierung->config['plan_oid_name'];
	
	//TEMP
	if ($GUI->formvars['planart'] == 'FP-Plan') {
		$GUI->title = 'Flächennutzungsplan';
		$GUI->plan_short_title = 'F-Plan';
		$GUI->plan_class = 'FP_Plan';
		$GUI->plan_abk = 'fplan';
		$GUI->plan_layer_id = XPLANKONVERTER_FP_PLAENE_LAYER_ID;
		$GUI->plan_table_name = 'fp_plan';
		
		$GUI->plan_table_name = strtolower($GUI->plan_class);
		$GUI->plan_oid_name = $GUI->plan_table_name . '_oid';
	}
	// TEMP END
}

function go_switch_xplankonverter($go) {
	global $GUI;
	global $admin_stellen;
	$GUI->write_xlog('go=' . $go . ' ' . ($GUI->formvars['konvertierung_id'] ? ' (konvertierung_id: ' . $GUI->formvars['konvertierung_id'] . ')' : ''));
	switch ($go) {
		/**
		 * Check if objects falling into defined classes
		 * @return integer 'The number of objects that fall not into defined classes' 
		 */
		case 'xplankonverter_check_class_completenesses' : {
			header('Content-Type: application/json');
			$success = false;
			$msg = [];

			$konvertierung_id = $GUI->formvars['konvertierung_id'];

			# Prüfen ob konvertierung_id angegeben wurde und der upload in der Stelle erlaubt ist
			if ($konvertierung_id == '') {
				send_error('Fehler bei der Überprüfung der Vollständigkeit der Klassifizierung!<p>Keine Konvertierung-ID angegeben.', false, false);
				break;
			}

			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);
			if ($GUI->konvertierung->get('id') == '') {
				send_error("Die Konvertierung mit id: ${konvertierung_id} konnte in der Datenbank nicht gefunden werden.");
				break;
			}

			if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
				send_error("Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
					Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
					Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
					Melden Sie sich mit einem anderen Benutzer an."
				);
				break;
			}

			if ($GUI->konvertierung->plan) {
				$result = $GUI->konvertierung->plan->get_layers_with_content(
					$GUI->xplankonverter_get_xplan_layers($GUI->konvertierung->get('planart')),
					$GUI->konvertierung->get_id()
				);
				if (! $result['success']) {
					send_error('Fehler bei der Abfrage der Planlayer mit Inhalten!<br>' . $result['msg']);
					break;
				}

				$layers_with_content = $result['layers_with_content'];
				$mapDB = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
				$num_unclassified = 0;
				foreach ($layers_with_content AS $layer) {
					$GUI->formvars['layer_id'] = $layer['id'];
					$result = $GUI->check_class_completeness();
					if (! $result['success']) {
						send_error('Fehler bei der Überprüfung der Vollständigkeit der Klassifizierung der Layer!<br>' . $result['msg']);
						break;
					}
					$num_unclassified += $result['num_unclassified'];
				}
				if (!$result['success']) {
					break;
				}
				if ($num_unclassified == 0) {
					$msg = 'Alle Objekte konnten bekannten Planzeichen zugeordnet werden!';
				}
				else {
					if ($num_unclassified == 1) {
						$msg = 'Es wurde ein Objekt gefunden, welches keinem Planzeichen zugeordnet werden konnte.';
					}
					else {
						$msg = 'Es wurden ' . $num_unclassified . ' Objekte gefunden, die keinem Planzeichen zugeordnet werden konnten.<br>Rufen Sie den Plan auf, klappen den Abschnitt "Planzeichen (Objektklassen)" auf und klicken auf "Lade Objektklassen". Dort werden die Klassen angezeigt, die Objekte ohne Zuordnung enthalten und ein Link zu den Objekten. Nehmen Sie Kontakt auf mit Ihrem Dienstleister um die fehlenden Klassen zu ergänzen oder bestehende so anzupassen, dass die Objekte fachlich korrekt zugeordnet werden können.';
					}

					if (empty($GUI->formvars['suppress_ticket_and_notification'])) {
						$GUI->create_ticket($msg);
						$result = $GUI->konvertierung->send_notification('Hinweis zur Zusammenzeichnung ' . $GUI->konvertierung->get('bezeichnung') . ' id: ' . $GUI->konvertierung->get_id() . $msg);
						if (! $result['success']) {
							send_error('Fehler bei der Benachrichtigung über das Fehlen von Planzeichenklassen!');
						}
						$msg .= '<br>Der Support wurde benachrichtigt und ein Ticket angelegt um fehlende Klassen und Planzeichen anzulegen. Bis dahin werden die Objekte mit Default-Styles in den Diensten angezeigt. Sie werden ggf. angefragt bei der Ausgestaltung der fehlenden Planzeichenstyles behilflich zu sein. Sie werden schließlich informiert wenn die fehlenden Planzeichen angelegt wurden.';
					}
				}
			}
			else {
				send_error('Bei der Überprüfung der Vollständigkeit der Klassifizierung der Layer konnte zur Konvertierung ID: ' . $GUI->konvertierung->get_id() . ' kein Plan gefunden werden!');
				break;
			}

			$result = array(
				'success' => true,
				'msg' => $msg,
				'num_unclassified' => $num_unclassified
			);
			echo json_encode($result);
		} break;

		case 'xplankonverter_show_class_completenesses' : {
			header('Content-Type: application/json');
			$success = false;
			$msg = [];

			$konvertierung_id = $GUI->formvars['konvertierung_id'];

			# Prüfen ob konvertierung_id angegeben wurde und der upload in der Stelle erlaubt ist
			if ($konvertierung_id == '') {
				send_error('Fehler bei der Überprüfung der Vollständigkeit der Klassifizierung!<p>Keine Konvertierung-ID angegeben.', false, false);
				break;
			}

			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);
			if ($GUI->konvertierung->get('id') == '') {
				send_error("Die Konvertierung mit id: ${konvertierung_id} konnte in der Datenbank nicht gefunden werden.");
				break;
			}

			if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
				send_error("Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
					Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
					Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
					Melden Sie sich mit einem anderen Benutzer an."
				);
				break;
			}

			if ($GUI->konvertierung->plan) {
				$result = $GUI->konvertierung->plan->get_layers_with_content(
					$GUI->xplankonverter_get_xplan_layers($GUI->konvertierung->get('planart')),
					$GUI->konvertierung->get_id()
				);
				if (! $result['success']) {
					send_error('Fehler bei der Abfrage der Planlayer mit Inhalten!<br>' . $result['msg']);
					break;
				}

				$layers_with_content = $result['layers_with_content'];
				$mapDB = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
				foreach($layers_with_content AS $layer) {
					$class_completeness_result .= '<a target="_blank" href="#" onclick="$(\'#class_expressions_layer_' . $layer['id'] . '\').toggle();event.preventDefault();">Layer: ' . layer_name_with_alias($layer['name'], $layer['alias'], array('alias_first' => true, 'brace_type' => 'round')) . '</a><br>';
					$GUI->formvars['layer_id'] = $layer['id'];
					$classes = $mapDB->read_Classes($layer['id']);
					$class_completeness_result .= '<table id="class_expressions_layer_' . $layer['id'] . '" style="display: none">
						<th class="class-th" style="width: 30%">Klasse</th>
						<th class="class-th" style="width: 70%">Definition</th>' . implode(
							'',
							array_map(
								function($class) {
									return '
										<tr>
											<td class="class-td">' . $class['name'] . '</td>
											<td class="class-td">' . $class['expression'] . '</td>
										</tr>
									';
								},
								$classes
							)
						) . '
					</table>';
					$result = $GUI->check_class_completeness();
					$class_completeness_result .= $result['html'];
				}
			}
			echo $class_completeness_result;
		} break;

		/**
		 * Erzeugt die Metadatendokumente für einen einzelnen Plan oder für
		 * den Geodatensatz, Darstellungs- und Downloaddienst, der alle Pläne enthält
		 * je nach dem ob eine Konvertierung-ID übergeben wurde oder nicht
		 */
		case 'xplankonverter_create_metadata' : {
			$creatorInfos = array();
			$GUI->sanitize([
				'konvertierung_id' => 'int',
				'planart' => 'text'
			]);
			$konvertierung_id = $GUI->formvars['konvertierung_id'];
			$md = new metadata($GUI);
			if ($konvertierung_id == '') {
				if ($GUI->formvars['planart'] == 'Plan') {
					send_error('Fehler beim Erzeugen der Metadaten!<p>Wenn Keine Konvertierung-ID angegeben ist, muss mindestens die planart angegeben sein.', false, false);
					break;
				}
				# Erzeugt Metadatendokumente für alle Pläne im Schema xplan_gml
				$metadata_documents = $GUI->xplankonverter_create_metadata_documents($md);
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);
				if (
					$GUI->Stelle->id != $GUI->konvertierung->get('stelle_id') AND
					in_array($GUI->Stelle->id, $admin_stellen)
				) {

					# Wechsel zur Stelle der Konvertierung
					$start_stelle_id = $GUI->Stelle->id;
					$GUI->Stelle->id = $GUI->konvertierung->get('stelle_id');
					$GUI->Stelle->readDefaultValues();
				}
				if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					send_error("Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
						Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
						Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
						Melden Sie sich mit einem anderen Benutzer an."
					);
					break;
				}

				/* 
				* Updates letzte_aktualisierung in gemeindeverbaende-Tabelle, so ARL's can track these dates (date is also manually editable)
				* As the date will also end up in the metadata, it needs to be set before the metadata is created/updated
				*/
				if ($GUI->formvars['suppress_gvbtable_letzteaktualisierung_update'] == false ||
				$GUI->formvars['suppress_gvbtable_letzteaktualisierung_update'] == 'false') {
					$GUI->write_xlog('Update des Aktualisierungsdatums in gebietseinheiten-Tabelle.');
					$GUI->debug->write('Setze Datum Letze Aktualisierung in Gemeindeverbände-Tabelle.');
					$ret_akt = $GUI->konvertierung->update_letztes_aktualisierungsdatum_gebietstabelle();
					
					if (!$ret_akt['success']) {
						$GUI->write_xlog('Fehler beim Update der Datums letzte Aktualisierung in Gebietseinheiten-Tabelle!');
						$GUI->add_message('Fehler', 'Fehler beim Update der Datums letzte Aktualisierung in Gebietseinheiten-Tabelle!');
					}
				}

				# Erzeugt die Metadatendokumente für einen einzelnen Plan
				$metadata_documents = $GUI->konvertierung->create_metadata_documents($md);
			}

			$result = $GUI->metadata_upload_to_geonetwork($metadata_documents['metaDataGeodatensatz']);
			if (! $result['success']) {
				send_error('Erstellung des Metadatendokumentes für den Geodatensatz '. $result['msg']);
				break;
			}
			$creatorInfos['datensatz'] = $result['metadataInfos'][0];

			$result = $GUI->metadata_upload_to_geonetwork($metadata_documents['metaDataDownload']);
			if (! $result['success']) {
				send_error('Erstellung des Metadatendokumentes für den Downloaddienst '. $result['msg']);
				break;
			}
			$creatorInfos['download'] = $result['metadataInfos'][0];

			$result = $GUI->metadata_upload_to_geonetwork($metadata_documents['metaDataView']);
			if (! $result['success']) {
				send_error('Erstellung des Metadatendokumentes für den Darstellungsdienst '. $result['msg']);
				break;
			}
			$creatorInfos['view'] = $result['metadataInfos'][0];

			$result = array(
				'success' => true,
				'msg' => 'Metadaten über Daten und Dienste erfolgreich in das Metainformationssystem hochgeladen.',
				'infos' => $creatorInfos
			);

			$GUI->Stelle_ID = $start_stelle_id; // setze Stelle_ID zurück auf die ID der Stelle die diese Funktion aufgerufen hat.

			header('Content-Type: application/json');
			echo json_encode($result);
		} break;

		case 'xplankonverter_konvertierungen_index' : {
			$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
			$GUI->output();
		} break;

		case 'xplankonverter_plaene_index' : {
			$landkreis = new PgObject($GUI, 'gebietseinheiten', 'kreise', 'krs_schl', 'string');
			$GUI->landkreise = $landkreis->find_by_sql(array('select' => 'krs_schl, krs_name', 'order' => 'krs_name'));
			$GUI->title = str_replace('an', 'äne', $GUI->title);
			$GUI->title = str_replace('Sonstiger', 'Sonstige', $GUI->title);
			$GUI->main = '../../plugins/xplankonverter/view/plaene.php';
			$GUI->output();
		} break;

		case 'xplankonverter_plan_edit' : {
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
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
					$GUI->formvars['name'] = $gml_class['name'];
					$GUI->formvars['datentyp'] = $shapeFile->get('datatype');
					$GUI->formvars['gruppe'] = $layer_group_id;
					$GUI->formvars['pfad'] = 'Select * from ' . $shapeFile->dataTableName() . ' where 1=1';
					$GUI->formvars['data'] = 'the_geom from (select oid, * from ' .
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
					$GUI->addLayersToStellen(
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
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
			if ($GUI->formvars['konvertierung_id'] == '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
				$GUI->main = 'Hinweis.php';
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if (isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					if (isset($_FILES['shape_files']) and $_FILES['shape_files']['name'][0] != '') {

						$GUI->konvertierung->create_directories();
						$upload_path = $GUI->konvertierung->get_file_path('uploaded_shapes');

						# unzip and copy files to upload folder
						$uploaded_files = xplankonverter_unzip_and_check_and_copy($_FILES['shape_files'], $upload_path);
						if (XPLANKONVERTER_CREATE_UPLOAD_SHAPE_LAYER) {
							# get layerGroupId or create a group if not exists
							$layer_group_id = $GUI->konvertierung->get('layer_group_id');
							if (empty($layer_group_id)) {
								$layer_group_id = $GUI->konvertierung->create_layer_group('Shape');
							}
						}

						foreach ($uploaded_files AS $uploaded_file) {
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
									if (XPLANKONVERTER_CREATE_UPLOAD_SHAPE_LAYER) {
										echo '<p>create Layer';
										# create layer
										$GUI->formvars['name'] = $shapeFile->get('filename');
										$GUI->formvars['datentyp'] = $shapeFile->get('datatype');
										$GUI->formvars['gruppe'] = $layer_group_id;
										$GUI->formvars['pfad'] = 'Select * from ' . $shapeFile->dataTableName() . ' where 1=1';
										$GUI->formvars['data'] = 'the_geom from (
											SELECT *
											FROM ' . $shapeFile->dataSchemaName() . '.' . $shapeFile->dataTableName() . '
											WHERE 1=1
										) as foo using unique gid using srid=' . $shapeFile->get('epsg_code');
										$GUI->formvars['maintable'] = $shapeFile->dataTableName();
										$GUI->formvars['schema'] = $shapeFile->dataSchemaName();
										$GUI->formvars['oid'] = 'gid';
										$GUI->formvars['connection'] = $GUI->pgdatabase->connect_string;
										if ($GUI->pgdatabase->connection_id != '') {
											$GUI->formvars['connection_id'] = $GUI->pgdatabase->connection_id;
										}
										$GUI->formvars['connectiontype'] = '6';
										$GUI->formvars['filteritem'] = 'gid';
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
										$GUI->addLayersToStellen(
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
					'msg' => ($success ? 'Shape-Datei erfolgreich gelöscht. ' : GUI::$messages[0]['msg'])
				);
			}
			header('Content-Type: application/json');
			echo json_encode($response);
		} break;

		case 'xplankonverter_konvertierung_status': {
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
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
						return $isValid || ($predStatus == $currStatus);
					}, $isValid
			);
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
	
		case 'xplankonverter_konvertierung_veroffentlichen': {
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
			header('Content-Type: application/json');
			$response = array();
			if ($GUI->formvars['konvertierung_id'] == '') {
				$response['success'] = false;
				$response['msg'] = 'Konvertierung wurde nicht angegeben';
				return;
			}
			if (!in_array($GUI->formvars['veroeffentlicht'], array('t', 'f'))) {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn das Attribut veroeffentlicht einen Wert t oder f hat.';
				$response['success'] = false;
				$response['msg'] = 'Attribut veroeffentlicht muss t oder f sein. Statt dessen ist es -' . print_r($GUI->formvars, true) . '-';
				echo json_encode($response);
				return;
			}
			// now get konvertierung
			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);

			// check stelle
			if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) return;

			$GUI->konvertierung->data = array(
				"id" => $GUI->formvars['konvertierung_id'],
				"veroeffentlicht" => $GUI->formvars['veroeffentlicht']
			);
			$GUI->konvertierung->update();
			$response['success'] = true;
			$response['veroeffentlicht'] = $GUI->formvars['veroeffentlicht'] == 't' ? 'Ja' : 'Nein';
			$response['konvertierung_id'] = $GUI->formvars['konvertierung_id'];
			echo json_encode($response);
			return;
		} break;

		case 'xplankonverter_konvertierung_veroffentlichungsdatum': {
			header('Content-Type: application/json');
			$response = array();
			if ($GUI->formvars['konvertierung_id'] == '') {
				$response['success'] = false;
				$response['msg'] = 'Konvertierung wurde nicht angegeben';
				return;
			}
			// now get konvertierung
			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);

			// check stelle
			if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) return;

			$stelle = new PgObject($GUI, 'kvwmap', 'stelle');
			$validation_msg = $stelle->validate_date_format($GUI->formvars['veroeffentlichungsdatum'], 'Y-M-D');
			if ($validation_msg != '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn das Attribut Veroeffentlichungsdatum leer ist oder einen gültigen Datumswert hat.';
				$response['success'] = false;
				$response['msg'] = 'Attribut veroeffentlicht muss leer sein oder ein gültiges Datum haben.' . $validation_msg;
				echo json_encode($response);
				return;
			}

			$GUI->konvertierung->data = array(
				"id" => $GUI->formvars['konvertierung_id'],
				"veroeffentlichungsdatum" => $GUI->formvars['veroeffentlichungsdatum']
			);
			
			$GUI->konvertierung->update();
			$response['success'] = true;
			$response['veroeffentlichungsdatum'] = $GUI->formvars['veroeffentlichungsdatum'];
			$response['konvertierung_id'] = $GUI->formvars['konvertierung_id'];
			echo json_encode($response);
			return;
		} break;

		case 'xplankonverter_create_konvertierung_directories' : {
			$konvertierung = new Konvertierung($GUI, $GUI->formvars['planart']);
			$konvertierungen = $konvertierung->find_where('1=1');
			Konvertierung::$write_debug = true;
			foreach($konvertierungen AS $k) {
				$k->create_directories();
			}
		} break;

		case 'xplankonverter_konvertierung': {
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
			$GUI->title = str_replace('an', 'äne', $GUI->title);
			$GUI->main = '../../plugins/xplankonverter/view/plaene.php';
			if ($GUI->formvars['konvertierung_id'] == '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
				$GUI->main = 'Hinweis.php';
				$GUI->data = array(
					'success' => false,
					'msg' => $GUI->Hinweis
				);
				$GUI->output(); break;
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if ($GUI->konvertierung->data === false) {
					$GUI->Fehlermeldung = "Die Konvertierung mit der ID={$GUI->formvars['konvertierung_id']} wurde nicht gefunden.";
					$GUI->data = array(
						'success' => false,
						'msg' => $GUI->Fehlermeldung
					);
					$GUI->output(); break;
				}
				if ($GUI->konvertierung->plan === false) {
					$GUI->Fehlermeldung = "Zur Konvertierung mit der ID={$GUI->formvars['konvertierung_id']} wurde keinen Plan der Planart {$GUI->konvertierung->get('planart')} gefunden!";
					$GUI->data = array(
						'success' => false,
						'msg' => $GUI->Fehlermeldung
					);
					$GUI->output(); break;
				}
				if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					$GUI->Fehlermeldung = "Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
						Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
						Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
						Melden Sie sich mit einem anderen Benutzer an.";
					$GUI->data = array(
						'success' => false,
						'msg' => $GUI->Fehlermeldung
					);
					$GUI->output(); break;
				}
				else {
					try {
						$GUI->konvertierung->reset_mapping();
						$GUI->konvertierung->mapping();
						#$GUI->konvertierung->set_historie();
						$GUI->konvertierung->set_status(
							($GUI->konvertierung->validierung_erfolgreich() ? 'Konvertierung abgeschlossen' : 'Konvertierung abgebrochen')
						);
						# Validierungsergebnisse anzeigen.
						$GUI->main = '../../plugins/xplankonverter/view/validierungsergebnisse.php';
					}
					catch (Exception $e) {
						send_error($e->getMessage());
						break;
					}
				}
			}
			if ($GUI->formvars['format'] === 'json_result') {
				header('Content-Type: application/json');
				if ($GUI->konvertierung->validierung_erfolgreich()) {
					$response = array(
						'success' => true,
						'msg' => $GUI->konvertierung->config['akkusativ'] . ' erfolgreich in Ziel-Version konvertiert.'
					);
				}
				else {
					send_error('Die Validierung war nicht erfolgreich. Validierungsergebnisse <a href="index.php?go=xplankonverter_validierungsergebnisse&konvertierung_id=' . $GUI->formvars['konvertierung_id'] . '">anzeigen</a> Die Nachricht wird gesendet an: ' . $GUI->konvertierung->get_arl_email());
					break;
				}
				echo json_encode($response);
			}
			else {
				$GUI->output();
			}
		} break;

		case 'xplankonverter_xplankonverter_report' : {
			if ($GUI->formvars['konvertierung_id'] == '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
				$GUI->main = 'Hinweis.php';
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					$GUI->Hinweis = "Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
						Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
						Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
						Melden Sie sich mit einem anderen Benutzer an.";
					$GUI->main = 'Hinweis.php';
				}
				else {
					# XPlankonverter Ergebnisse anzeigen.
					$GUI->main = '../../plugins/xplankonverter/view/xplanvalidator_report.php';
				}
			}
			$GUI->output();
		} break;

		/**
		* Erzeugt die Map-Datei für die Dienste eines einzelnen Planes oder für
		* den Geodatensatz, Darstellungs- und Downloaddienst, der alle Pläne enthält
		* je nach dem ob eine Konvertierung-ID übergeben wurde oder nicht
		*/
		case 'xplankonverter_create_geoweb_service' : {
			$GUI->write_xlog('case xplankonverter_create_geoweb_service gestartet.');
			$GUI->data = array(
				'success' => true,
				'msg' => 'Landesdienst erfolgreich angelegt.'
			);
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
			$konvertierung_id = $GUI->formvars['konvertierung_id'];
			if ($konvertierung_id == '' AND $GUI->formvars['planart'] == 'Plan') {
				send_error('Fehler beim Erzeugen des GeoWeb-Dienstes!<p>Wenn Keine Konvertierung-ID angegeben ist, muss mindestens die planart angegeben sein.', false, false);
				break;
			}

			$GUI->xplan_layers = $GUI->xplankonverter_get_xplan_layers($GUI->formvars['planart']);
			if ($konvertierung_id == '') {
				$GUI->write_xlog('Erzeugt den Geowebservice für alle Pläne im Schema xplan_gml');

				$result = $GUI->xplankonverter_create_geoweb_service($GUI->xplan_layers, OWS_SERVICE_ONLINERESOURCE . $GUI->plan_abk_plural);
				if (! $result['success']) {
					$msg = 'Fehler beim Erzeugen des Map-Objektes, welches alle Layer des Dienstes enthält.' . $result['msg'];
					$GUI->write_xlog('error: ' . $msg);
					$GUI->data = array(
						'success' => false,
						'msg' => $msg
					);
					$GUI->add_message('error', $msg);
				}
				else {
					$result = $GUI->write_mapfile($result['mapfile'], $GUI->plan_abk_plural);
					if (!$result['success']) {
						$GUI->write_xlog('error: ' . $result['msg']);
						$GUI->data = $result;
						$GUI->add_message('error', $result['msg']);
					}
					$GUI->write_xlog('mapfile: ' . $result['mapfile'] . ' geschrieben.');
				}
				$GUI->main = '../../plugins/xplankonverter/view/show_service_data.php';
				$GUI->output();
				exit;
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);
				if (
					$GUI->Stelle->id != $GUI->konvertierung->get('stelle_id') AND
					in_array($GUI->Stelle->id, $admin_stellen)
				) {
					# Wechsel zur Stelle der Konvertierung
					$start_stelle_id = $GUI->Stelle->id;
					$GUI->Stelle->id = $GUI->konvertierung->get('stelle_id');
					$GUI->Stelle->readDefaultValues();
				}
				if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					send_error("Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
						Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
						Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
						Melden Sie sich mit einem anderen Benutzer an."
					);
					break;
				}

				$GUI->write_xlog('Erzeugt den Geowebservice für den Plan "' . $GUI->konvertierung->plan->get('name') . '" der Stelle ' . $GUI->Stelle->id);
				$result_create_geoweb_service = $GUI->konvertierung->create_geoweb_service($GUI->xplan_layers, OWS_SERVICE_ONLINERESOURCE . $GUI->Stelle->id . '/' . $GUI->plan_abk);

				if (!$result_create_geoweb_service['success']) {
					send_error('Fehler beim Erzeugen des Map-Objektes, welches die Layer des Dienstes der Stelle enthält. ' . $result_create_geoweb_service['msg']);
					break;
				}
				$result_write_mapfile = $GUI->write_mapfile($result_create_geoweb_service['mapfile'], $GUI->Stelle->id . '/' . $GUI->plan_abk);
	
				$GUI->Stelle_ID = $start_stelle_id; // setze Stelle_ID zurück auf die ID der Stelle die diese Funktion aufgerufen hat.

				if (!$result_write_mapfile['success']) {
					send_error($result_write_mapfile['msg']);
					break;
				}
			}
	
			header('Content-Type: application/json');
			$response = array(
				'success' => true,
				'msg' => 'Map-Datei ' . WMS_MAPFILE_PATH . $result_create_geoweb_service['mapfile'] . ' erfolgreich geschrieben.',
				'geoweb_service_updated_at' => $result_create_geoweb_service['geoweb_service_updated_at']
			);
			echo json_encode($response);
		} break;

		/**
		* Variante mit json response
		*/
		case 'xplankonverter_validierungsergebnisse' : {
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
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

		/**
		* Case erzeugt xplan_gml-Dateien von Konvertierungen und liefert ein JSON mit success und msg zurück
		* In folgenden Fällen wird keine Datei erzeugt, success = false und in msg eine Fehlermeldung geliefert:
		* - Wenn alle_veroeffentlichen angegeben ist aber user nicht die Funktion admin hat
		* - Wenn alle_veroeffentlichen und konvertierung_id beide leer sind
		* - Wenn alle_veroeffentlichen leer ist aber keine Konvertierung mit konvertierung_id gefunden werden konnte.
		* - Wenn alle_veroeffentlichen leer ist aber die gefundene Konvertierung nicht die gleiche Stellen ID hat wie die aktuelle Stelle
		* - Wenn alle_veroeffentlichen angegeben ist aber keine Konvertierungen gefunden wurden
		* - Wenn der Status der Konvertierung vorher nicht einen der folgenden Werte hat: ERSTELLT, KONVERTIERUNG_OK, IN_GML_ERSTELLUNG, GML_ERSTELLUNG_OK
		*/
		case 'xplankonverter_gml_generieren' : {
			# Hier weiter machen mit Bug 'Fehler beim Start der GML-Generierung
			include(PLUGINS . 'xplankonverter/model/build_gml.php');
			include(PLUGINS . 'xplankonverter/model/TypeInfo.php');
			$success = true;
			$messages = array();
			$konvertierung_id = $GUI->formvars['konvertierung_id'];
			header('Content-Type: application/json');

			if ($GUI->formvars['alle_veroeffentlichten'] AND $GUI->user->funktion != 'admin') {
				echo json_encode(
					array(
						'success' => false,
						'msg' => 'Die Erzeugung von XPlanGML für alle veröffentlichten Konvertierungen ist nur für einen Nutzer mit der Funktion admin erlaubt!'
					)
				);
				break;
			}

			if ($GUI->formvars['alle_veroeffentlichten'] == '' AND $konvertierung_id == '') {
				echo json_encode(
					array(
						'success' => false,
						'msg' => 'Diese Erzeugung von XPlanGML für eine Konvertierung kann nur aufgerufen werden wenn eine Konvertierung Id angegeben wurde!'
					)
				);
				break;
			}

			$konvertierungen = Konvertierung::find_where_with_plan($GUI, $GUI->formvars['alle_veroeffentlichten'] ? 'veroeffentlicht' : 'id = ' . $konvertierung_id, 'id');

			if ($GUI->formvars['alle_veroeffentlichten'] == '' AND count($konvertierungen) == 0) {
				echo json_encode(
					array(
						'success' => false,
						'msg' => 'Zur Id: ' . $konvertierung_id . ' konnte keine Konvertierung gefunden werden!'
					)
				);
				break;
			}

			if ($GUI->formvars['alle_veroeffentlichten'] == '' AND !isInStelleAllowed($GUI->Stelle, $konvertierungen[0]->get('stelle_id'))) {
				echo json_encode(
					array(
						'success' => false,
						'msg' => 'Von Konvertierung ' . $konvertierung_id . ' kann nur in Stelle ' . $konvertierungen[0]->get('stelle_id') . ' eine XPlanGML-Datei erstellt werden! Sie sind in Stelle ist ' . $GUI->Stelle->id . '!'
					)
				);
				break;
			}

			if ($GUI->formvars['alle_veroeffentlichten'] != '' AND count($konvertierungen) == 0) {
				echo json_encode(
					array(
						'success' => false,
						'msg' => 'Es konnten keine veröffentlichten Pläne gefunden werden!'
					)
				);
				break;
			}

			foreach ($konvertierungen AS $konvertierung) {
				if (in_array($konvertierung->get('status'), array(
					Konvertierung::$STATUS['ERSTELLT'],
					Konvertierung::$STATUS['KONVERTIERUNG_OK'],
					Konvertierung::$STATUS['IN_GML_ERSTELLUNG'],
					Konvertierung::$STATUS['GML_ERSTELLUNG_OK']
				))) {
					// Status setzen
					$konvertierung->set('status', Konvertierung::$STATUS['IN_GML_ERSTELLUNG']);
					$konvertierung->update();

					// XPlan-GML ausgeben
					$GUI->gml_builder = new Gml_builder($GUI->pgdatabase);
					$plan = XP_Plan::find_by_id($GUI,'konvertierung_id', $konvertierung->get('id'), $konvertierung->get('planart'));
					if (!$GUI->gml_builder->build_gml($konvertierung, $plan)) {
						// Status setzen
						$konvertierung->set('status', Konvertierung::$STATUS['GML_ERSTELLUNG_ERR']);
						$konvertierung->update();
						// Antwort absenden und case beenden
						$success = false;
						$messages[] = 'Bei der XPlan-GML-Generierung ist ein Fehler aufgetreten.';
					}
					# Creates path if it doesnt exist (e.g. because of gmlas-creation
					if (!file_exists($konvertierung->get_file_path('xplan_gml'))) {
						mkdir($konvertierung->get_file_path('xplan_gml'), 0777, true);
					}

					$GUI->gml_builder->save($konvertierung->get_file_name('xplan_gml'));

					// Status setzen
					$konvertierung->set('status', Konvertierung::$STATUS['GML_ERSTELLUNG_OK']);
					$konvertierung->update();

					// Erzeuge Layergruppe, falls noch nicht vorhanden
					$layer_group_id = $konvertierung->create_layer_group('GML');
					// vorhandene Layer dieser Konvertierung löschen
					// Neue Layer von Vorlagen GML kopieren
					/*
					$GUI->formvars['group_id'] = $layer_group_id;
					$GUI->formvars['pg_schema'] = XPLANKONVERTER_CONTENT_SCHEMA;
					$GUI->layer_generator_erzeugen(); # Funktion aus kvwmap.php
					*/
					$messages[] = 'XPlanGML-Datei ' . $konvertierung->get_file_name('xplan_gml') . ' für Konvertierung ' . $konvertierung->get('id') . ' erfolgreich erstellt.<br>';
				}
				else {
					$success = false;
					$messages[] = 'Es muss erst die Konvertierung ' . $konvertierung->get('id') . ' ausgeführt werden.<br>';
				}
			}

			echo json_encode(
				array(
					'success' => $success,
					'msg' => implode('<br>', $messages)
				)
			);
		} break;

		case 'xplankonverter_konvertierung_loeschen' : {
			$GUI->formvars['checkbox_names_' . $GUI->plan_layer_id] = 'check;;' . $GUI->plan_table_name . ';' . $GUI->formvars['plan_oid'];
			$GUI->formvars['check;;' . $GUI->plan_table_name . ';' . $GUI->formvars['plan_oid']] = 'on';
			$GUI->formvars['chosen_layer_id'] = $GUI->plan_layer_id;
			$success = $GUI->layer_Datensaetze_loeschen(false);
			$response = array(
				'success' => $success,
				'type' => ($success ? 'notice' : 'error'),
				'msg' => ($success ? 'Plan und Konvertierung erfolgreich gelöscht. ' : GUI::$messages[0]['msg'])
			);
			header('Content-Type: application/json');
			echo json_encode($response);
		} break;

		case 'xplankonverter_inspire_gml_generieren' : {
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
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
				$proc = new XsltProcessor();
				$doc = new DOMDocument();
				$doc->load($xsl);
				$proc->importStylesheet($doc); // load script
				$docfile = new DOMDocument();
				$docfile->load($fileinput);
				$output = $proc->transformToXML($docfile); // load your file
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
			$GUI->sanitize([
				'konvertierung_id' => 'int',
				'bereich_gml_id' => 'text',
				'class_name' => 'text'
			]);
			$konvertierung_id = $GUI->formvars['konvertierung_id'];
			$bereich_gml_id = $GUI->formvars['bereich_gml_id'];
			$class_name = $GUI->formvars['class_name'];

			if (empty($konvertierung_id)) {
				if (!empty($bereich_gml_id)) {
					# Hole konvertierung_id über den Bereich
					$pg_object = new PgObject($GUI, 'xplan_gml', 'xp_bereich');
					$bereich = $pg_object->find_by('gml_id', $bereich_gml_id);
					$konvertierung_id = $bereich->get('konvertierung_id');

				#					$bereich = XP_Bereich::find_by_id($GUI, 'gml_id', $bereich_gml_id, 'RP-Plan');
				#					$plan = $bereich->get_plan();
				#					$konvertierung_id = $plan->get('konvertierung_id');
				}
			}

			include(PLUGINS . 'xplankonverter/view/regeleditor/index.php');

		} break;

		case 'xplankonverter_regeleditor_getxplanattributes' : {
			$GUI->sanitize([
				'featuretype' => 'text'
			]);
			$sql = "
			SELECT
				column_name, udt_name, data_type, is_nullable
			FROM
				information_schema.columns
			WHERE
				table_name='" . $GUI->formvars['featuretype'] . "' AND
				table_schema='" . XPLANKONVERTER_CONTENT_SCHEMA . "'
			ORDER BY
				column_name
			";

			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);
			include(PLUGINS . 'xplankonverter/view/regeleditor/getxplanattributes.php');
		} break;

		case 'xplankonverter_regeleditor_getshapeattributes' : {
			$GUI->sanitize([
				'konvertierung_id' => 'int',
				'shapefile' => 'text'
			]);
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
			$GUI->sanitize([
				'konvertierung_id' => 'int',
				'shapefile' => 'text'
			]);
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
			$GUI->sanitize([
				'konvertierung_id' => 'int',
				'shapefile' => 'text'
			]);
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
			$GUI->sanitize([
				'konvertierung_id' => 'int',
				'shapefile_attribut' => 'text',
				'shapefile' => 'text'
			]);
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
			$GUI->sanitize([
				'konvertierung_id' => 'int',
				'shapefile_attribut' => 'text',
				'shapefile' => 'text'
			]);
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
			$GUI->sanitize([
				'featuretype' => 'text',
				'xplanattribut' => 'text'
			]);
			//Enumerationsliste Auswahl von Shape
			$sql = "
				SELECT
					udt_name
				FROM
					information_schema.columns
				WHERE
					table_name = '" . $GUI->formvars['featuretype'] . "' AND
					column_name = '" . $GUI->formvars['xplanattribut'] . "' AND
					table_schema = '" . XPLANKONVERTER_CONTENT_SCHEMA . "'
				ORDER BY
					column_name
			";

			$result = pg_query($GUI->pgdatabase->dbConn, $sql);

			while($row = pg_fetch_row($result)) {
				$enumerationsliste = $row[0];
			}
			# wenn es mit _startet ist es ein array, dann das _ entfernen
			$arrayEnum = false;
			if(substr($enumerationsliste, 0, 1) === '_') {
				$arrayEnum = true;
			}
			$enumerationsliste = ltrim($enumerationsliste, '_');

			$sql = "
				SELECT
					wert,
					beschreibung
				FROM
					" . XPLANKONVERTER_CONTENT_SCHEMA . ".enum_" . $enumerationsliste . "
			";
			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);

			include(PLUGINS . 'xplankonverter/view/regeleditor/getxplanenumerationattributes.php');
		} break;

		case 'xplankonverter_regeleditor_getxplanenumerationattributes2' : {
			$GUI->sanitize([
				'featuretype' => 'text',
				'xplanattribut' => 'text'
			]);

			//Enumerationsliste Wenn-Dann Auswahl von Shape
			$sql = "
				SELECT
					udt_name
				FROM
					information_schema.columns
				WHERE
					table_name='" . $GUI->formvars['featuretype'] . "' AND
					column_name = '" . $GUI->formvars['xplanattribut'] . "' AND
					table_schema = '" . XPLANKONVERTER_CONTENT_SCHEMA . "'
				ORDER BY
					column_name
			";
			#echo '<br>Sql: ' . $sql;

			$result = pg_query($GUI->pgdatabase->dbConn, $sql);

			while($row = pg_fetch_row($result)) {
				$enumerationsliste = $row[0];
			}
			# wenn es mit _startet ist es ein array, dann das _ entfernen
			$arrayEnum = false;
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
					" . XPLANKONVERTER_CONTENT_SCHEMA . ".enum_" . $enumerationsliste . "
				";
			#echo '<br>Sql: ' . $sql;
			$GUI->result = pg_query($GUI->pgdatabase->dbConn, $sql);

			include(PLUGINS . 'xplankonverter/view/regeleditor/getxplanenumerationattributes2.php');
		} break;

		/**
		 * Archiviere bisher veröffentlichte Pläne
		 * Setze den Plan mit konvertierung_id auf veroeffentlicht=true
		 * Lösche zuvor fehlgeschlagene Konvertierungen
		 */
		case 'xplankonverter_replace_zusammenzeichnung' : {
			header('Content-Type: application/json');

			$konvertierung_id = $GUI->formvars['konvertierung_id'];
			if ($konvertierung_id == '') {
				send_error('Fehler beim Ersetzen der vorhandenen Zusammenzeichnung!<p>Es muss eine Konvertierung-ID angegeben werden!');
				break;
			}

			if ($GUI->xplankonverter_is_case_forbidden()) {
				send_error("Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
					Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
					Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
					Melden Sie sich mit einem anderen Benutzer an."
				);
				break;
			}

			$new_konvertierung = $GUI->konvertierung;
			$konvertierungen = Konvertierung::find_konvertierungen(
				$GUI,
				$GUI->konvertierung->get('planart'),
				$GUI->konvertierung->plan_class
			);

			$GUI->debug->write('Konvertierungen gefunden');
			$GUI->xlog->write('Anzahl gefundene Konvertierungen: ' . count($konvertierungen['published']));
			if (count($konvertierungen['published']) > 0) {
				$GUI->debug->write('Veröffentlichte Konvertierung mit id ' . $konvertierungen['published'][0]->get('id') . ' gefunden.');

				$old_konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierungen['published'][0]->get('id'));

				$result = $old_konvertierung->archiv_old_plan();
				if (!$result['success']) {
					send_error($result['msg']);
					break;
				}
			}

			$GUI->debug->write('Setze Veröffentlichungsdatum: ' . $new_konvertierung->get_aktualitaetsdatum() . ' für neuen Plan.');
			$result = $new_konvertierung->update_attr(array('error_id = NULL', 'veroeffentlicht = true', "veroeffentlichungsdatum = '" . $new_konvertierung->get_aktualitaetsdatum() . "'"));
			if (!$result['success']) {
				send_error($result['msg']);
				break;
			}

			$GUI->debug->write('Lösche zuvor fehlgeschlagene Konvertierungen.');
			$result = $GUI->xplankonverter_remove_failed_konvertierungen();
			if (!$result['success']) {
				send_error($result['msg']);
				break;
			}
			
			// muss nochmal Konvertierung finden, um published korrekt abzufragen, da veröffentlicht attribut inzwischen geändert wurde
			$konvertierungen = Konvertierung::find_konvertierungen(
				$GUI,
				$GUI->konvertierung->get('planart'),
				$GUI->konvertierung->plan_class
			);
			// Löscht auch alle Zusammenzeichnungen, die draft(d.h nicht published oder faulty) sind, wenn mindestens eine existiert
			if (count($konvertierungen['published']) > 0) {
				$GUI->debug->write('Lösche draft Zusammenzeichnungen.');
				//$GUI->xlog->write('Lösche draft Zusammenzeichnungen.');
				$result = $GUI->xplankonverter_remove_old_konvertierungen();
				if (!$result['success']) {
					send_error($result['msg']);
					break;
				}
			}

			if (empty($GUI->formvars['suppress_ticket_and_notification'])) {
				$GUI->debug->write('Sende Benachrichtigung.');
				$result = $new_konvertierung->send_notification('der Plan ' . $new_konvertierung->get('bezeichnung') . ' ist von dem Nutzer ' . $GUI->user->Vorname . ' ' . $GUI->user->Name . ' (login: ' . $GUI->user->login_name . ") aktualisiert worden.\n\nDiese E-Mail ist vom Portal " . URL.APPLVERSION . " versendet worden.\nDie aktuelle Zusammenzeichnung können Sie sich hier ansehen: " . URL.APPLVERSION . "index.php?go=xplankonverter_konvertierung_anzeigen&planart=" . $GUI->formvars['planart'] . "}\n\nIhr Team von " . TITLE);
				if (!$result['success']) {
					send_error($result['msg']);
					break;
				}
			}

			$response = array(
				'success' => true,
				'msg' => 'Zusammenzeichnung erfolgreich ausgetauscht und ' . $result['msg']
			);
			echo json_encode($response);
		} break;

		#-------------------------------------------------------------------------------------------------------------------------
		# Download cases
		#-------------------------------------------------------------------------------------------------------------------------
		case 'xplankonverter_download_uploaded_shapes' : {
			if ($GUI->xplankonverter_is_case_forbidden()) return;

			if (!$GUI->konvertierung->download_files_exists('uploaded_shapes')) {
				$GUI->add_message('warning', 'Es sind keine Dateien für den Export vorhanden.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}

			$exportfile = $GUI->konvertierung->create_export_file('uploaded_shapes');
			$GUI->konvertierung->send_export_file($exportfile, 'application/octet-stream');

		} break;

		case 'xplankonverter_download_uploaded_xplan_gml' : {
			if ($GUI->formvars['konvertierung_id'] == '') {
				send_error('Sie müssen eine Konvertierung-ID iim Parameter konvertierueng_id angeben!');
				return;
			}
			$GUI->sanitize(array('konvertierung_id' => 'int'));
			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);

			if (!$GUI->konvertierung->download_files_exists('uploaded_xplan_gml')) {
				$GUI->add_message('warning', 'Die Dateien sind nicht auf dem Server oder an der falschen Stelle.');
				if ($GUI->formvars['page'] === 'zusammenzeichnung') {
					go_switch('xplankonverter_konvertierung_anzeigen');
					exit;
				}
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}

			$exportfile = $GUI->konvertierung->create_export_file('uploaded_xplan_gml');
			$GUI->konvertierung->send_export_file($exportfile, 'application/octet-stream');

		} break;

		case 'xplankonverter_download_archivdatei' : {
			$konvertierung = new Konvertierung($GUI, $GUI->formvars['planart']);
			$filename = XPLANKONVERTER_FILE_PATH . 'archiv/' . $GUI->Stelle->id . '/' . $konvertierung->config['plan_abk_plural'] . '/' . basename($GUI->formvars['datei']);

			if (!file_exists($filename)) {
				$GUI->add_message('warning', 'Diese Datei ist nicht vorhanden. Wenden Sie sich an den Support.');
				if ($GUI->formvars['page'] === 'zusammenzeichnung') {
					go_switch('xplankonverter_konvertierung_anzeigen');
					exit;
				}
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}


			$konvertierung->send_export_file($filename, 'application/zip');
		} break;

		case 'xplankonverter_download_edited_shapes' : {
			if ($GUI->xplankonverter_is_case_forbidden()) {
				return;
			}

			$GUI->konvertierung->create_edited_shapes();
			if (!$GUI->konvertierung->download_files_exists('edited_shapes')) {
				$GUI->add_message('warning', 'Es sind keine Dateien für den Export vorhanden.');
				//				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->formvars['planart'] = 'BP-Plan';
				$GUI->main = '../../plugins/xplankonverter/view/plaene.php';
				$GUI->output();
				return;
			}

			$exportfile = $GUI->konvertierung->create_export_file('edited_shapes');
			$GUI->konvertierung->send_export_file($exportfile, 'application/octet-stream');
		} break;

		/*
		* Case query if files of the defined file_type exists for konvertierung_id
		* If yes show a download link
		* If not show that there are no files for download
		*/
		case 'xplankonverter_download_files_query' : {
			if ($GUI->xplankonverter_is_case_forbidden()) {
				include_once('plugins/xplankonverter/view/xplankonverter_download_files_not_allowed.php');
				return;
			}
			$GUI->files_exists = $GUI->konvertierung->files_exists($GUI->formvars['file_type']);
			$GUI->main = '../../plugins/xplankonverter/view/xplankonverter_download_edited_shapes_query.php';
			$GUI->formvars['only_main'] = 'true';
			$GUI->mime_type ='html';
			$GUI->output();
		} break;

		case 'xplankonverter_download_xplan_shapes' : {
			if ($GUI->xplankonverter_is_case_forbidden()) {
				return;
			}

			$GUI->konvertierung->create_xplan_shapes();

			if (!$GUI->konvertierung->download_files_exists('xplan_shapes')) {
				$GUI->add_message('warning', 'Es sind keine Dateien für den Export vorhanden.');
				$GUI->plan_layer_id = $GUI->konvertierung->plan_layer_id;
				$GUI->main = '../../plugins/xplankonverter/view/plaene.php';
				$GUI->output();
				return;
			}

			$exportfile = $GUI->konvertierung->create_export_file('xplan_shapes');
			$GUI->konvertierung->send_export_file($exportfile, 'application/octet-stream');

		} break;

		case 'xplankonverter_download_xplan_gml' : {
			if ($GUI->xplankonverter_is_case_forbidden()) {
				include_once('plugins/xplankonverter/view/xplankonverter_download_files_not_allowed.php');
			#				echo 'Anwendungsfall nicht erlaubt!';
				return;
			}
			$filename = XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/xplan_gml/xplan_' . $GUI->formvars['konvertierung_id'] . '.gml';

			if (!file_exists($filename)) {
				$GUI->add_message('warning', 'Diese Datei ist nicht vorhanden. Prüfen Sie ob die Konvertierung schon korrekt ausgeführt wurde. Wenn ja, wenden Sie sich an den Support.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}
			header('Content-Disposition: attachment; filename="xplan_' . $GUI->formvars['konvertierung_id'] . '.gml"; subtype="gml/3.3"');
			echo fread(fopen($filename, "r"), filesize($filename));
		} break;

		case 'xplankonverter_download_zusammenzeichnung_gml' : {
			if ($GUI->xplankonverter_is_case_forbidden()) {
				echo 'Anwendungsfall nicht erlaubt!';
				return;
			}
			$filename = XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/xplan_gml/zusammenzeichnung_' . $GUI->formvars['konvertierung_id'] . '.gml';

			if (!file_exists($filename)) {
				$GUI->add_message('warning', 'Diese Datei ist nicht vorhanden. Prüfen Sie ob die Konvertierung schon korrekt ausgeführt wurde. Wenn ja, wenden Sie sich an den Support.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}
			header('Content-Disposition: attachment; filename="zusammenzeichnung_' . $GUI->formvars['konvertierung_id'] . '.gml"; subtype="gml/3.3"');
			echo fread(fopen($filename, "r"), filesize($filename));
		} break;

		case 'xplankonverter_download_zusammenzeichnung-neu_gml' : {
			if ($GUI->xplankonverter_is_case_forbidden()) {
				echo 'Anwendungsfall nicht erlaubt!';
				return;
			}
			$filename = XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/xplan_gml/zusammenzeichnung-neu_' . $GUI->formvars['konvertierung_id'] . '.gml';

			if (!file_exists($filename)) {
				$GUI->add_message('warning', 'Diese Datei ist nicht vorhanden. Prüfen Sie ob die Konvertierung schon korrekt ausgeführt wurde. Wenn ja, wenden Sie sich an den Support.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}
			header('Content-Disposition: attachment; filename="zusammenzeichnung-neu_' . $GUI->formvars['konvertierung_id'] . '.gml"; subtype="gml/3.3"');
			echo fread(fopen($filename, "r"), filesize($filename));
		} break;

		case 'xplankonverter_download_xlog' : {
			if ($GUI->xplankonverter_is_case_forbidden()) return;
			$logfilename = XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/xplankonverter.log';

			if (!file_exists($logfilename)) {
				$GUI->add_message('warning', 'Diese Datei ist nicht vorhanden. Prüfen Sie ob die Konvertierung schon korrekt ausgeführt wurde. Wenn ja, wenden Sie sich an den Support.');
				$GUI->main = '../../plugins/xplankonverter/view/konvertierungen.php';
				$GUI->output();
				return;
			}
			header('Content-Disposition: attachment; filename="xplankonverter.log"; subtype="text/plan"');
			echo fread(fopen($logfilename, "r"), filesize($logfilename));
		} break;

		case 'xplankonverter_download_inspire_gml' : {
			if ($GUI->xplankonverter_is_case_forbidden()) return;

			$filename = XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/inspire_gml/inspire_' . $GUI->formvars['konvertierung_id'] . '.gml';
			header('Content-Disposition: attachment; filename="inspire_' . $GUI->formvars['konvertierung_id'] . '.gml"; subtype="gml/3.3"');
			echo fread(fopen($filename, "r"), filesize($filename));
		} break;

		case 'xplankonverter_go_to_plan' : {
			$GUI->sanitize([
				'plan_gml_id' => 'text'
			]);

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
				$result = unzip($zip_file, false, false, true);
				# get shape file name
				$first_file = explode('.', $result['files'][0]);
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
			$import_result = $importer->load_shp_into_pgsql($GUI->pgdatabase, IMAGEPATH, $shape_file_name, $epsg, CUSTOM_SHAPE_SCHEMA, 'b' . strtolower(sonderzeichen_umwandeln(substr($shape_file_name, 0, 15))) . rand(1, 1000000), $encoding);

			# return name of import table
			$response['result'] = $import_result[0]['tablename'];

			header('Content-Type: application/json');
			echo json_encode($response);
			return;
		} break;

		/**
		*	Schritt 1:	In xplankonverter_upload_xplan_gml wird view upload_xplan_gml ausgeliefert. Der enthält ein Formular zum hochladen der gml oder zip Datei.
		*	Schritt 2:	Der Ajax-Aufruf im Formular ruft nochmal den case xplankonverter_upload_xplan_gml auf, aber dieses mal mit einem upload_file
		*							Das wird in XPLANKONVERTER_FILE_PATH . 'tmp/' . session_id() zwischengespeichert und wenn es ein Zip-File ist
		*							im Unterverzeichnis zip/ ausgepackt und die externen referenzen in das document_path des Layers mit random_number als Postfix verschoben.
		*							Die Namen der gml und referenz-Dateien werden per json an das upload_xplan_gml Formular zurückgeliefert und dort angezeigt.
		*							Dort klickt der Anwender auf den Button zum Übernehmen der Daten in das Plan-Formular
		*	Schritt 3:	Der View upload_xplan_gml ruft den case xplankonverter_extract_gml_to_form auf mit dem gml_file und der random_number als parameter.
		*							Darin wird die Methode extract_gml_classes der Klasse gml_extractor ausgeführt, die folgendes macht:
		*							- GML-Datei in das gmlastmp Schema schreiben mit ogr2ogr_gmlas
		*							- Hier wurde der Sonderfall eingeführt wenn in dem XPlanGML mehrere Pläne sind.
		*								- In dem Fall wird dem Nutzer nicht das eine Formular mit den Daten des ersten Plans angezeigt, sondern eine Auswahl gestellt:
		*									- Ersten Plan in das Formular übernehmen weiter mit Schritt 4
		*									- Alle Pläne aus dem XPlanGML-Dokument automatisch in den XPlankonverter aufnehmen => Anzeige der Pläne der Stelle
		*								- Wenn es nur ein Plan ist oder nur der erste Plan genommen werden soll formularvariablen mit dem was in GML-Datei steht belegen
		*									außer bei externereferenz. Da wird die document_url + doc_file + random_number eingetragen und Vorschaubilder erzeugt.
		*	Schritt 4:	Speichern des Formulars. Dabei wird der Plandatensatz angelegt und der Trigger handle_xp_plan ausgeführt in dem:
		*							- der temporäre Schemaname xplan_gmlas_tmp_$konvertierung_id in xplan_gmlas_$konvertierung_id umbenannt wird
		*							- Bereiche und Regeln erzeugt werden
		*							- Verschieben der xplan_gml-Datei von 'tmp/' . session_id() nach $konvertierung_id . '/uploaded_xplan_gml/'
		*/
		case 'xplankonverter_upload_xplan_gml' : {
			$GUI->check_csrf_token();
			switch ($GUI->formvars['planart']) {
				case 'BP-Plan' : $layer_id = XPLANKONVERTER_BP_PLAENE_LAYER_ID; break;
				case 'FP-Plan' : $layer_id = XPLANKONVERTER_FP_PLAENE_LAYER_ID; break;
				case 'SO-Plan' : $layer_id = XPLANKONVERTER_SO_PLAENE_LAYER_ID; break;
				case 'RP-Plan' : $layer_id = XPLANKONVERTER_RP_PLAENE_LAYER_ID; break;
			}
			$GUI->plan_layerset = $GUI->user->rolle->getLayer($layer_id)[0];
			$success = false;
			$gml_file = '';
			$doc_files = array();
			$GUI->response = array();
			if ($GUI->formvars['upload_xplan_gml'] == 'Daten hochladen') {
				$upload_file = $_FILES['gml_file'];
				if ($upload_file['name'] != '') {
					# TODO check $_FILES['userfile']['size'] == eg less than 100 MB else abort and message that the file has to be converted piecemail or by an administrator
					# Die Dateien kommen erstmal nach tmp, weil es noch keine Konvertierung-ID gibt (zumindest nicht beim neu Anlegen von Plänen)
					$upload_dir = XPLANKONVERTER_FILE_PATH . 'tmp/' . session_id() . '/';
					if (!is_dir($upload_dir)) {
						mkdir($upload_dir, 0777, true);
					};
					$target_file = $upload_dir . $upload_file['name'];
					if (move_uploaded_file($upload_file['tmp_name'], $target_file)) {
						$zip = new ZipArchive();
						$res = $zip->open($target_file, ZipArchive::CHECKCONS);
						if ($res === true) {
							# Es ist eine ZIP-Datei, erzeuge einen Ordner in den entpackt wird
							$zip_dir = $upload_dir . 'zip/';
							if (!is_dir($zip_dir)) {
								mkdir($zip_dir, 0777, true);
							}
							$zip->extractTo($zip_dir);
							# Schließe ZIP-Datei und lösche die hochgeladene Datei
							$zip->close();
							exec('rm ' . $target_file);
							$random_number = rand(1, 1000000);
							foreach (scandir($zip_dir) AS $index => $entry) {
								if ($index > 1) { # do not for . and .. entry
									if (strpos(strrev(strtolower($entry)), 'lmg.') === 0 OR strpos(strrev(strtolower($entry)), 'lmx.') === 0) {
										$gml_file = $entry;
										$success = true;
										exec('mv ' . $zip_dir . $entry . ' ' . $upload_dir);
									}
									else {
										$path_parts = pathinfo($entry);
										$doc_files[] = array(
											'upload_file_name' => $entry,
											'file_name' => $path_parts['filename'],
											'store_file_name' => $path_parts['filename'] . '-' . $random_number . '.' . $path_parts['extension'],
											'thumb_file_name' => $path_parts['filename'] . '-' . $random_number . '_thumb.jpg'
										);
										exec('mv ' . $zip_dir . $entry . ' ' . $GUI->plan_layerset['document_path'] . $path_parts['filename'] . '-' . $random_number . '.' . $path_parts['extension']);
										$GUI->create_dokument_vorschau('local_img', pathinfo($GUI->plan_layerset['document_path'] . $path_parts['filename'] . '-' . $random_number . '.' . $path_parts['extension']));
										/*
										$msg .= print_r($GUI->get_dokument_vorschau(
											$GUI->plan_layerset['document_path'] . $path_parts['filename'] . '-' . $random_number . '.' . $path_parts['extension'],
											$GUI->plan_layerset['document_path'],
											$GUI->plan_layerset['document_url']
										), true);
										*/
									}
								}
							}
							exec('rm -R ' . $zip_dir);
							if ($gml_file == '') {
								$msg = 'Die ZIP-Datei ' . $upload_file['name'] . ' enthält keine GML-Datei!';
							}
						}
						else {
							if ($res == ZipArchive::ER_NOZIP) {
								# Es ist keine ZIP-Datei
								if (strpos(strrev(strtolower($upload_file['name'])), 'lmg.') === 0 OR strpos(strrev(strtolower($upload_file['name'])), 'lmx.') === 0) {
									$gml_file = $upload_file['name'];
									exec('mv ' . $target_file . ' ' . $xplan_gml_dir);
									$success = true;
									$msg .= 'Keine weiteren Dateien hochgeladen.';
								}
								else {
									$msg .= 'Die hochgeladene Datei ' . $upload_file['name'] . ' ist keine GML-Datei';
								}
							}
							else {
								# Es ist eine fehlerhafte ZIP-Datei
								switch ($res) {
									case ZipArchive::ER_INCONS : {
										$msg .= 'Die ZIP-Datei hat den Konsistenztest nicht bestanden!';
									} break;
									case ZipArchive::ER_CRC : {
										$msg .= 'Die Check-Summe der ZIP-Datei stimmt nicht!';
									} break;
									default : {
										$msg .= 'Die ZIP-Datei konnte nicht geöffnet werden!';
									}
								}
								$msg .= ' Prüfen Sie den Inhalt und versuchen Sie es erneut.';
							}
						}
					}
					else {
						$msg = 'Kann Datei nicht auf dem Server zwischenspeichern. Prüfen Sie ob genüg Speicherplatz auf dem Server ist und ob im Verzeichnis ' . IMAGEPATH . ' Schreibrechte vorhanden sind.';
					}
					$GUI->response = array(
						'success' => $success,
						'msg' => $msg,
						'random_number' => $random_number,
						'doc_files' => $doc_files,
						'gml_file' => $gml_file
					);
				}
				else {
					$GUI->add_message('error', 'Es wurde keine Datei hochgeladen.');
				}
			}
			$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
			$GUI->output();
		} break;

		/**
		*	Upload einer Zusammenzeichnung und Abarbeitung der Schritte der Validierung, Plananlegen, Konvertierung und XPlanGML-Erzeugen
		*/

		case 'xplankonverter_upload_zusammenzeichnung' : {
			header('Content-Type: application/json');
			$upload_file = $_FILES['upload_file'];
			$tmp_dir = 	XPLANKONVERTER_FILE_PATH . 'tmp/zusammenzeichnung_' . random_int(100000, 999999) . '/';
			$tmp_file = $upload_file['name'];

			$result = $GUI->xplankonverter_validate_uploaded_zusammenzeichnungen($upload_file, $tmp_dir);

			if (! $result['success']) {
				send_error('Fehler beim Hochladen der Zusammenzeichnung!<p>' . $result['msg'], false, false);
				if (strpos($tmp_dir, XPLANKONVERTER_FILE_PATH . 'tmp/zusammenzeichnung_') !== false AND file_exists($tmp_dir)) {
					exec('rm -R ' . $tmp_dir);
				}
				exit;
			}

			echo json_encode($result);

		} break;

		/**
		* Importiert die Zusammenzeichnung und falls vorhanden die Geltungsbereiche von konvertierung_id in die Datenbank
		* es muss formvar konvertierung_id angegeben sein
		* mit formvar xplan_gml_path = 'reindexed_xplan_gml wird die Zusammenzeichnung mit den geänderten gml_id's eingelesen
		* Beispiel:
		* index.php?go=xplankonverter_import_zusammenzeichnung&konvertierung_id=6403&xplan_gml_path=reindexed_xplan_gml&planart=FP-Plan&mime_type=json&format=json_result&csrf_token=67115c408421d9d39f0be726aa2fbdbc
		*/
		case 'xplankonverter_import_zusammenzeichnung' : {
			header('Content-Type: application/json');
			$success = false;
			$msg = [];

			$konvertierung_id = $GUI->formvars['konvertierung_id'];

			# Prüfen ob konvertierung_id angegeben wurde und der upload in der Stelle erlaubt ist
			if ($konvertierung_id == '') {
				send_error('Fehler beim Importieren der Zusammenzeichnung!<p>Keine Konvertierung-ID angegeben.', false, false);
				break;
			}

			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);
			if ($GUI->konvertierung->get('id') == '') {
				send_error("Die Konvertierung mit id: ${konvertierung_id} konnte in der Datenbank nicht gefunden werden.");
				break;
			}

			if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
				send_error("Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
					Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
					Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
					Melden Sie sich mit einem anderen Benutzer an."
				);
				break;
			}

			# Importiert die Datei der zuletzt hochgeladenen Zusammenzeichnung
			$file_zusammenzeichnung = $GUI->konvertierung->get_file_path($GUI->formvars['xplan_gml_path'] == 'reindexed_xplan_gml' ? 'reindexed_xplan_gml' : 'uploaded_xplan_gml') . $GUI->konvertierung->get_plan_file_name();

			$gml_extractor = new Gml_extractor($GUI->pgdatabase, $file_zusammenzeichnung, 'xplan_gmlas_tmp_' . $GUI->user->id);

			$import_result = $gml_extractor->import_gml_to_db();

			if (! $import_result['success']) {
				$GUI->konvertierung->set('error_id', 1);
				$GUI->konvertierung->update();
				send_error('Fehler beim Einlesen der Datei in die Datenbank mit ogr2ogr_gmlas. Fehler: ' . $import_result['msg']);
				break;
			}

			$result = $GUI->konvertierung->get_num_gmlas_tmp_plaene();
			if (!$result['success']) {
				$GUI->konvertierung->set('error_id', 8);
				$GUI->konvertierung->update();
				send_error($result['msg']);
				break;
			}

			if ($result['num_plaene'] == 0) {
				$GUI->konvertierung->set('error_id', 7);
				$GUI->konvertierung->update();
				send_error('Fehler beim Einlesen der Datei in die Datenbank. Es wurden keine Pläne importiert. Importbefehl: ' . $import_result['url']);
				break;
			}

			if ($GUI->konvertierung->plan_exists('xplan_gmlas_tmp_' . $GUI->user->id, $GUI->plan_class)) {
				$GUI->konvertierung->set('error_id', 2);
				$GUI->konvertierung->update();
				send_error('Warnung: Der Plan in der hochgeladenen Datei ' . $file_zusammenzeichnung . ' enthält eine gml_id, die schon im System vorhanden ist.', false, false);
				break;
			}
			
			// Check if zusammenzeichnung has at least 95% area overlap with gebietseinheit_flaeche
			// to validate that it is actually a zusammenzeichnung of the entire area and not e.g. just of one aenderung/berichtigung
			if (
				$GUI->konvertierung->get('planart') == 'FP-Plan' AND
				$GUI->konvertierung->is_geltungsbereich_gebietseinheiten_area_similar('xplan_gmlas_tmp_' . $GUI->user->id, $GUI->plan_class) == false
			) {
				$GUI->konvertierung->set('error_id', 9);
				$GUI->konvertierung->update();
				//send_error('Fehler: Der Plan in der hochgeladene Datei ' . $file_zusammenzeichnung . ' enthält einen räumlichen Geltungsbereich, der mindestens 5% von der Fläche der Gebietseinheit abweicht.');
				send_error('Fehler: Sie haben versucht, eine XPlanGML in der Datei' . $file_zusammenzeichnung . ' hochzuladen, welche räumlich nicht die gesamte Fläche der Stadt, Gemeinde oder Samtgemeinde umfasst. Möglicherweise handelt es sich um eine XPlanGML, die nur einen kleineren Änderungsbereich umfasst. Zur Fortschreibung der Daten ist der Upload von Einzeländerungen nicht vorgesehen. Es ist notwendig, den Änderungsplan in eine XPlanGML des Flächennutzungsplans zu integrieren, die den gesamten Kommunalbereich umfasst. Mithilfe der Änderung ist der gesamte Flächennutzungsplan fortzuschreiben. Die XPlanGML des geänderten Gesamt-Flächennutzungsplans ist hochzuladen; sie ersetzt dann die vormals veröffentlichten Daten. Weitere Hinweise zur Fortführung der Daten erhalten Sie in der Handreichung für die Kommunen unter plandigital.niedersachsen.de.');
				break;
			}

			$response = array(
				'success' => true,
				'msg' => 'Import der Zusammenzeichnung erfolgreich abgeschlossen.',
				'konvertierung_id' => $konvertierung_id
			);

			echo json_encode($response);
		} break;

		case 'xplankonverter_test' : {
			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
			$plan_file = $GUI->konvertierung->get_file_path($GUI->formvars['xplan_gml_path'] == 'reindexed_xplan_gml' ? 'reindexed_xplan_gml' : 'uploaded_xplan_gml') . $GUI->konvertierung->get_plan_file_name();
			echo '<br>plan_file: ' . $plan_file;
			$gml_extractor = new Gml_extractor($GUI->pgdatabase, $plan_file, 'xplan_gmlas_tmp_' . $GUI->user->id);
			$import_result = $gml_extractor->import_gml_to_db();
			echo 'Fertig';
		} break;

		case 'xplankonverter_create_plaene' : {
			header('Content-Type: application/json');
			$success = false;
			$msg = [];

			$konvertierung_id = $GUI->formvars['konvertierung_id'];

			# Prüfen ob konvertierung_id angegeben wurde und der upload in der Stelle erlaubt ist
			if ($konvertierung_id == '') {
				send_error('Fehler beim Erzeugen der Pläne der Zusammenzeichnung!<p>Keine Konvertierung-ID angegeben.', false, false);
				break;
			}

			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);
			if ($GUI->konvertierung->get('id') == '') {
				send_error("Die Konvertierung mit id: ${konvertierung_id} konnte in der Datenbank nicht gefunden werden.");
				break;
			}

			if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
				send_error("Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
					Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
					Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
					Melden Sie sich mit einem anderen Benutzer an."
				);
				break;
			}

				#			if ($GUI->formvars['xplan_gml_path'] == 'reindexed_xplan_gml') {
				#				# Lösche Plan der Konvertierung
				#				$GUI->konvertierung->plan->destroy();
				#			}

			# Anlegen von Plan und Bereich der Zusammenzeichnung
			$result = $GUI->konvertierung->create_plaene_from_gmlas('xplan_gmlas_tmp_' . $GUI->user->id, $GUI->plan_class, $konvertierung_id, true);
			if (! $result['success']) {
				$GUI->konvertierung->set('error_id', 3);
				$GUI->konvertierung->update();
				send_error('Fehler beim Anlegen des Planes in der Datenbank. Fehler: ' . $result['msg']);
				break;
			}

			$GUI->konvertierung->get_plan();
			if ($GUI->konvertierung->plan === false) {
				$GUI->konvertierung->set('error_id', 3);
				$GUI->konvertierung->update();
				send_error('Nach dem Einlesen des Planes mit GMLAS konnte dieser nicht gefunden werden. ' . $import_result['msg']);
				break;
			}

			if ($GUI->konvertierung->get_aktualitaetsdatum() == '') {
				$GUI->konvertierung->set('error_id', 6);
				send_error('Der Plan ' . $GUI->konvertierung->get('bezeichnung') . ' (konvertierung_id: ' . $GUI->konvertierung->get_id() . ', gml_id: ' . $GUI->konvertierung->plan->get('gml_id') . ') hat kein ' . ucfirst($GUI->konvertierung->get_plan_attribut_aktualitaet()) . '. Das muss in der XPlan-GML angepasst werden. Anschließend kann der Plan erneut hochgeladen werden. Die Attribute ' . natural_join($GUI->konvertierung->config['plan_attribut_aktualitaet'], ', ', ' und ') . ' waren alle leer!');
				break;
			}

			# Setze den Plan für das Konvertierungsobjekt
			# Die Konvertierung hat hier noch gar keinen Plan.
			#$GUI->konvertierung->get_plan();

			# Übername der Plandaten in permanentes Schema xplan_gmlas_$konvertierung_id
			$result = $GUI->konvertierung->rename_xplan_gmlas($GUI->user->id, $konvertierung_id);
			if (! $result['success']) {
				$GUI->konvertierung->set('error_id', 4);
				$GUI->konvertierung->update();
				send_error('Fehler beim Umbennen des Import-Schemas xplan_gmlas_tmp_' . $GUI->user->id . ' nach xplan_gmlas_' . $konvertierung_id . ' Fehler: ' . $result['msg']);
				break;
			}

			$gml_extractor = new Gml_extractor($GUI->pgdatabase, '', 'xplan_gmlas_tmp_' . $GUI->user->id);
			$gml_extractor->gmlas_schema = 'xplan_gmlas_' . $GUI->konvertierung->get_id();
			$GUI->konvertierung->insert_textabschnitte($gml_extractor);
			$debug_log = $gml_extractor->insert_all_regeln_into_db(
				$konvertierung_id,
				$GUI->Stelle->id,
				(array_key_exists('simplify_fachdaten_geom', $GUI->formvars) ? floatval($GUI->formvars['simplify_fachdaten_geom']) : null)
			);
			$msg = 'Zusammenzeichnung';

			$file_geltungsbereiche = $GUI->konvertierung->get_file_path('uploaded_xplan_gml') . 'Geltungsbereiche.gml';
			if (file_exists($file_geltungsbereiche)) {
				$gml_extractor = new Gml_extractor($GUI->pgdatabase, $file_geltungsbereiche, 'xplan_gmlas_tmp_' . $GUI->user->id);
				$result = $gml_extractor->import_gml_to_db();

				# Übernahme der Geltungsbereiche in die Zieltabelle
				$result = $GUI->konvertierung->insert_geltungsbereiche($gml_extractor);
				if (! $result['success']) {
					$GUI->konvertierung->set('error_id', 5);
					$GUI->konvertierung->update();
					send_error('Fehler beim Einlesen der Geltungsbereiche. Fehler: ' . $result['msg'] . ' sql: ' . $result['sql']);
					break;
				}
				$msg .= ' und Geltungsbereiche';
			}

			$response = array(
				'success' => true,
				'msg' => 'Einlesen der ' . $msg . ' erfolgreich abgeschlossen.' . $debug_log,
				'konvertierung_id' => $konvertierung_id
			);

			echo json_encode($response);
		} break;

		case 'xplankonverter_reindex_gml_ids' : {
			$result = $GUI->xplankonverter_reindex_gml_ids();
			if (! $result['success']) {
				send_error($result['msg']);
				break;
			}
			header('Content-Type: application/json');
			echo json_encode($result);
		} break;

		case 'xplankonverter_extract_gml_to_form' : {
			$GUI->checkCaseAllowed($go);

			#if (!isset($_POST['gml_file']) or empty($_POST['gml_file'])) {
			$upload_dir = XPLANKONVERTER_FILE_PATH . 'tmp/' . session_id() . '/';
			$gml_file = $upload_dir . $GUI->formvars['gml_file'];
			if (!is_file($gml_file)) {
				$GUI->add_message('error', 'GML-Datei nicht gefunden. Bitte hochladen.');
				$GUI->main = '../../plugins/xplankonverter/view/upload_xplan_gml.php';
				$GUI->output();
				exit;
			}
			#echo 'uploaded gml file: ' . $gml_file
			#$gml_location = IMAGEPATH . $_POST['gml_file'] . '_' . $GUI->user->id . '.gml';
			$gml_extractor = new Gml_extractor($GUI->pgdatabase, $gml_file, 'xplan_gmlas_tmp_' . $GUI->user->id);
			$gml_extractor->extract_gml_classes($GUI->plan_class);

			$GUI->user->rolle->oGeorefExt->minx = $GUI->formvars['minx'];
			$GUI->user->rolle->oGeorefExt->miny = $GUI->formvars['miny'];
			$GUI->user->rolle->oGeorefExt->maxx = $GUI->formvars['maxx'];
			$GUI->user->rolle->oGeorefExt->maxy = $GUI->formvars['maxy'];

			$num_plane = $gml_extractor->get_num_plaene('xplan_gmlas_tmp_' . $GUI->user->id, strtolower($GUI->plan_class));
			if ($num_plane > 1) {
				$GUI->add_message('warning', 'Im hochgeladenen GML-Dokument befinden sich ' . $num_plane . ' Pläne.<p>Sollen alle Pläne automatisch zur Planliste der Stelle hinzugefügt werden klicken Sie <a href="index.php?go=xplankonverter_create_plaene_from_gmlas&planart=' . $GUI->formvars['planart'] . '&csrf_token=' . $_SESSION['csrf_token'] . '">hier</a>.<p>Soll nur der erste im GML-Dokument enthaltene Plan übernommen werden klicken Sie "ok" und speichern das Formular.');
			}
			$GUI->neuer_Layer_Datensatz();
		} break;

		case 'xplankonverter_create_plaene_from_gmlas' : {
			$GUI->checkCaseAllowed('xplankonverter_extract_gml_to_form');
			$GUI->konvertierung = new Konvertierung($GUI, $GUI->formvars['planart']);
			$res = $GUI->konvertierung->create_plaene_from_gmlas('xplan_gmlas_tmp_' . $GUI->user->id, $GUI->plan_class);
			$GUI->add_message(($res['success'] ? 'notice' : 'error'), $res['msg']);
			$GUI->title = str_replace('an', 'äne', $GUI->title);
			$GUI->main = '../../plugins/xplankonverter/view/plaene.php';
			$GUI->output();
		} break;

		case 'xplankonverter_extract_standardshapes_to_regeln' : {
			$GUI->sanitize([
				'konvertierung_id' => 'int'
			]);
			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
			if ($GUI->konvertierung->get('id') != '') {
				if (isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					$bereich_gml_id = $GUI->formvars['bereich_gml_id'];
					$konvertierung_id = $GUI->formvars['konvertierung_id'];
					$stelle_id = $GUI->formvars['stelle_id'];

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
				else {
					$GUI->add_message('error', 'Die Konvertierung mit der ID: ' . $GUI->formvars['konvertierung_id'] . ' wurde nicht gefunden!');
				}
			}

			# go to layer search with layer of planbereich
			switch ($GUI->konvertierung->get('planart')) {
				case 'BP-Plan' : $layer_id = XPLANKONVERTER_BP_BEREICHE_LAYER_ID; break;
				case 'FP-Plan' : $layer_id = XPLANKONVERTER_FP_BEREICHE_LAYER_ID; break;
				case 'SO-Plan' : $layer_id = XPLANKONVERTER_SO_BEREICHE_LAYER_ID; break;
				case 'RP-Plan' : $layer_id = XPLANKONVERTER_RP_BEREICHE_LAYER_ID; break;
			}

			$GUI->go = 'Layer-Suche_Suchen';
			$GUI->formvars = array(
				'go' => $GUI->go,
				'selected_layer_id' => $layer_id,
				'operator_gml_id' => '=',
				'value_gml_id' => $GUI->formvars['bereich_gml_id'],
			);

			$GUI->goNotExecutedInPlugins = true;
		} break;

		/*
		* Importiert die gml-Daten eines WFS in die Plantabellen
		* - Läd das gml herunter
		* - Spielt die Daten in eine import Tabelle
		* - Führt SQL zum Update der Plantabellen aus
		*   Berücksichtigt dabei das Anlegen von Konvertierung und extref Tabelle
		*/
		case 'xplankonverter_import_plaene_from_dienst' : {
			
		} break;

		case 'xplankonverter_validate_zusammenzeichnung' : {
			if ($GUI->formvars['konvertierung_id'] == '') {
				$result = array(
					'success' => false,
					'msg' => 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.'
				);
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					$result = array(
						'success' => true,
						'msg' => "Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
						Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
						Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
						Melden Sie sich mit einem anderen Benutzer an."
					);
				}
				else {
					$result = $GUI->konvertierung->xplanvalidator('zusammenzeichnung-neu_gml');
					$status = '';
					if ($result['success']) {
						$GUI->konvertierung->set_status($result['valid'] ? Konvertierung::$STATUS['GML_VALIDIERUNG_OK'] : Konvertierung::$STATUS['GML_VALIDIERUNG_ERR']);
					}
				}
			}
			$GUI->data = $result;
			$GUI->output();
		} break;

		case 'xplankonverter_xplanvalidator' : {
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
					$result = $GUI->konvertierung->xplanvalidator('xplan_gml');
					$status = '';
					if ($result['success']) {
						$GUI->konvertierung->set_status($result['valid'] ? Konvertierung::$STATUS['GML_VALIDIERUNG_OK'] : Konvertierung::$STATUS['GML_VALIDIERUNG_ERR']);
					}
				}
			}
			#$GUI->output();
		} break;

		case 'xplankonverter_zusammenzeichnung' : {
			// Dieser Fall ist nur noch ein Alias für xplankonverter_konvertierung_anzeigen
			// weil nicht nur Zusammenzeichnungen hochgeladen werden können.
		}

		case 'xplankonverter_konvertierung_anzeigen' : {
			if ((array_key_exists('planart', $GUI->formvars) AND $GUI->formvars['planart'] != 'Plan')) {
				$GUI->sanitize([
					'konvertierung_id' => 'int',
					'planart' => 'text'
				]);
				$GUI->konvertierungen = Konvertierung::find_konvertierungen(
					$GUI, $GUI->formvars['planart'],
					$GUI->plan_class,
					$GUI->formvars['konvertierung_id']
				);
			}
			$GUI->main = '../../plugins/xplankonverter/view/konvertierung.php';
			$GUI->output();
		} break;

		case 'xplankonverter_insert_textabschnitte' : {
			$GUI->main = 'Hinweis.php';
			$GUI->check_csrf_token();
			if ($GUI->formvars['konvertierung_id'] == '') {
				$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
			}
			else {
				$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
				if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
					$GUI->Hinweis = "Der Zugriff auf den Anwendungsfall ist nicht erlaubt.<br>
						Die Konvertierung mit der ID={$GUI->konvertierung->get('id')} gehört zur Stelle ID= {$GUI->konvertierung->get('stelle_id')}<br>
						Sie befinden sich aber in Stelle ID= {$GUI->Stelle->id}<br>
						Melden Sie sich mit einem anderen Benutzer an.";
				}
				else {
					$gml_extractor = new Gml_extractor($GUI->pgdatabase, 'placeholder', 'xplan_gmlas_' . $GUI->konvertierung->get_id());
					$GUI->konvertierung->insert_textabschnitte($gml_extractor);
				}
			}
			$GUI->output();
		} break;

		default : {
			$GUI->goNotExecutedInPlugins = true;		// in diesem Plugin wurde go nicht ausgeführt
		}
	}
	if (!$GUI->goNotExecutedInPlugins AND !empty($GUI->xlog)) {
		$GUI->xlog->close();
	};
}
?>