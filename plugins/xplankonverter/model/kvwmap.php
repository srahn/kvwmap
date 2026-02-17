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
				$xp_plan->update();
				
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
	$GUI->xplankonverter_validate_uploaded_zusammenzeichnungen = function($upload_file, $tmp_dir) use ($GUI) {
		$success = false;
		if (!file_exists($tmp_dir)) {
			$deb_msg = '<br>Dir ' . $tmp_dir . ' angelegt.';
			try {
				mkdir($tmp_dir, 0660);
			} catch (Exception $ex) {
				return array(
					'success' => false,
					'msg' => 'Das Verzeichnis ' . $tmp_dir . ' kann auf dem Server nicht angelegt werden. ' . $ex
				);
			}
		}

		try {
			$deb_msg .= '<br>move ' . $upload_file['tmp_name'] . ' nach ' . $tmp_dir . $upload_file['name'];
			move_uploaded_file($upload_file['tmp_name'], $tmp_dir . $upload_file['name']);
		} catch (Exception $ex) {
			return array(
				'success' => false,
				'msg' => 'Die hochgeladene Datei kann nicht als Datei ' . $tmp_dir . $upload_file['name'] . ' auf dem Server gespeichert werden. ' . $ex
			);
		}

		if (is_zip_file($tmp_dir . $upload_file['name'])) {
			$zip = new ZipArchive;
			if ($zip->open($tmp_dir . $upload_file['name']) === FALSE) {
				return array(
					'success' => false,
					'msg' => 'Die Zip-Datei ' . $tmp_dir . $upload_file['name'] . ' kann nicht geöffnet werden. ' . $ex
				);
			}
			$msg .= 'Extract ' . $tmp_dir . $upload_file['name'] . ' nach ' . $tmp_dir;

			try {
				$zip->extractTo($tmp_dir);
			} catch (Exception $ex) {
				return array(
					'success' => false,
					'msg' => 'Die Zip-Datei ' . $tmp_dir . $upload_file['name'] . ' kann nicht nach ' . $tmp_dir . ' ausgepakt werden. ' . $ex
				);
			}
			$zip->close();
		}
		else {
			return array(
				'success' => false,
				'msg' => 'Die Datei ' . $upload_file['name'] . ' ist keine Zip-Datei. Laden Sie die Zusammenzeichnung und ggf. Geltungsbereiche in einer Zip-Datei hoch.'
			);
		}

		try {
			if (strpos($tmp_dir, XPLANKONVERTER_FILE_PATH . 'tmp/zusammenzeichnung_') !== false AND file_exists($tmp_dir . '__MACOSX')) {
				exec('rm -R ' . $tmp_dir . '__MACOSX');
			}
			unlink($tmp_dir . $upload_file['name']);
		} catch (Exception $ex) {
			return array(
				'success' => false,
				'msg' => 'Kann die hochgeladene Zip-Datei: ' . $tmp_dir . $upload_file['name'] . ' nicht löschen.' . $ex
			);
		}

		#TODO: Hier kann man die hochgeladenen Datei ggf. noch umbenennen in Zusammenzeichnung.gml falls die anders heißt
		# Aber wie rausbekommen wie die Zusammenzeichnung heißt. Vorerst bleibt es bei der Konvention dass die Datei
		# Zusammenzeichnung.gml heißen muss.

		$konvertierung = new Konvertierung($GUI, $GUI->formvars['planart']); # Create empty Konvertierungsobjekt

		$upload_validation_result = $konvertierung->validate_uploaded_files($tmp_dir);
		if (!$upload_validation_result['success']) {
			return $upload_validation_result;
		}

		$result_zusammenzeichnung = $konvertierung->xplanvalidator($tmp_dir . $upload_validation_result['plan_file_name']);

		if (!$result_zusammenzeichnung['success']) {
			return $result_zusammenzeichnung;
		}

		$msg = $konvertierung->config['title'];

		if ($konvertierung->get('planart') == 'FP-Plan') {
			if (file_exists($tmp_dir . $zip_dir . 'Einzelfassungen.gml')) {
				rename($tmp_dir . $zip_dir . 'Einzelfassungen.gml', $tmp_dir . 'Geltungsbereiche.gml');
			}

			if (file_exists($tmp_dir . 'Geltungsbereiche.gml')) {
				$result_geltungsbereiche = $konvertierung->xplanvalidator($tmp_dir . 'Geltungsbereiche.gml');
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
			$GUI->Stelle->id,
			$GUI->user->id,
			$upload_validation_result['plan_file_name']
		);
		if (!$result['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Anlegen der Konvertierung. ' . $result['msg']
			);
		}
		$konvertierung->create_directories();

		# move files from tmp to upload folder from konvertierung
		rename($tmp_dir, $konvertierung->get_file_path('uploaded_xplan_gml'));
		$msg .= '<br>Temporäre Dateien von ' . $tmp_dir . ' nach ' .  $konvertierung->get_file_path('uploaded_xplan_gml') . ' kopiert.';

		$result = $konvertierung->save_validation_report('Zusammenzeichnung', $result_zusammenzeichnung['report']);
		# Der Validierungsreport der Geltungsbereiche wird nicht gespeichert, weil es nur einen Report pro Konvertierung geben kann und für die Geltungsbereiche
		# auch nichts weiter interessantes drin stehen dürfte, weil ja keine Fachdaten drin sind.
		#$result = $konvertierung->save_validation_report('Geltungsbereiche', $result_geltungsbereiche['report']);
    if (!$result['success']) {
      return $result;
    }
		$msg .= $result['msg'];

		return array(
			'success' => true,
			'msg' => $msg,
			'konvertierung_id' => $konvertierung_id
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

	$GUI->xplankonverter_reindex_gml_ids = function() use ($GUI) {
		$uploaded_xplan_gml_path =  XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/uploaded_xplan_gml/';
		$reindexed_xplan_gml_path =  XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/reindexed_xplan_gml/';
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
		if (mail_att($from_name, $from_email, $to_email, $cc_email, $reply_email, $subject, $message, $attachement, $mode, $smtp_server, $smtp_port)) {
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
	}
?>