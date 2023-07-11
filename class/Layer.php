<?php
class Layer extends MyObject {

	static $write_debug = false;

	function __construct($gui) {
		parent::__construct($gui, 'layer');
		$this->stelle_id = $gui->stelle->id;
		$this->identifier = 'Layer_ID';
		$this->geometry_types = array('Point', 'LineString', 'Polygon');
	}

	public static	function find($gui, $where, $order = '') {
		$layer = new Layer($gui);
		return $layer->find_where($where, $order);
	}

	public static	function find_by_id($gui, $id) {
		$layer = new Layer($gui);
		return $layer->find_by('Layer_ID', $id);
	}

	public static	function find_by_name($gui, $name) {
		$layer = new Layer($gui);
		$layers = $layer->find_where("Name LIKE '" . $name . "'");
		return $layers[0];
	}

	public static function find_by_obergruppe_und_name($gui, $obergruppe_id, $layer_name) {
		$layer = new Layer($gui);
		$result = $layer->find_by_sql(
			array(
				'select' => 'l.*',
				'from' => "
					u_groups g JOIN
					layer l ON (g.id = l.Gruppe)",
				'where' => "
					g.obergruppe = " . $obergruppe_id . " AND
					l.Name = '" . $layer_name . "'"
			)
		);
		return $result[0];
	}

	/**
	* This function return the layer id's of the duplicates of a layer
	* @param mysql_connection object
	* @param integer $duplicate_from_layer_id The layer id from witch the others are duplicates
	* @param array(integer) The layer_ids of the duplicates
	*/
	public static function find_by_duplicate_from_layer_id($database, $duplicate_from_layer_id) {
		$duplicate_layer_ids = array();
		$sql =	"
			SELECT
				`Layer_ID`
			FROM
				`layer`
			WHERE
				`duplicate_from_layer_id` = " . $duplicate_from_layer_id . "
				AND `Layer_ID` != `duplicate_from_layer_id`
		";
		# letzte Where Bedinung, damit keine Entlosschleifen entstehen beim Aufruf von update_layer falls
		# Layer_ID fälschlicherweise identisch sein sollte mit duplicate_layer_id was nicht passieren sollte
		# wenn das Layerformular genutzt wurde.
		#echo	MyObject::$write_debug ? 'Layer find_by_duplicate_from_layer_id sql:<br> ' . $sql : '';
		$ret = $database->execSQL($sql, 4, 1, true);
		if (!$ret['success']) {
			$database->gui->add_message('error', $ret[1]);
		}
		else {
			while ($rs = $database->result->fetch_assoc()) {
				$duplicate_layer_ids[] = $rs['Layer_ID'];
			}
		}
		return $duplicate_layer_ids;
	}

	function get_maintable_attributes() {
		$sql = "
			SELECT
				attr.table_schema,
				attr.table_name,
				attr.att_name,
				at.typname,
				at.typcategory,
				attr.is_array,
				atc.relname AS dtd_table_name
			FROM
				(
					SELECT
						cn.nspname table_schema,
						c.relname table_name,
						a.attname att_name,
						CASE WHEN t.typcategory = 'A' THEN te.oid ELSE t.oid END typid,
						t.typcategory = 'A' is_array
					FROM
						pg_catalog.pg_class c JOIN
						pg_catalog.pg_type ct ON c.reltype = ct.oid JOIN
						pg_catalog.pg_namespace cn ON c.relnamespace = cn.oid JOIN
						pg_catalog.pg_attribute a ON c.oid = a.attrelid JOIN
						pg_catalog.pg_type t ON a.atttypid = t.oid JOIN
						pg_catalog.pg_namespace tn ON t.typnamespace = tn.oid LEFT JOIN
						pg_catalog.pg_type te ON t.typelem = te.oid
					WHERE
						a.attnum > 0 AND
						NOT a.attisdropped
				) attr JOIN
				pg_type at ON attr.typid = at.oid LEFT JOIN
				pg_class atc ON at.typrelid = atc.oid
			WHERE
				attr.table_schema LIKE '" . $this->get('schema') . "' AND
				attr.table_name LIKE '" . $this->get('maintable') . "'
			ORDER BY
				attr.table_name,
				attr.att_name
		";
		#echo 'SQL zur Abfrage der Attribute: ' . $sql;
		$mapDB = new db_mapObj($this->gui->Stelle->id, $this->gui->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->get($this->identifier), $this->gui->Stelle->pgdbhost);
		$ret = $layerdb->execSQL($sql, 4, 0);
		if (!$ret['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler bei der Abfrage der Tabellenattribute. Fehler: ' . $ret['msg']
			);
		}

		while ($attr = pg_fetch_assoc($ret['query'])) {
			$this->maintable_attributes[] = $attr;
		}

		return array(
			'success' => true,
			'msg' => 'Maintable Attribute erfolgreich abgefragt.',
			'maintable_attributes' => $this->maintable_attributes
		);
	}

	/*
	* Diese Funktion legt vom aktuellen layer Objekt einen neuen Layer an
	* mit der übergebenen Layergruppe sowie alle seine zugehörigen Klassen und layer_attributes.
	* Vom Layer verwendete Styles und Labels werden wiederverwendet.
	* @return Layer Das kopierte Layerobjekt
	*/
	function copy($attributes) {
		$success = true;
		$this->debug->show('<p>Clone Templatelayer: ' . $this->get($this->identifier), Layer::$write_debug);
		$new_layer = clone $this;
		unset($new_layer->data['Layer_ID']);
		foreach ($attributes AS $key => $value) {
			$new_layer->set($key, $value);
		}
		$new_layer->create();
		$new_layer_id = $new_layer->get($new_layer->identifier);

		if (!empty($new_layer_id)) {
			$this->debug->show('<p>Copiere die Klassen des Template layers für neuen Layer id: ' . $new_layer_id, Layer::$write_debug);
			$this->copy_classes($new_layer_id);
			$this->debug->show('<p>Copiere die layer_attributes des Template layers für neuen Layer id: ' . $new_layer_id, Layer::$write_debug);
			$this->copy_layer_attributes($new_layer_id);
		}
		return $new_layer;
	}

	/*
	* Kopiere die Klassen des Layers mit anderer Layer_id
	*/
	function copy_classes($new_layer_id) {
		foreach(LayerClass::find($this->gui, 'Layer_id = ' . $this->get('Layer_ID')) AS $layer_class) {
			$this->debug->show('Copy class: ' . $layer_class->get('Name') . ' mit layer id: ' . $this->get('Layer_ID') . ' => ' . $new_layer_id, Layer::$write_debug);
			$layer_class->copy($new_layer_id);
		}
	}

	function copy_layer_attributes($new_layer_id) {
		foreach(LayerAttribute::find($this->gui, 'Layer_id = ' . $this->get('Layer_ID')) AS $attribute) {
			$this->debug->show('Copy Attribute: ' . $attribute->get('name') . ' mit neuer layer id: ' . $this->get('Layer_ID') . ' => ' . $new_layer_id, Layer::$write_debug);
			$attribute->copy($new_layer_id);
		}
	}

	/*
	* Function return true, if table of this layer is used at least in on other layer
	* It searches for layers with same maintable and schema
	* or schema.maintable used in Data exclude it self
	* @return boolean true if at least one other layer uses the table els false
	*/
	function tableUsedFromOtherLayers() {
		$data = $this->data;
		$layers = $this->find_where("
			(
				(
					`maintable` = '" . $this->get('maintable') . "' AND
					`schema` = '" . $this->get('schema') . "'
				) OR
				`Data` LIKE '%" . $this->get('schema') . "." . $this->get('maintable') . "%'
			) AND
			`Layer_ID` != " . $this->get($this->identifier) . "
		");
		$this->data = $data;
		return (count($layers) > 0);
	}

	function delete() {
		#echo '<br>Class Layer Method delete';
		$ret = parent::delete();
		if (MYSQLVERSION > 412) {
			parent::reset_auto_increment();
		}
		return $ret;
	}

	function get_subform_layers() {
		include_once(CLASSPATH . 'LayerAttribute.php');
		$subform_layer_ids = array_unique(
			array_map(
				function($attribute) {
					return explode(',', $attribute->get('options'))[0];
				},
				LayerAttribute::find($this->gui, "Layer_ID = " . $this->get('Layer_ID') . " AND form_element_type LIKE 'SubForm%PK'")
			)
		);
		if (count($subform_layer_ids) > 0) {
			return Layer::find(
				$this->gui,
				"Layer_ID IN (" . implode(', ', $subform_layer_ids) . ')'
			);
		}
		else {
			return array();
		}
	}

	function get_parentform_layers() {
		include_once(CLASSPATH . 'LayerAttribute.php');
		$parentform_layer_ids = array_unique(
			array_map(
				function($attribute) {
					return $attribute->get('layer_id');
				},
				LayerAttribute::find($this->gui, "Layer_ID != " . $this->get('Layer_ID') . " AND options LIKE '" . $this->get('Layer_ID') . ",%' AND form_element_type LIKE 'SubForm%PK'")
			)
		);
		if (count($parentform_layer_ids) > 0) {
			return Layer::find(
				$this->gui,
				"Layer_ID IN (" . implode(', ', $parentform_layer_ids) . ')'
			);
		}
		else {
			return array();
		}
	}

	function get_edit_link_list($layers, $anchor = '') {
		return '<ul><li>' . implode(
			'</li><li>',
			array_map(
				function($layer) use ($anchor) {
					return '<a title="Layereditor anzeigen" href="index.php?go=Layereditor&selected_layer_id=' . $layer->get('Layer_ID') . '#' . $anchor . '" target="_blank">' . $layer->get('Name') . ' (ID: ' . $layer->get('Layer_ID') . ')</a>';
				},
				$layers
			)
		) . '</li></ul>';
	}

	function get_group_name() {
		include_once(CLASSPATH . 'LayerGroup.php');
		$group = LayerGroup::find_by_id($this->gui, $this->get('Gruppe'));
		if ($group->get('obergruppe') != '') {
			$obergroup = LayerGroup::find_by_id($this->gui, $group->get('obergruppe'));
			return $obergroup->get('Gruppenname') . '|' . $group->get('Gruppenname');
		}
		else {
			return $group->get('Gruppenname');
		}
	}

	function get_baselayers_def($stelle_id) {
		$this->debug->show('<p>Layer->get_baselayers_def for stelle_id: ' . $stelle_id, MyObject::$write_debug);
		#echo '<p>get_baselayer_def for Layer: ' . $this->get('Name');

		include_once(CLASSPATH . 'LayerClass.php');
		include_once(CLASSPATH . 'LayerAttribute.php');

		$layerAttributes = new stdClass();
		foreach (LayerAttribute::find_visible($this->gui, $stelle_id, $this->get('Layer_ID')) AS $attr) {
			$key = $attr->get('name');
			$value = ($attr->get('alias') == '' ? $attr->get('name') : $attr->get('alias'));
			$layerAttributes->$key = $value;
		}
		$classes = LayerClass::find($this->gui, 'Layer_ID = ' . $this->get('Layer_ID'));
		$legendgraphic = URL . APPLVERSION . (count($classes) > 0 ? $classes[0]->get('legendgraphic') : 'graphics/leer.gif');
		$layerdef = (Object) array(
			'img' => $this->get('icon'),
			'label' => ($this->get('alias') != '' ? $this->get('alias') : $this->get('Name')),
			'options' => $this->get_baselayer_options(),
			'shortLabel' => $this->get('Name'),
			'img' => $legendgraphic,
			'url' => $this->get_baselayer_url()
		);
		return $layerdef;
	}

	function get_baselayer_options() {
		if (strpos($this->get('Data'), '{') === 0) {
			$data = json_decode($this->get('Data'));
			$data->options->attribution = $this->get('datasource');
			return $data->options;
		}
		else {
			return (Object) array(
				'attribution' => $this->get('datasource')
			);
		}
	}

	function get_baselayer_url() {
		if ($this->get('Data') == '') {
			return $this->get('connection');
		}
		if (strpos($this->get('Data'), '{') === 0) {
			$data = json_decode($this->get('Data'));
			return $data->url;
		}
		else {
			return $this->get('Data');
		}
	}

	function get_overlays_def($stelle_id) {
		$this->debug->show('<p>Layer->get_overlays_def for stelle_id: ' . $stelle_id, MyObject::$write_debug);
		#echo '<p>get_overlays_def for Layer: ' . $this->get('Name');
		include_once(CLASSPATH . 'LayerClass.php');
		include_once(CLASSPATH . 'LayerAttribute.php');

		$layerAttributes = new stdClass();
		foreach (LayerAttribute::find_visible($this->gui, $stelle_id, $this->get('Layer_ID')) AS $attr) {
			$key = $attr->get('name');
			$value = ($attr->get('alias') == '' ? $attr->get('name') : $attr->get('alias'));
			$layerAttributes->$key = $value;
		}

		switch ($this->get('connectiontype')) {
			case 6 : { # WFS-Layer werden exportiert wie PostGIS Layer
				$type = 'GeoJSON';
				$url = URL . APPLVERSION . 'index.php';
				$params = (Object) array(
					'gast' =>(int)$stelle_id,
					'go' => 'Daten_Export_Exportieren',
					'Stelle_ID' => (int)$stelle_id,
					'selected_layer_id' => (int)$this->get('Layer_ID'),
					'export_format' =>	'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name')
				);
			} break;
			case 7 : { # WMS-Layer
				$type = 'WMS';
				$url = explode('?', $this->get('connection'))[0];
				$params = '';
				$options = (Object) array(
					'crs' => 'EPSG4326',
					'version' => get_first_word_after($this->get('connection'), 'version=', ' ', '&'),
					'layers' => get_first_word_after($this->get('connection'), 'layers=', ' ', '&'),
					'format' => 'image/png',
					'transparent' => true,
					'attribution' => $this->get('dataowner_name'),
					'opacity' => $this->opacity / 100
				);
			} break;
			case 9 : { # PostGIS-Layer
				$type = 'GeoJSON';
				$url = URL . APPLVERSION . 'index.php';
				$params = (Object) array(
					'gast' => (int)$stelle_id,
					'go' => 'Daten_Export_Exportieren',
					'Stelle_ID' => (int)$stelle_id,
					'selected_layer_id' => (int)$this->get('Layer_ID'),
					'export_format' =>	'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name')
				);
			} break;
			default : { # currently same as PostGIS-Layer
				$type = 'GeoJSON';
				$url = URL . APPLVERSION . 'index.php';
				$params = (Object) array(
					'gast' => (int)$stelle_id,
					'go' => 'Daten_Export_Exportieren',
					'Stelle_ID' => (int)$stelle_id,
					'selected_layer_id' => (int)$this->get('Layer_ID'),
					'export_format' =>	'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name')
				);
			}
		}

		$classitem = $this->get('classitem');
		$datentyp = $this->get('Datentyp');

		$layerdef = (Object) array(
			'thema' => $this->get_group_name(),
			'label' => ($this->get('alias') != '' ? $this->get('alias') : $this->get('Name')),
			'abstract' => $this->get('kurzbeschreibung'),
			'contactOrganisation' => $this->get('datasource'),
			'contactPersonName' => $this->get('dataowner_name'),
			'contactEMail' => $this->get('dataowner_email'),
			'contactPhon' => $this->get('dataowner_tel'),
			'actuality' => $this->get('uptodateness'),
			'actualityCircle' => $this->get('updatecycle'),
			'type' => $type,
			'geomType' => array('Point', 'Linestring', 'Polygon', 'Raster', 'Annotation', 'Query', 'Circle', 'Tileindex', 'Chart')[$this->get('Datentyp')],
			'backgroundColor' => '#c1ffd8',
			'infoAttribute' => ($this->get('labelitem') != '' ? $this->get('labelitem') : $this->get('oid')),
			'url' => $url,
			'params' => $params,
			'options' => $options,
			'classes' => array_map(
				function($class) use ($classitem, $datentyp) {
					return $class->get_layerdef($classitem, $datentyp);
				},
				LayerClass::find($this->gui, 'Layer_ID = ' . $this->get('Layer_ID'), 'legendorder')
			),
#			'icon' => (Object) array(
#				'iconUrl' => 'images/Haus.svg',
#				'iconSize' => array(30, 30),
#				'iconAnchor' => array(7, 0),
#				'popupAnchor' => array(0, 0)
#			),
			'hideEmptyLayerAttributes' => true,
			'layerAttributes' => $layerAttributes
		);

		if ($this->get('processing') != '') {
			$processing = explode(';', $this->get('processing'));
			if (count($processing) > 0) {
				$layerdef->processing = (Object) array();
				foreach ($processing AS $process) {
					$parts = explode('=', $process);
					switch ($parts[0]) {
						case ('CHART_TYPE') : {
							$layerdef->processing->chart_type = $parts[1];
						} break;
						case ('CHART_SIZE') : {
							$layerdef->processing->style = (Object) array(
								'radius' => $parts[1],
								'fillOpacity' => 0.6,
								"strokeOpacity" => 0.2,
								"strokeWeight" => 3
							);
						} break;
					}
				}
			}
		}
		if ($this->minScale != '') {
			$layerdef->minScale = (int)$this->minScale;
		}
		if ($this->maxScale != '') {
			$layerdef->maxScale = (int)$this->maxScale;
		}
		return $layerdef;
	}

	function get_name($name_col = 'Name') {
		return $this->get($name_col . (($name_col == 'Name' AND $this->gui->user->rolle->language != 'german') ? '_' . $this->gui->user->rolle->language : ''));
	}

	function write_mapserver_templates($ansicht = 'Tabelle') {
		$layer_id = $this->get($this->identifier);
		$mapDB = new db_mapObj($this->gui->Stelle->id, $this->gui->user->id);
		$layerdb = $mapDB->getlayerdatabase($layer_id, '');
		$all_data_attributes = $mapDB->getDataAttributes(
			$layerdb,
			$layer_id,
			array(
				'use_generic_data_sql' => ($this->get('write_mapserver_templates') == 'generic')
			)
		);

		$data_attributes = array_filter(
			array_slice($all_data_attributes, 0, -2),
			function($data_attribute) {
				return $data_attribute['type'] != 'geometry';
			}
		);

		$query_attributes = $mapDB->read_layer_attributes($layer_id, $layerdb, NULL);
		$query_attribute_aliases = array();

		for ($i = 0; $i < count($query_attributes['name']); $i++) {
			$query_attribute_aliases[$query_attributes['name'][$i]] = $query_attributes['alias' . ($this->gui->user->rolle->language != 'german' ? '_' . $this->gui->user->rolle->language : '')][$i];
		}

		$data_attribute_names = array_map(
			function($data_attribute) use ($query_attribute_aliases) {
				$data_attribute_name = $data_attribute['name'];
				$data_attribute_alias = (
					(
						array_key_exists(
							$data_attribute_name,
							$query_attribute_aliases
						) AND
						$query_attribute_aliases[$data_attribute_name] != $data_attribute_name
					) ? $query_attribute_aliases[$data_attribute_name] : '');
				return array(
					'name' => $data_attribute_name,
					'alias' => $data_attribute_alias ?: $data_attribute_name
				);
			},
			$data_attributes
		);

		if (count($data_attribute_names) > 0) {
			$template_dir = WMS_MAPFILE_PATH . 'templates/';
			if (!is_dir($template_dir)) {
				mkdir($template_dir, 0770, true);
			}

			$fp = fopen($template_dir . $this->get_name() . '_head.html', "w");
			#if ($this->gui->user->id == 3) echo "Schreibe Datei " . $template_dir . $this->get_name() . '_head.html';
			fwrite($fp, $this->get_wms_template_header($this->get_name('alias'), $data_attribute_names, $ansicht));
			fclose($fp);

			$fp = fopen($template_dir . $this->get_name() . '_body.html', "w");
			#if ($this->gui->user->id == 3) echo $template_dir . $this->get_name() . '_body.html';
			fwrite($fp, $this->get_wms_template_body($data_attribute_names, $ansicht));
			fclose($fp);
		}
	}

	function remove_mapserver_templates() {
		$template_dir = WMS_MAPFILE_PATH . 'templates/';
		foreach(array('head', 'body') AS $postfix) {
			if (file_exists($template_dir . $this->get_name() .'_' . $postfix . '.html')) {
				unlink($template_dir . $this->get_name() .'_' . $postfix . '.html');
			}
		}
	}

	function get_wms_template_header($layer_name, $attributes, $ansicht = 'Tabelle') {
		$html = "<!-- MapServer Template -->";
		$html .= "
<style>
		body {
				font-family: helvetica;
		}
		td {
			border: 1px solid #cccccc;
			padding: 5px;
		}
		th {
			background: linear-gradient(#DAE4EC 0%, #c7d9e6 100%);
			border: 1px solid #cccccc;
			padding: 5px;
		}
</style>
<h2>" . $layer_name . "</h2>";
		if ($ansicht == 'Tabelle') {
			$html .= "
<table>
	<tr>";
			foreach ($attributes AS $attribute) {
				$html .= "
		<th>
			" . $attribute['alias'] . "
		</th>";
			}
			$html .= "
	</tr>";
		}
		return $html;
	}

	function get_wms_template_body($attributes, $ansicht = 'Tabelle') {
		$html = "<!-- MapServer Template -->";
		if ($ansicht == 'Tabelle') {
			$html .= "
	<tr>";
			foreach ($attributes AS $attribute) {
				$html .= "
		<th>
			[item name=" . $attribute['name'] . " escape=none]
		</th>";
			}
			$html .= "
	</tr>";
		}
		else {
			$html .= "
<table>";
			foreach ($attributes AS $attribute) {
				$html .= "
	<tr>
		<th align=\"left\">" . $attribute['alias'] . "</th>
		<td>[item name=" . $attribute['name'] . " escape=none]</td>
	</tr>";
			}
			$html .= "
</table>";
		}
		return $html;
	}

  function get_table_alias() {
    return mb_substr($this->get('schema'), 0, 1) . mb_substr($this->get('maintable') , 0, 1);
  }

	/**
	* Liefert an Hand des schema und maintable ein data-Statement wie es vom MapServer genutzt wird mit den dazugehörigen Attributen der Datentypen, Aufzählungen, CodeListen mit oder ohne Array.
	* Hier ein Beispiel für die Attriubte noch ohne Aufspreizung auf Unterattribute von Typen, Arrays etc.
	 position (select xplankonverter.konvertierungen.bezeichnung AS planname, xplan_gml.fp_landwirtschaft.aufschrift, xplan_gml.fp_landwirtschaft.created_at, gdi_codelist_json_to_text(to_json(xplan_gml.fp_landwirtschaft.detailliertezweckbestimmung)) AS detailliertezweckbestimmung, xplan_gml.fp_landwirtschaft.ebene, gdi_datatype_json_to_text(to_json(xplan_gml.fp_landwirtschaft.endebedingung), false) AS endebedingung, gdi_datatype_json_to_text(to_json(xplan_gml.fp_landwirtschaft.externereferenz), true) AS externereferenz, xplan_gml.fp_landwirtschaft.flaechenschluss, xplan_gml.fp_landwirtschaft.flussrichtung, xplan_gml.fp_landwirtschaft.gehoertzubereich, gdi_codelist_json_to_text(to_json(xplan_gml.fp_landwirtschaft.gesetzlichegrundlage)) AS gesetzlichegrundlage, xplan_gml.fp_landwirtschaft.gliederung1, xplan_gml.fp_landwirtschaft.gliederung2, xplan_gml.fp_landwirtschaft.gml_id, gdi_datatype_json_to_text(to_json(xplan_gml.fp_landwirtschaft.hatgenerattribut), true) AS hatgenerattribut, gdi_datatype_json_to_text(to_json(xplan_gml.fp_landwirtschaft.hoehenangabe), true) AS hoehenangabe, xplan_gml.fp_landwirtschaft.konvertierung_id, xplan_gml.fp_landwirtschaft.nordwinkel, xplan_gml.fp_landwirtschaft.position, gdi_enum_json_to_text(to_json(xplan_gml.fp_landwirtschaft.rechtscharakter), 'xplan_gml', 'fp_rechtscharakter', false) AS rechtscharakter, gdi_enum_json_to_text(to_json(xplan_gml.fp_landwirtschaft.rechtsstand), 'xplan_gml', 'xp_rechtsstand', false) AS rechtsstand, xplan_gml.fp_landwirtschaft.refbegruendunginhalt, xplan_gml.fp_landwirtschaft.reftextinhalt, gdi_codelist_json_to_text(to_json(xplan_gml.fp_landwirtschaft.spezifischepraegung)) AS spezifischepraegung, gdi_datatype_json_to_text(to_json(xplan_gml.fp_landwirtschaft.startbedingung), false) AS startbedingung, xplan_gml.fp_landwirtschaft.text, xplan_gml.fp_landwirtschaft.updated_at, xplan_gml.fp_landwirtschaft.user_id, xplan_gml.fp_landwirtschaft.uuid, xplan_gml.fp_landwirtschaft.wirdausgeglichendurchflaeche, xplan_gml.fp_landwirtschaft.wirdausgeglichendurchspe, xplan_gml.fp_landwirtschaft.wirddargestelltdurch, gdi_enum_json_to_text(to_json(xplan_gml.fp_landwirtschaft.zweckbestimmung), 'xplan_gml', 'xp_zweckbestimmunglandwirtschaft', true) AS zweckbestimmung from xplan_gml.fp_landwirtschaft JOIN xplankonverter.konvertierungen ON xplan_gml.fp_landwirtschaft.konvertierung_id = xplankonverter.konvertierungen.id) as foo using unique gml_id using srid=25832
	* Die Abfrage der Attribute erfolgt in der Funktion get_maintable_attributes
	*/
	function get_generic_data_sql($options = array()) {
		$default_options = array(
			'attributes' => array(
				'select' => array(),
				'from' => array(),
				'where' => array()
			),
			'geom_attribute' => 'geom',
			'geom_type_filter' => false
		);
		$options = array_merge($default_options, $options);
		$attributes = $options['attributes'];
		$geom_attribute = $options['geom_attribute'];
		$geom_type_filter = $options['geom_type_filter'];

		include_once(CLASSPATH . 'Enumeration.php');
		include_once(CLASSPATH . 'DataType.php');
		include_once(CLASSPATH . 'CodeList.php');
		include_once(CLASSPATH . 'LayerAttribute.php');
		$msg = 'Generisch anhand des Datenbankmodells ermittelte DATA-Definition des Layers ' . $this->get('Name');
		$ret = $this->get_maintable_attributes();
		if (!$ret['success']) {
			return $ret;
		}

		foreach ($ret['maintable_attributes'] AS $attr) {
			switch($this->get_attribute_type($attr)) {
				case 'Enumeration' : {
					$formatter_objekt = new Enumeration($this->gui);
				} break;
				case 'Enumeration with table' : {
					$formatter_objekt = new Enumeration($this->gui, 'enum_' . $attr['typname']);
				} break;
				case 'CodeList' : {
					$formatter_objekt = new CodeList($this->gui);
				} break;
				case 'DataType' : {
					$formatter_objekt = new DataType($this->gui);
				} break;
				case 'TableType' : {
					# There is no separate class for tabletype yet, take Enumeration for it
					$formatter_objekt = new Enumeration($this->gui);
				} break;
				default : {
					$formatter_objekt = new LayerAttribute($this->gui);
				}
			}
			# ToDo in get_generic_select jeweils weiter Attribute vom Typ oder Werte, Codes und Beschreibungen etc. abfragen und ggf. mehrere wenn Arraytyp
			$sql = $formatter_objekt->get_generic_select($this, $attr);
			foreach(array('select', 'from', 'where') AS $key) {
				if (array_key_exists($key, $sql) AND $sql[$key] != '') {
					$attributes[$key][] = $sql[$key];
				}
			}

			if ($attr['typname'] == 'geometry') {
				$geom_attribute = $attr['att_name'];
				if ($geom_type_filter) {
					$attributes['where'][] = "ST_GeometryType(" . $this->get_table_alias() . "." . $geom_attribute . ") LIKE 'ST_%" . $this->geometry_types[$this->get('Datentyp')] . "'";
				}
			}
		}

		$data_sql = $geom_attribute . " from (
  select
    " . implode(",
    ", $attributes['select']) . "
  from
    " . $this->get('schema') . "." . $this->get('maintable') . " AS " . $this->get_table_alias()
		. (count($attributes['from']) > 0 ? "
    " . implode("
    ", $attributes['from']) : '') . (count($attributes['where']) > 0 ? "
  where
    " . implode(" AND
    ", $attributes['where']) : '') . "
) as foo using unique " . $this->get('oid') . " using srid=" . $this->get('epsg_code');

		return array(
			'success' => true,
			'msg' => $msg,
			'data_sql' => $data_sql
		);
	}

	function enum_table_exists($table_name) {
		$sql = "
			SELECT EXISTS (
				SELECT FROM 
					pg_tables
				WHERE 
					schemaname = 'xplan_gml' AND 
					tablename	= 'enum_" . $typname . "'
			);
		";
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 1, true);
		$rs = pg_fetch_array($ret[1]);
		return $rs[0] == 't';
	}

	function codelist_table_exists($typname) {
		$sql = "
			SELECT
				count(*) = 3
			FROM 
				information_schema.columns
			WHERE 
				table_schema = 'xplan_gml' AND 
				table_name	= '" . $typname . "' AND
				column_name IN ('id', 'value', 'codespace')
		";
		#echo $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 1, true);
		$rs = pg_fetch_array($ret[1]);
		return $rs[0] == 't';
	}

	function datatype_table_exists($typname) {
		$sql = "
			SELECT EXISTS (
				SELECT FROM 
					pg_type
				WHERE
					typname	= '" . $typname . "'
			)
		";
		#echo $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 1, true);
		$rs = pg_fetch_array($ret[1]);
		return $rs[0] == 't';
	}

	function tabletype_table_exists($typname) {
		$sql = "
			SELECT EXISTS (
				SELECT FROM 
					pg_tables
				WHERE 
					schemaname = 'xplan_gml' AND 
					tablename	= '" . $typname . "'
			);
		";
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 1, true);
		$rs = pg_fetch_array($ret[1]);
		return $rs[0] == 't';
	}

	function get_attribute_type($def) {
		$type = 'normal';
		if ($def['typcategory'] == 'E') {
			if ($this->enum_table_exists($def['typname'])) {
				#extent select to query wert and beschreibung
				$type = 'Enumeration with table';
			}
			$type = 'Enumeration';
		}
		if ($def['dtd_table_name'] != '') {
			if ($this->codelist_table_exists($def['typname'])) {
				$type = 'CodeList';
			}
			else {
				if ($this->datatype_table_exists($def['typname'])) {
					$type = 'DataType';
				}
				else {
					if ($this->tabletype_table_exists($def['typname'])) {
						$type = 'TableType';
					}
				}
			}
		}
		#echo "\ncategory: " . $def['typcategory'] . " name: " . $def['att_name'] . " type: " . $def['typname'] . ' attr_type: ' . $type;
		return $type;
	}

}
?>
