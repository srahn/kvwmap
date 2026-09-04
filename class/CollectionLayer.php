<?php
##########################
# Klasse CollectionLayer #
##########################

class CollectionLayer extends PgObject {
	static $schema = 'kvwmap';
	static $tableName = 'collection_layer';

	function __construct($gui) {
		parent::__construct($gui, CollectionLayer::$schema, CollectionLayer::$tableName);
		$this->select = "*";
		$this->from = CollectionLayer::$schema . '.' . CollectionLayer::$tableName;
		$this->where = "";
	}

	public static function generate($gui, $collection_id, $layer_id) {
		$collection_layer = new CollectionLayer($gui);
		$result = $collection_layer->create(
      array(
        'collection_id' => $collection_id,
        'layer_id' => $layer_id
      )
    );
    if (!$result['success']) {
      return $result;
    }
    $result['collection_layer'] = $collection_layer;
    return $result;
  }

  /**
	 * Function find collection layer with $id in database
	 * If no layer has been found, it returns false
	 * @param GUI $gui
	 * @param int $id
	 * @return CollectionLayer|false
	 */
  public static	function find_by_id($gui, $id) {
		$collection_layer = new CollectionLayer($gui);
		$result = $collection_layer->find_by('id', $id, '=', true);
    if (!result['success']) {
      return $result;
    }
		$collection_layer = $result['feature'];
		if ($collection_layer->get_id() == '') {
			return array(
				'success' => false,
				'msg' => 'CollectionLayer mit id ' . $id . ' nicht gefunden.'
			);
		}
		return array(
      'success' => true,
      'collection_layer' => $collection_layer
    );
	}

  function add_to_rollen($stelle_id) {
    $sql = "
      INSERT INTO kvwmap.collection_layer2rolle(collection_layer_id, stelle_id, user_id)
      SELECT
        " . $this->get_id() . ",
        stelle_id,
        user_id
      FROM
        kvwmap.rolle
      WHERE
        stelle_id = " . $stelle_id . "
    ";
    $query = $this->execSQL($sql);
    $last_error = pg_last_error($this->database->dbConn);
    if ($last_error != '') {
      return array(
        'success' => false,
        'msg' => 'Fehler beim Speichenr der Zuordnung des Collection Layers ' . $this->get_id() . ' zu den Nutzern der Stelle ' . $stelle_id . ' Fehler: ' . $last_error
      );
    }
    return array(
      'success' => true,
      'msg' => 'CollectionLayer erfolgreich den Nutzern der Stelle ' . $stelle_id . ' zugeordnet'
    );
	}
}