<?php
#############################
# Klasse Ressource #
#############################
include_once(CLASSPATH . 'PgObject.php');	
include_once(PLUGINS . 'metadata/model/SubRessource.php');
include_once(PLUGINS . 'metadata/model/Lineage.php');

class Ressource extends PgObject {

	static $schema = 'metadata';
	static $tableName = 'ressources';
	public $has_subressources = false;
	public $has_ressource_ranges = false;
	public $sub_ressources = array();
	public $layer_id = METADATA_RESSOURCES_LAYER_ID;
	public $unlogged = true;

	public $outdated_expression = "
		r.auto_update AND
		(
			r.last_updated_at IS NULL OR 
			(
				(
					r.next_update_at IS NOT NULL AND
					r.next_update_at > last_updated_at AND
					r.next_update_at < now()
				) OR
				(
					r.update_interval IS NOT NULL AND
					DATE(r.last_updated_at) + r.update_time + r.update_interval < now()
				)
			)
		)
	";

	function __construct($gui) {
		$gui->debug->show('Create new Object ressource in table ' . Ressource::$schema . '.' . Ressource::$tableName, $this->show);
		parent::__construct($gui, Ressource::$schema, Ressource::$tableName);
		include_once(CLASSPATH . 'data_import_export.php');
		$this->gui->data_import_export = new data_import_export('gid');
		// $this->typen = array(
		// 	'Punkte',
		// 	'Linien',
		// 	'Flächen'
		// );
	}

	/**
	 * Function append document files to $zip_file
	 * @param Ressource $ressource
	 * @param String $zip_file, The name of the ZIP-File on which the documents to be append.
	 */
	function append_docs($zip_file) {
		$documents = arrStrToArr($this->get('documents'), ',', '{}');
		foreach ($documents AS $document) {
			$doc_file = explode('&original_name=' , $document)[0];
			if (file_exists($doc_file)) {
				// Put the document file into the $export_file.zip
				$command = ZIP_PATH . ' -j ' . $zip_file . ' ' . $doc_file;
				exec($command);
			}
		}
	}

	public static	function find($gui, $where, $order = '', $limit = '') {
		$ressource = new Ressource($gui);
		$select = "r.ampel_id, r.gruppe_id, r.bezeichnung, r.hinweise_auf, r.beschreibung, r.datasource_id, r.ansprechperson, r.format_id, r.aktualitaet, r.url, r.datenguete_id, r.quelle, r.github, r.download_url, r.dest_path, r.download_method, r.id, r.download_path, r.last_updated_at, r.auto_update, r.update_interval, r.import_epsg, r.error_msg, r.relevanz, r.digital, r.flaechendeckend, r.bemerkung_prioritaet, r.inquiries_required, r.inquiries, r.inquiries_responses, r.inquiries_responsible, r.inquiries_to, r.check_required, r.created_at, r.created_from, r.updated_at, r.updated_from, r.use_for_datapackage, r.transform_command, r.unpack_method, r.import_method, r.transform_method, r.status_id, r.von_eneka, r.documents, r.import_layer, r.import_schema, r.import_table, r.layer_id, r.update_time, r.import_filter, r.import_file, r.metadata_document, r.gebietseinheit_id, r.next_update_at,
		DATE(r.last_updated_at) + r.update_time + r.update_interval AS next_interval_date,
		s.status,
		(r.status_id IS NULL OR r.status_id = 0) AND
		" . $ressource->outdated_expression . " AS outdated";
		$ressource->show = false;
		return $ressource->find_where($where, $order, $select, $limit, 'metadata.ressources r JOIN metadata.update_status AS s ON r.status_id = s.id');
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

	/**
	 * Function query outdated ressources.
	 * The amount of results is limited by $limit.
	 * If $ressource_id is set with a single id or an array of ids, it query only if these ressources are outdated.
	 * A Ressource is outdated if
	 *   - auto_update is true
	 *   - status_id IS NULL, = 0 or > -1 wenn $force is true
	 *   - von_eneka or use_for_datapackage is true
	 *   - last_updated_at is null or
	 *     - next_updated_at is not null and between last_updated_at and now() or
	 *     - update_interval is not null and date of last_updated_at + update_time is < now
	 * @param integer $ressource_id
	 * @param integer $limit If limit is given only the amount of ressources will be replied
	 * @return Ressource[] An Array of Ressources that are outdated
	 * WHERE
	 * 	(von_eneka
	 * 		OR use_for_datapackage)
	 * 	AND (status_id IS NULL
	 * 		OR status_id = 0)
	 * 	AND auto_update
	 * 	AND ( last_updated_at IS NULL
	 * 		OR ( ( next_update_at IS NOT NULL
	 * 			AND next_update_at > last_updated_at
	 * 			AND next_update_at < now() )
	 * 		OR ( update_interval IS NOT NULL
	 * 			AND DATE(last_updated_at) + update_time + update_interval < now() ) ) )
	*/
	public static function find_outdated($gui, $ressource_id = NULL, $limit = NULL, $force = false) {
		$ressources = array();
		$ressource = new Ressource($gui);
		if ($force) {
			// all with status > -1 find to be outdated
			$status_condition = "> -1";
		}
		else {
			// only ressouces with state Uptodate will be find as outdated
			$status_condition = "= 0";
		}
		$ressources = $ressource->find(
			$gui,
			"
				(r.von_eneka OR r.use_for_datapackage) AND
				(r.status_id IS NULL OR r.status_id " . $status_condition . ") AND
			" . $ressource->outdated_expression .
			($ressource_id ? " AND r.id " . (is_array($ressource_id) ? "IN (" . implode(', ', $ressource_id) . ")" : "= " . $ressource_id) : ""),
			"r.last_updated_at",
			$limit
		);
		// $ressources = $ressource->find_where(
		// 	"
		// 		(r.von_eneka OR r.use_for_datapackage) AND
		// 		(r.status_id IS NULL OR r.status_id " . $status_condition . ") AND
		// 		r.auto_update AND
		// 		(
		// 			r.last_updated_at IS NULL OR
		// 			(
		// 				(
		// 					r.next_update_at IS NOT NULL AND
		// 					r.next_update_at > r.last_updated_at AND
		// 					r.next_update_at < now()
		// 				) OR
		// 				(
		// 					r.update_interval IS NOT NULL AND
		// 					DATE(r.last_updated_at) + r.update_time + r.update_interval < now()
		// 				)
		// 			)
		// 		)
		// 	" .
		// 	($ressource_id ? " AND r.id " . (is_array($ressource_id) ? "IN (" . implode(', ', $ressource_id) . ")" : "= " . $ressource_id) : ""),
		// 	"r.last_updated_at",
		// 	"r.*, s.status",
		// 	$limit,
		// 	'metadata.ressources r JOIN metadata.update_status s ON r.status_id = s.id'
		// );
		return $ressources;
	}

	function get_next_update_at() {
		$now = new DateTime('now');
		if ($this->get('status_id') == -1 OR !$this->get('auto_update')) {
			return null;
		}
		if ($this->get('last_updated_at') == '') {
			return date("d.m.Y H:i:s", $now);
		}
		$next_update_at = new DateTime($this->get('next_update_at'));
		$last_updated_at = new DateTime($this->get('last_updated_at'));
		if (
			$this->get('next_update_at') != '' AND
			$next_update_at > $last_updated_at
		) {
			return $this->get('next_update_at');
		}
		$last_update_at = new DateTime($last_updated_at->format('d.m.Y'));
		if ($this->get('update_time') != '') {
			list($hours,$minutes,$seconds) = explode(':', $this->get('update_time'));
			$last_update_at = $last_update_at->add(new DateInterval('PT' . $hours . 'H' . $minutes . 'M'));
		}
		if ($this->get('update_interval') != '') {
			$next_update_at = $this->get('next_interval_date');
			return $next_update_at;
		}
	}
	
	function get_sources() {
		return Lineage::find_sources($this->gui, $this->get('id'));
	}

	function get_source_ids() {
		return array_map(
			function($source) {
				return $source->get_id();
			},
			$this->get_sources()
		);
	}

	function get_targets() {
		return Lineage::find_targets($this->gui, $this->get('id'));
	}

	function get_subressources() {
		$subresource = new SubRessource($this->gui);
		$subressources = $subresource->find_by_ressource_id($this->get_id());
		$this->has_subressources = count($subressources) > 0;
		$this->subressources = $subressources;
		return $subressources;
	}

	function get_full_path($path) {
		return rtrim(METADATA_DATA_PATH . 'ressourcen/' . $path, '/') .'/';
	}

	function destroy() {
		#echo "\ndestroy Dataset: " . $this->get($this->identifier);
		$this->debug->show('destroy dataset ' . $this->get('datenquelle'), $this->show);
		$this->delete();
	}

	/**
	 * Function find outdated ressource which sources are up to date allready and run the update process
	 * for 1 to 10 of it, if less than 10 processes running already.
	 * A ressource is outdated if
	 * status is not set or 0 and
	 * auto_update is set to true and (
	 *   last_update is not defined or (
	 *     last_update is defined and
	 *     update_internval is defined and
	 *     DATE(last_updated_at) + update_time + update_interval is in the past
	 *   )
	 * )
	 * if $ressource_id is given force to update it also if it is not outdated!
	 * @param GUI $gui
	 * @param int $ressource_id
	 * @param string $method_only
	 * @param boolean $force Update not only ressources with update_state_id = 0, but also with > 0 and < 11
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
	public static function update_outdated($gui, $ressource_id = null, $method_only = '', $only_missing = false, $force = false) {
		$gui->debug->show('Starte Funktion update_outdated' . ($ressource_id != null ? ' mit Ressource id: ' . $ressource_id : ' ohne Ressource id'), true);
		$msg = '';
		$ressource = new Ressource($gui);
		if ($ressource_id != null) {
			$ressources = $ressource->find_where('id = ' . $ressource_id);
			$num_updateable = 1;
		}
		else {
			$results = $ressource->getSQLResults("
				SELECT count(id) AS num_running FROM metadata.ressources WHERE use_for_datapackage AND status_id > 0 AND status_id < 11;
			");
			$num_updateable = 10 - $results[0]['num_running'];
			if ($results[0]['num_running'] < 10) {
				$gui->debug->show('Es laufen nur ' . $results[0]['num_running'] . ' Updates.', true);
				$ressources = Ressource::find_outdated($gui, NULL, '', $force); // liefert nur die ersten 1 - 10 gefundenen zurück
			}
			else {
				$gui->debug->show('Abbruch weil bereits 10 Updates laufen.', true);
				return array(
					'success' => true,
					'msg' => 'Es laufen bereits 10 Updates.'
				);
			}
		}
		if (count($ressources) > 0) {
			$gui->debug->show('Anzahl gefundener Ressourcen: ' . count($ressources), true);
			$num_updated = 0;
			foreach ($ressources AS $ressource) {
				// A ressource shall only be updated when all its source ressources are uptodate yet.
				// Test it with a stack of pending ressources.
				if ($ressource->sources_uptodate()) {
					$gui->debug->show('Update outdated ressource: ' . $ressource->get('bezeichnung') . ' (' . $ressource->get_id() . ')' . ($method_only != '' ? ' method_only: ' . $method_only : ''), true);
					if ($gui->formvars['dry_run'] == 1) {
						echo "\nUpdate outdated ressource: " . $ressource->get('bezeichnung') . ' (' . $ressource->get_id() . ')' . ($method_only != '' ? ' method_only: ' . $method_only : '') . ($only_missing ? ' only_missing' : ' download all');
					}
					else {
						$result = $ressource->run_update($method_only, $only_missing);
						$msg .= 'Ressource ' . $ressource->get_id() . ' aktualisiert';
						$ressource->log($result, false);
					}
					$num_updated += 1;
				}
				else {
					$msg .= '<br>Quellen von Ressource ' . $ressource->get_id() . ' nicht aktuell.';
				}
				if ($num_updateable <= $num_updated) {
					$gui->debug->show('Genug geupdated.');
					break;
				}
			}
			return array(
				'success' => true,
				'msg' => $msg
			);
		}
		else {
			return array(
				'success' => true,
				'msg' => 'Nichts zu tun. ' . $msg
			);
		}
	}

	/**
	 * Function determine if the sources of the ressource are uptodate.
	 * It returns allways true if no sources exists.
	 */
	function sources_uptodate() {
		$source_ids = $this->get_source_ids();
		if (count($source_ids === 0)) {
			return true;
		}
		$ressources = $this->find_outdated(
			$this->gui,
			$source_ids,
			'',
			false
		);
		return (count($ressources) === 0);
	}

	function log($result, $show = false) {
		UpdateLog::write($this->gui, $this, $result, $show);
	}

	/**
	 * function calculate if a method should be executed
	 * If the $method is $method_only or $method_only is empty and current
	 * status_id is below the $status_threshold of the $method it returns true.
	 * @param String $method The method tested to be executed.
	 * @param String $method_only The name of the method that should be executed only
	 * @return Boolean If the method must be executed or not.
	 */
	function must_be_executed($method, $method_only) {
		$status_thresholds = array(
			'download' => 2,
			'unpack' => 4,
			'import' => 6,
			'transform' => 8
		);
		return
			(
				$method_only == '' AND
				$this->get('status_id') < $status_thresholds[$method]
			) OR
			$method_only == $method;
	}

	function run_update($method_only = '', $only_missing = false) {
		$this->debug->show('Update Ressource ' . $this->get_id(), true);
		if ($only_missing === false) {
			$only_missing = $this->get('only_missing');
		}

		$this->update_status(1);

		if ($this->must_be_executed('download', $method_only)) {
			$result = $this->download($only_missing);
			if (!$result['success']) { return $result; }
		}
		if ($this->must_be_executed('unpack', $method_only)) {
			$result = $this->unpack($only_missing);
			if (!$result['success']) { return $result; }
		}
		if ($this->must_be_executed('import', $method_only)) {
			$result = $this->import();
			if (!$result['success']) { return $result; }
		}
		if ($this->must_be_executed('transform', $method_only)) {
			$result = $this->transform();
			if (!$result['success']) { return $result; }

			// Update metadata document
			$this->gui->formvars['aktivesLayout'] = METADATA_PRINT_LAYOUT_ID;
			$this->gui->formvars['chosen_layer_id'] = METADATA_RESSOURCES_LAYER_ID;
			$this->gui->formvars['oid'] = $this->get_id();
			$this->gui->formvars['archivieren'] = 1;
			$this->gui->formvars['no_output'] = true;

			include_once(CLASSPATH . 'Layer.php');
			$layer = Layer::find_by_id($this->gui, METADATA_RESSOURCES_LAYER_ID);
			# Erzeuge die Checkboxvariablen an Hand der maintable des Layers und der mitgegebenen object_id
			# Für den Case archivieren = 1 werden nicht die checkbox_names mit ihrer Semikolon getrennten Struktur
			# verwendet damit man die URL in dynamicLink verwenden kann mit Semikolon für Linkname und no_new_window.
			$checkbox_name = 'check;' . $layer->get('maintable') . ';' . $layer->get('maintable') . ';' . $this->get_id();
			$this->gui->formvars['checkbox_names_' . METADATA_RESSOURCES_LAYER_ID] = $checkbox_name;
			$this->gui->formvars[$checkbox_name] = 'on';
			$result = $this->gui->generischer_sachdaten_druck_createPDF(
				NULL, // pdfobject
				NULL, // offsetx
				NULL, // offsety
				true, // output
				false // append
			);
			$this->gui->outputfile = basename($result['pdf_file']);
			$this->gui->pdf_archivieren(METADATA_RESSOURCES_LAYER_ID, $this->get_id(), $result['pdf_file']);
			$this->debug->show('Metadatendokument für Ressource ' . $this->get_id() . ' aktualisiert.', true);
		}

		if ($method_only == '') {
			$this->update_status(0, ' Datum der letzten Aktualisierung gesetzt.');
		}
		$last_updated_at = date("Y-m-d H:i:s");
		$this->update_status(0, '', $last_updated_at);
		$this->gui->debug->show('run_update ' . $this->get_id() . ' am ' . $last_updated_at . ' beendet.', true);
		return array(
			'success' => true,
			'msg' => $msg . '<br>Ressource ' . $this->get_id() . ' am ' . $last_updated_at . ' erfolgreich aktualisiert.'
		);
	}

	/**
	 * Set the $status_id of ressource and if switch to uptodate status
	 * set the last_updated_at timestamp too.
	 * If parameter $msg is not empty the message will be echoed. 
	 * @param Integer $status_id
	 * @param String (optional) $msg
	 */
	function update_status($status_id, $msg = '', $date = '') {
		// $this->debug->show('Set status_id: ' . $status_id, true);
		$attributes = array('status_id = ' . (string)$status_id);
		$last_updated_at = date('Y-m-d H:i:s', time());
		if ($msg != '') {
			$this->debug->show('Ressource ' . $this->get_id() . ' uptodate: ' . $last_updated_at, true);
		}
		if ($this->get('status_id') != 0 AND $status_id == 0) {
			$attributes[] = "last_updated_at = '" . $last_updated_at . "'";
		}
		$attributes = array();
		$attributes[] = "status_id = " . (string)$status_id;
		if ($date != '') {
			$attributes[] = "last_updated_at = '" . $date . "'";
		}
		$this->update_attr($attributes, true);
		// $this->show = true;
		// echo '<br>Update status_id: ' . $this->get('status_id') . ' auf ' . $status_id;
	}

	####################
	# Download methods #
	####################
	function download($only_missing = false) {
		// $this->debug->show('Starte Funktion download', true);
		if ($this->get('download_method') != '' AND $this->get('download_method') != 'upload') {
			$method_name = 'download_' . $this->get('download_method');
			if (!method_exists($this, $method_name)) {
				return array(
					'success' => false,
					'msg' => 'Die Funktion ' . $method_name . ' zum Download der Ressource existiert nicht.'
				);
			}

			$this->update_status(2);
			$this->only_missing = $only_missing;
			$result = $this->${method_name}();
			if (!$result['success']) { return $result; }

			$this->update_status(3);
			return $result;
		}
		return array(
			'success' => true,
			'msg' => 'Keine Downloadmethode ausgeführt.'
		);
	}
	/**
	 * Download dataset or its subsets to download_path
	 */
	function download_urls() {
		// $this->debug->show('Starte Funktion download_urls', true);
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
			$download_path = $this->get_full_path($this->get('download_path'));
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
			if ($this->only_missing) {
				array_map('unlink', glob($download_path . "/*"));
				$this->debug->show('Alle Dateien im Verzeichnis ' . $download_path . ' gelöscht.', true);
			}

			foreach ($download_urls AS $download_url) {
				if ($this->only_missing === false OR !file_exists($download_path . basename($download_url))) {
					$this->debug->show('Download from: ' . $download_url . ' to ' . $download_path, true);
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
		$url = $this->get('download_url');
		// ToDo: query first capabilites to check if epsg is available
		$epsg = ($this->get('import_epsg') ? $this->get('import_epsg') : '25832');
		$version = $this->get_wfs_version($url, '1.1.0');
		$params = array(
			'Service' => 'WFS',
			'Version' => $version,
			'Request' => 'GetFeature',
			'SRS' => 'urn:ogc:def:crs:EPSG::' . $epsg
		);
		$params['TypeName' . (strpos($version, '2.') !== false ? 's' : '')] = $this->get('import_layer');

		try {
			$this->debug->show('Download WFS onlineressource: ' . $url, true);
			if ($this->get('download_path') == '') {
				return array(
					'success' => false,
					'msg' => 'Es ist kein relatives Download-Verzeichnis angegeben.'
				);
			}
			$download_path = $this->get_full_path($this->get('download_path'));
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

			$epsg = ($this->get('import_epsg') ? $this->get('import_epsg') : '25832');
			$version = $this->get_wfs_version($url, '1.1.0');
			$params = array(
				'Service' => 'WFS',
				'Version' => $version,
				'Request' => 'GetFeature',
				'SRS' => 'urn:ogc:def:crs:EPSG::' . $epsg
			);

			include_once(CLASSPATH . 'wfs.php');
			$wfs = new wfs($url, $params['Version'], '', '', $this->get('import_epsg'));
			$wfs->parse_capabilities($url);
			// query featuretypes from capabilities and download all in separate gml-files.
			$featuretypes = $wfs->get_featuretypes();
			foreach ($featuretypes AS $featuretype) {
				// If download_typenames given, only download those and only if found in service capabilities,
				// else all from capabilities.
				if (
					$this->get('download_typenames') AND
					!in_array($featuretype['name'], array_map('trim', explode(',', $this->get('download_typenames'))))
				) {
					continue;
				}
				$params['TypeName' . (strpos($version, '2.') !== false ? 's' : '')] = $featuretype['name'];
				$download_url = $url . (strpos($url, '?') === false ? '?' : (in_array(substr($url, -1), array('?', '&')) ? '' : '&')) . http_build_query($params);
				$download_file = strtolower(sonderzeichen_umwandeln(str_replace(':', '_', umlaute_umwandeln($featuretype['name'])))) . '.gml';
				$this->debug->show('Download FeatureType: ' . $featuretype['name'] . ' (' . $featuretype['title'] . ') from<br>' . $download_url . '<br>in Datei: ' . $download_path . $download_file, true);
				#echo '<br>wget -O ' . $download_path . $download_file . ' "' . $download_url . '"';
				$result = copy($download_url, $download_path . $download_file);
				if (!$result) {
					return array(
						'success' => false,
						'msg' => '<p>Download in Datei: ' . $download_path . $download_file . ' fehlgeschlagen!<br>owner von ' . $download_path . ' ' . fileowner($download_path) . ':' . filegroup($download_path)
					);
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

	function download_parallel_from_file() {
		$this->debug->show('Starte Funktion download_parallel_from_file', true);
		try {
			if ($this->get('download_path') == '') {
				return array(
					'success' => false,
					'msg' => 'Es ist kein relatives Download-Verzeichnis angegeben.'
				);
			}

			$download_path = $this->get_full_path($this->get('download_path'));
			if (strpos($download_path, '/var/www/data/') !== 0) {
				return array(
					'success' => false,
					'msg' => 'Das Download-Verzeichnis ' . $download_path . ' fängt nicht mit ' . '/var/www/data/' . ' an.'
				);
			}

			$urls_file = $this->get_urls_file();
			if (!file_exists($urls_file)) {
				return array(
					'success' => false,
					'msg' => 'Die Datei ' . $urls_file . ' ist nicht vorhanden.'
				);
			}

			$parallel_download_script = WWWROOT . APPLVERSION . 'plugins/metadata/tools/download_parallel.sh';
			if (!file_exists($parallel_download_script)) {
				return array(
					'success' => false,
					'msg' => 'Das Script ' . $parallel_download_script . ' zum parallelen runterladen von den URLs aus der Datei ' . $urls_file . ' ist nicht vorhanden.'
				);
			}

			// Script aufrufen zum Download der Dateien in urls.txt
			$cmd = $parallel_download_script . ' ' . $urls_file . ' ' . $download_path . ' 10' . ($this->only_missing ? ' 1' : '');
			$this->debug->show('Download Dateien aus urls.txt mit Befehl: ' . $cmd, true);
			// // Befehl z.B. /var/www/apps/kvwmap/plugins/metadata/tools/download_parallel.sh /var/www/data/fdm/ressourcen/dgm/dgm1/NS/urls.txt /var/www/data/fdm/ressourcen/dgm/dgm1/NS/downloads/ 10 1
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
			if ($stderr != '') {
				$err_msg[] = 'Fehler bei parallelen Download der Dateien aus urls.txt für Ressource ' . $this->get_id() . ' in Datei: ' . basename(__FILE__) . ' Zeile: ' . $line . ' Rückgabewert: ' . $stderr;
			}
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim parallelen Download der Dateien aus urls.txt: ', $e->getMessage()
			);
		}

		return array(
			'success' => true,
			'msg' => 'Download parralel von Datei urls.txt erfolgreich beendet.'
		);
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

			$download_path = $this->get_full_path($this->get('download_path'));
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

	/**
	 * Download ressource from overpass-api
	 * need download_url, overpass_filter aus <import_layer>.overpass, targetfile aus <import_layer>.json
	 */
	function download_overpass() {
		// Download per curl --data-urlencode "data@query.txt" https://overpass-api.de/api/interpreter -o osm.json
		// Wird später umgewandelt nach geojson mit osmtogeojson stromnetz-mv.json > stromnetz-mv.geojson
		// Wird später importiert mit:
		// ogr2ogr \
		// 	-f "PostgreSQL" \
		// 	PG:"host=pgsql dbname=kvwmapsp user=kvwmap password=secret" \
		// 	stromnetz-mv.geojson \
		// 	-nln stromnetz_osm \
		// 	-nlt PROMOTE_TO_MULTI \
		// 	-lco GEOMETRY_NAME=geom \
		// 	-lco FID=gid \
		// 	-lco PRECISION=NO \
		// 	-lco SCHEMA=import \
		// 	-lco SPATIAL_INDEX=GIST \
		// 	-progress \
		// 	-overwrite \
		// 	-fieldTypeToString All
		try {
			$this->debug->show('Download from overpass-api:<br>' . $url, true);
			if ($this->get('download_path') == '') {
				return array(
					'success' => false,
					'msg' => 'Es ist kein relatives Download-Verzeichnis angegeben.'
				);
			}
			$download_path = $this->get_full_path($this->get('download_path'));
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

			// ToDo make filter in supermaerkte.overpass dynamic
			$cmd = 'curl --data-urlencode "data@' . $download_path . $this->get('import_layer') . '.overpass" ' . $this->get('download_url') . ' -o ' . $download_path . $this->get('import_layer') . '.json';
			$this->debug->show("Download der OSM-Daten in Datei " . $download_path . "osm.json mit folgendem Befehl: {$cmd}", true);
			exec($cmd, $output, $result_code);

			// ToDo Fehler abfangen
			$result = $output[0];
			$this->debug->show($result, true);

			return array(
				'success' => true,
				'msg' => 'Download von overpass-api erfolgreich beendet.'
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Download der OSM-Daten: ', $e->getMessage()
			);
		}
	}

	##################
	# Unpack methods #
	##################
	function unpack($only_missing = false) {
		// $this->debug->show('Starte Funktion unpack', true);
		if ($this->get('unpack_method') != '') {
			$method_name = 'unpack_' . $this->get('unpack_method');
			if (!method_exists($this, $method_name)) {
				return array(
					'success' => false,
					'msg' => 'Die Funktion ' . $method_name . ' zum Auspacken der Ressource existiert nicht.'
				);
			}
			$this->update_status(4);
			$this->only_missing = $only_missing;
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
		// $this->debug->show('Starte Funktion unpack_unzip', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein relatives Auspackverzeichnis angegeben.'
			);
		}
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		else {
			// Nicht löschen wenn nur fehlende Dateien ausgepackt werden sollen
			if ($this->only_missing === false) {
				array_map('unlink', glob("$dest_path/*.*"));
			}
		}

		if ($this->get('download_method') === 'upload') {
			// Daten wurden manuell hochgeladen und liegen in Datei die in upload_file angegeben ist.
			$this->debug->show('upload_file: ' . $this->get('upload_file'), true);
			$zip_files = array(document_info($this->get('upload_file'), 'path'));
			$this->debug->show('zipfiles: ' . implode(', ', $zip_files), true);
		}
		else {
			// Daten wurden runtergeladen und liegen in download_path
			$download_path = $this->get_full_path($this->get('download_path'));;
			$err_msg = array();
			$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type aka mimetype extension
			$zip_files = array_filter(glob($download_path . '*'), function($file) use ($finfo) { return finfo_file($finfo, $file) == 'application/zip'; });
		}
		$this->debug->show('Packe ZIP-Dateien aus:');
		foreach ($zip_files as $zip_file) {
			if (finfo_file($finfo, $zip_file) == 'application/zip') {
				if ($this->only_missing == false OR !file_exists($dest_path . str_replace('.zip', '.tif', basename($zip_file)))) {
					$result = unzip($zip_file, $dest_path);
					if ($result['success']) {
						$this->debug->show($zip_file . ' ausgepackt. ' . implode(', ', $result['files']), true);
					}
					else {
						$err_msg[] = "\nFehler beim Auspacken der Datei " . $zip_file . ' für Ressource ID: ' . $this->get_id() . ' Überprüfen Sie die Schreibrechte im Verzeichnis ' . $dest_path;
					}
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
	 * Function create a new destination folder if not exists or remove all files if exists,
	 * and than unzip specific or all files of a directory to a destination directory,
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
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		else {
			array_map('unlink', glob("$dest_path/*.*"));
		}
		$download_path = $this->get_full_path($this->get('download_path'));

		$err_msg = array();
		$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type aka mimetype extension
		$zip_files = array_filter(glob($download_path . '*'), function($file) use ($finfo) { return finfo_file($finfo, $file) == 'application/zip'; });
		$this->debug->show('Packe ZIP-Dateien aus: ' . implode(', ', $zip_files), true);
		foreach ($zip_files as $zip_file) {
			$cmd = 'unzip -j -o "' . $zip_file . '" -d ' . $dest_path;
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
			if ($stderr != '') {
				$err_msg[] = 'Fehler beim Auspacken der Datei ' . $zip_file . ' für Ressource ' . $this->get_id() . ' Fehler: ' . $stderr;
			}
		}
		finfo_close($finfo);

		$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type aka mimetype extension
		$zip_files = array_filter(glob($dest_path . '*'), function($file) use ($finfo) { return finfo_file($finfo, $file) == 'application/zip'; });
		$this->debug->show('Packe ' . count($zip_files) . 'ZIP-Dateien in Verzeichnis ' . $dest_path . ' aus.', true);
		foreach ($zip_files as $zip_file) {
			$cmd = 'unzip -j -o "' . $zip_file . '" -d ' . $dest_path;
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
			if ($stderr != '') {
				$err_msg[] = 'Fehler beim Auspacken der Datei ' . $zip_file . ' für Ressource ' . $this->get_id() . ' Fehler: ' . $stderr;
			}
			unlink($zip_file);
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
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$download_path = $this->get_full_path($this->get('download_path'));
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
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$download_path = $this->get_full_path($this->get('download_path'));
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
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$download_path = $this->get_full_path($this->get('download_path'));
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
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$dest_path = $this->get_full_path($this->get('dest_path'));
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

	function unpack_replace_xml_encoding() {
		$this->debug->show('Starte Funktion unpack_replace_xml_encoding', true);
		$encoding = $this->get('encoding') ?: 'UTF-8';
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein relatives Zielverzeichnis angegeben.'
			);
		}
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$download_path = $this->get_full_path($this->get('download_path'));
		forEach(scandir($download_path) AS $entry) {
			$gml_path = $download_path . $entry;
			if (is_file($gml_path) AND pathinfo($gml_path, PATHINFO_EXTENSION) == 'gml') {
				$this->debug->show('Ersetze encoding in Datei: ' . $download_path . $entry, true);

				// Read original content
				$gml_content = file_get_contents($gml_path);
				if ($gml_content === false) {
					$err_msg[] = 'Konte GML-Datei ' . $gml_path . ' nicht öffnen um encoding zu schreiben.';
				}

				$updated_gml_content = preg_replace(
					'/(encoding\s*=\s*")[^"]*(")/i',
					'${1}UTF-8${2}',
					$gml_content
				);

				$dest_gml_path = $dest_path . $entry;
				if (file_put_contents($dest_gml_path, $updated_gml_content) === false) {
					$err_msg[] = 'Konte GML-Datei ' . $dest_gml_path . ' mit geändertem Encoding ' . $encoding . ' nicht schreiben.';
				}
				// sed -i 's/encoding="ISO-8859-1"/encoding="UTF-8"/' filename.xml
				$this->debug->show('encoding durch ' . $encoding . ' in GML-Datei ' . $dest_gml_path . ' ersetzt.', true);
			}
		};

		if (count($err_msg) > 0) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Ersetzen des encodings der GML-Dateien für die Ressource ' . $this->get_id() . ' in Datei: ' . basename(__FILE__) . ' Zeile: ' . $line . ' Meldung: ' . implode(', ', $err_msg)
			);
		}

		return array(
			'success' => true,
			'msg' => 'XML encoding in GML-Dateien erfolgreich auf ' . $encoding . ' geändert.'
		);
	}

	/**
	 * Function konvert OSMJSON to GeoJSON
	 * Need sourcefile aus <import_layer>.json), targetfile $this->get('import_file')
	 */
	function unpack_osmtogeojson() {
		$this->debug->show('Starte Funktion unpack_osmtogeojson', true);
		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein relatives Auspackverzeichnis angegeben.'
			);
		}
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$download_path = $this->get_full_path($this->get('download_path'));

		$osm_file = $download_path . $this->get('import_layer') . '.json';
		if (!file_exists($osm_file)) {
			return array(
				'success' => false,
				'msg' => 'Die OSM-Datei ' . $osm_file . ' existiert nicht!'
			);
		}

		// ToDo: Den Befehl im gdal-Container ausführen
		$cmd = "osmtogeojson " . $osm_file . " > " . $dest_path . $this->get('import_file');
		$this->debug->show("Konvertierung der OSM-Daten mit folgendem Befehl<br>{$cmd}", true);
		exec($cmd, $output, $result_code);

		// ToDo Fehler abfangen
		$this->debug->show('Output: ' . print_r($output, true), true);

		return array(
			'success' => true,
			'msg' => 'Konvertieren der OSMJSON-Datei erfolgreich beendet.'
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

	function import_mastr() {
		$import_command = 'mastrImport';
		$this->debug->show("Importiere Markstammdaten mit Befehl: " . $import_command, true);
		$url = 'gdalcmdserver:8080/t/?tool=' . $import_command;
		// echo '<br>url:   ' . urldecode($url) . '<br><br>';
		// echo '<br>url:   ' . $url . '<br><br>';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		#echo '<br>output: ' . $output;
		$result = json_decode($output);
		$ret = $result->exitCode;
		if ($ret != 0 OR strpos($result->stderr, 'statement failed') !== false) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Einlesen des Mastr!' . $output
			);
		}
		else {
			return array(
				'success' => true,
				'msg' => 'Import Mastr erfolgreich ausgeführt.'
			);
		}
	}

	/**
	 * Import shape with ogr2ogr to Postgres
	 * Import only one if $this->get('import_layer') is defined else all shapes in $shape_path
	 */
	function import_ogr2ogr_shape() {
		$this->debug->show('Starte Funktion import_org2ogr_shape', true);
		$shape_path = $this->get_full_path($this->get('dest_path'));
		$imported_shape_files = array();

		if ($this->get('import_table') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
			);
		}

		$dest_path = $this->get_full_path($this->get('dest_path'));

		if ($this->get('import_layer') != '') {
			// shape file is set explicit. Import only this
			// load only one shape
			$files = glob($shape_path . $this->get('import_layer') . '.[sS][hH][pP]');
			if (count($files) == 0) {
				return array(
					'success' => false,
					'msg' => 'Die Shape-Datei mit dem Namen ' . $this->get('import_layer') . ' existiert nicht im Verzeichnis ' . $shape_path
				);
			}

			$shape_file = $files[0];
			$this->debug->show('Shape-Datei ' . $shape_file . ' gefunden.', true);
			$result = $this->shape_file_import($shape_file);
			if (!$result['success']) {
				return array(
					'success' => false,
					'msg' => $result['msg']
				);
			}
			else {
				$imported_shape_files[] = $shape_file;
			}
		}
		else {
			$this->debug->show('Importiere alle Shape-Dateien in Verzeichnis ' . $shape_path, true);
			// import all shape files in shape_path
			foreach (glob($shape_path . '*.[sS][hH][pP]') AS $shape_file) {
				$this->debug->show('Shape-Datei ' . $shape_file . ' gefunden.', true);
				$result = $this->shape_file_import($shape_file);
				if (!$result['success']) {
					return array(
						'success' => false,
						'msg' => $result['msg']
					);
				}
				else {
					$imported_shape_files[] = $shape_file;
				}
			};
		}
		return array(
			'success' => true,
			'msg' => 'Shape-Dateien geladen: ' . implode(', ', $imported_shape_files)
		);
	}

	/**
	 * Function imports data with ogr2ogr from an ESRI Geodatabase file to postgres
	 * Import with ogr2ogr command: ogr2ogr --config OGR_TRUNCATE YES -f PostgreSQL -dim XY -nlt CONVERT_TO_LINEAR -a_srs EPSG:5650 PG:"host=$host user=$user password=\'$password\' dbname=$dbname active_schema=$import_schema" $gdb_file
	 */
	function import_ogr2ogr_gdb() {
		if (!$this->get('dest_path')) {
			return array(
				'success' => false,
				'msg' => 'Es muss ein Zielverzeichnis angegeben sein!'
			);
		}
		$dest_path = METADATA_DATA_PATH . 'ressourcen/' . $this->get('dest_path');
		if (!file_exists($dest_path)) {
			return array(
				'success' => false,
				'msg' => 'Das angegebene Zielverzeichnis ' . $dest_path . ' existiert nicht!'
			);
		}
		if (!$this->get('import_file')) {
			return array(
				'success' => false,
				'msg' => 'Es muss eine Importdatei angegeben werden!'
			);
		}
		$gdb_file = rtrim($dest_path, '/') . '/' . $this->get('import_file');
		if (!file_exists($gdb_file)) {
			return array(
				'success' => false,
				'msg' => 'Importdatei ' . $gdb_file . ' nicht auf dem Server gefunden!'
			);
		}
		if (!$this->get('import_schema')) {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Importschema angegeben!'
			);
		}

		$this->debug->show('Starte mit Einlesen der GDB-Datei ' . $gdb_file, true);
		$result = $this->gui->data_import_export->ogr2ogr_import(
			$this->get('import_schema'),
			$this->get('import_table') != '' ? $this->get('import_table') : '',
			$this->get('import_epsg') != '' ? $this->get('import_epsg') : 25833,
			$gdb_file,
			$this->database,
			'',
			NULL,
			'--config OGR_TRUNCATE YES',
			'UTF-8',
			false,
			$this->unlogged
		);

		if ($result != 0) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Einlesen der GDB-Datei ' . $gdb_file
			);
		}

		return array(
			'success' => true,
			'msg' => 'GDB-Datei erfolgreich geladen!'
		);
	}

	function import_ogr2ogr_geojson() {
		$this->debug->show('Starte Funktion import_org2ogr_geojson', true);

		if ($this->get('dest_path') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für den Pfad der Importdatei angegeben!'
			);
		}

		$dest_path = $this->get_full_path($this->get('dest_path'));

		if ($this->get('import_file') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für die Importdatei angegeben!'
			);
		}

		$import_file = $dest_path . $this->get('import_file');
		if (!file_exists($import_file)) {
			return array(
				'success' => false,
				'msg' => 'Die Import-Datei ' . $import_file . ' existiert nicht!'
			);
		}

		if ($this->get('import_schema') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für das Importschema angegeben!'
			);
		}

		if ($this->get('import_table') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
			);
		}

		$this->debug->show('GeoJSON-Datei ' . $import_file . ' gefunden.', true);

		$result = $this->gui->data_import_export->ogr2ogr_import(
			$this->get('import_schema'),
			$this->get('import_table'),
			$this->get('import_epsg') != '' ? $this->get('import_epsg') : 25833,
			$import_file,
			$this->database,
			'',
			NULL,
			'-overwrite',
			'UTF-8',
			true,
			$this->unlogged
		);

		if (!$result['success']) {
			return array(
				'success' => false,
				'msg' => $result['msg']
			);
		}
		else {
			$this->debug->show('GeoJSON-Datei erfolgreich eingelesen!', true);
		}

		return array(
			'success' => true,
			'msg' => 'GeoJSON-Datei erfolgreich geladen.'
		);
	}

	function shape_file_import($shape_file) {
		$this->debug->show('Starte mit Einlesen der Shape-Datei ' . $shape_file);
		$pathinfo = pathinfo($shape_file);
		$result = required_shape_files_exists(glob($pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.*'));
		if (!$result['success']) { return $result; }
		$import_table = ($this->get('import_table') != '' ? $this->get('import_table') : strtolower(sonderzeichen_umwandeln($pathinfo['filename'])));
		$this->debug->show('Importiere in Tabelle ' . ($this->get('import_schema') != '' ? $this->get('import_schema') : $import_table) . '.' . $import_table, true);
		$result = $this->gui->data_import_export->ogr2ogr_import(
			$this->get('import_schema') != '' ? $this->get('import_schema') : $import_table,
			$import_table,
			$this->get('import_epsg') != '' ? $this->get('import_epsg') : 25833,
			$shape_file,
			$this->database,
			'',
			NULL,
			'-overwrite',
			'UTF-8',
			true,
			$this->unlogged
		);
		return array(
			'success' => ($result == 0),
			'msg' => ($result == 0 ? 'Shape-Datei ' . $shape_file . ' erfolgreich eingelesen' : 'Fehler beim Einlesen der Shape-Datei ' . $shape_file)
		);
	}

	function import_ogr2ogr_gml() {
		// $this->debug->show('Starte Funktion import_org2ogr_gml', true);
		if ($this->get('import_table') == '') {
			return array(
				'success' => false,
				'msg' => 'Es ist kein Name für die Importtabelle angegeben!'
			);
		}

		// get the files from dest_path
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$this->database->create_schema($this->get('import_schema'));
		$result = $this->drop_import_table($this->get('import_schema'), $this->get('import_table'));
		if (!$result['success']) {
			return array(
				'success' => false,
				'msg' => $result['msg']
			);
		}
		$this->debug->show('Importiere ' . count($gml_files) . ' GML-Dateien aus Verzeichnis: ' . $dest_path . ' in Tabelle: ' . $this->get('import_schema') . '.' . $this->get('import_table'), true);
		foreach ($gml_files as $gml_file) {
			// $this->debug->show('Importiere Datei: ' . $dest_path . $gml_file, true);
			// $result = $this->gui->data_import_export->ogr2ogr_import($this->get('import_schema'), $this->get('import_table'), $this->get('import_epsg'), $dest_path . $gml_file, $this->database, $this->get('import_layer'), NULL, ($first ? '-overwrite' : '-append'), 'UTF-8', true, $this->unlogged);
			$result = $this->gui->data_import_export->ogr2ogr_import(
				$this->get('import_schema'),
				$this->get('import_table'),
				$this->get('import_epsg'),
				$dest_path . $gml_file,
				$this->database,
				$this->get('import_layer'),
				NULL,
				($first ? '-overwrite' : '-append') . ' -nlt convert_to_linear', // . ' -nlt MULTIPOLYGON',
				'UTF-8',
				false,
				$this->unlogged,
				false,
				$this->get('force_nullable') == 't'
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
	 * Function drop the import table if it exists and all its dependencies.
	 * @param string $table_schema, Name of the database schema containing the table.
	 * @param string $table_name, Name of the table that shall be droped.
	 * @return array (
	 * 	boolean $success, True if table has been droped successfully.
	 *  string $msg, Error or success message.
	 * )
	 */
	function drop_import_table($table_schema, $table_name) {
		$this->debug->show('Lösche Tabelle: ' . $table_schema . "." . $table_name, true);
		$sql = "DROP TABLE IF EXISTS " . $table_schema . "." . $table_name . ' CASCADE';
		// $this->debug->show('SQL zum löschen der Tabelle: ' . $sql, true);
		$query = $this->execSQL($sql);
		if (!$query) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Löschen der Tabelle ' . $table_schema . '.' . $table_name . ' Meldung; ' . pg_last_error($this->database->dbConn)
			);
		}
		return array(
			'success' => true,
			'msg' => 'Tabelle ' . $table_schema . '.' . $table_name . ' erfolgreich gelöscht'
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
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$dest_path = $this->get_full_path($this->get('dest_path'));
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
		$this->debug->show("Transform Ressource " . $this->get_id() . " mit sql", true);
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
		$gdaltindex_command = 'gdaltindex ' . $gdaltindex_params;
		$this->debug->show("Erzeuge gdaltindex mit Befehl: " . $gdaltindex_command, true);
		// $descriptorspec = [
		// 	0 => ["pipe", "r"],  // stdin
		// 	1 => ["pipe", "w"],  // stdout
		// 	2 => ["pipe", "w"],  // stderr
		// ];
		// $process = proc_open($gdaltindex_command, $descriptorspec, $pipes, dirname(__FILE__), null);
		// $stdout = stream_get_contents($pipes[1]);
		// fclose($pipes[1]);
		// $stderr = stream_get_contents($pipes[2]);
		// fclose($pipes[2]);

		exec($gdaltindex_command, $output, $return_var);
		if ($return_var !== 0) {
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

	/**
	 * Die Funktion ließt die Version des WFS aus dem Capabilities-Dokument,
	 * welches von der $online_ressource gelesen werden kann oder die $default_version wenn
	 * keine Version im abgefragten Text gefunden werden kann.
	 * @param string $online_ressource
	 * @param string $default_version
	 * @return string $version
	 */
	function get_wfs_version($online_ressource, $default_version = '1.1.0') {
		if (strpos($online_ressource, '?') !== false) {
			$url_parts = explode('?', $online_ressource);
			$online_ressource = $url_parts[0];
		}
		$download_url = $online_ressource . '?SERVICE=WFS&REQUEST=GetCapabilities';
		$capabilities = file_get_contents($download_url);
		if (preg_match('/<([a-zA-Z0-9]+:)?ServiceTypeVersion>([\d\.]+)<\/\1?ServiceTypeVersion>/i', $capabilities, $matches)) {
			$version = $matches[2];
		} else {
			$version = $default_version;
		}
		return $version;
	}

	/**
	 * Liefert den Namen der Datei zurück, in der die Download-URL's liegen, die in
	 * downloads abgelegt werden.
	 * urls.txt wird von der Methode download_parallel_from_file im Pfad oberhalb von download_path erwarte
	 */
	function get_urls_file() {
		$download_path = $this->get_full_path($this->get('download_path'));
		$path_parts = explode('/', rtrim($download_path, '/'));
		array_pop($path_parts);
		$urls_file_path = implode('/', $path_parts) . '/';
		$urls_file = $urls_file_path . 'urls.txt';
		return $urls_file;
	}

}

?>
