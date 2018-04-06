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
		$this->children_ids = array();
		$this->debug->show('<p>New MyObject for table: '. $this->tableName, MyObject::$write_debug);
		$this->validations = array();
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
	function find_where($where, $order = '') {
		$where = ($where == '' ? '' : 'WHERE ' . $where);
		$orders = array_map(
			function ($order) {
				return trim($order);
			},
			explode(',', replace_semicolon($order))
		);
		$sql = "
			SELECT
				*
			FROM
				`" . $this->tableName . "`
			" . $where .
			($order != '' ? " ORDER BY `" . implode('`, `', $orders) . "`" : "");
		$this->debug->show('mysql find_where sql: ' . $sql, MyObject::$write_debug);
		$query = mysql_query($sql, $this->database->dbConn);
		$result = array();
		while($this->data = mysql_fetch_assoc($query)) {
			$result[] = clone $this;
		}
		return $result;
	}

	/*
	* Search for a records in the database by the given sql clause
	* @ return all found objects
	*/
	function find_by_sql($params, $hierarchy_key = NULL) {
		$sql = "
			SELECT
				" . (!empty($params['select']) ? $params['select'] : '*') . "
			FROM
				" . (!empty($params['from']) ? $params['from'] : "`" . $this->tableName . "`") . "
			WHERE
				" . (!empty($params['where']) ? $params['where'] : '') . "
				" . (!empty($params['order']) ? 'ORDER BY ' . replace_semicolon($params['order']) : '') . "
		";
		$this->debug->show('mysql find_by_sql sql: ' . $sql, MyObject::$write_debug);
		$this->debug->write('#mysql find_by_sql sql:<br> ' . $sql.';<br>',4);
		$query = mysql_query($sql, $this->database->dbConn);
		$results = array();
		while($this->data = mysql_fetch_assoc($query)) {
			if($hierarchy_key == NULL){
				$results[] = clone $this;
			}
			else{
				$results[$this->data[$this->identifier]] = clone $this;		// create result-array as associative array
				if($this->data[$hierarchy_key] > 0 AND $results[$this->data[$hierarchy_key]] != NULL)$results[$this->data[$hierarchy_key]]->children_ids[] = $this->data[$this->identifier];		// add this id to parents children array
			}
		}
		return $results;
	}

	function getAttributes() {
		$attributes = [];
		foreach ($this->data AS $key => $value) {
			$attribute_validations  = array_filter(
				$this->validations,
				function ($validation) use ($key) {
					return $validation['attribute'] == $key;
				}
			);
			$attributes[] = new MyAttribute($this->debug, $key, 'text', $value, $attribute_validations, $this->identifier);
		}
		return $attributes;
	}

	function getKeys() {
		return array_keys($this->data);
	}

	function has_key($key) {
		return in_array($key, $this->getKeys());
	}

	function setKeys($keys) {
		foreach ($keys AS $key) {
			if (!array_key_exists($key, $this->data)) {
				$this->set($key, NULL);
			}
		}
	}

	function setKeysFromTable() {
		$columns = $this->getColumnsFromTable();
		foreach($columns AS $column) {
			$this->set($column['Field'], NULL);
		}
		return $this->getKeys();
	}

	function getColumnsFromTable() {
		$columns = array();
		$sql = "
			SHOW COLUMNS
			FROM
				`" . $this->tableName . "`
		";
		$this->debug->show('<p>sql: ' . $sql, MyObject::$write_debug);
		$result = mysql_query($sql, $this->database->dbConn);
		while ($column = mysql_fetch_assoc($result)) {
			$columns[] = $column;
		};
		return $columns;
	}

	function getTypesFromColumns() {
		$types = array();
		$columns = $this->getColumnsFromTable();
		foreach ($columns AS $column) {
			$types[$column['Field']] = $column['Type'];
		}
		return $types;
	}

	function setData($formvars) {
		foreach ($this->data AS $key => $value) {
			if (array_key_exists($key, $formvars)) {
				$this->set($key, $formvars[$key]);
			}
		}
	}

	function getValues() {
		return array_values($this->data);
	}

	function getKVP() {
		$types = $this->getTypesFromColumns();
		$kvp = array();
		if (is_array($this->data)) {
			foreach($this->data AS $key => $value) {
				$kvp[] = "`" . $key . "` = " . ((stripos($types[$key], 'int') !== false AND $value == '') ? 'NULL' : "'" . $value . "'");
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
				`" . implode('`, `', $this->getKeys()) . "`
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
		return NULL;
	}

	function update($data = array()) {
		$quote = ($this->identifier_type == 'text') ? "'" : "";
		if (!empty($data))
			$this->data = $data;

		$sql = "
			UPDATE
				`" . $this->tableName . "`
			SET
				" . implode(', ', $this->getKVP()) . "
			WHERE
				" . $this->identifier . " = {$quote}" . $this->get($this->identifier) . "{$quote}
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

	public function validate() {
		$results = array();
		foreach($this->validations AS $validation) {
			$results[] = $this->validates($validation['attribute'], $validation['condition'], $validation['description'], $validation['options']);
		}

		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public function validates($key, $condition, $msg = '', $option = '') {
		switch ($condition) {

			case 'presence' :
				$result = $this->validate_presence($key, $msg);
				break;

			case 'not_null' :
				$result = $this->validate_not_null($key, $msg);
				break;

			case 'presence_one_of' :
				$result = $this->validate_presence_one_of($key, $msg);
				break;

			case 'validate_value_is_one_off' :
				$result = $this->validate_value_is_one_off($key, $option, $msg);
				break;

			case 'format' :
				$result = $this->validate_format($key, $msg, $option);
				break;
		}

		return (empty($result) ? '' : array('type' => 'error', 'msg' => $result));
	}


	function validate_presence($key, $msg = '') {
		if (empty($msg)) {
			$msg = "Der Parameter <i>{$key}</i> wurde nicht an den Server übermittelt.";
		}

		return (array_key_exists($key, $this->data) ? '' : $msg);
	}

	function validate_not_null($key, $msg = '') {
		if ($msg == '') $msg = "Der Parameter <i>{$key}</i> darf nicht leer sein.";

		return ($this->get($key) != '' ? '' : $msg);
	}

	function validate_presence_one_of($keys, $msg = '') {
		if ($msg == '') $msg = 'Einer der Parameter <i>' . implode(', ', $keys) . '</i> muss angegeben und darf nicht leer sein.';

		$one_present = false;
		foreach($keys AS $key) {
			if (array_key_exists($key, $this->data) AND $this->get($key) != '') {
				$one_present = true;
			}
		}
		return ($one_present ? '' : $msg);
	}

	function validate_value_is_one_off($key, $allowed_values, $msg = '') {
		if ($msg == '') $msg = 'Der angegebene Wert ' . $this->get($key) . ' muss einer von diesen sein: <i>(' . implode(', ', $allowed_values) . '</i>)';
		return (in_array($this->get($key), $allowed_values) ? '' : $msg);
	}

	function validate_format($key, $msg, $format) {
		$invalid_msg = '';
		# ToDo validate again regex pattern in format

		# This validates only the amount of parts separated by single spaces.
		$format_parts = explode(' ', $format);
		$value_parts = explode(' ', $this->get($key));

		if (count($format_parts) != count($value_parts)) {
			$invalid_msg = 'Der angegebene Wert <i>' . $this->get($key) . ' enthält nur ' . count($value_parts) . ' Bestandteile, muss aber ' . count($format_parts) . ' haben.';
		}

		return ($invalid_msg == '' ? '' : $msg . '<br>' . $invalid_msg);
	}

	function as_form_html() {
		$html = implode(
			"<div class=\"clear\"></div>",
			array_map(
				function ($attribute) {
					return $attribute->as_form_html();
				},
				$this->getAttributes()
			)
		);
		return $html;
	}

}
?>
