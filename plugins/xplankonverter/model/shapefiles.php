<?php
#############################
# Klasse Konvertierung #
#############################

class ShapeFile extends PgObject {

  function ShapeFile($pgDatabase, $schema, $tableName) {
    $this->PgObject($pgDatabase, $schema, $tableName);
  }

}
  
?>
