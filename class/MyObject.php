<?php
class MyObject {
  
  function MyObject($gui, $tableName, $debug = false) {
    $this->debug = $gui->debug;
    $this->database = $gui->database;
    $this->tableName = $tableName;
    $this->identifier = 'id';
    $this->identifier_type = 'integer';
    $this->data = array();
  }

  /*
  * Search for an record in the database
  * by the given attribut and value
  * @ return an object with this record
  */
  function find_by($attribute, $value) {
    $sql = "
      SELECT
        *
      FROM
        `" . $this->tableName . "`
      WHERE
        `" . $attribute . "` = '" . $value . "'
    ";
    $this->debug->show('<p>sql: ' . $sql);
    $query = mysql_query($sql, $this->database->dbConn);
    $this->data = mysql_fetch_assoc($query);
    return $this;
  }

	/*
	* Search for an record in the database
	* by the given where clause
	* @ return an object with this record
	*/
	function find_where($where) {
		$sql = "
			SELECT
				*
			FROM
				`" . $this->tableName . "`
				WHERE
					" . $where . "
		";
		$this->debug->show('mysql find_where sql: ' . $sql, false);
		$query = mysql_query($sql, $this->database->dbConn);
		$result = array();
		while($this->data = mysql_fetch_assoc($query)) {
			$result[] = clone $this;
		}
		return $result;
	}

  function getAttributes() {
    return array_keys($this->data);
  }

  function getValues() {
    return array_values($this->data);
  }

  function getKVP() {
    $kvp = array();
    foreach($this->data AS $key => $value) {
      $kvp[] = "`" . $key . "` = '" . $value . "'";
    }
    return $kvp;
  }

  function get($attribute) {
    return $this->data[$attribute];
  }

  function set($attribute, $value) {
    $this->data[$attribute] = $value;
  }

  function create($data) {
    if (!empty($data))
      $this->data = $data;
    $sql = "
      INSERT INTO `" . $this->tableName . "` (
        `" . implode('`, `', $this->getAttributes()) . "`
      )
      VALUES (
        '" . implode("', '", $this->getValues()) . "'
      )
    ";
    $this->debug->show('<p>sql: ' . $sql);
    mysql_query($sql);
    $this->set('id', mysql_insert_id());
  }

  function update() {
    $sql = "
      UPDATE
        `" . $this->tableName . "`
      SET
        " . implode(', ', $this->getKVP()) . "
      WHERE
        `id` = " . $this->get('id') . "
    ";
    $this->debug->show('<p>sql: ' . $sql);
    $query = mysql_query($sql);
  }

  function delete() {
    $quote = ($this->identifier_type == 'text') ? "'" : "";
    $sql = "
      DELETE
      FROM
        `" . $this->tableName . "`
      WHERE
        " . $this->identifier . " = {$quote}" . $this->get($this->identifier) . "{$quote}
    ";
    $this->debug->show('MyObject delete sql: ' . $sql, true);
    $result = mysql_query($sql);
    return $result;
  }
}
?>
