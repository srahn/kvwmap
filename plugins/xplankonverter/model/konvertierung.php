<?php
#############################
# Klasse Konvertierung #
#############################

class Konvertierung extends PgObject {

  static $STATUS = array('in Bearbeitung', 'Konvertierung offen', 'Konvertierung abgeschlossen');
  
  function Konvertierung($pgDatabase, $schema, $tableName) {
    $this->PgObject($pgDatabase, $schema, $tableName);
  }

}
  
?>
