<?php
class MyObject {

	static $write_debug = false;

	function MyObject($gui, $tableName) {
		$this->gui = $gui;
		$this->debug = $gui->debug;
		$this->database = $gui->database;
		$this->tableName = $tableName;
		$this->identifier = 'id';
		$this->identifier_type = 'integer';
		$this->data = array();
		$this->debug->show('<p>New MyObject for table: '. $this->tableName, MyObject::$write_debug);
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
		$this->debug->show('<p>sql: ' . $sql, MyObject::$write_debug);
		$query = mysql_query($sql, $this->database->dbConn);
		$this->data = mysql_fetch_assoc($query);
		return $this;
	}

	/*
	* Search for an record in the database
	* by the given where clause
	* @ return all objects
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
		$this->debug->show('mysql find_where sql: ' . $sql, MyObject::$write_debug);
		$query = mysql_query($sql, $this->database->dbConn);
		$result = array();
		while($this->data = mysql_fetch_assoc($query)) {
			$result[] = clone $this;
		}
		return $result;
	}

	/*
	* Search for an record in the database
	* by the given sql clause
	* @ return all found objects
	*/
	function find_by_sql($params) {
		$sql = "
			SELECT
				" . (!empty($params['select']) ? $params['select'] : '*') . "
			FROM
				" . (!empty($params['from']) ? $params['from'] : "`" . $this->tableName . "`") . "
			WHERE
				" . (!empty($params['where']) ? $params['where'] : '') . "
				" . (!empty($params['order']) ? 'ORDER BY ' . $params['order'] : '') . "
		";
		$this->debug->show('mysql find_by_sql sql: ' . $sql, MyObject::$write_debug);
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

	function setAttributes($keys) {
		foreach ($keys AS $key) {
			if (!array_key_exists($key, $this->data)) {
				$this->set($key, NULL);
			}
		}
	}

	function getValues() {
		return array_values($this->data);
	}

	function getKVP() {
		$kvp = array();
		if (is_array($this->data)) {
			foreach($this->data AS $key => $value) {
				$kvp[] = "`" . $key . "` = '" . $value . "'";
			}
		}
		return $kvp;
	}

	function get($attribute) {
		return $this->data[$attribute];
	}

	function set($attribute, $value) {
		$this->data[$attribute] = $value;
	}

	function create($data = array()) {
		$this->debug->show('<p>MyObject create ' . $this->tablename, MyObject::$write_debug);
		if (!empty($data))
			$this->data = $data;

		$sql = "
			INSERT INTO `" . $this->tableName . "` (
				`" . implode('`, `', $this->getAttributes()) . "`
			)
			VALUES (
				" . implode(
					", ", 
					array_map(
						function ($value) {
							if ($value === NULL) {
								$v = 'NULL';
							}
							else if (is_numeric($value)) {
								$v = "'" . $value . "'";
							}
							else {
								$v = "'" . addslashes($value) . "'";
							}
							return $v;
						},
						$this->getValues()
					)
				) . "
			)
		";
		$this->debug->show('<p>sql: ' . $sql, MyObject::$write_debug);
		mysql_query($sql);
		$new_id = mysql_insert_id();
		$this->debug->show('<p>new id: ' . $new_id, MyObject::$write_debug);
		$this->set($this->identifier, $new_id);
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
		$this->debug->show('<p>sql: ' . $sql, MyObject::$write_debug);
		$query = mysql_query($sql);
		return mysql_error($this->database->dbConn);
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
		$this->debug->show('MyObject delete sql: ' . $sql, MyObject::$write_debug);
		$result = mysql_query($sql);
		return $result;
	}
}
?>
