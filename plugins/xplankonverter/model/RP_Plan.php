<?php
#############################
# Klasse RP_Plan #
#############################

class RP_Plan extends PgObject {

  function RP_Plan($gui, $schema, $tableName) {
    $this->PgObject($gui, $schema, $tableName);
    $this->bereiche = array();
  }


}

?>
