<?php
#############################
# Klasse Konvertierung #
#############################

class Konvertierung extends PgObject {

  static $STATUS = array('in Erstellung', 'erstellt', 'in Validierung', 'Validierung fehlgeschlagen' ,'validiert', 'in Konvertierung', 'Konvertierung abgeschlossen', 'Konvertierung abgebrochen');

  function Konvertierung($pgDatabase, $schema, $tableName) {
    $this->PgObject($pgDatabase, $schema, $tableName);
  }

}

?>
