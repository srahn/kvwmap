<?php
	# GUI-Fuctions for plugin/xplankonverter
	/**
	* Trigger für Konvertierungen
	*/
	$GUI->trigger_functions['handle_konvertierung'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		#echo '<br>Handle Konvertierungen trigger mit fired: ' . $fired . ' event: ' . $event . ' layer: ' . print_r($layer, true) . ' oid: ' . $oid;
		$executed = true;
		$success = true;

		switch(true) {
			# Erzeuge Layergruppe und Verzeichnisse nach dem Erzeugen einer Konvertierung
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				#echo 'AFTER INSERT';
				$konvertierung = Konvertierung::find_by_id($GUI, 'id', $oid);
				# layer_group wird erstellt, wenn diese noch nicht existiert (wird derzeit nicht mehr gelöscht)
				$layer_group_id = $GUI->get(strtolower($layer_type) . '_layer_group_id');
				if (empty($layer_group_id)) {
					$konvertierung->create_layer_group('GML');
					$konvertierung->create_directories();
				}
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				#echo 'INSTEAD DELETE';
				$konvertierung = Konvertierung::find_by_id($GUI, 'id', $oid);
				$konvertierung->destroy();
			} break;

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};

	/**
	* Trigger für Shapefiles
	*/
	$GUI->trigger_functions['handle_shapes'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;

		switch(true) {
			# Passe die SRID der Spalte the_geom an den epsg_code des Shapefiles an.
			case ($fired == 'AFTER' AND $event == 'UPDATE') : {
				$shapefile = ShapeFile::find_by_id($GUI, 'id', $oid);
				if ($shapefile->geometry_column_srid() != $shapefile->get(epsg_code))
					$shapefile->update_geometry_srid();
			} break;

			case ($fired == 'BEFORE' AND $event == 'DELETE') : {
				$GUI->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_shapes Funktion aus.', false);
				$shapefile = ShapeFile::find_by_id($GUI, 'id', $oid);
				# Delete the layerdefinition in (rolleneinstellungen, layer, classes, styles, etc.)
				$shapefile->deleteLayer();
				# Delete the postgis data table that hold the data of the shape file
				$shapefile->deleteDataTable();
				# Delete the uploaded shape files
				$shapefile->deleteUploadFiles();
			} break;

			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				$GUI->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_shapes Funktion aus.', true);
				$GUI->add_message('warning', 'Lösche Gruppe wenn keine Layer mehr enthalten sind.');
			}

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};


	/**
	* Trigger für XP_Plan Objekte
	*/
	$GUI->trigger_functions['handle_xp_plan'] = function($fired, $event, $layer = '', $oid = 0, $old_dataset = array()) use ($GUI) {
		#echo '<br>Trigger Funktion handle_xp_plan ' . $fired . ' ' . $event . ' mit id: ' . $oid . ' aufgerufen.';
		$executed = true;
		$success = true;

		switch ($layer['layer_id']) {
			case XPLANKONVERTER_BP_PLAENE_LAYER_ID : {
				$planart = 'BP-Plan';
				$bereichtable = 'bp_bereich';
			} break;
			case XPLANKONVERTER_FP_PLAENE_LAYER_ID : {
				$planart = 'FP-Plan';
				$bereichtable = 'fp_bereich';
			} break;
			case XPLANKONVERTER_SO_PLAENE_LAYER_ID : {
				$planart = 'SO-Plan';
				$bereichtable = 'so_bereich';
			} break;
			case XPLANKONVERTER_RP_PLAENE_LAYER_ID : {
				$planart = 'RP-Plan';
				$bereichtable = 'rp_bereich';
			} break;
		}

		switch(true) {
			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				# echo '<br>Führe ' . $fired . ' ' . $event . ' mit gml_id: ' . $oid . ' in handle_xp_plan Funktion aus.';
				// check if uploaded files exist -> gmlas instead of manual entry
				$is_gmlas = false;
				$upload_dir = XPLANKONVERTER_FILE_PATH . 'tmp/' . session_id() . '/';
				if(is_dir($upload_dir)) {
					$files = glob($upload_dir . "*.gml");
					if (!empty($files) && $files != null) {
						$is_gmlas = true;
					}
				}
				
				$xp_plan = XP_Plan::find_by_id($GUI, 'gml_id', $oid, $planart);

				# Create Konvertierung and get konvertierung_id
				$konvertierung = new Konvertierung($GUI);
				$konvertierung->create(
					$xp_plan->get_anzeige_name(),
					$GUI->Stelle->epsg_code,
					$GUI->user->rolle->epsg_code,
					$planart,
					$GUI->Stelle->id,
					$GUI->user->id
				);
				
				$konvertierung_id = $konvertierung->get_id();
				$xp_plan->set('konvertierung_id', $konvertierung_id);
				//$xp_plan->update();
				$sql = "
						UPDATE xplan_gml.xp_plan
						SET konvertierung_id = " . $konvertierung_id . " 
						WHERE gml_id = '" . $oid . "';
					";
				#echo $sql;
				$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);
				
				$konvertierung = $konvertierung->find_by_id($GUI, 'id', $konvertierung_id);
				// $GUI->debug->show('Trigger ' . $fired . ' ' . $event . ' konvertierung planart: ' . $konvertierung->get('planart') . ' plan planart: ' . $konvertierung->plan->get('planart'), false);
				$konvertierung->set_status();
				// echo '<script>console.log("' . print_r($GUI->formvars, true) . '")</script>';
				# layer_schemaname needs to be an empty textfield in the layer definition
				//if (($GUI->formvars[$layer['layer_id'] . ';layer_schemaname;;;Text;;unknown;0'] == 'xplan_gmlas_tmp_' . $GUI->user->id) 
					//|| ($GUI->formvars[$layer['layer_id'] . ';layer_schemaname;;;Text;;text;0'] == 'xplan_gmlas_tmp_' . $GUI->user->id)) {
				if ($is_gmlas) {
					# renames to xplan_gmlas_ + konvertierung_id to make schema permanent
					//$konvertierung->rename_xplan_gmlas($GUI->user->id, $konvertierung_id);
					$sql = "
						ALTER SCHEMA xplan_gmlas_tmp_" . $GUI->user->id . "
							RENAME TO xplan_gmlas_" . $konvertierung_id . ";
					";
					#echo $sql;
					$ret = $GUI->pgdatabase->execSQL($sql, 4, 0);

					# Creates Bereiche for each Plan loaded with GMLAS
					$gml_extractor = new Gml_extractor($GUI->pgdatabase, 'placeholder', 'xplan_gmlas_' . $konvertierung_id);
					$gml_extractor->insert_into_bereich($bereichtable, $konvertierung_id, $GUI->user->id);

					# Inserts all existing Textabschnitte if they exist(no regel as potential link to plan)
					$result = $konvertierung->insert_textabschnitte($gml_extractor);
					if (!$result['success']) {
						$GUI->add_message('error', $result['msg']);
					}

					# Inserts regeln for each possible class loaded with GMLAS
					$gml_extractor->insert_all_regeln_into_db(
						$konvertierung_id,
						$GUI->Stelle->id,
						(array_key_exists('simplify_fachdaten_geom', $GUI->formvars) ? floatval($GUI->formvars['simplify_fachdaten_geom']) : null)
					);

					# directories to be created (if they do no exist yet e.g. for shape export)
					$konvertierung->create_directories($gml_extractor);

					# mv uploaded xplan_gml from tmp to uploaded_xplan_gml
					$upload_dir = XPLANKONVERTER_FILE_PATH . 'tmp/' . session_id() . '/';
					$store_dir = XPLANKONVERTER_FILE_PATH . $konvertierung_id . '/uploaded_xplan_gml/';
					$gml_file = scandir($upload_dir)[2];
					# Speichern der externen referenzen im Filesystem und Anpassen der Werte im Datensatz des Planes.
					exec('mv ' . XPLANKONVERTER_FILE_PATH . 'tmp/' . session_id() . '/* ' . XPLANKONVERTER_FILE_PATH . $konvertierung_id . '/uploaded_xplan_gml/');
				}
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				#echo '<br>Führe ' . $fired . ' ' . $event . ' in handle_xp_plan Funktion aus.';
				$konvertierung_id = $old_dataset['konvertierung_id'];
				#echo '<p>Lösche Konvertierung mit Id: ' . $konvertierung_id;
				$konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);

				$konvertierung->destroy();
			} break;

			default : {
				#echo '<br>Default Case in ' . $fired . ' ' . $event . ' Triggerfunktion, tuhe nichts!';
				$executed = false;
			}
		}
		#echo '<br>Trigger Funktion ' . $fired . ' ' . $event . ' ausgeführt: ' . ($executed ? 'Ja' : 'Nein');
		return array('executed' => $executed, 'success' => $success);
	};

	/**
	* Trigger für Regeln
	* @params $layer Array mit Angben des Layers aus der Datenbank
	*/
	$GUI->trigger_functions['handle_regel'] = function($fired, $event, $layer, $oid = 0, $old_dataset = array()) use ($GUI) {
		$executed = true;
		$success = true;
		include_once(PLUGINS . 'xplankonverter/model/regel.php');

		switch(true) {

			case ($fired == 'AFTER' AND $event == 'INSERT') : {
				$GUI->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus mit id: ' . $oid, false);
				$regel = Regel::find_by_id($GUI, 'id', $oid);
				$regel->create_gml_layer();
				$regel->set('konvertierung_id', $regel->konvertierung->get('id'));
				$regel->update();
				$regel->konvertierung->set_status();
			} break;

			case ($fired == 'AFTER' AND $event == 'UPDATE') : {
				$GUI->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus mit oid: ' . $oid, false);
				$regel = Regel::find_by_id($GUI, 'id', $oid);
				#$regel->delete_gml_layer();
				$regel->create_gml_layer();
				$regel->konvertierung->set_status();
			} break;

			case ($fired == 'INSTEAD' AND $event == 'DELETE') : {
				$GUI->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus.', false);
				$regel = Regel::find_by_id($GUI, 'id', $oid);
				$regel->destroy();
				$regel->konvertierung->set_status();
			} break;

			case ($fired == 'AFTER' AND $event == 'DELETE') : {
				$GUI->debug->show('Führe ' . $fired . ' ' . $event . ' in handle_regel Funktion aus.', false);
				if (empty($old_dataset['konvertierung_id'])) {
					# hole konvertierung_id ueber plan und bereich_gml_id
					$bereich = XP_Bereich::find_by_id($GUI, 'gml_id', $old_dataset['bereich_gml_id']);
					$plan = XP_Plan::find_by_id($GUI, 'gml_id', $bereich->get('gehoertzuplan'));
					$konvertierung_id = $plan->get('konvertierung_id');
				}
				else {
					$konvertierung_id = $old_dataset['konvertierung_id'];
				}

				$konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);
				#echo '<br>Konvertierung mit id: ' . $konvertierung->get('id') . ' gefunden.';
				$konvertierung->set_status();
			}

			default : {
				$executed = false;
			}
		}
		return array('executed' => $executed, 'success' => $success);
	};

	$GUI->xplankonverter_is_case_forbidden = function() use ($GUI) {
		$GUI->sanitize([
			'konvertierung_id' => 'int'
		]);
		$forbidden = false;
		if ($GUI->formvars['konvertierung_id'] == '') {
			#echo 'Dieser Link kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
			$forbidden = true;
		}
		else {
			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $GUI->formvars['konvertierung_id']);
			if (!isInStelleAllowed($GUI->Stelle, $GUI->konvertierung->get('stelle_id'))) {
				$forbidden = true;
			}
		}
		return $forbidden;
	};

	$GUI->xplankonverter_get_xplan_layers = function($planart) use ($GUI) {
		include_once(CLASSPATH . 'Layer.php');
		# ToDo pk: Hier prüfen ob die richtigen layer abgefragt werden, weil die Namen geändert wurden.
		# zusammenzeichnungen/fp_plan should check for layers named xp,bp_,fp,rp,so and the zusammenzeichnung layer.
		# An alternative filter could be built e.g. over a defined list of elements for each service-type
		$layers = Layer::find($GUI, "
				(
					(
						schema LIKE 'xplan_gml' AND
						LOWER(name) NOT LIKE '%\_textabschnitt' AND
						LOWER(name) NOT LIKE '%\_begruendungabschnitt' AND
						LOWER(name) NOT LIKE '%_aendert'" .
						//($planart == 'FP-Plan' ? " AND LOWER(name) NOT LIKE 'rp\_%'" : '') . "
						($planart == 'FP-Plan' ? " AND (
						LOWER(name) LIKE 'xp\_%' OR
						LOWER(name) LIKE 'bp\_%' OR
						LOWER(name) LIKE 'fp\_%' OR
						LOWER(name) LIKE 'rp\_%' OR
						LOWER(name) LIKE 'so\_%' OR
						LOWER(name) LIKE 'zusammenzeichnungen%'
						)
						AND LOWER(name) != 'rp_bereich'
						AND LOWER(name) != 'rp_plan'
						" : '') . "
					) OR
					(
						schema LIKE 'xplankonverter' AND
						LOWER(name) = 'geltungsbereiche'
					)
				) AND
				datentyp IN (0, 1, 2) AND
				connectiontype = 6
		", 'drawingorder');
		$xplan_layers = array_map(
			function ($layer) {
				return array(
					'id' => $layer->get('layer_id'),
					'name' => $layer->get('name'),
					'alias' => $layer->get('alias'),
					'datentyp' =>$layer->get('datentyp'),
					'schema' => $layer->get('schema'),
					'maintable' => $layer->get('maintable'),
					'geom_column' => $layer->get('geom_column')
				);
			},
			$layers
		);
		return $xplan_layers;
	};

	/**
	 * This function save the uploaded file on the server, test if it is a zip file
	 * and if it contain the correkt files. After this the files will be validated
	 * at XPlanung-Leitstelle. It removes uploaded files and returns messages in error case.
	 * If both files are valid, it creates a konvertierung, saves the validation reports,
	 * moves the data to uploaded_gml diretory, removes the tmp_dir and
	 * finish with success and a success message.
	*/
	$GUI->xplankonverter_validate_uploaded_zusammenzeichnungen = function($upload_file, $upload_path) use ($GUI) {
		#TODO: Hier kann man die hochgeladenen Datei ggf. noch umbenennen in Zusammenzeichnung.gml falls die anders heißt
		# Aber wie rausbekommen wie die Zusammenzeichnung heißt. Vorerst bleibt es bei der Konvention dass die Datei
		# Zusammenzeichnung.gml heißen muss.
		$konvertierung = new Konvertierung($GUI, $GUI->formvars['planart']); # Create empty Konvertierungsobjekt

		$upload_files = getAllFiles($upload_path);
		if ($konvertierung->get('planart') == 'RP-Plan') {
			$uploaded_xplangml_file = current($upload_files); // get the first file only
			if ($uploaded_xplangml_file != $upload_path . $konvertierung->config['plan_file_name']) {
				// Umbenennen der ersten Datei in den konfigurierten Namen.
				// ToDo: Umstellen, so dass auch der Name von $uploaded_xplangml_file verwendet werden kann
				// und nicht mehr umbenannt werden muss
				rename($uploaded_xplangml_file, $upload_path . $konvertierung->config['plan_file_name']);
			}
		}

		try {
			$upload_validation_result = $konvertierung->validate_uploaded_files($upload_path, $upload_files, ($GUI->formvars['digital_mv'] === 'true' ? 'MV' : null));
			if (!$upload_validation_result['success']) {
				$upload_validation_result['msg'] = $msg . ' ' . $upload_validation_result['msg']; 
				return $upload_validation_result;
			}
		} catch (Exception $ex) {
			return array(
				'success' => false,
				'msg' => 'Fehler bei der Validierung der hochgeladenen Dateien. ' . $ex
			);
		}

		$konvertierung->set_plan_file_name($upload_files);
		if ($GUI->formvars['skip_xplanvalidator'] === 'true') {
			$result_zusammenzeichnung = array(
				'success' => true,
				'msg' => 'Die Validierung gegen den XPlanValidator der XLeitstelle wurde übersprungen.'
			);
		}
		else {
			$result_zusammenzeichnung = $konvertierung->xplanvalidator($upload_path . $konvertierung->get_plan_file_name());
		}
		if (!$result_zusammenzeichnung['success']) {
			return $result_zusammenzeichnung;
		}

		$msg = $konvertierung->config['title'];

		if ($konvertierung->get('planart') == 'FP-Plan') {
			if (file_exists($upload_path . $zip_dir . 'Einzelfassungen.gml')) {
				rename($upload_path . $zip_dir . 'Einzelfassungen.gml', $upload_path . 'Geltungsbereiche.gml');
			}

			if (file_exists($upload_path . 'Geltungsbereiche.gml')) {
				$result_geltungsbereiche = $konvertierung->xplanvalidator($upload_path . 'Geltungsbereiche.gml');
				if (!$result_geltungsbereiche['success']) {
					return $result_geltungsbereiche;
				}
				$msg .= ' und Geltungsbereiche';
			}
		}
		$msg .= ' valide.';

		# Hochgeladene Zusammenzeichnung hat Prüfung im XPlanValidator bestanden
		# Create Konvertierung and get konvertierung_id
		# Bezeichnung wird später wenn die Zusammenzeichnung eingelesen wurde noch entsprechend der Zusammenzeichnung.gml aktualisiert.
		$konvertierung_id = $result = $konvertierung->create(
			$GUI->konvertierung->config['title'] . ' aus Datei ' . $upload_file['name'],
			$GUI->Stelle->epsg_code,
			$GUI->user->rolle->epsg_code,
			$GUI->formvars['planart'],
			$konvertierung->stelle_id, // wurde bei der Validierung des ags ermittelt (fn: validate_ags_in_name)
			$GUI->user->id,
			$konvertierung->get_plan_file_name()
		);
		if (!$result['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Anlegen der Konvertierung. ' . $result['msg']
			);
		}
		$konvertierung->create_directories();

		# move files from tmp to upload folder from konvertierung
		rename($upload_path, $konvertierung->get_file_path('uploaded_xplan_gml'));
		$msg .= '<br>Temporäre Dateien von ' . $upload_path . ' nach ' .  $konvertierung->get_file_path('uploaded_xplan_gml') . ' kopiert.';

		if ($GUI->formvars['skip_xplanvalidator'] === 'true') {
			$msg .= $result_zusammenzeichnung['msg'];
		}
		else {
			$result = $konvertierung->save_validation_report('Zusammenzeichnung', $result_zusammenzeichnung['report']);
			# Der Validierungsreport der Geltungsbereiche wird nicht gespeichert, weil es nur einen Report pro Konvertierung geben kann und für die Geltungsbereiche
			# auch nichts weiter interessantes drin stehen dürfte, weil ja keine Fachdaten drin sind.
			#$result = $konvertierung->save_validation_report('Geltungsbereiche', $result_geltungsbereiche['report']);
			if (!$result['success']) {
				return $result;
			}
			$msg .= $result['msg'];
		}

		return array(
			'success' => true,
			'msg' => $msg,
			'konvertierung' => $konvertierung
		);
	};

	//also rewrites Gml-id to always use GML_ and lowercase UUID (excludes uppercase UUIDs)
	$GUI->xplankonverter_get_new_gml_id = function($gml_id) use ($GUI) {
		$rewritten_gml_id = 'GML_' . strtolower(ltrim($gml_id,'GML_'));
		if (! array_key_exists($rewritten_gml_id, $GUI->xplan_gml_ids)) {
			$GUI->xplan_gml_ids[$rewritten_gml_id] = 'GML_' . uuid();
		}
		return $GUI->xplan_gml_ids[$rewritten_gml_id];
	};

	$GUI->xplankonverter_import_plan = function($xplan_gml_path, $variante) use ($GUI) {
		# Importiert die Datei, die im Job angegeben ist
		$file_zusammenzeichnung = $GUI->konvertierung->get_file_path($xplan_gml_path) . $GUI->konvertierung->get_plan_file_name();

		$gml_extractor = new Gml_extractor($GUI->pgdatabase, $file_zusammenzeichnung, 'xplan_gmlas_tmp_' . $GUI->user->id);

		$import_result = $gml_extractor->import_gml_to_db();
		if (!$import_result['success']) {
			$GUI->konvertierung->set('error_id', 1);
			$GUI->konvertierung->update();
			send_error('Fehler beim Einlesen der Datei in die Datenbank mit ogr2ogr_gmlas. Fehler: ' . $import_result['msg'], 'Planimport abgebrochen');
			exit;
		}

		$result = $GUI->konvertierung->get_num_gmlas_tmp_plaene();
		if (!$result['success']) {
			$GUI->konvertierung->set('error_id', 8);
			$GUI->konvertierung->update();
			send_error($result['msg']);
			exit;
		}

		if ($result['num_plaene'] == 0) {
			$GUI->konvertierung->set('error_id', 7);
			$GUI->konvertierung->update();
			send_error('Fehler beim Einlesen der Datei in die Datenbank. Es wurden keine Pläne importiert. Importbefehl: ' . $import_result['url']);
			exit;
		}
		return '';
	};

	$GUI->xplankonverter_reindex_gml_ids = function($konvertierung_id) use ($GUI) {
		$uploaded_xplan_gml_path =  XPLANKONVERTER_FILE_PATH . $konvertierung_id . '/uploaded_xplan_gml/';
		$reindexed_xplan_gml_path =  XPLANKONVERTER_FILE_PATH . $konvertierung_id . '/reindexed_xplan_gml/';
		if (! file_exists($reindexed_xplan_gml_path)) {
			mkdir($reindexed_xplan_gml_path, 0777);
		}
		$read_handle = fopen($uploaded_xplan_gml_path . $GUI->konvertierung->get_plan_file_name(), "r");
		$write_handle = fopen($reindexed_xplan_gml_path . $GUI->konvertierung->get_plan_file_name(), "w");
		$GUI->xplan_gml_ids = array();
		if ($read_handle) {
			while (($line = fgets($read_handle)) !== false) {
				$gml_id = get_first_word_after($line, 'id=', '"', '"');
				if ($gml_id == '') {
					$gml_id = ltrim(get_first_word_after($line, 'href=', '"', '"'), '#');
				}
				fputs($write_handle, ($gml_id == '' ? $line : str_replace($gml_id, $GUI->xplankonverter_get_new_gml_id($gml_id), $line)));
			}
			fclose($read_handle);
			fclose($write_handle);
		}
		else {
			return array(
				'success' => false,
				'msg' => "Fehler beim Öffnen der Datei ${uploaded_xplan_gml_path}${$GUI->konvertierung->get_plan_file_name()} zum Umbenennen der gml_id's."
			);
		}
		return array(
			'success' => true,
			'msg' => 'GML-IDs in GML-Datei ' . $reindexed_xplan_gml_path . $GUI->konvertierung->get_plan_file_name() . 'erfolgreich umbennannt.'
		);
	};

	$GUI->xplankonverter_create_plaene = function() use ($GUI) {
		# Anlegen von Plan und Bereich der Zusammenzeichnung
		$result = $GUI->konvertierung->create_plaene_from_gmlas('xplan_gmlas_tmp_' . $GUI->user->id, $GUI->plan_class, $GUI->konvertierung->get_id(), true);
		if (!$result['success']) {
			$GUI->konvertierung->set('error_id', 3);
			$GUI->konvertierung->update();
			send_error('Fehler beim Anlegen des Planes in der Datenbank. Fehler: ' . $result['msg']);
			exit;
		}

		$GUI->konvertierung->get_plan();
		if ($GUI->konvertierung->plan === false) {
			$GUI->konvertierung->set('error_id', 3);
			$GUI->konvertierung->update();
			send_error('Nach dem Einlesen des Planes mit GMLAS konnte dieser nicht gefunden werden. ' . $import_result['msg']);
			exit;
		}

		if ($GUI->konvertierung->get_aktualitaetsdatum() == '') {
			$GUI->konvertierung->set('error_id', 6);
			send_error('Der Plan ' . $GUI->konvertierung->get('bezeichnung') . ' (konvertierung_id: ' . $GUI->konvertierung->get_id() . ', gml_id: ' . $GUI->konvertierung->plan->get('gml_id') . ') hat kein ' . ucfirst($GUI->konvertierung->get_plan_attribut_aktualitaet()) . '. Das muss in der XPlan-GML angepasst werden. Anschließend kann der Plan erneut hochgeladen werden. Die Attribute ' . natural_join($GUI->konvertierung->config['plan_attribut_aktualitaet'], ', ', ' und ') . ' waren alle leer!');
			exit;
		}

		# Übername der Plandaten in permanentes Schema xplan_gmlas_$GUI->konvertierung->get_id()
		$result = $GUI->konvertierung->rename_xplan_gmlas($GUI->user->id, $GUI->konvertierung->get_id());
		if (! $result['success']) {
			$GUI->konvertierung->set('error_id', 4);
			$GUI->konvertierung->update();
			send_error('Fehler beim Umbennen des Import-Schemas xplan_gmlas_tmp_' . $GUI->user->id . ' nach xplan_gmlas_' . $GUI->konvertierung->get_id() . ' Fehler: ' . $result['msg']);
			exit;
		}

		$gml_extractor = new Gml_extractor($GUI->pgdatabase, '', 'xplan_gmlas_tmp_' . $GUI->user->id);
		$gml_extractor->gmlas_schema = 'xplan_gmlas_' . $GUI->konvertierung->get_id();
		$GUI->konvertierung->insert_textabschnitte($gml_extractor);
		$debug_log = $gml_extractor->insert_all_regeln_into_db(
			$GUI->konvertierung->get_id(),
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
				exit;
			}
			$msg .= ' und Geltungsbereiche';
		}

		$upload_path = $GUI->konvertierung->get_file_path('uploaded_xplan_gml');
		$externereferenz_json = json_decode($GUI->konvertierung->plan->get('externereferenz_json'), JSON_OBJECT_AS_ARRAY);
		foreach ($externereferenz_json as $item) {
			if (!empty($item['referenzurl'])) {
				$referenzfile = pathinfo($item['referenzurl'], PATHINFO_BASENAME);
				$srcfile = $GUI->konvertierung->get_file_path('uploaded_xplan_gml') . $referenzfile;
				$dstfile = XPLANKONVERTER_FILE_PATH . 'plaene/' . $referenzfile;
				if (!file_exists($srcfile)) {
					send_error('Die in der externen Referenz bezeichnete Datei ' . $referenzfile . ' befand sich nicht in der hochgeladenen ZIP-Datei.', 'Planerzeugung abgebrochen');
					exit;
				}
				rename($srcfile, $dstfile);
				$GUI->create_dokument_vorschau('local_img', pathinfo($dstfile));
			}
		}
		return $msg;
	};

	$GUI->xplankonverter_gml_generieren = function($konvertierung) use ($GUI) {
		$success = true;
		$messages = array();
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
		return array(
			'success' => $success,
			'msg' => $messages
		);
	};

	// MARK: create_geoweb_service
	$GUI->xplankonverter_create_geoweb_service = function($xplan_layers, $ows_onlineresource) use ($GUI) {
		global $admin_stellen;

		# Frage xplan_layer_with_content ab
		$xp_plan = new XP_Plan($GUI, $GUI->formvars['planart']);
		$result = $xp_plan->get_layers_with_content($xplan_layers);
		if (! $result['success']) {
			return $result;
		}

		# Lade das MapObjekt (nur mit $LayerIds)
		$GUI->class_load_level = 2;
		$GUI->formvars['only_layer_ids'] = implode(', ', array_map(function($layer) { return $layer['id']; }, $result['layers_with_content']));
		$GUI->service_layernames = array_keys($result['layers_with_content']); // set layernames array for output in view show_service_data.php
		$start_stelle_id = $GUI->Stelle_ID; // speichern für späteren Gebrauch
		$admin_stelle = new Stelle($admin_stellen[0], $GUI->pgdatabase);
		$GUI->Stelle_ID = $admin_stelle->id; // setze Stelle_ID von Adminstelle zur Erzeugung des MapFiles der Adminstelle
		$GUI->loadMap('DataBase', array(), true); // Layer name immer aus Attribute Name

		# Setze globale Metadaten im MapObjekt des Dienstes der Adminstelle
		#$GUI->xlog->write('Setze Metadaten im MapObjekt des Landesdienstes.');
		$bb = $admin_stelle->MaxGeorefExt;
		$GUI->map->set('name', sonderzeichen_umwandeln(PUBLISHERNAME));
		$GUI->map->extent->setextent($bb->minx, $bb->miny, $bb->maxx, $bb->maxy);
		$GUI->map->setMetaData("ows_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
		#$GUI->write_xlog('create_geoweb_service Landesdienst, set ows_extent: ' . $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
		$GUI->map->setMetaData("ows_title", $admin_stelle->ows_title);
		$GUI->map->setMetaData("ows_abstract", $admin_stelle->ows_abstract . ' Letzte Aktualisierung: ' . date('m.Y') . ' (letzte Aktualisierung des landesweiten Dienstes, nicht ' . $GUI->konvertierung->config['genitiv_plural']);
		$GUI->map->setMetaData("ows_onlineresource", $ows_onlineresource);
		$GUI->map->setMetaData("ows_service_onlineresource", $ows_onlineresource);
		$GUI->map->setMetaData("ows_contactorganization", $admin->stelle->ows_contactorganization ?: OWS_CONTACTORGANIZATION);
		$GUI->map->setMetaData("ows_contactperson", $admin->stelle->ows_contactperson ?: OWS_CONTACTPERSON);
		$GUI->map->setMetaData("ows_contactposition", $admin->stelle->ows_contactposition ?: OWS_CONTACTPOSITION);
		$GUI->map->setMetaData("ows_contactelectronicmailaddress", $admin->stelle->ows_contactelectronicmailaddress ?: OWS_CONTACTELECTRONICMAILADDRESS);
		$GUI->map->setMetaData("ows_contactvoicetelephone", $admin->stelle->ows_contactvoicephone ?: OWS_CONTACTVOICETELEPHONE);
		$GUI->map->setMetaData("ows_contactfacsimiletelephone", $admin->stelle->ows_contactfacsimile ?: OWS_CONTACTFACSIMILETELEPHONE);
		$GUI->map->setMetaData("ows_stateorprovince", $admin->stelle->ows_contactadministrativearea ?: OWS_STATEORPROVINCE);
		$GUI->map->setMetaData("ows_address", $admin->stelle->ows_contactaddress ?: OWS_ADDRESS);
		$GUI->map->setMetaData("ows_postcode", $admin->stelle->ows_contactpostalcode ?: OWS_POSTCODE);
		$GUI->map->setMetaData("ows_city", $admin->stelle->ows_contactcity ?: OWS_CITY);
		$GUI->map->setMetaData("ows_country", OWS_COUNTRY);
		$GUI->map->web->set('header', 'templates/header.html');
		$GUI->map->web->set('footer', 'templates/footer.html');
		# Setze Metadaten der Layer
		for ($i = 0; $i < $GUI->map->numlayers; $i++) {
			$layer = $GUI->map->getLayer($i);
			$layer->set('header', 'templates/' . $layer->name . '_head.html');
			$layer->set('template', 'templates/' . $layer->name . '_body.html');
			# Extent mit Ausdehnung von adminstelle überschreiben
			$layer->setMetaData("ows_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
			$layer->setMetaData("ows_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);

			$layer_id = $layer->getMetadata('kvwmap_layer_id');
			$layerObj = Layer::find_by_id($GUI, $layer_id);
			if (!$layerObj) {
				return array(
					'success' => false,
					'msg' => 'Fehler bei der Erzeugung des Web-Services. Layer mit der ID ' . $layer_id . ' wurde nicht gefunden!'
				);
			}
			if ($layerObj->get('write_mapserver_templates') == 'generic') {
				# Set generic Data sql for layer
				$result = $layerObj->get_generic_data_sql();
				if ($result['success']) {
					$layer->set('data', $result['data_sql']);
				}
				else {
					$result['msg'] = 'Fehler bei der Erstellung der Map-Datei in Funktion get_generic_data_sql! ' . $result['msg'];
					return $result;
				}
			}
			$layer->set('data', str_replace('< 9999 OR', '> 0 OR', $layer->data));
			if (strpos($layer->data, 'xplankonverter.konvertierungen k') !== false) {
				$layer->set('data', str_ireplace(' WHERE ', ' WHERE (', $layer->data));
				$layer->set('data', str_ireplace(') as foo using unique', ') AND k.veroeffentlicht) AS foo using unique', $layer->data)); 
			}
		}

		$GUI->Stelle_ID = $start_stelle_id; // setze Stelle_ID zurück auf die ID der Stelle die diese Funktion aufgerufen hat.
		$geoweb_service_updated_at = Date('Y-m-d H:i:s');
		return array(
			'success' => true,
			'mapfile' => $GUI->konvertierung->config['mapfile_name'],
			'geoweb_service_updated_at' => $geoweb_service_updated_at
		);
	};

	$GUI->xplankonverter_create_geoweb_service_alt = function($xplan_layers, $ows_onlineresource) use ($GUI) {
		global $admin_stellen;

		$GUI->class_load_level = 2;
		$GUI->loadMap('DataBase');

		$GUI->xlog->write('Setze Metadaten im MapObjekt des Landesdienstes.');

		$admin_stelle = new Stelle($admin_stellen[0], $GUI->pgdatabase);
		$bb = $admin_stelle->MaxGeorefExt;
		$GUI->map->set('name', sonderzeichen_umwandeln(PUBLISHERNAME));
		$GUI->map->extent->setextent($bb->minx, $bb->miny, $bb->maxx, $bb->maxy);
		$GUI->map->setMetaData("ows_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
		$GUI->write_xlog('create_geoweb_service Landesdienst, set ows_extent: ' . $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
		$GUI->map->setMetaData("ows_title", $admin_stelle->ows_title);
		$GUI->map->setMetaData("ows_abstract", $admin_stelle->ows_abstract . ' Letzte Aktualisierung: ' . date('m.Y') . ' (letzte Aktualisierung des landesweiten Dienstes, nicht der einzelnen Zusammenzeichnungen der Flächennutzungspläne)');
		$GUI->map->setMetaData("ows_onlineresource", $ows_onlineresource);
		$GUI->map->setMetaData("ows_service_onlineresource", $ows_onlineresource);
		$GUI->map->setMetaData("ows_contactorganization", $admin->stelle->ows_contactorganization ?: OWS_CONTACTORGANIZATION);
		$GUI->map->setMetaData("ows_contactperson", $admin->stelle->ows_contactperson ?: OWS_CONTACTPERSON);
		$GUI->map->setMetaData("ows_contactposition", $admin->stelle->ows_contactposition ?: OWS_CONTACTPOSITION);
		$GUI->map->setMetaData("ows_contactelectronicmailaddress", $admin->stelle->ows_contactelectronicmailaddress ?: OWS_CONTACTELECTRONICMAILADDRESS);
		$GUI->map->setMetaData("ows_contactvoicetelephone", $admin->stelle->ows_contactvoicephone ?: OWS_CONTACTVOICETELEPHONE);
		$GUI->map->setMetaData("ows_contactfacsimiletelephone", $admin->stelle->ows_contactfacsimile ?: OWS_CONTACTFACSIMILETELEPHONE);
		$GUI->map->setMetaData("ows_stateorprovince", $admin->stelle->ows_contactadministrativearea ?: OWS_STATEORPROVINCE);
		$GUI->map->setMetaData("ows_address", $admin->stelle->ows_contactaddress ?: OWS_ADDRESS);
		$GUI->map->setMetaData("ows_postcode", $admin->stelle->ows_contactpostalcode ?: OWS_POSTCODE);
		$GUI->map->setMetaData("ows_city", $admin->stelle->ows_contactcity ?: OWS_CITY);
		$GUI->map->setMetaData("ows_country", OWS_COUNTRY);
		$GUI->map->web->set('header', 'templates/header.html');
		$GUI->map->web->set('footer', 'templates/footer.html');

		$xp_plan = new XP_Plan($GUI, $GUI->formvars['planart']);
	
		$result = $xp_plan->get_layers_with_content($xplan_layers);
		if (! $result['success']) {
			return $result;
		}

		$GUI->service_layers = $result['layers_with_content'];
		$GUI->service_layernames = array_keys($GUI->service_layers);
		$GUI->xlog->write('service_layernames: ' . implode(', ' . $GUI->service_layer_names));
		$layers_to_remove = array();

		for ($i = 0; $i < $GUI->map->numlayers; $i++) {
			$layer = $GUI->map->getLayer($i);
			#$GUI->xlog->write('gui map layer: ' . $layer->name);	
			if (in_array($layer->name, $GUI->service_layernames)) {
				$layer->set('header', 'templates/' . $layer->name . '_head.html');
				$layer->set('template', 'templates/' . $layer->name . '_body.html');
				# Extent mit Ausdehnung von adminstelle überschreiben
				$layer->setMetaData("ows_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
				$layer_id = $layer->getMetadata('kvwmap_layer_id');
				$layerObj = Layer::find_by_id($GUI, $layer_id);
				if (!$layerObj) {
					return array(
						'success' => false,
						'msg' => 'Fehler bei der Erzeugung des Web-Services. Layer mit der ID ' . $layer_id . ' wurde nicht gefunden!'
					);
				}

				if ($layerObj->get('write_mapserver_templates') == 'generic') {
					# Set generic Data sql for layer
					$result = $layerObj->get_generic_data_sql();
					if ($result['success']) {
						$layer->set('data', $result['data_sql']);
					}
					else {
						$result['msg'] = 'Fehler bei der Erstellung der Map-Datei in Funktion get_generic_data_sql! ' . $result['msg'];
						return $result;
					}
				}
				else {
					$layer->set('data', str_replace('< 9999 OR', '> 0 OR', $layer->data));
				}
			}
			else {
				$GUI->map->removeLayer($i);
				$i--;
			}	
		}

		return array(
			'success' => true,
			'mapfile' => MAPFILENAME . '.map'
		);
	};

	/**
	 * Erzeugt die Metadatendokumente des Geodatensatzes und der Dienste, die alle Pläne des xplan_gml-Schemas
	 * der Planart $GUI->formvars['planart'] enthalten
	 * @param array $md metadata Metadatenobjekt aus dem plugin metadata mit vorgegebenen Werten für Metadaten
	 */
	$GUI->xplankonverter_create_metadata_documents = function($md) use ($GUI) {
		$GUI->xlog('GUI->xplankonverter_create_metadata_documents für Landesdienst');
		global $admin_stellen;
		$current_time = time();
		$pg_object = new PgObject($GUI, 'xplankonverter', 'plan_services');

		$plan_object = new XP_Plan($GUI, $GUI->formvars['planart']);
		if ($GUI->konvertierung->get('planart') == 'FP-Plan') {
			$plan_object->get_extent(OWS_SRS, 'p.zusammenzeichnung AND k.veroeffentlicht'); # Pläne mit Attribut zusammenzeichnung = true
		}
		else {
			$plan_object->get_extent(OWS_SRS); # Alle Pläne in Tabelle der Planart
		}
		$plan_service = $pg_object->find_by('planart', $GUI->formvars['planart']);

		if (! $plan_service) {
			$plan_service->create(array(
				'planart' => $GUI->formvars['planart'],
				'metadata_dataset_uuid' => uuid(),
				'metadata_viewservice_uuid' => uuid(),
				'metadata_downloadservice_uuid' => uuid()
			));
		}

		$admin_stelle = new stelle($admin_stellen[0], $GUI->Stelle->database);
		$md->set('stellendaten', $admin_stelle->getstellendaten());
		$md->set('uuids', array(
			'metadata_dataset_uuid' => $plan_service->get('metadata_dataset_uuid'),
			'metadata_viewservice_uuid' => $plan_service->get('metadata_viewservice_uuid'),
			'metadata_downloadservice_uuid' => $plan_service->get('metadata_downloadservice_uuid')
		));
		$md->set('md_date', date('Y-m-d', $current_time));
		$md->set('id_cite_title', $admin_stelle->ows_title);
		$md->set('date_title', 'Datum');
		$md->set('namespace', $admin_stelle->ows_namespace);
		$md->set('date_de', date('d.m.Y', $current_time));
		$md->set('id_cite_date', date('Y-m-d', $current_time));
		if ($GUI->konvertierung->get('planart') == 'FP-Plan') {
			//$abstract_zusatz = ' Es handelt sich um einen Gebrauchsdienst der Zusammenzeichnung von Planelementen mit je einem Layer pro XPlanung-Klasse. Das ' . ucfirst($md->get('date_title')) . " der letzten Änderung ist " . $md->get('date_de') . '. Die Umringe der Änderungspläne sind im Layer Geltungsbereiche zusammengefasst. Die Daten wurden im Rahmen des Projektes PlanDigital zusammengestellt und durch das testportal-plandigital.de bereitgestellt.';
		}
		else {
			$abstract_zusatz = ' Es handelt sich um einen Gebrauchsdienst der Planelementen mit je einem Layer pro XPlanung-Klasse. Das ' . ucfirst($md->get('date_title')) . " der letzten Änderung ist " . $md->get('date_de') . '.';
		}

		$md->set('id_abstract', array(
			//'dataset' => $admin_stelle->ows_abstract . $abstract_zusatz,
			//'viewservice' => $admin_stelle->ows_abstract . $abstract_zusatz,
			//'downloadservice' => $admin_stelle->ows_abstract . $abstract_zusatz,
			'dataset' => defined('XPLAN_ABSTRACT_ZUSAMMENZEICHNUNGEN_DATASET') ? XPLAN_ABSTRACT_ZUSAMMENZEICHNUNGEN_DATASET : ($admin_stelle->ows_abstract . $abstract_zusatz),
			'viewservice' => defined('XPLAN_ABSTRACT_ZUSAMMENZEICHNUNGEN_VIEWSERVICE') ? XPLAN_ABSTRACT_ZUSAMMENZEICHNUNGEN_VIEWSERVICE : ($admin_stelle->ows_abstract . $abstract_zusatz),
			'downloadservice' =>  defined('XPLAN_ABSTRACT_ZUSAMMENZEICHNUNGEN_DOWNLOADSERVICE') ? XPLAN_ABSTRACT_ZUSAMMENZEICHNUNGEN_DOWNLOADSERVICE : ($admin_stelle->ows_abstract . $abstract_zusatz)
		));
		$md->set('version', floatval(implode('.', array_slice(explode('/', XPLAN_NS_URI), -2))));
		$md->set('extents', $plan_object->extents);
		$md->set('service_layer_name', sonderzeichen_umwandeln($admin_stelle->get('Bezeichnung')));
		$md->set('onlineresource', URL . 'ows/fplaene?');
		$md->set('dataset_browsegraphic', URL . APPLVERSION . 'custom/graphics/Vorschau_Datensatz.png');
		$md->set('viewservice_browsegraphic', $md->get('onlineresource') . "Service=WMS&amp;Request=GetMap&amp;Version=1.1.0&amp;Layers=" . $plan_object->tableName . "&amp;FORMAT=image/png&amp;SRS=EPSG:" . $md->get('stellendaten')['epsg_code'] . "&amp;BBOX=" . implode(',', $md->get('extents')[$md->get('stellendaten')['epsg_code']]) . "&amp;WIDTH=300&amp;HEIGHT=300");
		$md->set('downloadservice_browsegraphic', URL . APPLVERSION . 'custom/graphics/Vorschau_Downloadservice.png');
		$md->set('geographicIdentifier', '');
		$md->set('withRegionalKeyword', true);
		$metaDataCreator = new MetaDataCreator($md);
		return array(
			'metaDataGeodatensatz' => $metaDataCreator->createMetadataGeodatensatz(),
			'metaDataDownload' => $metaDataCreator->createMetadataDownload(),
			'metaDataView' =>  $metaDataCreator->createMetadataView()
		);
	};

	$GUI->xplankonverter_konvertierung = function($konvertierung_id, $output) use ($GUI) {
		$GUI->sanitize([
			'konvertierung_id' => 'int'
		]);
		$GUI->title = str_replace('an', 'äne', $GUI->title);
		$GUI->main = '../../plugins/xplankonverter/view/plaene.php';
		if ($konvertierung_id == '') {
			$GUI->Hinweis = 'Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
			$GUI->main = 'Hinweis.php';
			$GUI->data = array(
				'success' => false,
				'msg' => $GUI->Hinweis
			);
			if ($output) {
				$GUI->output();
			}
			return true;
		}
		else {
			$GUI->konvertierung = Konvertierung::find_by_id($GUI, 'id', $konvertierung_id);
			if ($GUI->konvertierung->data === false) {
				$GUI->Fehlermeldung = "Die Konvertierung mit der ID={$konvertierung_id} wurde nicht gefunden.";
				$GUI->data = array(
					'success' => false,
					'msg' => $GUI->Fehlermeldung
				);
				if ($output) {
					$GUI->output();
				}
				return true;
			}
			if ($GUI->konvertierung->plan === false) {
				$GUI->Fehlermeldung = "Zur Konvertierung mit der ID={$konvertierung_id} wurde keinen Plan der Planart {$GUI->konvertierung->get('planart')} gefunden!";
				$GUI->data = array(
					'success' => false,
					'msg' => $GUI->Fehlermeldung
				);
				if ($output) {
					$GUI->output();
				}
				return true;
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
				if ($output) {
					$GUI->output();
				}
				return true;
			}
			else {
				try {
					$GUI->konvertierung->reset_mapping();
					$GUI->konvertierung->mapping();
					#$GUI->konvertierung->set_historie();
					$GUI->konvertierung->set_status(
						($GUI->konvertierung->validierung_erfolgreich() ? 'Konvertierung abgeschlossen' : 'Konvertierung abgebrochen')
					);
					$GUI->xplan_layers = $GUI->xplankonverter_get_xplan_layers($GUI->formvars['planart']);
					$GUI->konvertierung->create_themenauswahl($GUI->xplan_layers);
					# Validierungsergebnisse anzeigen.
					$GUI->main = '../../plugins/xplankonverter/view/validierungsergebnisse.php';
				}
				catch (Exception $e) {
					send_error($e->getMessage());
					return false;
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
				send_error('Die Validierung war nicht erfolgreich. Validierungsergebnisse <a href="index.php?go=xplankonverter_validierungsergebnisse&konvertierung_id=' . $konvertierung_id . '">anzeigen</a> Die Nachricht wird gesendet an: ' . $GUI->konvertierung->get_arl_email());
				return true;
			}
			if ($output) {
				echo json_encode($response);
			}
		}
		else {
			if ($output) {
				$GUI->output();
			}
		}
		return true;
	};

	$GUI->xplankonverter_remove_failed_konvertierungen = function() use ($GUI) {
		$konvertierungen = Konvertierung::find_konvertierungen($GUI, $GUI->formvars['planart'], $GUI->plan_class, $GUI->plan_attribut_aktualitaet);
		foreach($konvertierungen['faulty'] AS $faulty_zusammenzeichnung) {
			$GUI->debug->write('Lösche zuvor fehlgeschlagene Konvertierung id: ', $faulty_zusammenzeichnung->get('id'));
			$faulty_zusammenzeichnung->destroy();
		}

		return array(
			'success' => true,
			'msg' => 'Fehlerhafte Konvertierungen gelöscht.'
		);
	};
	
	$GUI->xplankonverter_remove_old_konvertierungen = function() use ($GUI) {
		$zusammenzeichnungen = Konvertierung::find_konvertierungen($GUI, $GUI->formvars['planart'], $GUI->plan_class, $GUI->plan_attribut_aktualitaet);
		foreach($zusammenzeichnungen['draft'] AS $draft_zusammenzeichnung) {
			$GUI->debug->write('Lösche alte (draft) Konvertierung id: ', $draft_zusammenzeichnung->get('id'));
			$draft_zusammenzeichnung->destroy();
		}

		return array(
			'success' => true,
			'msg' => 'Fehlerhafte Konvertierungen gelöscht.'
		);
	};

	$GUI->xplankonverter_send_notification = function($msg) use ($GUI) {
		$from_name = 'XPlan-Server PlanDigital';
		$from_email = 'plandigital@arl-we.niedersachsen.de';
		$to_email = 'plandigital@arl-we.niedersachsen.de';
		$cc_email = 'peter.korduan@gdi-service.de';
		$reply_email = null;
		$subject = 'Fehler in Plandigital';
		$message 	= "Sehr geehrte Damen und Herren,\r\n\r\n";
		$message .= $msg . "\r\n";
		$attachment = '';
		$mode = 'sendEmail async';
		$smtp_server = 'smtp.ionos.de';
		$smtp_port = '587';
		if (mail_att($from_name, $from_email, $to_email, $cc_email, $reply_email, $subject, $message, $attachment, $mode, $smtp_server, $smtp_port)) {
			return array(
				'success' => true,
				'msg' => 'Benachrichtigung versendet.'
			);
		}
		else {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Versenden der E-Mail zum Update der Zusammenzeichnung!'
			);
		}
	};

	$GUI->xplankonverter_stelle_kontaktdaten = function() use ($GUI) {
		$GUI->checkCaseAllowed('xplankonverter_stelle_kontaktdaten');
		if ($GUI->formvars['stelle_id'] == $GUI->Stelle->id AND $GUI->formvars['action'] == 'Speichern') {
			$stelle_obj = stelle::find($GUI, 'id=' . $GUI->Stelle->id)[0];
			$stelle_obj->update_attr(
				array(
					"ows_distributionorganization = '" . $GUI->formvars['ows_distributionorganization'] . "'",
					"ows_distributionaddress = '" . $GUI->formvars['ows_distributionaddress'] . "'",
					"ows_distributionpostalcode = '" . $GUI->formvars['ows_distributionpostalcode'] . "'",
					"ows_distributioncity = '" . $GUI->formvars['ows_distributioncity'] . "'",
				),
				true,
				'id = ' . $GUI->Stelle->id
			);
			$GUI->Stelle->data = $stelle_obj->data;
		}
		$GUI->titel = 'Stellendaten';
		$GUI->main = '../../plugins/xplankonverter/view/stelle_kontaktdaten.php';
	};

	$GUI->xplankonverter_upload_zusammenzeichnung = function($upload_file, $variante = 'default') use ($GUI) {
		$upload_path = 	XPLANKONVERTER_FILE_PATH . 'tmp/zusammenzeichnung_' . random_int(100000, 999999) . '/';

		$success = false;
		$deb_msg = '';
		if (!is_dir($upload_path)) {
			try {
				mkdir($upload_path, 0770, true);
				$deb_msg .= '<br>Verzeichnis ' . $upload_path . ' angelegt.';
			} catch (Exception $ex) {
				return array(
					'success' => false,
					'msg' => 'Das Verzeichnis ' . $upload_path . ' kann auf dem Server nicht angelegt werden. ' . $ex
				);
			}
		}

		if ($upload_file['tmp_name'] != $upload_file['name']) {
			$deb_msg .= '<br>move ' . $upload_file['tmp_name'] . ' nach ' . $upload_path . $upload_file['name'];
			if (is_uploaded_file($upload_file['tmp_name'])) {
				$ok = move_uploaded_file($upload_file['tmp_name'], $upload_path . $upload_file['name']);
			}
			else {
				$ok = copy($upload_file['tmp_name'], $upload_path . $upload_file['name']);
			}
			if (!$ok) {
				return array(
					'success' => false,
					'msg' => 'Die hochgeladene Datei kann nicht als Datei ' . $upload_path . $upload_file['name'] . ' auf dem Server gespeichert werden. ' . $ex
				);
			}
		}

		if (is_zip_file($upload_path . $upload_file['name'])) {
			$cmd =
					'unzip -o '
					. escapeshellarg($upload_path . $upload_file['name'])
					. ' -d '
					. escapeshellarg($upload_path)
					. ' 2>&1';
			exec($cmd, $output, $exitCode);
			if ($exitCode > 1) {
    		$msg = '<br>Fehler beim Entpacken exitCode: ' . $exitCode . '<br>';
				$msg .= implode("<br>", $output);
				$files = scandir($upload_path);
				$files = array_diff($files, ['.', '..']);
				$filesString = implode("<br>", $files);
				$msg .= '<br>files nach extract:' . $filesString;
				$msg .= '<br>' . is_writable($upload_path) ? 'dir writable' : 'dir NOT writable';
				return array(
					'success' => false,
					'msg' => $msg
				);
			}

			// $zip = new ZipArchive;
			// if ($zip->open($upload_path . $upload_file['name']) === FALSE) {
			// 	return array(
			// 		'success' => false,
			// 		'msg' => 'Die Zip-Datei ' . $upload_path . $upload_file['name'] . ' kann nicht geöffnet werden. ' . $ex
			// 	);
			// }
			// $msg .= 'Extract ' . $upload_path . $upload_file['name'] . ' nach ' . $upload_path;

			// try {

			// } catch (Exception $ex) {
			// 	return array(
			// 		'success' => false,
			// 		'msg' => 'Die Zip-Datei ' . $upload_path . $upload_file['name'] . ' kann nicht nach ' . $upload_path . ' ausgepakt werden. ' . $ex
			// 	);
			// }
			// $zip->close();
		}
		else {
			return array(
				'success' => false,
				'msg' => $deb_msg . 'Die Datei ' . $upload_path . $upload_file['name'] . ' ist keine Zip-Datei. Laden Sie die Zusammenzeichnung und ggf. Geltungsbereiche in einer Zip-Datei hoch.'
			);
		}

		try {
			if (strpos($upload_path, XPLANKONVERTER_FILE_PATH . 'tmp/zusammenzeichnung_') !== false AND file_exists($upload_path . '__MACOSX')) {
				exec('rm -R ' . $upload_path . '__MACOSX');
			}
			unlink($upload_path . $upload_file['name']);
		} catch (Exception $ex) {
			return array(
				'success' => false,
				'msg' => 'Kann die hochgeladene Zip-Datei: ' . $upload_path . $upload_file['name'] . ' nicht löschen.' . $ex
			);
		}

		return array(
			'success' => true,
			'msg' => 'Upload erfolgreich',
			'upload_path' => $upload_path
		);
	};

?>