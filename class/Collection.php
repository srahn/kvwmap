<?php
#####################
# Klasse Collection #
#####################
class Collection extends PgObject {
	static $schema = 'kvwmap';
	static $tableName = 'collections';

	function __construct($gui) {
		parent::__construct($gui, Collection::$schema, Collection::$tableName);
		$this->select = "*";
		$this->from = Collection::$schema . '.' . Collection::$tableName;
		$this->where = "";
		include_once(LAYOUTPATH . 'languages/collection_formular_' . rolle::$language . '.php');

		$mapDB = new db_mapObj($this->gui->Stelle->id, $this->gui->user->id);
		$layer_groups = $mapDB->get_groups();
		$group_id_options = array();
		for ($i=0; $i < count($layer_groups['ID']); $i++) {
			$group_id_options[] = array(
				'output' => $layer_groups['Bezeichnung'][$i] . ' (' . $layer_groups['ID'][$i] . ')',
				'value' => $layer_groups['ID'][$i]
			);
		}
		$this->aliases = array(
			'group_id' => 'Gruppe der Collection',
			'collection_layer_group_id' => 'Gruppe der Layer',
			'only_with_content' => 'Nur Layer mit Inhalten',
			'stelle_id' => 'Id der Stelle'
		);
		$this->auswahloptions = array(
			'group_id' => $group_id_options,
			'collection_layer_group_id' => $group_id_options
		);
		$this->tooltips = array(
			'bezeichnung' => $strToolTipBezeichnung,
			'group_id' => $strToolTipGroupId,
			'collection_layer_group_id' => $strToolTipCollectionLayerGroupId,
			'filter' => $strToolTipFilter,
			'extent' => $strToolTipExtent,
			'only_with_content' => $strToolTipOnlyWithContent,
			'stelle_id' => $strToolTipStelleId
		);
		$this->validations = array(
			array(
				'attribute' => 'bezeichnung',
				'condition' => 'not_null',
				'description' => 'Es muss ein Name für die Collection angegeben werden.',
				'option' => null
			)
		);
	}

	public static function generate($gui, $bezeichnung, $group_id, $filter, $extent) {
		$collection = new Collection($gui);
		$result = $collection->create(
			array(
				'bezeichnung' => $bezeichnung,
				'group_id' => $group_id,
				'filter' => $filter,
				'extent' => $extent
			)
		);
		if (!$result['success']) {
			return $result;
		}
		$result['collection'] = $collection;
		return $result;
	}

	public static	function find($gui, $where = '', $order = '') {
		$collection = new Collection($gui);
		return $collection->find_where($where, $order);
	}

	/**
	 * Function find collection with $id in database
	 * If no collection has been found, it returns false
	 * @param GUI $gui
	 * @param int $id
	 * @return Collection|false
	 */
	public static	function find_by_id($gui, $id) {
		$collection = new Collection($gui);
		$result = $collection->find_by('id', $id, '=', true);
		if (!$result['success']) {
			return $result;
		}
		$collection = $result['feature'];
		if ($collection->get_id() == '') {
			return array(
				'success' => false,
				'msg' => 'Collection mit id ' . $id . ' nicht gefunden.'
			);
		}
		$collection->layers = $collection->get_collection_layers();
		return array(
			'success' => true,
			'collection' => $collection
		);
	}

	function get_collection_layers() {
		include_once(CLASSPATH . 'CollectionLayer.php');
		$obj = new CollectionLayer($this->gui);
		$collection_layers = $obj->find_where("collection_id = " . $this->get_id());
		return $collection_layers;
	}

	function add_to_rollen($stelle_id) {
		$sql = "
			INSERT INTO kvwmap.collections2rolle(collection_id, stelle_id, user_id)
			SELECT
				" . $this->get_id() . ",
				stelle_id,
				user_id
			FROM
				kvwmap.rolle
			WHERE
				stelle_id = $1
		";
		$result = $this->database->execSQL($sql, 4, 1, false, array($stelle_id));
		if (!$result['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Speichenr der Zuordnung der Collection ' . $this->get_id() . ' zu den Nutzern der Stelle ' . $stelle_id . ' mit SQL: ' . $sql . ' Fehler: ' . $result['msg']
			);
		}
		return array(
			'success' => true,
			'msg' => 'Collection erfolgreich den Nutzern der Stelle ' . $stelle_id . ' zugeordnet'
		);
	}

	function add_group_to_rollen($stelle_id) {
		$sql = "
			INSERT INTO kvwmap.u_groups2rolle(user_id, stelle_id, id, status)
			SELECT
				r.user_id,
				r.stelle_id,
				" . $this->get('group_id') . ",
				0
			FROM
				kvwmap.rolle r
			WHERE
				r.stelle_id = $1 AND
				NOT EXISTS (
					SELECT 1
					FROM kvwmap.u_groups2rolle ugr
					WHERE
						ugr.user_id = r.user_id AND
						ugr.stelle_id = r.stelle_id AND
						ugr.id = " . $this->get('group_id') . "
				)
		";
		$result = $this->database->execSQL($sql, 4, 1, false, array($stelle_id));
		if (!$result['success']) {
			return array(
				'success' => false,
				'msg' => 'Fehler beim Speichenr der Zuordnung der Collection ' . $this->get_id() . ' zu den Nutzern der Stelle ' . $stelle_id . ' Fehler: ' . $result['msg']
			);
		}
		return array(
			'success' => true,
			'msg' => 'Collection erfolgreich den Nutzern der Stelle ' . $stelle_id . ' zugeordnet'
		);
	}
}