<?php
#############################
# Klasse RP_Plan #
#############################

class RP_Plan extends PgObject {

  static $schema = 'gml_classes';
  static $tableName = 'rp_plan';

  function RP_Plan($gui, $select = '*') {
    $this->PgObject($gui, RP_Plan::$schema, RP_Plan::$tableName);
    $this->bereiche = array();
    $this->select = $select;
  }

  function select($select){
    $this->select = $select;
  }

  function find_by($attribute, $value) {
    $sql = "
      SELECT
        $this->select
      FROM
        \"" . $this->schema . "\".\"" . $this->tableName . "\"
      WHERE
        \"" . $attribute . "\" = '" . $value . "'
    ";
    $this->debug('<p>find_by sql: ' . $sql);
    $query = pg_query($this->database->dbConn, $sql);
    $this->data = pg_fetch_assoc($query);
  }

  }

?>
