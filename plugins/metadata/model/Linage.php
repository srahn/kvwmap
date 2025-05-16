<?php
##################
# Klasse Lineage #
##################

class Lineage extends PgObject {
	
	static $schema = 'metadata';
	static $tableName = 'lineages';
	static $write_debug = false;

	function __construct($gui) {
		$gui->debug->show('Create new Object lineage', Lineage::$write_debug);
		parent::__construct($gui, Lineage::$schema, Lineage::$tableName);
	}

  public static	function find_sources($gui, $target_id) {
    $ressources = array();
    $linages = $this->find_where('target_id = ' . $target_id);
    if (count($lineages) > 0) {
      $ressources = $ressource->find_where("id IN (" . implode(', ',
        array_map(
          function($lineage) {
            return $lineage->get('source_id');
          },
          $linages
        )) . ")"
      );
    }
    return $ressources;
  }

  public static	function find_source_ids($gui, $target_id) {
    return array_map(
      function($lineage) {
        return $lineage->get('source_id');
      },
      $this->find_where('target_id = ' . $target_id)
    );
  }

  public static	function find_targets($gui, $souce_id) {
    $ressources = array();
    $lineages = $this->find_where('source_id = ' . $source_id);
    if (count($lineages) > 0) {
      $ressources = $ressource->find_where("id IN (" . implode(', ',
        array_map(
          function($lineage) {
            return $lineage->get('target_id');
          },
          $linages
        )) . ")"
      );
    }
    return $ressources;
  }

}

?>
