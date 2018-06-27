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
	function PgObject($gui, $schema, $tableName) {
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

	/*
	* Search for an record in the database by the given where clause
	* @ return an array with all found object
	*/
	function find_where($where, $order = NULL, $select = '*') {
		$order = (empty($order) ? "" : " ORDER BY " . replace_semicolon($order));
		$sql = "
			SELECT
				{$this->select}
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

	function getKVP($escaped = false) {
		$kvp = array();
		foreach($this->data AS $key => $value) {
			if (is_array($value))
				$value = "{" . implode(", ", $value) . "}";
			if ('' . $value == '')
				$value = 'NULL';

			$kvp[] = "\"" . $key . "\" = " . ($value == 'NULL' ? $value : "'" . ($escaped ? pg_escape_string($value) : $value) . "'");
		}
		return $kvp;
	}

	function get($attribute) {
		return $this->data[$attribute];
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
		";
		#echo $sql.'<br>';
		$this->debug->show('create sql: ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
		$oid = pg_last_oid($query);
		if (empty($oid)) {
			$this->lastquery = $query;
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
			$query = pg_query($this->database->dbConn, $sql);
			$row = pg_fetch_assoc($query);
			$this->set($this->identifier, $row[$this->identifier]);
		}
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
		$sql = "
			UPDATE
				\"" . $this->schema . "\".\"" . $this->tableName . "\"
			SET
				" . implode(', ', $this->getKVP(true)) . "
			WHERE
				id = " . $this->get('id') . "
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

}
?>
