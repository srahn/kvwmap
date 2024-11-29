<?php
#############################
# Klasse SubRessourceRange #
#############################

class SubRessourceRange extends PgObject {
	
	static $schema = 'metadata';
	static $tableName = 'subressourceranges';
	static $write_debug = false;

	function __construct($gui) {
		$gui->debug->show('Create new Object ressourcerange', SubRessourceRange::$write_debug);
		parent::__construct($gui, SubRessourceRange::$schema, SubRessourceRange::$tableName);
	}

  function find_by_subressource_id($subressource_id) {
    return $this->find_where('subressource_id = ' . $subressource_id);
  }

}

?>
