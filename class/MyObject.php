<?php
# mvbio_dev
class MyObject {

	static $write_debug = false;
	public $gui;
	public $debug;
	public $database;
	public $stelle_id;
	public $tableName;
	public $identifier;
	public $identifier_type;
	public $identifiers;
	public $field_types;
	public $data;
	public $has_many;
	public $children_ids;
	public $validations;

	function __construct($gui, $tableName, $identifier = 'id', $identifier_type = 'integer') {
		$this->gui = $gui;
		$this->debug = $gui->debug;
		$this->database = $gui->database;
		$this->tableName = $tableName;
		$this->identifier = $identifier;
		$this->identifier_type = $identifier_type;
		$this->identifiers = ($this->identifier_type == 'array' ? $identifier : array());
		$this->data = array();
		$this->children_ids = array();
		$this->validations = array();
		if (!empty($this->has_many)) {
			foreach ($this->has_many AS $key => $relation) {
				$this->$key = array();
			}
		}
		$this->field_types = array(
			MYSQLI_TYPE_DECIMAL => 'MYSQLI_TYPE_DECIMAL',
			MYSQLI_TYPE_NEWDECIMAL => 'MYSQLI_TYPE_NEWDECIMAL',
			MYSQLI_TYPE_BIT => 'MYSQLI_TYPE_BIT',
			MYSQLI_TYPE_TINY => 'MYSQLI_TYPE_TINY',
			MYSQLI_TYPE_SHORT => 'MYSQLI_TYPE_SHORT',
			MYSQLI_TYPE_LONG => 'MYSQLI_TYPE_LONG',
			MYSQLI_TYPE_FLOAT => 'MYSQLI_TYPE_FLOAT',
			MYSQLI_TYPE_DOUBLE => 'MYSQLI_TYPE_DOUBLE',
			MYSQLI_TYPE_NULL => 'MYSQLI_TYPE_NULL',
			MYSQLI_TYPE_TIMESTAMP => 'MYSQLI_TYPE_TIMESTAMP',
			MYSQLI_TYPE_LONGLONG => 'MYSQLI_TYPE_LONGLONG',
			MYSQLI_TYPE_INT24 => 'MYSQLI_TYPE_INT24',
			MYSQLI_TYPE_DATE => 'MYSQLI_TYPE_DATE',
			MYSQLI_TYPE_TIME => 'MYSQLI_TYPE_TIME',
			MYSQLI_TYPE_DATETIME => 'MYSQLI_TYPE_DATETIME',
			MYSQLI_TYPE_YEAR => 'MYSQLI_TYPE_YEAR',
			MYSQLI_TYPE_NEWDATE => 'MYSQLI_TYPE_NEWDATE',
			MYSQLI_TYPE_INTERVAL => 'MYSQLI_TYPE_INTERVAL',
			MYSQLI_TYPE_ENUM => 'MYSQLI_TYPE_ENUM',
			MYSQLI_TYPE_SET => 'MYSQLI_TYPE_SET',
			MYSQLI_TYPE_TINY_BLOB => 'MYSQLI_TYPE_TINY_BLOB',
			MYSQLI_TYPE_MEDIUM_BLOB => 'MYSQLI_TYPE_MEDIUM_BLOB',
			MYSQLI_TYPE_LONG_BLOB => 'MYSQLI_TYPE_LONG_BLOB',
			MYSQLI_TYPE_BLOB => 'MYSQLI_TYPE_BLOB',
			MYSQLI_TYPE_VAR_STRING => 'MYSQLI_TYPE_VAR_STRING',
			MYSQLI_TYPE_STRING => 'MYSQLI_TYPE_STRING',
			MYSQLI_TYPE_CHAR => 'MYSQLI_TYPE_CHAR',
			MYSQLI_TYPE_GEOMETRY => 'MYSQLI_TYPE_GEOMETRY',
			MYSQLI_TYPE_JSON => 'MYSQLI_TYPE_JSON'
		);
/*
		tinyint_    1
		boolean_    1
		smallint_    2
		int_        3
		float_        4
		double_        5
		real_        5
		timestamp_    7
		bigint_        8
		serial        8
		mediumint_    9
		date_        10
		time_        11
		datetime_    12
		year_        13
		bit_        16
		decimal_    246
		text_        252
		tinytext_    252
		mediumtext_    252
		longtext_    252
		tinyblob_    252
		mediumblob_    252
		blob_        252
		longblob_    252
		varchar_    253
		varbinary_    253
		char_        254
		binary_        254
*/
	}

	/**
	 * Search for an record in the database
	 * by the given attribut and value
	 * @param string $attribut Attribute you searching for.
	 * @param string $value Value that shall fit the attribute.
	 * @return MyObject This object with the record in data or empty array if not found
	 */
	function find_by($attribute, $value) {
		if (empty($attribute)) {
			$attribute = $this->identifer;
		}
		if (empty($value)) {
			$value = $this->get_id();
		}
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
		$rs = $this->mysqli_fetch_assoc_casted($this->database->result);
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
		$ret = $this->database->execSQL($sql);
		if (!$ret['success']) {
			echo '<br>Fehler bei find_by_ids: ' . $ret['err_msg'];
			return $this;
		}
		$rs = $this->database->result->fetch_assoc();
		if ($rs !== false) {
			$this->data = $rs;
		}
		else {
			// Hier auch eine Fehlermeldung?
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

	/**
	 * Search for records in the database by the given where clause
	 * @param string $where WHERE clause to find the objects.
	 * @param string $order? ORDER clause to sort the results.
	 * @param string $sort_direction? Sort direction to sort the results.
	 * @return MyObject[] All found objects.
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
			($order != '' ? " ORDER BY " . $q . implode($q . ', ' . $q, $orders) . $q . ($sort_direction == 'DESC' ? ' DESC' : ' ASC') : "") . "
		";
		$this->debug->show('mysql find_where sql: ' . $sql, MyObject::$write_debug);
		$this->debug->write('mysql find_where sql: ' . $sql);
		$this->database->execSQL($sql);
		$result = array();
		while ($this->data = $this->database->result->fetch_assoc()) {
			$result[] = clone $this;
		}
		return $result;
	}

	/**
	 * 
	 * Function searching for records in the database by the given sql clause
	 * @param Array $params: Array with select, from, where and order parts of sql.
	 * @return Array $results: All found objects.
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

	function mysqli_fetch_assoc_casted($result) {
		$fields = mysqli_fetch_fields($result);
		$types = array();
		foreach ($fields as $field) {
			switch ($field->type) {
				case MYSQLI_TYPE_DECIMAL:
				case MYSQLI_TYPE_NEWDECIMAL:
				case MYSQLI_TYPE_FLOAT:
				case MYSQLI_TYPE_DOUBLE:
					$types[$field->name] = 'float';
					break;
				case MYSQLI_TYPE_BIT:
					$types[$field->name] = 'boolean';
					break;
				case MYSQLI_TYPE_TINY:
				case MYSQLI_TYPE_SHORT:
				case MYSQLI_TYPE_LONG:
				case MYSQLI_TYPE_LONGLONG:
				case MYSQLI_TYPE_INT24:
				case MYSQLI_TYPE_YEAR:
				case MYSQLI_TYPE_ENUM:
					$types[$field->name] = 'int';
					break;
				case MYSQLI_TYPE_TIMESTAMP:
				case MYSQLI_TYPE_DATE:
				case MYSQLI_TYPE_TIME:
				case MYSQLI_TYPE_DATETIME:
				case MYSQLI_TYPE_NEWDATE:
				case MYSQLI_TYPE_INTERVAL:
				case MYSQLI_TYPE_SET:
				case MYSQLI_TYPE_VAR_STRING:
				case MYSQLI_TYPE_STRING:
				case MYSQLI_TYPE_CHAR:
				case MYSQLI_TYPE_GEOMETRY:
					$types[$field->name] = 'string';
					break;
				case MYSQLI_TYPE_TINY_BLOB:
				case MYSQLI_TYPE_MEDIUM_BLOB:
				case MYSQLI_TYPE_LONG_BLOB:
				case MYSQLI_TYPE_BLOB:
					$types[$field->name] = 'string';
					break;
				default:
					$types[$field->name] = 'string';
			}
			$this->debug->show('Cast attribute: ' . $field->name . ' (' . $field->type . ':' . $this->field_types[$field->type] . ' => ' . $types[$field->name] . ')', MyObject::$write_debug);
		}
		$rs = mysqli_fetch_assoc($result);
		foreach ($types as $name => $type) {
			if (gettype($rs[$name]) != 'NULL') {
				settype($rs[$name], $type);
			}
		}
		return $rs;
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

	function get_id() {
		return $this->get($this->identifier);
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
			$attributes[] = new MyAttribute($this->debug, $key, $this->columns[$key]['Type'], $value, $attribute_validations, $this->identifier);
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
		$this->data = array_map(function($attribute) { return null; }, array_flip(array_intersect(array_keys($formvars), array_map(function($attribute) { return $attribute['Field']; }, $this->getColumnsFromTable()))));
	}

	function getColumnsFromTable() {
		$this->debug->show('getColumnsFromTable', MyObject::$write_debug);
		$this->columns = array();
		$sql = "
			SHOW COLUMNS
			FROM
				`" . $this->tableName . "`
		";
		$this->debug->show('sql: ' . $sql, MyObject::$write_debug);
		$this->database->execSQL($sql);
		while ($column = $this->database->result->fetch_assoc()) {
			$this->columns[$column['Field']] = $column;
		};
		return $this->columns;
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
		$this->debug->show('<br>Class MyObject Method get_identifier_expression', MyObject::$write_debug);
		$where = array();
		if (count($this->identifiers) > 0) {
			$where = array_map(
				function($identifier) {
					$quote = ($identifier['type'] == 'text' ? "'" : "");
					return $identifier['key'] . " = " . $quote . $this->get($identifier['key']) . $quote;
				},
				$this->identifiers
			);
		}
		else {
			if (
				in_array($this->identifier, $this->getKeys()) AND
				$this->get($this->identifier) != null AND
				$this->get($this->identifier) != ''
			) {
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

	function getKVP($options = array('escaped' => false), $data = array()) {
		#$this->debug->show('getKVP', MyObject::$write_debug);
		$data = (count($data) > 0 ? $data : $this->data);
		$types = $this->getTypesFromColumns();
		$kvp = array();
		if (is_array($data)) {
			foreach ($data AS $key => $value) {
				if ($options['escaped']) {
					$value = str_replace("'", "''", $value);
				}
				$kvp[] = "`" . $key . "` = " . (((stripos($types[$key], 'int') !== false OR stripos($types[$key], 'date') !== false OR stripos($types[$key], 'time') !== false OR stripos($types[$key], 'varchar') !== false) AND $value == '') ? 'NULL' : "'" . $value . "'");
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
	
	function print_data() {
		echo '<br>data: ' . print_r($this->data, true);
	}

	function create($data = array()) {
		$this->debug->show('<p>MyObject create ' . $this->tableName, MyObject::$write_debug);

		$results = array();
		if (!empty($data)) {
			$this->data = $data;
		}

		$sql = "
			INSERT INTO `" . $this->tableName . "` (
				`" . implode('`, `', $this->getKeys()) . "`
			)
			VALUES (
				" . implode(
					", ",
					array_map(
						function ($value) {
							if ($value === NULL OR $value == '' AND $value !== 0) {
								$v = 'NULL';
							}
							else if (is_numeric($value)) {
								$v = "'" . $value . "'";
							}
							else {
								$v = "'" . $this->database->mysqli->real_escape_string($value) . "'";
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
				'msg' => $this->database->errormessage,
				'err_msg' => $this->database->errormessage
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
								$v = "'" . $this->database->mysqli->real_escape_string($value) . "'";
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

	/**
	 * Update dataset.
	 * When $data is not empty first merge with $this->data.
	 * When $data is empty and $update_all_attributes is false do nothing else.
	 * When $update_all_attributes is true update with $this->data else only $data.
	 * @param Array $data Array with key, value pairs of attributes.
	 * @param Boolean $update_all_attributes. Update $this->data or only $data.
	 * @return Array('success', 'msg', 'err_msg')
	 */
	function update($data = array(), $update_all_attributes = true) {
		$results = array();
		if (!empty($data)) {
			$this->debug->show('Merge this->data: ' . print_r($this->data, true) . ' mit data: ' . print_r($data, true), MyObject::$write_debug);
			$this->data = array_merge($this->data, $data);
		}

		if (empty($data) AND $update_all_attributes == false) {
			$results = array(array(
				'success' => true,
				'err_msg' => '',
				'msg' => ''
			));
		}
		else {
			$sql = "
				UPDATE
					`" . $this->tableName . "`
				SET
					" . implode(', ', $this->getKVP(array('escaped' => true), ($update_all_attributes ? $this->data : $data))) . "
				WHERE
					" . $this->get_identifier_expression() . "
			";
			$this->debug->show('<p>sql: ' . $sql, MyObject::$write_debug);
			$this->database->execSQL($sql);
			$err_msg = $this->database->errormessage;
			$results[] = array(
				'success' => ($err_msg == ''),
				'err_msg' => ($err_msg == '' ? '' : $err_msg . ' Aufgetreten bei SQL: ' . $sql),
				'msg' => ($err_msg == '' ? '' : $err_msg . ' Aufgetreten bei SQL: ' . $sql)
			);
		}
		return $results;
	}

	function delete($where = NULL) {
		$sql = "
			DELETE
			FROM
				`" . $this->tableName . "`
			WHERE
				" . ($where ?: $this->get_identifier_expression()) . "
		";
		#$this->debug->show('MyObject delete sql: ' . $sql, true);
		$result = $this->database->execSQL($sql);
		$err_msg = $this->database->errormessage;
		$result = array(
			'success' => ($err_msg == ''),
			'msg' => ($err_msg == '' ? 'Abfrage zum Löschen erfolgreich' : 'Fehler bei Ausführung der Löschanfrage!'),
			'err_msg' => ($err_msg == '' ? '' : $err_msg . ' Aufgetreten bei SQL: ' . $sql)
		);
		return $result;
	}

	function reset_auto_increment() {
		$sql = "
			ALTER TABLE `" . $this->tableName . "`
			AUTO_INCREMENT = 1
		";
		$this->debug->show('MyObject delete sql: ' . $sql, MyObject::$write_debug);
		$result = $this->database->execSQL($sql);
	}

	public function validate($on = '') {
		$results = array();
		foreach ($this->validations AS $key => $validation) {
			$result = $this->validates($validation['attribute'], $validation['condition'], $validation['description'], $validation['option'], $on);
			$this->validations[$key]['validated'] = true;
			if (empty($result)) {
				$this->validations[$key]['valid'] = true;
			}
			else {
				$this->validations[$key]['valid'] = false;
				$this->validations[$key]['result'] = $result['msg'];
			}
			$results[] = $result;
		}

		$messages = array();
		foreach ($results AS $result) {
			if (!empty($result)) {
				$messages[] = $result;
			}
		}
		return $messages;
	}

	public function validates($key, $condition, $msg = '', $option = '', $on = '') {
		$this->debug->show('MyObject validates key: ' . $key . ' condition: ' . $condition . ' msg: ' . $msg . ' option: ' . print_r($option, true), MyObject::$write_debug);
		switch ($condition) {

			case 'date' :
				$result = $this->validate_date($key, $msg);
				break;

			case 'format' :
				$result = $this->validate_format($key, $msg, $option);
				break;

			case 'greater_or_equal' :
				$result = $this->validate_greater_or_equal($key, $msg, $option);
				break;

			case 'not_null' :
				$result = $this->validate_not_null($key, $msg);
				break;

			case 'pending_value' :
				$result = $this->validate_pending_value($key, $option, $msg);
				break;

			case 'presence' :
				$result = $this->validate_presence($key, $msg);
				break;

			case 'presence_one_of' :
				$result = $this->validate_presence_one_of($key, $msg);
				break;

			case 'unique' :
				$result = ($this->get($key) == '' ? '' : $this->validate_unique($key, $msg, $option, $on));
				break;

			case 'validate_value_is_one_off' :
				$result = $this->validate_value_is_one_off($key, $option, $msg);
				break;
		}
		$this->debug->show('MyObject validates result: ' . print_r($result, true), MyObject::$write_debug);
		return (empty($result) ? '' : array('type' => 'error', 'msg' => $result, 'attribute' => $key, 'condition' => $condition, 'option' => $option));
	}

	function validate_greater_or_equal($key, $msg, $option) {
		$this->debug->show('MyObject validate if ' . $key . ' = ' . $this->get($key) . ' is grater than ' . $option['other_key'] . '=' . $this->data[$option['other_key']], MyObject::$write_debug);
		if ($this->get($key) >= $this->get($option['other_key'])) {
			return '';
		}
		return $msg;
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
		if ($msg == '') {
			$msg = "Der Parameter <i>{$key}</i> darf nicht leer sein.";
		}

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

	function validate_date($key, $msg) {
		if ($this->get('key') != '') {
			$date_arr = explode('-', $this->get($key));
			if (!checkdate($date_arr[1], $date_arr[2], $date_arr[0])) {
				return $msg;
			}
		}
		return '';
	}

	function validate_date_format($key, $format) {
		$invalid_msg = array();
		DateTime::createFromFormat($format, $this->get($key));
		$last_errors = DateTime::getLastErrors();
		if ($last_errors === false) {
			return '';
		}
		else {
			if ($last_errors['warning_count'] > 0) $invalide_msg[] = '<b>Warung:</b><br>' . implode('<br>', $last_errors['warnings']);
			if ($last_errors['error_count']   > 0) $invalide_msg[] = '<b>Fehler:</b><br>' . implode('<br>', $last_errors['errors']);
			return implode('<p>', $invalid_msg);
		}
	}

	function as_form_html() {
		$attributes_html = array_map(
			function ($attribute) {
				return $attribute->as_form_html();
			},
			$this->getAttributes()
		);

		if (!empty($this->has_many) AND is_array($this->has_many)) {
			foreach ($this->has_many AS $key => $relation) {
				$many_attribut = new MyAttribute($this->debug, $key, 'fk', $this->$key, array(), $key, $relation);
				array_push($attributes_html, $many_attribut->as_form_html());
			}
		}

		$html = implode(
			"<div class=\"clear\"></div>",
			$attributes_html
		);
		return $html;
	}

}
?>
