<?php
include_once(CLASSPATH . 'PgObject.php');
include_once(CLASSPATH . 'LayerAttribute.php');
include_once(CLASSPATH . 'LayerChart.php');
include_once(CLASSPATH . 'DataSource.php');
include_once(CLASSPATH . 'LayerDataSource.php');

class Layer extends PgObject {

	static $write_debug = false;
	public $geometry_types;
	public $geom_column;
	public $attributes;
	public $charts;
	public $table_alias;
	public $opacity;
	public $minScale;
	public $maxScale;
	public $document_attributes;
	public $layer2stelle;

	function __construct($gui) {
		$this->gui = $gui;
		$this->has_many = array(
			"attributes" => array(
				"alias" => 'Attribute',
				"table" => 'layer_attributes',
				"vorschau" => 'name',
				"pk" => 'layer_id, name',
				"fk" => 'layer_id'
			),
			"charts" => array(
				"alias" => 'Diagramme',
				"table" => 'layer_charts',
				"vorschau" => 'title',
				"pk" => 'id',
				"fk" => 'layer_id'
			)
		);
		parent::__construct($gui, 'kvwmap', 'layer', 'layer_id');
		$this->stelle_id = ($gui->stelle ? $gui->stelle->id : null);
		$this->geometry_types = array('Point', 'LineString', 'Polygon');
		$this->geom_column = 'geom';
	}

	public static	function find($gui, $where, $order = '') {
		$layer = new Layer($gui);
		return $layer->find_where($where, $order);
	}

	/**
	 * Function find layer with $id in MariaDb database
	 * and return the layer object with beloning attributes and charts
	 * If no layer has been found, it returns false
	 * @param GUI $gui
	 * @param int $id
	 * @return Layer|false
	 */
	public static	function find_by_id($gui, $id) {
		$obj = new Layer($gui);
		$layer = $obj->find_by('layer_id', $id);
		if ($layer->get_id() == '') {
			return false;
		}
		$layer->attributes = $layer->get_layer_attributes();
		$layer->charts = $layer->get_layer_charts();
		return $layer;
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
					layer l ON (g.id = l.gruppe)",
				'where' => "
					g.obergruppe = " . $obergruppe_id . " AND
					l.Name = '" . $layer_name . "'"
			)
		);
		return $result[0];
	}

	/**
	* This function return the layer id's of the duplicates of a layer
	* @param database object
	* @param int $duplicate_from_layer_id The layer id from witch the others are duplicates
	* @param array(integer) The layer_ids of the duplicates
	*/
	public static function find_by_duplicate_from_layer_id($database, $duplicate_from_layer_id) {
		$duplicate_layer_ids = array();
		$sql =	"
			SELECT
				layer_id
			FROM
				kvwmap.layer
			WHERE
				duplicate_from_layer_id = " . $duplicate_from_layer_id . "
				AND layer_id != duplicate_from_layer_id
		";
		# letzte Where Bedinung, damit keine Endlosschleifen entstehen beim Aufruf von update_layer falls
		# Layer_ID fälschlicherweise identisch sein sollte mit duplicate_layer_id was nicht passieren sollte
		# wenn das Layerformular genutzt wurde.
		#echo	PgObject::$write_debug ? 'Layer find_by_duplicate_from_layer_id sql:<br> ' . $sql : '';
		$ret = $database->execSQL($sql, 4, 1, true);
		if (!$ret['success']) {
			$database->gui->add_message('error', $ret[1]);
		}
		else {
			while ($rs = pg_fetch_assoc($ret[1])) {
				$duplicate_layer_ids[] = $rs['layer_id'];
			}
		}
		return $duplicate_layer_ids;
	}

	function get_layer_db() {
		$db_map_obj = new db_mapObj($this->gui->Stelle->id, $this->gui->user->id);
		$layer_db = $db_map_obj->getlayerdatabase($this->get_id(), '');
		return $layer_db;
	}

	function update_datasources($gui, $datasource_ids) {
		$layer_datasource = new LayerDataSource($gui);
		$layer_datasource->delete('layer_id = ' . $this->get_id());
		foreach($datasource_ids AS $datasource_id) {
			$layer_datasource->create(array(
				'layer_id' => $this->get_id(),
				'datasource_id' => $datasource_id
			));
		}
	}

	function update_labelitems($gui, $names, $aliases) {
		$layer_labelitems = new PgObject($gui, 'kvwmap', 'layer_labelitems', 'layer_id');
		$layer_labelitems->delete('layer_id = ' . $this->get_id());
		for ($i = 1; $i < count($names); $i++) {
			# der erste ist ein Dummy und wird ausgelassen
			if ($names[$i] != '') {
				$layer_labelitems->create(array(
					'layer_id' => $this->get_id(),
					'name' => $names[$i],
					'alias' => $aliases[$i],
					'order' => $i + 1
				));
			}
		}
	}

	/**
	 * This function query the data sources for the layer
	 * and aggregate and returns the $attriubte from datasource to a source text.
	 * If $attribute is empty it takes the other (name in sted of beschreibung and vise versa).
	 * If not of both exists it takes the kurzbeschreibung from the layer.
	 */
	function get_datasources($attribute = 'beschreibung') {
		$datasources = DataSource::find_by_layer_id($this->gui, $this->get_id());
		if (count($datasources) == 0) {
			return null;
		}
		else {
			$source_text = array();
			if ($attribute == 'beschreibung') {
				$source_text = array_map(function($datasource) { return $datasource->get('beschreibung') ?? $this->get('name') ?? $this->get('kurzbeschreibung'); }, $datasources);
			}
			else {
				$source_text = array_map(function($datasource) { return $datasource->get('name') ?? $datasource->get('beschreibung') ?? $this->get('kurzbeschreibung'); }, $datasources);
			}
			return implode(', ', $source_text);
		}
	}

	function get_layer_attributes() {
		include_once(CLASSPATH . 'LayerAttribute.php');
		$obj = new LayerAttribute($this->gui);
		$layer_attributes = $obj->find_where(
			$this->has_many['attributes']['fk'] . ' = ' . $this->get_id(),
			'"order"'
		);
		return $layer_attributes;
	}

	function get_layer_charts() {
		$obj = new LayerChart($this->gui);
		$layer_charts = $obj->find_where(
			$this->has_many['charts']['fk'] . ' = ' . $this->get_id(),
			'title'
		);
		return $layer_charts;
	}

	/**
	 * Function returning array of stelle_id's
	 * where layer belongs to in table used_layer.
	 */
	function get_stellen() {
		include_once(CLASSPATH . 'Layer2Stelle.php');
		$used_layers = Layer2Stelle::find($this->gui, "layer_id = " . $this->get_id());
		$stellen_ids = array_map(
			function($used_layer) {
				return $used_layer->get('stelle_id');
			},
			$used_layers
		);
		return $stellen_ids;
	}

	function get_maintable_attributes($layerdb) {
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
		$ret = $layerdb->execSQL($sql, 4, 0);
		if (!$ret['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler bei der Abfrage der Tabellenattribute. Fehler: ' . $ret['msg']
			);
		}

		while ($attr = pg_fetch_assoc($ret['query'])) {
			$this->maintable_attributes[$attr['att_name']] = $attr;
		}

		return array(
			'success' => true,
			'msg' => 'Maintable Attribute erfolgreich abgefragt.',
			'maintable_attributes' => $this->maintable_attributes
		);
	}

	/**
	 * This function check if the stellen, where the layer belongs to,
	 * have given menue_or_functions
	 * @param Array $menue_or_functions: The array with the go resp. function_names
	 * @return Array $msg: An Array of messages when conditions not matches.
	 */
	function has_sync_functions($functions) {
		include_once(CLASSPATH . 'Menue.php');
		include_once(CLASSPATH . 'Funktion.php');
		$msg = array();
		$stellen_ids = $this->get_stellen();
		foreach ($functions AS $function) {
			$menues = Menue::find($this->gui, "links LIKE '%go=" . $function . "%'");
			$funktionen = Funktion::find($this->gui, "bezeichnung = '" . $function . "'");
			if (count($menues) == 0 AND count($funktionen) == 0) {
				$msg[] = "Die Funktion '" . $function . "' ist für die Synchronisation von Layern notwendig. Sie konnte weder in den Menüs noch den Funktionen gefunden werden. Bitte Menü oder Funktion anlegen und den Stellen, in denen der zu synchronisierende Layer ist, zuordnen.";
			}
			else {
				foreach($stellen_ids AS $stelle_id) {
					$menues2stelle = array();
					$funktionen2stelle = array();	
					if (count($menues) > 0) {
						include_once(CLASSPATH . 'Menue2Stelle.php');
						$menues2stelle = Menue2Stelle::find($this->gui, "stelle_id = " . $stelle_id . " AND menue_id = " . $menues[0]->get_id());
					}
					if (count($funktionen) > 0) {
						include_once(CLASSPATH . 'Funktion2Stelle.php');
						$funktionen2stelle = Funktion2Stelle::find($this->gui, "stelle_id = " . $stelle_id . " AND funktion_id = " . $funktionen[0]->get_id());
					}
					if (count($menues2stelle) == 0 AND count($funktionen2stelle) == 0) {
						$msg[] = "Die Funktion '" . $function . "' muss in einem Menü oder als Funktion der Stelle ID: " . $stelle_id . " zugewiesen sein damit der Layer '" . $this->get('name') . "' synchronisiert werden kann!";
					}
				}
			}
		}
		return $msg;
	}

	/**
	 * Function check if layer have the necessary attributes, there type and dependencies.
	 * @param Array $attributes: The array with attributes that have to be checked.
	 * @return Array $msg: An Array of messages when conditions not matches.
	 */
	function has_sync_attributes($attributes) {
		# ToDo: implement
		$msg = array();
		return $msg;
	}

	/**
	 * Function check if layer has $id as primary key in main table and
	 * is set as oid in layer definition.
	 * @param text $id: Name of the unique id for the sync layer.
	 * @return Array $msg: An Array of messages when condition not maches.
	 */
	function has_sync_id($id) {
		# ToDo: implement
		$msg = array();
		return $msg;
	}

	/**
	 * Function check if coupled sub_layers are in symc mode
	 */
	function get_none_synced_sub_layers($id) {
		$sql = "
		SELECT DISTINCT
			la.layer_id,
			la.name attribute_name,
			split_part(la.options, ',', 1) AS sub_layer_id,
			l.name AS sub_layer_name
		FROM
			kvwmap.layer_attributes la JOIN
			kvwmap.used_layer ul ON la.layer_id = ul.layer_id JOIN
			kvwmap.layer_attributes2stelle las ON la.name = las.attributename AND la.layer_id = las.layer_id AND ul.stelle_id = las.stelle_id LEFT JOIN
			kvwmap.layer l ON split_part(la.options, ',', 1)::integer = l.layer_id
		WHERE
			la.layer_id = " . $id . " AND
			la.form_element_type = 'SubFormEmbeddedPK' AND
			l.layer_id IS NOT NULL AND
			l.sync != '1' AND
			las.privileg = '1'
		";
		#echo '<br>SQL to find coupled sub_layer that are not in sync mode: ' . $sql;
		$ret = $this->database->execSQL($sql);
		$not_synced_sub_layers = array();
		while ($rs = pg_fetch_assoc($ret[1])) {
			$not_synced_sub_layers[] = $rs;
		}
		return $not_synced_sub_layers;
	}

	/**
	 * find coupled sublayer that not exists.
	 * TODO: Muss eigentlich
	 *       beim Speichern der options,
	 *       beim Ändern der Layer-ID,
	 *       und Löschen von Layern geprüft werden
	 */
	function get_missing_sublayers($id) {
		$sql = "
			SELECT DISTINCT
				la.name attribute_name,
				split_part(la.options, ',', 1) AS sub_layer_id,
				l.name AS layer_name
			FROM
				kvwmap.layer_attributes la LEFT JOIN
				kvwmap.layer l ON split_part(la.options, ',', 1)::integer = l.layer_id
			WHERE
				la.layer_id = " . $id . " AND
				la.visible = 1 AND
				la.form_element_type = 'SubFormEmbeddedPK' AND
				l.layer_id IS NULL
		";
		#echo '<br>SQL to find coupled sublayer that not exists: ' . $sql;
		$ret = $this->database->execSQL($sql);
		$sublayers = array();
		while ($rs = pg_fetch_assoc($ret[1])) {
			$sublayers[] = $rs;
		}
		return $sublayers;
	}

	/**
	 * find coupled sublayer that are not in same stelle
	 * TODO: Muss eigentlich
	 *       beim Ändern der Zugehörigkeit zu Stellen geprüft werden
	 */
	function get_missing_sub_layers_in_stellen($id) {
		$sql = "
			SELECT DISTINCT
				la.name AS attribute_name,
				split_part(la.options, ',', 1) AS sub_layer_id,
				l.name AS layer_name,
				ul.stelle_id AS stelle_id,
				s.bezeichnung AS stelle_bezeichnung
			FROM
				kvwmap.layer_attributes la JOIN
				kvwmap.layer l ON split_part(la.options, ',', 1)::integer = l.layer_id JOIN
				kvwmap.used_layer ul ON la.layer_id = ul.layer_id JOIN
				kvwmap.layer_attributes2stelle las ON la.name = las.attributename AND la.layer_id = las.layer_id AND ul.stelle_id = las.stelle_id JOIN 
				kvwmap.stelle s ON ul.stelle_id = s.ID LEFT JOIN
				kvwmap.used_layer ul2 ON ul.stelle_id = ul2.stelle_id AND split_part(la.options, ',', 1)::integer = ul2.layer_id
			WHERE
				la.layer_id = " . $id. " AND
				la.form_element_type = 'SubFormEmbeddedPK' AND
				ul2.stelle_id IS NULL AND
				ul2.layer_id IS NULL
		";
		#echo '<br>SQL to find sublayer that are not in same stelle: ' . $sql;
		$ret = $this->database->execSQL($sql);
		$sublayers = array();
		while ($rs = pg_fetch_assoc($ret[1])) {
			$sublayers[] = $rs;
		}
		return $sublayers;
	}

	/**
	 * Diese Funktion legt vom aktuellen layer Objekt einen neuen Layer an
	 * mit der übergebenen Layergruppe sowie alle seine zugehörigen Klassen und layer_attributes.
	 * Vom Layer verwendete Styles und Labels werden wiederverwendet.
	 * @return Layer Das kopierte Layerobjekt
	 */
	function copy($attributes) {
		$success = true;
		$this->debug->show('<p>Clone Templatelayer: ' . $this->get($this->identifier), Layer::$write_debug);
		$new_layer = clone $this;
		unset($new_layer->data['layer_id']);
		foreach ($attributes AS $key => $value) {
			$new_layer->set($key, $value);
		}

		$result = $new_layer->create()[0];
		if ($result['success'] === false) {
			throw new Exception('Fehler beim Kopieren des Layers: ' . $result['msg']);
		}
		$new_layer_id = $new_layer->get($new_layer->identifier);

		if (!empty($new_layer_id)) {
			$this->debug->show('<p>Kopiere die Klassen des Template layers für neuen Layer id: ' . $new_layer_id, Layer::$write_debug);
			$this->copy_classes($new_layer_id);
			$this->debug->show('<p>Kopiere die layer_attributes des Template layers für neuen Layer id: ' . $new_layer_id, Layer::$write_debug);
			$this->copy_layer_attributes($new_layer_id);
		}
		return $new_layer;
	}

	/*
	* Kopiere die Klassen des Layers mit anderer Layer_id
	*/
	function copy_classes($new_layer_id) {
		include_once(CLASSPATH . 'LayerClass.php');
		foreach(LayerClass::find($this->gui, 'layer_id = ' . $this->get('layer_id')) AS $layer_class) {
			$this->debug->show('Copy class: ' . $layer_class->get('name') . ' mit layer id: ' . $this->get('layer_id') . ' => ' . $new_layer_id, Layer::$write_debug);
			$layer_class->copy($new_layer_id);
		}
	}

	function copy_layer_attributes($new_layer_id) {
		include_once(CLASSPATH . 'LayerAttribute.php');
		foreach(LayerAttribute::find($this->gui, 'layer_id = ' . $this->get('layer_id')) AS $attribute) {
			$this->debug->show('Copy Attribute: ' . $attribute->get('name') . ' mit neuer layer id: ' . $this->get('layer_id') . ' => ' . $new_layer_id, Layer::$write_debug);
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
					maintable = '" . $this->get('maintable') . "' AND
					schema = '" . $this->get('schema') . "'
				) OR
				data LIKE '%" . $this->get('schema') . "." . $this->get('maintable') . "%'
			) AND
			layer_id != " . $this->get($this->identifier) . "
		");
		$this->data = $data;
		return (count($layers) > 0);
	}

	function get_subform_layers() {
		include_once(CLASSPATH . 'LayerAttribute.php');
		$subform_layer_ids = array_unique(
			array_map(
				function($attribute) {
					return explode(',', $attribute->get('options'))[0];
				},
				LayerAttribute::find($this->gui, "layer_id = " . $this->get('layer_id') . " AND form_element_type LIKE 'SubForm%PK'")
			)
		);
		if (count($subform_layer_ids) > 0) {
			return Layer::find(
				$this->gui,
				"layer_id IN (" . implode(', ', $subform_layer_ids) . ')'
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
				LayerAttribute::find($this->gui, "layer_id != " . $this->get('layer_id') . " AND options LIKE '" . $this->get('layer_id') . ",%' AND form_element_type LIKE 'SubForm%PK'")
			)
		);
		if (count($parentform_layer_ids) > 0) {
			return Layer::find(
				$this->gui,
				"layer_id IN (" . implode(', ', $parentform_layer_ids) . ')'
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
					return '<a title="Layereditor anzeigen" href="index.php?go=Layereditor&selected_layer_id=' . $layer->get('layer_id') . '#' . $anchor . '" target="_blank">' . $layer->get('name') . ' (ID: ' . $layer->get('layer_id') . ')</a>';
				},
				$layers
			)
		) . '</li></ul>';
	}

	function get_group_name() {
		include_once(CLASSPATH . 'LayerGroup.php');
		$group = LayerGroup::find_by_id($this->gui, $this->get('gruppe'));
		if ($group->get('obergruppe') != '') {
			$obergroup = LayerGroup::find_by_id($this->gui, $group->get('obergruppe'));
			return $obergroup->get('gruppenname') . '|' . $group->get('gruppenname');
		}
		else {
			return $group->get('gruppenname');
		}
	}

	/**
	 * Create the layer definition for a baselayer in stelle with $stelle_id
	 * @param int $stelle_id
	 * @return array{ label: String, options: array{}, shortLabel: String, img: String, url: String}
	 */
	function get_baselayers_def($stelle_id) {
		$this->debug->show('<p>Layer->get_baselayers_def for stelle_id: ' . $stelle_id, PgObject::$write_debug);
		#echo '<p>get_baselayer_def for Layer: ' . $this->get('name');

		include_once(CLASSPATH . 'LayerClass.php');
		include_once(CLASSPATH . 'LayerAttribute.php');

		$layerAttributes = new stdClass();
		foreach (LayerAttribute::find_visible($this->gui, $stelle_id, $this->get('layer_id')) AS $attr) {
			$key = $attr->get('name');
			$value = ($attr->get('alias') == '' ? $attr->get('name') : $attr->get('alias'));
			$layerAttributes->$key = $value;
		}
		$classes = LayerClass::find($this->gui, 'layer_id = ' . $this->get('layer_id'));
		if ($this->get('icon') != '') {
			$legendgraphic = $this->get('icon');
		}
		elseif (count($classes) > 0) {
			$legendgraphic = $classes[0]->get('legendgraphic');
		}
		else {
			$legendgraphic = 'graphics/leer.gif';
		}
		$layerdef = (Object) array(
			'label' => ($this->get('alias') != '' ? $this->get('alias') : $this->get('name')),
			'options' => $this->get_baselayer_options(),
			'shortLabel' => $this->get('name'),
			'img' => URL . APPLVERSION . $legendgraphic,
			'url' => $this->get_baselayer_url()
		);
		return $layerdef;
	}

	function get_baselayer_options() {
		if (strpos($this->get('data'), '{') === 0) {
			$data = json_decode($this->get('data'));
			$data->options->attribution = $this->get_datasources('name');
			return $data->options;
		}
		else {
			return (Object) array(
				'attribution' => $this->get_datasources('name')
			);
		}
	}

	function get_baselayer_url() {
		if ($this->get('data') == '') {
			return $this->get('connection');
		}
		if (strpos($this->get('data'), '{') === 0) {
			$data = json_decode($this->get('data'));
			return $data->url;
		}
		else {
			return $this->get('data');
		}
	}

	/**
	 * Get layer definition from layer for stelle
	 */
	function get_overlays_def($stelle_id) {
		$this->debug->show('<p>Layer->get_overlays_def for stelle_id: ' . $stelle_id, PgObject::$write_debug);
		#echo '<p>get_overlays_def for Layer: ' . $this->get('name');
		include_once(CLASSPATH . 'LayerClass.php');
		include_once(CLASSPATH . 'LayerAttribute.php');

		$layerAttributes = new stdClass();
		foreach (LayerAttribute::find_visible($this->gui, $stelle_id, $this->get('layer_id')) AS $attr) {
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
					'selected_layer_id' => (int)$this->get('layer_id'),
					'export_format' =>	'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name'),
					'opacity' => $this->opacity / 100
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
					'selected_layer_id' => (int)$this->get('layer_id'),
					'export_format' =>	'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name'),
					'opacity' => $this->opacity / 100
				);
			} break;
			default : { # currently same as PostGIS-Layer
				$type = 'GeoJSON';
				$url = URL . APPLVERSION . 'index.php';
				$params = (Object) array(
					'gast' => (int)$stelle_id,
					'go' => 'Daten_Export_Exportieren',
					'Stelle_ID' => (int)$stelle_id,
					'selected_layer_id' => (int)$this->get('layer_id'),
					'export_format' =>	'GeoJSON',
					'browserwidth' => 800,
					'browserheight' => 600,
					'epsg' => 4326,
					'all' => 1
				);
				$options = (Object) array(
					'transparent' => true,
					'attribution' => $this->get('dataowner_name'),
					'opacity' => $this->opacity / 100
				);
			}
		}

		$classitem = $this->get('classitem');
		$datentyp = $this->get('datentyp');
		$layer_opacity = $this->opacity;

		$layerdef = (Object) array(
			'thema' => $this->get_group_name(),
			'label' => ($this->get('alias') != '' ? $this->get('alias') : $this->get('name')),
			'abstract' => $this->get('kurzbeschreibung'),
			'contactOrganisation' => $this->get_datasources('beschreibung'),
			'contactPersonName' => $this->get('dataowner_name'),
			'contactEMail' => $this->get('dataowner_email'),
			'contactPhon' => $this->get('dataowner_tel'),
			'actuality' => $this->get('uptodateness'),
			'actualityCircle' => $this->get('updatecycle'),
			'type' => $type,
			'geomType' => array('Point', 'Linestring', 'Polygon', 'Raster', 'Annotation', 'Query', 'Circle', 'Tileindex', 'Chart')[$this->get('datentyp')],
			'backgroundColor' => '#c1ffd8',
			'infoAttribute' => ($this->get('labelitem') != '' ? $this->get('labelitem') : $this->get('oid')),
			'classItem' => ($this->get('classitem') != '' ? $this->get('classitem') : $this->get('oid')),
			'url' => $url,
			'params' => $params,
			'options' => $options,
			'classes' => array_map(
				function($class) use ($classitem, $datentyp, $layer_opacity) {
					return $class->get_layerdef($classitem, $datentyp, $layer_opacity);
				},
				LayerClass::find($this->gui, 'layer_id = ' . $this->get('layer_id'), 'legendorder')
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

		if ($this->get_layer2stelle($stelle_id) AND $this->layer2stelle->get('symbolscale')) {
			$layerdef->symbolscale = $this->layer2stelle->get('symbolscale');
		}

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

	function get_name($name_col = 'name') {
		return $this->get($name_col . (($name_col == 'name' AND rolle::$language != 'german') ? '_' . rolle::$language : ''));
	}

	function write_mapserver_templates($ansicht = 'Tabelle') {
		$layer_id = $this->get($this->identifier);
		$mapDB = new db_mapObj($this->gui->Stelle->id, $this->gui->user->id);
		$layerdb = $mapDB->getlayerdatabase($layer_id, '');

		if ($this->get('write_mapserver_templates') == 'generic') {
			$result = $this->get_generic_data_sql();
			if ($result['success']) {
				$data = $result['data_sql'];
			}
			else {
				$result['msg'] = 'Fehler bei der Erstellung der Map-Datei in Funktion get_generic_data_sql! ' . $result['msg'];
				return $result;
			}
			$select = getDataParts($data)['select'];
			if ($layerdb->schema != '') {
				$select = str_replace($layerdb->schema . '.', '', $select);
			}
			$ret = $layerdb->getFieldsfromSelect($select);
			if ($ret[0]) {
				$this->gui->add_message('error', $ret[1]);
			}
			$all_data_attributes = $ret[1];
		}
		else {
			$all_data_attributes = $mapDB->getDataAttributes($layerdb, $layer_id, []);
		}

		$data_attributes = array_filter(
			array_slice($all_data_attributes, 0, -2),
			function($data_attribute) {
				return $data_attribute['type'] != 'geometry';
			}
		);

		$query_attributes = $mapDB->read_layer_attributes($layer_id, $layerdb, NULL);
		$query_attribute_aliases = array();

		for ($i = 0; $i < count($query_attributes['name']); $i++) {
			$query_attribute_aliases[$query_attributes['name'][$i]] = $query_attributes['alias' . (rolle::$language != 'german' ? '_' . rolle::$language : '')][$i];
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
			#if ($this->gui->user->id == 3) echo $template_dir . $this->get_name() . '_body.html' . 'oid-Spalte: ' . $this->get('oid');
			fwrite($fp, $this->get_wms_template_body($this->get_name('alias'), $data_attribute_names, $ansicht));
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
</style>";
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

	function get_wms_template_body($layer_name, $attributes, $ansicht = 'Tabelle') {
		$html = "<!-- MapServer Template -->
  <h2>" . $layer_name . "</h2>
  Objekt: [item name=" . $this->get('oid') . " escape=none]";
		if ($ansicht == 'Tabelle') {
			$html .= "
	<tr style=\"display: [item name=" . $attribute['name'] . " nullformat=none]\">";
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
	<tr style=\"display: [item name=" . $attribute['name'] . " nullformat=none]\">
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
    return $this->table_alias;
  }

	/**
	 * Liefert das Layer2Stelle-Objekt für die übergebene Stelle zurück, wenn es existiert.
	 * @param int $stelle_id
	 * @return Layer2Stelle|null
	 */
	function get_layer2stelle($stelle_id) {
		include_once(CLASSPATH . 'Layer2Stelle.php');
		$result = Layer2Stelle::find($this->gui, 'Stelle_ID = ' . $stelle_id . ' AND Layer_ID = ' . $this->get('Layer_ID'));
		$this->layer2stelle = (count($result) == 0 ? null : $result[0]);
		return $this->layer2stelle;
	}

	/**
	 * Generiert ein data-Statement in dem fehlende Attribute aus dem main_table ergänzt werden.
	 * Bei den Attributen, die ergänzt werden werden auch zusätzliche Attribute für Codelistenwerte, ids und Formatierungen für DataTypen etc. angelegt, siehe Formatter-Klassen.
	 * Attribute, die schon im Datastatement enthalten sind werden so belassen wie sie sind. Nur die dessen real_name nicht gefunden wurde wird ergänzt.
	 */
	function get_generic_data_sql() {
		include_once(CLASSPATH . 'Enumeration.php');
		include_once(CLASSPATH . 'DataType.php');
		include_once(CLASSPATH . 'CodeList.php');
		include_once(CLASSPATH . 'LayerAttribute.php');

		$msg = 'Generisch anhand des Datenbankmodells ermittelte DATA-Definition des Layers ' . $this->get('name');
		$mapDB = new db_mapObj($this->gui->Stelle->id, $this->gui->user->id);
		$layerdb = $mapDB->getlayerdatabase($this->get($this->identifier), $this->gui->Stelle->pgdbhost);

		// get the attribute names from the data select statement
		$data_attribute_names = array_map(
			function($attr) {
				return $attr['name'];
			},
			array_filter(
				$mapDB->getDataAttributes($layerdb, $this->get($this->identifier)),
				function($attr) {
					// filter out geom name and index attributes
					return is_array($attr);
				}
			)
		);
		$data = str_replace('$SCALE', '1000', $mapDB->getData($this->get($this->identifier)));
		$this->table_alias = get_table_alias(getDataParts($data)['select'], $this->get('schema'), $this->get('maintable'));

		// read the attributes from the maintable
		$ret = $this->get_maintable_attributes($layerdb);
		if (!$ret['success']) {
			return $ret;
		}

		// get the select expressions for the different attribute types
		// ToDo in get_generic_select jeweils weiter Attribute vom Typ oder Werte, Codes und Beschreibungen etc. abfragen und ggf. mehrere wenn Arraytyp
		$generic_selects = array();
		foreach ($ret['maintable_attributes'] AS $attr) {
			switch($this->get_attribute_type($attr)) {
				case 'Enumeration' : {
					$formatter_objekt = new Enumeration($this->gui);
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
			$generic_select = $formatter_objekt->get_generic_select($this->table_alias, $attr);
			$generic_selects = array_merge($generic_selects, $generic_select);
		}

		// filter out these that are already in data select statement and extract sql string
		$additional_selects = array_map(
			function ($select) {
				return $select['sql'];
			},
			array_filter(
				$generic_selects,
				function ($select) use ($data_attribute_names) {
					return (! in_array($select['att_name'], $data_attribute_names));
				}
			)
		);

		# fehlende Attribute in das data statement einfügen vor dem ersten Vorkommen von 'from' nach dem ersten Vorkommen von 'select'
		$pos_select = stripos($data, 'select');
		$pos_from = strripos($data, 'from', $pos_select);
		if ($pos_from !== false and count($additional_selects) > 0) {
			$data = substr_replace(
				$data,
				"  ," . implode(",\n    ", $additional_selects) . "\n  from", 
				$pos_from,
				strlen('from')
			);
		}

		return array(
			'success' => true,
			'msg' => $msg,
			'data_sql' => $data
		);
	}	

	function codelist_table_exists($typname, $schema) {
		$sql = "
			SELECT
				count(*) = 3
			FROM 
				information_schema.columns
			WHERE 
				table_schema = '" . $schema . "' AND 
				table_name	= '" . $typname . "' AND
				column_name IN ('id', 'value', 'codespace')
		";
		#echo $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 1, true);
		$rs = pg_fetch_array($ret[1]);
		return $rs[0] == 't';
	}

	function datatype_table_exists($typname, $schema) {
		$sql = "
			SELECT EXISTS (
				SELECT FROM 
					pg_type
				WHERE
					typnamespace = '" . $schema . "'::regnamespace AND 
					typname	= '" . $typname . "'
			)
		";
		#echo $sql;
		$ret = $this->gui->pgdatabase->execSQL($sql, 4, 1, true);
		$rs = pg_fetch_array($ret[1]);
		return $rs[0] == 't';
	}

	function tabletype_table_exists($typname, $schema) {
		$sql = "
			SELECT EXISTS (
				SELECT FROM 
					pg_tables
				WHERE 
					schemaname = '" . $schema . "' AND 
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
			$type = 'Enumeration';
		}
		if ($def['dtd_table_name'] != '') {
			if ($this->codelist_table_exists($def['typname'], $def['table_schema'])) {
				$type = 'CodeList';
			}
			else {
				if ($this->datatype_table_exists($def['typname'], $def['table_schema'])) {
					$type = 'DataType';
				}
				else {
					if ($this->tabletype_table_exists($def['typname'], $def['table_schema'])) {
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
