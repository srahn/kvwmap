<?php
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2004  Peter Korduan                               #
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
##################################################
# Klasse Datenbank für ALB-Info Modell und MySQL #
##################################################
class database {
  var $ist_Fortfuehrung;
  var $debug;
  var $loglevel;
  var $logfile;
  var $commentsign;
  var $blocktransaction;
	var $success;
	var $errormessage;

  function __construct($open = false) {
    global $debug;
		global $GUI;
		$this->gui = $GUI;
    $this->debug=$debug;
    $this->loglevel=LOG_LEVEL;
 		$this->defaultloglevel=LOG_LEVEL;
 		global $log_mysql;
    $this->logfile=$log_mysql;
 		$this->defaultlogfile=$log_mysql;
    $this->ist_Fortfuehrung=1;
    $this->type="MySQL";
    $this->commentsign='#';
    # Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
    # BEGIN TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
    # Anweisungen nicht in Transactionsblöcken ablaufen.
    # Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
    # Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
    # und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
    # Dazu Fehlerausschriften bearchten.
    $this->blocktransaction=0;
		if ($open) {
			$this->host = MYSQL_HOST;
			$this->user = MYSQL_USER;
			$this->passwd = MYSQL_PASSWORD;
			$this->dbName = MYSQL_DBNAME;
			$this->open();
		}
  }

	function login_user($username, $passwort, $agreement = ''){
		$sql = "
			SELECT
				* 
			FROM
				information_schema.COLUMNS 
			WHERE
				TABLE_SCHEMA = '" . $this->dbName . "' AND
				TABLE_NAME = 'user' AND
				COLUMN_NAME = 'agreement_accepted'
		";
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__, 4); return 0; }

		$colunn_aggreement_accepted = (mysqli_num_rows($this->result) == 1 ? ', agreement_accepted' : ', 1');

		$sql = "
			SELECT
				ID,
				login_name" .
				$colunn_aggreement_accepted . "
			FROM
				user
			WHERE
				login_name = '" . $this->mysqli->real_escape_string($username) . "' AND
				passwort = md5('" . $this->mysqli->real_escape_string($passwort) . "') AND
				(
					('" . date('Y-m-d h:i:s') . "' >= start AND '" . date('Y-m-d h:i:s') . "' <= stop) OR
					(start='0000-00-00 00:00:00' AND stop='0000-00-00 00:00:00')
				)
		"; # Zeiteinschränkung wird nicht berücksichtigt.
		#echo $sql;
		$this->execSQL("SET NAMES '" . MYSQL_CHARSET . "'", 0, 0);
		$this->execSQL($sql, 4, 0);
		if (!$this->success) { $this->debug->write("<br>Abbruch Zeile: " . __LINE__, 4); return 0; }
		$rs = $this->result->fetch_array();
		if (mysqli_num_rows($this->result) != '') {
			# wenn Nutzer bisher noch nicht akzeptiert hatte
			if (defined('AGREEMENT_MESSAGE') AND AGREEMENT_MESSAGE != '' AND $rs['agreement_accepted'] == 0) {
				if ($agreement != '') { # es wurde jetzt akzeptiert
					$sql = "
						UPDATE
							user
						SET
							agreement_accepted = TRUE
						WHERE
							ID = " . $rs['ID'] . "
					";
					$this->execSQL($sql, 4, 0);
					return true;
				}
				else { # jetzt wurde auch nicht akzeptiert
					$this->agreement_not_accepted = true;
					return false;
				}
			}
			else {
				return true;
			}
		}
		else{
			return false;
		}
	}

  function read_colors(){	
  	$sql = "SELECT * FROM colors";
  	#echo $sql;
  	$this->execSQL($sql, 4, 0);
    if (!$this->success) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs = $this->result->fetch_assoc()) {
      $colors[] = $rs;
    }
    return $colors;
  }

  function read_color($id){
  	$sql = "SELECT * FROM colors WHERE id = ".$id;
  	#echo $sql;
  	$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    if($ret[0]==0){
      $color = $this->result->fetch_assoc();
    }
    return $color;
  }

	function create_new_gast($gast_stelle) {
		$loginname = "";
		$laenge = 10;
		$string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		mt_srand((double)microtime() * 1000000);
		for ($i=1; $i <= $laenge; $i++) {
			$loginname .= substr($string, mt_rand(0, strlen($string) - 1), 1);
		}
		# Gastnutzer anlegen
		$sql = "
			INSERT INTO user (
				`login_name`,
				`Name`,
				`Vorname`,
				`Namenszusatz`,
				`passwort`,
				`ips`,
				`Funktion`,
				`stelle_id`
			)
			VALUES (
				'" . $loginname . "',
				'gast',
				'gast',
				'',
				'd4061b1486fe2da19dd578e8d970f7eb',
				'',
				'gast',
				'" . $this->mysqli->real_escape_string($gast_stelle) . "'
			);
		";
		#echo '<br>sql: ' . $sql;
		$this->execSQL($sql, 4, 0);

		# ID des Gastnutzers abfragen
		$sql = "
			SELECT LAST_INSERT_ID();
		";
		$this->execSQL($sql, 4, 0);
		$row = $this->result->fetch_row();
		$new_user_id = $row[0];

		include_once(CLASSPATH . 'stelle.php');
		include_once(CLASSPATH . 'rolle.php');
		$stelle = new stelle($gast_stelle, $this);
		$rolle = new rolle(NULL, $gast_stelle, $this);
		$layers = $stelle->getLayers(NULL);
		$rolle->setRolle($new_user_id, $gast_stelle, $stelle->default_user_id);
		$rolle->setMenue($new_user_id, $gast_stelle, $stelle->default_user_id);
		$rolle->setLayer($new_user_id, $gast_stelle, $stelle->default_user_id);
		$rolle->setGroups($new_user_id, $gast_stelle, $stelle->default_user_id, $layers['ID']);
		$rolle->setSavedLayersFromDefaultUser($new_user_id, $gast_stelle, $stelle->default_user_id);

		$gast['username'] = $loginname;
		$gast['passwort'] = 'gast';
		return $gast;
	}

  function getRow($select,$from,$where) {
		$sql = "SELECT ".$select;
    $sql.= " FROM ".$from;
    $sql.= " WHERE ".$where;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $ret[1] = $this->result->fetch_assoc();
    return $ret;
  }

	/**
	* Erzeugt SQL zum anlegen eines Layer in mysql
	*
	* @params $geometrie_column, Name des Attributes der Tabelle, die abgefragt wird, die Geometrie beinhaltet.
	* @params $geometrietyp String, Name des Attributes des Datentyps der Geometriespalte, welches die Geometrie beinhalten soll.
	* @params $layertyp Integer, 0 point, 1 line, 2 polygon, 5 query
	*
	*/
	function generate_layer($schema, $table, $group_id = 0, $connection_id, $epsg = 25832, $geometrie_column = 'the_geom', $geometrietyp = '', $layertyp = '2') {
		#echo '<br>Create Layer: ' . $table['name'];
		if ($geometrietyp != '') $geometrie_column = "({$geometrie_column}).{$geometrietyp}";
		if ($group_id == 0) $group_id = '@group_id';
		if ($connection_id == '') $connection_id = '@connection_id';
		$sql = "
-- Create layer {$table['name']}
INSERT INTO layer (
	`Name`,
	`Datentyp`,
	`Gruppe`,
	`pfad`,
	`maintable`,
	`Data`,
	`schema`,
	`connection_id`,
	`connectiontype`,
	`tolerance`,
	`toleranceunits`,
	`epsg_code`,
	`queryable`,
	`transparency`,
	`ows_srs`,
	`wms_name`,
	`wms_server_version`,
	`wms_format`,
	`wms_connectiontimeout`,
	`querymap`,
	`kurzbeschreibung`,
	`privileg`
)
VALUES (
	'{$table['name']}',
	'{$layertyp}',
	{$group_id},
	'SELECT * FROM {$table['name']} WHERE 1=1',
	'{$table['name']}',
	'geom from (select oid, {$geometrie_column} AS geom FROM {$schema}.{$table['name']}) as foo using unique oid using srid={$epsg}',
	'{$schema}',
	'{$connection_id}'
	'6',
	'3',
	'pixels',
	'{$epsg}',
	'1',
	'60',
	'EPSG:{$epsg}',
	'{$table['name']}',
	'1.1.0',
	'image/png',
	'60',
	'1',
	'Diese Tabelle enthält alle Objekte aus der Tabelle {$table['name']}.',
	'2'
);
SET @last_layer_id_{$table['oid']} = LAST_INSERT_ID();
";
		return $sql;
	}

	function generate_layer_attribute($attribute, $table, $options) {
		#echo '<br>Create Layerattribute: ' . $attribute['name'];
		if($attribute['nullable'] == '')$attribute['nullable'] = 'NULL';
		if($attribute['length'] == '')$attribute['length'] = 'NULL';
		if($attribute['decimal_length'] == '')$attribute['decimal_length'] = 'NULL';
		$sql = "
-- Create layer_attribute {$attribute['name']} for layer {$attribute['table_name']}
INSERT INTO layer_attributes (
	`layer_id`,
	`name`,
	`real_name`,
	`tablename`,
	`table_alias_name`,
	`type`,
	`geometrytype`,
	`constraints`,
	`nullable`,
	`length`,
	`decimal_length`,
	`default`,
	`form_element_type`,
	`options`,
	`group`,
	`raster_visibility`,
	`mandatory`,
	`order`,
	`privileg`,
	`query_tooltip`
)
VALUES (
	@last_layer_id_{$table['oid']},
	'{$attribute['name']}',
	'{$attribute['name']}', -- real_name
	'{$attribute['table_name']}',
	'{$attribute['table_name']}', -- table_alias_name
	'{$attribute['type_name']}', -- type
	'', -- geometrytype
	'{$options['constraint']}', -- constraints
	'{$attribute['nullable']}',
	'{$attribute['length']}', -- length
	'{$attribute['decimal_length']}', -- decimal_length
	'{$attribute['default']}', -- default
	'text', -- form_element_type
	'{$options['option']}', -- options
	'', -- group
	NULL, -- raster_visibility
	NULL, -- mandatory
	'{$attributes['ordinal_position']}', -- order
	'1',
	'0'
);
";
		return $sql;
	}

	function generate_datatype($schema, $datatype, $epsg = 25832) {
		#echo '<br>Create Datatype: ' . $datatype['type'] . ' for attribute ' . $datatype['name'];
		$sql = "
-- Create datatype {$datatype['type_name']}
INSERT INTO datatypes (
	`name`,
	`schema`,
	`dbname`,
	`host`,
	`port`,
)
VALUES (
	'{$datatype['type']}',
	'{$schema}', -- schema
	'xplan_gml'
	'localhost',
	'5432'
);
SET @last_datatype_id_{$datatype['attribute_type_oid']} = LAST_INSERT_ID();
";
		return $sql;
	}

	function generate_datatype_attribute($attribute, $table, $options) {
		#echo '<br>Create Datatypeattribute: ' . $attribute['name'] . ' für Datentyp: ' . $attribute['table_name'];
		$sql = "
--Create datatype_attribute {$attribute['name']} for datatype {$attribute['table_name']}
INSERT INTO datatype_attributes (
	`layer_id`,
	`name`,
	`real_name`,
	`tablename`,
	`table_alias_name`,
	`type`,
	`geometrytype`,
	`constraints`,
	`nullable`,
	`length`,
	`decimal_length`,
	`default`,
	`form_element_type`,
	`options`,
	`group`,
	`raster_visibility`,
	`mandatory`,
	`order`,
	`privileg`,
	`query_tooltip`
)
VALUES (
	@last_datatype_id_{$table['attribute_type_oid']},
	'{$attribute['name']}',
	'{$attribute['name']}', -- real_name
	'{$attribute['table_name']}',
	'{$attribute['table_name']}', -- table_alias_name
	'{$attribute['type_name']}', -- type
	'', -- geometrytype
	'{$options['constraint']}', -- constraints
	" . (($attribute['is_nullable'] == 't') ? 'TRUE' : 'FALSE') . ",
	'{$attribute['character_maximum_length']}', -- length
	'{$attribute['numeric_precision']}', -- decimal_length
	'{$attribute['attribute_default']}', -- default
	'text', -- form_element_type
	'{$options['option']}', -- options
	'', -- group
	NULL, -- raster_visibility
	" . (($attribute['is_nullable'] == 'NO') ? 'TRUE' : 'NULL') . ", -- mandatory
	'{$attributes['ordinal_position']}', -- order
	'1',
	'0'
);
";
		return $sql;
	}

	function generate_classes($table) {
		$sql = "
-- Create class for layer {$table['name']}
INSERT INTO classes (
	`Name`,
	`Layer_ID`,
	`Expression`,
	`drawingorder`,
	`text`
)
VALUES(
	'alle',
	@last_layer_id_{$table['oid']},
	'(1 = 1)',
	'1',
	''
);
SET @last_class_id = LAST_INSERT_ID();
";
		return $sql;
	}

	function generate_styles() {
		$sql = "
INSERT INTO styles (
	`symbol`,
	`symbolname`,
	`size`,
	`color`,
	`backgroundcolor`,
	`outlinecolor`,
	`minsize`,
	`maxsize`,
	`angle`,
	`angleitem`,
	`antialias`,
	`width`,
	`minwidth`,
	`maxwidth`,
	`sizeitem`
) VALUES (
	NULL,
	'',
	'1',
	'0 189 231',
	'',
	'22 97 113',
	NULL,
	'1',
	'360',
	'',
	NULL,
	NULL,
	NULL,
	NULL,
	''
);
SET @last_style_id = LAST_INSERT_ID();
";
		return $sql;
	}

	function generate_style2classes() {
		$sql = "
INSERT INTO u_styles2classes (
	style_id,
	class_id,
	drawingorder
) VALUES (
	@last_style_id,
	@last_class_id,
	0
);
";
		return $sql;
	}
	
	/*
	* Funktion liefert das Ergebnis einer SQL-Abfrage als INSERT-Dump für die Tabelle "$table" 
	* über $extra kann ein Feld angegeben werden, welches nicht mit in das INSERT aufgenommen wird
	* dieses Feld wird jedoch auch mit abgefragt und separat zurückgeliefert
	*/
	function create_insert_dump($table, $extra, $sql){
		#echo '<br>Create_insert_dump for table: ' . $table;
		#echo '<br>sql: ' . $sql;
		#echo '<br>extra: ' . $extra;
		$this->debug->write("<p>file:kvwmap class:database->create_insert_dump :<br>".$sql,4);
		$this->execSQL($sql, 4, 0);
		$dump = array(
			'insert' => array(),
			'extra'  => array()
		);
		$feld_anzahl = $this->result->field_count;
		#echo '<br>Anzahl Felder: ' . $feld_anzahl;
		for ($i = 0; $i < $feld_anzahl; $i++) {
			$meta = $this->result->fetch_field_direct($i);
			#echo '<br>Meta name: ' . $meta->name;
			# array mit feldnamen
			$felder[$i] = $meta->name;
			if ($meta->name == 'connectiontype'){
				$connectiontype = $i;
			}
			if($meta->name == 'connection'){
				$connection_field_index = $i;
			}
			if($meta->name == 'connection_id'){
				$connection_id_field_index = $i;
			}
		}

		while ($rs = $this->result->fetch_array()) {
			$insert = '';
			if ($rs[$connectiontype] == 6) {
				$rs[$connection_field_index] = '@connection';
				$rs[$connection_id_field_index] = '@connection_id';
			}
			$insert .= 'INSERT INTO '.$table.' (';
			for ($i = 0; $i < $feld_anzahl; $i++) {
				if($felder[$i] != $extra) {
					$insert .= "`".$felder[$i]."`";
					if ($feld_anzahl-1 > $i){$insert .= ', ';}
				}
			}
			$insert .= ') VALUES (';
			for ($i = 0; $i < $feld_anzahl; $i++) {
				if ($felder[$i] != $extra) {
					if (strpos($rs[$i], '@') === 0) {
						$insert .= $this->mysqli->real_escape_string($rs[$i]);
					}
					else {
						$field = $this->result->fetch_field_direct($i);
						if (!in_array($field->type, [252, 253, 254]) AND $rs[$i] == '') {
							$insert .= "NULL";
						} else{
							$insert .= "'".$this->mysqli->real_escape_string($rs[$i])."'";
						}
					}
					if ($feld_anzahl - 1 > $i) { $insert .= ', '; }
				}
				else {
					$dump['extra'][] = $rs[$i];
				}
			}
			$insert .= ');';
			$dump['insert'][] = $insert;
		}
		#echo '<br>insert: ' . $insert;
		return $dump;
	}

	function create_update_dump($table){
		# Funktion erstellt zu einer Tabelle einen Update-Dump und liefert ihn als String zurück
		$sql = 'SELECT * FROM '.$table;
		$this->debug->write("<p>file:kvwmap class:database->create_update_dump :<br>".$sql,4);
		$ret = $this->execSQL($sql, 4, 0);

    $feld_anzahl = $this->result->field_count;
    for($i = 0; $i < $feld_anzahl; $i++){
    	$meta = $this->result->fetch_field_direct($i);
    	# array mit feldnamen
    	$felder[$i] = $meta->name;
    	# array mit indizes der primary-keys
    	if($meta->primary_key == 1){
    		$keys[] = $i;
    	}
    }
    while ($rs = $this->result->fetch_array()) {
    	$update = 'UPDATE '.$table.' SET ';
    	$update .= $felder[0].' = '.$rs[0];
    	for($i = 1; $i < $feld_anzahl; $i++){
    		$update .= ", ".$felder[$i]." = '".$rs[$i]."'";
    	}
    	$update .= ' WHERE ';
    	for($i = 0; $i < count($keys); $i++){
    		$update .= $felder[$keys[$i]].' = '.$rs[$keys[$i]].' AND ';
    	}
    	$update .= ' (1=1);';
    	$dump .= "\n".$update;
    }
   return $dump;
	}


####################################################
# database Funktionen
###########################################################
	function open() {
		$this->debug->write("<br>MySQL Verbindung öffnen mit Host: " . $this->host . " User: " . $this->user . " Datenbbank: " . $this->dbName, 4);
		$this->mysqli = mysqli_init();
		$ret = $this->mysqli->real_connect($this->host, $this->user, $this->passwd, $this->dbName, 3306, null, MYSQLI_CLIENT_FOUND_ROWS);
	  $this->debug->write("<br>MySQL VerbindungsID: " . $this->mysqli->thread_id, 4);
		$this->debug->write("<br>MySQL Fehlernummer: " . mysqli_connect_errno(), 4);
		$this->debug->write("<br>MySQL Fehler: " . mysqli_connect_error(), 4);
		return $ret;
	}

	function close() {
		$this->debug->write("<br>MySQL Verbindung ID: " . $this->mysqli->thread_id . " schließen.", 4);
		if (LOG_LEVEL > 0) {
			$this->logfile->close();
		}
		return $this->mysqli->close();
	}

	function exec_commands($commands_string, $replace_connection, $replace_connection_id, $replace_constants = false, $suppress_err_msg = false) {
		if ($commands_string != '') {
			foreach (explode(';' . chr(10), $commands_string) as $query2) { // verschiedene Varianten des Zeilenumbruchs berücksichtigen
				foreach (explode(';' . chr(13), $query2) as $query) {
					$query_to_execute = '';
					$query = trim($query);
					if ($replace_connection != NULL) {
						$query = str_replace('user=xxxx password=xxxx dbname=kvwmapsp', $replace_connection, $query);
					}
					if ($replace_connection_id != NULL) {
						$query = str_replace('xxxx_connection_id_xxxx', $replace_connection_id, $query);
					}
					// foreach (explode(chr(10), $query) as $line) {
						// if ($line != '' AND strpos($line, "--") !== 0 && strpos($line, "#") !== 0) { // Zeilen mit Kommentarzeichen ignorieren
							// $query_to_execute .= ' '.$line;
						// }
					// }
					$query_to_execute = $query;
					if (!empty($query_to_execute)) {
						$query_to_execute = str_replace('$EPSGCODE_ALKIS', EPSGCODE_ALKIS, $query_to_execute);
						$query_to_execute = str_replace(':alkis_epsg', EPSGCODE_ALKIS, $query_to_execute);
						if ($replace_constants) {
							foreach (get_defined_constants(true)['user'] AS $key => $value) {
								$query_to_execute = str_replace('$' . $key, $value, $query_to_execute);
							}
						}
						#echo '<br>exec sql: ' . $query_to_execute;
						$ret = $this->execSQL($query_to_execute, 0, 0, $suppress_err_msg);
						if ($ret[0] == 1) {
							return $ret;
						}
					}
				}
			}
		}
		return array(0);
	}

  function begintransaction() {
    # Starten einer Transaktion
    # initiates a transaction block, that is, all statements
    # after BEGIN command will be executed in a single transaction
    # until an explicit COMMIT or ROLLBACK is given
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('START TRANSACTION',4, 1);
    }
    return $ret;
  }

  function rollbacktransaction() {
    # Rückgängigmachung aller bisherigen Änderungen in der Transaktion
    # und Abbrechen der Transaktion
    # rolls back the current transaction and causes all the updates
    # made by the transaction to be discarded
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('ROLLBACK',4, 1);
    }
    return $ret;
  }

  function committransaction() {
    # Gültigmachen und Beenden der Transaktion
    # commits the current transaction. All changes made by the transaction
    # become visible to others and are guaranteed to be durable if a crash occurs
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('COMMIT',4, 1);
    }
    return $ret;
  }

  function vacuum() {
    # Hier sollten alle Tabellen optimiert werden können
    # in MySQL müsste man den Befehl OPTIMIZE für alle Tabellen einzeln aufrufen
    # eine Idee wie man das umgehen kann?
    # in postgres gibt es dafür den vacuum-Befehl
    if (!$this->vacuumOff) {
    	# OPTIMIZE ALL
    }
    return $ret;
  }

  function setLogLevel($loglevel,$logfile) {
  	if ($loglevel==-1) {
  		# setzen der Defaulteinstellungen
  		$this->loglevel=$this->defaultloglevel;
  		$this->logfile=$this->defaultlogfile;
  	}
  	else {
  		$this->loglevel=$loglevel;
  		$this->logfile=$logfile;
  	}
  }

	/**
	* Führt die in $sql übergebene SQL-Anweisung aus
	* @param varchar $sql Das SQL-Statement
	* @return array(
	*		0 => integer 0 Erfolgreiche Abfrage, 1 Fehler bei der Abfrage)
	*   1 => varchar query mysql_result object wenn 0 = 0 und die Fehlermeldung als varchar wenn 0 = 1
	*   'query' => mysql_result object
	*   'success' => boolean true bei Erfolg, false bei Fehler 
	*/
	function execSQL($sql, $debuglevel = 4, $loglevel = 0, $suppress_error_msg = false) {
		switch ($this->loglevel) {
			case 0 : {
				$logsql=0;
			} break;
			case 1 : {
				$logsql=1;
			} break;
			case 2 : {
				$logsql=$loglevel;
			} break;
		}
		# SQL-Statement wird nur ausgeführt, wenn DBWRITE gesetzt oder
		# wenn keine INSERT, UPDATE und DELETE Anweisungen in $sql stehen.
		# (lesend immer, aber schreibend nur mit DBWRITE=1)
		if (DBWRITE OR (!stristr($sql,'INSERT') AND !stristr($sql,'UPDATE') AND !stristr($sql,'DELETE'))) {
			#echo '<br>sql in execSQL: ' . $sql;
			if ($result = $this->mysqli->query($sql)) {
				$ret[0] = 0;
				$ret['success'] = $this->success = true;
				$ret[1] = $ret['query'] = $ret['result'] = $this->result = $result;
				$this->errormessage = '';
				if ($logsql) {
					$this->logfile->write($sql . ';');
				}
				$this->debug->write(date('H:i:s')."<br>" . $sql, $debuglevel);
			}
			else {
				$ret[0] = 1;
				$ret['success'] = $this->success = false;
				$div_id = rand(1, 99999);
				$errormessage = $this->mysqli->error;
				$ret[1] = $this->errormessage = sql_err_msg('MySQL', $sql, $errormessage, $div_id);
				if ($logsql) {
					$this->logfile->write("#" . $errormessage);
				}
				if (!$suppress_error_msg) {
					if (gettype($this->gui) == 'object') {
						$this->gui->add_message('error', $this->errormessage);
					}
					else {
						echo '<br>error: ' . $this->errormessage;
					}
				}
			}
			$ret[2] = $sql;
		}
		else {
			if ($logsql) {
				$this->logfile->write($sql . ';');
			}
			$this->debug->write("<br>" . $sql, $debuglevel);
		}
		return $ret;
	}
}
