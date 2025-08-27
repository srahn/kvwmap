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
	* Durch die Übergabe von gui besitzt das Object beide Datenbankverbindungen
	*	$this->database Postgres Datenbank
	* $this->gui->pgdatabase PostgresDatenbank
	* $this->gui->database MySQL Datenbank
	*
	*/

	public $select;
	public $from;
	public $where;
	public $show;
	public $fkeys;
	public $pkey;
	public $data;

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
		$this->from = $schema . '.' . $tableName;
		$this->where = '';
		$this->identifier = $identifier;
		$this->identifier_type = $identifier_type;
		$this->identifiers = array(
			array(
				'column' => $identifier,
				'type' => $identifier_type
			)
		);
		$this->show = false;
		$this->attribute_types = array();
		$this->geom_column = 'geom';
		$this->extent = array();
		$this->extents = array();
		$this->fkeys = array();
		$this->pkey = array();
		$gui->debug->show('Create new Object PgObject with schema ' . $schema . ' table ' . $tableName, $this->show);
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

	function find_by($attribute, $value) {
		$this->debug->show('find by attribute ' . $attribute . ' with value ' . $value, $this->show);
		$sql = "
			SELECT
				{$this->select}
			FROM
				\"{$this->schema}\".\"{$this->tableName}\"
			WHERE
				\"{$attribute}\" = '{$value}'
		";
		$this->debug->show('find_by sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
		$this->data = pg_fetch_assoc($query);
		return $this;
	}

	function get_id_condition($ids = array()) {
	function get_id_condition($ids = array()) {
		$parts = array();
		if (count($ids) == 0) {
			$ids = $this->get_ids();
		if (count($ids) == 0) {
			$ids = $this->get_ids();
		}
		foreach ($this->identifiers AS $identifier) {
			$quote = ($identifier['type'] == 'text' ? "'" : "");
			$parts[] = '"' . $identifier['column'] . '" = ' . $quote . $ids[$identifier['column']] . $quote;
		foreach ($this->identifiers AS $identifier) {
			$quote = ($identifier['type'] == 'text' ? "'" : "");
			$parts[] = '"' . $identifier['column'] . '" = ' . $quote . $ids[$identifier['column']] . $quote;
		}
		return implode(' AND ', $parts);
	}

	function find_by_ids($ids) {
	function find_by_ids($ids) {
		$where_condition = $this->get_id_condition($ids);
		$this->debug->show('find by ids: ' . $where_condition, $this->show);
		$sql = "
			SELECT
				{$this->select}
			FROM
				\"{$this->schema}\".\"{$this->tableName}\"
			WHERE
				" . $where_condition . "
		";
		$this->debug->show('find_by_ids sql: ' . $sql, $this->show);
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

	function exists($where) {
		$sql = "
			SELECT
				count(*) num_rows
			FROM
				\"{$this->schema}\".\"{$this->tableName}\"
			WHERE
				" . $where . "
		";
		$this->debug->show('find_by_id sql: ' . $sql, $this->show);
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

	function getKeys() {
		return array_keys($this->data);
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

	function getKVP($escaped = false, $without_identifier = false) {
		$kvp = array();
		foreach ($this->data AS $key => $value) {
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
				implode(
					", ",
					array_map(
						function($value) {
							return (($value == '' OR $value === null) ? "NULL" : "'" . pg_escape_string($value) . "'");
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

		return array(
			'success' => true,
			'ids' => $this->get_ids(),
			'msg' => 'Datensatz erfolgreich angelegt'
		);
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

	function update() {
		$quote = ($this->identifier_type == 'text') ? "'" : "";
		$sql = "
			UPDATE
				\"" . $this->schema . "\".\"" . $this->tableName . "\"
			SET
				" . implode(', ', $this->getKVP(true, true)) . "
			WHERE
				" . $this->identifier . " = {$quote}" . $this->get($this->identifier) . "{$quote}
		";
		$this->debug->show('update sql: ' . $sql, $this->show);
		$query = pg_query($this->database->dbConn, $sql);
	}

	function update_attr($attributes, $set = false) {
		$quote = ($this->identifier_type == 'text' ? "'" : "");
		$sql = "
			UPDATE
				\"" . $this->schema . "\".\"" . $this->tableName . "\"
			SET
				" . implode(', ', $attributes) . "
			WHERE
				" . $this->identifier . " = {$quote}" . $this->get($this->identifier) . "{$quote}
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

	function delete() {
		$sql = "
			DELETE
			FROM
				" . $this->qualifiedTableName . "
			WHERE
				" . $this->get_id_condition() . "
		";
		$this->debug->show('delete sql: ' . $sql, $this->show);
		$result = pg_query($this->database->dbConn, $sql);
		return $result;
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
	function find_by_sql($params) {
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
		$results = array();
		while ($this->data = pg_fetch_assoc($query)) {
			$results[] = clone $this;
		}
		return $results;
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

	function validate_date($key, $format) {
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
}
?>