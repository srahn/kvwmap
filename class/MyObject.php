<?php
# mvbio_dev
class MyObject {

	static $write_debug = false;

	function __construct($gui, $tableName, $identifier = 'id', $identifier_type = 'integer') {
		$this->gui = $gui;
		$this->debug = $gui->debug;
		$this->database = $gui->database;
		$this->tableName = $tableName;
		$this->identifier = $identifier;
		$this->identifier_type = $identifier_type;
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
		$this->database->execSQL($sql);
		$rs = $this->database->result->fetch_assoc();
		if ($rs !== false) {
			$this->data = $rs;
		}
		return $this;
	}

	/**
	* Search for a unique record in the database by identifier of the table
	* if $id is empty use the values from data array
	* else set the identifier from given value or array
	* @param $id string, text or associative array with id keys and values
	* @return an object with this record
	*/
	function find_by_ids($id) {
		if ($id) {
			if (getType($id) == 'array') {
				$this->data = $id;
			}
			else {
				$this->set($this->identifier, $id);
			}
		}
		$sql = "
			SELECT
				*
			FROM
				`" . $this->tableName . "`
			WHERE
				" . $this->get_identifier_expression() . "
		";
		$this->debug->show('<p>sql: ' . $sql, MyObject::$write_debug);
		$this->database->execSQL($sql);
		$rs = $this->database->result->fetch_assoc();
		if ($rs !== false) {
			$this->data = $rs;
		}
		return $this;
	}

	function setDataOnlyFormvars($formvars) {
		$columns = array_map(
			function($column) {
				return $column['Field'];
			},
			$this->getColumnsFromTable()
		);
		foreach ($formvars AS $key => $value) {
			if (in_array($key, $columns)) {
				$this->set($key, $formvars[$key]);
			}
		}
	}

	/*
	* Search for records in the database by the given where clause
	* @ return all objects
	*/
	function find_where($where, $order = '', $sort_direction = '') {
		$where = ($where == '' ? '' : 'WHERE ' . $where);
		if(strpos($order, '(') === false){
			$q = '`';		# wenn kein Funktion im order steht, quote-Zeichen verwenden
		}
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
			($order != '' ? " ORDER BY " .$q.implode($q.', '.$q, $orders).$q.($sort_direction == 'DESC' ? ' DESC' : ' ASC') : "") . "
		";
		$this->debug->show('mysql find_where sql: ' . $sql, MyObject::$write_debug);
		$this->database->execSQL($sql);
		$result = array();
		while ($this->data = $this->database->result->fetch_assoc()) {
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
			" . (!empty($params['where']) ? "WHERE " . $params['where'] : "") . "
			" . (!empty($params['order']) ? "ORDER BY " . replace_semicolon($params['order']) : "") . "
		";
		$this->debug->show('mysql find_by_sql sql: ' . $sql, MyObject::$write_debug);
		$this->database->execSQL($sql);
		$results = array();
		while ($this->data = $this->database->result->fetch_assoc()) {
			if ($hierarchy_key == NULL) {
				$results[] = clone $this;
			}
			else {
				$results[$this->data[$this->identifier]] = clone $this;		// create result-array as associative array
				if ($this->data[$hierarchy_key] > 0 AND value_of($results, $this->data[$hierarchy_key]) != NULL) {
					$results[$this->data[$hierarchy_key]]->children_ids[] = $this->data[$this->identifier];		// add this id to parents children array
				}
			}
		}
		return $results;
	}

	function exists($key) {
		$types = $this->getTypesFromColumns();

		$quote = ($types[$key] == 'text' OR strpos($types[$key], 'varchar') !== false) ? "'" : "";
		$sql = "
			SELECT
				`" . $key . "`
			FROM
				`" . $this->tableName . "`
			WHERE
				`" . $key . "` = {$quote}" . $this->get($key) . "{$quote} AND
				NOT " . $this->get_identifier_expression() . "
		";
		$this->debug->show('mysql exists sql: ' . $sql, MyObject::$write_debug);
		$this->database->execSQL($sql);
		return $this->database->result->num_rows > 0;
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
		return (is_array($this->data) ? array_keys($this->data) : array());
	}

	function has_key($key) {
		return ($key ? in_array($key, $this->getKeys()) : false);
	}

	function setKeys($keys) {
		foreach ($keys AS $key) {
			if (!array_key_exists($key, $this->data)) {
				$this->set($key, NULL);
			}
		}
	}

	function setKeysFromTable() {
		#$this->debug->show('setKeysFromTable', MyObject::$write_debug);
		$columns = $this->getColumnsFromTable();
		foreach($columns AS $column) {
			$this->set($column['Field'], NULL);
		}
		return $this->getKeys();
	}

	function setKeysFromFormvars($formvars) {
		$this->debug->show('setKeysFromFormvars', MyObject::$write_debug);
		$this->data = array_flip(array_intersect(array_keys($formvars), array_map(function($attribute) { return $attribute['Field']; }, $this->getColumnsFromTable())));
	}

	function getColumnsFromTable() {
		#$this->debug->show('getColumnsFromTable', MyObject::$write_debug);
		$columns = array();
		$sql = "
			SHOW COLUMNS
			FROM
				`" . $this->tableName . "`
		";
		$this->debug->show('sql: ' . $sql, MyObject::$write_debug);
		$this->database->execSQL($sql);
		while ($column = $this->database->result->fetch_assoc()) {
			$columns[] = $column;
		};
		return $columns;
	}

	function getTypesFromColumns() {
		#$this->debug->show('getTypesFromColumns', MyObject::$write_debug);
		$types = array();
		$columns = $this->getColumnsFromTable();
		foreach ($columns AS $column) {
			$types[$column['Field']] = $column['Type'];
		}
		return $types;
	}

	/**
	* Function return the expression to identify the unique dataset
	* SQL-Statements
	* It compose it from the identifier and its value or
	* from more identifiers if these are defined in an array
	* @return string The expression representing true or false in a sql statement
	*/
	function get_identifier_expression() {
	    $where = array();
		if ($this->identifier_type == 'array' AND getType($this->identifier) == 'array') {
			$where = array_map(
				function($id) {
					$quote = ($id['type'] == 'text' ? "'" : "");
					return $id['key'] . " = " . $quote . $this->get($id['key']) . $quote;
				},
				array_filter(
					$this->identifier,
					function($id) {
						return in_array($id['key'], $this->getKeys()) AND $this->get($id['key']) != null AND $this->get($id['key']) != '';
					}
				)
			);
		}
		else {
			if (in_array($this->identifier, $this->getKeys()) AND $this->get($this->identifier) != null AND $this->get($this->identifier) != '') {
				$quote = ($this->identifier_type == 'text' ? "'" : "");
				$where = array($this->identifier . " = " . $quote . $this->get($this->identifier) . $quote);
			}
		}
		return implode(' AND ', $where);
	}

	function setData($formvars) {
		foreach ($this->data AS $key => $value) {
			if (array_key_exists($key, $formvars)) {
				$this->set($key, $formvars[$key]);
			}
		}
	}

	function getValues() {
		$this->debug->show('getValues', MyObject::$write_debug);
		return array_values($this->data);
	}

	function getKVP($options = array('escaped' => false)) {
		#$this->debug->show('getKVP', MyObject::$write_debug);
		$types = $this->getTypesFromColumns();
		$kvp = array();
		if (is_array($this->data)) {
			foreach ($this->data AS $key => $value) {
				if ($options['escaped']) {
					$value = str_replace("'", "''", $value);
				}
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

		$results = array();
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
							if ($value === NULL OR $value == '') {
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
		$this->database->execSQL($sql);
		if ($this->database->success) {
			$new_id = $this->database->mysqli->insert_id;
			$new_id = ($new_id == 0 ? $this->get($this->identifier) : $new_id);
			$this->debug->show('<p>new id: ' . $new_id, MyObject::$write_debug);
			$this->set($this->identifier, $new_id);
			$results[] = array(
				'success' => true,
				'msg' => 'Datensatz erfolgreich angelegt.',
				'id' => $new_id
			);
		}
		else {
			$results[] = array(
				'success' => false,
				'msg' => $this->database->errormessage
			);
		}

		return $results;
	}

	/*
	* Function insert new dataset if not exists else update it with data values
	* corresonding to identifier attributes
	* INSERT INTO `layer_attributes2rolle` (
	*   `layer_id`, `attributename`, `stelle_id`, `user_id`, `switchable`, `switched_on`, `sortable`, `sort_order`, `sort_direction`
	* ) VALUES (
	*   146, 'user_id', 1, 1, 1, 1, 1, 1, 'desc'
	* ) ON DUPLICATE KEY UPDATE
	* layer_id = 146, `attributename` = 'user_id', stelle_id = 1, user_id = 1, switched_on = 1, sort_order = 2, sort_direction = 'desc'
	*/
	function insert_or_update() {
		$sql = "
			INSERT INTO `" . $this->tableName . "` (
				`" . implode('`, `', $this->getKeys()) . "`
			)
			VALUES (
				" . implode(
					", ",
					array_map(
						function ($value) {
							if ($value === NULL OR $value == '') {
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
			ON DUPLICATE KEY UPDATE"
				. implode(', ', $this->getKVP()) . "
		";
		$this->debug->show('<p>insert_or_update: ' . $sql, MyObject::$write_debug);
		$this->database->execSQL($sql);
		$err_msg = $this->database->errormessage;
		$result = array(
			'success' => ($err_msg == ''),
			'err_msg' => $err_msg . ' Aufgetreten bei SQL: ' . $sql
		);
		return $result;
	}

	function update($data = array()) {
		$results = array();
		if (!empty($data)) {
			array_merge($this->data, $data);
		}
		$sql = "
			UPDATE
				`" . $this->tableName . "`
			SET
				" . implode(', ', $this->getKVP(array('escaped' => true))) . "
			WHERE
				" . $this->get_identifier_expression() . "
		";
		$this->debug->show('<p>sql: ' . $sql, MyObject::$write_debug);
		$this->database->execSQL($sql);
		$err_msg = $this->database->errormessage;
		$results[] = array(
			'success' => ($err_msg == ''),
			'err_msg' => $err_msg . ' Aufgetreten bei SQL: ' . $sql
		);
		return $results;
	}

	function delete() {
		$sql = "
			DELETE
			FROM
				`" . $this->tableName . "`
			WHERE
				" . $this->get_identifier_expression() . "
		";
		$this->debug->show('MyObject delete sql: ' . $sql, MyObject::$write_debug);
		$result = $this->database->execSQL($sql);
		return $result;
	}

	public function validate($on = '') {
		$results = array();
		foreach($this->validations AS $validation) {
			$results[] = $this->validates($validation['attribute'], $validation['condition'], $validation['description'], $validation['option'], $on);
		}

		$messages = array();
		foreach($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public function validates($key, $condition, $msg = '', $option = '', $on = '') {
		$this->debug->show('MyObject validates key: ' . $key . ' condition: ' . $condition . ' msg: ' . $msg . ' option: ' . $option, MyObject::$write_debug);
		switch ($condition) {

			case 'presence' :
				$result = $this->validate_presence($key, $msg);
				break;

			case 'pending_value' :
				$result = $this->validate_pending_value($key, $option, $msg);
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

			case 'unique' :
				$result = ($this->get($key) == '' ? '' : $this->validate_unique($key, $msg, $option, $on));
				break;
		}
		$this->debug->show('MyObject validates result: ' . print_r($result, true), MyObject::$write_debug);
		return (empty($result) ? '' : array('type' => 'error', 'msg' => $result));
	}

	function validate_presence($key, $msg = '') {
		if (empty($msg)) {
			$msg = "Der Parameter <i>{$key}</i> wurde nicht an den Server übermittelt.";
		}

		return (array_key_exists($key, $this->data) ? '' : $msg);
	}

  /*
  * Validates the presence of a value in $key that is dependent on an $pending_key
  * Returns an error msg if keys not exists or its value is empty even though pending_key exists and its value is not empty
  */
  function validate_pending_value($key, $pending_key, $msg = '') {
    if (
      array_key_exists($pending_key, $this->data) AND
      !empty($this->data->get($pending_key)) AND (
        !array_key_exists($key, $this->data) OR
        empty($this->data->get($key))
      )
    ) {
      $msg = "Wenn im Attribut ${pending_key} ein Wert angegeben ist, muss im Attribut ${key} auch einer angegeben sein!";
    }
    return $msg;
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
		if ($msg == '') $msg = 'Der im Attribut <i>' . $key . '</i> angegebene Wert <i>' . $this->get($key) . '</i> muss einer von diesen sein: <i>(' . implode(', ', $allowed_values) . '</i>)';
		return (in_array($this->get($key), $allowed_values) ? '' : $msg);
	}

	/*
	* Prüft ob der Wert im Attribut key innerhalb der vorhandenen Datensätze schon vorkommt
	* Wenn ja, ist die Validierung nicht bestanden
	*/
	function validate_unique($key, $msg = '', $option = '', $on = '') {
		$msg = $msg . ' Der Wert ' . $this->get($key) . ' im Attribut ' . $key . ' existiert schon.';
		if ($option == $on) {
			return ($this->exists($key) ? $msg : '');
		}
		else {
			return ''; # nicht validieren
		}
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
