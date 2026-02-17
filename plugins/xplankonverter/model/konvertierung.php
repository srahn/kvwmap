<?php
#############################
# Klasse Konvertierung #
#############################
class Konvertierung extends PgObject {

	static $schema = 'xplankonverter';
	static $tableName = 'konvertierungen';
	static $STATUS = array(
		'IN_ERSTELLUNG'			=> 'in Erstellung',
		'ERSTELLT'					 => 'erstellt',
		//		'IN_VALIDIERUNG'		 => 'in Validierung',
		//		'VALIDIERUNG_ERR'		=> 'Validierung fehlgeschlagen',
		//		'VALIDIERUNG_OK'		 => 'validiert',
		'ANGABEN_VOLLSTAENDIG'	=> 'Angaben vollständig',
		'IN_KONVERTIERUNG'			=> 'in Konvertierung',
		'KONVERTIERUNG_OK'			=> 'Konvertierung abgeschlossen',
		'KONVERTIERUNG_ERR'		 => 'Konvertierung abgebrochen',
		'IN_GML_ERSTELLUNG'		 => 'in GML-Erstellung',
		'GML_ERSTELLUNG_OK'		 => 'GML-Erstellung abgeschlossen',
		'GML_ERSTELLUNG_ERR'		=> 'GML-Erstellung abgebrochen',
		'GML_VALIDIERUNG_OK'		=> 'GML-Validierung abgeschlossen',
		'GML_VALIDIERUNG_ERR'	 => 'GML-Validierung mit Fehlern',
		'GEOWEB_SERVICE_ERSTELLT' => 'GeoWeb-Dienst erstellt',
		'IN_INSPIRE_GML_ERSTELLUNG'	=> 'in INSPIRE-GML-Erstellung',
		'INSPIRE_GML_ERSTELLUNG_OK'	=> 'INSPIRE-GML-Erstellung abgeschlossen',
		'INSPIRE_GML_ERSTELLUNG_ERR' => 'INSPIRE-GML-Erstellung abgebrochen'
	);
	static $write_debug = false;
	static $art = '';
	public $beschriftung = array();

	function __construct($gui, $planart = NULL) {
		parent::__construct($gui, Konvertierung::$schema, Konvertierung::$tableName);
		$this->set_config($planart);
	}

	/**
	 * Die Funktion setzt Einstellungen entsprechen der $planart.
	 * Wenn $planart nicht übergeben wurde wird die Planart aus $this->data abgefragt.
	 * @param string $planart (pptional) Planart der Konvertierung
	 */
	// MARK: Konfiguration
	function set_config($planart = null) {
		$this->set('planart', $planart ?? $this->get('planart') ?? 'Plan');
		$this->config = Konvertierung::get_config($this->get('planart'));
		# Die Attribute des Objektes, die mit plan_ anfangen kommen noch in $config und
		# als Klassenvariablen vor.
		# ToDo 2 pk: config-Variablen nutzen und Klassenvariablen ablöschen und löschen, siehe auch ToDo 1 pk in index.php
		$this->plan_title = $this->config['plan_title'];
		$this->plan_short_title = $this->config['plan_short_title'];
		$this->plan_class = $this->config['plan_class'];
		$this->plan_abk = $this->config['plan_abk'];
		$this->plan_layer_id = $this->config['plan_layer_id'];
		return $this->config;
	}

	public static function get_config($planart) {
		switch ($planart) {
			case ('BP-Plan') : {
				$config = array(
					'title' => 'Bebauungsplan', //Nominativ
					'artikel' => 'Der',
					'singular' => 'B-Plan',
					'akkusativ' => 'B-Plan',
					'genitiv' => 'des B-Planes',
					'genitiv_plural' => 'der B-Pläne',
					'plural' => 'B-Pläne',
					'keine_zusammenzeichnung' => 'keinen Plan mit der angegebenen Konvertierung-ID',
					'plan_title' => 'Bebauungsplan',
					'plan_short_title' => 'B-Plan',
					'plan_class' => 'BP_Plan',
					'plan_abk' => 'bplan',
					'plan_abk_plural' => 'bplaene',
					'plan_layer_id' => XPLANKONVERTER_BP_PLAENE_LAYER_ID,
					'plan_attribut_aktualitaet' => 'inkrafttretensdatum, genehmigungsdatum',
					'plan_file_name' => 'Bebauungsplan.gml',
					'mapfile_name' => 'bplaene.map',
					'upload_steps' => array(
						'upload_zusammenzeichnung',
						'import_zusammenzeichnung',
						'create_plaene',
						'convert_zusammenzeichnung',
						'gml_generieren',
						'check_class_completeness'
					)
				);
				// ToDo 'replace_zusammenzeichnung' noch nicht bei B-Plänen. Erst das optionale Überschreiben oder nicht in Dialog beim Upload einbauen, siehe Konzept bplan-Server.
				if (XPLANKONVERTER_CREATE_SERVICE) {
					array_merge(
						$config['upload_steps'],
						array(
							'create_geoweb_service',
							'create_metadata',
							'update_full_geoweb_service',
							'update_full_metadata'
						)
					);
				}
				$config['upload_bedingungen'] = "
					<li>{$config['artikel']} {$config['plan_title']} muss im Attribut " . natural_join($config['plan_attribut_aktualitaet'], ', ', ' oder ') . " des Objektes {$config['plan_class']} ein gültiges Datum beinhalten.</li>";
			} break;
			case ('FP-Plan') : {
				$config = array(
					'title' => 'Flächennutzungsplan',
					'artikel' => 'Die',
					'singular' => 'Zusammenzeichnung',
					'akkusativ' => 'Zusammenzeichnung',
					'genitiv' => 'der Zusammenzeichnung',
					'genitiv_plural' => 'der Zusammenzeichnungen der Flächennutzungspläne',
					'plural' => 'Zusammenzeichnungen',
					'keine_zusammenzeichnung' => 'keine veröffentlichte Zusammenzeichnung',
					'plan_title' => 'Flächennutzungsplan',
					'plan_short_title' => 'F-Plan',
					'plan_class' => 'FP_Plan',
					'plan_abk' => 'fplan',
					'plan_abk_plural' => 'fplaene',
					'plan_layer_id' => XPLANKONVERTER_FP_PLAENE_LAYER_ID,
					'plan_attribut_aktualitaet' => 'wirksamkeitsdatum, aenderungenbisdatum, genehmigungsdatum',
					'plan_file_name' => 'Zusammenzeichnung.gml',
					'mapfile_name' => 'zusammenzeichnung.map',
					'upload_steps' => array(
						'upload_zusammenzeichnung',
						'import_zusammenzeichnung',
						'create_plaene',
						'convert_zusammenzeichnung',
						'gml_generieren',
						'create_geoweb_service',
						'create_metadata',
						'update_full_geoweb_service',
						'update_full_metadata',
						'check_class_completeness',
						'replace_zusammenzeichnung'
					)
        );
				$config['upload_bedingungen'] = "
					<li>Die Daten müssen in einem ZIP-Archiv abgelegt sein.</li>
					<li>Die GML-Datei im ZIP-Archiv muss die Dateibezeichnung \"Zusammenzeichnung.gml\" aufweisen.</li>
					<li>Es kann eine GML-Datei mit Geltungsbereichen von Änderungsplänen enthalten sein. Sie muss \"Geltungsbereiche.gml\" heißen.</li>
					<li>Der {$config['plan_title']} muss im Attribut " . natural_join($config['plan_attribut_aktualitaet'], ', ', ' oder ') . " des Objektes {$config['plan_class']} ein gültiges Datum beinhalten. Weiterhin muss das Attribut rechtsstand erfasst werden.</li>
					<li>Die XPlanGML-Datei muss die gesamte Fläche der Kommune räumlich erfassen.</li>
				";
			} break;
			case ('SO-Plan') : {
				$config = array(
					'title' => 'Sonstiger Plan',
					'artikel' => 'Der',
					'singular' => 'sonstige Plan',
					'akkusativ' => 'sonstigen Plan',
					'genitiv' => 'des sonstigen Planes',
					'genitiv_plural' => 'der sonstigen Pläne',
					'plural' => 'Sonstige Pläne',
					'keine_zusammenzeichnung' => 'keine veröffentlichte Version',
					'plan_title' => 'Sonstiger Plan',
					'plan_short_title' => 'SO-Plan',
					'plan_class' => 'SO_Plan',
					'plan_abk' => 'soplan',
					'plan_abk_plural' => 'soplaene',
					'plan_layer_id' => XPLANKONVERTER_SO_PLAENE_LAYER_ID,
					'plan_attribut_aktualitaet' => 'genehmigungsdatum',
					'upload_bedingungen' => '',
					'plan_file_name' => 'SonstigerPlan.gml',
					'mapfile_name' => 'soplaene.map',
					'upload_steps' => array()
				);
			} break;
			case ('RP-Plan') : {
				$config = array(
					'title' => 'Regionales Raumordnungsprogramm',
					'artikel' => 'Das',
					'singular' => 'Regionales Raumordnungsprogramm',
					'akkusativ' => 'Regionalen Raumordnungsprogramm',
					'genitiv' => 'des Regionalen Raumordnungsprogramms',
					'genitiv_plural' => 'der Regionalen Raumordnungsprogramme',
					'plural' => 'Regionale Raumordnungsprogramme',
					'keine_zusammenzeichnung' => 'keine veröffentlichte Version',
					'plan_title' => 'Regionales Raumordnungsprogramm',
					'plan_short_title' => 'RP-Plan',
					'plan_class' => 'RP_Plan',
					'plan_abk' => 'rplan',
					'plan_abk_plural' => 'rplaene',
					'plan_layer_id' => XPLANKONVERTER_RP_PLAENE_LAYER_ID,
					'plan_attribut_aktualitaet' => 'datumdesinkrafttretens, planbeschlussdatum, genehmigungsdatum',
					'plan_file_name' => 'RROP.gml',
					'mapfile_name' => 'rplaene.map',
					'upload_steps' => array(
						'upload_zusammenzeichnung',
						'import_zusammenzeichnung',
						'create_plaene',
						'convert_zusammenzeichnung',
						'gml_generieren',
						'update_full_geoweb_service',
						'update_full_metadata',
						'check_class_completeness',
						'replace_zusammenzeichnung'
					)
				);
				$config['upload_bedingungen'] = "
					<li>Die Daten müssen in einem ZIP-Archiv abgelegt sein.</li>
					<li>In dem ZIP-Archiv muss mindestens eine GML-Datei vorhanden sein. Sind mehrere enthalten wird nur die alphabetisch sortiert erste verwendet.</li>
					<li>{$config['artikel']} {$config['singular']} muss im Attribut " . natural_join($config['plan_attribut_aktualitaet'], ', ', ' oder ') . " des Objektes {$config['plan_class']} ein gültiges Datum beinhalten.</li>
				";
			} break;
			default : {
				$config = array(
					'title' => 'XPlan',
					'artikel' => 'Der',
					'singular' => 'XPlan',
					'nominativ' => 'XPlan',
					'plural' => 'XPläne',
					'genitiv' => 'des Plans',
					'genitiv_plural' => 'der XPläne',
					'keine_zusammenzeichnung' => 'keine veröffentlichte Version',
					'plan_title' => 'Plan',
					'plan_short_title' => 'Plan',
					'plan_class' => 'Plan',
					'plan_abk' => 'plan',
					'plan_abk_plural' => 'xplaene',
					'plan_layer_id' => NULL,
					'plan_attribut_aktualitaet' => 'genehmigungsdatum',
					'upload_bedingungen' => '
						<li>Es wurde keine Planart ausgewählt.</li>
					',
					'plan_file_name' => 'uploaded_xplan.gml',
					'mapfile_name' => 'plaene.map',
					'upload_steps' => array()
				);
			}
		}
		$config['plan_table_name'] = strtolower($config['plan_class']);
		$config['plan_oid_name'] = $config['plan_table_name'] . '_oid';
		$config['planart_abk'] = strtolower(substr($planart, 0, 2));
		$config['planart_short'] = strtolower(substr($planart, 0, 1));
		return $config;
	}

	function planart_to_planclass($planart) {
		return strtolower(str_replace('-', '_', $planart));
	}

	function create_geoweb_service($xplan_layers, $ows_onlineresource) {
		$gui = $this->gui;

		$gui->class_load_level = 2;
		$gui->loadMap('DataBase', array(), true); // Layer name immer aus Attribute Name
		$bb = $gui->Stelle->MaxGeorefExt;

		# Setze Metadaten
		$gui->map->set('name', sonderzeichen_umwandeln($this->plan->get('name')));
		$gui->map->extent->setextent($this->plan->extent['minx'], $this->plan->extent['miny'], $this->plan->extent['maxx'], $this->plan->extent['maxy']);
		$gui->map->setMetaData("ows_extent", implode(' ', $this->plan->extent));
		$gui->map->setMetaData("ows_abstract", $gui->map->getMetaData('ows_abstract') . ' Rechtskraft ' . $this->get_aktualitaetsdatum());
		# Hier die Variante wo die Bezeichnung des Datums aus dem Attributnamen der Aktualität entnommen wird:
		#$gui->map->setMetaData("ows_abstract", $gui->map->getMetaData('ows_abstract') . ' ' . ucfirst($this->get_plan_attribut_aktualitaet()) . ': ' . $this->get_aktualitaetsdatum());
		$gui->map->setMetaData("ows_onlineresource", $ows_onlineresource);
		$gui->map->setMetaData("ows_service_onlineresource", $ows_onlineresource);
		$gui->map->setMetaData("ows_srs", $gui->Stelle->ows_srs ?: OWS_SRS);
		$gui->map->web->set('header', '../templates/header.html');
		$gui->map->web->set('footer', '../templates/footer.html');
		# Filter Layer, die nicht im Dienst zu sehen sein sollen
		# Und setze bei den anderen die Templates
		$result = $this->plan->get_layers_with_content($xplan_layers, $this->get($this->identifier));
		if (! $result['success']) {
			return $result;
		}

		$layers_with_content = $result['layers_with_content'];
		$layernames_with_content = array_keys($layers_with_content);
		$layernames_with_content[] = strtoupper($planartkuerzel) . '-Pläne';
		$layernames_with_content[] = strtoupper($this->plan->planartAbk) . '-Bereiche';
		$layernames_with_content[] = 'Geltungsbereiche';
		#echo '<br>pk layernames_with_content: ' . print_r($layernames_with_content, true);
		$layers_to_remove = array();
		for ($i = 0; $i < $gui->map->numlayers; $i++) {
			$layer = $gui->map->getLayer($i);
			if (in_array($layer->name, $layernames_with_content)) {
				$layer->set('header', '../templates/' . $layer->name . '_head.html');
				$layer->set('template', '../templates/' . $layer->name . '_body.html');
				# Hier eingeführt und nicht in GUI->loadlayer, weil führt dort beim WebAtlas-WMS zu einem Fehler
				$layer->setMetaData('ows_extent', $bb->minx . ' '. $bb->miny . ' ' . $bb->maxx . ' ' . $bb->maxy);
				# Set Data sql for layer
				$layer_id = $layer->getMetadata('kvwmap_layer_id');
				$layerObj = Layer::find_by_id($gui, $layer_id);
				if (!$layerObj) {
					return array(
						'success' => false,
						'msg' => 'Fehler bei der Erzeugung des Web-Services. Layer mit der ID ' . $layer_id . ' wurde nicht gefunden!'
					);
				}
				$result = $layerObj->get_generic_data_sql();
				if ($result['success']) {
					$layer->set('data', $result['data_sql']);
					if (strpos($layer->data, 'xplankonverter.konvertierungen k') !== false) {
						$layer->set('data', str_ireplace(' WHERE ', ' WHERE (', $layer->data));
						$layer->set('data', str_ireplace(') as foo using unique', ') AND k.veroeffentlicht) AS foo using unique', $layer->data));
					}
				}
				else {
					$result['msg'] = 'Fehler bei der Erstellung der Map-Datei in Funktion get_generic_data_sql! ' . $result['msg'];
					return $result;
				}
			}
			else {
				$gui->map->removeLayer($i);
				$i--;
			}
		}
		$geoweb_service_updated_at = Date('Y-m-d H:i:s');
		$update_attributes = array("geoweb_service_updated_at = '" . $geoweb_service_updated_at . "'");
		if (!$this->get('geoweb_service_created_at')) {
			$update_attributes[] = "geoweb_service_created_at = '" . $geoweb_service_updated_at . "'";
		}

		$this->update_attr($update_attributes);
		return array(
			'success' => true,
			'mapfile' => $this->get('stelle_id') . '/' . MAPFILENAME. '.map',
			'geoweb_service_updated_at' => $geoweb_service_updated_at
		);
	}

	public static	function find_by_id($gui, $by, $id, $select = '*') {
		$konvertierung = new Konvertierung($gui);
		$konvertierung->select = $select;
		$konvertierung->find_by($by, $id);
		$konvertierung->debug->show('Found Konvertierung with planart: ' . $konvertierung->get('planart'), Konvertierung::$write_debug);
		if ($konvertierung->get('planart') != '') {
			if ($konvertierung->get_plan()) {
				$konvertierung->plan->get_center_coord();
				$konvertierung->plan->get_extent(OWS_SRS, 'konvertierung_id = ' . $id);
			}
			$konvertierung->set_config($konvertierung->get('planart'));
		}
		return $konvertierung;
	}

	public static	function find_where_with_plan($gui, $where, $order = '') {
		$konvertierung = new Konvertierung($gui);
		$konvertierungen = $konvertierung->find_where($where, $order);
		array_walk(
			$konvertierungen,
			function($k) {
				if ($k->get('planart') != '') {
					$k->get_plan();
				}
			}
		);
		return $konvertierungen;
	}

	public static function find_by_document($gui, $document) {
		$parts = explode('_', APPLVERSION);
		if (trim(end($parts), '/') == 'dev') {
			$xplankonverter_file_path = str_replace('/var/www/data/', '/var/www/data_dev/', XPLANKONVERTER_FILE_PATH);
		}
		else {
			$xplankonverter_file_path = XPLANKONVERTER_FILE_PATH;
		}
		$path = pathinfo($document);
		$konvertierung = new Konvertierung($gui);
		switch (strToLower($path['extension'])) {
			case 'gml' :
				$konvertierungen = $konvertierung->find_where('id = ' . explode('_', $path['filename'])[1] . ' AND veroeffentlicht');
				if (count($konvertierungen) == 0) {
					return false;
				}
				$konvertierung = $konvertierungen[0];
				$konvertierung->exportfile = $xplankonverter_file_path . $konvertierung->get($this->identifier) . '/xplan_gml' . $document;
				$konvertierung->contenttype = 'text/xml';
				return $konvertierung;
			case 'pdf' :
				$sql = "
					SELECT
						*
					FROM
						(
							SELECT
								k.*,
								(unnest(p.externereferenz)).referenzurl
							FROM
								xplan_gml.bp_plan p JOIN
								xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
						) sub
					WHERE
						sub.referenzurl LIKE '%" . $path['basename'] . "%' AND
						" . ($_SESSION['angemeldet'] ? 'true' : "sub.veroeffentlicht") . "
				";
				#echo '<br>Sql zur Abfrage der Konvertierung: ' . $sql;
				$rows = $konvertierung->getSQLResults($sql)[0];
				if (count($rows) == 0) return false;
				$konvertierung->data = $rows[0];
				$konvertierung->exportfile = $xplankonverter_file_path . 'plaene' . $document;
				$konvertierung->contenttype = 'application/pdf';
				return $konvertierung;
			case 'jpg' :
				$filename = get_name_from_thumb($path['basename']);
				$sql = "
					SELECT
						*
					FROM
						(
							SELECT
								k.*,
								(unnest(p.externereferenz)).referenzurl
							FROM
								xplan_gml.bp_plan p JOIN
								xplankonverter.konvertierungen k ON p.konvertierung_id = k.id
						) sub
					WHERE
						right(sub.referenzurl, position('/' in reverse(sub.referenzurl)) - 1) LIKE '" . $filename . "%' AND
						" . ($_SESSION['angemeldet'] ? 'true' : "sub.veroeffentlicht") . "
				";
				#echo '<br>Sql zur Abfrage der Konvertierung: ' . $sql;
				$rows = $konvertierung->getSQLResults($sql)[0];
				if (count($rows) == 0) return false;
				$konvertierung->data = $rows[0];
				$konvertierung->exportfile = $xplankonverter_file_path . 'plaene' . $document;
				$konvertierung->contenttype = 'image/jpg';
				return $konvertierung;
			default :
				return false;
		}
	}

	/**
	 * Fragt die Konvertierung der Stelle, Planart und/oder id ab und teilt sie ein in 
	 * entwurf (draft), veröffentlicht (pubished), archiviert (archived) und fehlerhaft (faulty)
	 * vormals hieß die Funktion find_zusammenzeichnung. Sie findet jetzt aber auch Konvertierungen,
	 * die keine Zusammenzeichnungen sind.
	 */
	public static function find_konvertierungen($gui, $planart, $plan_class, $konvertierung_id = '') {
		$konvertierungen = array(
			'published' => array(),
			'draft' => array(),
			'archived' => array(),
			'faulty' => array()
		);
		$konvertierung = new Konvertierung($gui);
		$where_conditions = array();
		$where_conditions[] = "k.stelle_id = " . $gui->Stelle->id;
		$where_conditions[]	=	"k.planart = '" . $planart . "'";
		if ($planart == 'FP-Plan') {
			$where_conditions[] = "(p.zusammenzeichnung OR p.zusammenzeichnung IS NULL)";
		}
		if ($planart == 'BP-Plan') {
			// Bei BP-Plan nur Konvertierung anzeigen wenn id angegeben.
			$where_conditions[] = ($konvertierung_id != '' ? "k.id = " . $konvertierung_id : "false");
		}
		$sql = "
			SELECT
				k.*
			FROM
				xplankonverter.konvertierungen k LEFT JOIN
				xplan_gml." . strtolower($plan_class) . " p ON k.id = p.konvertierung_id
			WHERE
				" . implode(" AND ", $where_conditions) . "
			ORDER BY 
				COALESCE(" . implode(', ', array_map(
					function($plan_attribut) {
						return "p." . $plan_attribut;
					},
					explode(', ', Konvertierung::get_config($planart)['plan_attribut_aktualitaet'])
				)) . ") DESC
		";
		// if ($gui->user->id == 41) {
		// 	echo "<p>SQL zur Abfrage der Zusammenzeichnungen: " . $sql; exit;
		// }
		$gui->xlog->write('find_konvertierungen.');
		//$gui->xlog->write('find_konvertierungen sql: ' . $sql);
		$konvertierung->debug->show('find_konvertierungen sql: ' . $sql, false);
		$query = pg_query($konvertierung->database->dbConn, $sql);
		while ($konvertierung->data = pg_fetch_assoc($query)) {
			$konvertierung->set_config();
			$konvertierung->plan = false;
			$konvertierung->get_plan();
			if ($konvertierung->get('veroeffentlicht') == 't') {
				$konvertierung->art = 'published';
			}
			elseif (
				$konvertierung->plan === false OR
				(
					$konvertierung->get('error_id') AND
					$konvertierung->get('error_id') > 0)
				) {
				$konvertierung->art = 'faulty';
			}
			else {
				$konvertierung->art = 'draft';
			}
			$konvertierungen[$konvertierung->art][] = clone $konvertierung;
		}
    $archiv_dir = XPLANKONVERTER_FILE_PATH . 'archiv/' . $gui->Stelle->id . '/' . $konvertierung->config['plan_abk_plural'] . '/';
		$gui->xlog->write('Archiv_Dir: ' . $archiv_dir);
		if (file_exists($archiv_dir)) {
			$konvertierungen['archived'] = glob($archiv_dir . '*');
			rsort($konvertierungen['archived'],  SORT_STRING);
		}
		// if ($gui->user->id == 41) {
		// 	echo 'z: ' . print_r(array_map(function($z) { return array_map(function($x) { return $x->data['id']; }, $z); }, $konvertierungen), true); exit;
		// }
		return $konvertierungen;
	}

	// function archiv_old_zusammenzeichnung() {
	// 	# Zippe Zusammenzeichnung und Geltungsbereiche aus Verzeichnis $GUI->konvertierung->get_file_path('uploaded_xplan_gml'); (XPLANKONVERTER_FILE_PATH/<konvertierung_id>/uploaded_xplan_gml/)
	// 	$zip_path = XPLANKONVERTER_FILE_PATH . 'archiv/' . $this->gui->Stelle->id . '/' . $konvertierung->config['plan_abk_plural'] . '/';
	// 	$zip_file = 'Zusammenzeichnung_' . $this->gui->Stelle->Bezeichnung . '_' . date_format(date_create($this->get_aktualitaetsdatum()), 'Y-m-d') . '.zip';
	// 	$this->gui->debug->write('Archiviere Plan in zip_file: ' . $zip_file);
	// 	if (!file_exists($zip_path)) {
	// 		mkdir($zip_path, 0660, true);
	// 	}

	// 	$archive = new ZipArchive();

	// 	if ($archive->open($zip_path . $zip_file, (ZipArchive::CREATE | ZipArchive::OVERWRITE)) !== true) {
	// 		return array(
	// 			'success' => false,
	// 			'msg' => "Kann Zip-Archiv " . $zip_path . $zip_file . " nicht anlegen"
	// 		);
	// 	}
	// 	$this->gui->debug->write('Zip-Datei angelegt.');
	// 	$archive->addGlob($this->get_file_path('uploaded_xplan_gml') . '*.gml');
	// 	$this->gui->debug->write('GML-Dateien aus Verzeichnis: ' . $this->get_file_path('uploaded_xplan_gml') . ' zur Zip-Datei hinzufügen.');
	// 	if ($zipArchive->status != ZIPARCHIVE::ER_OK) {
	// 		try {
	// 			$archive->close();
	// 		}
	// 		catch (Exception $e) {
	// 			return array(
	// 				'success' => false,
	// 				'msg' => "Fehler beim Hinzufügen der hochgeladenen Dateien aus Verzeichnis " . $this->get_file_path('uploaded_xplan_gml') . " in das Archiv."
	// 			);
	// 		}
	// 	}

	// 	$geodata_metadata_url = METADATA_CATALOG . '/srv/api/records/' . $this->get('metadata_dataset_uuid') . '/formatters/xml?approved=true';
	// 	$geodata_metadata_file = @file_get_contents($geodata_metadata_url);
	// 	$this->gui->debug->write('Metadaten von URL: ' . $geodata_metadata_url . ' zur Zipdatei hinzufügen.');
	// 	if ($geodata_metadata_file !== FALSE) {
	// 		#add it to the zip
	// 		$archive->addFromString('Metadaten.xml', $geodata_metadata_file);
	// 		if ($zipArchive->status != ZIPARCHIVE::ER_OK) {
	// 			try {
	// 				$archive->close();
	// 			}
	// 			catch (Exception $e) {
	// 				return array(
	// 					'success' => false,
	// 					'msg' => "Fehler beim Hinzufügen der Metadatendatei von " . $geodata_metadata_url . " in das Archiv"
	// 				);
	// 			}
	// 		}
	// 	}

	// 	try {
	// 		$this->gui->debug->write('Löschen der archivierten Konvertierung id: ' . $this->get('id'));
	// 		$this->destroy();
	// 		$archive->close();
	// 	}
	// 	catch (Exception $e) {
	// 		if ($archive->numFiles > 0) {
	// 			return array(
	// 				'success' => false,
	// 				'msg' => "Fehler beim Schließen des Archivs" . $zip_path . $zip_file . ": " . $e->getMessage()
	// 			);
	// 		}
	// 		else {
	// 			return array(
	// 				'success' => true,
	// 				'msg' => 'ZIP-Archiv ' . $zusammenzeichnung_zip . ' für die vorherige Konvertierung erfolgreich angelegt und Objekt in Datenbank gelöscht.'
	// 			);
	// 		}
	// 	}
	// 	return array(
	// 		'success' => true,
	// 		'msg' => 'ZIP-Archiv ' . $zusammenzeichnung_zip . ' für die vorherige Konvertierung erfolgreich angelegt und Objekt in Datenbank gelöscht.'
	// 	);
	// }

	/**
	 * Sichert die Daten des alten Planes der aktualisiert wird in eine ZIP-Datei und legt sie im Unterordner archiv/<stelle_id>/<planart> ab.
	 */
	function archiv_old_plan() {
		# Zippe Dateien in Verzeichnis $GUI->konvertierung->get_file_path('uploaded_xplan_gml'); (XPLANKONVERTER_FILE_PATH/<konvertierung_id>/uploaded_xplan_gml/)
		$zip_path = XPLANKONVERTER_FILE_PATH . 'archiv/' . $this->gui->Stelle->id . '/' . $this->config['plan_abk_plural'] . '/';
		$zip_file = $this->config['plan_short_title'] . '_' . $this->gui->Stelle->Bezeichnung . '_' . date_format(date_create($this->get_aktualitaetsdatum()), 'Y-m-d') . '.zip';
		$this->gui->xlog->write('Archiviere ' . $this->config['singular'] . ' in zip_file: ' . $zip_path . $zip_file);
		if (!file_exists($zip_path)) {
			mkdir($zip_path, 0660, true);
		}

		$archive = new ZipArchive();

		if ($archive->open($zip_path . $zip_file, ZipArchive::CREATE|ZipArchive::OVERWRITE) !== true) {
			return array(
				'success' => false,
				'msg' => "Kann Zip-Archiv " . $zip_path . $zip_file . " nicht anlegen"
			);
		}
		$this->gui->xlog->write('Zip-Datei angelegt.');
		$archive->addGlob($this->get_file_path('uploaded_xplan_gml') . '*.gml');
		$this->gui->xlog->write('GML-Dateien aus Verzeichnis: ' . $this->get_file_path('uploaded_xplan_gml') . ' zur Zip-Datei hinzufügen.');
		$this->gui->xlog->write('Files: ' . implode("\n", glob($this->get_file_path('uploaded_xplan_gml') . '*.gml')));
		if ($archive->status != ZipArchive::ER_OK) {
			try {
				$this->gui->xlog->write('Fehler bei Hinzufügen der Dateien in die ZIP-Datei. zipArchiv status: ' . $archive->status);
				$archive->close();
			}
			catch (Exception $e) {
				$this->gui->xlog->write('Fehler bei Schließen des zipArchivs');
				return array(
					'success' => false,
					'msg' => "Fehler beim Hinzufügen der hochgeladenen Dateien aus Verzeichnis " . $this->get_file_path('uploaded_xplan_gml') . " in das Archiv."
				);
			}
		}

		$geodata_metadata_url = METADATA_CATALOG . '/srv/api/records/' . $this->get('metadata_dataset_uuid') . '/formatters/xml?approved=true';
		$geodata_metadata_file = @file_get_contents($geodata_metadata_url);
		$this->gui->debug->write('Metadaten von URL: ' . $geodata_metadata_url . ' zur Zipdatei hinzufügen.');
		$this->gui->xlog->write('Metadaten von URL: ' . $geodata_metadata_url . ' zur Zipdatei hinzufügen.');
		if ($geodata_metadata_file !== FALSE) {
			#add it to the zip
			$archive->addFromString('Metadaten.xml', $geodata_metadata_file);
			if ($archive->status != ZipArchive::ER_OK) {
				try {
					$archive->close();
				}
				catch (Exception $e) {
					return array(
						'success' => false,
						'msg' => "Fehler beim Hinzufügen der Metadatendatei von " . $geodata_metadata_url . " in das Archiv"
					);
				}
			}
		}
		
		try {
			$this->gui->xlog->write('Anzahl Files in ZIP-File: ' . $archive->numFiles);
			$this->gui->xlog->write('Zippath and zipfile: ' . $zip_path . $zip_file);
			$archive->close();
			$this->gui->debug->write('Löschen der archivierten Konvertierung id: ' . $this->get('id'));
			$this->gui->xlog->write('Löschen der archivierten Konvertierung id: ' . $this->get('id'));
			$this->destroy();
			$this->gui->xlog->write('Konvertierung gelöscht.');
			$this->gui->xlog->write('Archiv geschlossen.');
		}
		catch (Exception $e) {
			$this->gui->xlog->write("Fehler beim Schließen des Archivs" . $zip_path . $zip_file . ": " . $e->getMessage());
			if ($archive->numFiles > 0) {
				return array(
					'success' => false,
					'msg' => "Fehler beim Schließen des Archivs" . $zip_path . $zip_file . ": " . $e->getMessage()
				);
			}
			else {
				return array(
					'success' => true,
					'msg' => 'ZIP-Archiv ' . $zip_path . $zip_file . ' für die vorherige Konvertierung erfolgreich angelegt und Objekt in Datenbank gelöscht.'
				);
			}
		}
		return array(
			'success' => true,
			'msg' => 'ZIP-Archiv ' . $zip_path . $zip_file . ' für die vorherige Konvertierung erfolgreich angelegt und Objekt in Datenbank gelöscht.'
		);
	}

	function archiv_old_plan_test() {
		# Zippe Dateien in Verzeichnis $GUI->konvertierung->get_file_path('uploaded_xplan_gml'); (XPLANKONVERTER_FILE_PATH/<konvertierung_id>/uploaded_xplan_gml/)
		$zip_path = XPLANKONVERTER_FILE_PATH . 'archiv/' . $this->gui->Stelle->id . '/' . $this->config['plan_abk_plural'] . '/';
		$zip_file = $this->config['plan_short_title'] . '_' . $this->gui->Stelle->Bezeichnung . '_' . date_format(date_create($this->get_aktualitaetsdatum()), 'Y-m-d') . '.zip';
		$this->gui->xlog->write('Archiviere ' . $this->config['singular'] . ' in zip_file: ' . $zip_path . $zip_file);
		if (!file_exists($zip_path)) {
			mkdir($zip_path, 0660, true);
		}

		$archive = new ZipArchive();

		if ($archive->open($zip_path . $zip_file, (ZipArchive::CREATE | ZipArchive::OVERWRITE)) !== true) {
			return array(
				'success' => false,
				'msg' => "Kann Zip-Archiv " . $zip_path . $zip_file . " nicht anlegen"
			);
		}
		$this->gui->xlog->write('Zip-Datei angelegt.');
		$archive->addGlob($this->get_file_path('uploaded_xplan_gml') . '*.gml');
		$this->gui->xlog->write('GML-Dateien aus Verzeichnis: ' . $this->get_file_path('uploaded_xplan_gml') . ' zur Zip-Datei hinzufügen.');
		$this->gui->xlog->write('Files: ' . implode("\n", glob($this->get_file_path('uploaded_xplan_gml') . '*.gml')));
		if ($zipArchive->status != ZIPARCHIVE::ER_OK) {
			try {
				$archive->close();
			}
			catch (Exception $e) {
				return array(
					'success' => false,
					'msg' => "Fehler beim Hinzufügen der hochgeladenen Dateien aus Verzeichnis " . $this->get_file_path('uploaded_xplan_gml') . " in das Archiv."
				);
			}
		}
		$this->gui->xlog->write('ZIP-File closed');
	}
	/**
	 * Anlegen einer Konvertierung
	 * @param string $anzeige_name, Der Name mit dem die Konvertierung angezeigt werden soll
	 * @param string $epsg_code, EPSG-Code der Konvertierung
	 * @param string $epsg_code, EPSG-Code der importierten Daten
	 * @param string $planart, Die Planart der Konvertierung
	 * @param string $stelle_id, Die Id der Stelle zu der die Konvertierung gehören soll
	 * @param string $user_id, Die Id des Nutzers der die Konvertierung angelegt hat.
	 * @param string $uploaded_xplan_gml_file_name, Der Name der XPlanGml-Datei des Plans die hoch geladen wurde.
	 * @return int id of new konvertierung if success, else -1
	 */
	function create($anzeige_name = '', $epsg_code = '', $input_epsg_code = '', $planart = '', $stelle_id = '', $user_id = '', $uploaded_xplan_gml_file_name = '') {
		$sql = "
			INSERT INTO " . $this->schema . "." . $this->tableName . " (
				bezeichnung,
				geom_precision,
				gml_layer_group_id,
				epsg,
				input_epsg,
				output_epsg,
				planart,
				uploaded_xplan_gml_file_name,
				stelle_id,
				user_id
			) VALUES (
				'" . $anzeige_name . "',
				15,
				null,
				'" . $epsg_code . "'::xplankonverter.epsg_codes,
				'" . $input_epsg_code . "'::xplankonverter.epsg_codes,
				'" . $epsg_code . "'::xplankonverter.epsg_codes,
				'" . $planart . "',
				'" . ($uploaded_xplan_gml_file_name ?: $this->config['plan_file_name']) . "',
				" . $stelle_id . ",
				" . $user_id . "
			)
			RETURNING " . $this->identifier . "
		";
		$this->debug->show('Create new konvertierung with sql: ' . $sql, Konvertierung::$write_debug);
		$query = pg_query($this->database->dbConn, $sql);
		if ($query === false) {
			return -1;
		}
		$row = pg_fetch_assoc($query);
		$oid = $row[$this->identifier];
		#echo '<br>oid: ' . $oid;
		if (empty($oid)) {
			$this->lastquery = $query;
		}
		else {
			$sql = "
				SELECT
					*
				FROM
					" . $this->schema . "." . $this->tableName . "
				WHERE
					" . $this->identifier . " = " . $oid . "
			";
			$this->debug->show('Query created ' . $this->identifier . ' with sql: ' . $sql, Konvertierung::$write_debug);
			$query = pg_query($this->database->dbConn, $sql);
			$row = pg_fetch_assoc($query);
			$this->set($this->identifier, $row[$this->identifier]);
		}
		$this->debug->show('Konvertierung created with ' . $this->identifier . ': '. $this->get($this->identifier), Konvertierung::$write_debug);
		return $this->get($this->identifier);
	}

	function create_directories() {
		$directories = array(
			'uploaded_shapes',
			'edited_shapes',
			'uploaded_xplan_gml',
			'xplanvalidator_reports',
			'xplan_gml',
			'xplan_shapes',
			'inspire_gml'
		);

		foreach ($directories AS $directory) {
			$path = $this->get_file_path($directory);
			$this->debug->show('Check if directory: ' . $path . ' exists.', Konvertierung::$write_debug);
			if (!is_dir($path)) {
				$this->debug->show('Create directory', Konvertierung::$write_debug);
				$old = umask(0);
				mkdir($path, 0660, true);
				umask($old);
			}
		}
	}

	/**
	 * Removes the directory determined which get_file_path() in which the uploaded and created documents are
	 * located.
	 * It only estroys if the id of the konvertierung is contained in the path. 
	 */
	function delete_upload_directory() {
		$file_path = $this->get_file_path('');
		if ($file_path AND strpos($file_path, $this->get($this->identifier)) !== false) {
			$cmd = "rm -R " . $file_path;
		}
		exec($cmd);
	}

	function get_geoweb_service_last_update() {
		$date = date_create($this->get('geoweb_service_updated_at') ?: $this->get('geoweb_service_created_at'));
		return date_format($date, 'd.m.Y H:i:s');
	}

	function get_file_path($directory) {
		return XPLANKONVERTER_FILE_PATH . $this->get($this->identifier) . '/' . ($directory ? $directory . '/' : '');
	}

	function get_file_name($name) {
		$parts = explode('_', $name);
		return $this->get_file_path($name) . $parts[0] . '_' . $this->get($this->identifier) . '.' . $parts[1];
	}

	/*
	* function check if files of file_type
	* exists for download in this konvertierung
	* return true if yes and false if not
	*/
	function files_exists($file_type) {
		$result = false;

		switch ($file_type) {
			case 'uploaded_shape_files' : {
				$result = $this->download_files_exists('uploaded_shapes');
			} break;
			case 'edited_shape_files' : {
				$sql = "
					SELECT
						table_name
					FROM
						information_schema.tables
					WHERE
						table_schema = 'xplan_shapes_" . $this->get($this->identifier) . "'
				";
				#echo 'SQL zum Abfragen des Tabellennamen: ' . $sql; exit;
				$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
				$result = (pg_num_rows(pg_query($this->database->dbConn, $sql)) > 0);
			} break;
			case 'zusammenzeichnung_gml' : {
				$filename = $this->get_file_name('zusammenzeichnung_gml');
				$result = file_exists($filename);
			} break;
			case 'zusammenzeichnung-neu_gml' : {
				$filename = $this->get_file_name('zusammenzeichnung-neu_gml');
				$result = file_exists($filename);
			} break;
			case 'xplan_gml' : {
				$filename = $this->get_file_name('xplan_gml');
				$result = file_exists($filename);
			} break;
			case 'xplan_shape_files' : {
				# Fragt die classen ab, die für die Konvertierung registriert sind und prüfe ob es dazu Daten gibt.
				# Wenn ja, setze result = true und Abbruch der Schleife
				foreach ($this->get_class_names() AS $class_name) {
					$sql = "
						SELECT
							count(*) AS num_objects
						FROM
							xplan_gml. " . strtolower($class_name) . "
						WHERE
							konvertierung_id = " . $this->get($this->identifier) . "
					";
					#echo 'SQL zur Abfrage der Anzahl der Objekte in Tabelle: xplan_gml.' . strtolower($class_name) . ': ' . $sql;
					$rs = pg_fetch_assoc(pg_query($this->database->dbConn, $sql));
					if ($rs['num_objects'] > 0) {
						$result = true;
						break;
					}
				}
			} break;
			case 'inspire_gml' : {
				$filename = $this->get_file_name('inspire_gml');
				$result = file_exists($filename);
			} break;
			default : {
				
			}
		}
		return $result;
	}

	function zusammenzeichnung_exists() {
		return $this->files_exists('zusammenzeichnung_gml');
	}

	function neue_zusammenzeichnung_exists() {
		return $this->files_exists('zusammenzeichnung-neu_gml');
	}

	function create_edited_shapes() {
		$path = $this->get_file_path('edited_shapes');
		$this->debug->show('Erzeuge Edited-Shape-Files in. ' . $path, Konvertierung::$write_debug);
		$export_class = new data_import_export();

		$this->debug->show('Frage Source-Tabellen ab: ', Konvertierung::$write_debug);
		$sql = "
			SELECT
				table_name
			FROM
				information_schema.tables
			WHERE
				table_schema = 'xplan_shapes_" . $this->get($this->identifier) . "'
		";
		#echo 'SQL zum Abfragen des Tabellennamen: ' . $sql; exit;

		$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
		$result = pg_fetch_all(
			pg_query($this->database->dbConn, $sql)
		);

		$class_names = [];
		if ($result) {
			$class_names = array_map(
				function($row) {
					return $row['table_name'];
				},
				$result
			);
		}
		else {
			$class_names = array();
		}

		$this->debug->show('Erzeuge Shape-Dateien für jede Klasse:', Konvertierung::$write_debug);
		foreach ($class_names AS $class_name) {
			$result = pg_query($this->database->dbConn, $sql);
			//$result = pg_fetch_assoc($query);
			if (pg_num_rows($result) == 0) {
				continue;
			}
			else {
				while ($row = pg_fetch_array($result)) {
					$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
					$sql = "
						SELECT
							*
						FROM
							xplan_shapes_" . $this->get($this->identifier) . "." . $class_name . "
					";
					$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
					$export_class->ogr2ogr_export($sql, '"ESRI Shapefile"', $path . ltrim($class_name, 'shp_') . '.shp', $this->database);
				}
			}
		}
	}

	function create_xplan_shapes() {
		$path = $this->get_file_path('xplan_shapes');
		if (!file_exists($path)) {
			mkdir($path, 0660);
		}

		// Delete existing shapes
		$this->debug->show('Lösche xplan-konforme-shape-Dateien', Konvertierung::$write_debug);
		$files = glob($path);
		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}
		// Delete existing zip if exists
		$zip_file = $this->get_file_path('') . 'xplan_shapes.zip';
		if (file_exists($zip_file)) {
			unlink($zip_file);
		}

		$this->debug->show('Erzeuge XPlan-Shape-Files in: ' . $path, Konvertierung::$write_debug);
		$export_class = new data_import_export();

		$this->debug->show('Frage XPlan-Klassen ab:', Konvertierung::$write_debug);
		$class_names = $this->get_class_names();

		$this->debug->show('Erzeuge Shape-Dateien für jede Klasse:', Konvertierung::$write_debug);
		foreach ($class_names AS $class_name) {
			$sql = "
				SELECT
					DISTINCT ST_GeometryType(position)
				FROM
					xplan_gml. " . strtolower($class_name). "
				WHERE
					konvertierung_id = " . $this->get($this->identifier) . "
			";
			$result = pg_query($this->database->dbConn, $sql);
			// Get src srid of table as its not necessarily konvertierung dependent
			$sql_srid = "
				SELECT
					DISTINCT ST_SRID(position)
				FROM
					xplan_gml. " . strtolower($class_name). "
				WHERE
					konvertierung_id = " . $this->get($this->identifier) . "
				LIMIT 1
			";
			$result_srid = pg_query($this->database->dbConn, $sql_srid);
			// fallback input-epsg konvertierung
			$src_srid = !empty(pg_fetch_result($result_srid, 0, 0)) ? pg_fetch_result($result_srid, 0, 0) : $this->get('output_epsg');

			//$result = pg_fetch_assoc($query);
			if (pg_num_rows($result) == 0) {
				continue;
			} else {
				while ($row = pg_fetch_array($result)){
					if ($row[0] == 'ST_MultiPoint') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_point = "
							SELECT
								*
							FROM
								xplan_gml." . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get($this->identifier) . "
							AND
								ST_GeometryType(position) = 'ST_MultiPoint'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						$export_class->ogr2ogr_export($sql_point, '"ESRI Shapefile" -s_srs epsg:' . $src_srid . ' -t_srs epsg:' . $this->get('output_epsg') . ' -nlt MULTIPOINT', $path . $class_name . '_point.shp', $this->database);
					}
					if ($row[0] == 'ST_MultiLineString') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_line = "
							SELECT
								*
							FROM
								xplan_gml." . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get($this->identifier) . "
							AND
								ST_GeometryType(position) = 'ST_MultiLineString'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						$export_class->ogr2ogr_export($sql_line, '"ESRI Shapefile" -s_srs epsg:' . $src_srid . ' -t_srs epsg:' . $this->get('output_epsg') . ' -nlt MULTILINESTRING ', $path . $class_name . '_line.shp', $this->database);
					}
					if ($row[0] == 'ST_MultiPolygon') {
						$this->debug->show('Klasse: ' . $class_name, Konvertierung::$write_debug);
						$sql_poly = "
							SELECT
								*
							FROM
								xplan_gml." . strtolower($class_name) . "
							WHERE
								konvertierung_id = " . $this->get($this->identifier) . "
							AND
								ST_GeometryType(position) = 'ST_MultiPolygon'
						";
						$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);
						#echo $path . $class_name . '_poly.shp';
						$export_class->ogr2ogr_export($sql_poly, '"ESRI Shapefile" -s_srs epsg:' . $src_srid . ' -t_srs epsg:' . $this->get('output_epsg') . ' -nlt MULTIPOLYGON', $path . $class_name . '_poly.shp', $this->database);
					}
				}
			}
		}

		// Fuer Plan
		$this->debug->show('Klasse: ' . $this->plan->umlName, Konvertierung::$write_debug);
		// As plan has multiple geometries in its source table, it must be checked against UML (which only holds one geom field)
		include(PLUGINS . 'xplankonverter/model/TypeInfo.php');
		$typeInfo = new TypeInfo($this->database);
		$uml_attribs = $typeInfo->getInfo($this->plan->tableName);

		$sql = "
			SELECT " . 
				implode(', ', array_column($uml_attribs, 'col_name')) . " 
			FROM
				xplan_gml." . $this->plan->tableName . "
			WHERE
				konvertierung_id = " . $this->get($this->identifier)
		;
		$this->debug->show('Objektabfrage sql: ' . $sql, Konvertierung::$write_debug);

		$sql_srid = "
				SELECT
					DISTINCT ST_SRID(raeumlichergeltungsbereich)
				FROM
					xplan_gml." . $this->plan->tableName . " 
				WHERE
					konvertierung_id = " . $this->get($this->identifier) . " 
				LIMIT 1
			";
		$result_srid = pg_query($this->database->dbConn, $sql_srid);
		// fallback input-epsg konvertierung
		$src_srid = !empty(pg_fetch_result($result_srid, 0, 0)) ? pg_fetch_result($result_srid, 0,0) : $this->get('output_epsg');
		$export_class->ogr2ogr_export($sql, '"ESRI Shapefile" -s_srs epsg:' . $src_srid . ' -t_srs epsg:' . $this->get('output_epsg') . ' -nlt MULTIPOLYGON', $path . $this->plan->umlName . '.shp', $this->database);
	}

	function create_export_file($file_type) {
		$path = $this->get_file_path($file_type);
		// -j for removing directory structure in .zip
		$cmd = ZIP_PATH . ' -j ' . $path . $file_type . '.zip ' . $path . '*';
		#echo 'cmd: ' . $cmd; exit;
		exec($cmd);

		$exportfile = $path . $file_type . '.zip';
		return $exportfile;
	}

	function send_export_file($exportfile, $contenttype) {
		header('Content-type: ' . $contenttype);
		header("Content-disposition:	attachment; filename=" . basename($exportfile));
		header("Content-Length: " . filesize($exportfile));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		readfile($exportfile);
	}

	function download_files_exists($dir) {
		$dir = $this->get_file_path($dir);
		$this->debug->show('Prüfe ob Dateien im Verzeichnis : ' . $dir . ' vorhanden sind: ', Konvertierung::$write_debug);
		$result = !(count(glob("$dir*")) === 0);
		$this->debug->show(($result ? 'ja' : 'nein'), Konvertierung::$write_debug);
		return $result;
	}

	function get_input_epgs_codes() {
		$sql = "
			SELECT
				unnest(enum_range(NULL::xplankonverter.epsg_codes)) AS epsg_code
		";
		$input_codes = array();
		$query = pg_query($this->database->dbConn, $sql);
		while ($rs = pg_fetch_assoc($query)) {
			$input_codes[] = $rs['epsg_code'];
		}
		$this->set('input_epgs_codes', $input_codes);
		return $input_codes;
	}

	function get_regeln($plan) {
		$this->debug->show('get_regeln', Konvertierung::$write_debug);

		$regeln = array();
		$regel = new Regel($this->gui);
		$regeln_ohne_bereich = $regel->find_where("
			konvertierung_id = {$this->get($this->identifier)} AND
			bereich_gml_id IS NULL
		");
		$this->debug->show(count($regeln_ohne_bereich) . ' Regeln ohne Bereich gefunden.', Konvertierung::$write_debug);

		foreach($this->get_bereiche($plan->get('gml_id')) AS $bereich) {
			$regeln_mit_bereich = $regel->find_where("bereich_gml_id = '{$bereich->get('gml_id')}'");
			$this->debug->show(count($regeln_mit_bereich) . ' Regeln mit Bereich gefunden.', Konvertierung::$write_debug);
			// needs to be merged with regeln to merge multiple bereiche
			$regeln = array_merge(
				$regeln,
				$regeln_ohne_bereich,
				$regeln_mit_bereich
			);
		}
		$this->debug->show('Insgesamt ' . count($regeln) . ' Regeln gefunden.', Konvertierung::$write_debug);

		foreach($regeln AS $regel) {
			$regel->konvertierung = $regel->get_konvertierung();
		}

		$this->debug->show(implode(
			'<br>',
			array_map(
				function($regel) {
					return '<br>id: ' . $regel->get($this->identifier) . '<br>sql: ' . $regel->get('sql');
				},
				$regeln
			)
		), Konvertierung::$write_debug);
		return $regeln;
	}

	/**
	 * Return plan of konvertierung, query first from database if not already assigned to plan attribute
	 * return false if not found, else plan
	 */
	function get_plan() {
		#echo 'Frage Plan für Konvertierung ' . $this->get($this->identifier) . ' ab.';
		if (!$this->plan) {
			$this->debug->show('get_plan with planart: ' . $this->get('planart') . ' for konvertierung: ' . $this->get($this->identifier), Konvertierung::$write_debug);
			$plan = new XP_Plan($this->gui, $this->get('planart'));
			$plan = $plan->find_where('konvertierung_id = ' . $this->get($this->identifier));
			$this->debug->show('found ' . count($plan) . ' Pläne', Konvertierung::$write_debug);
			if (count($plan) > 0) {
				$this->plan = $plan[0];
				$this->plan->get_center_coord();
				$this->plan->get_extent(OWS_SRS, 'konvertierung_id = ' . $this->get($this->identifier));
				$this->debug->show('get_plan assign first plan with planart: ' . $this->plan->planart . ' gml_id: ' . $this->plan->get('gml_id') . ' to Konvertierung.', Konvertierung::$write_debug);
			}
			else {
				$this->plan = false;
			}
		}
		return $this->plan;
	}

	function get_plan_file_name() {
		return ($this->get('uploaded_xplan_gml_file_name') ?: $this->config['plan_file_name']);
	}

	/**
	 * Diese Funktion liefert das Datum der Aktualität des Plans.
	 */
	function get_aktualitaetsdatum() {
		return $this->plan->get($this->get_plan_attribut_aktualitaet());
	}

	/**
	 * Liefert den Namen des Attributes in dem die Aktualität des Planes steht.
	 * Es wird der Reihe nach geprüft ob in den Attributen, die in $this->config['plan_attribut_aktualitaet'] stehen
	 * Datumsangaben stehen. Der Attributename in dem zuerst ein Datum gefunden wurde wird zurückgegeben.
	 * Wird kein Datum gefunden, wird der erste definierte Attributname aus $this->config['plan_attribut_aktualitaet']
	 * zurückgegeben.
	 */
	function get_plan_attribut_aktualitaet() {
		$attributes = explode(', ', $this->config['plan_attribut_aktualitaet']);
		$result = array_reduce(
			$attributes,
			function($carry, $attribute) {
				$attribute = trim($attribute);
				if ($carry['matched_attribute'] == NULL AND $carry['plan']->get($attribute) != '') {
					$carry['matched_attribute'] = $attribute;
				}
				return $carry;
			},
			array(
				'matched_attribute' => NULL,
				'plan' => $this->plan
			)
		);
		return $result['matched_attribute'] ?? $attributes[0];
	}

	function get_num_gmlas_tmp_plaene() {
		$sql = "
			SELECT
				count(*) AS num_plaene
			FROM
				xplan_gmlas_tmp_" . $this->gui->user->id . "." . $this->planart_to_planclass($this->get('planart')) . "
			";
		$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
		try {
			$ret = $this->database->execSQL($sql, 4, 1);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => $e
			);
		}
		$result = pg_fetch_assoc($ret[1]);
		if (!$ret['success']) {
			return $ret;
		}

		return array(
			'success' => true,
			'num_plaene' => $result['num_plaene']
		);
	}

	/**
	*	Erzeugt für alle Pläne aus dem Schema $table_schema eine Konvertierung in xplankonverter.konvertierungen sowie
	*	den Plan und Bereich in der Plan- und Bereichtabelle des Schemas xplan_gml je nach planart
	*	Falls eine konvertierung_id übergeben wird, wird nur der Name und die Beschreibung der schon vorhandenen Konvertierung
	*	überschrieben und keine neue Konvertierung angelegt.
	*	@params $table_schema string: Schema aus dem die Daten entnommen werden
	*	@params $plan_class string: Planart in der UML-Schreibweise mit Unterstrich, z.B. BP_Plan, FP_Plan, RP_Plan etc.
	*	@return array
	*		$success boolean: Erfolg oder Fehler
	*		$msg string: Fehlermeldung im Fehlerfall, sonst Erfolgsmeldung
	*/
	function create_plaene_from_gmlas($table_schema, $plan_class, $konvertierung_id = 0, $zusammenzeichnung = false) {
		$planartAbk = $this->config['planart_abk'];

		if ($konvertierung_id == 0) {
			# Anlegen der Konvertierung
			$sql = "
				INSERT INTO xplankonverter.konvertierungen (
					bezeichnung,
					status,
					stelle_id,
					user_id,
					geom_precision,
					gml_layer_group_id,
					epsg,
					output_epsg,
					input_epsg,
					planart,
					veroeffentlicht,
					beschreibung
				)
				SELECT
					gmlas.xplan_name AS bezeichnung,
					'erstellt' AS status,
					" . $this->gui->Stelle->id . " AS stelle_id,
					" . $this->gui->user->id . " AS user_id,
					15 AS geom_precision,
					null AS gml_layer_group_id,
					" . XPLANKONVERTER_DEFAULT_EPSG . "::text::xplankonverter.epsg_codes AS epsg,
					" . XPLANKONVERTER_DEFAULT_EPSG . "::text::xplankonverter.epsg_codes AS output_epsg,
					" . XPLANKONVERTER_DEFAULT_EPSG . "::text::xplankonverter.epsg_codes AS input_epsg,
					'" . $this->get('planart') . "' AS planart,
					false AS veroeffentlicht,
					gmlas.id AS beschreibung
				FROM
					" . $table_schema . "." . strtolower($plan_class) . " gmlas;
			";
		}
		else {
			$sql = "
				UPDATE
					xplankonverter.konvertierungen k
				SET
					beschreibung = gmlas.id,
					bezeichnung = gmlas.xplan_name
				FROM
					(
						SELECT
							id,
							xplan_name
						FROM
							" . $table_schema . "." . strtolower($plan_class) . "
						LIMIT 1
					) AS gmlas
				WHERE
					k.id = " . $konvertierung_id . ";
			";
		}
		# echo 'SQL zum updaten der Beschreibung und Bezeichnung der Konvertierung ' . $this->get($this->identifier) . ': ' . $sql;

		switch ($this->get('planart')) {
			case ('BP-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml." . strtolower($plan_class) . " (
						gml_id,
						user_id,
						konvertierung_id,
						name,
						nummer,
						internalid,
						beschreibung,
						kommentar,
						technherstelldatum,
						genehmigungsdatum,
						untergangsdatum,
						aendert,
						wurdegeaendertvon,
						erstellungsmassstab,
						bezugshoehe,
						raeumlichergeltungsbereich,
						verfahrensmerkmale,
						externereferenz,
						auslegungsenddatum,
						gemeinde,
						status,
						plangeber,
						rechtsstand,
						auslegungsstartdatum,
						traegerbeteiligungsstartdatum,
						aenderungenbisdatum,
						traegerbeteiligungsenddatum,
						verfahren,
						sonstplanart,
						planart,
						aufstellungsbeschlussdatum,
						technischerplanersteller,
						veraenderungssperre,
						veraenderungssperrebeschlussdatum,
						veraenderungssperredatum,
						veraenderungssperreenddatum,
						inkrafttretensdatum,
						durchfuehrungsvertrag,
						staedtebaulichervertrag,
						erschliessungsvertrag,
						rechtsverordnungsdatum,
						ausfertigungsdatum,
						satzungsbeschlussdatum,
						versionbaunvodatum,
						versionbaunvotext,
						versionbaugbdatum,
						versionbaugbtext,
						versionsonstrechtsgrundlagedatum,
						versionsonstrechtsgrundlagetext,
						hoehenbezug,
						gruenordnungsplan
					)
					SELECT
						trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid AS gml_id,
						" . $this->gui->user->id . " AS user_id,
						k.id AS konvertierung_id,
						gmlas.xplan_name AS name,
						gmlas.nummer AS nummer,
						gmlas.internalid AS internalid,
						gmlas.beschreibung AS beschreibung,
						gmlas.kommentar AS kommentar,
						to_char(gmlas.technherstelldatum, 'DD.MM.YYYY')::date AS technherstelldatum,
						to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY')::date AS genehmigungsdatum,
						to_char(gmlas.untergangsdatum, 'DD.MM.YYYY')::date AS untergangsdatum,
						CASE
							WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS aendert,
						CASE
							WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS wurdegeaendertvon,
						gmlas.erstellungsmassstab AS erstellungsmassstab,
						gmlas.bezugshoehe AS bezugshoehe,
						ST_Multi(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS raeumlichergeltungsbereich,
						CASE
							WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
								ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[]
							ELSE NULL
						END AS verfahrensmerkmale,
						CASE
							WHEN count_externeref > 0
							THEN externeref.externereferenz
							ELSE NULL
						END AS externereferenz,
						NULLIF(ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS auslegungsenddatum,
						CASE
							WHEN count_gemeinde > 0
							THEN gemeindelink.gemeinde
							ELSE NULL
						END AS gemeinde,
						(gmlas.status_codespace, gmlas.status, NULL)::xplan_gml." . $planartAbk . "_status AS status,
						(pg.name, pg.kennziffer)::xplan_gml.xp_plangeber AS plangeber,
						gmlas.rechtsstand::xplan_gml." . $planartAbk . "_rechtsstand AS rechtsstand,
						NULLIF(ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS auslegungsstartdatum,
						NULLIF(ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS traegerbeteiligungsstartdatum,
						to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY')::date AS aenderungenbisdatum,
						NULLIF(ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS traegerbeteiligungsenddatum,
						gmlas.verfahren::xplan_gml." . $planartAbk . "_verfahren AS verfahren,
						(gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml." . $planartAbk . "_sonstplanart AS sonstplanart,
						gmlas.planart::xplan_gml." . strtolower($plan_class) . "art[] AS planart,
						to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY')::date AS aufstellungsbeschlussdatum,
						gmlas.technischerplanersteller AS technischerplanersteller,
						gmlas.veraenderungssperre AS veraenderungssperre,
						gmlas.veraenderungssperrebeschlussdatum AS veraenderungssperrebeschlussdatum,
						gmlas.veraenderungssperredatum AS veraenderungssperredatum,
						gmlas.veraenderungssperreenddatum AS veraenderungssperreenddatum,
						gmlas.inkrafttretensdatum AS inkrafttretensdatum,
						gmlas.durchfuehrungsvertrag AS durchfuehrungsvertrag,
						gmlas.staedtebaulichervertrag AS staedtebaulichervertrag,
						gmlas.erschliessungsvertrag AS erschliessungsvertrag,
						gmlas.rechtsverordnungsdatum AS rechtsverordnungsdatum,
						gmlas.ausfertigungsdatum AS ausfertigungsdatum,
						gmlas.satzungsbeschlussdatum AS satzungsbeschlussdatum,
						gmlas.versionbaunvodatum AS versionbaunvodatum,
						gmlas.versionbaunvotext AS versionbaunvotext,
						gmlas.versionbaugbdatum AS versionbaugbdatum,
						gmlas.versionbaugbtext AS versionbaugbtext, 
						gmlas.versionsonstrechtsgrundlagedatum AS versionsonstrechtsgrundlagedatum,
						gmlas.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext,
						gmlas.hoehenbezug AS hoehenbezug,
						gmlas.gruenordnungsplan AS gruenordnungsplan
					FROM
						" . $table_schema . "." . strtolower($plan_class) . " gmlas JOIN
						xplankonverter.konvertierungen k ON gmlas.id = k.beschreibung LEFT JOIN
						(
							SELECT
								COUNT(*) AS count_gemeinde,
								gemeindelink_sub.parent_id,
								array_agg((
									g_sub.ags,
									g_sub.rs,
									g_sub.gemeindename,
									g_sub.ortsteilname
								)::xplan_gml.xp_gemeinde) AS gemeinde
							FROM
								" . $table_schema . "." . strtolower($plan_class) . "_gemeinde gemeindelink_sub LEFT JOIN
								" . $table_schema . ".xp_gemeinde g_sub ON gemeindelink_sub.xp_gemeinde_pkid = g_sub.ogr_pkid
							GROUP BY
								gemeindelink_sub.parent_id
						) gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
						(
							SElECT
								COUNT(*) AS count_externeref,
								externereferenzlink_sub.parent_id,
								array_agg((e_sub.georefurl,
										(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.art::xplan_gml.xp_externereferenzart,
										e_sub.informationssystemurl,
										e_sub.referenzname,
										e_sub.referenzurl,
										(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
										COALESCE(e_sub.beschreibung, e_sub.referenzname, e_sub.art, 'Dokument'),
										to_char(e_sub.datum, 'DD.MM.YYYY'),
										e_sub.typ::xplan_gml.xp_externereferenztyp,
										false
									)::xplankonverter.xp_spezexternereferenzauslegung) AS externereferenz
							FROM
								" . $table_schema . "." . strtolower($plan_class) . "_externereferenz externereferenzlink_sub LEFT JOIN
								" . $table_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
							GROUP BY
								externereferenzlink_sub.parent_id
						) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
						" . $table_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
						" . $table_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
						" . $table_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_plangeber pg ON gmlas.plangeber_xp_plangeber_pkid = pg.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_auslegungsstartdatum alsd ON gmlas.id = alsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_auslegungsenddatum aled ON gmlas.id = aled.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_traegerbeteiligungsstartdatum tbsd ON gmlas.id = tbsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_traegerbeteiligungsenddatum tbed ON gmlas.id = tbed.parent_id;
				";
				# ToDo weitere relationen von bp_plan_ ... aus gmlas_tmp_41 im Left join einbinden
				//  SELECT der Subquery mit spezexternereferenz statt spezexternereferenzauslegung
				// 	SElECT
				// 	COUNT(*) AS count_externeref,
				// 	externereferenzlink_sub.parent_id,
				// 	array_agg((e_sub.georefurl,
				// 			(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
				// 			e_sub.art::xplan_gml.xp_externereferenzart,
				// 			e_sub.informationssystemurl,
				// 			e_sub.referenzname,
				// 			e_sub.referenzurl,
				// 			(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
				// 			e_sub.beschreibung,
				// 			to_char(e_sub.datum, 'DD.MM.YYYY'),
				// 			e_sub.typ::xplan_gml.xp_externereferenztyp
				// 		)::xplan_gml.xp_spezexternereferenz) AS externereferenz
				// FROM
				// 	" . $table_schema . "." . strtolower($plan_class) . "_externereferenz externereferenzlink_sub LEFT JOIN
				// 	" . $table_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
				// GROUP BY
				// 	externereferenzlink_sub.parent_id

			} break;

			case ('FP-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml." . strtolower($plan_class) . " (
						gml_id, konvertierung_id, name, nummer, internalid, beschreibung, kommentar, technherstelldatum, genehmigungsdatum, untergangsdatum, aendert,
						wurdegeaendertvon, erstellungsmassstab, bezugshoehe, raeumlichergeltungsbereich, verfahrensmerkmale, externereferenz,
						auslegungsenddatum, gemeinde, status, sachgebiet, plangeber, rechtsstand, wirksamkeitsdatum, auslegungsstartdatum,
						traegerbeteiligungsstartdatum, entwurfsbeschlussdatum, aenderungenbisdatum, traegerbeteiligungsenddatum, verfahren, sonstplanart,
						planart, planbeschlussdatum, aufstellungsbeschlussdatum, zusammenzeichnung
					)
					SELECT
						trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid AS gml_id,
						k.id AS konvertierung_id,
						COALESCE(gmlas.xplan_name, 'F-Plan') AS name,
						COALESCE(gmlas.nummer, '') AS nummer,
						gmlas.internalid AS internalid,
						gmlas.beschreibung AS beschreibung,
						gmlas.kommentar AS kommentar,
						to_char(gmlas.technherstelldatum, 'DD.MM.YYYY')::date AS technherstelldatum,
						to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY')::date AS genehmigungsdatum,
						to_char(gmlas.untergangsdatum, 'DD.MM.YYYY')::date AS untergangsdatum,
						CASE
							WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS aendert,
						CASE
							WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS wurdegeaendertvon,
						gmlas.erstellungsmassstab AS erstellungsmassstab,
						gmlas.bezugshoehe AS bezugshoehe,
						ST_Multi(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS raeumlichergeltungsbereich,
						CASE
							WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
								ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[]
							ELSE NULL
						END AS verfahrensmerkmale,
						CASE
							WHEN count_externeref > 0
							THEN externeref.externereferenz
							ELSE NULL
						END AS externereferenz,
						NULLIF(ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS auslegungsenddatum,
						CASE
							WHEN count_gemeinde > 0
							THEN gemeindelink.gemeinde
							ELSE NULL
							END AS gemeinde,
						(gmlas.status_codespace, gmlas.status, NULL)::xplan_gml." . $planartAbk . "_status AS status,
						gmlas.sachgebiet AS sachgebiet,
						(pg.name, pg.kennziffer)::xplan_gml.xp_plangeber AS plangeber,
						gmlas.rechtsstand::xplan_gml." . $planartAbk . "_rechtsstand AS rechtsstand,
						to_char(gmlas.wirksamkeitsdatum, 'DD.MM.YYYY')::date AS wirksamkeitsdatum,
						NULLIF(ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS auslegungsstartdatum,
						NULLIF(ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS traegerbeteiligungsstartdatum,
						to_char(gmlas.entwurfsbeschlussdatum, 'DD.MM.YYYY')::date AS entwurfsbeschlussdatum,
						to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY')::date AS aenderungenbisdatum,
						NULLIF(ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS traegerbeteiligungsenddatum,
						gmlas.verfahren::xplan_gml." . $planartAbk . "_verfahren AS verfahren,
						(gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml." . $planartAbk . "_sonstplanart AS sonstplanart,
						gmlas.planart::xplan_gml." . strtolower($plan_class) . "art AS planart,
						to_char(gmlas.planbeschlussdatum, 'DD.MM.YYYY')::date AS planbeschlussdatum,
						to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY')::date AS aufstellungsbeschlussdatum,
						" . ($zusammenzeichnung ? 'true' : 'false') . " AS zusammenzeichnung
					FROM
						" . $table_schema . "." . strtolower($plan_class) . " gmlas JOIN
						xplankonverter.konvertierungen k ON gmlas.id = k.beschreibung LEFT JOIN
						(
							SELECT
								COUNT(*) AS count_gemeinde,
								gemeindelink_sub.parent_id,
								array_agg((
									g_sub.ags,
									g_sub.rs,
									g_sub.gemeindename,
									g_sub.ortsteilname
								)::xplan_gml.xp_gemeinde) AS gemeinde
							FROM
								" . $table_schema . "." . strtolower($plan_class) . "_gemeinde gemeindelink_sub LEFT JOIN
								" . $table_schema . ".xp_gemeinde g_sub ON gemeindelink_sub.xp_gemeinde_pkid = g_sub.ogr_pkid
							GROUP BY
								gemeindelink_sub.parent_id
						) gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
						(
							SElECT
								COUNT(*) AS count_externeref,
								externereferenzlink_sub.parent_id,
								array_agg((e_sub.georefurl,
										(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.art::xplan_gml.xp_externereferenzart,
										e_sub.informationssystemurl,
										e_sub.referenzname,
										e_sub.referenzurl,
										(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.beschreibung,
										to_char(e_sub.datum, 'DD.MM.YYYY'),
										e_sub.typ::xplan_gml.xp_externereferenztyp
									)::xplan_gml.xp_spezexternereferenz) AS externereferenz
							FROM
								" . $table_schema . "." . strtolower($plan_class) . "_externereferenz externereferenzlink_sub LEFT JOIN
								" . $table_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
							GROUP BY
								externereferenzlink_sub.parent_id
						) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
						" . $table_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
						" . $table_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
						" . $table_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_plangeber pg ON gmlas.plangeber_xp_plangeber_pkid = pg.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_auslegungsstartdatum alsd ON gmlas.id = alsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_auslegungsenddatum aled ON gmlas.id = aled.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_traegerbeteiligungsstartdatum tbsd ON gmlas.id = tbsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_traegerbeteiligungsenddatum tbed ON gmlas.id = tbed.parent_id;
				";
			} break;

			case ('SO-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml." . strtolower($plan_class) . " (
						gml_id, konvertierung_id, name, nummer, internalid, beschreibung, kommentar, technherstelldatum, genehmigungsdatum, untergangsdatum, aendert,
						wurdegeaendertvon, erstellungsmassstab, bezugshoehe, raeumlichergeltungsbereich, verfahrensmerkmale, externereferenz,
						plangeber,planart,gemeinde,versionbaugbdatum,versionbaugbtext,versionsonstrechtsgrundlagedatum,
						versionsonstrechtsgrundlagetext
					)
					SELECT
						trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid AS gml_id,
						k.id AS konvertierung_id,
						COALESCE(gmlas.xplan_name, 'F-Plan') AS name,
						COALESCE(gmlas.nummer, '') AS nummer,
						gmlas.internalid AS internalid,
						gmlas.beschreibung AS beschreibung,
						gmlas.kommentar AS kommentar,
						to_char(gmlas.technherstelldatum, 'DD.MM.YYYY')::date AS technherstelldatum,
						to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY')::date AS genehmigungsdatum,
						to_char(gmlas.untergangsdatum, 'DD.MM.YYYY')::date AS untergangsdatum,
						CASE
							WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS aendert,
						CASE
							WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS wurdegeaendertvon,
						gmlas.erstellungsmassstab AS erstellungsmassstab,
						gmlas.bezugshoehe AS bezugshoehe,
						ST_Multi(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS raeumlichergeltungsbereich,
						CASE
							WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
								ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[]
							ELSE NULL
						END AS verfahrensmerkmale,
						CASE
							WHEN count_externeref > 0
							THEN externeref.externereferenz
							ELSE NULL
						END AS externereferenz,
						(pg.name, pg.kennziffer)::xplan_gml.xp_plangeber AS plangeber,
						gmlas.planart::xplan_gml." . strtolower($plan_class) . "art AS planart,
						NULLIF(ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS auslegungsenddatum,
						CASE
							WHEN count_gemeinde > 0
							THEN gemeindelink.gemeinde
							ELSE NULL
							END AS gemeinde,
						b.versionbaugbdatum AS versionbaugbdatum,
						b.versionbaugbtext AS versionbaugbtext,
						b.versionsonstrechtsgrundlagedatum AS versionsonstrechtsgrundlagedatum,
						b.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext
					FROM
						" . $table_schema . "." . strtolower($plan_class) . " gmlas JOIN
						xplankonverter.konvertierungen k ON gmlas.id = k.beschreibung LEFT JOIN
						(
							SELECT
								COUNT(*) AS count_gemeinde,
								gemeindelink_sub.parent_id,
								array_agg((
									g_sub.ags,
									g_sub.rs,
									g_sub.gemeindename,
									g_sub.ortsteilname
								)::xplan_gml.xp_gemeinde) AS gemeinde
							FROM
								" . $table_schema . "." . strtolower($plan_class) . "_gemeinde gemeindelink_sub LEFT JOIN
								" . $table_schema . ".xp_gemeinde g_sub ON gemeindelink_sub.xp_gemeinde_pkid = g_sub.ogr_pkid
							GROUP BY
								gemeindelink_sub.parent_id
						) gemeindelink ON gmlas.id = gemeindelink.parent_id LEFT JOIN
						(
							SElECT
								COUNT(*) AS count_externeref,
								externereferenzlink_sub.parent_id,
								array_agg((e_sub.georefurl,
										(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.art::xplan_gml.xp_externereferenzart,
										e_sub.informationssystemurl,
										e_sub.referenzname,
										e_sub.referenzurl,
										(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.beschreibung,
										to_char(e_sub.datum, 'DD.MM.YYYY'),
										e_sub.typ::xplan_gml.xp_externereferenztyp
									)::xplan_gml.xp_spezexternereferenz) AS externereferenz
							FROM
								" . $table_schema . "." . strtolower($plan_class) . "_externereferenz externereferenzlink_sub LEFT JOIN
								" . $table_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
							GROUP BY
								externereferenzlink_sub.parent_id
						) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
						" . $table_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
						" . $table_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
						" . $table_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_plangeber pg ON gmlas.plangeber_xp_plangeber_pkid = pg.ogr_pkid;
				";
			} break;

			case ('RP-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml." . strtolower($plan_class) . " (
						amtlicherschluessel,
						bundesland,
						datumdesinkrafttretens,
						genehmigungsbehoerde,
						genehmigungsdatum,
						gml_id,
						konvertierung_id,
						name,
						nummer,
						internalid,
						beschreibung,
						kommentar,
						technherstelldatum,
						technischerplanersteller,
						untergangsdatum,
						aendert,
						wurdegeaendertvon,
						erstellungsmassstab,
						bezugshoehe,
						raeumlichergeltungsbereich,
						verfahrensmerkmale,
						externereferenz,
						auslegungstartdatum,
						auslegungenddatum,
						status,
						rechtsstand,
						traegerbeteiligungsstartdatum,
						traegerbeteiligungsenddatum,
						entwurfsbeschlussdatum,
						aenderungenbisdatum,
						verfahren,
						sonstplanart,
						planart,
						planbeschlussdatum,
						planungsregion,
						aufstellungsbeschlussdatum
					)
					SELECT
						gmlas.amtlicherschluessel AS amtlicherschluessel,
						gmlas.bundesland::xplan_gml.xp_bundeslaender[] AS bundesland,
						to_char(gmlas.datumdesinkrafttretens, 'DD.MM.YYYY')::date AS datumdesinkrafttretens,
						gmlas.genehmigungsbehoerde AS genehmigungsbehoerde,
						to_char(gmlas.genehmigungsdatum, 'DD.MM.YYYY')::date AS genehmigungsdatum,
						trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid AS gml_id,
						k.id AS konvertierung_id,
						COALESCE(gmlas.xplan_name, 'R-Plan') AS name,
						COALESCE(gmlas.nummer, '') AS nummer,
						gmlas.internalid AS internalid,
						gmlas.beschreibung AS beschreibung,
						gmlas.kommentar AS kommentar,
						to_char(gmlas.technherstelldatum, 'DD.MM.YYYY')::date AS technherstelldatum,
						gmlas.technischerplanersteller AS technischerplanersteller,
						to_char(gmlas.untergangsdatum, 'DD.MM.YYYY')::date AS untergangsdatum,
						CASE
							WHEN vpa.planname IS NOT NULL OR vpa.rechtscharakter IS NOT NULL OR vpa.nummer IS NOT NULL OR vpa.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpa.planname, vpa.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpa.nummer, vpa.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS aendert,
						CASE
							WHEN vpwgv.planname IS NOT NULL OR vpwgv.rechtscharakter IS NOT NULL OR vpwgv.nummer IS NOT NULL OR vpwgv.verbundenerplan_href IS NOT NULL THEN
								ARRAY[(vpwgv.planname, vpwgv.rechtscharakter::xplan_gml.xp_rechtscharakterplanaenderung, vpwgv.nummer, vpwgv.verbundenerplan_href)]::xplan_gml.xp_verbundenerplan[]
							ELSE NULL
						END AS wurdegeaendertvon,
						gmlas.erstellungsmassstab AS erstellungsmassstab,
						gmlas.bezugshoehe AS bezugshoehe,
						ST_Multi(ST_ForceRHR(gmlas.raeumlichergeltungsbereich)) AS raeumlichergeltungsbereich,
						CASE
							WHEN vm.xp_verfahrensmerkmal_vermerk IS NOT NULL OR vm.xp_verfahrensmerkmal_datum IS NOT NULL OR vm.xp_verfahrensmerkmal_signatur IS NOT NULL OR vm.xp_verfahrensmerkmal_signiert IS NOT NULL THEN
								ARRAY[(vm.xp_verfahrensmerkmal_vermerk, vm.xp_verfahrensmerkmal_datum, vm.xp_verfahrensmerkmal_signatur, vm.xp_verfahrensmerkmal_signiert)]::xplan_gml.xp_verfahrensmerkmal[]
							ELSE NULL
						END AS verfahrensmerkmale,
						CASE
							WHEN count_externeref > 0
							THEN externeref.externereferenz
							ELSE NULL
						END AS externereferenz,
						NULLIF(ARRAY[to_char(alsd.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS auslegungstartdatum,
						NULLIF(ARRAY[to_char(aled.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS auslegungenddatum,
						(gmlas.status_codespace, gmlas.status, NULL)::xplan_gml." . $planartAbk . "_status AS status,
						gmlas.rechtsstand::xplan_gml." . $planartAbk . "_rechtsstand AS rechtsstand,
						NULLIF(ARRAY[to_char(tbsd.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS traegerbeteiligungsstartdatum,
						NULLIF(ARRAY[to_char(tbed.value, 'DD.MM.YYYY')]::date[], '{NULL}') AS traegerbeteiligungsenddatum,
						to_char(gmlas.entwurfsbeschlussdatum, 'DD.MM.YYYY')::date AS entwurfsbeschlussdatum,
						to_char(gmlas.aenderungenbisdatum, 'DD.MM.YYYY')::date AS aenderungenbisdatum,
						gmlas.verfahren::xplan_gml." . $planartAbk . "_verfahren AS verfahren,
						(gmlas.sonstplanart_codespace, gmlas.sonstplanart, NULL)::xplan_gml." . $planartAbk . "_sonstplanart AS sonstplanart,
						gmlas.planart::xplan_gml." . $planartAbk . "_art AS planart,
						to_char(gmlas.planbeschlussdatum, 'DD.MM.YYYY')::date AS planbeschlussdatum,
						gmlas.planungsregion AS planungsregion,
						to_char(gmlas.aufstellungsbeschlussdatum, 'DD.MM.YYYY')::date AS aufstellungsbeschlussdatum
					FROM
						" . $table_schema . "." . strtolower($plan_class) . " gmlas JOIN
						xplankonverter.konvertierungen k ON gmlas.id = k.beschreibung LEFT JOIN
						(
							SElECT
								COUNT(*) AS count_externeref,
								externereferenzlink_sub.parent_id,
								array_agg((e_sub.georefurl,
										(e_sub.georefmimetype_codespace, e_sub.georefmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.art::xplan_gml.xp_externereferenzart,
										e_sub.informationssystemurl,
										e_sub.referenzname,
										e_sub.referenzurl,
										(e_sub.referenzmimetype_codespace, e_sub.referenzmimetype, NULL)::xplan_gml.xp_mimetypes,
										e_sub.beschreibung,
										to_char(e_sub.datum, 'DD.MM.YYYY'),
										e_sub.typ::xplan_gml.xp_externereferenztyp
									)::xplan_gml.xp_spezexternereferenz) AS externereferenz
							FROM
								" . $table_schema . "." . strtolower($plan_class) . "_externereferenz externereferenzlink_sub LEFT JOIN
								" . $table_schema . ".xp_spezexternereferenz e_sub ON externereferenzlink_sub.xp_spezexternereferenz_pkid = e_sub.ogr_pkid
							GROUP BY
								externereferenzlink_sub.parent_id
						) externeref ON gmlas.id = externeref.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_aendert_aendert aendertlink ON gmlas.id = aendertlink.parent_pkid LEFT JOIN
						" . $table_schema . ".aendert aendertlinktwo ON aendertlink.child_pkid = aendertlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpa ON aendertlinktwo.xp_verbundenerplan_pkid = vpa.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_wurdegeaendertvon_wurdegeaendertvon wurdegeaendertvonlink ON gmlas.id = wurdegeaendertvonlink.parent_pkid LEFT JOIN
						" . $table_schema . ".wurdegeaendertvon wurdegeaendertvonlinktwo ON wurdegeaendertvonlink.child_pkid = wurdegeaendertvonlinktwo.ogr_pkid LEFT JOIN
						" . $table_schema . ".xp_verbundenerplan vpwgv ON wurdegeaendertvonlinktwo.xp_verbundenerplan_pkid = vpwgv.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_verfahrensmerkmale_verfahrensmerkmale verfahrensmerkmalelink ON gmlas.id = verfahrensmerkmalelink.parent_pkid LEFT JOIN
						" . $table_schema . ".verfahrensmerkmale vm ON verfahrensmerkmalelink.child_pkid = vm.ogr_pkid LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_auslegungsstartdatum alsd ON gmlas.id = alsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_auslegungsenddatum aled ON gmlas.id = aled.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_traegerbeteiligungsstartdatum tbsd ON gmlas.id = tbsd.parent_id LEFT JOIN
						" . $table_schema . "." . strtolower($plan_class) . "_traegerbeteiligungsenddatum tbed ON gmlas.id = tbed.parent_id;
				";
			} break;
		}

		switch ($this->get('planart')) {
			case ('BP-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml.bp_bereich (
						gml_id, nummer, name, bedeutung, detailliertebedeutung, erstellungsmassstab, geltungsbereich, user_id, konvertierung_id, rasterbasis,
						versionbaunvodatum, versionbaugbtext, versionsonstrechtsgrundlagetext, versionbaunvotext, versionsonstrechtsgrundlagedatum, versionbaugbdatum, gehoertzuplan
					)
					SELECT
						trim(replace(lower(b.id), 'gml_', ''))::text::uuid AS id,
						b.nummer AS nummer,
						b.xplan_name AS name,
						b.bedeutung::xplan_gml.xp_bedeutungenbereich AS bedeutung,
						b.detailliertebedeutung AS detailliertebedeutung,
						b.erstellungsmassstab AS erstellungsmassstab,
						ST_Multi(ST_ForceRHR(st_transform(b.geltungsbereich, 25832))) AS geltungsbereich,
						" . $this->gui->user->id . " AS user_id,
						k.id AS konvertierung_id,
						trim(replace(lower(b.rasterbasis_href), '#gml_', ''))::text AS rasterbasis,
						b.versionbaunvodatum AS versionbaunvodatum,
						b.versionbaugbtext AS versionbaugbtext,
						b.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext,
						b.versionbaunvotext AS versionbaunvotext,
						b.versionsonstrechtsgrundlagedatum AS versionsonstrechtsgrundlagedatum,
						b.versionbaugbdatum AS versionbaugbdatum,
						trim(replace(lower(b.gehoertzuplan_pkid), 'gml_', ''))::text::uuid	AS gehoertzuplan
					FROM
						" . $table_schema . ".bp_bereich AS b JOIN
						xplankonverter.konvertierungen k ON b.gehoertzuplan_pkid = k.beschreibung;
				";
			} break;
			case ('FP-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml.fp_bereich (
						gml_id, nummer, name, bedeutung, detailliertebedeutung, erstellungsmassstab, geltungsbereich, user_id, konvertierung_id, rasterbasis,
						versionbaunvodatum, versionbaugbtext, versionsonstrechtsgrundlagetext, versionbaunvotext, versionsonstrechtsgrundlagedatum, versionbaugbdatum, gehoertzuplan
					)
					SELECT
						trim(replace(lower(b.id), 'gml_', ''))::text::uuid AS id,
						b.nummer AS nummer,
						b.xplan_name AS name,
						b.bedeutung::xplan_gml.xp_bedeutungenbereich AS bedeutung,
						b.detailliertebedeutung AS detailliertebedeutung,
						b.erstellungsmassstab AS erstellungsmassstab,
						ST_Multi(ST_ForceRHR(st_transform(b.geltungsbereich, 25832))) AS geltungsbereich,
						" . $this->gui->user->id . " AS user_id,
						k.id AS konvertierung_id,
						trim(replace(lower(b.rasterbasis_href), '#gml_', ''))::text AS rasterbasis,
						b.versionbaunvodatum AS versionbaunvodatum,
						b.versionbaugbtext AS versionbaugbtext,
						b.versionsonstrechtsgrundlagetext AS versionsonstrechtsgrundlagetext,
						b.versionbaunvotext AS versionbaunvotext,
						b.versionsonstrechtsgrundlagedatum AS versionsonstrechtsgrundlagedatum,
						b.versionbaugbdatum AS versionbaugbdatum,
						trim(replace(lower(b.gehoertzuplan_pkid), 'gml_', ''))::text::uuid	AS gehoertzuplan
					FROM
						" . $table_schema . ".fp_bereich AS b JOIN
						xplankonverter.konvertierungen k ON b.gehoertzuplan_pkid = k.beschreibung;
				";
			} break;
			case ('RP-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml.rp_bereich (
						gml_id,
						nummer,
						name,
						bedeutung,
						detailliertebedeutung,
						erstellungsmassstab,
						geltungsbereich,
						user_id,
						konvertierung_id,
						rasterbasis,
						versionbrog,
						versionbrogtext,
						versionlplg,
						versionlplgtext,
						geltungsmassstab,
						gehoertzuplan
					)
					SELECT
						trim(replace(lower(b.id), 'gml_', ''))::text::uuid AS gml_id,
						b.nummer AS nummer,
						b.xplan_name AS name,
						b.bedeutung::xplan_gml.xp_bedeutungenbereich AS bedeutung,
						b.detailliertebedeutung AS detailliertebedeutung,
						b.erstellungsmassstab AS erstellungsmassstab,
						ST_Multi(ST_ForceRHR(st_transform(b.geltungsbereich, 25832))) AS geltungsbereich,
						" . $this->gui->user->id . " AS user_id,
						k.id AS konvertierung_id,
						trim(replace(lower(b.rasterbasis_href), '#gml_', ''))::text AS rasterbasis,
						to_char(b.versionbrog, 'DD.MM.YYYY')::date AS versionbrog,
						b.versionbrogtext AS versionbrogtext,
						to_char(b.versionlplg, 'DD.MM.YYYY')::date AS versionlplg,
						b.versionlplgtext AS versionlplgtext,
						b.geltungsmassstab AS geltungsmassstab,
						trim(replace(lower(b.gehoertzuplan_pkid), 'gml_', ''))::text::uuid	AS gehoertzuplan
					FROM
						" . $table_schema . ".rp_bereich AS b JOIN
						xplankonverter.konvertierungen k ON b.gehoertzuplan_pkid = k.beschreibung;
				";
			} break;
			case ('SO-Plan') : {
				$sql .= "
					INSERT INTO xplan_gml.so_bereich (
						gml_id, nummer, name, bedeutung, detailliertebedeutung, erstellungsmassstab, geltungsbereich, user_id, konvertierung_id, rasterbasis,
						gehoertzuplan
					)
					SELECT
						trim(replace(lower(b.id), 'gml_', ''))::text::uuid AS id,
						b.nummer AS nummer,
						b.xplan_name AS name,
						b.bedeutung::xplan_gml.xp_bedeutungenbereich AS bedeutung,
						b.detailliertebedeutung AS detailliertebedeutung,
						b.erstellungsmassstab AS erstellungsmassstab,
						ST_Multi(ST_ForceRHR(st_transform(b.geltungsbereich, 25832))) AS geltungsbereich,
						" . $this->gui->user->id . " AS user_id,
						k.id AS konvertierung_id,
						trim(replace(lower(b.rasterbasis_href), '#gml_', ''))::text AS rasterbasis,
						trim(replace(lower(b.gehoertzuplan_pkid), 'gml_', ''))::text::uuid	AS gehoertzuplan
					FROM
						" . $table_schema . ".so_bereich AS b JOIN
						xplankonverter.konvertierungen k ON b.gehoertzuplan_pkid = k.beschreibung;
				";
			} break;
		}

		$sql .= "
			SELECT
				plan.gml_id
			FROM
				" . $table_schema . "." . strtolower($plan_class) . " gmlas JOIN
				xplan_gml." . strtolower($plan_class) . " AS plan ON trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid = plan.gml_id
		";

		# echo '<br>SQL mit Anweisung zum Anlegen der Pläne und der Bereiche: ' . $sql; exit;
/*
		return array(
			'success' => false,
			'msg' => $sql
		);
*/
		$ret = $this->database->execSQL($sql, 4, 0);
		if (!$ret['success']) {
			return array(
				'success' => false,
				'msg' => $ret['msg']
			);
		}

		$num_plaene = pg_num_rows($ret[1]);
		return array(
			'success' => true,
			'msg' => $num_plaene . ' ' . ($num_plaene > 1 ? 'Pläne' : 'Plan') . ' angelegt.'
		);
	}

	function get_bereiche($plan_id) {
		#echo 'get_bereiche';
		$bereich = new XP_Bereich($this->gui, $this->get('planart'));
		return $bereich->find_where("gehoertzuplan = '{$plan_id}'");
	}

	/**
	* Fragt die unique class names ab, die in der Konvertierung verwendet wurden.
	*/
	function get_class_names() {
		$this->debug->show('Konvertierung: get_class_names.', Konvertierung::$write_debug);
		$sql = "
			SELECT DISTINCT
				r.class_name
			FROM
				xplankonverter.regeln r LEFT JOIN
				xplan_gml." . $this->plan->planartAbk . "_bereich b ON r.bereich_gml_id::text = b.gml_id::text LEFT JOIN
				xplan_gml." . $this->plan->planartAbk . "_plan bp ON b.gehoertzuplan::text = bp.gml_id::text LEFT JOIN
				xplan_gml." . $this->plan->planartAbk . "_plan rp ON r.konvertierung_id = rp.konvertierung_id
			WHERE
				bp.konvertierung_id = " . $this->get($this->identifier) . " OR
				rp.konvertierung_id = " . $this->get($this->identifier) . "
		";
		#echo 'SQL zur Abfrage der Klassen-Namen, die zur Konvertierung gehören.' . $sql;

		$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
		$result = pg_fetch_all(
			pg_query($this->database->dbConn, $sql)
		);

		if ($result) {
			$class_names = array_map(
				function($row) {
					return $row['class_name'];
				},
				$result
			);
		}
		else {
			$class_names = array();
		}
		return $class_names;
	}
	
	/**
	* Fragt die unique textabschnitte ab, die in der Konvertierung verwendet wurden.
	*/
	function get_textabschnitt_names() {
		$this->debug->show('Konvertierung: get_textabschnitt_names.', Konvertierung::$write_debug);
		$sql = "
			SELECT * FROM (
				SELECT
					CASE WHEN EXISTS(SELECT 1 FROM xplan_gml.bp_textabschnitt ta WHERE ta.konvertierung_id = " . $this->get($this->identifier) . ")
					THEN 'BP_TextAbschnitt'
					END AS class_name
				UNION
				SELECT
					CASE WHEN EXISTS(SELECT 1 FROM xplan_gml.fp_textabschnitt ta WHERE ta.konvertierung_id = " . $this->get($this->identifier) . ")
					THEN 'FP_TextAbschnitt'
					END AS class_name
				UNION
				SELECT
					CASE WHEN EXISTS(SELECT 1 FROM xplan_gml.rp_textabschnitt ta WHERE ta.konvertierung_id = " . $this->get($this->identifier) . ")
					THEN 'RP_TextAbschnitt'
					END AS class_name
				UNION
				SELECT
					CASE WHEN EXISTS(SELECT 1 FROM xplan_gml.so_textabschnitt ta WHERE ta.konvertierung_id = " . $this->get($this->identifier) . ")
					THEN 'SO_TextAbschnitt'
					END AS class_name
			) AS result
			WHERE result.class_name IS NOT NULL
		";

		$this->debug->show('sql: ' . $sql, Konvertierung::$write_debug);
		$result = pg_fetch_all(
			pg_query($this->database->dbConn, $sql)
		);

		if ($result) {
			$textabschnitte_names = array_map(
				function($row) {
					return $row['class_name'];
				},
				$result
			);
		}
		else {
			$textabschnitte_names = array();
		}

		return $textabschnitte_names;
	}

	function set_status($new_status = '') {
		$this->debug->show('<br>Setze status in Konvertierung.', Konvertierung::$write_debug);
		if ($new_status == '') {
			$sql = "
				SELECT DISTINCT
					CASE
						WHEN p.gml_id IS NOT NULL OR r.id IS NOT NULL THEN true
						ELSE false
					END AS plan_or_regel_assigned
				FROM
					xplankonverter.konvertierungen k LEFT JOIN
					xplan_gml." . strtolower(substr($this->get('planart'), 0, 2)) . "_plan p ON k.id = p.konvertierung_id LEFT JOIN
					xplankonverter.regeln r ON k.id = r.konvertierung_id
				WHERE
					k.id = {$this->get($this->identifier)}
			";
			$this->debug->show('<br>Setze Status mit sql: ' . $sql, Konvertierung::$write_debug);
			$query = pg_query($this->database->dbConn, $sql);
			$result = pg_fetch_assoc($query);
			$plan_or_regel_assigned = $result['plan_or_regel_assigned'];

			$new_status = $this->get('status');
			if ($plan_or_regel_assigned == 't') {
				if ($this->get('status') == 'in Erstellung') {
					$new_status = 'erstellt';
				}
			}
			else {
				$new_status = 'in Erstellung';
			}
		}

		$this->set('status', $new_status);
		$this->update();
		/*
			UPDATE
				xplankonverter.konvertierungen
			SET
				status = new_state::xplankonverter.enum_konvertierungsstatus
			WHERE
				id = _konvertierung_id;
		*/
	}

	/**
	* Erzeugt eine Layergruppe vom Typ GML oder Shape und trägt die dazugehörige
	* gml_layer_group_id oder shape_layer_group_id in PG-Tabelle konvertierung ein.
	*
	*/
	function create_layer_group($layer_type) {
		$this->debug->show('Konvertierung create_layer_group layer_type: ' . $layer_type, Konvertierung::$write_debug);
		$layer_group_id = $this->get(strtolower($layer_type) . '_layer_group_id');
		if (empty($layer_group_id)) {
			$layer_group = new PgObject($this->gui, 'kvwmap', 'u_groups');
			if ($layer_type == 'GML') {
				$layer_group = $layer_group->find_by('gruppenname', 'XPlanung');
				$layer_group->create(array(
					'gruppenname' => 'XPlanung'
				));
			}
			else {
				$layer_group->create(array(
					'gruppenname' => $this->get('bezeichnung') . ' ' . $layer_type
				));
			}
			$this->set(strtolower($layer_type) . '_layer_group_id', $layer_group->get($this->identifier));
			$this->update();
		}
		return $this->get(strtolower($layer_type) . '_layer_group_id');
	}

	/**
	 * Löscht eine Layergruppe vom Typ GML oder Shape und trägt die dazugehörige
	 * gml_layer_group_id oder shape_layer_group_id in PG-Tabelle konvertierung ein.
	 *
	*/
	function delete_layer_group($layer_type) {
		$this->debug->show('delete_layer_group typ: ' . $layer_type, Konvertierung::$write_debug);
		$layer_group_id = $this->get(strtolower($layer_type) . '_layer_group_id');
		if (!empty($layer_group_id)) {
			$layer_group = new PgObject($this->gui, 'kvwmap', 'u_groups');
			$layer_group->set($this->identifier, $layer_group_id);
			$layer_group->identifier = 'id';
			$layer_group->identifier_type = 'integer';
			return $layer_group->delete();
		}
		else {
			return false;
		}
	}

	/*
	* Diese Funktion löscht alle zuvor für diese Konvertierung angelegten
	* XPlan GML Datensätze, Beziehungen und Validierungsergebnisse
	*/
	function reset_mapping() {
		$this->debug->show('Konvertierung: reset_mapping mit konvertierung_id: ' . $this->get($this->identifier), Konvertierung::$write_debug);
		# Lösche vorhandene Validierungsergebnisse der Konvertierung
		Validierungsergebnis::delete_by_id($this->gui, 'konvertierung_id', $this->get($this->identifier));

		# Lösche vorhandene Datenobjekte der Konvertierung
		# Überspringe leere Class-Names bei fehlerhaften Regeln
		$class_names = array_filter($this->get_class_names(), 'strlen');
		foreach($class_names AS $class_name) {
			# Lösche Relationen
			$sql = "
				DELETE FROM
					xplan_gml." . strtolower($class_name) . "
				WHERE
					konvertierung_id = $1
			;";
			$this->debug->show("Delete Objekte von {$class_name} with konvertierung_id " . $this->get($this->identifier) . ' und sql: ' . $sql . ' in reset Mapping.', Konvertierung::$write_debug);
			#echo '<br>sql: ' . $sql;
			$query = pg_query_params($this->database->dbConn, $sql, array($this->get($this->identifier)));
		}

		# Lösche vorhandene xplan_shapes Export-Dateien
		$file = XPLANKONVERTER_FILE_PATH . $this->get($this->identifier) . '/xplan_shapes.zip';
		if (file_exists($file)) unlink($file);
		array_map('unlink', glob($this->get_file_path('xplan_shapes') . '*'));
	}

	/*
	* Diese Funktion prüft die Konformitätsbedingungen für Plan und Bereichsobjekte und
	* führt das Mapping zwischen den Shape Dateien
	* und den in den Regeln definierten XPlan GML Features durch.
	* Jedes im Mapping erzeugte Feature bekommt eine eindeutige gml_id.
	* Darüber hinaus muss die Zuordnung zum überordneten Objekt
	* abgebildet werden. Das ist der Bereich oder die Konvertierung über die 
	* gml_id des objektes oder die konvertierung_id.
	* Derzeit umgesetzt in index.php xplankonverter_regeln_anwenden
	* $this->converter->regeln_anwenden($this->formvars['konvertierung_id']);
	*/
	function mapping() {
		$regeln = $this->get_regeln($this->plan);

		// validate Plan
		$validierung = new Validierung($this->gui);
		$planvalidierungen = $validierung->find_where("functionsname LIKE 'plan_attribute_has_value'");
		foreach($planvalidierungen AS $planvalidierung) {
			$planvalidierung->konvertierung_id = $this->get($this->identifier);
			$planvalidierung->plan_attribute_has_value();
		}

		// validate Bereiche
		$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'detaillierte_requires_bedeutung');
		$validierung->konvertierung_id = $this->get($this->identifier);
		$bereiche = $this->plan->get_bereiche();
		foreach ($bereiche AS $bereich) {
			$validierung->detaillierte_requires_bedeutung($bereich);
		}

		if (!empty($regeln)) {
			$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'regel_existiert');
			$validierung->konvertierung_id = $this->get($this->identifier);
			if ($validierung->regel_existiert($regeln)) {
				$success = true;
				foreach ($regeln AS $regel) {
					$result = $regel->validate($this);
					if (!$result) {
						$success = false;
					}
					else {
						$result = $regel->convert($this);
						// TODO: Fix this, currently no gids break the result
						/*if (!empty($result)) {
							if($regel->is_source_shape_or_gmlas($regel,	$this->get($this->identifier)) != 'gmlas') {
								$regel->rewrite_gml_ids($result);
							}
						}*/
					}
				}
				$alle_sql_ausfuehrbar = true;
				$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'alle_sql_ausfuehrbar');
				$validierung->konvertierung_id = $this->get($this->identifier);
				foreach ($regeln AS $regel) {
					if (!$validierung->sql_ausfuehrbar($regel)) {
						$alle_sql_ausfuehrbar = false;
					}
				}
				$validierung->alle_sql_ausfuehrbar($alle_sql_ausfuehrbar);

				if ($alle_sql_ausfuehrbar) {
					# Prüft die Konformitäten der Klassen der Konvertierung für die aktuelle Version
					#echo $this->get_version_from_ns_uri(XPLAN_NS_URI);
					foreach ($this->get_konformitaetsbedingungen($this->get_version_from_ns_uri(XPLAN_NS_URI)) AS $bedingung) {
						foreach ($bedingung['konformitaet']->validierungen AS $validierung) {
							$validierung->validiere_konformitaet($this->get($this->identifier), $bedingung);
						}
					}

					if (in_array($this->get('planart'), array('BP-Plan', 'FP-Plan', 'SO-Plan'))) {
						# Flächenschlussprüfung
						$this->clearTopology();
						# Create topology of plan objects
						$this->createTopology();
						$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'flaechenschluss_ueberlappungen');
						$validierung->konvertierung_id = $this->get($this->identifier);
						$validierung->flaechenschluss_ueberlappungen($this->plan);
						$validierung = Validierung::find_by_id($this->gui, 'functionsname', 'flaechenschluss_luecken');
						$validierung->konvertierung_id = $this->get($this->identifier);
						$validierung->flaechenschluss_luecken($this->plan);
					}
				}
			}
		}
	}

	/**
	 * Function validiert ob die hochgeladenen Dateien in Ordnung sind,
	 * sucht die Plandatei, benennt sie ggf. um und liefert den Plandateinamen zurück.
	 * @param String $upload_path
	 * @return array[
	 *	'success' => Boolean, Erfolgreich validiert oder nicht
	 *  'plan_file_name' => String, Bei Erfolg: Datei des hochgeladenen Planes
	 *	'msg' => String, Meldung im Fehler- oder Erfolgsfall.
	 * ]
	 */
	function validate_uploaded_files($upload_path) {
		$uploaded_files = getAllFiles($upload_path);
		$plan_file_name =  $this->config['plan_file_name'];


		if (count($uploaded_files) == 0) {
			return array(
				'success' => false,
				'msg' => 'Die hochgeladene ZIP-Datei enthält keine Dateien. Das Verzeichnis ' . $upload_path . ' ist leer!'
			);
		}

		if ($this->get('planart') == 'RP-Plan') {
			$uploaded_xplangml_file = current($uploaded_files); // get the first file only
			if ($uploaded_xplangml_file != $upload_path . $this->config['plan_file_name']) {
				// ToDo: Umstellen, so dass auch der Name von $uploaded_xplangml_file verwendet werden kann
				// und nicht mehr umbenannt werden muss
				rename($uploaded_xplangml_file, $upload_path . $this->config['plan_file_name']);
			}
		}

		if (!file_exists($upload_path . $this->config['plan_file_name'])) {
			// Suche eine GML-Datei im upload_path.
			// ToDo: Wenn es möglich ist einen beliebigen plan_file_name zu vergeben,
			// bräuchte man auch keinen Standardmäßigen plan_file_name in config mehr.
			$gml_files = glob($upload_path . '*.gml');
			if (count($gml_files) > 0) {
				$plan_file_name = basename($gml_files[0]);
			}
			else {
				// Weder $this->get_plan_file_name() noch eine Datei mit .gml gefunden
				$plan_file_name = $this->get_plan_file_name();
				return array(
					'success' => false,
					'msg' => 'Die hochgeladene ZIP-Datei enthält keine Datei mit dem Namen: '. $plan_file_name
				);
			}
		}

		return array(
			'success' => true,
			'plan_file_name' => $plan_file_name,
			'msg' => 'Hochgeladene Datei wurde auf dem Server gefunden.'
		);
	}

	/**
	*	This function validate a XPlanGML-File against the XPlanValidator at https://www.xplanungsplattform.de/xplan-validator/
	*	and write the report in xplankonverter database tables
	*/
	function xplanvalidator($gml_file) {
		$pathinfo = pathinfo($gml_file);
		$msg = array();
		if (!is_file($gml_file)) {
			return array(
				'success' => false,
				'msg' => 'GML-Datei ' . $gml_file . ' die validiert werden sollte, wurde nicht gefunden.'
			);
		}

		# ToDo pk: Hier vielleicht noch einen anderen sprechenderen Namen finden, ggf. der Planname aus der GML-Datei
		$url =	'https://www.xplanungsplattform.de/xplan-api-validator/xvalidator/api/v1/validate' . '?' .
						'name=' . $pathinfo['basename'] . '&' .
						'skipSemantisch=false' . '&' .
						'skipGeometrisch=false' . '&' .
						'skipFlaechenschluss=true' . '&' .
						'skipGeltungsbereich=false';

		$cmd = "curl -X 'POST' '" . $url	. "'-H 'accept: application/json' -H 'X-Filename: " . $pathinfo['basename'] . "' -H 'Content-Type: application/gml+xml' --data-binary @" . $gml_file;
		$this->debug->write("<br><b>Validierung der Datei : {$pathinfo['basename']} mit folgendem Befehl</b><br>{$cmd}", $debuglevel);
		exec($cmd, $output, $result_code);

    // $ch = curl_init($url);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_POST, true);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, Array('accept: application/json', 'X-Filename: ' . $pathinfo['basename'], 'Content-Type: text/gml+xml'));
		// curl_setopt($ch, CURLOPT_POSTFIELDS, Array('file' => new CURLFile($gml_file)));
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_VERBOSE, 1);
		// curl_setopt($ch, CURLOPT_HEADER, 1);
		// $response = curl_exec($ch);
    // $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// echo "Request:\n" . curl_getinfo($ch, CURLINFO_HEADER_OUT) . "\n";
		// echo "Response:\n$response\n";
		// curl_close($ch);
		// $this->debug->write("<br><b>Output der Validierung</b><br>{$response}", $debuglevel);
		// if ($httpCode != 400) {
		// 	return array(
		// 		'success' => false,
		// 		'msg' => 'Fehler bei der Abfrage am XPlanValidator!<br>HTTP-Fehlercode: ' . $httpCode . '<p>
		// 		Der Server der Leistelle liefert folgende Antwort:' .  utf8_encode($response) . '<p>
		// 		<br>Der Validator ist zur Zeit nicht erreichbar. Prüfen Sie die Verfügbarkeit unter <a href="https://www.xplanungsplattform.de/xplan-validator/">https://www.xplanungsplattform.de/xplan-validator/</a> und versuchen Sie es wieder wenn der Validator wieder läuft.'
		// 	);
		// }

		$msg = array();
		$result = $output[0];
		$report = json_decode($result, false);

		if (json_last_error() !== JSON_ERROR_NONE) {
			if (strpos($result, 'Http/1.1 Service Unavailable') !== false) {
				$msg[0] = 'Fehler bei der Abfrage am XPlanValidator!<br>Http/1.1 Service Unavailable<br>Der Validator ist zur Zeit nicht erreichbar. Prüfen Sie die Verfügbarkeit unter <a href="https://www.xplanungsplattform.de/xplan-validator/">https://www.xplanungsplattform.de/xplan-validator/</a> und versuchen Sie es wieder wenn der Validator wieder läuft.';
			}
			else if (strpos($result, 'HTTP Status 406 – Not Acceptable') !== false) {
				$msg[0] = 'Fehler bei der Abfrage am XPlanValidator!<br>HTTP Status 406 – Not Acceptable<br>Überprüfen Sie Ihre XPlanGML-Datei auf Wohlgeformtheit und Validität.';
			}
			else {
				$msg[0] = 'Fehler bei der Abfrage am XPlanValidator!<br>Der XPlanValidator ist wahrscheinlich derzeit nicht online. Bitte versuchen Sie den Upload zu einem späteren Zeitpunkt erneut.<br>Fehler: ' . print_r($output, true);
			}
			return array(
				'success' => false,
				'msg' => $msg[0]
			);
		}

		if (is_array($report->validationResult->geometrisch->errors) AND count($report->validationResult->geometrisch->errors) > 0) {
			$msg[] = 'Der XPlanValidator der Leitstelle liefert folgende geometrischen Fehlermeldungen: .' . implode("<br>", $report->validationResult->geometrisch->errors);
		}
		if (is_array($report->validationResult->geometrisch->warnings) AND count($report->validationResult->geometrisch->warnings) > 0) {
			$msg[] = 'Der XPlanValidator der Leitstelle liefert folgende geometrischen Warnungen: .' . implode("<br>", $report->validationResult->geometrisch->warnings);
		}
		if (is_array($report->validationResult->syntaktisch->messages) AND count($report->validationResult->syntaktisch->messages) > 0) {
			$msg[] = 'Der XPlanValidator der Leitstelle liefert folgende syntaktischen Fehlermeldungen: .' . implode("<br>", $report->validationResult->syntaktisch->messages);
		}

		if (count($msg) > 0) {
			# Write report to file in tmp dir
			$report_file = $pathinfo['filename'] . '_validation_report.json';
			file_put_contents(IMAGEPATH . $report_file, $result);
			$msg[] = '<a href="' . IMAGEURL . $report_file . '" target="_blank">vollständiger Validierungsbericht</a>';
			$msg[] = 'Sie können Ihr XPlanGML-Dokument <a href="https://www.xplanungsplattform.de/xplan-validator/" target="_blank" title="Link zum XPlanValidator der XPlanung Leitstelle">hier</a> prüfen.';
			$msg[] = 'Dort finden Sie auch die <a href="https://www.xplanungsplattform.de/xplan-validator/XPlanValidatorWeb-Benutzerhandbuch/index-xPlanValidator.html" target="_blank" title="Benutzerhandbuch für den XPlanValidator">Anleitung zur Validierung</a>.';
			return array(
				'success' => false,
				'msg' => implode("\n", $msg)
			);
		}

		return array(
			'success' => true,
			'msg' => 'Validierung der Datei ' . $pathinfo['basename'] . ' erfolgreich.',
			'report' => $report
		);
	}

	/**
	* Save the validation report in table xplanvalidator_reports and the
	* according results in table xplanvalidator_semantische_results
	* @param Array $report
	* @return Array
	*		success boolean: true if function works correctly, false if an error occured when writing in database
	*		valid boolean: returns if the report was valid or not
	*		msg string: error or success message
	*/
	function save_validation_report($filename, $report) {
		file_put_contents($this->get_file_path('xplanvalidator_reports') . $filename . '_validation_report.json', json_encode($report));

		$sql = "
			INSERT INTO xplankonverter.xplanvalidator_reports(
				konvertierung_id,
				version,
				filename,
				name,
				box_minx,
				box_miny,
				box_maxx,
				box_maxy,
				box_crs,
				datetime,
				valid,
				externalreferences,
				wmsurl,
				rulesmetadata_version,
				rulesmetadata_source,
				semantisch_valid,
				geometrisch_valid,
				geometrisch_errors,
				geometrisch_warnings,
				syntaktisch_valid,
				syntaktisch_messages
			) VALUES (
				" . $this->get($this->identifier) . ",
				'" . $report->version . "',
				'" . $report->filename . "',
				'" . $report->name . "',
				" . quote_or_null($report->bbox->minX) . ",
				" . quote_or_null($report->bbox->minY) . ",
				" . quote_or_null($report->bbox->maxX) . ",
				" . quote_or_null($report->bbox->maxY) . ",
				'" . $report->bbox->crs . "',
				'" . $report->date . "',
				" . ($report->valid ? 'true' : 'false') . ",
				" . ((is_array($report->externalReferences) AND count($report->externalReferences) > 0) ? "ARRAY['" . pg_escape_string(implode("', '", $report->externalReferences)) . "']" : "NULL") . ",
				'" . $report->wmsUrl . "',
				'" . $report->rulesMetadata->version . "',
				'" . $report->rulesMetadata->source . "',
				" . ($report->validationResult->semantisch->valid ? 'true' : 'false') . ",
				" . ($report->validationResult->geometrisch->valid ? 'true' : 'false') . ",
				" . ((is_array($report->validationResult->geometrisch->errors) AND count($report->validationResult->geometrisch->errors) > 0) ? "ARRAY['" . pg_escape_string(implode("', '", $report->validationResult->geometrisch->errors)) . "']" : "NULL") . ",
				" . ((is_array($report->validationResult->geometrisch->warnings) AND count($report->validationResult->geometrisch->warnings) > 0) ? "ARRAY['" . pg_escape_string(implode("', '", $report->validationResult->geometrisch->warnings)) . "']" : "NULL") . ",
				" . ($report->validationResult->syntaktisch->valid ? 'true' : 'false') . ",
				" . ((is_array($report->validationResult->syntaktisch->messages) AND count($report->validationResult->syntaktisch->messages) > 0) ? "ARRAY['" . pg_escape_string(implode("', '", $report->validationResult->syntaktisch->messages)) . "']" : "NULL") . "
			) RETURNING id
		";
		#echo '<br>SQL to create a validation report: ' . $sql;
		$msg[] = 'SQL to create a validation report: ' . $sql;
		$ret = $this->database->execSQL($sql);
		if (!$ret['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Eintragen der Ergebnisse des XPlan-Validators!<br>' . $ret['msg']
			);
		}
		$msg[] = 'XPlan-Validator-Report erfolgreich in Tabelle xplanvalidator_reports eingetragen.';
		$rs = pg_fetch_assoc($ret[1]);
		$values = array();
		foreach ($report->validationResult->semantisch->rules AS $rule) {
			$values[] = "(" . $rs['id'] . ",
				'" . $rule->name . "',
				" . ($rule->isValid ? 'true' : 'false') . ",
				'" . $rule->message . "',
				" . ((is_array($rule->invalidFeatures) AND count($rule->invalidFeatures) > 0) ? "ARRAY['" . implode("', '", $rule->invalidFeatures) . "']" : "NULL") . "
			)";
		}
		if (count($values) > 0) {
			$sql = "
				INSERT INTO xplankonverter.xplanvalidator_semantische_results(
					xplanvalidator_report_id,
					name,
					isvalid,
					message,
					invalidefeatures
				)
				VALUES
					" . implode(', ', $values) . "
			";
			#echo '<br>SQL to create the semantic results: ' . $sql; exit;
			$msg[] = 'SQL to create the semantic results: ' . $sql; 
			$ret = $this->database->execSQL($sql);
			if (!$ret['success']) {
				return array(
					'success' => false,
					'msg' => 'Fehler beim Eintragen der Ergebnisse des XPlan-Validators!<br>' . $ret['msg']
				);
			}
			$msg[] = 'Ergebnisse der semantischen Prüfung erfolgreich in Tabelle validation_results_semantisch eingetragen.';
		}
		return array(
			'success' => true,
			'valid' => ($report->valid ? true : false),
			'msg' => implode('<br>', $msg),
			'sql' => $sql
		);
	}

	/**
	* Return the xplan version number from the xplan_ns_uri constants as float
	*/
	function get_version_from_ns_uri($uri) {
		return floatval(implode('.', array_slice(explode('/', $uri), -2)));
	}

	/**
	* Return konformitaetsbedingungen der Klassen der Konvertierung in der angegebenen XPlanung-Version
	*/
	function get_konformitaetsbedingungen($version) {
		$sql = "
			SELECT
				name, konvertierung_id, nummer, version_von, version_bis, inhalt, bezeichnung
			FROM
				xplankonverter.konformitaeten_der_konvertierungen
			WHERE
				" . $version . " BETWEEN version_von::float AND coalesce(version_bis::float, 99999) AND
				konvertierung_id = " . $this->get($this->identifier) . "
		";
		$this->debug->show('sql to find konformitaetsbedingungen for konvertierung_id: ' . $this->get($this->identifier) . ' für XPlanung Version: ' . $version, false);
		$query = pg_query($this->database->dbConn, $sql);
		while ($rs = pg_fetch_assoc($query)) {
			$bedingungen[] = array(
				'class_name' => $rs['name'],
				'konformitaet' => Konformitaetsbedingung::find_by_id($this->gui, $rs['nummer'], $rs['version_von'])
			);
		}
		return $bedingungen;
	}


	/**
	* Clear the topology from all flaechenschlussobjekte of the plan
	*/
	function clearTopology() {
		# Lösche die Topologie der flaechenschlussobjekte des Planes
		$sql ="
			UPDATE xplankonverter.flaechenschlussobjekte
			SET topo = topology.clearTopoGeom(topo)
			WHERE
				konvertierung_id = " . $this->get($this->identifier) . "
		";
		#echo '<p>SQL zum Löschen der Topology: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim Löschen der Topologie!');
			return false;
		}

		# Lösche die flaechenschlussobjekte des Plans in temporärer Tabelle flaechenschlussobjekte
		$sql ="
			DELETE FROM xplankonverter.flaechenschlussobjekte
			WHERE
				konvertierung_id = " . $this->get($this->identifier) . "
		";
		#echo '<p>SQL zum Löschne die flaechenschlussobjekte: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim Löschen die flaechenschlussobjekte!');
			return false;
		}
	}

	/**
	* Creates a topology from all flaechenschlussobjekten of the plan
	*/
	function createTopology() {
		# Füge vorhandene Flächenschlussobjekte neu in Tabelle flächenschlussobjekte ein
		$sql = "
			INSERT INTO xplankonverter.flaechenschlussobjekte (gml_id, uuid, konvertierung_id, teilpolygon, teilpolygon_nr)
			SELECT
				gml_id,
				uuid,
				konvertierung_id,
				(st_dump(position)).geom AS teilpolygon,
				(st_dump(position)).path[1] AS teilpolygon_nr
			FROM
				xplan_gml.bp_flaechenschlussobjekt
			WHERE
				flaechenschluss AND
				(ebene = 0 OR ebene IS NULL) AND
				konvertierung_id = " . $this->get($this->identifier) . "
		";
		#echo '<p>SQL zur Erzeugung von Topology: ' . $sql;
		$result = $this->database->execSQL($sql, 4, 0);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim Anlegen der Topologie!');
			return false;
		}
		
		# Füge vorhandene Flächenschlussobjekte in BP_Geometrieobjekt neu in Tabelle flächenschlussobjekte ein
		# BP_Geometrieobjekt kann auch Flächenschlussobjekte enthalten
		$sql = "
			INSERT INTO xplankonverter.flaechenschlussobjekte (gml_id, uuid, konvertierung_id, teilpolygon, teilpolygon_nr)
			SELECT
				gml_id,
				uuid,
				konvertierung_id,
				(st_dump(position)).geom AS teilpolygon,
				(st_dump(position)).path[1] AS teilpolygon_nr
			FROM
				xplan_gml.bp_geometrieobjekt
			WHERE
				flaechenschluss AND
				(ebene = 0 OR ebene IS NULL) AND
				ST_GeometryType(position) ILIKE '%Polygon%' AND
				konvertierung_id = " . $this->get($this->identifier) . "
		";
		#echo '<p>SQL zur Erzeugung von Topology: ' . $sql;
		$result = $this->database->execSQL($sql, 4, 0);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim Anlegen der Topologie!');
			return false;
		}

		# Füge ein umschließendes Polygon des raeumlichen Geltungsbereiches zur Tabelle flaechenschlussobjekte hinzu
		$sql = "
			INSERT INTO xplankonverter.flaechenschlussobjekte (gml_id, uuid, konvertierung_id, teilpolygon, teilpolygon_nr)
	 		SELECT
				gml_id,
				'räumlicher Geltungsbereich Plan' AS uuid,
				konvertierung_id,
				(st_dump(position)).geom AS teilpolygon,
				(st_dump(position)).path[1] AS teilpolygon_nr
			FROM
			(
				SELECT
					gml_id,
					konvertierung_id,
					ST_Multi(ST_Difference(ST_SetSrid(ST_Buffer(Box2d(raeumlichergeltungsbereich)::geometry, 1), 25833), raeumlichergeltungsbereich)) AS position
				FROM
					xplan_gml.bp_plan
				WHERE
					konvertierung_id = " . $this->get($this->identifier) . "
			) plan
		";
		#echo '<p>SQL Füge ein umschließendes Polygon des raeumlichen Geltungsbereiches zur Tabelle flaechenschlussobjekte hinzu: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler beim hinzufügen eines umschließenden Polygon des raeumlichen Geltungsbereiches zur Tabelle flaechenschlussobjekte!');
			return false;
		}

		# Berechne die topologie der flaechenschlussobjekte
		$sql = "
			UPDATE
				xplankonverter.flaechenschlussobjekte
			SET
				topo = topology.toTopoGeom(teilpolygon, 'flaechenschluss_topology', 1, 0.002)
			WHERE
				konvertierung_id = " . $this->get($this->identifier) . "
		";
		#echo '<p>SQL zur Berechnung der topologie der flaechenschlussobjekte: ' . $sql;
		$result = $this->database->execSQL($sql, 0, 3);
		if (!$result['success']) {
			$this->gui->add_message('Fehler', 'Fehler bei der Berechnung der Topologie der flaechenschlussobjekte!');
			return false;
		}
	}

	function validierung_erfolgreich() {
		$sql = "
			SELECT DISTINCT
				status
			FROM
				xplankonverter.validierungsergebnisse
			WHERE
				status = 'Fehler' AND
				konvertierung_id = {$this->get($this->identifier)}
		";
		return pg_num_rows(pg_query($this->database->dbConn, $sql)) == 0;
	}

	/*
	 * Entfernt alles was mit der Konvertierung zusammenhängt und
	 * löscht sich am Ende selbst.
	 */
	function destroy() {
		$this->debug->show('Lösche Konvertierung', Konvertierung::$write_debug);

		$this->delete_upload_directory();

		# Lösche Regeln, die direkt zur Konvertierung gehören
		$regeln = array();
		$regel = new Regel($this->gui);
		$regeln = $regel->find_where("
			konvertierung_id = {$this->get($this->identifier)} AND
			bereich_gml_id IS NULL
		");
		foreach($regeln AS $regel) {
			$regel->destroy();
		}

		# Lösche zugehörige Postgres gmlas und shape schemas
		$this->destroy_gmlas_and_shape_schemas();
		# TODO Lösche Shape Layer + Gruppe

		# Lösche Plan der Konvertierung
		if ($this->plan) {
			$msg .= "\n " . $this->plan->umlName . ' ' . $this->plan->get('name') . ' gelöscht.';
			$this->plan->destroy();
		}

		# Lösche flaechenschlussobjekte-topology to avoid duplicates of uuid
		$this->clearTopology();

		# Lösche Konvertierung
		$this->delete();
		$this->debug->show($msg, Konvertierung::$write_debug);
	}

	function destroy_gmlas_and_shape_schemas() {
		# Destroy the xplan_shapes_ + $konvertierung_id if it exists
		# Destroy the xplan_gmlas: + $konvertierung_id schema if it exists
		$sql = "
			DROP SCHEMA IF EXISTS xplan_shapes_" .	$this->get($this->identifier) . " CASCADE;
			DROP SCHEMA IF EXISTS xplan_gmlas_" .	$this->get($this->identifier) . " CASCADE;
		";
		pg_query($this->database->dbConn, $sql);
	}

	function rename_xplan_gmlas() {
		$sql = "
			ALTER SCHEMA
				xplan_gmlas_tmp_" . $this->gui->user->id .
			" RENAME TO 
				xplan_gmlas_" . $this->get($this->identifier) . ";
		";
		#echo $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 0);
		return $ret;
	}

	function insert_textabschnitte($gml_extractor) {
		# Inserts all existing Textabschnitte if they exist (no regel as potential link to plan)
		$textabschnitte = array("bp_textabschnitt", "fp_textabschnitt", "so_textabschnitt", "rp_textabschnitt", "lp_textabschnitt");
		foreach ($textabschnitte as $textabschnitt) {
			if (strpos($textabschnitt, $this->plan->planartAbk) !== false) {
				if ($gml_extractor->check_if_table_exists_in_schema($textabschnitt, 'xplan_gmlas_' . $this->get($this->identifier))) {
					$gml_extractor->insert_into_textabschnitt($textabschnitt, $this->get($this->identifier), $this->gui->user->id);
				}
			}
		}
		return array(
			'success' => true,
			'msg' => 'Textabschnitte erfolgreich eingelesen'
		);
	}

	function insert_geltungsbereiche($gml_extractor) {
		$schema = $gml_extractor->this->gmlas_schema;
		$table = 'fp_plan';
		$sql = "
			INSERT INTO xplankonverter.geltungsbereiche (
				konvertierung_id,
				plan_gml_id,
				plan_name,
				plan_nummer,
				stand,
				geom
			)
			SELECT
				" . $this->get($this->identifier) . ",
				split_part(id, '_', 2) AS plan_gml_id,
				xplan_name AS plan_name,
				nummer,
				COALESCE(" . $this->config['plan_attribut_aktualitaet'] .") AS stand,
				ST_Multi(raeumlichergeltungsbereich)
			FROM
				" . $gml_extractor->gmlas_schema . "." . strtolower($this->gui->plan_class) . "
		";
		#echo $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 0);
		return $ret;
	}

	function plan_exists($table_schema) {
		$sql = "
			SELECT
				p.gml_id
			FROM
				xplan_gml." . strtolower($this->gui->plan_class) . " p JOIN
				" . $table_schema . "." . strtolower($this->gui->plan_class) . " gmlas ON
					p.gml_id = trim(replace(lower(gmlas.id), 'gml_', ''))::text::uuid
		";
		#echo 'SQL zur Abfrage ob der Plan schon existiert: ' . $sql;
		return (pg_num_rows(pg_query($this->database->dbConn, $sql)) > 0);
	}

	/**
	 * Erzeugt die Metadatendokumente für die Beschreibung
	 * des Datensatzes des Plans der zu dieser Konvertierung gehört
	 * sowie je einen zur Beschreibung des Darstellungs- und Downloaddienstes.
	 * @params $md metadata Es können Metadatendokumente zu einem Plan einer einzelnen Konvertierung erstellt werden
	 * oder zu dem Gesamtdatensatz und den Diensten aller Pläne in einem xplan_gml - Schema. Das hängt davon ab
	 * welche Metadaten in $md übergeben werden.
	 * @return array of strings Die die Dokumente enthalten
	 */
	function create_metadata_documents($md) {
		$this->plan = $this->get_plan();
		$plan = $this->plan;
		$konvertierungen = Konvertierung::find_konvertierungen(
			$this->gui,
			$this->get('planart'),
			$this->plan_class
		);
		#echo '<br>' . $plan->get('gemeinde');
		# Setzen der Metadaten für die Metadatendokumente
		if (count($konvertierungen['published']) == 0) {
			# Noch keine Zusammenzeichnung vorhanden entnehme die uuids von der neuen Zusammenzeichnung
			$md->set('uuids', $this->get_metadata_uuids());
		}
		else {
			# Entnehme die uuids von der alten Zusammenzeichnung
			$md->set('uuids', $konvertierungen['published'][0]->get_metadata_uuids());
			$this->set_metadata_uuids($md->get('uuids'));
		}

		$current_time = time();

		$md->set('stellendaten', $this->gui->Stelle->getstellendaten_full_contact());
		// md_date muss immer auf das aktuelle Datum gesetzt werden,
		// da das Harvesting von GDI-NI nur stattfindet, wenn hier ein unterschiedlicher Wert gesetzt wird
		$md->set('md_date', date('Y-m-d', $current_time));
		// $md->set('md_date', en_date($this->get_aktualitaetsdatum()));
		// $md->set('md_date', en_date($this->get_letztes_aktualisierungsdatum_gebietstabelle()));
		$md->set('date_de', $this->get_aktualitaetsdatum());
		//$md->set('date_de', $this->plan->get('wirksamkeitsdatum'));
		$md->set('date_title', 'Datum');
		$md->set('id_cite_title', $md->get('stellendaten')['ows_title'] . ' (Zusammenzeichnung)');
		//$md->set('id_cite_title', $plan->get('name'));
		
		$abstract_dataset = 'Dieser Geodatensatz beinhaltet den Flächennutzungsplan der in der Überschrift angegebenen Kommune des Landes Niedersachsen in einem standardisierten Format. Eine rechtlich verbindliche Auskunft zur Flächennutzungsplanung erteilt jedoch ausschließlich die zuständige Einheits- oder Samtgemeinde als Trägerin der Flächennutzungsplanung. Die Daten beinhalten mindestens einen Layer pro XPlanung-Klasse, basierend auf dem Datenaustauschformat XPlanGML. 

Die hier vorliegende Zusammenzeichnung beinhaltet den aktuellen Stand der rechtswirksamen Flächennutzungsplanung, der gegebenenfalls in den ursprünglich aufgestellten Flächennutzungsplan alle inzwischen rechtswirksam erfolgten Änderungen und Berichtigungen einarbeitet, so dass es ein Gesamtplanwerk ergibt. Die XPlanGML-Dateien wurden im Rahmen des Projektes PlanDigital erstellt bzw. veröffentlicht und werden über die Plattform PlanDigital (https://testportal-plandigital.de/kvwmap/index.php) für die Träger der Flächennutzungsplanung zugangsbeschränkt bereitgestellt. Das angegebene Datum der kontinuierlichen Aktualisierung bezieht sich auf die letzte technische Aktualisierung des Geodatensatzes bzw. der Dienste, die möglicherweise keine Änderung der Inhalte bedeutet. Die Veröffentlichung aktualisierter Daten sollte mindestens einmal jährlich erfolgen. 
In der Plattform/Testportal PlanDigital wurde in den Dienstmetadaten von der zuständigen Kommune folgende Aktualität angegeben: ';
		$abstract_dataset .= $md->get('stellendaten')['ows_abstract'];
		$abstract_dataset .= '. Das angegebene Veröffentlichungsdatum soll das Datum der Rechtskraft des Plans oder der letzten Änderung sein; diese Information wird der XPlanGML entnommen.';
			
		$abstract_viewservice = 'Dieser Darstellungsdienst (Web Map Service oder kurz WMS) stellt die Zusammenzeichnung des Flächennutzungsplans der in der Überschrift angegebenen Kommune des Landes Niedersachsen in einer landesweit einheitlichen Visualisierung bereit.
Eine rechtlich verbindliche Auskunft erteilt jedoch ausschließlich die zuständige Einheits- oder Samtgemeinde als Trägerin der Flächennutzungsplanung. Hierbei handelt es sich um einen Gebrauchsdienst der Zusammenzeichnungen von Planelementen des Flächennutzungsplans der in der Überschrift angegebenen Kommune mit mindestens einem Layer pro XPlanung-Klasse, basierend auf dem Datenaustauschformat XPlanGML. Es handelt sich explizit nicht um einen XPlanung-konformen Dienst, da er nicht dem XPlanung-Schema entspricht. Stattdessen wird ein eigenes, abgeflachtes Schema verwendet. 

Die Zusammenzeichnung beinhaltet den aktuellen Stand der rechtswirksamen Flächennutzungs-planung, der in den ursprünglich aufgestellten Flächennutzungsplan alle inzwischen rechtswirksam erfolgten Änderungen und Berichtigungen einarbeitet, so dass es ein Gesamtplanwerk ergibt. Die Grenzen der Geltungsbereiche von Flächennutzungsplan-Änderungen und Berichtigungen sind im Layer „Geltungsbereiche“ zusammengefasst. Die Daten wurden im Rahmen des Projektes PlanDigital erstellt bzw. veröffentlicht und werden durch die Plattform PlanDigital (https://testportal-plandigital.de/kvwmap/index.php) für die Träger der Flächennutzungsplanung zugangsbeschränkt bereitgestellt. 
Das angegebene Datum der kontinuierlichen Aktualisierung bezieht sich auf die letzte technische Aktualisierung des Geodatensatzes bzw. der Dienste, die möglicherweise keine Änderung der Inhalte bedeutet. Die Veröffentlichung aktualisierter Daten sollte mindestens einmal jährlich erfolgen. In der Plattform/Testportal PlanDigital wurde in den Dienstmetadaten von der zuständigen Kommune folgende Aktualität angegeben: ';
		$abstract_viewservice .= $md->get('stellendaten')['ows_abstract'];
		$abstract_viewservice .= '. Das angegebene Veröffentlichungsdatum soll das Datum der Rechtskraft des Plans oder der letzten Änderung sein; diese Information wird der XPlanGML entnommen.';
		
		$abstract_downloadservice = 'Dieser Downloaddienst (Web Feature Service oder kurz WFS) stellt die Zusammenzeichnung des Flächennutzungsplans der in der Überschrift angegebenen Kommune des Landes Niedersachsen in einer landesweit einheitlichen Visualisierung bereit.
Eine rechtlich verbindliche Auskunft erteilt jedoch ausschließlich die zuständige Einheits- oder Samtgemeinde als Trägerin der Flächennutzungsplanung. Hierbei handelt es sich um einen an XPlanung angelehnten Gebrauchsdienst der Zusammenzeichnungen von Planelementen des Flächennutzungsplans der in der Überschrift angegebenen Kommune mit mindestens einem Layer pro XPlanung-Klasse. Es handelt sich explizit nicht um einen XPlanung-konformen Dienst, da er nicht dem XPlanung-Schema entspricht. Stattdessen wird ein eigenes, abgeflachtes Schema verwendet. 

Die Zusammenzeichnung beinhaltet den aktuellen Stand der rechtswirksamen Flächennutzungs-planung, der in den ursprünglich aufgestellten Flächennutzungsplan alle inzwischen rechtswirksam erfolgten Änderungen und Berichtigungen einarbeitet, so dass es ein Gesamtplanwerk ergibt. Die Grenzen der Geltungsbereiche von Flächennutzungsplan-Änderungen und Berichtigungen sind im Layer „Geltungsbereiche“ zusammengefasst. Die Daten wurden im Rahmen des Projektes PlanDigital erstellt bzw. veröffentlicht und werden durch die Plattform PlanDigital (www.testportal-plandigital.de) bereitgestellt. 
Das angegebene Datum der kontinuierlichen Aktualisierung bezieht sich auf die letzte technische Aktualisierung des Geodatensatzes bzw. der Dienste, die möglicherweise keine Änderung der Inhalte bedeutet. Die Veröffentlichung aktualisierter Daten sollte mindestens einmal jährlich erfolgen. In der Plattform/Testportal PlanDigital wurde in den Dienstmetadaten von der zuständigen Kommune folgende Aktualität angegeben: ';
		$abstract_downloadservice .= $md->get('stellendaten')['ows_abstract'];
		$abstract_downloadservice .= '. Das angegebene Veröffentlichungsdatum soll das Datum der Rechtskraft des Plans oder der letzten Änderung sein; diese Information wird der XPlanGML entnommen.';
		
		$abstract_zusatz = ' Es handelt sich um einen Gebrauchsdienst der Zusammenzeichnung von Planelementen mit je einem Layer pro XPlanung-Klasse. Das ' . ucfirst($md->get('date_title')) . " der letzten Änderung ist der " . $md->get('date_de') . '. Die Umringe der Änderungspläne sind im Layer Geltungsbereiche zusammengefasst.';
		$md->set('id_abstract', array(
			'dataset' => $abstract_dataset,
			'viewservice' => $abstract_viewservice,
			'downloadservice' => $abstract_downloadservice
		));
		$md->set('date_title', $this->get_plan_attribut_aktualitaet());
		$md->set('id_cite_date', en_date($this->get_aktualitaetsdatum()));
		// id_cite_date should be set to aktualisierungsdatum for metadataportal niedersachsen
		//$md->set('id_cite_date', en_date($this->get_letztes_aktualisierungsdatum_gebietstabelle()));
		
		//Auf Wunsch von ArL's/GDI-NI wurde der Identifier für alle Pläne auf den Namespace Plandigital angepasst
		$md->set('namespace', $md->get('stellendaten')['ows_namespace']);
		
		$md->set('version', $this->get_version_from_ns_uri(XPLAN_NS_URI));
		$md->set('extents', $plan->extents);
		$md->set('service_layer_name', sonderzeichen_umwandeln($plan->get('name')));
		$md->set('onlineresource', URL . 'ows/' . $this->gui->Stelle->id . '/fplan?');
		$md->set('download_name', 'Download der XPlan-GML Dateien');
		$md->set('download_url', URL . APPLVERSION . 'index.php?go=xplankonverter_download_uploaded_xplan_gml&amp;konvertierung_id=' . $this->get_id());
		$md->set('search_name', 'Suche im UVP-Portal Niedersachsen');
		$md->set('search_url', 'https://uvp.niedersachsen.de/kartendienste?layer=blp&amp;N=' . $this->plan->center_coord['lat'] . '&amp;E=' . $this->plan->center_coord['lon'] . '&amp;zoom=13');
		$md->set('dataset_browsegraphic', URL . APPLVERSION . 'custom/graphics/Vorschau_Datensatz.png');
		$md->set('viewservice_browsegraphic', $md->get('onlineresource') . "Service=WMS&amp;Request=GetMap&amp;Version=1.1.0&amp;Layers=" . $md->get('service_layer_name') . "&amp;FORMAT=image/png&amp;SRS=EPSG:" . $md->get('stellendaten')['epsg_code'] . "&amp;BBOX=" . implode(',', $md->get('extents')[$md->get('stellendaten')['epsg_code']]) . "&amp;WIDTH=300&amp;HEIGHT=300");
		$md->set('downloadservice_browsegraphic', URL . APPLVERSION . 'custom/graphics/Vorschau_Downloadservice.png');
		$md->set('geographicIdentifier', $plan->get_regionalschluessel());
		$md->set('withRegionalKeyword', false);

		// echo '<br>metadata: ' . print_r($md->data, true);
		$metaDataCreator = new MetaDataCreator($md);
		return array(
			'metaDataGeodatensatz' => $metaDataCreator->createMetadataGeodatensatz(),
			'metaDataDownload' => $metaDataCreator->createMetadataDownload(),
			'metaDataView' =>  $metaDataCreator->createMetadataView()
		);
	}

	function get_metadata_uuids() {
		$uuids = array();
		foreach (array('dataset', 'viewservice', 'downloadservice') AS $type) {
			if ($this->get('metadata_' . $type . '_uuid') == '') {
				$this->set_metadata_uuid($type);
			}
			$uuids['metadata_' . $type . '_uuid'] = $this->get('metadata_' . $type . '_uuid');
		}
		return $uuids;
	}

	function set_metadata_uuid($type) {
		$uuid = uuid();
		$result = $this->update_attr(array("metadata_" . $type . "_uuid = '" . $uuid . "'"));
		$this->set('metadata_' . $type . '_uuid', $uuid);
		return $result;
	}

	function set_metadata_uuids($uuids) {
		$attributes = array();
		foreach ($uuids AS $key => $value) {
			$attributes[] = $key . " = '" . $value . "'";
			$this->set($key, $value);
		}
		$result = $this->update_attr($attributes);
		return $result;
	}

	function set_uploaded_xplan_gml_file_name($uploaded_xplan_gml_file_name) {
		$result = $this->update_attr(array("uploaded_xplan_gml_file_name = '" . $uploaded_xplan_gml_file_name . "'"));
		$this->set('uploaded_xplan_gml_file_name', $uploaded_xplan_gml_file_name);
		return $result;
	}

	function get_arl_email() {
		$sql = "
			SELECT
				ko.stelle_id,
				email_arl
			FROM
				xplankonverter.konvertierungen ko JOIN
				plandigital.stelle_to_arl sa ON ko.stelle_id = sa.stelle_id
			WHERE
				ko.id = " . $this->get_id() . "
			LIMIT 1
		";
		#echo $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 0);
		$rs = pg_fetch_assoc($ret[1]);
		return $rs['email_arl'];
	}

	function send_notification($msg) {
		$from_name = 'XPlan-Server PlanDigital';
		$from_email = 'info@testportal-plandigital.de';
		$to_email = $this->get_arl_email();
		$cc_email = 'peter.korduan@gdi-service.de';
		$reply_email = null;
		$subject = 'Update in Plandigital';
		$message 	= "Sehr geehrte Damen und Herren,\r\n\r\n";
		$message .= $msg . "\r\n";
		$attachment = '';
		$mode = 'sendEmail async';
		$smtp_server = 'smtp.ionos.de';
		$smtp_port = '587';
		if (mail_att($from_name, $from_email, $to_email, $cc_email, $reply_email, $subject, $message, $attachement, $mode, $smtp_server, $smtp_port)) {
			return array(
				'success' => true,
				'msg' => 'Benachrichtigung an den Systemadministrator versendet.'
			);
		}
		else {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Versenden der E-Mail zum Update der Zusammenzeichnung!'
			);
		}
	}
	
	/*
	* Die Funktion liefert das Aktualitätsdatum aus der Gemeindeverbandstabelle.
	* Das Aktualitätsdatum wird beim Upload auf das Datum des Uploads gesetzt.
	* Das Datum kann aber auch händisch über die Administration (z.b. von den ARLS) bearbeitet werden.
	* Falls kein Datum gesetzt ist, wird das Aktualitätsdatum verwendet.
	*/
	function get_letztes_aktualisierungsdatum_gebietstabelle() {
		$sql = "
			SELECT
				gv.letzte_aktualisierung,zs.konvertierung_id
			FROM
				gebietseinheiten.gemeindeverbaende AS gv
			LEFT JOIN
				xplankonverter.zusammenzeichnung_der_stellen zs ON gv.stelle_id = zs.stelle_id
			WHERE
				zs.konvertierung_id = " . $this->get_id() . ";";
		#echo $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 0);
		$rs = pg_fetch_assoc($ret[1]);
		if($rs['letzte_aktualisierung'] != '' AND $rs['letzte_aktualisierung'] != NULL) {
			return $rs['letzte_aktualisierung'];
		} else {
			return $this->get_aktualitaetsdatum();
		}							
	}
	
	function update_letztes_aktualisierungsdatum_gebietstabelle() {
		$sql ="
			UPDATE gebietseinheiten.gemeindeverbaende gemvb
			SET letzte_aktualisierung = now()::date
			FROM (
				SELECT
					gv.gvb_name,
					gv.stelle_id AS idderstelle
				FROM
					gebietseinheiten.gemeindeverbaende AS gv
					LEFT JOIN xplankonverter.zusammenzeichnung_der_stellen zs ON gv.stelle_id = zs.stelle_id
				WHERE zs.konvertierung_id = " . $this->get_id() . "
			) AS sub
			WHERE sub.gvb_name = gemvb.gvb_name
			AND sub.idderstelle = gemvb.stelle_id
		";
		#echo '<p>SQL zum Update der Gebietseinheiten-Tabelle Datum letzte_aktualisierung: ' . $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 0);
		return $ret;
	}

	/*
	* Checks if the geometry of the plan-geltungsbereich is similar to the geometry (95% area equality)
	* in gebietseinheiten in the temporary_gmlas_table
	* This check makes sure to disqualify geometries that only include just one small change (e.g. Berichtigung/Aenderung)
	* i.e. if failed, the uploaded file is not a full but at best only a partial zusammenzeichnung
	* this also prevents uploads to the wrong stelle/administration
	*/
	function is_geltungsbereich_gebietseinheiten_area_similar($schema_tmp, $plan_class_tmp) {
		$sql = "
			SELECT
				(ST_area(gv.geom_25832)/p.plan_area > 0.95 AND ST_area(gv.geom_25832)/p.plan_area < 1.15) AS is_95_equal
			FROM
				gebietseinheiten.gemeindeverbaende gv,
				(
					SELECT ST_Area(raeumlichergeltungsbereich) AS plan_area
					FROM " . $schema_tmp . "." . strtolower($plan_class_tmp) . "
					ORDER BY ST_Area(raeumlichergeltungsbereich) DESC LIMIT 1
				) AS p
			WHERE stelle_id = " . $this->get('stelle_id');
		//$this->debug->show('Check if Geltungsbereich is 95% area-equivalent to gebietseinheit with sql: ' . $sql, Konvertierung::$write_debug);
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 0);
		$rs = pg_fetch_assoc($ret[1]);
		if($rs['is_95_equal'] == '' OR $rs['is_95_equal'] == NULL OR $rs['is_95_equal'] == 'f') {
			$rs['is_95_equal'] = false;
		}
		if($rs['is_95_equal'] == 't') {
			$rs['is_95_equal'] = true;
		}
		return $rs['is_95_equal'];
		
		
		/*
		$plan_or_regel_assigned = $result['plan_or_regel_assigned'];
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 0);
		$rs = pg_fetch_assoc($ret[1]);
		if($rs['is_95_equal'] != '' AND $rs['is_95_equal'] != NULL) {
			return $rs['is_95_equal'];
		} else {
			return false;
		}
		*/
	}
}
?>
