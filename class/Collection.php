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
        stelle_id = " . $stelle_id . "
    ";
    $query = $this->execSQL($sql);
    $last_error = pg_last_error($this->database->dbConn);
    if ($last_error != '') {
      return array(
        'success' => false,
        'msg' => 'Fehler beim Speichenr der Zuordnung der Collection ' . $this->get_id() . ' zu den Nutzern der Stelle ' . $stelle_id . ' Fehler: ' . $last_error
      );
    }
    return array(
      'success' => true,
      'msg' => 'Collection erfolgreich den Nutzern der Stelle ' . $stelle_id . ' zugeordnet'
    );
	}
}