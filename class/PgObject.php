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
	function __construct($gui, $schema, $tableName) {
		$gui->debug->show('Create new Object PgObject with schema ' . $schema . ' table ' . $tableName, false);
		$this->debug = $gui->debug;
		$this->gui = $gui;
		$this->database = $gui->pgdatabase;
		$this->schema = $schema;
		$this->tableName = $tableName;
		$this->qualifiedTableName = $schema . '.' . $tableName;
		$this->data = array();
		$this->select = '*';
		$this->identifier = 'id';
		$this->identifier_type = 'integer';
		$this->identifiers = array(
			array(
				'column' => 'id',
				'type' => 'integer'
			)
		);
		$this->show = false;
		$this->attribute_types = array();
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
		$this->debug->show('find by attribute ' . $attribute . ' with value ' . $value, false);
		$sql = "
			SELECT
				{$this->select}
			FROM
				\"{$this->schema}\".\"{$this->tableName}\"
			WHERE
				\"{$attribute}\" = '{$value}'
		";
		$this->debug->show('find_by sql: ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
		$this->data = pg_fetch_assoc($query);
		return $this;
	}

	function get_id_condition($ids) {
		$parts = array();
		foreach ($this->identifiers AS $key => $identifier) {
			$parts[] = "\"{$identifier['column']}\" = '{$ids[$key]}'"; 
		}
		return implode(' AND ', $parts);
	}

	function find_by_ids(...$ids) {
		$where_condition = $this->get_id_condition($ids);
		$this->debug->show('find by ids: ' . $where_condition, false);
		$sql = "
			SELECT
				{$this->select}
			FROM
				\"{$this->schema}\".\"{$this->tableName}\"
			WHERE
				" . $where_condition . "
		";
		$this->debug->show('find_by_id sql: ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
		$this->data = pg_fetch_assoc($query);
		return $this;
	}

	function execSQL($sql) {
		$query = @pg_query($this->database->dbConn, $sql);
		return $query;
	}

	/*
	* Search for an record in the database by the given where clause
	* @ return an array with all found object
	*/
	function find_where($where, $order = NULL, $select = '*') {
		$select = (empty($select) ? $this->select : $select);
		$where = (empty($where) ? "true": $where);
		$order = (empty($order) ? "" : " ORDER BY " . replace_semicolon($order));
		$sql = "
			SELECT
				" . $select . "
			FROM
				" . $this->schema . '.' . $this->tableName . "
			WHERE
				" . $where . "
			" . $order . "
		";
		$this->debug->show('find_where sql: ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
		$result = array();
		while($this->data = pg_fetch_assoc($query)) {
			$result[] = clone $this;
		}
		return $result;
	}

	function delete_by($attribute, $value) {
		#echo '<br>delete by attribute ' . $attribute . ' with value ' . $value;
		$sql = "
			DELETE FROM
				\"{$this->schema}\".\"{$this->tableName}\"
			WHERE
				\"{$attribute}\" = '{$value}'
		";
		$this->debug->show('delete_by sql: ' . $sql, false);
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
		return $this->data[$attribute];
	}

	function get_id() {
		return $this->get($this->identifier);
	}

	function set($attribute, $value) {
		$this->data[$attribute] = $value;
		return $value;
	}

	function set_array($attribute, $value) {
		$this->data[$attribute][] = $value;
		return $this->data[$attribute];
	}

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
			RETURNING
				" . $this->identifier . ";
		";
		$this->debug->show('Create new dataset with sql: ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
		$oid = pg_last_oid($query);
		if (empty($oid)) {
			$ret_id = pg_fetch_assoc($query)[$this->identifier];
			$this->debug->show('Query created identifier ' . $this->identifier . ' with values ' . $ret_id, false);
			$this->lastquery = $query;
			$this->set($this->identifier, $ret_id);
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
			$this->debug->show('Query created oid with sql: ' . $sql, false);
			$query = pg_query($this->database->dbConn, $sql);
			$row = pg_fetch_assoc($query);
			$this->set($this->identifier, $row[$this->identifier]);
		}
		$this->debug->show('Dataset created with ' . $this->identifier . ': '. $this->get($this->identifier), false);
		return $this->get($this->identifier);
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
		$this->debug->show('create sql: ' . $sql, false);
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
		$this->debug->show('update sql: ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
	}

	function update_attr($attributes) {
		$quote = ($this->identifier_type == 'text') ? "'" : "";
		$sql = "
			UPDATE
				\"" . $this->schema . "\".\"" . $this->tableName . "\"
			SET
				" . implode(', ', $attributes) . "
			WHERE
				" . $this->identifier . " = {$quote}" . $this->get($this->identifier) . "{$quote}
		";
		$this->debug->show('update sql: ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
	}

	function delete() {
		$quote = ($this->identifier_type == 'text') ? "'" : "";
		$sql = "
			DELETE
			FROM
				" . $this->qualifiedTableName . "
			WHERE
				" . $this->identifier . " = {$quote}" . $this->get($this->identifier) . "{$quote}
		";
		$this->debug->show('delete sql: ' . $sql, false);
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

	/*
	* Query all child elementes of a table related over given fk_id
	* @params $child_schema string - Name of the schema of child table
	* @params $child_table string - Name of the table of child table
	* @params $fkey_column string - Name of the column where the fkeys resists in child table
	* @params $fk_id string - ID of the parent to filter the childs that belongs to the parent
	* @return array(PgObject) - The childs that belongs to the parent over this fkey constraint
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