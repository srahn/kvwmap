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
				# Delete the layerdefinition in mysql (rolleneinstellungen, layer, classes, styles, etc.)
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

		switch ($layer['Layer_ID']) {
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
				$xp_plan = XP_Plan::find_by_id($GUI, 'gml_id', $oid, $planart);

				# Create Konvertierung and get konvertierung_id
				$konvertierung = new Konvertierung($GUI);
				$konvertierung_id = $konvertierung->create(
					$xp_plan->get_anzeige_name(),
					$GUI->Stelle->epsg_code,
					$GUI->user->rolle->epsg_code,
					$planart,
					$GUI->Stelle->id,
					$GUI->user->id
				);

				$xp_plan->set('konvertierung_id', $konvertierung_id);
				$xp_plan->update();

				$konvertierung = $konvertierung->find_by_id($GUI, 'id', $konvertierung_id);
				//$GUI->debug->show('Trigger ' . $fired . ' ' . $event . ' konvertierung planart: ' . $konvertierung->get('planart') . ' plan planart: ' . $konvertierung->plan->get('planart'), false);
				$konvertierung->set_status();

				# layer_schemaname needs to be an empty textfield in the layer definition
				# 03.11.21 change from ... layer_schemaname;;;Text;;unknown;0' to ... layer_schemaname;;;Text;;text;0'
				if (($GUI->formvars[$layer['Layer_ID'] . ';layer_schemaname;;;Text;;unknown;0'] == 'xplan_gmlas_tmp_' . $GUI->user->id) || ($GUI->formvars[$layer['Layer_ID'] . ';layer_schemaname;;;Text;;text;0'] == 'xplan_gmlas_tmp_' . $GUI->user->id)) {
					# renames to xplan_gmlas_ + konvertierung_id to make schema permanent
					//$konvertierung->rename_xplan_gmlas($GUI->user->id, $konvertierung_id);
					$sql = "
								 ALTER SCHEMA
												xplan_gmlas_tmp_" . $GUI->user->id .
								 " RENAME TO
												 xplan_gmlas_" . $konvertierung_id . ";
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
					//$gml_extractor->insert_all_regeln_into_db();
					$gml_extractor->insert_all_regeln_into_db($konvertierung_id, $GUI->Stelle->id);

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
				# Delete Konvertierung and all pending objects instead of deleting only plan
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
	* @params $layer Array mit Angben des Layers aus der MySQL-Datenbank
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

	$GUI->xplankonverter_get_xplan_layers = function() use ($GUI) {
		include_once(CLASSPATH . 'Layer.php');
		# ToDo pk: Hier prüfen ob die richtigen layer abgefragt werden, weil die Namen geändert wurden.
		$layers = Layer::find($GUI, "
				(
					(
						`schema` LIKE 'xplan_gml' AND
						LOWER(`Name`) NOT LIKE '%_textabschnitt' AND
						LOWER(`Name`) NOT LIKE '%_begruendungabschnitt'
					) OR
					(
						`schema` LIKE 'xplankonverter' AND
						LOWER(`Name`) = 'geltungsbereiche'
					)
				) AND
				`Datentyp` IN (0, 1, 2) AND
				`connectiontype` = 6
		", 'alias');
		$xplan_layers = array_map(
			function ($layer) {
				return array(
					'id' => $layer->get('Layer_ID'),
					'Name' => $layer->get('Name'),
					'alias' => $layer->get('alias'),
					'Datentyp' =>$layer->get('Datentyp'),
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
			try {
				mkdir($tmp_dir, 0775);
			} catch (Exception $ex) {
				return array(
					'success' => false,
					'msg' => 'Das Verzeichnis ' . $tmp_dir . ' kann auf dem Server nicht angelegt werden. ' . $ex
				);
			}
		}

		try {
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

		$konvertierung = new Konvertierung($GUI); # Create empty Konvertierungsobjekt

		$result_zusammenzeichnung = $konvertierung->xplanvalidator($tmp_dir . 'Zusammenzeichnung.gml');
		if (!$result_zusammenzeichnung['success']) {
			return $result_zusammenzeichnung;
      $msg = 'Zusammenzeichnung';
		}

		if (file_exists($tmp_dir . 'Einzelfassungen.gml')) {
			rename($tmp_dir . 'Einzelfassungen.gml', $tmp_dir . 'Geltungsbereiche.gml');
		}

		if (file_exists($tmp_dir . 'Geltungsbereiche.gml')) {
			$result_geltungsbereiche = $konvertierung->xplanvalidator($tmp_dir . 'Geltungsbereiche.gml');
			if (!$result_geltungsbereiche['success']) {
				return $result_geltungsbereiche;
			}
			$msg .= ' und Geltungsbereiche';
		}
		$msg .= ' valide.';

		# Hochgeladene Zusammenzeichnung hat Prüfung im XPlanValidator bestanden
		# Create Konvertierung and get konvertierung_id
		# Bezeichnung wird später wenn die Zusammenzeichnung eingelesen wurde noch entsprechend der Zusammenzeichnung.gml aktualisiert.
		$konvertierung_id = $konvertierung->create(
			'Neue Zusammenzeichnung aus Datei ' . $upload_file['name'],
			$GUI->Stelle->epsg_code,
			$GUI->user->rolle->epsg_code,
			$GUI->formvars['planart'],
			$GUI->Stelle->id,
			$GUI->user->id
		);
		$konvertierung = $konvertierung->find_by_id($GUI, 'id', $konvertierung_id);

		$konvertierung->create_directories();

		# move files from tmp to upload folder from konvertierung
		rename($tmp_dir, $konvertierung->get_file_path('uploaded_xplan_gml'));

		$result = $konvertierung->save_validation_report('Zusammenzeichnung', $result_zusammenzeichnung['report']);
		# Der Validierungsreport der Geltungsbereiche wird nicht gespeichert, weil es nur einen Report pro Konvertierung geben kann und für die Geltungsbereiche
		# auch nichts weiter interessantes drin stehen dürfte, weil ja keine Fachdaten drin sind.
		#$result = $konvertierung->save_validation_report('Geltungsbereiche', $result_geltungsbereiche['report']);
    if (!$result['success']) {
      return $result;
    }

		return array(
			'success' => true,
			'msg' => $msg,
			'konvertierung_id' => $konvertierung_id
		);
	};

	$GUI->xplankonverter_get_new_gml_id = function($gml_id) use ($GUI) {
		if (! array_key_exists($gml_id, $GUI->xplan_gml_ids)) {
			$GUI->xplan_gml_ids[$gml_id] = 'GML_' . uuid();
		}
		return $GUI->xplan_gml_ids[$gml_id];
	};

	$GUI->xplankonverter_reindex_gml_ids = function() use ($GUI) {
		$uploaded_xplan_gml_path =  XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/uploaded_xplan_gml/';
		$reindexed_xplan_gml_path =  XPLANKONVERTER_FILE_PATH . $GUI->formvars['konvertierung_id'] . '/reindexed_xplan_gml/';
		if (! file_exists($reindexed_xplan_gml_path)) {
			mkdir($reindexed_xplan_gml_path, 0777);
		}
		$read_handle = fopen($uploaded_xplan_gml_path . 'Zusammenzeichnung.gml', "r");
		$write_handle = fopen($reindexed_xplan_gml_path . 'Zusammenzeichnung.gml', "w");
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
				'msg' => "Fehler beim Öffnen der Datei ${uploaded_xplan_gml_path}Zusammenzeichnung.gml zum Umbenennen der gml_id's."
			);
		}
		return array(
			'success' => true,
			'msg' => "GML-ID's in GML-Datei erfolgreich umbennannt."
		);
	};

	$GUI->xplankonverter_create_geoweb_service = function($xplan_layers) use ($GUI) {
		global $admin_stellen;
		$planartAbk = substr($GUI->formvars['planart'], 0, 2);
		$planartkuerzel = $GUI->formvars['planart'][0];

		$GUI->ows_onlineresource = OWS_SERVICE_ONLINERESOURCE . MAPFILENAME . '/';
		$GUI->class_load_level = 2;
		$GUI->loadMap('DataBase');

		# Setze Metadaten
		$admin_stelle = new Stelle($admin_stellen[0], $GUI->database);
		$bb = $admin_stelle->MaxGeorefExt;
		$GUI->map->set('name', PUBLISHERNAME);
		$GUI->map->extent->setextent($bb->minx, $bb->miny, $bb->maxx, $bb->maxy);
		$GUI->map->setMetaData("ows_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
		$GUI->map->setMetaData("ows_title", $admin_stelle->ows_title);
		$GUI->map->setMetaData("ows_abstract", $admin_stelle->ows_abstract . ' Letzte Aktualisierung: ' . date('d.m.Y'));
		$GUI->map->setMetaData("ows_onlineresource", $GUI->ows_onlineresource);
		$GUI->map->setMetaData("ows_service_onlineresource", $GUI->ows_onlineresource);
		$GUI->map->web->set('header', 'templates/header.html');
		$GUI->map->web->set('footer', 'templates/footer.html');

		$xp_plan = new XP_Plan($GUI, $GUI->formvars['planart']);
		$result = $xp_plan->get_layers_with_content($xplan_layers);
		if (! $result['success']) {
			return $result;
		}

		$GUI->layers_with_content = $result['layers_with_content'];

		$GUI->layernames_with_content = array_keys($GUI->layers_with_content);
		#echo '<br>pk layernames_with_content: ' . print_r($GUI->layernames_with_content, true);

		$layers_to_remove = array();

		for ($i = 0; $i < $GUI->map->numlayers; $i++) {
			$layer = $GUI->map->getLayer($i);
			if (in_array($layer->name, $GUI->layernames_with_content)) {
				$layer->set('header', 'templates/' . $layer->name . '_head.html');
				$layer->set('template', 'templates/' . $layer->name . '_body.html');
				# Set Data sql for layer
				$layerObj = Layer::find_by_id($GUI, $layer->getMetadata('kvwmap_layer_id'));
				$options = array(
					'attributes' => array(
						'select' => array('k.bezeichnung AS plan_name', 'k.stelle_id'),
						'from' => array('JOIN xplankonverter.konvertierungen AS k ON ' . $layerObj->get_table_alias() . '.konvertierung_id = k.id')
					),
					'geom_attribute' => 'position',
					'geom_type_filter' => true
				);
				$result = $layerObj->get_generic_data_sql($options);
				if ($result['success']) {
					$layer->set('data', $result['data_sql']);
				}
				else {
					$result['msg'] = 'Fehler bei der Erstellung der Map-Datei in Funktion get_generic_data_sql! ' . $result['msg'];
					return $result;
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
	 */
	$GUI->xplankonverter_create_metadata_documents = function($md) use ($GUI) {
		global $admin_stellen;
		$current_time = time();
		$pg_object = new PgObject($GUI, 'xplankonverter', 'plan_services');

		$plan_object = new XP_Plan($GUI, $GUI->formvars['planart']);
		$plan_object->get_extent(OWS_SRS, 'zusammenzeichnung'); # Pläne mit Attribut zusammenzeichnung = true
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
		$md->set('version', floatval(implode('.', array_slice(explode('/', XPLAN_NS_URI), -2))));
		$md->set('extents', $plan_object->extents);
		$md->set('service_layer_name', umlaute_umwandeln($admin_stelle->get('Bezeichnung')));
		$md->set('onlineresource', URL . 'ows/fplaene?');
		$md->set('dataset_browsegraphic', URL . APPLVERSION . 'custom/graphics/Vorschau_Datensatz.png');
		$md->set('viewservice_browsegraphic', $md->get('onlineresource') . "Service=WMS&amp;Request=GetMap&amp;Version=1.1.0&amp;Layers=" . $plan_object->tableName . "&amp;FORMAT=image/png&amp;SRS=EPSG:" . $md->get('stellendaten')['epsg_code'] . "&amp;BBOX=" . implode(',', $md->get('extents')[$md->get('stellendaten')['epsg_code']]) . "&amp;WIDTH=300&amp;HEIGHT=300");
		$md->set('downloadservice_browsegraphic', URL . APPLVERSION . 'custom/graphics/Vorschau_Downloadservice.png');

		$metaDataCreator = new MetaDataCreator($md);
		return array(
			'metaDataGeodatensatz' => $metaDataCreator->createMetadataGeodatensatz(),
			'metaDataDownload' => $metaDataCreator->createMetaDataDownload(),
			'metaDataView' =>  $metaDataCreator->createMetaDataView()
		);
	};

  $GUI->xplankonverter_remove_failed_konvertierungen = function() use ($GUI) {
    $zusammenzeichnungen = Konvertierung::find_zusammenzeichnungen($GUI, $GUI->formvars['planart'], $GUI->plan_class, $GUI->plan_attribut_aktualitaet);
    foreach($zusammenzeichnungen['faulty'] AS $faulty_zusammenzeichnung) {
      $faulty_zusammenzeichnung->destroy();
    }
    return array(
      'success' => true,
      'msg' => 'Fehlerhafte Konvertierungen gelöscht.'
    );
  };

	$GUI->xplankonverter_send_notification = function($msg) use ($GUI) {
		$from_name = 'XPlan-Server PlanDigital';
		$from_email = 'info@testportal-plandigital.de';
		$to_email = 'petra.wilken-janssen@arl-we.niedersachsen.de';
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