<?php
#############################
# Klasse RP_Bereich #
#############################

class RP_Bereich extends PgObject {

  function RP_Bereich($gui, $schema, $tableName) {
    $this->PgObject($gui, $schema, $tableName);
    $this->rp_objekte = array();
  }

  function holeObjekte() {
    $this->rp_objekte;
  }

}

?>
