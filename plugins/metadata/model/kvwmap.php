<?php
	// GUI functions of plugins/metadata
	// metadata_cancel_data_package
	// metadata_create_data_package
	// metadata_delete_data_package
	// metadata_download_data_package
	// metadata_order_data_package
	// metadata_show_data_packages
	// Metadaten_Auswaehlen_Senden
	// Metadaten_Recherche
	// Metadaten_update_outdated
	// Metadateneingabe

	include_once(PLUGINS . 'metadata/model/GeonetworkClient.php');
	include_once(PLUGINS . 'metadata/model/Ressource.php');
	include_once(PLUGINS . 'metadata/model/DataPackage.php');

	function exceptions_error_handler($severity, $message, $filename, $lineno) {
	  throw new ErrorException($message, 0, $severity, $filename, $lineno);
	}

	// set_error_handler('exceptions_error_handler');

	/**
	 * Die Bestellung zum Packen eines Paketes wird beendet.
	 * Das Paketobjekt wird in der Datenbank gelöscht.
	 * Es erfolgt ein Abbruch wenn der Parameter $package_id leer ist,
	 * das Paket mit der package_id in der Datenbank nicht gefunden wird,
	 * der Status 3 (in Arbeit) oder Status 4 (Paket bereits erstellt) ist.
	 * @param int $package_id Die Id des Paketes für die die Bestellung aufgehoben werden soll.
	 * @return array{ success: Boolean, msg: String} 
	 */
	$GUI->metadata_cancel_data_package = function($package_id) use ($GUI) {
		if ($package_id == '') {
			return array(
				'success' => false,
				'msg' => 'Der Eingabeparameter package_id ist leer!'
			);
		}

		try {
			$package = DataPackage::find_by_id($GUI, $package_id);

			if (!$package) {
				return array(
					'success' => false,
					'msg' => 'Packetbeauftragung für Paket mit der ID: ' . $package_id . ' kann nicht abgebrochen werden. Paket wurde nicht gefunden!'
				);
			}

			if ($package->get('pack_status_id') == 3) {
				return array(
					'success' => true,
					'msg' => 'Paketbeauftragung kann nicht abgebrochen werden, weil die Paketerstellung schon in Arbeit ist.'
				);
			}

			if ($package->get('pack_status_id') == 4) {
				return array(
					'success' => true,
					'msg' => 'Paketbeauftragung kann nicht abgebrochen werden, weil das Paket bereits erstellt wurde.'
				);
			}

			$package->delete();

			return array(
				'success' => true,
				'msg' => 'Paketbeauftragung erfolgreich abgebrochen.'
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler: ' . print_r($e, true)
			);
		}
	};

	/**
	 * Function create the package with id $package_id.
	 * @param int $package_id
	 * @return array{ success: Boolean, msg: String, downloadUrl?: String}
	 */
	$GUI->metadata_create_data_package = function($package_id) use ($GUI) {
		if ($package_id == '') {
			return array(
				'success' => false,
				'msg' => 'Der Eingabeparameter package_id ist leer!'
			);
		}
		try {
			$package = DataPackage::find_by_id($GUI, $package_id);
			if (!$package) {
				return array(
					'success' => false,
					'msg' => 'Das Paket mit der ID: ' . $package_id . ' kann nicht befüllt werden. Paket wurde nicht gefunden!'
				);
			}

			if ($package->get('pack_status_id') == 3) {
				return array(
					'success' => true,
					'msg' => 'Der Auftrag wird abgebrochen, weil bereits ein anderer Prozess das Paket befüllt.'
				);
			}

			if ($package->get('pack_status_id') == 4) {
				return array(
					'success' => true,
					'msg' => 'Der Auftrag wird abgebrochen, weil das Paket bereits befüllt ist. Zur Neuerstellung Paket vorher löschen und Paketerstellung erneut beauftragen.'
				);
			}

			if (!$package->layer) {
				return array(
					'success' => true,
					'msg' => 'Der Auftrag wird abgebrochen, weil die Ressource keinen zugeordneten Layer hat!'
				);
			}

			$package->update_attr(array('pack_status_id = 3'));
			$GUI->formvars['selected_layer_id'] = $package->get('layer_id');
			$GUI->formvars['epsg'] = $package->layer->get('epsg_code');
			$exportpath = $package->get_export_path();
			if (!file_exists($exportpath)) {
				$GUI->debug->show('Lege Verzeichnis ' . $exportpath . ' an, weil es noch nicht existiert!', false);
				mkdir($exportpath, 0777, true);
			}
			$result = $package->get_export_format();
			if (!$result['success']) {
				return $result;
			}
			$GUI->formvars['export_format'] = $result['export_format'];
			$GUI->formvars['all'] = 1;

			$exportfilename = $package->layer->get('Name');

			if (in_array($result['export_format'], array('Shape', 'CSV'))) {
				// mit ogr2ogr exportieren und zippen
				include_once(CLASSPATH . 'data_import_export.php');
				$data_import_export = new data_import_export();
				$result = $data_import_export->export_exportieren($GUI->formvars, $GUI->Stelle, $GUI->user , $exportpath, $exportfilename, true);
				if (!$result['success']) {
					// Fehler loggen
					$result['msg'] = 'Fehler beim Exportieren des Layers ID: ' . $package->get('layer_id') . ' des Paket ID: ' . $package->get_id() . ' für Ressource ID: ' . $package->get('ressource_id') . "\n" . $result['msg'];
					$package->log($result['msg']);
					// Fehlerstatus setzen
					$package->update_attr(array('pack_status_id = -1'));
					return $result;
				}
			}
			else {
				// Text-Datei mit Links zum Dienst anlegen und Zip.
				if (!is_dir($exportpath)) {
					mkdir($exportpath);
				}
				file_put_contents($exportpath . $exportfilename . '.txt', 'GetCapabilties: ' . $package->layer->get('connection') . 'Service=WMS&Request=GetCapabilities&Version=' . $package->layer->get('wms_server_version'));
				exec(ZIP_PATH . ' -j ' . rtrim($exportpath, '/') . ' ' . $exportpath . '*');
			}

			// Metadatendatei erzeugen und in ZIP packen

			// An Ressourcen hängende Dokumente in ZIP packen

			// Wenn ZIP-Datei existiert und etwas drin ist, Verzeichnis löschen. (Aufräumen)

			$package->update_attr(array('pack_status_id = 4'));

			return array(
				'success' => true,
				'msg' => 'Paket ID: ' . $package->get_id() . ' für Ressource ID: ' . $package->get('ressource_id') . ' mit Layer ID: ' . $package->get('layer_id') . ' gepackt.',
				'downloadURL' => $result['exportfile']
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler: ' . print_r($e, true)
			);
		}
	};

	/**
	 * Function delete the package with id $package_id.
	 * @param int $package_id
	 * @return array{ success: Boolean, msg: String, downloadUrl?: String}
	 */
	$GUI->metadata_delete_data_package = function($package_id) use ($GUI) {
		if ($package_id == '') {
			return array(
				'success' => false,
				'msg' => 'Der Eingabeparameter package_id ist leer!'
			);
		}
		try {
			$package = DataPackage::find_by_id($GUI, $package_id);
			if (!$package) {
				return array(
					'success' => false,
					'msg' => 'Das Paket mit der ID: ' . $package_id . ' kann nicht gelöscht werden. Paket wurde nicht gefunden!'
				);
			}

			if ($package->get('pack_status_id') == 2) {
				// Package is not packed already. Only cancle the package order by deleting the package.
				$package->delete();
				return array(
					'success' => true,
					'msg' => 'Paket erfolgreich gelöscht.'
				);
			}

			if (!$GUI->formvars['force'] != '' AND $package->get('pack_status_id') == 3) {
				return array(
					'success' => true,
					'msg' => 'Der Auftrag wird abgebrochen, weil ein anderer Prozess gerade das Paket befüllt.'
				);
			}

			if (!$package->layer) {
				return array(
					'success' => true,
					'msg' => 'Der Auftrag wird abgebrochen, weil die Ressource keinen zugeordneten Layer hat!'
				);
			}

			$package->update_attr(array('pack_status_id = 3'));
			$export_path = $package->get_export_path();
			// Dateien im Verzeichnis löschen
			array_map('unlink', glob($export_path . '*.*'));
			// Verzeichnis löschen
			rmdir($export_path);
			$package_id = $package->get_id();
			$package->delete();

			return array(
				'success' => true,
				'msg' => 'Paket ID: ' . $package_id . ' ist gelöscht.'
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler: ' . print_r($e, true)
			);
		}
	};

	/**
	 * Function download the package with id $package_id.
	 * @param int $package_id
	 * @return array{ success: Boolean, msg: String, downloadUrl?: String}
	 */
	$GUI->metadata_download_data_package = function($package_id) use ($GUI) {
		if ($package_id == '') {
			return array(
				'success' => false,
				'msg' => 'Der Eingabeparameter package_id ist leer!'
			);
		}
		try {
			$package = DataPackage::find_by_id($GUI, $package_id);
			if (!$package) {
				return array(
					'success' => false,
					'msg' => 'Das Paket mit der ID: ' . $package_id . ' kann nicht gelöscht werden. Paket wurde nicht gefunden!'
				);
			}

			if ($package->get('pack_status_id') != 4) {
				return array(
					'success' => true,
					'msg' => 'Das Paket mit der ID: ' . $package_id . ' kann nicht heruntergeladen werden weil es noch nicht fertig gepackt ist!'
				);
			}

			if (!$package->layer) {
				return array(
					'success' => true,
					'msg' => 'Der Auftrag wird abgebrochen, weil die Ressource keinen zugeordneten Layer hat!'
				);
			}

			// find download_file
			$downloadfile = rtrim($package->get_export_path(), '/') . '.zip';

			return array(
				'success' => true,
				'msg' => 'Paket ID: ' . $package_id . ' ist gelöscht.',
				'downloadfile' => $downloadfile
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler: ' . print_r($e, true)
			);
		}
	};

	/**
	 * Function create a package object for ressource with $ressource_id in database for $stelle_id
	 * @param int $ressource_id
	 * @param int $stelle_id
	 * @return array{ success: Boolean, msg: String}
	 */
	$GUI->metadata_order_data_package = function($ressource_id, $stelle_id) use ($GUI) {
		try {
			$package = new DataPackage($GUI);
			$new_package_id = $package->create(array(
				'stelle_id' => $stelle_id,
				'ressource_id' => $GUI->formvars['ressource_id'],
				'pack_status_id' => 2, // Paketerstellung beauftragt
				'created_from' => $GUI->user->Vorname . ' ' . $GUI->user->Name
			), true);
			$package = $package->find_by_id($GUI, $new_package_id);

			return array(
				'success' => true,
				'package' => $package->data,
				'msg' => 'Download-Paket mit id: ' . $new_package_id . ' angelegt. Packen für Ressource ID: ' . $package->get('ressource_id') . ' beauftragt.'
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler: ' . print_r($e, true)
			);
		}
	};

	$GUI->metadata_show_data_packages = function() use ($GUI) {
		$GUI->main = PLUGINS . 'metadata/view/data_packages.php';
		$GUI->metadata_data_packages = DataPackage::find_by_stelle_id($GUI, $GUI->Stelle->id);
		$GUI->output();
	};

	$GUI->metadata_upload_to_geonetwork = function($data) use ($GUI) {
		$success = false;
		// echo "<br>saveData=truetruetrue send_metadata=\"${send_metadata}\"<br>";
		try {
			$geonetworkClient = new GeonetworkClient(
				METADATA_CATALOG,
				METADATA_CATALOGUSER,
				METADATA_CATALOGPASS
			);
			$connectResult = $geonetworkClient->connect();
			// echo "<textarea id=\"connectResult\" rows=\"10\" style=\"width:100%; background-color:white\"><" .
			// 	print_r($connectResult, true) . "></textarea>";
			#echo "<textarea id=\"connectResult\" rows=\"1\" style=\"width:100%; background-color:white\">angemeldet: " .
				$connectResult->name . "</textarea>";
	
			// uuidProcessing = GENERATEUUID, NOTHING, OVERWRITE
			$putMetaDataResult = $geonetworkClient->putMetaData($data, $uuidProcessing='OVERWRITE');
	
			#echo "<textarea id=\"put-result\" rows=\"10\" style=\"width:100%; background-color:white\"><" .
			 # print_r($putMetaDataResult, true) . "></textarea>";

			if ($putMetaDataResult->success) {
				$success = true;
				$msg = "war erfolgreich\nuuid:\t" . $putMetaDataResult->uuid;
				$metadataInfos = $putMetaDataResult->metadataInfos;
				$infos = array();
				foreach ($metadataInfos as $metadataInfo) {
					$infos[] = $metadataInfo[0];
				}
			} else {
				$msg = "war nicht erfolgreich\nmessage:\t" . $putMetaDataResult->message . "\ndescription: " . $putMetaDataResult->description;
			}
			#echo "<textarea id=\"connectResult\" rows=\"10\" style=\"width:100%; background-color:white\">${msg}</textarea>";

		} catch (Exception $e) {
			$msg = 'Exception abgefangen: ' .  $e->getMessage() . "\n";
			$msg .= $e->getTraceAsString();
		}
		try {
			$geonetworkClient->close();
		} catch (Exception $e) {
			$msg = 'Exception abgefangen: ' . $e->getMessage() . "\n";
			$msg .= $e->getTraceAsString();
		}
		$response = array(
			'success' => $success,
			'msg' => $msg,
			'metadataInfos' => $infos
		);
		return $response;
	};

	$GUI->metadatenSuchen = function() {
		$GUI->metadata = new metadata($GUI);
		$GUI->metadaten = $GUI->metadata->findQuickSearch($GUI->formvars);
		$GUI->main = PLUGINS . 'metadata/view/searchresults.php';
		$GUI->output();
	};

  $GUI->metadateneingabe = function() use ($GUI) {
    $metadatensatz = new metadatensatz($GUI->formvars['oid'],$GUI->pgdatabase);
    if ($GUI->formvars['oid']!='') {
      # Es handelt sich um eine Änderung eines Datensatzes
      # Auslesen der Metadaten aus der Datenbank und Zuweisung zu Formularobjekten
      $ret=$metadatensatz->getMetadaten($GUI->formvars);
      if ($ret[0]) {
        $errmsg='Suche ergibt kein Ergebnis'.$ret[1];
      }
      else {
        $GUI->formvars=array_merge($GUI->formvars,$ret[1][0]);
      }
      $GUI->titel='Metadatenänderung';
    }
    else {
      # Anzeigen des Metadateneingabeformulars
      $GUI->titel='Metadateneingabe';
      # Zuweisen von defaultwerten für die Metadatenelemente wenn nicht vorher
      # schon ein Formular ausgefüllt wurde
      if ($GUI->formvars['mdfileid']=='') {
        $defaultvalues=$metadatensatz->readDefaultValues($GUI->user);
        $GUI->formvars=array_merge($GUI->formvars,$defaultvalues);
      }
      else {
        # Wenn das Formular erfolgreich eingetragen wurde neue mdfileid vergeben
        if ($GUI->Fehlermeldung=='') {
          $GUI->formvars['mdfileid']=rand();
        }
      }
    }
    # Erzeugen der Formularobjekte für die Schlagworteingabe
    $ret=$metadatensatz->getKeywords('','','theme','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $GUI->formvars['allthemekeywords']=$ret[1];
    }

    $ret=$metadatensatz->getKeywords('','','place','','','keyword');
    if ($ret[0]) {
      echo $ret[1];
    }
    else {
      $GUI->formvars['allplacekeywords']=$ret[1];
    }
    $GUI->allthemekeywordsFormObj = new FormObject(
			"allthemekeywords",
			"select",
			$GUI->formvars['allthemekeywords']['id'],
			explode(", ",$GUI->formvars['selectedthemekeywordids']),
			$GUI->formvars['allthemekeywords']['keyword'],
			4,
			0,
			1,
			'200'
		);
    $GUI->allplacekeywordsFormObj = new FormObject(
			"allplacekeywords",
			"select",
			$GUI->formvars['allplacekeywords']['id'],
			explode(", ",$GUI->formvars['selectedplacekeywordids']),
			$GUI->formvars['allplacekeywords']['keyword'],
			4,
			0,
			1,
			'200'
		);
    $GUI->main='metadateneingabeformular.php';
    $GUI->loadMap('DataBase');
    if ($GUI->formvars['refmap_x']!='') {
      $GUI->zoomToRefExt();
    }
    $GUI->navMap($GUI->formvars['CMD']);
    $GUI->saveMap('');
    $GUI->drawMap();
    $GUI->output();
  };
?>