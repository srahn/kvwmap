<?php
#############################
# Klasse RP_Bereich #
#############################

class RP_Bereich extends PgObject {

  static $schema = 'xplan_gml';
  static $tableName = 'rp_bereich';

  function RP_Bereich($gui) {
    $this->PgObject($gui, RP_Bereich::$schema, RP_Bereich::$tableName);
    $this->rp_objekte = array();
  }

  function holeObjekte($konvertierung_id) {
    $this->rp_objekte;
  }

	function get_regeln() {
		$regel = new Regel($this->gui);
		$regeln = $regel->find_where("
			bereich_gml_id = '{$this->get('gml_id')}'
		");
		return $regeln;
	}

	/*
	* Löscht den Bereich und alles was dazugehört
	* Löscht dazugehörige Regeln
	*/
	function destroy() {
		$this->get_regeln();
		foreach($regeln AS $regel) {
			$regel->destroy();
		}
		$this->delete();
	}

}

?>
