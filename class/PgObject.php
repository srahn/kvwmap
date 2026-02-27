<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen										 #
###################################################################
# Lizenz																													#
#																																 #
# Copyright (C) 2004	Peter Korduan															 #
#																																 #
# This program is free software; you can redistribute it and/or	 #
# modify it under the terms of the GNU General Public License as	#
# published by the Free Software Foundation; either version 2 of	#
# the License, or (at your option) any later version.						 #
#																																 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of	#
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the		#
# GNU General Public License for more details.										#
#																																 #
# You should have received a copy of the GNU General Public			 #
# License along with this program; if not, write to the Free			#
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,	#
# MA 02111-1307, USA.																							#
#																																	#
# Kontakt:																												#
# peter.korduan@gdi-service.de																		#
# stefan.rahn@gdi-service.de																			#
###################################################################
#############################
# Klasse PgObject #
#############################

class PgObject {
	/*
	* Durch die Übergabe von gui besitzt das Object die Datenbankverbindung
	*	$this->database Postgres Datenbank
	* $this->gui->pgdatabase PostgresDatenbank
	*
	*/
	static $write_debug = false;
	static $schema;
	static $tableName;
	static $identifier;
	public $debug;
	public $gui;
	public $database;
	public $qualifiedTableName;
	public $children_ids;
	public $select;
	public $from;
	public $where;
	public $show;
	public $attribute_names;
	public $fkeys;
	public $pkey;
	public $data;
	public $identifier_type;
	public $identifiers;
	public $attribute_types;
	public $geom_column;
	public $extent;
	public $extents;

	function __construct($gui, $schema_name, $table_name, $identifier = 'id', $identifier_type = 'integer') {
		$gui->debug->show('Create new Object PgObject with schema ' . $schema_name . ' table ' . $table_name, $this->show);
		$this->debug = $gui->debug;
		$this->gui = $gui;
		$this->database = $gui->pgdatabase;
		$this->schema = $schema_name;
		$this->tableName = $table_name;
		$this->qualifiedTableName = $schema_name . '.' . $table_name;
		$this->data = array();
		$this->select = '*';
		$this->from = '"' . $this->schema . '"."' . $this->tableName . '"';
		$this->where = '';
		$this->identifier = $identifier;
		$this->identifier_type = $identifier_type;
		$this->identifiers = ($this->identifier_type == 'array' ? $identifier : array(
				array(
					'column' => $identifier,
					'type' => $identifier_type
				)
			)
		);
		$this->show = false;
		$this->attribute_types = array();
		$this->geom_column = 'geom';
		$this->extent = array();
		$this->extents = array();
		$this->children_ids = array();
		$this->attribute_names = array();
		$this->fkeys = array();
		$this->pkey = array();
		$gui->debug->show('Create new Object PgObject with schema ' . $this->schema . ' table ' . $this->tableName, $this->show);
	}

	/**
	 * Dis function can be used to find if a function is called in static context
	 */
	public static function _isStatic() {
		$backtrace = debug_backtrace();

		// The 0th call is to _isStatic(), so we need to check the next
		// call down the stack.
		return $backtrace[1]['type'] == '::';
	}

	public static	function postgis_version($gui) {
		$query = pg_query(
			$gui->pgdatabase->dbConn, "
				SELECT split_part(postgis_version(), ' ', 1) AS postgis_version
			"
		);
		$result = pg_fetch_assoc($query);
		return floatval($result['postgis_version']);
	}

	function setKeys($keys) {
		foreach ($keys AS $key) {
			if (!array_key_exists($key, $this->data)) {
				$this->set($key, NULL);
			}
		}
	}

	/**
	 * @return PgObject $this->data is false if nothing found.
	 */
	function find_by($attribute, $value) {
		$this->debug->show('find by attribute ' . $attribute . ' with value ' . $value, $this->show);
		$sql = '
			SELECT
				' . $this->select . '
			FROM
				"' . $this->schema . '"."' . $this->tableName . '"
			WHERE
				"' . $attribute . '"';
		if ($value == '') {
			$sql .= ' IS NULL';
		}
		else {
			$sql .= ' = \'' . $value . '\'';
		}
		$this->debug->show('find_by sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		if (!$query) {
			echo 'Fehler beim Ausführen von SQL: ' . $sql . pg_last_error($this->database->dbConn);
			exit;
		}
		$this->data = pg_fetch_assoc($query);
		return $this;
	}

	function get_id_condition($ids = array()) {
		$parts = array();
		if (count($ids) == 0) {
			$ids = $this->get_ids();
		}
		foreach ($this->identifiers AS $identifier) {
			$quote = (array_filter(
				array('text', 'varchar', 'character', 'date', 'time', 'uuid'),
				function ($teil) use ($identifier) {
						return strpos($identifier['type'], $teil) !== false;
				}
			) ? "'" : "");
			$parts[] = '"' . $identifier['column'] . '" = ' . $quote . $ids[$identifier['column']] . $quote;
		}
		return implode(' AND ', $parts);
	}

	/**
	 * @return PgObject $this->data is false if nothing found.
	 */
	function find_by_ids($ids) {
		$where_condition = $this->get_id_condition($ids);
		$sql = "
			SELECT
				*
			FROM
				(
					SELECT
						{$this->select}
					FROM
						{$this->from}
					" . ($this->where != '' ? 'WHERE ' . $this->where : '') . "
				) foo
			WHERE
				" . $where_condition . "
		";
		$this->debug->show('find_by_ids sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		$this->data = pg_fetch_assoc($query);
		return $this;
	}

	function execSQL($sql) {
		$query = @pg_query($this->database->dbConn, $sql);
		return $query;
	}

	/**
	 * Search for an record in the database by the given where clause
	 * @param string $where
	 * @param string $order?
	 * @param string $select?
	 * @param string $limit?
	 * @return array PgObject An array with all found object
	 */
	function find_where($where, $order = NULL, $select = '*', $limit = NULL, $from = NULL) {
		$select = (empty($select) ? $this->select : $select);
		$from   = (empty($from) ? $this->schema . ".\"" . $this->tableName . "\"" : $from);
		$where  = (empty($where) ? "true" : $where);
		$order  = (empty($order) ? "" : " ORDER BY " . replace_semicolon($order));
		$limit  = (empty($limit) ? "" : " LIMIT " . replace_semicolon($limit));
		$sql = "
			SELECT
				" . $select . "
			FROM
				" . $from . "
			WHERE
				" . $where . "
			" . $order . "
			" . $limit . "
		";
		$this->debug->show('find_where sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		$results = array();
		while ($this->data = pg_fetch_assoc($query)) {
			$results[] = clone $this;
		}
		return $results;
	}

	function find_where_ohne_clone($where, $order = NULL, $select = '*', $limit = NULL, $from = NULL) {
		$select = (empty($select) ? $this->select : $select);
		$from   = (empty($from) ? $this->schema . ".\"" . $this->tableName . "\"" : $from);
		$where  = (empty($where) ? "true" : $where);
		$order  = (empty($order) ? "" : " ORDER BY " . replace_semicolon($order));
		$limit  = (empty($limit) ? "" : " LIMIT " . replace_semicolon($limit));
		$sql = "
			SELECT
				" . $select . "
			FROM
				" . $from . "
			WHERE
				" . $where . "
			" . $order . "
			" . $limit . "
		";
		$this->debug->show('find_where sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		if ($query == false) {
			echo $sql; exit;
		}
		$results = array();
		while ($data = pg_fetch_assoc($query)) {
			$results[] = $data;
		}
		return $results;
	}

	/**
	 * Function query, set and return extent of all features in epsg of $this->geom_column in $this->extent variable
	 * additional it query and set the extents in epsg given in $ows_srs string
	 * @param String $ows_srs: Empty space separated list of srs codes with or without EPSG: or epsg:
	 * e.g. "EPSG:25833 EPSG:25832 EPSG:4326 5650"
	 * with an empty string in $ows_srs only extent in geom_column srs will be queried, set and returned.
	 * @return Array Array with extent in geom_column srs, other extents will be set in extents array with epsg codes as keys
	 * $this->extent contains the array of minx, miny, maxx, maxy in srs of geom_column
	 * $this->extent['25832'] eg. contains the same extent in EPSG:25832
	 */
	function get_extent($ows_srs = '', $where = '') {
		if ($where == '') {
			$where = $this->get_id_condition(array($this->identifier => $this->get($this->identifier)));
		}
		$epsg_codes = explode(' ', trim(preg_replace('~[EPSGepsg: ]+~', ' ', $ows_srs)));
		$extents = array();
		$sql = "
			SELECT
				ST_XMin(ST_EXTENT(" . $this->geom_column . ")) AS minx,
				ST_YMin(ST_EXTENT(" . $this->geom_column . ")) AS miny,
				ST_XMax(ST_EXTENT(" . $this->geom_column . ")) AS maxx,
				ST_YMax(ST_EXTENT(" . $this->geom_column . ")) AS maxy
			FROM
				" . $this->schema . '.' . $this->tableName . "
			WHERE
				" . $where . "
		";
		#echo $sql; exit;
		$this->debug->show('get_extent sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		$this->extent = pg_fetch_assoc($query);

		foreach ($epsg_codes AS $epsg_code) {
			if ($epsg_code != '') {
				$geom_column = 'ST_Transform(' . $this->geom_column . ', ' . $epsg_code . ')';
				$sql = "
					SELECT
						ST_XMin(ST_EXTENT(" . $geom_column . ")) AS minx,
						ST_YMin(ST_EXTENT(" . $geom_column . ")) AS miny,
						ST_XMax(ST_EXTENT(" . $geom_column . ")) AS maxx,
						ST_YMax(ST_EXTENT(" . $geom_column . ")) AS maxy
					FROM
						" . $this->schema . '.' . $this->tableName . "
					WHERE
						" . $where . "
				";
				$sqls[] = $sql;
				$this->debug->show('get_extent sql: ' . $sql, $this->show);
				$query = pg_query($this->database->dbConn, $sql);
				$this->extents[$epsg_code] = pg_fetch_assoc($query);
			}
		}
		return $this->extent;
	}

	function exists($key) {
		$sql = "
			SELECT
				" . $key . "
			FROM
				\"{$this->schema}\".\"{$this->tableName}\"
			WHERE
				" . $key . " = " . quote($this->get($key)) . " AND
				NOT " . $this->get_id_condition() . "
		";
		$query = pg_query($this->database->dbConn, $sql);
		$result = pg_fetch_assoc($query);
		return ($result['num_rows'] > 0);
	}

	function delete_by($attribute, $value) {
		#echo '<br>delete by attribute ' . $attribute . ' with value ' . $value;
		$sql = "
			DELETE FROM
				\"{$this->schema}\".\"{$this->tableName}\"
			WHERE
				\"{$attribute}\" = '{$value}'
		";
		$this->debug->show('delete_by sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		return $query;
	}

	function getAttributes() {
		$attributes = [];
		$this->columns = $this->get_attribute_types();
		foreach ($this->data AS $key => $value) {
			$attribute_validations  = array_filter(
				$this->validations,
				function ($validation) use ($key) {
					return $validation['attribute'] == $key;
				}
			);
			$attributes[] = new PgAttribute($this->debug, $key, $this->columns[$key], $value, $attribute_validations, $this->identifier);
		}
		return $attributes;
	}

	function getKeys() {
		return array_keys($this->data);
	}

	function has_key($key) {
		return ($key ? in_array($key, $this->getKeys()) : false);
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

	function get_values_for_insert($escaped = false, $attribute_types = array()) {
		#echo '<br>attr_types in get_values_for_insert: ' . print_r($attribute_types, true);
		if (count($attribute_types) == 0) {
			$this->get_attribute_types();
		}
		$values = array();
		foreach ($this->data AS $key => $value) {
			$bracket = (in_array($attribute_types[$key], $this->database->pg_text_attribute_types) ? "'" : "");
			#echo '<br>is type: ' . $attribute_types[$key] . ' of key: ' . $key . ' in pg_text_attribute_types';

			if (is_array($value)) {
				$value = array_map(
					function($v) {
						return ($escaped ? pg_escape_string($v) : $v);
					},
					$value
				);
				$value = "{" . $bracket . implode($bracket . ", " . $bracket, $value) . $bracket . "}";
			}

			if ('' . $value == '') {
				$values[] = "NULL";
			}
			else {
				if ($attribute_types[$key] == 'boolean') {
					$values[] = ($value == 't' ? "true" : "false");
				}
				else {
					$values[] = $bracket . ($escaped ? pg_escape_string($value) : $value) . $bracket;
				}
			}
		}
		return $values;
	}

	function getKVP($escaped = false, $without_identifier = false, $data = array()) {
		$data = (count($data) > 0 ? $data : $this->data);
		$kvp = array();
		foreach ($data AS $key => $value) {
			if ($without_identifier AND ($key == $this->identifier)) {
				# identifier nicht ausgehen
			}
			else {
				if (is_array($value)) {
					$value = "{" . implode(", ", $value) . "}";
				}
				if ('' . $value == '') {
					$value = 'NULL';
				}

				$kvp[] = "\"" . $key . "\" = " . ($value == 'NULL' ? $value : "'" . ($escaped ? pg_escape_string($value) : $value) . "'");
			}
		}
		return $kvp;
	}

	function get($attribute) {
		// return (array_key_exists($attriubte, $this->data) ? $this->data[$attribute] : NULL);
		return $this->data[$attribute];
	}

	function get_id() {
		return $this->get($this->identifier);
	}

	function get_ids() {
		$ids = array();
		foreach ($this->identifiers AS $identifier) {
			$ids[$identifier['column']] = $this->get($identifier['column']);
		}
		return $ids;
	}

	function set($attribute, $value) {
		$this->data[$attribute] = $value;
		return $value;
	}

	function unset_($attribute) {
		unset($this->data[$attribute]);
	}

	function set_array($attribute, $value) {
		$this->data[$attribute][] = $value;
		return $this->data[$attribute];
	}

	function create($data = '') {
		if (!empty($data)) {
			$this->data = $data;
		}
		if ($this->data[$this->identifier] == '' OR $this->data[$this->identifier] == 0) {
			unset($this->data[$this->identifier]);
		}
		$values = array_map(
			function($value) {
				return (is_array($value) ? "{" . implode(", ", $value) . "}" : $value);
			},
			$this->getValues()
		);

		$sql = "
			INSERT INTO " . $this->qualifiedTableName . " (
				" . implode(', ', array_map(function($key) { return '"' . $key . '"'; }, $this->getKeys())) . "
			)
			VALUES (" .
				implode(
					", ",
					array_map(
						function($value) {
							return (($value === '' OR $value === null) ? "NULL" : "'" . pg_escape_string($value) . "'");
						},
						$values
					)
				) . "
			)
			RETURNING
				" . implode(', ', array_keys($this->get_ids())) . ";
		";
		/*
		$sql = "
			INSERT INTO " . $this->qualifiedTableName . " (
				$1
			)
			VALUES (
				'$2'
			)
			RETURNING
				$3
		";
		*/
		$this->debug->show('Create new dataset with sql: ' . $sql, $this->show);
		#echo 'SQL zum Eintragen des Datensatzes: ' . $sql; exit;
		/*
		$query = pg_query_params(
			$this->database->dbConn, $sql,
			array(
				implode(", ", $this->getKeys()),
				implode(
					"', '",
					array_map(
						function($value) {
							return pg_escape_string($value);
						},
						$values
					)
				),
				$this->identifier
			)
		);
		*/
		$query = pg_query($this->database->dbConn, $sql);
		if (!$query) {
			$this->debug->show('Error in create query: ' . pg_last_error($this->database->dbConn), true);
			return array(
				'success' => false,
				'msg' => 'Fehler in Create-Statement: ' . pg_last_error($this->database->dbConn));
		}
		$oid = pg_last_oid($query);
		if (empty($oid)) {
			$this->lastquery = $query;
			$returning_ids = pg_fetch_assoc($query);
			$this->find_by_ids($returning_ids);
		}
		else {
			$sql = "
				SELECT
					*
				FROM
					" . $this->qualifiedTableName . "
				WHERE
					oid = " . $oid . "
			";
			$this->debug->show('Query created dataset with new oid: ' . $sql, $this->show);
			$query = pg_query($this->database->dbConn, $sql);
			$this->data = pg_fetch_assoc($query);
		}
		$this->debug->show('Dataset created with ' . $this->get_id_condition(), $this->show);

		if ($this->database->success) {
			$results = array(
				'success' => true,
				'msg' => 'Datensatz erfolgreich angelegt.',
				'id' => $this->get($this->identifier)
			);
		}
		else {
			$results = array(
				'success' => false,
				'msg' => $this->database->errormessage,
				'err_msg' => $this->database->errormessage
			);
		}
		return $results;
	}
	/* Für Postgres Version in der RETURNING zusammen mit RULE und Bedingung funktioniert. 
	function create($data = '') {
		if (!empty($data))
			$this->data = $data;

		$values = array_map(
			function($value) {
				return (is_array($value) ? "{" . implode(", ", $value) . "}" : $value);
			},
			$this->getValues()
		);

		$sql = "
			INSERT INTO " . $this->qualifiedTableName . " (
				" . implode(', ', $this->getKeys()) . "
			)
			VALUES (" .
				"'" . implode("', '", $values) . "'
			)
			RETURNING id
		";
		$this->debug->show('create sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		$row = pg_fetch_assoc($query);
		$this->set($this->identifier, $row[$this->identifier]);
		return $this->get('id');
	} */

	function update($data = array(), $update_all_attributes = true) {
		$results = array();
		if (!empty($data)) {
			$this->data = array_merge($this->data, $data);
		}
		$results = [];
		$sql = "
			UPDATE
				\"" . $this->schema . "\".\"" . $this->tableName . "\"
			SET
				" . implode(', ', $this->getKVP(true, true, ($update_all_attributes ? $this->data : $data))) . "
			WHERE
				" . $this->get_id_condition() . "
		";
		$this->debug->show('update sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		if (!$query) {
			$err_msg = pg_last_error($this->database->dbConn);
		}
		$results = array(
			'success' => ($err_msg == ''),
			'err_msg' => ($err_msg == '' ? '' : $err_msg . ' Aufgetreten bei SQL: ' . $sql),
			'msg' => ($err_msg == '' ? '' : $err_msg . ' Aufgetreten bei SQL: ' . $sql)
		);
		return $results;
	}

	function update_attr($attributes, $set = false, $where = NULL) {
		$quote = ($this->identifier_type == 'text' ? "'" : "");
		$sql = "
			UPDATE
				\"" . $this->schema . "\".\"" . $this->tableName . "\"
			SET
				" . implode(', ', $attributes) . "
			WHERE
				" . ($where !== NULL ? $where : $this->get_id_condition()) . "
		";
		#echo $sql;
		$this->debug->show('update sql: ' . $sql, $this->show);
		try {
			pg_query($this->database->dbConn, $sql);
			if ($set) {
				foreach($attributes AS $attribute) {
					$parts = explode('=', $attribute);
					$this->set(trim($parts[0]), trim($parts[1], "'"));
				}
			}
			return array(
				'success' => true,
				'msg' => 'Attributes erfolgreich geupdated'
			);
		}
		catch (Exception $e) {
			return array(
				'success' => false,
				'msg' => 'Fehler bei der Abfrage ' . $sql . ': ' .  $e->getMessage()
			);
		}
	}

	function delete($where = NULL) {
		$sql = "
			DELETE
			FROM
				" . $this->qualifiedTableName . "
			WHERE
				" . ($where ?: $this->get_id_condition()) . "
		";
		$this->debug->show('delete sql: ' . $sql, $this->show);
		$result = pg_query($this->database->dbConn, $sql);
		$err_msg = $this->database->errormessage;
		return array(
			'success' => ($err_msg == ''),
			'msg' => ($err_msg == '' ? 'Abfrage zum Löschen erfolgreich' : 'Fehler bei Ausführung der Löschanfrage!'),
			'err_msg' => ($err_msg == '' ? '' : $err_msg . ' Aufgetreten bei SQL: ' . $sql),
			'result' => $result
		);
	}

	function getSQLResults($sql) {
		$query = pg_query($this->database->dbConn, $sql);
		$results = array();
		while ($rs = pg_fetch_assoc($query)) {
			$results[] = $rs;
		}
		return $results;
	}

	/**
	 * 
	 * Function searching for records in the database by the given sql params
	 * @param array $params: Array with select, from, where and order parts of sql.
	 * @return array $results: All found objects.
	 */
	function find_by_sql($params, $hierarchy_key = NULL) {
		$sql = "
			SELECT
				" . (!empty($params['select']) ? $params['select'] : '*') . "
			FROM
				" . (!empty($params['from']) ? $params['from'] : $this->schema . '.' . $this->tableName) . "
			" . (!empty($params['where']) ? "WHERE " . $params['where'] : "") . "
			" . (!empty($params['order']) ? "ORDER BY " . replace_semicolon($params['order']) : "") . "
		";
		// echo '<br>PgObject->find_by_sql with sql: ' . $sql;
		$this->debug->show('PgObject find_by_sql sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		if (!$query){echo $sql; exit;}
		$results = array();
		while ($this->data = pg_fetch_assoc($query)) {
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

	function setKeysFromTable() {
		#$this->debug->show('setKeysFromTable', PgObject::$write_debug);
		$columns = $this->get_attribute_types();
		foreach($columns AS $column => $type) {
			$this->set($column, NULL);
		}
		return $this->getKeys();
	}

	function setKeysFromFormvars($formvars) {
		$this->debug->show('setKeysFromFormvars', PgObject::$write_debug);
		$this->data = array_map(function($attribute) { return null; }, array_flip(array_intersect(array_keys($formvars), array_map(function($attribute) { return $attribute['Field']; }, $this->getColumnsFromTable()))));
	}


	/*
	* Fragt die foreign constraints der Tabelle ab und
	* speichert sie im Array $this->fkeys
	* Ein fkey enthält die Schlüssel constraint_name, child_schema, child_name und fkey_column
	*/
	function get_fkey_constraints() {
		$sql = "
			SELECT DISTINCT
			  ccu.column_name AS parent_id,
			  ccu.constraint_name AS constraint_name,
			  kcu.table_schema AS child_schema,
			  kcu.table_name AS child_table,
			  kcu.column_name AS fkey_column
			FROM
			  information_schema.constraint_column_usage ccu JOIN
			  information_schema.key_column_usage kcu ON ccu.constraint_name = kcu.constraint_name JOIN
				information_schema.table_constraints tc ON kcu.constraint_name = tc.constraint_name AND kcu.table_name = tc.table_name
			WHERE
				ccu.table_schema = '" . $this->schema . "' AND
				ccu.table_name = '" . $this->tableName . "' AND
				tc.constraint_type = 'FOREIGN KEY'
		";
		#echo '<p>sql zur Abfrage von fkey_constrains: ' . $sql;
		$query = pg_query($this->database->dbConn, $sql);
		$this->fkeys = array();
		while ($rs = pg_fetch_assoc($query)) {
			$this->fkeys[] = $rs;
		}
		return $this->fkeys;
	}

	function get_pkey_constraint() {
		$sql = "
			SELECT DISTINCT
			  cu.constraint_name,
			  tc.constraint_type,
			  string_agg(cu.column_name, ',') AS constraint_columns
			FROM
			  information_schema.table_constraints tc
			  JOIN information_schema.constraint_column_usage cu ON (
			    tc.table_schema = cu.table_schema AND
			    tc.table_name = cu.table_name AND
			    tc.constraint_name = cu.constraint_name
			  )
			WHERE
			  tc.table_schema = '" . $this->schema . "' AND
			  tc.table_name = '" . $this->tableName . "' AND
			  tc.constraint_type IN ('PRIMARY KEY')
			GROUP BY
				tc.constraint_type, cu.constraint_name
		";
		#echo '<p>sql zur Abfrage von fkey_constrains: ' . $sql;
		$query = pg_query($this->database->dbConn, $sql);
		$this->pkey = array();
		$rs = pg_fetch_assoc($query);
		$this->pkey = $rs;
		return $this->pkey;
	}

	function get_constraints() {
		$sql = "
			SELECT DISTINCT
			  cu.constraint_name,
			  tc.constraint_type,
			  string_agg(cu.column_name, ',') AS constraint_columns
			FROM
			  information_schema.table_constraints tc
			  JOIN information_schema.constraint_column_usage cu ON (
			    tc.table_schema = cu.table_schema AND
			    tc.table_name = cu.table_name AND
			    tc.constraint_name = cu.constraint_name
			  )
			WHERE
			  tc.table_schema = '" . $this->schema . "' AND
			  tc.table_name = '" . $this->tableName . "' AND
			  tc.constraint_type IN ('PRIMARY KEY', 'UNIQUE')
			GROUP BY
				tc.constraint_type, cu.constraint_name
		";
		#echo '<p>SQL zur Abfrage der Constraints der Tabelle: ' . $sql;
		$query = pg_query($this->database->dbConn, $sql);
		$this->constraints = array();
		while ($rs = pg_fetch_assoc($query)) {
			$this->constraints[] = array(
				'name' => $rs['constraint_name'],
				'type' => $rs['constraint_type'],
				'columns' => explode(',', $rs['constraint_columns'])
			);
		}
		return $this->constraints;
	}

	/**
	* Return true if $colum occure together with $other_columns at least in one same constraint colums list
	* requires call of get_constraints before
  * @param array(string) $columns
	* @return boolean true if all elements of $columns are in at least one constraint columns list
	*/
	function has_other_constraint_column($column, $other_columns) {
		if (count($other_columns) == 0) {
			return false;
		}
		$columns = array_unique(array_merge(array($column), $other_columns));
		return count(array_filter($this->constraints, function($constraint) use ($columns) {
			return $columns == array_intersect($constraint['columns'], $columns);
		})) > 0;
	}

	/**
	* Return true if $column is part of a primary key in any constraint column list
	* requires call of get_constraints before
	* @param string $column The name of the column
	* @return boolean true if $column is part of a primary key
	*/
	function is_part_of_primary_keys($column) {
		$result = count(array_filter($this->constraints, function($constraint) use ($column) {
			return $constraint['type'] == 'PRIMARY KEY' AND in_array($column, $constraint['columns']);
		})) > 0;
		return $result;
	}

	function get_attribute_names() {
		$sql = "
			SELECT
				column_name
			FROM
				information_schema.columns
			WHERE
				table_schema = '" . $this->schema . "' AND
				table_name = '" . $this->tableName . "'
		";
		#echo '<p>sql zur Abfrage von attribut namen: ' . $sql;
		$this->sql = $sql;
		$query = pg_query($this->database->dbConn, $sql);
		$this->attribute_names = array();
		while ($rs = pg_fetch_assoc($query)) {
			$this->attribute_names[] = $rs['column_name'];
		}
		return $this->attribute_names;
	}

	function get_attribute_types() {
		$sql = "
			SELECT
				column_name,
				data_type
			FROM
				information_schema.columns
			WHERE
				table_schema = '" . $this->schema . "' AND
				table_name = '" . $this->tableName . "'
		";
		#echo '<p>sql zur Abfrage von attribut typen: ' . $sql;
		$query = pg_query($this->database->dbConn, $sql);
		$this->attribute_types = array();
		while ($rs = pg_fetch_assoc($query)) {
			$this->attribute_types[$rs['column_name']] = $rs['data_type'];
		}
		return $this->attribute_types;
	}

	/**
	* Query all child elementes of a table related over given fk_id
	* @param String $child_schema - Name of the schema of child table
	* @param String $child_table - Name of the table of child table
	* @param String $fkey_column - Name of the column where the fkeys resists in child table
	* @param String $fk_id - ID of the parent to filter the childs that belongs to the parent
	* @return Array(PgObject) - The childs that belongs to the parent over this fkey constraint
	*/
	function find_childs($child_schema, $child_table, $fkey_column, $fk_id) {
		$table = new PgObject($this->gui, $child_schema, $child_table);
		$this->childs[$child_table] = $table->find_where($fkey_column . " = '" . $fk_id. "'");
		return $this->childs[$child_table];
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
		return (empty($result) ? '' : array('type' => 'error', 'msg' => $result, 'attribute' => $key, 'condition' => $condition, 'option' => $option));
	}

	function validate_greater_or_equal($key, $msg, $option) {
		$this->debug->show('PgObject validate if ' . $key . ' = ' . $this->get($key) . ' is grater than ' . $option['other_key'] . '=' . $this->data[$option['other_key']], PgObject::$write_debug);
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

	/*
	* Fragt die fkey constraints der Tabelle ab
	* Fragt die dazugehörigen childs ab uns setzt sie in der Variable $this->childs mit den child_table_name als keys im array
	*/
	function set_all_childs() {
		$fkeys = $this->get_fkey_constraints();
		foreach ($fkeys AS $fk) {
			$this->find_childs($fk['child_schema'], $fk['child_table'], $fk['fkey_column'], $this->get($this->identifier));
		}
	}

	/*
	* Erzeugt SQL Insert-Statements des Datensatzes und aller seiner verknüpften Unterdatensätze in der richtigen Reihenfolge
	* damit die Foreign Key Constaints berücksichtigt bleiben
	* Sucht nach fkeys der Tabelle des aktuellen Objektes
	* Wenn welche gefunden wurden
	*  führe für jeden fkey aus:
	*    Suche nach Childobjekten
	*    Wenn welche gefunden wurden
	*      führe für jedes child aus:
	*        rufe diese Funktion auf
	* Gebe das Insert-Statement dieses Datensatzes heraus.
	*/
	function as_inserts_with_childs($fkeys, $attribute_types) {
		$sql = $this->as_insert($attribute_types);
		#echo '<p>anzahl fkeys: ' . count($fkeys);
		foreach ($fkeys AS $fk) {
			$this->find_childs($fk['child_schema'], $fk['child_table'], $fk['fkey_column'], $this->get($this->identifier));
			#echo '<p>anzahl childs: ' . count($this->childs[$fk['child_table']]);
			if (count($this->childs[$fk['child_table']]) > 0) {
				#echo '<p>child 0: ' . $this->childs[$fk['child_table']][0]->tableName;
				# fragt die child fkeys nur vom ersten child ab und übergibt diese in der Schleife an alle Aufrufe
				$child_keys = $this->childs[$fk['child_table']][0]->get_fkey_constraints();
				# fragt die attribut typen nur vom ersten child ab und übergibt diese in der Schleife an alle Aufrufe
				#echo '<p>Anzahl child_keys: ' . count($child_keys);
				#echo '<p>keys: ' . print_r($child_keys, true);

				$child_attribute_types = $this->childs[$fk['child_table']][0]->get_attribute_types();
				foreach ($this->childs[$fk['child_table']] AS $child) {
					$sql .= $child->as_inserts_with_childs($child_keys, $child_attribute_types);
				}
			}
		}
		return $sql;
	}

	/*
	* Liefert dieses Objekt als SQL INSERT-Statement zurück
	* @return text - Das INSERT Statement des Objektes
	*/
	function as_insert($attribute_types) {
		#echo '<br>as_insert attribute_types: ' . print_r($attribute_types, true);
		$sql = "INSERT INTO " . $this->schema . "." . $this->tableName . " (" . implode(', ', $this->getKeys()) . ")
VALUES (" . implode(
			", ",
			$this->get_values_for_insert(true, $attribute_types)
		) . ");
";
		return $sql;
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