<?php

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
				$GUI->debug->show('Trigger ' . $fired . ' ' . $event . ' konvertierung planart: ' . $konvertierung->get('planart') . ' plan planart: ' . $konvertierung->plan->get('planart'), false);
				$konvertierung->set_status();
				
				# layer_schemaname needs to be an empty textfield in the layer definition
				# 03.11.21 change from ... layer_schemaname;;;Text;;unknown;0' to ... layer_schemaname;;;Text;;text;0'
				if (($GUI->formvars[$layer['Layer_ID'] . ';layer_schemaname;;;Text;;unknown;0'] == 'xplan_gmlas_tmp_' . $GUI->user->id) || ($GUI->formvars[$layer['Layer_ID'] . ';layer_schemaname;;;Text;;text;0'] == 'xplan_gmlas_tmp_' . $GUI->user->id)) {
					# renames to xplan_gmlas_ + konvertierung_id to make schema permanent
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
					$textabschnitte = array("bp_textabschnitt", "fp_textabschnitt", "so_textabschnitt", "rp_textabschnitt", "lp_textabschnitt");
					foreach($textabschnitte as $textabschnitt) {
						if($gml_extractor->check_if_table_exists_in_schema($textabschnitt, 'xplan_gmlas_' . $konvertierung_id)) {
							$gml_extractor->insert_into_textabschnitt($textabschnitt, $konvertierung_id, $GUI->user->id);
						}
					}
					
					# Inserts regeln for each possible class loaded with GMLAS
					$gml_extractor->insert_all_regeln_into_db($konvertierung_id, $GUI->Stelle->id);
					
					# directories to be created (if they do no exist yet e.g. for shape export)
					$konvertierung->create_directories();

					# mv uploaded xplan_gml from tmp to uploaded_xml_gml
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

	$GUI->xplankonverter_is_case_forbidden = function() use ($GUI){
		$forbidden = false;
		if ($GUI->formvars['konvertierung_id'] == '') {
			#echo 'Diese Link kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.';
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
		$layers = Layer::find($GUI, "
				`schema` LIKE 'xplan_gml' AND
				LOWER(`Name`) NOT LIKE '%textabschnitt' AND
				LOWER(`Name`) NOT LIKE '%begruendungabschnitt' AND
				LOWER(`Name`) NOT LIKE '%bereiche' AND
				LOWER(`Name`) NOT LIKE '%Pläne' AND
				`Datentyp` IN (0, 1, 2) AND
				`connectiontype` = 6
		", 'Name');
		$xplan_layers = array_map(
			function ($layer) {
				return array(
					'Name' => $layer->get('Name'),
					'Datentyp' =>$layer->get('Datentyp'),
					'schema' => $layer->get('schema'),
					'maintable' => $layer->get('maintable')
				);
			},
			$layers
		);
		return $xplan_layers;
	};

	$GUI->xplankonverter_create_geoweb_service = function($xplan_layers) use ($GUI) {
		# Erzeuge GeoWeb-Service in dem alle Pläne enthalten sind.
		$planartAbk = substr($GUI->formvars['planart'], 0, 2);
		$planartkuerzel = $GUI->formvars['planart'][0];
		
		$ows_onlineresource = URL . 'ows/' . strtolower($planartkuerzel) . 'plaene/';

		$GUI->class_load_level = 2;
		$GUI->loadMap('DataBase');

		# Setze Metadaten
		$bb = $GUI->Stelle->MaxGeorefExt;
		$GUI->map->extent->setextent($bb->minx, $bb->miny, $bb->maxx, $bb->maxy);
    $GUI->map->setMetaData("ows_extent", $bb->minx . ' ' . $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
		$GUI->map->setMetaData("ows_onlineresource", $ows_onlineresource);
		$GUI->map->setMetaData("ows_service_onlineresource", $ows_onlineresource);

		$xp_plan = new XP_Plan($GUI, $GUI->formvars['planart']);
		$layers_with_content = $xp_plan->get_layers_with_content($xplan_layers);
		$layernames_with_content = array_keys($layers_with_content);
		$layernames_with_content[] = strtoupper($planartkuerzel) . '-Pläne';
		$layernames_with_content[] = strtoupper($planartAbk) . '-Bereiche';
		$layernames_with_content[] = 'Geltungsbereiche';
		#echo '<br>pk layernames_with_content: ' . print_r($layernames_with_content, true);

		$layers_to_remove = array();
		for ($i = 0; $i < $GUI->map->numlayers; $i++) {
			$layer = $GUI->map->getLayer($i);
			if (in_array($layer->name, $layernames_with_content)) {
				$layer->set('header', 'templates/' . $layer->name . '_head.html');
				$layer->set('template', 'templates/' . $layer->name . '_body.html');
			}
			else {
				$GUI->map->removeLayer($i);
				$i--;
			}
		}
		return 'zusammenzeichnung.map';
	};
?>