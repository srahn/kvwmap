<?php
#############################
# Klasse Ressource #
#############################
include_once(CLASSPATH . 'PgObject.php');
include_once(PLUGINS . 'metadata/model/SubRessource.php');

class Ressource extends PgObject {

	static $schema = 'metadata';
	static $tableName = 'ressources';
	public $write_debug = false;
	public $has_subressources = false;
	public $has_ressource_ranges = false;

	public $sub_ressources = array();

	function __construct($gui) {
		$gui->debug->show('Create new Object ressource in table ' . Ressource::$schema . '.' . Ressource::$tableName, $this->$write_debug);
		parent::__construct($gui, Ressource::$schema, Ressource::$tableName);
		include_once(CLASSPATH . 'data_import_export.php');
		$this->gui->data_import_export = new data_import_export('gid');
		// $this->typen = array(
		// 	'Punkte',
		// 	'Linien',
		// 	'Flächen'
		// );
	}

	public static	function find($gui, $where, $order = '') {
		$ressource = new Ressource($gui);
		return $ressource->find_where($where, $order);
	}

	public static	function find_by_id($gui, $by, $id) {
		$ressource = new Ressource($gui);
		$ressource->find_by($by, $id);
		$ressource->get_subressources();
		return $ressource;
	}

	public static	function find_by_layer_id($gui, $layer_id) {
		$ressource = new Ressource($gui);
		$ressource->find_by('layer_id', $layer_id);
		$ressource->get_subressources();
		return $ressource;
	}

	function get_subressources() {
		$subresource = new SubRessource($this->gui);
		$subressources = $subresource->find_by_ressource_id($this->get_id());
		$this->has_subressources = count($subressources) > 0;
		$this->subressources = $subressources;
		return $subressources;
	}

	function get_full_dest_path() {
		return rtrim(METADATA_DATA_PATH . 'ressourcen/' . $this->get('dest_path'), '/') .'/';
	}

	function destroy() {
		#echo "\ndestroy Dataset: " . $this->get($this->identifier);
		$this->debug->show('destroy dataset ' . $this->get('datenquelle'), Dataset::$write_debug);
		$this->delete();
	}

	/**
	 * Function find first outdated ressource and run the update process
	 * if less than 10 processes running already
	 * A ressource is outdated if
	 * status is not set or 0 and
	 * auto_update is set to true and (
	 *   last_update is not defined or (
	 *     last_update is defined and
	 *     update_internval is defined and
	 *     DATE(last_updated_at) + update_time + update_interval is in the past
	 *   )
	 * )
	 * Status of ressources during update:
	 * -1 - Abbruch wegen Fehler
	 *  0 - Uptodate
	 *  1 - Update gestartet
	 *  2 - Download gestartet
	 *  3 - Download fertig
	 *  4 - Auspacken gestartet
	 *  5 - Auspacken fertig
	 *  6 - Import gestartet
	 *  7 - Import fertig
	 *  8 - Transformation gestartet
	 *  9 - Transformation fertig
	 */
	public static function update_outdated($gui, $ressource_id = null, $method_only = '') {
		$gui->debug->show('<br>Starte Funktion update_outdated' . ($ressource_id != null ? ' mit Ressource id: ' . $ressource_id : ''), true);
		$ressource = new Ressource($gui);
		if ($ressource_id != null) {
			$ressources = $ressource->find_where('id = ' . $ressource_id);
		}
		else {
			$results = $ressource->getSQLResults("
				SELECT count(id) AS num_running FROM metadata.ressources WHERE status_id > 0;
			");
			if ($results[0]['num_running'] < 10) {
				$ressources = $ressource->find_where(
					"
						(status_id IS NULL OR status_id = 0) AND
						auto_update AND
						(
							last_updated_at IS NULL OR
							(
								last_updated_at IS NOT NULL AND
								update_interval IS NOT NULL AND
								DATE(last_updated_at) + update_time + update_interval < now()
							)
						)
					",
					"last_updated_at",
					"*",
					1
				);
			}
		}
		// $gui->debug->show('Anzahl gefundener Ressourcen: ' . count($ressources), true);

		if (count($ressources) > 0) {
			$ressource = $ressources[0];
			// echo '<br>Update outdated ressource: ' . $ressource->get('bezeichnung') . ' (' . $ressource->get_id() . ')';
			$result = $ressource->run_update($method_only);
			$ressource->log($result, true);
		}
		else {
			return array(
				'success' => true,
				'msg' => 'Es sind zur Zeit keine Ressourcen zu aktualisieren.'
			);
		}
	}

	function log($result, $show = false) {
		UpdateLog::write($this->gui, $this, $result, $show);
	}

	function run_update($method_only = '') {
		$this->debug->show('Run Update für Ressource id: ' . $this->get_id(), true);
		$this->update_status(1, $msg);

		if ($method_only == '' OR $method_only == 'download') {
			$result = $this->download();
			if (!$result['success']) { return $result; }
		}
		if ($method_only == '' OR $method_only == 'unpack') {
			$result = $this->unpack();
			if (!$result['success']) { return $result; }
		}
		if ($method_only == '' OR $method_only == 'import') {
			$result = $this->import();
			if (!$result['success']) { return $result; }
		}
		if ($method_only == '' OR $method_only == 'transform') {
			$result = $this->transform();
			if (!$result['success']) { return $result; }
		}

		$this->update_status(0);
		return array(
			'success' => true,
			'msg' => $msg . '<br>Ressource erfolgreich aktualisiert.'
		);
	}

	function update_status($status_id, $msg = '') {
		if ($msg != '') {
			echo '<br>Update Status auf ' . $status_id . '<br>Msg: ' . $msg;
		}
		$this->update_attr(array('status_id = ' . (string)$status_id));
	}

	####################
	# Download methods #
	####################
	function download() {
		$this->debug->show('Starte Funktion download', true);
		if ($this->get('download_method') != '') {
			$method_name = 'download_' . $this->get('download_method');
			if (!method_exists($this, $method_name)) {
				return array(
					'success' => false,
					'msg' => 'Die Funktion ' . $method_name . ' zum Download der Ressource existiert nicht.'
				);
			}

			$this->update_status(2);
			$result = $this->${method_name}();
			if (!$result['success']) { return $result; }

			$this->update_status(3);
			return $result;
		}
		return array(
			'success' => true,
			'msg' => 'Keine Downloadmethode angegeben.'
		);
	}
	/**
	 * Download dataset or its subsets to download_path
	 */
	function download_urls() {
		$this->debug->show('Starte Funktion download_urls', true);
		$download_urls = array();
		try {
			if ($this->get('download_url') != '') {
				$download_urls = array($this->get('download_url'));
			}
			else {
				$this->debug->show('download_url ist leer.', true);
				$this->get_subressources();
				if ($this->has_subressources) {
					$this->debug->show('Hole urls aus subressources.', true);
					$download_urls = array_merge(
						$download_urls,
						array_merge(
							...array_map(
								function($subresource) {
									$urls = $subresource->get_download_urls();
									return $urls;
								},
								$this->subressources
							)
						)
					);
				}
			}
			// $this->debug->show('Download from URLs:<br>' . implode('<br>', $download_urls), true);
			if ($this->get('download_path') == '') {
				return array(
					'success' => false,
					'msg' => 'Es ist kein relatives Download-Verzeichnis angegeben.'
				);
			}
			$download_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('download_path');
			if (strpos($download_path, '/var/www/data/') !== 0) {
				return array(
					'success' => false,
					'msg' => 'Das Download-Verzeichnis ' . $download_path . ' fängt nicht mit ' . '/var/www/data/' . ' an.'
				);
			}
			if (!file_exists($download_path)) {
				$this->debug->show('Lege Verzeichnis ' . $download_path . ' an, weil es noch nicht existiert!', true);
				mkdir($download_path, 0777, true);
			}
			$only_missing = ((array_key_exists('only_missing', $this->formvars) AND $this->formvars['only_missing']) ? true : false);
			if ($only_missing) {
				array_map('unlink', glob($download_path . "/*"));
				$this->debug->show('Alle Dateien im Verzeichnis ' . $download_path . ' gelöscht.', true);
			}

      foreach ($download_urls AS $download_url) {
        $this->debug->show('Download from: ' . $download_url . ' to ' . $download_path, true);
        if (!($only_missing AND file_exists($download_path . basename($download_url)))) {
          copy($download_url, $download_path . basename($download_url));
          // if ($this->get('format_id') == 5 AND !exif_imagetype($download_path . basename($download_url))) {
          //   unlink($download_path . basename($download_url));
          //   $this->debug->show('Datei ' . basename($download_url) . ' gelöscht weil es keine Bilddatei ist.');
          // }
        }
      }
    }
    catch (Exception $e) {
      return array(
        'success' => false,
        'msg' => 'Fehler beim Download der Daten: ', $e->getMessage()
      );
    }

		return array(
			'success' => true,
			'msg' => 'Download von URLs erfolgreich beendet.'
		);
	}

	/**
	 * Download from WMS
	 */
	function download_wms() {
		// Die Download Methode läd die Daten nicht beim Aktualisierungsprozess runter sondern nur bei der Visualisierung
		// Im Datenpaket werden Ressourcen mit dieser Downloadmethode als Remote WMS beschrieben und mit Metadatend versehen.
		// ToDo: Hier könnte aber ggf. die Verfügbarkeit des Dienstes geprüft werden und im Fehler fall in dem Protokoll des Updates mit ausgegeben werden.
		// letzer_update wäre dann das Datum wann das letzte mal geprüft wurde ob der Lienst funktioniert.
		return array(
			'success' => true,
			'msg' => 'Download von WMS erfolgreich beendet.'
		);
	}

	/**
	 * Download from WFS
	 */
	function download_wfs() {
		// ToDo: implement on demand
		$url = $this->get('download_url');
		$epsg = ($this->get('import_epsg') ? $this->get('import_epsg') : '25832');
		$params = array(
			'Service' => 'WFS',
			'Version' => '2.0.0',
			'Request' => 'GetFeature',
			'TypeNames' => $this->get('import_layer'),
			'SRS' => 'urn:ogc:def:crs:EPSG::' . $epsg
		);

		try {
			$this->debug->show('Download from URL:<br>' . $url, true);
			if ($this->get('download_path') == '') {
				return array(
					'success' => false,
					'msg' => 'Es ist kein relatives Download-Verzeichnis angegeben.'
				);
			}
			$download_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('download_path');
			if (strpos($download_path, '/var/www/data/') !== 0) {
				return array(
					'success' => false,
					'msg' => 'Das Download-Verzeichnis ' . $download_path . ' fängt nicht mit ' . '/var/www/data/' . ' an.'
				);
			}

			if (!file_exists($download_path)) {
				$this->debug->show('Lege Verzeichnis ' . $download_path . ' an, weil es noch nicht existiert!', true);
				mkdir($download_path, 0777, true);
			}

			array_map('unlink', glob($download_path . "/*"));
			$this->debug->show('Alle Dateien im Verzeichnis ' . $download_path . ' gelöscht.', true);


			if ($this->get('import_table')) {
				$download_url = $url . (strpos($url, '?') === false ? '?' : (in_array(substr($url, -1), array('?', '&')) ? '' : '&')) . http_build_query($params);
				$download_file = $this->get('import_table') . '.gml';
				$this->debug->show('Download ' . $download_file . ' from url: ' . $download_url . ' to ' . $download_path, true);
				copy($download_url, $download_path .  $download_file);
			}
			else {
				// query featuretypes from capabilities and download all in separate gml-files.
				include_once(CLASSPATH . 'wfs.php');
				$wfs = new wfs($url, $params['Version'], '', '', $this->get('import_epsg'));
				$wfs->parse_capabilities($url);
				$featuretypes = $wfs->get_featuretypes();
				$this->debug->show('Download folgende FeatureTypes in gml files in download path:' . $download_path, true);
				foreach ($featuretypes AS $featuretype) {
					$params['TypeNames'] = $featuretype['name'];
					// $this->debug->show('params: ' . http_build_query($params), true);
					$download_url = $url . (strpos($url, '?') === false ? '?' : (in_array(substr($url, -1), array('?', '&')) ? '' : '&')) . http_build_query($params);
					$download_file = strtolower(sonderzeichen_umwandeln(str_replace(':', '_', umlaute_umwandeln($featuretype['name'])))) . '.gml';
					$this->debug->show($featuretype['name'] . ' (' . $featuretype['title'] . ') => ' . $download_file, true);
					copy($download_url, $download_path .  $download_file);
				}
			}

			return array(
				'success' => true,
				'msg' => 'Download von WFS erfolgreich beendet.'
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Download der Daten: ', $e->getMessage()
			);
		}
	}

	/**
	 * Download dataset or its subsets to download_path
	 */
	function download_atom() {
		$this->debug->show('Starte Funktion download_atom', true);
		try {
			// $this->debug->show('Download from URLs:<br>' . implode('<br>', $download_urls), true);
			if ($this->get('download_path') == '') {
				return array(
					'success' => false,
					'msg' => 'Es ist kein relatives Download-Verzeichnis angegeben.'
				);
			}

			$download_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('download_path');
			if (strpos($download_path, '/var/www/data/') !== 0) {
				return array(
					'success' => false,
					'msg' => 'Das Download-Verzeichnis ' . $download_path . ' fängt nicht mit ' . '/var/www/data/' . ' an.'
				);
			}

			if ($this->get('download_url') == '') {
				return array(
					'success' => false,
					'msg' => 'Es ist keine Download-URL angegeben.'
				);
			}
			$atom_url = $this->get('download_url');
			// z.B. https://www.geodaten-mv.de/dienste/dom_atom?type=dataset&id=us214578-a1n5-4v12-v31c-5tg2az3a2164


			$this->get_subressources();
			if (!$this->has_subressources) {
				return array(
					'success' => false,
					'msg' => 'Es sind keine Teildaten angegeben.'
				);
			}

			if ($this->subressources[0]->get('download_url') == '') {
				return array(
					'success' => false,
					'msg' => 'Die Teildaten haben keine download_url.'
				);
			}
			$teile_url = $this->subressources[0]->get('download_url');
			// z.B. https://www.geodaten-mv.de/dienste/dom_download?index=4&amp;dataset=us214578-a1n5-4v12-v31c-5tg2az3a2164&amp;file=dom1_33_$x_$y_2_gtiff.tif

			if (!$this->subressources[0]->has_ranges) {
				return array(
					'success' => false,
					'msg' => 'Die Teildaten haben keine Bereiche.'
				);
			}

			$placeholders = array_map(
				function($range) {
					return $range->get('name');
				},
				$this->subressources[0]->ranges
			);

			if (!file_exists($download_path)) {
				$this->debug->show('Lege Verzeichnis ' . $download_path . ' an, weil es noch nicht existiert!', true);
				mkdir($download_path, 0777, true);
			}

			array_map('unlink', glob($download_path . "/*"));
			$this->debug->show('Alle Dateien im Verzeichnis ' . $download_path . ' gelöscht.', true);

			$this->debug->show('Download ATOM-Feed from: ' . $atom_url . ' to ' . $download_path, true);
			copy($atom_url, $download_path . 'atom-feed.xml');
			$handle = fopen($download_path . 'atom-feed.xml', "r");
			if (!$handle) {
				return array(
					'success' => false,
					'msg' => 'Konnte Atom-Feed Datei ' . $download_path . ' atom-feed.xml nicht öffnen'
				);
			}

			$teile_url = str_replace('?', '\?', str_replace('/', '\/', $teile_url));
			foreach ($placeholders AS $placeholder) {
				$teile_url = str_replace($placeholder, '(.*?)', $teile_url);
			}
			$regex = '/' . $teile_url . '/';
			$this->debug->show('Check Lines against regex: ' . $regex, true);
			while (($line = fgets($handle)) !== false) {
				if (preg_match($regex, $line, $match) == 1) {
					$download_url = str_replace('&amp;', '&', $match[0]);
					$parts = explode('file=', $download_url);
					$this->debug->show('Download File from: ' . $download_url . ' to ' . $download_path . $parts[1], true);
					copy($download_url, $download_path . $parts[1]);
					if ($this->get('format_id') == 5 AND !exif_imagetype($download_path . $parts[1])) {
						unlink($download_path . $parts[1]);
						$this->debug->show('Datei ' . $parts[1] . ' gelöscht weil es keine Bilddatei ist.');
					}
				}
			}
			fclose($handle);
			unlink($download_path . 'atom-feed.xml');
			$this->debug->show('Download beendet und ATOM-Feed gelöscht', true);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Download der Atom-Daten: ', $e->getMessage()
			);
		}

		return array(
			'success' => true,
			'msg' => 'Download von URLs erfolgreich beendet.'
		);
	}

	##################
	# Unpack methods #
	##################
	function unpack() {
		$this->debug->show('Starte Funktion unpack', true);
		if ($this->get('unpack_method') != '') {
			$method_name = 'unpack_' . $this->get('unpack_method');
			if (!method_exists($this, $method_name)) {
				return array(
					'success' => false,
					'msg' => 'Die Funktion ' . $method_name . ' zum Auspacken der Ressource existiert nicht.'
				);
			}
			$this->update_status(4);
			$result = $this->${method_name}();
			if (!$result['success']) { return $result; }
			$this->update_status(5);
			return $result;
		}
		return array(
			'success' => true,
			'msg' => 'Keine Auspackmethode angegeben.'
		);
	}
	/**
	 * Function unzip specific or all files of a directory to a destination directory,
	 * log it in a logfile,
	 * and remove the zip-files afterward
	 */
	function unpack_unzip() {
		$this->debug->show('Starte Funktion unpack_unzip', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein relatives Auspackverzeichnis angegeben.'
			);
		}
		$dest_path = $this->get_full_dest_path();
		if (strpos($dest_path, '/var/www/data/') !== 0) {
			return array(
				'success' => false,
				'msg' => 'Das Auspackverzeichnis ' . $dest_path . ' fängt nicht mit /var/www/data/ an.'
			);
		}
		if (!file_exists($dest_path)) {
			$this->debug->show('Lege Verzeichnis ' . $dest_path . ' an, weil es noch nicht existiert!', true);
			mkdir($dest_path, 0777, true);
		}

		$download_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('download_path');

		$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type aka mimetype extension
		$err_msg = array();
		foreach (glob($download_path . '*') as $filename) {
			if (finfo_file($finfo, $filename) == 'application/zip') {
				echo '<br>filename: ' . $filename;
				$cmd = 'unzip -j -o "' . $filename . '" -d ' . $dest_path;
				$this->debug->show('Packe Datei aus mit Befehl: ' . $cmd, true);
				$descriptorspec = [
					0 => ["pipe", "r"],  // stdin
					1 => ["pipe", "w"],  // stdout
					2 => ["pipe", "w"],  // stderr
				];
				$process = proc_open($cmd, $descriptorspec, $pipes, dirname(__FILE__), null);
				$line = __LINE__;
				$stdout = stream_get_contents($pipes[1]);
				fclose($pipes[1]);
				$stderr = stream_get_contents($pipes[2]);
				fclose($pipes[2]);
				#    exec($cmd, $output, $return_var);
				if ($stderr != '') {
					$err_msg[] = 'Fehler bei unzip der Ressource ' . $this->get_id() . ' in Datei: ' . basename(__FILE__) . ' Zeile: ' . $line . ' Rückgabewert: ' . $stderr;
				}
			}
		}
		finfo_close($finfo);
		if (count($err_msg) > 0) {
			return array(
				'success' => false,
				'msg' => implode(', ', $err_msg)
			);
		}
		return array(
			'success' => true,
			'msg' => 'Ressource erfolgreich ausgepackt.'
		);
	}

	/**
	 * Function unzip specific or all files of a directory to a destination directory,
	 * unzip the extracted files in destination directory when they are zip files
	 * and remove the original zip files in destination directory
	 */
	function unpack_unzip_unzip() {
		$this->debug->show('Starte Funktion unpack_unzip_unzip', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein relatives Auspackverzeichnis angegeben.'
			);
		}
		$dest_path = $this->get_full_dest_path();
		if (strpos($dest_path, '/var/www/data/') !== 0) {
			return array(
				'success' => false,
				'msg' => 'Das Auspackverzeichnis ' . $dest_path . ' fängt nicht mit /var/www/data/ an.'
			);
		}
		if (!file_exists($dest_path)) {
			$this->debug->show('Lege Verzeichnis ' . $dest_path . ' an, weil es noch nicht existiert!', true);
			mkdir($dest_path, 0777, true);
		}
		$download_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('download_path');

		$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type aka mimetype extension
		$err_msg = array();
		foreach (glob($download_path . '*') as $filename) {
			if (finfo_file($finfo, $filename) == 'application/zip') {
				echo '<br>filename: ' . $filename;
				$cmd = 'unzip -j -o "' . $filename . '" -d ' . $dest_path;
				$this->debug->show('Packe Datei aus mit Befehl: ' . $cmd, true);
				$descriptorspec = [
					0 => ["pipe", "r"],  // stdin
					1 => ["pipe", "w"],  // stdout
					2 => ["pipe", "w"],  // stderr
				];
				$process = proc_open($cmd, $descriptorspec, $pipes, dirname(__FILE__), null);
				$line = __LINE__;
				$stdout = stream_get_contents($pipes[1]);
				fclose($pipes[1]);
				$stderr = stream_get_contents($pipes[2]);
				fclose($pipes[2]);
				#    exec($cmd, $output, $return_var);
				if ($stderr != '') {
					$err_msg[] = 'Fehler bei unzip der Ressource ' . $this->get_id() . ' in Datei: ' . basename(__FILE__) . ' Zeile: ' . $line . ' Rückgabewert: ' . $stderr;
				}
			}
		}
		finfo_close($finfo);
		if (count($err_msg) > 0) {
			return array(
				'success' => false,
				'msg' => implode(', ', $err_msg)
			);
		}
		return array(
			'success' => true,
			'msg' => 'Ressource erfolgreich ausgepackt.'
		);
	}

	function unpack_unzip_filter_stelle_extent() {
		$result = $this->unpack_unzip();
		if (!$result['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Auspacken in Methode unpack_unzip_filter_stelle_extent: ' . $result['msg']
			);
		}
		$minx = $this->Stelle->MaxGeorefExt->minx;
		$miny = $this->Stelle->MaxGeorefExt->miny;
		$maxx = $this->Stelle->MaxGeorefExt->maxx;
		$maxy = $this->Stelle->MaxGeorefExt->maxy;
		$this->debug->show('Starte Funktion unpack_filter', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein relatives Verzeichnis zur Ablage der gefilterten Daten angegeben.'
			);
		}
		$dest_path = $this->get_full_dest_path();
		if (strpos($dest_path, '/var/www/data/') !== 0) {
			return array(
				'success' => false,
				'msg' => 'Das Verzeichnis ' . $dest_path . ' fängt nicht mit /var/www/data/ an.'
			);
		}
		if (!file_exists($dest_path)) {
			$this->debug->show('Lege Verzeichnis ' . $dest_path . ' an, weil es noch nicht existiert!', true);
			mkdir($dest_path, 0777, true);
		}

		$err_msg = array(); 
		$download_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('download_path');
		forEach(scandir($download_path) AS $entry) {
			if (is_file($download_path . $entry)) {
				$fp_dest = fopen($dest_path, $entry, "w");
				if (!$fp_dest) {
					$err_msg[] = 'Konnte gefilterte Datei ' . $entry . ' in Verzeichnis ' . $dest_path . ' nicht anlegen.';
				}
				$fp_src = fopen($download_path . $entry, "r");
				if (!$fp_src) {
					$err_msg[] = 'Konnte Datei ' . $download_path . $entry . ' nicht zum filtern öffnen.';
				}
				$i = 0;
				while (($line = fgets($fp_src)) !== false AND $i < 100) {
					$n = intval(substr($line, 5, 5)); // y
					$e = intval(substr($line, 11, 5)); // x

					// $np = explode('N', $s);
					// $ep = explode('E', $np[1]);
					// $n = $ep[0];
					// $epk = explode(',', $ep[1]);
					// $e = $epk[0];

					if ($minx <= $e AND $e <= $maxx AND $miny <= $n AND $n <= $maxy) { 
						fwrite($fp_dest, $line);
						$i++;
					}
				}
				fclose($fp_dest);
				fclose($fp_src);
			}
		};

		if (count($err_msg) > 0) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Filtern der Dateien aus dem Downloadverzeichnis ' . $download_path . ' in das Zielverzeichnis ' . $dest_path . ' für die Ressource ' . $this->get_id() . ' in Datei: ' . basename(__FILE__) . ' Zeile: ' . $line . ' Meldung: ' . implode(', ', $err_msg)
			);
		}

		return array(
			'success' => true,
			'msg' => 'Ressource erfolgreich refiltert'
		);
	}

	/**
	 * Funktion kopiert die im $download_path liegenden Dateien nach $dest_path
	 */
	function unpack_copy() {
		$this->debug->show('Starte Funktion unpack_copy', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein relatives Auspackverzeichnis angegeben.'
			);
		}
		$dest_path = $this->get_full_dest_path();
		if (strpos($dest_path, '/var/www/data/') !== 0) {
			return array(
				'success' => false,
				'msg' => 'Das Auspackverzeichnis ' . $dest_path . ' fängt nicht mit /var/www/data/ an.'
			);
		}
		if (!file_exists($dest_path)) {
			$this->debug->show('Lege Verzeichnis ' . $dest_path . ' an, weil es noch nicht existiert!', true);
			mkdir($dest_path, 0777, true);
		}

		$err_msg = array(); 
		$download_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('download_path');
		forEach(scandir($download_path) AS $entry) {
			if (is_file($download_path . $entry)) {
				if (!copy($download_path . $entry, $dest_path . $entry)) {
					$err_msg[] = 'Fehler beim Kopieren der Datei ' . $entry;
				}
				else {
					$this->debug->show('Datei ' . $download_path . $entry . ' nach ' . $dest_path . $entry . ' kopiert.', true);
				}
			}
		};

		if (count($err_msg) > 0) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Kopieren der Dateien aus dem Downloadverzeichnis ' . $download_path . ' in das Zielverzeichnis ' . $dest_path . ' für die Ressource ' . $this->get_id() . ' in Datei: ' . basename(__FILE__) . ' Zeile: ' . $line . ' Meldung: ' . implode(', ', $err_msg)
			);
		}

		return array(
			'success' => true,
			'msg' => 'Ressource erfolgreich kopiert'
		);
	}

	/**
	 * Funktion verschiebt die im $download_path liegenden Dateien nach $dest_path
	 */
	function unpack_move() {
		$this->debug->show('Starte Funktion unpack_move', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein relatives Zielverzeichnis angegeben.'
			);
		}
		$dest_path = $this->get_full_dest_path();
		if (strpos($dest_path, '/var/www/data/') !== 0) {
			return array(
				'success' => false,
				'msg' => 'Das Zielverzeichnis ' . $dest_path . ' fängt nicht mit /var/www/data/ an.'
			);
		}
		if (!file_exists($dest_path)) {
			$this->debug->show('Lege Zielverzeichnis ' . $dest_path . ' an, weil es noch nicht existiert!', true);
			mkdir($dest_path, 0777, true);
		}

		$err_msg = array(); 
		$download_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('download_path');
		forEach(scandir($download_path) AS $entry) {
			if (is_file($download_path . $entry)) {
				if (!rename($download_path . $entry, $dest_path . $entry)) {
					$err_msg[] = 'Fehler beim Verschieben der Datei ' . $entry;
				}
				else {
					$this->debug->show('Datei ' . $download_path . $entry . ' nach ' . $dest_path . $entry . ' verschoben.', true);
				}
			}
		};

		if (count($err_msg) > 0) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Verschieben der Dateien aus dem Downloadverzeichnis ' . $download_path . ' in das Zielverzeichnis ' . $dest_path . ' für die Ressource ' . $this->get_id() . ' in Datei: ' . basename(__FILE__) . ' Zeile: ' . $line . ' Meldung: ' . implode(', ', $err_msg)
			);
		}

		return array(
			'success' => true,
			'msg' => 'Dateien der Ressource erfolgreich verschoben'
		);
	}

	/**
	 * Die Methode prüft nur ob es das Verzeichnis zum manuellen Kopieren von Dateien gibt.
	 * Die Daten die da rein sollen müssen vom Admin selbst dort hinkopiert werden.
	 * Diese Methode gibt es um festlegen zu können wo sich die manuell hochgeladenen Dateien
	 * befinden sollen. Welche Dateien dort liegen sollen und ob sie vorhanden sind
	 * wird in der Importmethode abgefragt und geprüft.
	 */
	function unpack_manual_copy() {
		$this->debug->show('Starte Funktion manual_copy', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Das Zielverzeichnis zum manuellen Kopieren fehlt.'
			);
		}
		$dest_path = $this->get_full_dest_path();
		if (strpos($dest_path, '/var/www/data/') !== 0) {
			return array(
				'success' => false,
				'msg' => 'Das Zielverzeichnis ' . $dest_path . ' fängt nicht mit /var/www/data/ an.'
			);
		}
		if (!file_exists($dest_path)) {
			$this->debug->show('Lege Zielverzeichnis ' . $dest_path . ' an, weil es noch nicht existiert!', true);
			mkdir($dest_path, 0777, true);
		}

		$msg = 'Zielverzeichnis zum manuellen Kopieren vorhanden.';
		$this->debug->show($msg, true);

		return array(
			'success' => true,
			'msg' => $msg
		);
	}

	/**
	 * Die Methode prüft nur ob es das angegebene Import-Verzeichnis Dateien gibt.
	 * Die Daten die da rein sollen müssen vom Admin selbst dort hinkopiert oder geladen worden sein.
	 * Diese Methode gibt es um festlegen zu können wo sich die hochgeladenen Dateien
	 * befinden. Welche Dateien dort liegen und ob sie vorhanden sind
	 * wird in der Importmethode abgefragt und geprüft.
	 */
	function unpack_no_copy() {
		$this->debug->show('Starte Funktion manual_copy', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Das Zielverzeichnis zum manuellen Kopieren fehlt.'
			);
		}
		$dest_path = $this->get_full_dest_path();
		if (strpos($dest_path, '/var/www/data/') !== 0) {
			return array(
				'success' => false,
				'msg' => 'Das Zielverzeichnis ' . $dest_path . ' fängt nicht mit /var/www/data/ an.'
			);
		}
		if (!file_exists($dest_path)) {
			$this->debug->show('Lege Zielverzeichnis ' . $dest_path . ' an, weil es noch nicht existiert!', true);
			mkdir($dest_path, 0777, true);
		}

		$msg = 'Zielverzeichnis zum manuellen Kopieren vorhanden.';
		$this->debug->show($msg, true);

		return array(
			'success' => true,
			'msg' => $msg
		);
	}

	##################
	# Import methods #
	##################
	function import() {
		if ($this->get('import_method') != '') {
			$method_name = 'import_' . $this->get('import_method');
			if (!method_exists($this, $method_name)) {
				return array(
					'success' => false,
					'msg' => 'Die Funktion ' . $method_name . ' zum importieren der Ressource existiert nicht.'
				);
			}
			$this->update_status(6);
			$result = $this->${method_name}();
			if (!$result['success']) {
				$this->update_status(-1);
				return $result;
			}

			$this->update_status(7);
			return $result;
		}
		else {
			return array(
				'success' => true,
				'msg' => 'Keine Importmethode angegeben.'
			);
		}
	}

	/**
	 * Import shape with ogr2ogr to Postgres
	 */
	function import_ogr2ogr_shape() {
		$this->debug->show('Starte Funktion import_org2ogr_shape', true);
		if ($this->get('import_table') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
			);
		}

		$dest_path = $this->get_full_dest_path();

		if ($this->get('import_layer') != '') {
			// shape file is set explicit
			if (file_exists($dest_path . $this->get('import_layer') . '.shp')) {
				$shp_ext = 'shp';
			}
			elseif (file_exists($dest_path . $this->get('import_layer') . '.SHP')) {
				$shp_ext = 'SHP';
			}
			else {
				return array(
					'success' => false,
					'msg' => 'Die Shape-Datei mit dem Namen ' . $this->get('import_layer') . ' existiert nicht im Verzeichnis ' . $dest_dir
				);
			}
			$shp_file = $this->get('import_layer') . '.' . $shp_ext;
		}
		else {
			// find the shape file name in dest_path
			$files = array_filter(
				scandir($dest_path),
				function($entry) use ($dest_path) {
					return is_file($dest_path . $entry);
				}
			);

			$this->debug->show('Dateien im Verzeichnis:<br>' . implode('<br>', $files), true);
			$result = required_shape_files_exists($files);
			if (!$result['success']) { return $result; }
			$shp_file = '';
			foreach($files AS $file) {
				$info = pathinfo($dest_path . $file);
				if (strtolower($info['extension']) == 'shp') {
					$shp_file = $info['basename'];
				}
			};
		}

		$this->debug->show('Der Name des Shapes lautet: ' . $shp_file, true);

		$result = $this->gui->data_import_export->ogr2ogr_import($this->get('import_schema'), $this->get('import_table'), $this->get('import_epsg'), $dest_path . $shp_file, $this->database, '', NULL, '-overwrite', 'UTF-8', true);
		if ($result != '') {
			return array(
				'success' => false,
				'msg' => $result
			);
		}
		return array(
			'success' => true,
			'msg' => 'Shape-Datei ' . $shp_file . ' erfolgreich eingelesen.'
		);
	}

	function import_ogr2ogr_gml() {
		$this->debug->show('Starte Funktion import_org2ogr_gml', true);
		if ($this->get('import_table') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
			);
		}

		// get the files from dest_path
		$dest_path = $this->get_full_dest_path();
		$gml_files = array();
		if ($this->get('import_file')) {
			$gml_files[] = $this->get('import_file');
		}
		else {
			$entries = scandir($dest_path);
			$i = 1;
			foreach($entries AS $entry) {
				if (is_file($dest_path . $entry)) {
					$path_parts = pathinfo($entry);
					if (strtolower($path_parts['extension']) == 'gml') {
						$gml_files[] = $entry;
					}
					if (strtolower($path_parts['extension']) == 'xml') {
						$gml_files[] = $path_parts['filename'] . '.gml';
						rename($dest_path . $entry, $dest_path . $path_parts['filename'] . '.gml');
					}
					// else {
					//   $gml_files[] = $this->get('import_layer') . '_' . $i . '.gml';
					//   rename($dest_path . $entry, $dest_path . $this->get('import_layer') . '_' . $i . '.gml');
					//   $i++;
					// }
				}
			}
		}

		$err_msg = array();
		$first = true;
		foreach ($gml_files as $gml_file) {
			echo '<br>Importiere Datei: ' . $dest_path . $gml_file;
			// $result = $this->gui->data_import_export->ogr2ogr_import($this->get('import_schema'), $this->get('import_table'), $this->get('import_epsg'), $dest_path . $gml_file, $this->database, $this->get('import_layer'), NULL, ($first ? '-overwrite' : '-append'), 'UTF-8', true);
			$result = $this->gui->data_import_export->ogr2ogr_import(
				$this->get('import_schema'),
				$this->get('import_table'),
				$this->get('import_epsg'),
				$dest_path . $gml_file,
				$this->database,
				$this->get('import_layer'),
				NULL,
				($first ? '-overwrite' : '-append'),
				'UTF-8',
				false
			);
			$first = false;
			if ($result != '') {
				$err_msg[] = $result;
			}
		}
		if (count($err_msg) > 0) {
			return array(
				'success' => false,
				'msg' => implode(', ', $err_msg)
			);
		}
		return array(
			'success' => true,
			'msg' => 'Anzahl erfolgreich gelesener GML-Dateien: ' . count($gml_files) . '.'
		);
	}

	/**
	 * Import raster files to Postgres
	 */
	function import_raster2pgsql() {

	}

	function import_csv_by_header() {
		$this->debug->show('Starte Funktion import_csv_by_header', true);
		if ($this->get('import_table') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
			);
		}

		// get the files from dest_path
		$dest_path = $this->get_full_dest_path();
		$csv_file = $this->get('import_layer') . '.csv';

		if (!is_file($dest_path . $csv_file)) {
			return array(
				'success' => false,
				'msg' => 'Konnte Datei ' . $dest_path . $csv_file . ' nicht finden!'
			);
		}

		echo '<br>Importiere Datei: ' . $dest_path . $csv_file;
		$err_msg = array();
		// Lege Tabelle an wenn es sie noch nicht gibt
		$importer = new data_import_export();
		$encoding = $importer->getEncoding($dest_path . $csv_file);

		$csv_fp = fopen($dest_path . $csv_file, "r");
		if (!$csv_fp) {
			$err_msg[] = 'Konnte Datei ' . $dest_path . $csv_filey . ' nicht zum lesen öffnen.';
		}
		$i = 0;

		while (($line = fgets($csv_fp)) !== false AND trim($line, $delimiter ."\n\r") != '' AND $i < 100) {
			$first_line = $line;
			break;
			$i++; // only to prevent from endless loop
		}
		fclose($csv_fp);

		$this->debug->show('Analysiere Kopfzeile: ' . $first_line, true);
		$delimiter = detect_delimiter($first_line);
		$this->debug->show('Ermittelter Delimiter: ' . $delimiter, true);

		$columns = array_map(
			function($column) use ($encoding) { 
				if ($encoding != 'UTF-8') {
					$column = utf8_encode($column);
				}
				return strtolower(sonderzeichen_umwandeln($column));
			},
			explode($delimiter, $first_line)
		);

		$this->debug->show('Ermittelte Spalten: ' . implode(', ', $columns), true);

		$sql = "
			DROP TABLE IF EXISTS " . $this->get('import_schema') . "." . $this->get('import_table') . ";
			CREATE TABLE IF NOT EXISTS " . $this->get('import_schema') . "." . $this->get('import_table') . " (
				" . implode(",
				", array_map(
							function($column) {
								return $column . " character varying";
							},
							$columns
						)
				) . "
			)
		";
		$this->debug->show('SQL zum anlegen der Tabelle: ' . $sql, true);
		$query = $this->execSQL($sql);
		if (!$query) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Anlegen der Tabelle: ' . pg_last_error($this->database->dbConn)
			);
		}

		$minx = $this->Stelle->MaxGeorefExt->minx;
		$miny = $this->Stelle->MaxGeorefExt->miny;
		$maxx = $this->Stelle->MaxGeorefExt->maxx;
		$maxy = $this->Stelle->MaxGeorefExt->maxy;

		// Lese Daten in die Tabelle ein
		$where = ($this->get('import_filter') ? 'WHERE ' . $this->get('import_filter') : '');
		$sql = "
			COPY " . $this->get('import_schema') . "." . $this->get('import_table') . "(" . implode(', ', $columns) . ")
			FROM '" . $dest_path . $csv_file . "'
			WITH (
				FORMAT CSV,
				DELIMITER '" . $delimiter . "',
				HEADER true,
				ENCODING '" . $encoding . "'
			)
			" . $where . "
		";
		$this->debug->show('SQL zum Einlesen der CSV-Daten: ' . $sql, true);
		$query = $this->execSQL($sql);
		if (!$query) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Einlesen der CSV-Datei: ' . $csv_file. ' ' . pg_last_error($this->database->dbConn)
			);
		}
		return array(
			'success' => true,
			'msg' => 'Anzahl erfolgreich gelesener CSV-Dateien: ' . count($csv_files) . '.'
		);
	}

	function import_gml_dictionary() {
		$this->debug->show('Starte Funktion import_gml_dictionary', true);
		if ($this->get('import_table') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
			);
		}

		// get the files from dest_path
		$dest_path = $this->get_full_dest_path();
		$gml_files = array();
		$entries = scandir($dest_path);
		foreach ($entries AS $entry) {
			if (is_file($dest_path . $entry)) {
				$path_parts = pathinfo($entry);
				if (strtolower($path_parts['extension']) == 'gml') {
					$gml_files[] = $entry;
				}
				if (strtolower($path_parts['extension']) == 'xml') {
					$gml_files[] = $path_parts['filename'] . '.gml';
					rename($dest_path . $entry, $dest_path . $path_parts['filename'] . '.gml');
				}
			}
		}

		if (count($gml_files) == 0) {
			return array(
				'success' => false,
				'msg' => 'Es wurde keine gml-Datei im Verzeichnis: ' . $dest_path . ' gefunden.'
			);
		}

		$result = $this->create_gml_dictionary_table($this->get('import_schema'), $this->get('import_table'), true);
		if (!$result['success']) {
			return $result;
		}

		$err_msg = array();
		$gml_file = $gml_files[0]; // nur die erste gml-Datei einlesen.
		$this->debug->show('Importiere Datei: ' . $dest_path . $gml_file, true);

		// XML-Datei laden
		$xml = simplexml_load_file($dest_path . $gml_file);

		if ($xml === false) {
			$err_msg[] = $dest_path . $gml_file . ' konnte nicht geladen werden.';
		}

		// Namespace definieren
		$namespaces = $xml->getNamespaces(true);
		$gml = $xml->children($namespaces['gml']);

		// Durchlaufe alle Definitionen im Dictionary
		foreach ($gml->dictionaryEntry as $entry) {
			$definition = $entry->children($namespaces['gml'])->Definition;
			
			$gml_id = (string)$definition->attributes('gml', true)->id;
			$description = (string)$definition->description;

			$name_elements = $definition->name;

			// Annahme: erster gml:name enthält den codeSpace, der zweite den eigentlichen Namen
			$code_space = (string)$name_elements[0]->attributes()->codeSpace;
			$code_value = (string)$name_elements[0];
			$name = (string)$name_elements[1];

			// SQL-Abfrage vorbereiten und ausführen
			$result = pg_query_params(
				$this->database->dbConn,
				"
					INSERT INTO " . $this->get('import_schema') . "." . $this->get('import_table') . " (gml_id, description, code_space, code_value, name) 
					VALUES ($1, $2, $3, $4, $5)
				",
				array($gml_id, $description, $code_space, $code_value, $name)
			);

			if (!$result) {
				$err_msg[] = "Fehler beim Einfügen der Daten: " . pg_last_error($this->database->dbConn);
			}
		}

		if (count($err_msg) > 0) {
			return array(
				'success' => false,
				'msg' => implode(', ', $err_msg)
			);
		}
		$this->debug->show("Import des GML-Dictionary erfolgreich abgeschlossen.", true);
		return array(
			'success' => true,
			'msg' => 'Anzahl erfolgreich gelesener GML-Dictionaries: ' . count($gml_files) . '.'
		);
	}

	#####################
	# Transform methods #
	#####################
	function transform() {
		if ($this->get('transform_method') != '') {
			$method_name = 'transform_' . $this->get('transform_method');
			if (!method_exists($this, $method_name)) {
				return array(
					'success' => false,
					'msg' => 'Die Funktion ' . $method_name . ' zum transformieren der Ressource existiert nicht.'
				);
			}

			$this->update_status(8);
			$result = $this->${method_name}();
			if (!$result['success']) {
				$this->update_status(-1);
				return $result;
			}
			$this->update_status(9);
			return $result;
		}
		else {
			return array(
				'success' => true,
				'msg' => 'Keine Transformationsmethode definiert.'
			);
		}
	}

	function transform_exec_sql() {
		$sql = $this->get('transform_command');
		$this->debug->show("Transformiere Ressource mit SQL: " . $sql, true);
		$query = $this->execSQL($sql);
		if (!$query) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Ausführen der Transformation: ' . pg_last_error($this->database->dbConn)
			);
		}
		return array(
			'success' => true,
			'msg' => 'Transformationsbefehl erfolgreich ausgeführt.'
		);
	}

	function transform_gdaltindex() {
		$gdaltindex_params = $this->get('transform_command');
		$gdaltindex_comand = 'gdaltindex ' . $gdaltindex_params;
		$this->debug->show("Erzeuge gdaltindex mit Befehl: " . $gdaltindex_command, true);
		exec($gdaltindex_command, $output, $return_var);
		if (count($output) > 0) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Ausführen des Programms gdaltindex: ' . implode(', ', $output)
			);
		}
		return array(
			'success' => true,
			'msg' => 'gdaltindex erfolgreich ausgeführt.'
		);
	}

	function transform_replace_from_import() {
		$sql = "

		";
	}

	/**
	 * Overwrite if exists from import
	 */
	function replace_from_import() {
		// ToDo: implement on demand
		return array(
			'success' => true,
			'msg' => 'Transformation in den Zieldatensatz erfolgreich beendet.'
		);
	}

	function waermebedarf() {
		// ToDo: implement on demand

	}

	function create_gml_dictionary_table($schema_name, $table_name, $drop = false) {
		$sql = 
			(drop ? "DROP TABLE IF EXISTS " . $schema_name . '.' . $table_name . ";" : "") . "
			CREATE TABLE IF NOT EXISTS " . $schema_name . '.' . $table_name . " (
				id SERIAL PRIMARY KEY,
				gml_id TEXT,
				description TEXT,
				code_space TEXT,
				code_value TEXT,
				name TEXT
			);
		";
		$query = $this->execSQL($sql);
		if ($query) {
			return array(
				'success' => true,
				'msg' => 'Tabelle ' . $schema_name . '.' . $table_name . ' angelegt.'
			);
		}
		else {
			return array(
				'success' => false,
				'msg' => 'Fehler bei anlegen der Tabelle: ' .  $schema_name . '.' . $table_name . ' ' . pg_last_error($this->database->dbConn)
			);
		}
	}

}

?>
