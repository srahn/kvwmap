<?php
// mobile_delete_images
// mobile_download_image
// mobile_get_data
// mobile_get_data_version
// mobile_get_layers
// mobile_get_pmtiles
// mobile_get_pmtiles_style
// mobile_get_stellen
// mobile_list_logs
// mobile_reset_log
// mobile_set_sync_reset_needed
// mobile_show_log
// mobile_sync
// mobile_sync_all
// mobile_upload_image'

if (strpos($go, '_') !== false AND substr($go, 0, strpos($go, '_')) === 'mobile') {
	include(PLUGINS . 'mobile/model/kvwmap.php');
}

function go_switch_mobile($go) {
	global $GUI;
	switch ($GUI->go) {
		case 'mobile_set_sync_reset_needed': {
			$GUI->checkCaseAllowed('Administratorfunktionen');
			$GUI->sanitize(['log_name' => 'text']);
			$GUI->mobile_set_sync_reset_needed($GUI->formvars['log_name']);
			$GUI->output();
		}
		break;

		case 'mobile_get_stellen': {
			$result = $GUI->mobile_get_stellen();
			echo json_encode($result);
		}
		break;

		case 'mobile_get_layers': {
			$result = $GUI->mobile_get_layers();
			if ($GUI->mobile_sync_reset_needed($GUI->user->login_name)) {
				$result['layers'][0]['version'] .= '_reset_needed';
			}
			echo json_encode($result);
		}
		break;

		case 'mobile_get_data_version': {
			$GUI->sanitize(['selected_layer_id' => 'int']);
			$result = $GUI->mobile_get_data_version();
			echo json_encode($result);
		}
		break;

		case 'mobile_get_data': {
			$GUI->sanitize(['selected_layer_id' => 'int', 'version' => 'int']);
			$GUI->formvars['without_filter'] = 1;
			$GUI->formvars['export_format'] = 'GeoJSONPlus';
			$GUI->formvars['all'] = 1;
			$GUI->formvars['epsg'] = 4326;
			$GUI->formvars['sql_' . $GUI->formvars['selected_layer_id']] = 'WHERE (version IS NULL OR version <= ' . $GUI->formvars['last_delta_version'] . ')';
			$GUI->daten_export_exportieren();
		}
		break;

		case 'mobile_get_pmtiles_style': {
			$file = '/var/www/data/pmtiles/Flurstuecke_Style.json';
			header('Content-Type: application/json; charset=utf-8');
			header('Content-Length: ' . filesize($file));
			readfile($file);
		}
		break;

		case 'mobile_get_pmtiles': {
			$filename = '/var/www/data/pmtiles/Flurstuecke.pmtiles';
			# Wenn Apache mit dem module mod_xsendfile (siehe: https://tn123.org/mod_xsendfile/)
			# eingerichtet ist (siehe: https://support.mfscripts.com/public/kb_view/1/)
			# kann X-Sendfile Header gesetzt werden und Apache kümmert sich um die Fileausgabe.
			# Das geht korrekter als mit PHP file_get_contents und schneller
			header("X-Sendfile: " . $filename);
			#				header("Content-Type: application/octet-stream");
			header('Content-Disposition: attachment; filename="Flurstuecke.pmtiles"');
			exit;

			// $headers = apache_request_headers();
			// if ($headers and array_key_exists('range', $headers)) {
			// 	# expects a header Range: bytes=0-16383
			// 	$range_parts = explode('=', $headers['range']);
			// 	if ($range_parts[0] == 'bytes') {
			// 		$bytes_parts = explode('-', $range_parts[1]);
			// 		$range_von = $bytes_parts[0];
			// 		$range_bis = $bytes_parts[1] - 1;
			// 	}
			// }
			// else {
			// 	$range_von = 0;
			// 	$range_bis = filesize($filename) - 1;
			// }
			// header('Content-Type: application/octet-stream;');
			// header('Content-Length: ' . ($range_bis - $range_von));
			// header('Accept-Ranges: bytes');
			// header('Content-Range: bytes ' . $range_von . '-' . $range_bis . '/*');

			// $file_part = file_get_contents($filename, false, null, $range_von, $range_bis);
			// echo $file_part;
			// exit;
		}
		break;

		case 'mobile_list_logs' : {
			$GUI->checkCaseAllowed('Administratorfunktionen');
			include_once(CLASSPATH . 'administration.php');
			$GUI->administration = new administration($GUI->database, $GUI->pgdatabase);	
			$GUI->mobile_list_logs();
			$GUI->output();
		} break;

		case 'mobile_reset_log' : {
			$GUI->checkCaseAllowed('Administratorfunktionen');
			$GUI->sanitize(['log_name' => 'text']);
			include_once(CLASSPATH . 'administration.php');
			$GUI->administration = new administration($GUI->database, $GUI->pgdatabase);	
			$GUI->mobile_reset_log($GUI->formvars['log_name']);
			$GUI->mobile_list_logs();
			$GUI->output();
		} break;

		case 'mobile_show_log' : {
			$GUI->checkCaseAllowed('Administratorfunktionen');
			$GUI->sanitize(['log_name' => 'text']);
			include_once(CLASSPATH . 'administration.php');
			$GUI->administration = new administration($GUI->database, $GUI->pgdatabase);	
			$GUI->mobile_show_log($GUI->formvars['log_name']);
			$GUI->output();
		} break;

		case 'mobile_sync': {
			$GUI->sanitize(['selected_layer_id' => 'int', 'table_name' => 'text', 'last_client_version' => 'int']);
			$result = $GUI->mobile_sync();
			echo json_encode($result);
		}
		break;

		case 'mobile_sync_all': {
			$GUI->sanitize([
				'client_id' => 'text',
				'client_time' => 'varchar',
				'last_delta_version' => 'int'
			]);
			// If the user is allowed to execute the deltas in this stelle will be checkt in GUI->mobile_sync_all() > sync->sync_allowed(delta, sync_layers)
			$result = $GUI->mobile_sync_all();
			if ($GUI->mobile_sync_reset_needed($GUI->user->login_name)) {
				$GUI->mobile_log->write('<br>sync_reset_proceeded');
			}
			echo json_encode($result);
		}
		break;

		case 'mobile_delete_images': {
			$GUI->sanitize(['selected_layer_id' => 'int']);
			$GUI->checkCaseAllowed($GUI->go, false);
			$result = $GUI->mobile_delete_images($GUI->formvars['selected_layer_id'], $GUI->formvars['images']);
			echo json_encode($result);
		}
		break;

		case 'mobile_upload_image': {
			$GUI->sanitize(['selected_layer_id' => 'int']);
			# Prüfen was hier kommt wenn go nicht erlaubt ist und ob checkCaseAllowed false liefert.
			if (($GUI->Stelle->isMenueAllowed($go) or $GUI->Stelle->isFunctionAllowed($go))) {
				$result = $GUI->mobile_upload_image($GUI->formvars['selected_layer_id'], $_FILES);
			} else {
				$result = array(
					'success' => false,
					'msg' => 'Anwendungsfall mobile_upload_image auf diesem Server für diese Stelle nicht erlaubt. Lassen Sie die Funktion oder einen entsprechenden Menüpunkt vom Administrator der Anwendung freigeben!'
				);
			}
			echo json_encode($result);
		}
		break;

		case 'mobile_download_image': {
			$GUI->checkCaseAllowed($GUI->go);
			$file = $GUI->formvars['image'];
			header('Content-Type: image/jpeg');
			header('Content-Length: ' . filesize($file));
			readfile($file);
		}
		break;

		default: {
			$GUI->goNotExecutedInPlugins = true; // in diesem Plugin wurde go nicht ausgeführt
		}
	}
}