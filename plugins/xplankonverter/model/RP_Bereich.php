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

}

?>
