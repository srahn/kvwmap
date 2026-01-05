<?php
	// GUI functions of plugins/metadata
	// metadata_add_urls
	// metadata_cancel_data_package
	// metadata_create_bundle_package
	// metadata_create_data_package
	// metadata_create_metadata_document
	// metadata_delete_bundle_package
	// metadata_delete_data_package
	// metadata_download_bundle_package
	// metadata_download_data_package
	// metadata_download_metadata_document
	// metadata_list_files
	// metadata_order_bundle_package
	// metadata_order_data_package
	// metadata_reorder_data_packages
	// metadata_set_ressource_status
	// metadata_show_data_packages
	// metadata_show_outdated
	// metadata_show_ressources_status
	// metadata_upload_to_geonetwork
	// Metadaten_Auswaehlen_Senden
	// Metadaten_Recherche
	// Metadateneingabe

	include_once(PLUGINS . 'metadata/model/GeonetworkClient.php');
	include_once(PLUGINS . 'metadata/model/Ressource.php');
	include_once(PLUGINS . 'metadata/model/DataPackage.php');

	function exceptions_error_handler($severity, $message, $filename, $lineno) {
	  throw new ErrorException($message, 0, $severity, $filename, $lineno);
	}

	// set_error_handler('exceptions_error_handler');

	$GUI->metadata_add_urls = function($ressource_id) use ($GUI) {
		$GUI->main = PLUGINS . 'metadata/view/add_urls.php';
		$GUI->sanitize([
			'ressource_id' => 'integer'
		]);
		if (!$ressource_id) {
			$msg = 'Der Parameter ressource_id ist leer oder wurde nicht übergeben.';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		$GUI->ressource = Ressource::find_by_id($GUI, 'id', $ressource_id);
		if ($GUI->ressource->data === false) {
			$msg = 'Die Ressource mit id ' . $ressource_id . ' wurde nicht gefunden!';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		if ($GUI->formvars['action'] == 'URLs erzeugen') {
			$GUI->sanitize([
				'x' => 'numeric',
				'y' => 'numeric',
				'radius' => 'integer',
				'tile_size' => 'integer',
				'url_pattern' => 'text'
			]);
			$kmq_x = round($GUI->formvars['x'] / 1000);
			$kmq_y = round($GUI->formvars['y'] / 1000);
			$r = $GUI->formvars['radius'];
			$n = $GUI->formvars['tile_size'] / 1000;
			$url_pattern = $GUI->formvars['url_pattern'];
			$download_url = $GUI->ressource->get('download_url');
			$urls_file = $GUI->ressource->get_urls_file();
			/**
			 * Erzeugen der Links für den Download von DGM und DOM und speichern in den Dateien urls.txt 
			 */
			$GUI->new_urls = array();
			for ($i = $r * -1; $i <= $r; $i += $n) {
				for ($j = $r * -1; $j <= $r; $j += $n) {
					$url = str_replace(
						'${kmq_y}',
						$kmq_y + $i,
						str_replace(
							'${kmq_x}',
							$kmq_x + $j,
							str_replace(
								'${URL}',
								$download_url,
								$url_pattern
							)
						)
					);
					// echo '<br>put: ' . $url;
					file_put_contents($urls_file, "\n" . $url, FILE_APPEND);
					$GUI->new_urls[] = $url;
				}
			}
			// urls in file schreiben
			$cmd = "sort " . $urls_file . " | uniq > " . $urls_file . "_sorted; mv " . $urls_file . " " . $urls_file . "_backup; cp " . $urls_file . "_sorted " . $urls_file;
			// echo '<br>' . $cmd;
			exec($cmd, $output, $result);

			if (count($output) > 0) {
				echo '<br>output: ' . implode('<br>', $output);
			}
		}

		return array(
			'success' => true,
			'msg' => 'View kann geladen werden.'
		);
	};

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
	 * Zip all Zip files in datentool directory of Stelle $stelle_id into a bundled zip-file
	 * @param int $stelle_id Id of Stelle to be packed.
	 * @return array{ success: boolean, msg: string, zipfile?: string}
	 */
	$GUI->metadata_create_bundle_package = function($stelle_id) use ($GUI) {
		// echo 'metadata_create_bundle_package stelle_id: ' . $stelle_id;
		// ToDo: Set a file that indicate that the bundle package creation is in progress and remove if ready
		// and or find a way to list the php background process that create packages as well later also find
		// runing processes that update ressources and pack packages.
		// Create a list of running processes in Admin GUI
		// Delete Package logs when deleting stellen
		list($bundle_package_path, $bundle_package_filename) = DataPackage::get_bundle_package_file($stelle_id);
		$cmd = ZIP_PATH . ' -j ' . $bundle_package_path . $bundle_package_filename . ' ' . $bundle_package_path . '*';
		$output = array();
		// echo $cmd;
		$result = exec($cmd, $output, $result_code);
		if ($result === false) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Einpacken der Datenpakete in Datei : ' . $bundle_package_path . $bundle_package_filename . ': ' . implode("\n", $output)
			);
		}
		return array(
			'success' => true,
			'msg' => 'Gesamtpaket für Stelle ID: ' . $stelle_id . ' erfolgreich gepackt in Datei: ' . $bundle_package_path . $bundle_package_filename,
			'zipfile' => $bundle_package_path . $bundle_package_filename
		);
	};

	/**
	 * Function create the package with id $package_id.
	 *  - change pack_status to start packing
	 *  - create export path if not exists
	 *  - export the file with data_import_export method export_exportieren
	 *  - exit with error and pack_status -1 in error case
	 *  - if export format is not shape or csv, download the capabilities document, write it to a xml-file and pack all from export_path into the zip file
	 *  - put metadata file into data package zip
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

			$package->update_attr(array('pack_status_id = 3')); // in Arbeit
			$GUI->formvars['selected_layer_id'] = $package->get('layer_id');
			$GUI->formvars['epsg'] = $package->layer->get('epsg_code');
			$export_path = $package->get_export_path();
			if (!is_dir($export_path)) {
				$GUI->debug->show('Lege Verzeichnis ' . $export_path . ' an, weil es noch nicht existiert!', false);
				$old_umask = umask(0);
				$result = mkdir($export_path, 0774, true);
				umask($old_umask);
				chgrp($export_path, 'gisadmin');
				if (!$result) {
					$GUI->debug->show('Anlegen von ' . $export_path . ' hat nicht geklappt!', false);
				}
			}
			$result = $package->get_export_format();
			if (!$result['success']) {
				return $result;
			}
			$GUI->formvars['export_format'] = $result['export_format'];
			$GUI->formvars['all'] = 1;

			$exportfilename = $package->layer->get('name');

			if (in_array($result['export_format'], array('Shape', 'CSV'))) {
				// mit ogr2ogr exportieren und zippen
				include_once(CLASSPATH . 'data_import_export.php');
				$data_import_export = new data_import_export();
				$result = $data_import_export->export_exportieren($GUI->formvars, $GUI->Stelle, $GUI->user , $export_path, $exportfilename, true);
				if ($result['success']) {
					if (strtolower(pathinfo($result['exportfile'])['extension']) !== 'zip') {
						$data_import_export->zip_export_path($export_path);
					}
				}
				else {
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
				file_put_contents($export_path . $exportfilename . '.xml', 'GetCapabilties: ' . $package->layer->get('connection') . 'Service=WMS&Request=GetCapabilities&Version=' . $package->layer->get('wms_server_version'));
				$data_import_export->zip_export_path($export_path);
			}

			$export_file = $package->get_export_file();

			// Metadatendatei der Ressouce erzeugen
			$GUI->formvars['aktivesLayout'] = METADATA_PRINT_LAYOUT_ID;
			$GUI->formvars['chosen_layer_id'] = METADATA_RESSOURCES_LAYER_ID;
			$GUI->formvars['oid'] = $package->get('ressource_id');
			$GUI->formvars['archivieren'] = 1;
			$result = $GUI->generischer_sachdaten_druck_createPDF(
				NULL, // pdfobject
				NULL, // offsetx
				NULL, // offsety
				true, // output
				false // append
			);
			$GUI->outputfile = basename($result['pdf_file']);
			$GUI->pdf_archivieren(METADATA_RESSOURCES_LAYER_ID, $package->get('ressource_id'), $result['pdf_file']);

			// Metadatendatei der Ressource in ZIP packen
			$command = ZIP_PATH . ' -j ' . $export_file . ' ' . METADATA_DATA_PATH . 'metadaten/Metadaten_Ressource_' . $package->get('ressource_id') . '.pdf';
			exec($command);

			// An Ressourcen hängende Dokumente in ZIP packen
			$ressource = Ressource::find_by_id($GUI, 'id', $package->get('ressource_id'));
			$ressource->append_docs($export_file);

			// Metadaten von Quellressourcen in ZIP packen
			$sources = $ressource->get_sources();
			foreach($sources AS $source) {
				$command = ZIP_PATH . ' -j ' . $export_file . ' ' . METADATA_DATA_PATH . 'metadaten/Metadaten_Ressource_' . $source->get_id() . '.pdf';
				exec($command);
			}

			// Metadaten von Zielressourcen in ZIP packen
			$targets = $ressource->get_targets();
			foreach($targets AS $target) {
				$command = ZIP_PATH . ' -j ' . $export_file . ' ' . METADATA_DATA_PATH . 'metadaten/Metadaten_Ressource_' . $target->get_id() . '.pdf';
				exec($command);
			}

			// Wenn ZIP-Datei existiert und etwas drin ist, chgrp www-data, chmod g+w und Verzeichnis löschen. (Aufräumen)
			$package->delete_export_path();
			chgrp($export_file, 'gisadmin');
			$old_umask = umask(0);
			$result = chmod($export_file, 0664, true);
			umask($old_umask);

			$package->update_attr(array('pack_status_id = 4'));

			return array(
				'success' => true,
				'msg' => 'Paket ID: ' . $package->get_id() . ' für Ressource ID: ' . $package->get('ressource_id') . ' mit Layer ID: ' . $package->get('layer_id') . ' gepackt.',
				'downloadURL' => $result['exportfile']
			);
		}
		catch (Exception $e) {
			$package->log($e->message);
			// Fehlerstatus setzen
			$package->update_attr(array('pack_status_id = -1'));

			return array(
				'success' => false,
				'msg' => 'Fehler: ' . print_r($e, true)
			);
		}
	};

	/**
	 * Function create a metadata document for layer $selected_layer_id
	 */
	$GUI->metadata_create_metadata_document = function($layer_id) use ($GUI) {
		if ($layer_id == '') {
			return array(
				'success' => false,
				'msg' => 'Der Parameter layer_id ist leer!'
			);
		}
		// ToDo pk: set metadata from layer and stelle metadata
		$ressource = Ressource::find_by_layer_id($GUI, $layer_id);
		if ($ressource->get_id()) {
			// ToDo pk: set additional metadata from ressource data
			
		}

		return array(
			'success' => false,
			'msg' => 'Funktion noch nicht implementiert!'
		);
	};

	$GUI->metadata_list_files = function($search_dir) use ($GUI) {
		$GUI->main = PLUGINS . 'metadata/view/list_files.php';

		if (strpos($seach_dir, '..') !== false) {
			$msg = 'Das Verzeichnis ' . $search_dir . ' darf keine .. Zeichenkette beinhalten!';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		$GUI->metadata_data_dir = append_slash(METADATA_DATA_PATH) . 'ressourcen/';
		$GUI->search_dir = $GUI->metadata_data_dir . $search_dir;

		if (!is_dir($GUI->search_dir)) {
			$msg = 'Das Verzeichnis ' . $GUI->seach_dir . ' existiert nicht!';
			return array(
				'success' => false,
				'msg' => $msg
			);
		}

		$GUI->files = getAllFiles($GUI->search_dir);

		return array(
			'success' => false,
			'msg' => $msg
		);
	};

	/**
	 * Function delete the bundle package of current stelle.
	 * @return array{ success: Boolean, msg: String, downloadUrl?: String}
	 */
	$GUI->metadata_delete_bundle_package = function() use ($GUI) {
		// echo 'metadata_delete_bundle_package';
		try {
			list($bundle_package_path, $bundle_package_filename) = DataPackage::get_bundle_package_file($GUI->Stelle->id);
			if ($bundle_package_path != '' AND $bundle_package_filename != '' AND file_exists($bundle_package_path . $bundle_package_filename)) {
				// echo 'delete: ' . $bundle_package_path . $bundle_package_filename;
				set_error_handler(function($errno, $errstr, $errfile, $errline) {
					// error was suppressed with the @-operator
					if (0 === error_reporting()) {
							return false;
					}
					
					throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
				});
				try {
					if (!unlink($bundle_package_path . $bundle_package_filename)) {
						return array(
							'success' => false,
							'msg' => 'Fehler beim Löschen der Datei ' . $bundle_package_path . $bundle_package_filename
						);
					}
				}
				catch (ErrorException $e) {
					return array(
						'success' => false,
						'msg' => 'Fehler beim Löschen der Datei ' . $e->getMessage()
					);
				}
			}
			else {
				return array(
					'success' => false,
					'msg' => 'Gesamtpaket ' . $bundle_package_path . $bundle_package_filename . ' in Stelle ID: ' . $GUI->Stelle->id . ' nicht gefunden.'
				);
			}

			return array(
				'success' => true,
				'msg' => 'Gesamtpaket der Stelle: ' . $GUI->Stelle->id . ' gelöscht.'
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
		// echo '<p>metadata_delete_data_package package_id: ' . $package_id;
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

			// Das löschen von Paketen die in Arbeit sind wird auch unterstützt, weil wir davon ausgehen,
			// das der Status in Arbeit nach einem Fehler stehen geblieben ist und solche Pakete auch zurück
			// gesetzt werden können müssen.
			// if (!$GUI->formvars['force'] != '' AND $package->get('pack_status_id') == 3) {
			// 	return array(
			// 		'success' => true,
			// 		'msg' => 'Der Auftrag wird abgebrochen, weil ein anderer Prozess gerade das Paket befüllt.'
			// 	);
			// }

			if (!$package->layer) {
				return array(
					'success' => true,
					'msg' => 'Der Auftrag wird abgebrochen, weil die Ressource keinen zugeordneten Layer hat!'
				);
			}

			$export_path = $package->get_export_path();
			// Dateien im Verzeichnis löschen
			$package->update_attr(array('pack_status_id = 3'));
			$package->delete_export_path();
			$package->delete_export_file();
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
	 * Function download the bundle package of current stelle.
	 * @return array{ success: Boolean, msg: String, downloadUrl?: String}
	 */
	$GUI->metadata_download_bundle_package = function() use ($GUI) {
		try {
			list($bundle_package_path, $bundle_package_filename) = DataPackage::get_bundle_package_file($GUI->Stelle->id);

			return array(
				'success' => true,
				'msg' => 'Download-Datei zur Stelle: ' . $GUI->Stelle->id . ' gefunden.',
				'downloadfile' => $bundle_package_path . $bundle_package_filename
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
				'msg' => 'Downloaddatei zum Paket ID: ' . $package_id . ' gefunden.',
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
	 * Function responds metadata document of layer with $selected_layer_id
	 */
	$GUI->metadata_download_metadata_document = function($layer_id) use ($GUI) {
		if ($layer_id == '') {
			return array(
				'success' => false,
				'msg' => 'Der Parameter layer_id ist leer!'
			);
		}
		$layer = Layer::find_by_id($GUI, $layer_id);
		if ($layer->get_id() == '') {
			return array(
				'success' => false,
				'msg' => 'Es wurde kein Layer mit der id: ' . $layer_id . ' gefunden!'
			);
		}
		$metadata_file = METADATA_DATA_PATH . 'metadaten/' . $layer_id . '.pdf';
		if (!file_exists($metadata_file)) {
			$response = $GUI->metadata_create_metadata_document($layer_id);
			if (!$response['success']) {
				return array(
					'success' => false, 
					'msg' => $response['msg']
				);
			}
		}
		if (!file_exists($metadata_file)) {
			return array(
				'success' => false,
				'msg' => 'Die Metadatendatei ' . $metadata_file . ' wurde nicht auf dem Server gefunden!'
			);
		}

		return array(
			'success' => true,
			'msg' => 'Metadatendatei gefunden.',
			'downloadfile' => $metadata_file,
			'filename' => $layer->get('name')
		);
	};

	/**
	 * Function remove existing bundle package of current Stelle and
	 * create a new bundle package with all existing zip-files of ressources for current stelle in the background
	 * by calling the package_cron.php with stelle_id and login_name
	 * @return array{ success: Boolean, msg: String}
	 */
	$GUI->metadata_order_bundle_package = function() use ($GUI) {
		// echo '<p>metadata_order_bundle_package for stelle_id: ' . $GUI->Stelle->id . ' and login_name: ' . $GUI->user->login_name;
		try {
			list($bundle_package_path, $bundle_package_filename) = DataPackage::get_bundle_package_file($GUI->Stelle->id);
			if ($bundle_package_path != '' AND $bundle_package_filename != '' AND file_exists($bundle_package_path . $bundle_package_filename)) {
				unlink($bundle_package_path . $bundle_package_filename);
			}
			$cmd = "cd /var/www/apps/kvwmap/plugins/metadata/tools; php -f packages_cron.php go=metadata_create_bundle_package stelle_id=" . $GUI->Stelle->id . " login_name=" . $GUI->user->login_name . " >> /var/www/logs/cron/packages_cron.log 2>&1 &";
			// echo $cmd;
			$outputs = array();
			exec($cmd, $outputs, $status);
			return array(
				'success' => true,
				'msg' => 'Erstellung des Gesamtpaketes für Stelle Id: ' . $GUI->Stelle->id . ' beauftragt. ' . implode(', ', $outputs)
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
		// echo '<p>metadata_order_data_package ressource_id: ' . $ressource_id . ' in stelle_id: ' . $stelle_id;
		try {
			$package = new DataPackage($GUI);
			$packages = $package->find_where("stelle_id = " . $stelle_id . " AND ressource_id = " . $ressource_id);
			if (count($packages) > 0) {
				// Dieses Paket gibt es schon. Setze es auf 2 (beauftragt)
				$packages[0]->update_attr(array('status_id' => 2));
			}
			else {
				$package->create(array(
					'stelle_id' => $stelle_id,
					'ressource_id' => $GUI->formvars['ressource_id'],
					'pack_status_id' => 2, // Paketerstellung beauftragt
					'created_from' => $GUI->user->Vorname . ' ' . $GUI->user->Name
				), true);

				return array(
					'success' => true,
					'package' => $package->data,
					'msg' => 'Download-Paket mit id: ' . $package->get_id() . ' angelegt. Packen für Ressource ID: ' . $package->get('ressource_id') . ' beauftragt.'
				);
			}
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler: ' . print_r($e, true)
			);
		}
	};

	/**
	 * Function update all packages related to the $ressource_id.
	 * This process first delete existing packages and than
	 * order the packages new to pack_status (beauftragt, ID: 2) and
	 * closing the error messages in pack_logs with fixed_at = now.
	 * The packages_cron will than automatically create and replace the packages
	 * and set the pack_status to ready (fertig, ID: 4).
	 * @param int $ressource_id The id of the ressource.
	 * @return array{ success: Boolean, msg: String} 
	 */
	$GUI->metadata_reorder_data_packages = function($ressource_id) use ($GUI) {
		$packages = DataPackage::find_by_ressource_id($GUI, $ressource_id);
		$results = array();
		foreach ($packages AS $package) {
			$package_id = $package->get('id');
			$stelle_id = $package->get('stelle_id');
			$results[] = $GUI->metadata_delete_data_package($package_id);
			$results[] = $GUI->metadata_order_data_package($ressource_id, $stelle_id);
		}
		$results[] = PackLog::fix($GUI, $ressource_id);

		$error_results = array_filter(
			$results,
			function ($result) {
				return !$result['success'];
			}
		);
		if (count($error_results) > 0) {
			return array(
				'success' => false,
				'msg' => implode(', ', array_map(
					function ($error_result) {
						return $error_result['msg'];
					},
					$error_results
				))
			);
		}
		return array(
			'success' => true,
			'msg' => 'Neuerstellung der Pakete beauftragt.'
		);
	};

	$GUI->metadata_set_ressource_status = function() use ($GUI) {
		$ressource = Ressource::find_by_id($GUI, 'ressource_id', $GUI->formvars['ressource_id']);
		$result = $ressource->update_attr(array('status_id' => $GUI->formvars['status_id']));
		if (!$result['success']) {
			return $result;
		}
		return array(
			'success' => true,
			'msg' => 'Status erfolgreich aktualisiert'
		);
	};

	$GUI->metadata_show_data_packages = function() use ($GUI) {
		$GUI->main = PLUGINS . 'metadata/view/data_packages.php';
		$all_packages = DataPackage::find_by_stelle_id($GUI, $GUI->Stelle->id);
		$GUI->metadata_data_packages = array();
		foreach ($all_packages AS $package) {
			$pfad = replace_params_rolle($package->layer->get('pfad'));
			$where = "";
			if (in_array($package->layer->get('datentyp'), array(0, 1, 2, 7, 8))) {
				// Nur für Vektorlayer
				$where = "WHERE " . $GUI->pgdatabase->get_extent_filter(
					$GUI->Stelle->MaxGeorefExt,
					$GUI->user->rolle->epsg_code,
					$package->layer->get('geom_column'),
					$package->layer->get('epsg_code')
				);
			}
			$sql = "
				SET search_path = " . $package->layer->get('schema') . ", public;
				SELECT
					count(*) AS anzahl
				FROM
					(
						" . $pfad . "
					) AS query
				" . $where . "
			";
			// echo '<br>SQL zum Filtern der Daten ' . $package->get('bezeichnung') . ' im Datenpaket: ' . $sql;
			$query = pg_query($sql);
			$num_feature = pg_fetch_assoc($query)['anzahl'];
			if ( $num_feature > 0) {
				// echo '<br>Anzahl Datensätze: ' . $num_feature;
				$package->num_feature = $num_feature;
				$GUI->metadata_data_packages[] = $package;
			}
			// else {
			// 	echo '<br>Ressource: ' . $package->get('ressource_id') . ' Paket: ' . $package->get('id') . ' ' . $package->get('bezeichnung') . ' Layer-ID: ' . $package->get('layer_id') . ' Datentyp: ' . $package->layer->get('Datentyp') . ' geom_column: ' . $package->layer->get('geom_column') . ' hat keine Daten in dieser Stelle Abfrage:<br><textarea cols="60" rows="10">' . $sql . '</textarea>';
			// }
		}
		$GUI->output();
	};

	$GUI->metadata_show_outdated = function() use ($GUI) {
		$GUI->metadata_outdated_ressources = Ressource::find_outdated($GUI);
		$results = $GUI->metadata_outdated_ressources[0]->getSQLResults("
			SELECT count(id) AS num_running FROM metadata.ressources WHERE use_for_datapackage AND status_id > 0 AND status_id < 11;
		");
		$GUI->metadata_num_running = $results[0]['num_running'];
		$command = "ps aux | grep -i 'ressources_cron.php' | grep -v grep";
		$output = '';
		$result_code = '';
		exec($command, $outputs, $result_code);
		$GUI->metadata_processes = $outputs;
		$GUI->main = PLUGINS . 'metadata/view/outdated_ressources.php';
		$GUI->output();
	};

	$GUI->metadata_show_ressources_status = function($ressource_id) use ($GUI) {
		$GUI->metadata_ressources = Ressource::find($GUI, "von_eneka OR use_for_datapackage", "auto_update, status_id");
		$command = "ps aux | grep -i 'ressources_cron.php' | grep -v grep";
		$output = '';
		$result_code = '';
		exec($command, $outputs, $result_code);
		$GUI->metadata_processes = $outputs;
		$GUI->main = PLUGINS . 'metadata/view/ressources_status.php';
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