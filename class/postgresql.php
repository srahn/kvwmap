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
# Klasse Datenbank für ALB Modell und PostgreSQL #
##################################################
class pgdatabase {
	var $ist_Fortfuehrung;
	var $debug;
	var $loglevel;
	var $defaultloglevel;
	var $logfile;
	var $defaultlogfile;
	var $commentsign;
	var $blocktransaction;
	var $host;
	var $port;
	var $dbname;
	var $user;
	var $passwd;
	var $schema;
	var $pg_text_attribute_types = array('character', 'character varying', 'text', 'timestamp without time zone', 'timestamp with time zone', 'date', 'USER-DEFINED');
	var $version = POSTGRESVERSION;
	var $connection_id;
	var $error;
	var $dbName;

	function __construct() {
		global $debug;
		global $GUI;
		$this->gui = $GUI;
		$this->debug=$debug;
		$this->loglevel=LOG_LEVEL;
		$this->defaultloglevel=LOG_LEVEL;
		global $log_postgres;
		$this->logfile=$log_postgres;
		$this->defaultlogfile=$log_postgres;
		$this->ist_Fortfuehrung=1;
		$this->type='postgresql';
		$this->commentsign='--';
		$this->err_msg = '';
		$this->error = false;
		# Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
		# START TRANSACTION, ROLLBACK und COMMIT unterdrückt, so daß alle anderen SQL
		# Anweisungen nicht in Transactionsblöcken ablaufen.
		# Kann zur Steigerung der Geschwindigkeit von großen Datenbeständen verwendet werden
		# Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
		# und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
		# Dazu Fehlerausschriften bearchten.
		$this->blocktransaction=0;
		$this->debug_level=4;
	}

	/**
	* Open the database connection based on the given connection_id
	* @param integer, $connection_id The id of the connection defined in connections table, if 0 default connection will be used
	* @return boolean, True if success or set an error message in $this->err_msg and return false when fail to find the credentials or open the connection
	*/
  function open($connection_id = 0, $flag = NULL) {
		$this->debug->write("Open Database connection with connection_id: " . $connection_id, 4);
		$this->connection_id = $connection_id;
		$connection_string = $this->get_connection_string();
		try {
			$this->dbConn = pg_connect($connection_string . ' connect_timeout=5', $flag);
		}
		catch (Exception $e) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden connection_id: ' . $connection_id . ' '
				. implode(' ' , array_filter(explode(' ', $connection_string), function($part) { return strpos($part, 'password') === false; })) . '<br>Exception: ' . $e;
			return false;
		}

		if (!$this->dbConn) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden connection_id: ' . $connection_id . ' '
				. implode(' ' , array_filter(explode(' ', $connection_string), function($part) { return strpos($part, 'password') === false; }));
			return false;
		}
		else {
			$this->debug->write("Database connection successfully opend.", $this->debug_level);
			$this->setClientEncodingAndDateStyle();
			$this->connection_id = $connection_id ?: POSTGRES_CONNECTION_ID;
			return true;
		}
	}

	/**
	* return the credential details as array from connections_table
	* or default values if no exists for connection_id
	* @param integer $connection_id The id of connection information in connection table
	* @return array $credentials array with connection details
	*/
	function get_credentials($connection_id) {
		#echo '<p>get_credentials with connection_id: ' . $connection_id;
		if ($connection_id == 0) {
			return $this->get_object_credentials();
		}
		else {
			include_once(CLASSPATH . 'Connection.php');
			$conn = Connection::find_by_id($this->gui, $connection_id);
			$this->host = $conn->get('host');
			return array(
				'host' => 		($conn->get('host')     != '' ? $conn->get('host')     : 'pgsql'),
				'port' => 		($conn->get('port')     != '' ? $conn->get('port')     : '5432'),
				'dbname' => 	($conn->get('dbname')   != '' ? $conn->get('dbname')   : 'kvwmapsp'),
				'user' => 		($conn->get('user')     != '' ? $conn->get('user')     : 'kvwmap'),
				'password' => ($conn->get('password') != '' ? $conn->get('password') : KVWMAP_INIT_PASSWORD)
			);
		}
	}

	/**
	* returns a postgres connection string used to connect to postgres with pg_connect
	* @param array $credentials array with connection details
	* @return string the postgres connection string
	*/
	function format_pg_connection_string($credentials) {
		$connection_string = "" .
			"host='" .		 $credentials['host'] 		. "' " .
			"port='" .		 $credentials['port'] 		. "' " .
			"dbname='" .	 $credentials['dbname'] 	. "' " .
			"user='" .		 $credentials['user'] 		. "' " .
			"password='" . addslashes($credentials['password']) . "' " .
			"application_name=kvwmap_user_" . ($this->gui->user ? $this->gui->user->id : '');
		return $connection_string;
	}

	function get_connection_string($bash_escaping = false) {
		$connection_string = $this->format_pg_connection_string($this->get_credentials($this->connection_id));
		if ($bash_escaping) {
			$connection_string = str_replace('$', '\$', $connection_string);
		}
		return $connection_string;
	}
	
	function format_pg_connection_string_p($credentials) {
		$connection_string = "" .
			"host='" .		 $credentials['host'] 		. "' " .
			"port='" .		 $credentials['port'] 		. "' " .
			"dbname='" .	 $credentials['dbname'] 	. "' " .
			"user='" .		 $credentials['user'] 		. "'";
		return $connection_string;
	}

	function get_connection_string_p() {
		return $this->format_pg_connection_string_p($this->get_credentials($this->connection_id));
	}

	/**
	* Set credentials to postgres object variables
	*/
	function set_object_credentials($credentials) {
		$this->host = 	$credentials['host'];
		$this->port = 	$credentials['port'];
		$this->dbName = $credentials['dbname'];
		$this->user = 	$credentials['user'];
		$this->passwd = $credentials['password'];
	}

	/**
	* Get credentials from postgres object variables, with Fallback to old constants
	*/
	function get_object_credentials() {
		return array(
			'host'     => $this->host ?: POSTGRES_HOST,
			'port'     => $this->port ?: 5432,
			'dbname'   => $this->dbName ?: POSTGRES_DBNAME,
			'user'     => $this->user ?: POSTGRES_USER,
			'password' => $this->passwd ?: POSTGRES_PASSWORD
		);
	}

  function setClientEncodingAndDateStyle() {
    $sql = "
			SET CLIENT_ENCODING TO '".POSTGRES_CHARSET."';
			SET datestyle TO 'German';
			";
		$ret = $this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    return $ret[1];
  }

  function close() {
    $this->debug->write("<br>PostgreSQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    return pg_close($this->dbConn);
  }

	function create_new_gast($gast_stelle) {
		$login_name = "";
		$laenge = 10;
		$string = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		mt_srand((double)microtime() * 1000000);
		for ($i = 1; $i <= $laenge; $i++) {
			$login_name .= substr($string, mt_rand(0, strlen($string) - 1), 1);
		}
		# Gastnutzer anlegen
		$sql = "
			INSERT INTO kvwmap.user (
				login_name,
				name,
				vorname,
				namenszusatz,
				password,
				ips,
				Funktion,
				stelle_id
			)
			VALUES (
				'" . $login_name . "',
				'gast',
				'gast',
				'',
				kvwmap.sha1('gast'),
				'',
				'gast',
				" . $gast_stelle . "
			) RETURNING id;
		";
		#echo '<br>sql: ' . $sql;
		$ret = $this->execSQL($sql, 4, 0);
		
		$row = pg_fetch_row($ret[1]);
		$new_user_id = $row[0];

		include_once(CLASSPATH . 'stelle.php');
		include_once(CLASSPATH . 'rolle.php');
		$stelle = new stelle($gast_stelle, $this);
		$rolle = new rolle($new_user_id, $gast_stelle, $this);
		$layers = $stelle->getLayers(NULL);
		$rolle->setRolle($new_user_id, $gast_stelle, $stelle->default_user_id);
		$rolle->setMenue($new_user_id, $gast_stelle, $stelle->default_user_id);
		$rolle->setLayer($new_user_id, $gast_stelle, $stelle->default_user_id);
		$rolle->setGroups($new_user_id, $gast_stelle, $stelle->default_user_id, $layers['ID']);
		$rolle->setSavedLayersFromDefaultUser($new_user_id, $gast_stelle, $stelle->default_user_id);

		$gast['username'] = $login_name;
		$gast['passwort'] = 'gast';
		return $gast;
	}

	function getRow($select,$from,$where) {
		$sql = "SELECT ".$select;
    $sql.= " FROM ".$from;
    $sql.= " WHERE ".$where;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $ret[1] = pg_fetch_assoc($ret[1]);
    return $ret;
  }

		/*
	* Funktion liefert das Ergebnis einer SQL-Abfrage als INSERT-Dump für die Tabelle "$table" 
	* über $extra kann ein Feld angegeben werden, welches nicht mit in das INSERT aufgenommen wird
	* dieses Feld wird jedoch auch mit abgefragt und separat zurückgeliefert
	*/
	function create_insert_dump($table, $extra, $sql, $returning = ''){
		#echo '<br>Create_insert_dump for table: ' . $table;
		#echo '<br>sql: ' . $sql;
		#echo '<br>extra: ' . $extra;
		$this->debug->write("<p>file:kvwmap class:database->create_insert_dump :<br>" . $sql, 4);
		$ret = $this->execSQL($sql, 4, 0);
		$dump = array(
			'insert' => array(),
			'extra'  => array()
		);		
		while ($rs = pg_fetch_assoc($ret[1])) {
			if ($rs['connectiontype'] == 6) {
				$rs['connection_id'] = 'vars_connection_id';
			}
			$dump['extra'][] = $rs[$extra];
			unset($rs[$extra]);
			$insert = "
				INSERT INTO " . $table . " 
					(\"" . implode('", "', array_keys($rs)) . "\") 
				VALUES 
					(" . 
					implode(
						', ', 
						array_map(
							function ($value){
								if (strpos($value, 'vars') === 0) {
									return pg_escape_string($value);
								}
								else {
									if ($value === null) {
										return "NULL";
									} else {
										return "'" . pg_escape_string($value) . "'";
									}
								}
							}, 
							$rs
						)
					) . ")" . $returning . ';';
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

	/**
	* Erzeugt SQL zum anlegen eines Layers
	*
	* @params $geometrie_column, Name des Attributes der Tabelle, die abgefragt wird, die Geometrie beinhaltet.
	* @params $geometrietyp String, Name des Attributes des Datentyps der Geometriespalte, welches die Geometrie beinhalten soll.
	* @params $layertyp Integer, 0 point, 1 line, 2 polygon, 5 query
	*
	*/
	function generate_layer($schema, $table, $group_id = 0, $connection_id, $epsg = 25832, $geometrie_column = 'the_geom', $geometrietyp = '', $layertyp = '2') {
		#echo '<br>Create Layer: ' . $table['name'];
		if ($geometrietyp != '') $geometrie_column = "({$geometrie_column}).{$geometrietyp}";
		if ($group_id == 0) $group_id = "vars_group_id";
		if ($connection_id == '') $connection_id = "vars_connection_id";
		$sql = "
-- Create layer {$table['name']}
INSERT INTO kvwmap.layer (
	name,
	datentyp,
	gruppe,
	pfad,
	maintable,
	data,
	schema,
	connection_id,
	connection,
	connectiontype,
	tolerance,
	toleranceunits,
	epsg_code,
	queryable,
	transparency,
	ows_srs,
	wms_name,
	wms_server_version,
	wms_format,
	wms_connectiontimeout,
	querymap,
	kurzbeschreibung,
	privileg,
	geom_column
)
VALUES (
	'{$table['name']}',
	'{$layertyp}',
	{$group_id},
	'SELECT * FROM {$table['name']} WHERE 1=1',
	'{$table['name']}',
	'geom from (select oid, {$geometrie_column} AS geom FROM {$schema}.{$table['name']}) as foo using unique oid using srid={$epsg}',
	'{$schema}',
	{$connection_id},
	'',
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
	'2',
	'{$geometrie_column}'
)
RETURNING layer_id INTO last_layer_id;
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
INSERT INTO kvwmap.layer_attributes (
	layer_id,
	name,
	real_name,
	tablename,
	table_alias_name,
	type,
	geometrytype,
	constraints,
	nullable,
	length,
	decimal_length,
	\"default\",
	form_element_type,
	options,
	\"group\",
	raster_visibility,
	mandatory,
	\"order\",
	privileg,
	query_tooltip
)
VALUES (
	last_layer_id,
	'{$attribute['name']}',
	'{$attribute['name']}', -- real_name
	'{$attribute['table_name']}',
	'{$attribute['table_name']}', -- table_alias_name
	'{$attribute['type_name']}', -- type
	'', -- geometrytype
	'{$options['constraint']}', -- constraints
	'{$attribute['nullable']}',
	{$attribute['length']}, -- length
	{$attribute['decimal_length']}, -- decimal_length
	'" . pg_escape_string($attribute['default']) . "', -- default
	'text', -- form_element_type
	'{$options['option']}', -- options
	'', -- group
	NULL, -- raster_visibility
	NULL, -- mandatory
	'{$attribute['ordinal_position']}', -- order
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
INSERT INTO kvwmap.datatypes (
	name,
	schema,
	dbname,
	host,
	port,
)
VALUES (
	'{$datatype['type']}',
	'{$schema}', -- schema
	'xplan_gml'
	'localhost',
	'5432'
)
RETURNING id INTO last_datatype_id;
";
		return $sql;
	}

	function generate_datatype_attribute($attribute, $table, $options) {
		#echo '<br>Create Datatypeattribute: ' . $attribute['name'] . ' für Datentyp: ' . $attribute['table_name'];
		$sql = "
--Create datatype_attribute {$attribute['name']} for datatype {$attribute['table_name']}
INSERT INTO kvwmap.datatype_attributes (
	layer_id,
	name,
	real_name,
	tablename,
	table_alias_name,
	type,
	geometrytype,
	constraints,
	nullable,
	length,
	decimal_length,
	default,
	form_element_type,
	options,
	group,
	raster_visibility,
	mandatory,
	order,
	privileg,
	query_tooltip
)
VALUES (
	last_datatype_id,
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
	'{$attribute['ordinal_position']}', -- order
	'1',
	'0'
);
";
		return $sql;
	}

	function generate_classes($table) {
		$sql = "
-- Create class for layer {$table['name']}
INSERT INTO kvwmap.classes (
	name,
	layer_id,
	expression,
	drawingorder,
	text
)
VALUES(
	'alle',
	last_layer_id,
	'(1 = 1)',
	'1',
	''
)
RETURNING class_id INTO last_class_id;
";
		return $sql;
	}

	function generate_styles() {
		$sql = "
INSERT INTO kvwmap.styles (
	symbol,
	symbolname,
	size,
	color,
	backgroundcolor,
	outlinecolor,
	minsize,
	maxsize,
	angle,
	angleitem,
	width,
	minwidth,
	maxwidth
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
	NULL
)
RETURNING style_id INTO last_style_id;
";
		return $sql;
	}

	function generate_style2classes() {
		$sql = "
INSERT INTO kvwmap.u_styles2classes (
	style_id,
	class_id,
	drawingorder
) VALUES (
	last_style_id,
	last_class_id,
	0
);
";
		return $sql;
	}

	function read_colors() {
		$sql = "
			SELECT
				*
			FROM
				kvwmap.colors
		";
		#echo $sql;
		$ret = $this->execSQL($sql, 4, 0);
		while ($rs = pg_fetch_assoc($ret[1])) {
			$colors[] = $rs;
		}
		return $colors;
	}

  function read_color($id){
  	$sql = "
			SELECT 
				* 
			FROM 
				kvwmap.colors 
			WHERE 
				id = ".$id;
  	#echo $sql;
  	$ret = $this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    if($ret[0]==0){
      $color = pg_fetch_assoc($ret[1]);
    }
    return $color;
  }	

	function schema_exists($schema_name) {
		$sql = "
			SELECT
				EXISTS(
					SELECT
						1
					FROM
						information_schema.schemata
					WHERE 
						schema_name = '" . $schema_name . "'
					AND
						catalog_name = '" . POSTGRES_DBNAME . "'
				)
			;";
		$ret = $this->execSQL($sql, 4, 0);
		$result = pg_fetch_row($ret[1]);
		return ($result[0] === 't');
	}

	function create_schema($schema_name) {
		$sql = "
			CREATE SCHEMA IF NOT EXISTS " . $schema_name . "
		";
		$ret = $this->execSQL($sql, 4,0);
		return $ret;
	}

	function get_schemata($user_name) {
		$schemata = array();
		$sql = "
			SELECT
				schema_name
			FROM
			  information_schema.schemata
			WHERE
				schema_owner = '" . $user_name . "'
		";
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret['success']){
			while($row = pg_fetch_assoc($ret[1])){
				$schemata[] = $row['schema_name'];
			}
		}
		return $schemata;
	}

	function get_tables($schema) {
		$tables = array();
		$sql = "
			SELECT
			  ('{$schema}.' || table_name)::regclass::oid AS oid,
				table_schema AS schema_name,
			  table_name AS name
			FROM
			  information_schema.tables
			WHERE
			  table_schema = '{$schema}' AND
				table_name NOT LIKE 'enum_%'
			ORDER BY table_name
		";
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret['success']) {
			while ($row = pg_fetch_assoc($ret[1])){
				$tables[] = $row;
			}
		}
		return $tables;
	}

	/**
	 * Return an array with information about the table $schema_name.$table_name in
	 * database represented by $connection_id. The attributes of the array are:
	 * success true if the request was successful else false.
	 * table_name The requested $table_name.
	 * schema_name The requested $schema_name.
	 * oid_column The name of the column that is a primary column or can be used as a unique column for mapserver.
	 * geom_column The first geometry column.
	 * Datentyp The MapServer layer datatyp that fits the geometry column type. If no geometry column found return the type for a MS_LAYER_QUERY.
	 * epsg_code The EPSG-Code of the geometry column found.
	 */
	function get_table_infos($connection_id, $schema_name, $table_name) {
		$table_infos = array(
			'success' => false,
			'err_msg' => ''
		);
		$pgdatabase = new pgdatabase();
		if ($pgdatabase->open($connection_id)) {
			$oid_column = $pgdatabase->get_pk($schema_name, $table_name);
			if ($oid_column == '') {
				$oid_column = $pgdatabase->get_id($schema_name, $table_name);
			}
			$geom = $pgdatabase->get_geom_column($schema_name, $table_name);
			# Abfragen des Datentyps
			# Abfragen des epsg_codes
			$table_infos = array(
				'success' => true,
				'table_name' => $table_name,
				'schema_name' => $schema_name,
				'oid_column' => $oid_column,
				'geom_column' => $geom['column'],
				'datentyp' =>  $geom['datentyp'],
				'epsg_code' => $geom['epsg_code']
			);
		}
		else {
			$table_infos['err_msg'] = 'Die Datenbank mit der connection_id: ' . $connection_id . ' konnte nicht geöffent werden.';
		}
		return $table_infos;
	}

	/**
	 * Return the name of the first column found that is valid to be used as an id
	 * Search for a column of type serial,
	 * Search for a column with name id, gid, fid, oid, ogc_fid or gml_id and if all values are unique
	 */
	function get_id($schema_name, $table_name) {
		$id_column = '';
		return $id_column;
	}

	/**
	 * Return the name of the primary key column of the table $schema_name.$table_name
	 */
	function get_pk($schema_name, $table_name) {
		$pk_column = '';
		$sql = "
			SELECT
				pg_attribute.attname,
				format_type(pg_attribute.atttypid, pg_attribute.atttypmod)
			FROM pg_index, pg_class, pg_attribute, pg_namespace
			WHERE
				pg_class.oid = '" . $schema_name . "." . $table_name . "'::regclass AND
				indrelid = pg_class.oid AND
				nspname = '" . $schema_name . "' AND
				pg_class.relnamespace = pg_namespace.oid AND
				pg_attribute.attrelid = pg_class.oid AND
				pg_attribute.attnum = any(pg_index.indkey) AND
				indisprimary
		";
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret['success']) {
			if (pg_num_rows($ret[1]) > 0) {
				$row = pg_fetch_assoc($ret[1]);
				$pk_column = $row['attname'];
			}
		}
		return $pk_column;
	}

	/**
	 * Return an array with following information about first geometry column in table $schema_name.$table_name
	 * 	column Name of the geometry column
	 *  Datentyp MapServer datatype of the layer witch have that table in data
	 *  epsg_code EPSG-Code of the geometry column
	 * ToDo: Query these information from the table and geometry column
	 */
	function get_geom_column($schema_name, $table_name) {
		$geom_types = array('POINT' => 0, 'LINESTRING' => 1, 'POLYGON' => 2);
		$geom = array(
			'column' => '',
			'datentyp' => 5,
			'epsg_code' => null
		);
		$sql = "
			SELECT
				*
			FROM
				" . $schema_name . '.' . $table_name . "
		";
		$fields_from_select = $this->getFieldsfromSelect($sql);
		if ($fields_from_select['success']) {
			if ($fields_from_select[1]['the_geom']) {
				$geometry_column = $fields_from_select[1]['the_geom'];
				$the_geom_id = $fields_from_select[1]['the_geom_id'];
				$geom = array(
					'column' => $geometry_column,
					'datentyp' => $geom_types[$fields_from_select[1][$the_geom_id]['geomtype']]
				);
				$sql = "
					SELECT
						srid
					FROM
						geometry_columns 
					WHERE 
						f_table_schema = '" . $schema_name . "' AND 
						f_table_name = '" . $table_name . "' AND
						f_geometry_column = '" . $geometry_column . "'
				";
				$ret = $this->execSQL($sql, 4, 0);
				if ($ret['success']) {
					$rs = pg_fetch_assoc($ret[1]);
					$geom['epsg_code'] = $rs['srid'];
				}
				else {
					$err_msg = 'Fehler bei der Abfrage der srid der Geometriespalte ' . $geometry_column . ': ' . $ret[1];
				}
			}
			else {
				# Keine Geometriespalte in Tabelle gefunden.
			}
		}
		else {
			$err_msg = 'Fehler bei der Abfrage der Tabellenfelder mit der Funktion getFieldsfromSelect. ' . $ret[1];
		}

		return $geom;
	}

	/*
	* connect to the database to test if exists
	* @return boolean true if exists else false
	*/
	function database_exists() {
		$this->debug->write("Open Database " . $credentials['dbname'] . " to test if exists", $this->debug_level);

		$this->dbConn = pg_connect(
		$this->get_connection_string()
	);
		if (!$this->dbConn) {
			$this->err_msg = 'Die Verbindung zur PostGIS-Datenbank konnte mit folgenden Daten nicht hergestellt werden: ' . str_replace($credentials['password'], '********', $connection_string);
			return false;
		}
		else {
			$this->debug->write("Database connection: " . $this->dbConn . " successfully opend.", $this->debug_level);
			$this->setClientEncodingAndDateStyle();
			$this->connection_id = $connection_id;
			return true;
		}
	}

	function table_exists($schema, $table) {
		$table_exists = false;
		$sql = "
SELECT EXISTS (
	SELECT
		1 AS exists
	FROM
		information_schema.tables 
	WHERE
		table_schema = '{$schema}' AND
		table_name = '{$table}'
)";
		#echo '<br>' . $sql;
		$ret = $this->execSQL($sql, 4, 0);
		if($ret[0]==0) {
			$row = pg_fetch_row($ret[1]);
			if ($row[0] == 't') $table_exists = true;
		}
		return $table_exists;
	}

	function get_enum_options($schema, $attribute) {
		if ($this->table_exists($schema, 'enum_' . $attribute['type'])) {
			$options = "
SELECT
	wert AS value,
	beschreibung AS output
FROM
	{$schema}.enum_{$attribute['table_name']}
";
		}
		else {
			$options = '';
		}
		$enum_values = array();
		$sql = "
			SELECT unnest(enum_range(NULL::{$schema}.{$attribute['type']}) ) AS value
		";
		#echo '<br>' . $sql;
		$ret = $this->execSQL($sql, 4, 0);
		if($ret[0]==0){
			while($row = pg_fetch_assoc($ret[1])){
				$enum_values[] = $row['value'];
			}
		}
		$constraints = "\'" . implode("\', \'", $enum_values) . "\'";
		return array('option' => $options, 'constraint' => $constraints);
	}

	function get_datatypes($schema) {
		$datatypes = array();
		$sql = "
			SELECT
			  ('{$schema}.' || user_defined_type_name)::regclass::oid AS datatype_oid,
			  user_defined_type_name
			FROM 
			  information_schema.user_defined_types udt
			WHERE
			  udt.user_defined_type_schema = '{$schema}'
			ORDER BY user_defined_type_name
		";
		$ret = $this->execSQL($sql, 4, 0);
		if($ret[0]==0){
			while($row = pg_fetch_assoc($ret[1])){
				$datatypes[] = $row;
			}
		}
		return $datatypes;
	}

	function read_epsg_codes($order = true){
		global $supportedSRIDs;
		# Wenn zu unterstützende SRIDs angegeben sind, ist die Abfrage diesbezüglich eingeschränkt
		$anzSupportedSRIDs = count($supportedSRIDs);
		$sql = "
			SELECT
				spatial_ref_sys.srid, coalesce(alias, substr(srtext, 9, 35)) as srtext,
				proj4text,
				minx, miny, maxx, maxy
			FROM
				spatial_ref_sys LEFT JOIN
				spatial_ref_sys_alias ON spatial_ref_sys_alias.srid = spatial_ref_sys.srid
			WHERE
				" . ($anzSupportedSRIDs > 0 ? "spatial_ref_sys.srid IN (" . implode(', ', $supportedSRIDs) . ")" : "true") . "
			ORDER BY
				" . ($order ? $order : "srtext") . "
		";
		#echo 'SQL zum Abfragen der EPSG-Codes: ' . $sql;
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret[0] == 0) {
			$i = 0;
			while ($row = pg_fetch_assoc($ret[1])){
				$epsg_codes[$row['srid']] = $row;
				$i++;
			}
		}
		return $epsg_codes;
	}

  function transformRect($curExtent,$curSRID,$newSRID) {
    $sql ="SELECT round(CAST (st_x(min) AS numeric),5) AS minx, round(CAST (st_y(min) AS numeric),5) AS miny";
    $sql.=", round(CAST (st_x(max) AS numeric),5) AS maxx, round(CAST (st_y(max) AS numeric),5) AS maxy";
    $sql.=" FROM (SELECT";
    $sql.=" st_transform(st_geomfromtext('POINT(".$curExtent->minx." ".$curExtent->miny.")',".$curSRID."),".$newSRID.") AS min";
    $sql.=" ,st_transform(st_geomfromtext('POINT(".$curExtent->maxx." ".$curExtent->maxy.")',".$curSRID."),".$newSRID.") AS max";
    $sql.=") AS foo";
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_assoc($ret[1]);
      $curExtent->minx=$rs['minx'];
      $curExtent->miny=$rs['miny'];
      $curExtent->maxx=$rs['maxx'];
      $curExtent->maxy=$rs['maxy'];
      $ret[1]=$curExtent;
    }
    
    /*$projFROM = new projectionObj("init=epsg:".$curSRID);
		$projTO = new projectionObj("init=epsg:".$newSRID);
		$curExtent->project($projFROM, $projTO);
		$ret[0] = 0;
		$ret[1] = $curExtent;*/
    return $ret;
  }

	/**
	*	Execute the sql. Executes the sql as prepared query if $prepared_params has been passed.
	*	For prepared queries the sql string must have the same amount of placeholder as elements in prepared_params array
	*	and in correct order.
	*/
	function execSQL($sql, $debuglevel = 4, $loglevel = 1, $suppress_err_msg = false, $prepared_params = array()) {
		if (!$this->dbConn) {
			echo '<p>pgconn: ' . $this->dbConn;
		}
		$ret = array(); // Array with results to return
		$ret['msg'] = '';
		$strip_context = true;

		switch ($this->loglevel) {
			case 0 : {
				$logsql = 0;
			} break;
			case 1 : {
				$logsql = 1;
			} break;
			case 2 : {
				$logsql = $loglevel;
			} break;
		}
		# SQL-Statement wird nur ausgeführt, wenn DBWRITE gesetzt oder
		# wenn keine INSERT, UPDATE und DELETE Anweisungen in $sql stehen.
		# (lesend immer, aber schreibend nur mit DBWRITE=1)
		if (DBWRITE OR (!stristr($sql, 'INSERT') AND !stristr($sql, 'UPDATE') AND !stristr($sql, 'DELETE'))) {
			#echo "<br>SQL in execSQL: " . $sql;
			if ($this->schema != '') {
				$sql = "SET search_path = " . $this->schema . ", public;" . $sql;
			}
			if (count($prepared_params) > 0) {
				$query_id = 'query_' . rand();
				$query = pg_prepare($this->dbConn, $query_id, $sql);
				$query = pg_execute($this->dbConn, $query_id, $prepared_params);
			}
			else {
				#echo "<br>SQL in execSQL: " . $sql;
				$query = @pg_query($this->dbConn, $sql);
			}
			//$query=0;
			if ($query === false) {
				$this->error = true;
				$ret['success'] = false;
				$ret['sql'] = $sql;
				$last_error = pg_last_error($this->dbConn);
				if ($strip_context AND strpos($last_error, 'CONTEXT: ') !== false) {
					$ret['msg'] = substr($last_error, 0, strpos($last_error, 'CONTEXT: '));
				}
				else {
					$ret['msg'] = $last_error;
				}

				if (strpos($last_error, '{') !== false AND strpos($last_error, '}') !== false) {
					# Parse als JSON String;
					$error_obj = json_decode(substr($last_error, strpos($last_error, '{'), strpos($last_error, '}') - strpos($last_error, '{') + 1), true);
					if ($error_obj) {
						if (array_key_exists('msg_type', $error_obj)) {
							$ret['type'] = $error_obj['msg_type'];
						}
						if (array_key_exists('msg', $error_obj) AND $error_obj['msg'] != '') {
							$ret['msg'] = $error_obj['msg'];
						}
					}
				}
				else {
					$ret['type'] = 'error';
				}
				$this->debug->write("<br><b>" . $last_error . "</b>", $debuglevel);
				if ($logsql) {
					$this->logfile->write($this->commentsign . ' ' . $sql . ' ' . $last_error);
				}
			}
			else {
				# Abfrage wurde zunächst erfolgreich ausgeführt
				$ret[0] = 0;
				$ret['success'] = true;
				$ret[1] = $ret['query'] = $query;

				# Prüfe ob eine Fehlermeldung in der Notice steckt
				if (PHPVERSION >= 710) {
					$last_notices = pg_last_notice($this->dbConn, PGSQL_NOTICE_ALL);
				}
				else {
					$last_notices = array(pg_last_notice($this->dbConn));
				}
				foreach ($last_notices as $last_notice) {
					if ($strip_context AND strpos($last_notice, 'CONTEXT: ') !== false) {
						$last_notice = substr($last_notice, 0, strpos($last_notice, 'CONTEXT: '));
					}
					# Verarbeite Notice nur, wenn sie nicht schon mal vorher ausgewertet wurde
					if ($last_notice != '' AND ($this->gui->notices == NULL OR !in_array($last_notice, $this->gui->notices))) {
						$this->gui->notices[] = $last_notice;
						if (strpos($last_notice, '{') !== false AND strpos($last_notice, '}') !== false) {
							# Parse als JSON String
							$notice_obj = json_decode(substr($last_notice, strpos($last_notice, '{'), strpos($last_notice, '}') - strpos($last_notice, '{') + 1), true);
							if ($notice_obj AND array_key_exists('success', $notice_obj)) {
								if (!$notice_obj['success']) {
									$ret['success'] = false;
								}
								if (array_key_exists('msg_type', $notice_obj)) {
									$ret['type'] = $notice_obj['msg_type'];
								}
								if (array_key_exists('msg', $notice_obj) AND $notice_obj['msg'] != '') {
									$ret['msg'] .= $notice_obj['msg'];
								}
							}
						}
						else {
							# Gebe Noticetext wie er ist zurück
							$ret['msg'] .= $last_notice.chr(10).chr(10);
						}
					}
				}

				# Schreibe Meldungen in Log und Debugfile
				$this->debug->write("<br>" . $sql, $debuglevel);
				if ($logsql) {
					$this->logfile->write($sql . ';');
				}
			}
			$ret[2] = $sql;
		}
		else {
			# Es werden keine SQL-Kommandos ausgeführt
			# Die Funktion liefert ret[0]=0, und zeigt damit an, daß kein Datenbankfehler aufgetreten ist,
			$ret[0] = 0;
			$ret['success'] = true;
			# jedoch hat $ret[1] keine query_ID sondern auch den Wert 0
			$ret[1] = 0;
			# Wenn $this->loglevel != 0 wird die sql-Anweisung in die logdatei geschrieben
			# zusätzlich immer in die debugdatei
			# 2006-07-04 pk $logfile ersetzt durch $this->logfile
			if ($logsql) {
				$this->logfile->write($sql . ';');
			}
			$this->debug->write("<br>" . $sql, $debuglevel);
		}

		if ($ret['success']) {
			# alles ok mach nichts weiter
		}
		else {
			# Fehler setze entsprechende Flags und Fehlermeldung
			$ret[0] = 1;
			$ret[1] = $ret['msg'];
			if ($suppress_err_msg) {
				# mache nichts, denn die Fehlermeldung wird unterdrückt
			}
			else {
				if (strpos(strtolower($this->gui->formvars['export_format']), 'json') !== false) {
					header('Content-Type: application/json; charset=utf-8');
					echo utf8_decode(json_encode($ret));
					exit;
				}
				# gebe Fehlermeldung aus.
				$ret[1] = $ret['msg'] = sql_err_msg('Fehler bei der Abfrage der PostgreSQL-Datenbank:' . $sql, $sql, $ret['msg'], 'error_div_' . rand(1, 99999));
				$this->gui->add_message($ret['type'], $ret['msg']);
				// echo $sql; exit;
				header('error: true');	// damit ajax-Requests das auch mitkriegen
			}
		}
		$this->success = $ret['success'];
		return $ret;
	}

	function build_temporal_filter($tablenames){
		$timestamp = rolle::$hist_timestamp;
		if($timestamp == ''){
			foreach($tablenames as $tablename){
				$filter .= ' AND '.$tablename.'.endet IS NULL ';
			}
		}
		else{
			foreach($tablenames as $tablename){
				$filter .= " AND tsrange(" . $tablename . ".beginnt, " . $tablename . ".endet) @> '" . $timestamp . "'::timestamp ";
			}
		}
		return $filter;
	}
	
	function build_temporal_filter_fachdatenverbindung($tablenames){
		$timestamp = rolle::$hist_timestamp;
		if($timestamp == ''){
			foreach($tablenames as $tablename){
				$filter .= ' AND (' . $tablename . '.historisch IS NULL OR ' . $tablename . '.historisch != \'true\') AND ('.$tablename.'.zeigtaufexternes_art IS NULL OR NOT \'urn:mv:fdv:7040\' = any('.$tablename.'.zeigtaufexternes_art))';
			}
		}
		return $filter;
	}		

  function transformPoly($polygon,$curSRID,$newSRID) {
    $sql ="SELECT st_astext(st_transform(st_geomfromtext('".$polygon."', ".$curSRID."), ".$newSRID."))";
    $ret=$this->execSQL($sql, 4, 0);
    if($ret[0] == 0){
      $rs=pg_fetch_row($ret[1]);
    }
    return $rs[0];
  }

	function transformPoint($point, $curSRID, $newSRID, $coordtype){
		$sql ="SELECT st_X(point) AS x, st_Y(point) AS y";
    $sql.=" FROM (SELECT st_transform(st_geomfromtext('POINT(".$point.")',".$curSRID."),".$newSRID.") AS point) AS foo";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_assoc($ret[1]);
      if($coordtype != 'dec' AND $rs['x'] < 361){
				switch ($coordtype) {
					case 'dms' :
	      		$rs['x'] = dec2dms($rs['x']);
	      		$rs['y'] = dec2dms($rs['y']);
						break;
					case 'dmin' :
	      		$rs['x'] = dec2dmin($rs['x']);
	      		$rs['y'] = dec2dmin($rs['y']);
						break;
				}
      }
      else{
      	if($newSRID == 4326){
      		$stellen = 5;
      	}
      	else{
      		$stellen = 2;
      	} 
      	$rs['x'] = round($rs['x'], $stellen);
      	$rs['y'] = round($rs['y'], $stellen);
      }
      $ret[1]=$rs['x'].' '.$rs['y'];
    }
    return $ret;
	}

  function pivotTable($schema, $table) {
    $sql ="SELECT * FROM \"$schema\".\"$table\"";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    return $ret[1];    
  }

	/**
	 * Function return the WHERE Expression-String to filter by $extent and if $null_geom true not the NULL-Geometries.
	 * @param rectObj $extent A MapServer Rect-Object with the extent used to create the envelope.
	 * @param Integer $extent_epsg The EPSG-Code of the extent in Rect-Object $extent.
	 * @param String $geom_column The name of the geometry column used for the filter.
	 * @param Integer $geom_column_epsg The EPSG-Code of the geometries in the geometry column
	 * @param String $geom_column_tablealias An optional alias name of the table that has the geometry column.
	 * @param Boolean $null_geom Optional parameter to append OR $geom_column IS NULL, if $null_geom is true, to the expression string.
	 * @return String The resulting Expression-String enclosed in round brackets ().
	 */
	function get_extent_filter($extent, $extent_epsg, $geom_column, $geom_column_epsg, $geom_column_table_alias = NULL, $null_geom = true) {
		$sql_envelope = "ST_MakeEnvelope(
			" . $extent->minx . ", " . $extent->miny . ", " . $extent->maxx . ", " . $extent->maxy . ",
			" . $extent_epsg . "
		)";
		if ($extent_epsg != $geom_column_epsg) {
			$sql_envelope = "ST_Transform(
				" . $sql_envelope . ",
				" . $geom_column_epsg . "
			)";
		}
		$extent_filter = $sql_envelope . " && " . ($geom_column_table_alias ? $geom_column_table_alias . "." : "") . $geom_column;
		$null_filter = ($null_geom ? " OR " . $geom_column . " IS NULL" : "");
		$sql = "(" . $extent_filter . $null_filter . ")";
		// echo '<br>SQL-Extent-Filter: ' . $sql;
		return $sql;
	}

	function pg_field_schema($table_oid){
		if($table_oid != ''){
			$sql = "select nspname as schema from pg_class c, pg_namespace ns
						where c.relnamespace = ns.oid 
						and c.oid = ".$table_oid;
			$ret = $this->execSQL($sql, 4, 0);
			if($ret[0]==0)$ret = pg_fetch_assoc($ret[1]);
			return $ret['schema'];
		}
	}

	function get_table_alias_names($query_plan){
		$table_alias_names = [];
		$table_info = explode(":eref \n         {ALIAS \n         ", $query_plan);
		for($i = 1; $i < count($table_info); $i++){
			$table_alias = str_replace([' ', chr(10), chr(13)], '', get_first_word_after($table_info[$i], ':aliasname', ' ', ':'));
			$table_oid = get_first_word_after($table_info[$i], ':relid');
			if($table_oid AND !array_key_exists($table_oid, $table_alias_names) AND $table_alias != 'unnamed_join'){
				$table_alias_names[$table_oid] = $table_alias;
			}
		}
		return $table_alias_names;
	}

	function get_target_entries($parse_tree){
		$target_entry_parts = explode("\n      {TARGETENTRY", $parse_tree);
		#$statement_begin = get_first_word_after($parse_tree, "\n   :stmt_location");
		array_shift($target_entry_parts);
		foreach ($target_entry_parts as $target_entry_part){
			# Spaltennummer in der Tabelle
			$target_entry['col_num'] = get_first_word_after($target_entry_part, "\n      :resorigcol");
			# Beginn im Statement (funktioniert nicht zuverlässig z.B. bei cast(...))
			#$target_entry['attribute_begin'] = get_first_word_after($target_entry_part, "\n         :location") - $statement_begin;
			$target_entries[] = $target_entry;
		}
		return $target_entries;
	}

	function getFieldsfromSelect($select, $assoc = false, $pseudo_realnames = false) {
		$err_msgs = array();
		$error_reporting = error_reporting();
		error_reporting(E_NOTICE);
		ini_set("pgsql.log_notice", '1');
		ini_set("pgsql.ignore_notice", '0');
		ini_set("display_errors", '0');
		$error_list = array();
		$myErrorHandler = function ($error_level, $error_message, $error_file, $error_line) use (&$error_list) {
			if(strpos($error_message, "\n      :resno") !== false){
				$error_list[] = $error_message;
			}
			#return false;
		};
		set_error_handler($myErrorHandler);
		# den Queryplan als Notice mitabfragen um an Infos zur Query zu kommen
		$sql = "
			SET client_min_messages='log';
			" . ($this->host == 'pgsql'? "SET log_min_messages='fatal';" : "") . "
			SET debug_print_parse=true;" . 
			$select . " LIMIT 0;";
		$ret = $this->execSQL($sql, 4, 0);
		$sql = "
			SET debug_print_parse = false;
			SET client_min_messages = 'NOTICE';
			" . ($this->host == 'pgsql'? "SET log_min_messages='error';" : "");
		$this->execSQL($sql, 4, 0);
		error_reporting($error_reporting);
		ini_set("display_errors", '1');
		if ($ret['success']) {
			$parse_tree = $error_list[0];
			$table_alias_names = $this->get_table_alias_names($parse_tree);
			$target_entries = $this->get_target_entries($parse_tree);
			if ($pseudo_realnames) {
				include_once(CLASSPATH . 'sql.php');
				$sql_object = new SQL($select);
				$select_attr = $sql_object->get_attributes(false);
			}
			for ($i = 0; $i < pg_num_fields($ret[1]); $i++) {
				# Attributname
				$fields[$i]['name'] = $fieldname = pg_field_name($ret[1], $i);
				
				# Spaltennummer in der Tabelle
				$col_num = $target_entries[$i]['col_num'];
				
				# Tabellen-oid des Attributs
				$table_oid = pg_field_table($ret[1], $i, true);

				# wenn das Attribut eine Tabellenspalte ist -> weitere Attributeigenschaften holen
				if ($table_oid > 0) {
					# Tabellenname des Attributs
					$fields[$i]['table_name'] = $tablename = pg_field_table($ret[1], $i);
					if ($tablename != NULL) {
						$all_table_names[] = $tablename;
					}
										
					# Tabellenaliasname des Attributs
					$fields[$i]['table_alias_name'] = $table_alias_names[$table_oid];

					# Schemaname der Tabelle des Attributs
					if($schema_names[$table_oid] == NULL){
						$schema_names[$table_oid] = $this->pg_field_schema($table_oid);		# der Schemaname kann hiermit aus der Query ermittelt werden; evtl. in layer_attributes speichern?	
					}
					$fields[$i]['schema_name'] = $schemaname = $schema_names[$table_oid];
					
					$constraintstring = '';
					// Frage die attribute informationen der Tablle falls noch nicht geschehen
					if(!is_array($attribute_infos[$schemaname][$tablename])){
						$attribute_infos[$schemaname][$tablename] = $this->get_attribute_information($schemaname, $tablename);
					}
					$attr_info = $attribute_infos[$schemaname][$tablename][$col_num];
					if($attr_info['relkind'] == 'v'){		# wenn View, dann Attributinformationen aus View-Definition holen
						if($view_defintion_attributes[$tablename] == NULL) {
							$ret2 = $this->getFieldsfromSelect(substr($attr_info['view_definition'], 0, -1), true);
							if ($ret2['success']) {
								$view_defintion_attributes[$tablename] = $ret2[1];
							}
							else {
								# Füge Fehlermeldung hinzu und setze leeres Array
								$err_msgs[] = $ret2[1];
								$view_defintion_attributes[$tablename] = array();
							}
						}
						if ($view_defintion_attributes[$tablename][$fieldname]['nullable'] != NULL) {
							$attr_info['nullable'] = $view_defintion_attributes[$tablename][$fieldname]['nullable'];
						}
						if ($view_defintion_attributes[$tablename][$fieldname]['default'] != NULL) {
							$attr_info['default'] = $view_defintion_attributes[$tablename][$fieldname]['default'];
						}
					}
					# realer Name der Spalte in der Tabelle
					$fields[$i]['real_name'] = $attr_info['name'];
					$fieldtype = $attr_info['type_name'];
					$fields[$i]['nullable'] = $attr_info['nullable']; 
					$fields[$i]['length'] = $attr_info['length'];
					$fields[$i]['decimal_length'] = $attr_info['decimal_length'];
					$fields[$i]['default'] = ($attr_info['generated'] == '' ? $attr_info['default'] : '');
					$fields[$i]['type_type'] = $attr_info['type_type'];
					$fields[$i]['type_schema'] = $attr_info['type_schema'];
					$fields[$i]['is_array'] = $attr_info['is_array'];
					if ($attr_info['is_array'] == 't') {
						$prefix = '_'; 
					}
					else {
						$prefix = '';
					}
					if($attr_info['type_type'] == 'e'){		# enum
						$fieldtype = $prefix.'text';
						$constraintstring = $this->getEnumElements($attr_info['type'], $attr_info['type_schema']);
					}
					if($attr_info['indisunique'] == 't')$constraintstring = 'UNIQUE';
					if($attr_info['indisprimary'] == 't')$constraintstring = 'PRIMARY KEY';
					if(!is_array($constraints[$table_oid])){
						$constraints[$table_oid] = $this->pg_table_constraints($table_oid);
					}
					if($fieldtype != 'geometry'){
						# testen ob es für ein Attribut ein constraint gibt, das wie enum wirkt
						for($j = 0; $j < count_or_0($constraints[$table_oid] ?: []); $j++){
							if(strpos($constraints[$table_oid][$j], '(' . $fieldname . ')') AND strpos($constraints[$table_oid][$j], '=')){
								$options = explode("'", $constraints[$table_oid][$j]);
								for($k = 0; $k < count($options); $k++){
									if($k%2 == 1){
										if($k > 1){
											$constraintstring.= ",";
										}
										$constraintstring.= "'".$options[$k]."'";
									}
								}
							}
						}
					}
					$fields[$i]['constraints'] = $constraintstring;
					$fields[$i]['saveable'] = ($attr_info['generated'] == '' ? 1 : 0);
				}
				else { # Attribut ist keine Tabellenspalte -> nicht speicherbar
					$fieldtype = pg_field_type($ret[1], $i);			# Typ aus Query ermitteln
					$fields[$i]['saveable'] = 0;
					$fields[$i]['real_name'] = pg_escape_string($select_attr[$fields[$i]['name']]['base_expr']);
				}
				$fields[$i]['type'] = $fieldtype;

				# Geometrietyp
				if ($fieldtype == 'geometry') {
					$fields[$i]['geomtype'] = $this->get_geom_type($schemaname, $fields[$i]['real_name'], $tablename);
					$field_the_geom = $fieldname;
					$field_the_geom_id = $i;
				}
				if ($assoc) {
					$fields_assoc[$fieldname] = $fields[$i];
				}
			}
			$fields['the_geom'] = $field_the_geom;
			$fields['the_geom_id'] = $field_the_geom_id;
			$ret[1] = ($assoc ? $fields_assoc : $fields);
		}
		else {
			# Füge Fehlermeldung hinzu
			$err_msgs[] = $ret[1];
		}

		if (count($err_msgs) > 0) {
			# Wenn Fehler auftraten liefer nur die Fehler zurück
			$ret[0] = 1;
			$ret['success'] = false;
			$ret[1] = implode('<br>', $err_msgs);
		}
		return $ret;
	}

	function get_attribute_information($schema, $table, $col_num = NULL) {
		if ($col_num != NULL) {
			$and_column = " a.attnum = " . $col_num . " ";
		}
		else {
			$and_column = " a.attnum > 0 ";
		}
		$attributes = array();
		$sql = "
			SELECT
				ns.nspname as schema,
				c.relname AS table_name,
				c.relkind,
				a.attname AS name,
				NOT a.attnotnull AS nullable,
				" . (POSTGRESVERSION >= 1300 ? 'a.attgenerated as generated' : 'NULL as generated') . ",
				a.attnum AS ordinal_position,
				pg_get_expr(ad.adbin, ad.adrelid) as default,
				t.typname AS type_name,
				tns.nspname as type_schema,
				CASE WHEN t.typarray = 0 THEN eat.typname ELSE t.typname END AS type,
				t.oid AS attribute_type_oid,
				coalesce(eat.typtype, t.typtype) as type_type,
				case when t.typarray = 0 THEN true ELSE false END AS is_array,
				CASE WHEN t.typname = 'varchar' AND a.atttypmod > 0 THEN a.atttypmod - 4 ELSE NULL END as character_maximum_length,
				CASE a.atttypid
				 WHEN 21 /*int2*/ THEN 16
				 WHEN 23 /*int4*/ THEN 32
				 WHEN 20 /*int8*/ THEN 64
				 WHEN 1700 /*numeric*/ THEN
				      CASE WHEN atttypmod = -1
					   THEN null
					   ELSE ((atttypmod - 4) >> 16) & 65535
					   END
				 WHEN 700 /*float4*/ THEN 24 /*FLT_MANT_DIG*/
				 WHEN 701 /*float8*/ THEN 53 /*DBL_MANT_DIG*/
				 ELSE null
				END   AS numeric_precision,
				CASE 
				    WHEN atttypid IN (21, 23, 20) THEN 0
				    WHEN atttypid IN (1700) THEN
					CASE 
					    WHEN atttypmod = -1 THEN null
					    ELSE (atttypmod - 4) & 65535
					END
				       ELSE null
				  END AS decimal_length,
				i.indisunique,
				i.indisprimary,
				v.definition as view_definition
			FROM
				pg_catalog.pg_class c JOIN
				pg_catalog.pg_attribute a ON (c.oid = a.attrelid) JOIN
				pg_catalog.pg_namespace ns ON (c.relnamespace = ns.oid) JOIN
				pg_catalog.pg_type t ON (a.atttypid = t.oid) LEFT JOIN
				pg_catalog.pg_namespace tns ON (t.typnamespace = tns.oid) LEFT JOIN
				pg_catalog.pg_type eat ON (t.typelem = eat.oid) LEFT JOIN 
				pg_index i ON i.indrelid = c.oid AND a.attnum = ANY(i.indkey)	LEFT JOIN 
				pg_catalog.pg_attrdef ad ON a.attrelid = ad.adrelid AND ad.adnum = a.attnum LEFT JOIN 
				pg_catalog.pg_views v ON v.viewname = c.relname AND v.schemaname = ns.nspname
			WHERE
				ns.nspname IN ('" .  implode("','", array_map(function($schema) { return trim($schema); }, explode(',', $schema)))  .  "') AND
				c.relname = '" . $table . "' AND
				" . $and_column . "
			ORDER BY a.attnum, indisunique desc, indisprimary desc
		";
		#echo '<br>SQL zur Abfrage der Attributinformationen aus der Datenbank: ' . $sql;
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret[0] == 0) {
			while ($attr_info = pg_fetch_assoc($ret[1])) {
				if ($attr_info['nullable'] == 'f' AND substr($attr_info['default'], 0, 7) != 'nextval') {
					$attr_info['nullable'] = '0';
				}
				else {
					$attr_info['nullable'] = '1';
				}
        if ($attr_info['numeric_precision'] != '') {
					$attr_info['length'] = $attr_info['numeric_precision'];
				}
				else {
					$attr_info['length'] = $attr_info['character_maximum_length'];
				}
				if ($attr_info['decimal_length'] == '') {
					$attr_info['decimal_length'] = 'NULL';
				}/*
				if (strpos($attr_info['type_name'], 'xp_spezexternereferenzauslegung') !== false) {
					$attr_info['type_name'] = str_replace('xp_spezexternereferenzauslegung', 'xp_spezexternereferenz', $attr_info['type_name']);
					$attr_info['type'] = str_replace('xp_spezexternereferenzauslegung', 'xp_spezexternereferenz', $attr_info['type']);
				}*/
				$attributes[$attr_info['ordinal_position']] = $attr_info;
			}
		}
		return $attributes;
	}
     	
	function writeCustomType($layer_id, $typname, $schema) {
		$datatype_id = $this->getDatatypeId($typname, $schema, $this->connection_id);
		$this->writeDatatypeAttributes($layer_id, $datatype_id, $typname, $schema);
		return $datatype_id;
	}
	
	function getDatatypeId($typname, $schema, $connection_id){
		$sql = "
			SELECT
				id
			FROM
				kvwmap.datatypes
			WHERE
				name = '" . $typname . "' AND
				schema = '" . $schema . "' AND
				connection_id = " . $connection_id . "
		";
		$ret1 = $this->execSQL($sql, 4, 1);
		if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		$rs = pg_fetch_assoc($ret1[1]);
		if ($rs == NULL) {
			$sql = "
				INSERT INTO kvwmap.datatypes (
					name,
					schema,
					connection_id
				)
				VALUES (
					'" . $typname . "',
					'" . $schema . "',
					'" . $connection_id . "'
				)
				RETURNING id
			";
			$ret2 = $this->execSQL($sql, 4, 1);
			$rs = pg_fetch_assoc($ret2[1]);
		}
		$datatype_id = $rs['id'];
		return $datatype_id;
	}
	
	function getEnumElements($name, $schema){
		$sql = "SELECT array_to_string(array_agg(''''||e.enumlabel||''''), ',') as enum_string ";
		$sql.= "FROM pg_enum e ";
		$sql.= "JOIN pg_type t ON e.enumtypid = t.oid ";
		$sql.= "JOIN pg_namespace ns ON (t.typnamespace = ns.oid) ";
		$sql.= "WHERE t.typname = '".$name."' ";
		$sql.= "AND ns.nspname = '".$schema."'";
		$ret1 = $this->execSQL($sql, 4, 0);
		if($ret1[0]==0){
			$result = pg_fetch_assoc($ret1[1]);
		}
		return $result['enum_string'];
	}
	
	function writeDatatypeAttributes($layer_id, $datatype_id, $typname, $schema){
		$attr_info = $this->get_attribute_information($schema, $typname);
		$attribute_names = [];
		for($i = 1; $i < count($attr_info)+1; $i++){
			$fields[$i]['real_name'] = $attr_info[$i]['name'];
			$attribute_names[] = $fields[$i]['name'] = $attr_info[$i]['name'];
			$fieldtype = $attr_info[$i]['type_name'];
			$fields[$i]['nullable'] = $attr_info[$i]['nullable']; 
			$fields[$i]['length'] = $attr_info[$i]['length'];
			$fields[$i]['decimal_length'] = $attr_info[$i]['decimal_length'];
			$fields[$i]['default'] = $attr_info[$i]['default'];					
			if($attr_info[$i]['is_array'] == 't')$prefix = '_'; else $prefix = '';
			if($attr_info[$i]['type_type'] == 'c'){		# custom datatype
				$sub_datatype_id = $this->writeCustomType($layer_id, $attr_info[$i]['type'], $attr_info[$i]['type_schema']);
				$fieldtype = $prefix.$sub_datatype_id; 
			}
			$constraintstring = '';
			if($attr_info[$i]['type_type'] == 'e'){		# enum
				$fieldtype = $prefix.'text';
				$constraintstring = $this->getEnumElements($attr_info[$i]['type'], $attr_info[$i]['type_schema']);
			}
			$fields[$i]['constraints'] = $constraintstring;
			$fields[$i]['type'] = $fieldtype;
			if($fields[$i]['nullable'] == '')$fields[$i]['nullable'] = 'NULL';
			if($fields[$i]['length'] == '')$fields[$i]['length'] = 'NULL';
			if($fields[$i]['decimal_length'] == '')$fields[$i]['decimal_length'] = 'NULL';

			$columns = [
				'layer_id' => $layer_id,
				'datatype_id' => $datatype_id,
				'name' => "'" . $fields[$i]['name'] . "'",
				'real_name' => "'" . $fields[$i]['real_name'] . "'",
				'type' => "'" . $fields[$i]['type'] . "'",
				'constraints' => "'" . pg_escape_string($fields[$i]['constraints']) . "'",
				'form_element_type' => "'" . (pg_escape_string($fields[$i]['constraints']) != '' ? 'Auswahlfeld' : 'Text') . "'",
				'nullable' => $fields[$i]['nullable'],
				'length' => $fields[$i]['length'],
				'decimal_length' => $fields[$i]['decimal_length'],
				'"default"' => "'" . pg_escape_string($fields[$i]['default']) . "'",
				'"order"' => $i
			];
			$sql = "
				INSERT INTO
					kvwmap.datatype_attributes
					(" . implode(', ', array_keys($columns)) . ")
				VALUES	
					(" . implode(', ', $columns) . ")
				ON CONFLICT (layer_id, datatype_id, name) DO	UPDATE 
					SET " .
						implode(', ',	array_map(function($key) {return $key . ' = EXCLUDED.' . $key;}, array_keys($columns)));
			#echo "<br>SQL zum Anlegen eines Datentypes: " . $sql;
			$ret1 = $this->execSQL($sql, 4, 1);
			if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
		}
		$sql = "
			DELETE FROM
				kvwmap.datatype_attributes
			WHERE
				layer_id = " . $layer_id . " AND
				datatype_id = " . $datatype_id . " AND
				name NOT IN ('" . implode("', '", $attribute_names) . "')";
		#echo "<br>Löschen der alten Datentyp-Attribute: " . $sql;
		$ret1 = $this->execSQL($sql, 4, 1);
		if($ret1[0]){ $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
	}

	/*
	* Fragt den Geometrietyp der Spalte aus geometry_column ab
	* Wird dort nichts gefunden wird GEOMETRY gesetzt
	* @param string $geomcolumn Name der Geometriespalte
	* @param string $tablename Name der Tabelle
	* @return string Geometrytyp
	*/
	function get_geom_type($schema, $geomcolumn, $tablename){
		if ($schema == '') {
			$schema = 'public';
		}
		$schema = str_replace(',', "','", $schema);
		if ($geomcolumn != '' AND $tablename != '') {
			#-- search_path ist zwar gesetzt, aber nur auf custom_shapes, daher ist das Schema der Tabelle erforderlich
			$sql = "
				SELECT coalesce(
					(select type from geometry_columns WHERE 
					 f_table_schema IN ('" . $schema . "') and 
					 f_table_name = '" . $tablename . "' AND 
					 f_geometry_column = '" . $geomcolumn . "'),
					(select geometrytype(" . $geomcolumn . ") FROM " . $schema . "." . pg_quote($tablename) . " where " . $geomcolumn . " IS NOT NULL limit 1)
				) as type
			";
			$ret1 = $this->execSQL($sql, 4, 0);
			if($ret1[0] == 0) {
				$result = pg_fetch_assoc($ret1[1]);
				$geom_type = $result['type'];
			}
			else {
				$geom_type = 'GEOMETRY';
			}
		}
		else{
			$geom_type = NULL;
		}
		return $geom_type;
	}
  	
  function pg_table_constraints($table_oid){
  	if($table_oid != ''){
			$constraints = array();
	    $sql = "SELECT pg_get_expr(conbin, conrelid) as constraint FROM pg_constraint, pg_class WHERE contype = 'check'";
	    $sql.= " AND pg_class.oid = pg_constraint.conrelid AND pg_class.oid = '".$table_oid."'";
	    $ret = $this->execSQL($sql, 4, 0);
	    if($ret[0]==0){
	      while($row = pg_fetch_assoc($ret[1])){
	        $constraints[] = $row['constraint'];
	      }
	    }
	    return $constraints;
  	}
  }

  function deletepolygon($poly_id){
    $sql = 'DELETE FROM u_polygon WHERE id = '.$poly_id;
    #echo $sql;
    $ret = $this->execSQL($sql, 4, 0);
    return $ret;
  }

  function updatepolygon($wkt_string, $srid, $poly_id){
    $sql = 'UPDATE u_polygon SET the_geom = st_transform(st_geomfromtext(\''.$wkt_string.'\','.$srid.'), (select srid from geometry_columns where f_table_name = \'u_polygon\' and f_table_schema = \'public\')) WHERE id = '.$poly_id;
    #echo $sql;
    $ret = $this->execSQL($sql, 4, 0);
    return $ret;
  }

  function insertpolygon($wkt_string, $srid){
    $sql = 'INSERT into u_polygon (the_geom) VALUES (st_transform(st_geomfromtext(\''.$wkt_string.'\','.$srid.'), (select srid from geometry_columns where f_table_name = \'u_polygon\' and f_table_schema = \'public\')))';
    #echo $sql;
    $ret = $this->execSQL($sql, 4, 0);
    $sql = 'SELECT currval(\'u_polygon_id_seq\')';
    $ret = $this->execSQL($sql, 4, 0);
    $poly_id = pg_fetch_row($ret[1]);
    return $poly_id[0];
  }

  function selectPolyAsSVG($poly_id, $srid){
    $sql = "SELECT st_assvg(st_transform(the_geom, ".$srid.")) FROM u_polygon WHERE id='".$poly_id."'";
    $ret=$this->execSQL($sql,4, 0);
    $rs= pg_fetch_row($ret[1]);
    $poly=$rs[0];
    return $poly;
  }

  function selectPolyAsText($poly_id, $srid){
    $sql = "SELECT st_astext(st_transform(the_geom, ".$srid.")) FROM u_polygon WHERE id='".$poly_id."'";
    $ret=$this->execSQL($sql,4, 0);
    $rs= pg_fetch_row($ret[1]);
    $poly=$rs[0];
    return $poly;
  }

  function getpolygon($poly_id, $srid){
    $sql = 'SELECT st_transform(the_geom, '.$srid.') from u_polygon WHERE id = '.$poly_id;
    $ret = $this->execSQL($sql, 4, 0);
    if($ret[0]==0){
      $poly = pg_fetch_row($ret[1]);
    }
    return $poly[0];
  }

  function getPolygonBBox($table, $id, $srid) {
    $sql ='SELECT st_xmin(st_extent(st_transform(the_geom, '.$srid.'))) AS minx,st_ymin(st_extent(st_transform(the_geom, '.$srid.'))) AS miny';
    $sql.=',st_xmax(st_extent(st_transform(the_geom, '.$srid.'))) AS maxx,st_ymax(st_extent(st_transform(the_geom, '.$srid.'))) AS maxy';
    $sql.=' FROM '.$table.' ';
    $sql.='WHERE id='.$id;
    $ret=$this->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1].='Fehler bei der Abfrage der Boundingbox! \n';
    }
    else {
      # Abfrage fehlerfrei
      # Erzeugen eines RectObject
      # Abfragen und zuordnen der Koordinaten der Box
      $rs=pg_fetch_assoc($ret[1]);
      if ($rs['maxx']-$rs['minx']==0) {
        $rs['maxx']=$rs['maxx']+1;
        $rs['minx']=$rs['minx']-1;
      }
      if ($rs['maxy']-$rs['miny']==0) {
        $rs['maxy']=$rs['maxy']+1;
        $rs['miny']=$rs['miny']-1;
      }
			$rect = rectObj(
      	$rs['minx'],
				$rs['miny'],
      	$rs['maxx'], 
				$rs['maxy']
			);
      $ret[1]=$rect;
    }
    return $ret;
  }

	function getWKTBBox($wkt, $fromsrid, $tosrid) {
    $sql ="SELECT st_xmin(geom) AS minx, st_ymin(geom) AS miny, st_xmax(geom) AS maxx, st_ymax(geom) AS maxy ";
    $sql.=" FROM (select st_extent(st_transform(st_geomfromtext('".$wkt."', ".$fromsrid."), ".$tosrid.")) as geom) as foo";
    $ret=$this->execSQL($sql,4, 0);
    if($ret[0] == 0){
      $rs=pg_fetch_assoc($ret[1]);
      $rect = rectObj(
				$rs['minx']-30,
				$rs['miny']-30,
      	$rs['maxx']+30,
				$rs['maxy']+30
			);
      return $rect;
    }
  }
  	  
  function getBuchungenFromGrundbuch($FlurstKennz,$Bezirk,$Blatt,$hist_alb = false, $fiktiv = false, $buchungsstelle = NULL, $without_temporal_filter = false) {
    $sql ="SELECT DISTINCT gem.schluesselgesamt as gemkgschl, gem.bezeichnung as gemarkungsname, g.land || g.bezirk as bezirk, g.bezirk as gbezirk, g.buchungsblattnummermitbuchstabenerweiterung AS blatt, g.blattart, s.gml_id, s.laufendenummer AS bvnr, ltrim(s.laufendenummer, '~>a')::integer, s.buchungsart, s.buchungstext, art.beschreibung as bezeichnung, f.flurstueckskennzeichen as flurstkennz, s.zaehler::text||'/'||s.nenner::text as anteil, s.nummerimaufteilungsplan as auftplannr, s.beschreibungdessondereigentums as sondereigentum "; 
		if($FlurstKennz!='') {
			if ($hist_alb) {
				$sql .= "FROM alkis.ax_historischesflurstueckohneraumbezug f ";
				$istgebucht = 'isthistgebucht';
			}
			else {
				$sql.="FROM alkis.ax_flurstueck f ";
				$istgebucht = 'istgebucht';
			}
			$sql.="LEFT JOIN alkis.ax_gemarkung gem ON f.land = gem.land AND f.gemarkungsnummer = gem.gemarkungsnummer ";
			if($fiktiv){
				$sql.="JOIN alkis.ax_buchungsstelle s ON ARRAY[f." . $istgebucht . "] <@ s.an ";
			}
			else $sql.="JOIN alkis.ax_buchungsstelle s ON f." . $istgebucht . " = s.gml_id OR ARRAY[f.gml_id] <@ s.verweistauf OR (s.buchungsart != 2103 AND ARRAY[f." . $istgebucht . "] <@ s.an) ";
			
			$sql.="LEFT JOIN alkis.ax_buchungsart_buchungsstelle art ON s.buchungsart = art.wert ";
			$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		}
		else{
			$sql.=", b.bezeichnung as gbname FROM alkis.ax_buchungsblatt g ";
			$sql.="LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk ";
			$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON s.istbestandteilvon = g.gml_id ";
			$sql.="LEFT JOIN alkis.ax_flurstueck f ON f.istgebucht = s.gml_id OR f.gml_id = ANY(s.verweistauf) OR (s.buchungsart != 2103 AND f.istgebucht = ANY(s.an)) ";		# angepasst wegen Gebäudeeigentum (2103) bei 13274300200046______
			$sql.="LEFT JOIN alkis.ax_gemarkung gem ON f.land = gem.land AND f.gemarkungsnummer = gem.gemarkungsnummer ";
			$sql.="LEFT JOIN alkis.ax_buchungsart_buchungsstelle art ON s.buchungsart = art.wert ";		
		}
		$sql.="WHERE g.blattart != 5000 ";
		if ($Bezirk!='') {
      $sql.=" AND b.schluesselgesamt='".$Bezirk."'";
		}
		if ($Blatt!='') {
			$sql.=" AND g.buchungsblattnummermitbuchstabenerweiterung='".$Blatt."'";
		}
    if ($FlurstKennz!='') {
      $sql.=" AND f.flurstueckskennzeichen='" . $FlurstKennz . "'";
    }
		if ($buchungsstelle!='') {
      $sql.=" AND s.gml_id='".$buchungsstelle."'";
    }		
		if(!$without_temporal_filter) $sql.= $this->build_temporal_filter(array('f', 's', 'g', 'gem'));
    $sql.=" ORDER BY g.bezirk,g.buchungsblattnummermitbuchstabenerweiterung,ltrim(s.laufendenummer, '~>a')::integer,f.flurstueckskennzeichen";
		#echo '<br>getBuchungenFromGrundbuch: ' . $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_assoc($ret[1])) {
      $Buchung[]=$rs;
    }
    $ret[1]=$Buchung;
    return $ret;
  }
 
  function getGemarkungListe($ganzeGemID, $GemkgID){
    $sql ="SELECT DISTINCT pp.schluesselgesamt as GemkgID, pp.gemarkungsname as Name, gem.bezeichnung as gemeindename, gem.schluesselgesamt as gemeinde ";
    $sql.="FROM alkis.ax_gemeinde AS gem, alkis.pp_gemarkung as pp ";
    $sql.="WHERE pp.gemeinde=gem.gemeinde AND pp.kreis=gem.kreis AND gem.endet IS NULL ";
		if($ganzeGemID[0]!='' OR $GemkgID[0]!=''){
			$sql.="AND (FALSE ";
			if($ganzeGemID[0]!=''){
				$sql.=" OR gem.schluesselgesamt IN ('".implode("','", $ganzeGemID)."')";
			}
			if($GemkgID[0]!=''){
				$sql.=" OR pp.schluesselgesamt IN ('".implode("','", $GemkgID)."')";
			}
			$sql.=")";
		}
    $sql.=" ORDER BY pp.gemarkungsname";
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_assoc($queryret[1])) {
      $Liste['GemkgID'][]=$rs['gemkgid'];
      $Liste['name'][]=$rs['name'];
      $Liste['gemeinde'][]=$rs['gemeinde'];
      $Liste['Bezeichnung'][]=$rs['name']." (".$rs['gemkgid'].") ".$rs['gemeindename'];
    }
    return $Liste;
  }
	
  function getGemarkungListeAll($ganzeGemID, $GemkgID){
    $sql ="
			SELECT DISTINCT 
				pp.schluesselgesamt as GemkgID, pp.gemarkungsname as Name, gem.bezeichnung as gemeindename, gem.schluesselgesamt as gemeinde, false as hist 
			FROM 
				alkis.ax_gemeinde AS gem, 
				alkis.pp_gemarkung as pp 
			WHERE 
				pp.gemeinde=gem.gemeinde AND 
				pp.kreis=gem.kreis AND 
				gem.endet IS NULL ";
		if($ganzeGemID[0]!='' OR $GemkgID[0]!=''){
			$sql.="AND (FALSE ";
			if($ganzeGemID[0]!=''){
				$sql.=" OR gem.schluesselgesamt IN ('".implode("','", $ganzeGemID)."')";
			}
			if($GemkgID[0]!=''){
				$sql.=" OR pp.schluesselgesamt IN ('".implode("','", $GemkgID)."')";
			}
			$sql.=")";
		}
		$sql .="
			UNION
			SELECT DISTINCT 
				schluesselgesamt as GemkgID, bezeichnung || ' (hist.)' as Name, '' as gemeindename, '' as gemeinde, true as hist 
			FROM 
				alkis.ax_gemarkung g
			WHERE 
				endet IS NULL AND
				NOT EXISTS (
					SELECT 
					FROM alkis.ax_flurstueck f
					WHERE f.endet IS NULL AND (f.land,f.gemarkungsnummer) = (g.land,g.gemarkungsnummer)
				)
		";
    $sql.=" ORDER BY Name";
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_assoc($queryret[1])) {
      $Liste['GemkgID'][]=$rs['gemkgid'];
      $Liste['name'][]=$rs['name'];
      $Liste['gemeinde'][]=$rs['gemeinde'];
      $Liste['Bezeichnung'][]=$rs['name']." (".$rs['gemkgid'].") ".$rs['gemeindename'];
			if ($rs['hist'] == 't') {
				$Liste['hist'][$rs['gemkgid']] = true;
			}
    }
    return $Liste;
  }	
    
  function getGemeindeListeByKreisGemeinden($Gemeinden){
    $sql ="SELECT DISTINCT g.schluesselgesamt AS id, g.bezeichnung AS name";
    $sql.=" FROM alkis.ax_gemeinde AS g WHERE 1=1";
    if(!empty($Gemeinden)){
			$sql.=" AND g.schluesselgesamt IN ('".implode("','", $Gemeinden)."')";
    }
		$sql.= $this->build_temporal_filter(array('g'));
		$sql.= $this->build_temporal_filter_fachdatenverbindung(array('g'));
    $sql.=" ORDER BY bezeichnung";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_assoc($ret[1])) {
      $GemeindeListe['ID'][]=$rs['id'];
      $GemeindeListe['name'][]=$rs['name'];
    }
    return $GemeindeListe;
  }
  
  function getFlurstuecksListe($GemID, $GemkgID, $FlurID, $FlstID, $history_mode = 'aktuell'){
		switch ($history_mode) {
			case 'aktuell' : {
				$sql = "
					SELECT 
						flurstueckskennzeichen, 
						ltrim(substring(flurstueckskennzeichen, 10, 5), '0') AS zaehler,
						ltrim(nullif(substring(flurstueckskennzeichen, 15, 4), '____'), '0') AS nenner
					FROM 
						alkis.ax_flurstueck
					WHERE 
						flurstueckskennzeichen LIKE '" . $GemkgID . str_pad($FlurID, 3, '0', STR_PAD_LEFT) . "%'" . 
						($FlstID != ''? " AND concat_ws('/', ltrim(substring(flurstueckskennzeichen, 10, 5), '0'), ltrim(nullif(substring(flurstueckskennzeichen, 15, 4), '____'), '0')) IN ('" . implode("','", $FlstID) . "')" : '') .
						$this->build_temporal_filter(array('ax_flurstueck')) . "
					ORDER BY 
						flurstueckskennzeichen";
			}break;
			case 'historisch' : {
				$sql = "
					SELECT 
						flurstueckskennzeichen,
						ltrim(substring(flurstueckskennzeichen, 10, 5), '0') AS zaehler,
						ltrim(nullif(substring(flurstueckskennzeichen, 15, 4), '____'), '0') AS nenner
					FROM 
						alkis.ax_flurstueck
					WHERE 
						flurstueckskennzeichen LIKE '" . $GemkgID . str_pad($FlurID, 3, '0', STR_PAD_LEFT) . "%'
					GROUP BY 
						flurstueckskennzeichen
					HAVING 
						bool_and(endet IS NOT NULL)
					UNION 
					SELECT 
						hf.flurstueckskennzeichen,
						ltrim(substring(hf.flurstueckskennzeichen, 10, 5), '0') AS zaehler,
						ltrim(nullif(substring(hf.flurstueckskennzeichen, 15, 4), '____'), '0') AS nenner
					FROM 
						alkis.ax_historischesflurstueckohneraumbezug hf
					WHERE 
						hf.flurstueckskennzeichen LIKE '" . $GemkgID . str_pad($FlurID, 3, '0', STR_PAD_LEFT) . "%'
					ORDER BY 
						flurstueckskennzeichen;";
			}break;
			case 'beides' : {
				$sql = "
					SELECT 
						flurstueckskennzeichen,
						ltrim(substring(flurstueckskennzeichen, 10, 5), '0') AS zaehler,
						ltrim(nullif(substring(flurstueckskennzeichen, 15, 4), '____'), '0') AS nenner
					FROM 
						alkis.ax_flurstueck
					WHERE 
						flurstueckskennzeichen LIKE '" . $GemkgID . str_pad($FlurID, 3, '0', STR_PAD_LEFT) . "%'" .
						($FlstID != ''? " AND concat_ws('/', ltrim(substring(flurstueckskennzeichen, 10, 5), '0'), ltrim(nullif(substring(flurstueckskennzeichen, 15, 4), '____'), '0')) IN ('" . implode("','", $FlstID) . "')" : '') . "
					UNION
					SELECT 
						hf.flurstueckskennzeichen,
						ltrim(substring(hf.flurstueckskennzeichen, 10, 5), '0') AS zaehler,
						ltrim(nullif(substring(hf.flurstueckskennzeichen, 15, 4), '____'), '0') AS nenner
					FROM 
						alkis.ax_historischesflurstueckohneraumbezug hf
					WHERE 
						hf.flurstueckskennzeichen LIKE '" . $GemkgID . str_pad($FlurID, 3, '0', STR_PAD_LEFT) . "%'
					ORDER BY 
						flurstueckskennzeichen";
			}
		}
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_assoc($queryret[1])) {
      $Liste['FlstID'][]=$rs['flurstueckskennzeichen'];
      $FlstNr=intval($rs['zaehler']);
      if ($rs['nenner']!='') { $FlstNr.="/".intval($rs['nenner']); }
      $Liste['FlstNr'][]=$FlstNr;
    }
    return $Liste;
  }
	
	function getFlurstueckByLatLng($latitude, $longitude) {
		$sql  = "SELECT flst.land, flst.kreis, flst.gemeindezugehoerigkeit_gemeinde gemeinde, flst.gemarkungsnummer, gemkg.bezeichnung AS gemarkungname, flst.flurnummer, flst.zaehler, flst.nenner, lpad(flst.land::text,2,'0')||lpad(flst.gemarkungsnummer::text,4,'0')||'-'||lpad(flst.flurnummer::text,3,'0')||'-'||lpad(flst.zaehler::text,5,'0')||'/'||CASE WHEN flst.nenner IS NULL THEN '000' ELSE lpad(flst.nenner::text,3,'0') END||'.00' AS flurstkennz, flst.flurstueckskennzeichen, flst.zaehler::text||CASE WHEN flst.nenner IS NULL THEN '' ELSE '/'||flst.nenner::text END AS flurstuecksnummer FROM alkis.ax_flurstueck AS flst, alkis.ax_gemarkung AS gemkg WHERE (flst.land::text||lpad(flst.gemarkungsnummer::text,4,'0'))::integer = gemkg.schluesselgesamt AND flst.gemarkungsnummer = gemkg.gemarkungsnummer AND ST_within(ST_transform(ST_GeomFromText('POINT(".$longitude." ".$latitude.")', 4326), ST_srid(flst.wkb_geometry)), flst.wkb_geometry);";
		$sql.= $this->build_temporal_filter(array('flst', 'gemkg'));
		#echo $sql.'<br>';
    $queryret = $this->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($queryret[1]);		
		return $rs;
	}
  
  function getFlurstKennzListeByGemSchlByStrSchl($GemeindeSchl, $StrassenSchl, $HausNr, $exclude_lmh_gml_ids) {
		if ($HausNr != '') {
			$adressen = explode(', ', $HausNr);
			foreach($adressen as $adresse){
				$adress = explode('-', $adresse, 3);
				$kreis = substr($adress[0], 3, 2);
				$gemeinde = substr($adress[0], 5, 3);
				$adr[] = "('" . $gemeinde . "', '" . $adress[1] . "', '" . $kreis . "', '" . $adress[2] . "')";
			}
			$adressfilter = "(l.gemeinde, l.lage, l.kreis, l.hausnummer) IN (" . implode(',', $adr) . ")";
    }
		else {
			$kreis = substr($GemeindeSchl, 3, 2);
			$gemeinde = substr($GemeindeSchl, 5, 3);
			$strassen = explode(', ', $StrassenSchl);
			foreach($strassen as $strasse){
				$adr[] = "('" . $gemeinde . "', '" . $strasse . "', '" . $kreis . "')";
			}
			$adressfilter = "(l.gemeinde, l.lage, l.kreis) IN (" . implode(', ', $adr) . ")";
		}
		if ($exclude_lmh_gml_ids != '') {
			$adressfilter .= " AND gml_id NOT IN (" . $exclude_lmh_gml_ids . ")";
		}
  	$sql = "
			SELECT 
				f.flurstueckskennzeichen as flurstkennz
			FROM  
				alkis.ax_flurstueck as f
			WHERE 
				true " .
				$this->build_temporal_filter(array('f')) . "
				AND (
					f.weistauf && ARRAY	( 
																		SELECT 
																			l.gml_id
																		FROM 
																			alkis.ax_lagebezeichnungmithausnummer l
																		WHERE " .
																			$adressfilter . 
																			$this->build_temporal_filter(array('l')) . "
																		)";
		if ($HausNr == '' AND $exclude_lmh_gml_ids == '') {
			$sql .= "
				OR f.zeigtauf && ARRAY	(
																	SELECT 
																		l.gml_id
																	FROM 
																		alkis.ax_lagebezeichnungohnehausnummer l
                                  WHERE " . 
																		$adressfilter . 
																		$this->build_temporal_filter(array('l')) . "
																)";
		}
		$sql .= ")";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $FlurstKennz[]=$rs['flurstkennz'];
      }
      $ret[1]=$FlurstKennz;
    }
    return $ret;
  } 
	
  function getFlurstueckeByGrundbuchblatt($bezirk, $blatt) {
    $sql ="set enable_seqscan = off;SELECT DISTINCT f.flurstueckskennzeichen as flurstkennz ";
		$sql.="FROM alkis.ax_buchungsblattbezirk b ";
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON g.land = b.land AND g.bezirk = b.bezirk ";
		$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON s.istbestandteilvon = g.gml_id ";
		$sql.="LEFT JOIN alkis.ax_flurstueck f ON f.istgebucht = s.gml_id OR f.gml_id = ANY(s.verweistauf) OR f.istgebucht = ANY(s.an) ";
    $sql.="WHERE f.flurstueckskennzeichen IS NOT NULL AND b.schluesselgesamt = '".$bezirk."' AND g.buchungsblattnummermitbuchstabenerweiterung = '".$blatt."'";
		$sql.= $this->build_temporal_filter(array('b', 'g', 's', 'f'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $FlurstKennz[]=$rs['flurstkennz'];
      }
      $ret[1]=$FlurstKennz;
    }
    return $FlurstKennz;
  }
  
  function getALBData($FlurstKennz, $without_temporal_filter = false, $oid_column, $eigentuemer_vcheck = NULL){		
		$sql ="
			SELECT  
				f." . $oid_column . "::text as oid, 
				f.gml_id, 
				0 as hist_alb, 
				lpad(f.flurnummer::text, 3, '0') as flurnr, 
				f.amtlicheflaeche as flaeche, 
				CASE WHEN f.abweichenderrechtszustand = 'true' THEN 'ja' ELSE 'nein' END AS abweichenderrechtszustand, 
				CASE WHEN f.zweifelhafterflurstuecksnachweis = 'true' THEN 'ja' ELSE 'nein' END as zweifelhafterflurstuecksnachweis, 
				zaehler, 
				nenner, 
				k.schluesselgesamt AS kreisid, 
				k.bezeichnung as kreisname, 
				gem.schluesselgesamt as gemkgschl, 
				gem.bezeichnung as gemkgname, 
				g.schluesselgesamt as gemeinde, 
				g.bezeichnung as gemeindename, 
				d.stelle as finanzamt, 
				d.bezeichnung AS finanzamtname, 
				zeitpunktderentstehung::date as entsteh, 
				a.kennzeichen as antragsnummer, 
				f.beginnt, 
				f.endet,
				gem.endet as gem_endet,
				g.endet as g_endet,
				" . ($eigentuemer_vcheck? $eigentuemer_vcheck['expression'] : '') . "
			FROM 
				alkis.ax_kreisregion AS k, 
				alkis.ax_gemeinde as g, 
				alkis.ax_gemarkung AS gem
				LEFT JOIN 
					alkis.ax_dienststelle as d ON d.stellenart = 1200 AND d.stelle = ANY(gem.istamtsbezirkvon_stelle),
				alkis.ax_flurstueck AS f 				
				LEFT JOIN
					alkis.aa_antrag a ON a.identifier = any(f.zeigtaufexternes_uri)
			WHERE 
				f.gemarkungsnummer=gem.gemarkungsnummer AND 
				f.land = gem.land AND 
				f.gemeindezugehoerigkeit_kreis = g.kreis AND 
				f.gemeindezugehoerigkeit_gemeinde = g.gemeinde AND 
				f.gemeindezugehoerigkeit_kreis = k.kreis AND 
				f.flurstueckskennzeichen='" . $FlurstKennz . "'";
		if (!$without_temporal_filter) {
			$sql.= $this->build_temporal_filter(array('k', 'g', 'gem', 'f', 'd'));		# a nicht, da es auch beendet sein kann
		}
		else {
			$sql.= " 
				UNION 
				SELECT  
					NULL, 
					f.gml_id, 
					1 as hist_alb, 
					lpad(f.flurnummer::text, 3, '0') as flurnr, 
					f.amtlicheflaeche as flaeche, 
					CASE WHEN f.abweichenderrechtszustand = 'true' THEN 'ja' ELSE 'nein' END AS abweichenderrechtszustand, 
					CASE WHEN f.zweifelhafterflurstuecksnachweis = 'true' THEN 'ja' ELSE 'nein' END as zweifelhafterflurstuecksnachweis, 
					zaehler, 
					nenner, 
					'0' AS kreisid, 
					'' as kreisname, 
					gem.schluesselgesamt as gemkgschl, 
					gem.bezeichnung as gemkgname, 
					g.schluesselgesamt as gemeinde, 
					g.bezeichnung as gemeindename, 
					'' as finanzamt, 
					'' AS finanzamtname, 
					zeitpunktderentstehung::date as entsteh, 
					'' as antragsnummer, 
					f.beginnt, 
					f.endet,
					gem.endet as gem_endet,
					g.endet as g_endet,
					" . ($eigentuemer_vcheck? "true as " . $eigentuemer_vcheck['attribute'] : '') . "
				FROM 
					alkis.ax_historischesflurstueckohneraumbezug as f 
					LEFT JOIN alkis.ax_gemarkung AS gem ON f.gemarkungsnummer=gem.gemarkungsnummer AND f.land = gem.land 
					LEFT JOIN alkis.pp_gemarkung ppg ON gem.land = ppg.land AND gem.gemarkungsnummer = ppg.gemarkung 
					LEFT JOIN alkis.ax_gemeinde g ON f.gemeindezugehoerigkeit_gemeinde=g.gemeinde AND ppg.kreis = g.kreis 
				WHERE 
					f.flurstueckskennzeichen = '" . $FlurstKennz . "'
				order by endet DESC, gem_endet DESC, g_endet DESC";		# damit immer die jüngste Version eines Flurstücks gefunden wird
		}		
    #echo $sql.'<br><br>';
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=0;
      $ret[1]=$queryret[1];
    }
    else{
			$rs=pg_fetch_assoc($queryret[1]);
			$ret[0]=0;
      $ret[1]=$rs;
    }
    return $ret;
  }
 
  function getFlurstuecksKennzByGemeindeIDs($GemeindenStelle, $FlurstKennz){
		$sql ="SELECT f.flurstueckskennzeichen as flurstkennz FROM alkis.ax_historischesflurstueckohneraumbezug AS f, alkis.pp_gemarkung AS g ";
		$sql.="WHERE f.gemarkungsnummer=g.gemarkung ";
		$sql.="AND f.flurstueckskennzeichen IN ('".implode("','", $FlurstKennz)."') ";
		if($GemeindenStelle != NULL){
			$sql.="AND (FALSE";
			if($GemeindenStelle['ganze_gemeinde'] != NULL)$sql.=" OR g.land::text||g.regierungsbezirk||g.kreis||g.gemeinde IN ('".implode("','", array_keys($GemeindenStelle['ganze_gemeinde']))."')";
			if($GemeindenStelle['ganze_gemarkung'] != NULL)$sql.=" OR f.land||f.gemarkungsnummer IN ('".implode("','", array_keys($GemeindenStelle['ganze_gemarkung']))."')";			
			if (count($GemeindenStelle['eingeschr_gemarkung']) > 0) {
				foreach ($GemeindenStelle['ganze_flur'] as $eingeschr_gemkg_id => $ganze_fluren) {
					$sql.=" OR (f.land||f.gemarkungsnummer = '" . $eingeschr_gemkg_id . "' AND flurnummer IN (" . implode(',', $ganze_fluren) . "))";
				}
				foreach ($GemeindenStelle['eingeschr_flur'] as $eingeschr_gemkg_id => $eingeschr_fluren) {
					foreach ($eingeschr_fluren as $eingeschr_flur => $flurstuecke) {
						$sql.=" OR (f.land||f.gemarkungsnummer = '" . $eingeschr_gemkg_id . "' AND flurnummer = " . $eingeschr_flur . "  AND f.zaehler || coalesce('/' || f.nenner, '') IN ('" . implode("','", $flurstuecke) . "'))";
					}
				}
			}			
			$sql .= ") ";
		}		
		$sql.="UNION ";
		$sql.="SELECT f.flurstueckskennzeichen as flurstkennz FROM alkis.ax_flurstueck AS f ";
		$sql.="WHERE f.flurstueckskennzeichen IN ('".implode("','", $FlurstKennz)."') ";
		if($GemeindenStelle != NULL){
			$sql.="AND (FALSE";
			if($GemeindenStelle['ganze_gemeinde'] != NULL)$sql.=" OR f.land||f.gemeindezugehoerigkeit_regierungsbezirk||f.gemeindezugehoerigkeit_kreis||f.gemeindezugehoerigkeit_gemeinde IN ('".implode("','", array_keys($GemeindenStelle['ganze_gemeinde']))."')";
			if($GemeindenStelle['ganze_gemarkung'] != NULL)$sql.=" OR f.land||f.gemarkungsnummer IN ('".implode("','", array_keys($GemeindenStelle['ganze_gemarkung']))."')";
			if (count($GemeindenStelle['eingeschr_gemarkung']) > 0) {
				foreach ($GemeindenStelle['ganze_flur'] as $eingeschr_gemkg_id => $ganze_fluren) {
					$sql.=" OR (f.land||f.gemarkungsnummer = '" . $eingeschr_gemkg_id . "' AND flurnummer IN (" . implode(',', $ganze_fluren) . "))";
				}
				foreach ($GemeindenStelle['eingeschr_flur'] as $eingeschr_gemkg_id => $eingeschr_fluren) {
					foreach ($eingeschr_fluren as $eingeschr_flur => $flurstuecke) {
						$sql.=" OR (f.land||f.gemarkungsnummer = '" . $eingeschr_gemkg_id . "' AND flurnummer = " . $eingeschr_flur . "  AND f.zaehler || coalesce('/' || f.nenner, '') IN ('" . implode("','", $flurstuecke) . "'))";
					}
				}
			}	
			$sql .= ") ORDER BY flurstkennz";
		}
    $this->debug->write("<p>postgresql.php getFlurstuecksKennzByGemeindeIDs() Abfragen erlaubten Flurstückskennzeichen nach Gemeindeids:<br>".$sql,4);
		#echo $sql;
    $query=pg_query($sql);
    if ($query==0) {
      $ret[0]=1; $ret[1]="Fehler bei der Abfrage der zur Anzeige erlaubten Flurstücke";
      $this->debug->write("<br>Abbruch in postgresql.php getFlurstuecksKennzByGemeindeIDs Zeile: ".__LINE__."<br>sql: ".$sql,4);
      return $ret;
    }
    while($rs=pg_fetch_assoc($query)) {
      $ret[1][]=$rs["flurstkennz"];
    }
    return $ret;
  }
	  
  function getStrassen($FlurstKennz) {
    $sql ="SELECT DISTINCT g.schluesselgesamt as gemeinde, g.bezeichnung as gemeindename, l.lage as strasse, s.bezeichnung as strassenname ";
    $sql.="FROM alkis.ax_gemeinde as g, alkis.ax_flurstueck as f ";
    $sql.="JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(f.weistauf) ";
    $sql.="LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND s.lage = l.lage ";
    $sql.="WHERE g.gemeinde = l.gemeinde AND g.kreis = l.kreis AND f.flurstueckskennzeichen = '" . $FlurstKennz . "'";
		$sql.= $this->build_temporal_filter(array('g', 'f', 'l', 's'));
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
		$Strassen = [];
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      while($rs=pg_fetch_assoc($queryret[1])) {
        $Strassen[]=$rs;
      }
      $ret[1]=$Strassen;
    }
    return $ret;
  }
    
	function getStrNameByID($GemID,$StrID) {
    $sql ="SELECT bezeichnung FROM alkis.ax_lagebezeichnungkatalogeintrag WHERE schluesselgesamt = '".$GemID.str_pad($StrID, 5, '0', STR_PAD_LEFT)."'";
		$sql.= $this->build_temporal_filter(array('ax_lagebezeichnungkatalogeintrag'));
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_row($queryret[1]);
      $StrID=$rs[0];
      $ret[1]=$StrID;
    }
    return $ret;
  }
  
  function getHausNummern($FlurstKennz,$Strasse) {
    # Abfragen der Hausnummern zu den jeweiligen Strassen
    $sql ="SELECT DISTINCT ".HAUSNUMMER_TYPE."(l.hausnummer) AS hausnr ";
    $sql.="FROM alkis.ax_flurstueck as f ";
    $sql.="JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(f.weistauf) ";
    $sql.="WHERE l.lage='".$Strasse."' AND f.flurstueckskennzeichen = '" . $FlurstKennz . "'";
		$sql.= $this->build_temporal_filter(array('f', 'l'));
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      while($rs=pg_fetch_row($queryret[1])) {
        $HausNr[]=$rs[0];
      }
      $ret[1]=$HausNr;
    }
    return $ret;
  }

  function getLage($FlurstKennz) {
    # liefert die Lage des Flurstückes
    $sql = "SELECT distinct l.unverschluesselt, s.bezeichnung, ' ('||s.lage||')' as lage ";
		$sql.= "FROM alkis.ax_flurstueck as f ";
		$sql.= "JOIN alkis.ax_lagebezeichnungohnehausnummer l ON l.gml_id = ANY(f.zeigtauf)  ";
		$sql.= "LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND l.lage=s.lage ";
		$sql.= "WHERE f.flurstueckskennzeichen = '" . $FlurstKennz . "'";
		$sql.= $this->build_temporal_filter(array('f', 'l', 's'));
		#echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      if (pg_num_rows($queryret[1])>0) {
        while($rs=pg_fetch_assoc($queryret[1])) {
          $Lage[]= $rs['unverschluesselt'].$rs['bezeichnung'].$rs['lage'];
        }
      }
      $ret[1]=$Lage;
    }
    return $ret;
  }
	
	function getVersionen($table, $gml_ids, $start){
		$versionen = array();
		if($gml_ids != NULL){
			$sql = "SELECT beginnt, endet, value as anlass, '".$table."' as table ";
			$sql.= "FROM alkis.".$table." LEFT JOIN alkis.aa_anlassart ON id = anlass[1] ";
			$sql.= "WHERE gml_id IN ('".implode("','", $gml_ids)."') ";
			if($start)$sql.= "AND beginnt > '".$start."' ";
			$sql.= "ORDER BY beginnt";
			#echo $sql.'<br>';
			$queryret=$this->execSQL($sql, 4, 0);
			while($rs=pg_fetch_assoc($queryret[1])) {
				$versionen[]=$rs;
			}
		}
		return $versionen;
	}
	
	function getAnschriften($gml_ids, $without_temporal_filter = false){
		$sql = "SELECT DISTINCT ON (strasse, hausnummer, postleitzahlpostzustellung, ort_post, ortsteil, bestimmungsland) anschrift.gml_id as anschrift_gml_id, anschrift.strasse, anschrift.hausnummer, anschrift.postleitzahlpostzustellung, anschrift.ort_post, 'OT '||anschrift.ortsteil as ortsteil, anschrift.bestimmungsland ";
		$sql.= "FROM alkis.ax_anschrift as anschrift ";
		$sql.= "WHERE gml_id IN ('".implode("','", $gml_ids)."')";
		if(!$without_temporal_filter)$sql.= $this->build_temporal_filter(array('anschrift'));
		$ret=$this->execSQL($sql, 4, 0);
		while($rs=pg_fetch_assoc($ret[1])){
			$anschriften[] = $rs;
		}
		return $anschriften;
	}
  
  function getEigentuemerliste($FlurstKennz,$Bezirk,$Blatt,$BVNR, $without_temporal_filter = false) {
    $sql = "SELECT distinct coalesce(n.laufendenummernachdin1421, '0') as order1, coalesce(bestehtausrechtsverhaeltnissenzu, '0') as order2, bestehtausrechtsverhaeltnissenzu, CASE WHEN n.beschriebderrechtsgemeinschaft is null and n.artderrechtsgemeinschaft is null THEN n.laufendenummernachdin1421 ELSE NULL END AS namensnr, n.gml_id as n_gml_id, p.gml_id, p.nachnameoderfirma, p.vorname, p.akademischergrad, p.namensbestandteil, p.geburtsname, p.geburtsdatum::date, p.sterbedatum::date, array_to_string(p.hat, ',') as hat, anschrift.gml_id as anschrift_gml_id, anschrift.strasse, anschrift.hausnummer, anschrift.postleitzahlpostzustellung, anschrift.ort_post, 'OT '||anschrift.ortsteil as ortsteil, anschrift.bestimmungsland, w.beschreibung as Art, n.zaehler||'/'||n.nenner as anteil, coalesce(NULLIF(n.beschriebderrechtsgemeinschaft, ''),adrg.beschreibung) as zusatz_eigentuemer ";
		$sql.= "FROM alkis.ax_buchungsstelle s ";
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		$sql.="LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk ";
		$sql.= "LEFT JOIN alkis.ax_namensnummer n ON n.istbestandteilvon = g.gml_id ";
		$sql.= "LEFT JOIN alkis.ax_artderrechtsgemeinschaft_namensnummer adrg ON n.artderrechtsgemeinschaft = adrg.wert ";
		$sql.= "LEFT JOIN alkis.ax_eigentuemerart_namensnummer w ON w.wert = n.eigentuemerart ";
		$sql.= "LEFT JOIN alkis.ax_person p ON n.benennt = p.gml_id ";
		$sql.= "LEFT JOIN alkis.ax_anschrift anschrift ON anschrift.gml_id = p.hat[1] ";		# da die meisten Eigentümer nur eine Anschrift haben, diese gleiche in dieser Abfrage mit abfragen
		if(!$without_temporal_filter)$sql.= $this->build_temporal_filter(array('anschrift'));
		$sql.= " WHERE 1=1"; 
    if ($Bezirk!="") {
      $sql.=" AND b.schluesselgesamt='".$Bezirk."'";
    }
    if ($Blatt!="") {
      $sql.=" AND g.buchungsblattnummermitbuchstabenerweiterung='".$Blatt."'";
    }
    if ($BVNR!="") {
      $sql.=" AND s.laufendenummer='".$BVNR."'";
    }
		if(!$without_temporal_filter)$sql.= $this->build_temporal_filter(array('s', 'g', 'b', 'n', 'p'));
    $sql.= " ORDER BY order1, order2;";
    #echo 'getEigentuemerliste: ' . $sql.'<br><br>';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0] OR pg_num_rows($ret[1])==0) { return; }
		$wurzel = new eigentuemer($Grundbuch,NULL, $this);
		$Eigentuemerliste['wurzel'] = $wurzel;
    while($rs=pg_fetch_assoc($ret[1])){
      $Grundbuch = new grundbuch("","",$this->debug);
      
			$newparts = array();
      $parts = explode('.', $rs['namensnr']);
			for($i = 0; $i < count($parts); $i++){
				$parts[$i] = intval($parts[$i]);
				if($parts[$i] != 0){
					$newparts[] = $parts[$i];
				}
			}
			$rs['namensnr'] = implode('.', $newparts);
      $Eigentuemer = new eigentuemer($Grundbuch,$rs['namensnr'], $this);

			$Eigentuemer->gml_id = $rs['gml_id'];
      $Eigentuemer->lfd_nr=$rs['lfd_nr_name'];
      $Eigentuemer->nachnameoderfirma = $rs['nachnameoderfirma'];
      $Eigentuemer->vorname  = $rs['vorname']; 
			$Eigentuemer->namensbestandteil = $rs['namensbestandteil'];
			$Eigentuemer->akademischergrad = $rs['akademischergrad'];
      $Eigentuemer->geburtsdatum = $rs['geburtsdatum'];
			$Eigentuemer->sterbedatum = $rs['sterbedatum'];
			$Eigentuemer->geburtsname = $rs['geburtsname'];
			
			$anschriften_gml_ids = explode(',', $rs['hat']);
			if(count($anschriften_gml_ids) > 1){
				$Eigentuemer->anschriften = $this->getAnschriften($anschriften_gml_ids, $without_temporal_filter);
			}
			else{
				$Eigentuemer->anschriften[0]['postleitzahlpostzustellung'] = $rs['postleitzahlpostzustellung'];
				$Eigentuemer->anschriften[0]['ort_post'] = $rs['ort_post'];
				$Eigentuemer->anschriften[0]['ortsteil'] = $rs['ortsteil'];
				$Eigentuemer->anschriften[0]['bestimmungsland'] = $rs['bestimmungsland'];
				$Eigentuemer->anschriften[0]['strasse'] = $rs['strasse'];
				$Eigentuemer->anschriften[0]['hausnummer'] = $rs['hausnummer'];
			}
			
      $Eigentuemer->Anteil=$rs['anteil'];
			$Eigentuemer->anschrift_gml_id=$rs['anschrift_gml_id'];
			$Eigentuemer->zusatz_eigentuemer=$rs['zusatz_eigentuemer'];
			$Eigentuemer->n_gml_id=$rs['n_gml_id'];
			$Eigentuemer->bestehtausrechtsverhaeltnissenzu=$rs['bestehtausrechtsverhaeltnissenzu'];
      $Eigentuemerliste[$rs['n_gml_id']]=$Eigentuemer;
			#if($this->listendarstellung OR $rs['namensnr'] != '')	Bugfix 2.12.9
			$this->writeRechtsverhaeltnisChildren($rs['n_gml_id'], $Eigentuemerliste);
    }
    $retListe[0]=0;
    $retListe[1]=$Eigentuemerliste;
    return $retListe;
  }
	
	function writeRechtsverhaeltnisChildren($gml_id, &$Eigentuemerliste){
		# Diese Funktion hängt an jedes Rechtsverhältnis ein Array "children" mit den zugehörigen Kindknoten (Eigentümer bzw. Unter-Rechtsverhältnisse) an
		# Ausgehend vom Wurzelknoten (Erstes Element aus der Eigentuemerliste) kann man damit dann den Rechtsverhältnisbaum aufbauen
		$eigentuemer = $Eigentuemerliste[$gml_id];
		$rechtsverhaeltnis = $eigentuemer->bestehtausrechtsverhaeltnissenzu;
		if($rechtsverhaeltnis != ''){
			if($rechtsverhaeltnis != '-'){
				if($Eigentuemerliste[$rechtsverhaeltnis] == NULL){
					# Wenn das Rechtsverhältnis eines Eigentümers noch nicht im Array $Eigentuemerliste vorhanden ist, also erst später dem Array hinzugefügt wird,
					# wird davon ausgegangen, dass alle Rechtsverhältnisse unter den Eigentümern angezeigt werden sollen. In diesem Fall werden alle Eigentümer und
					# Rechtsverhältnisse der Wurzel zugeordnet. Dadurch erfolgt keine Baumdarstellung der Eigentümer, sondern eine alternative Darstellung als einfache Liste.
					$this->listendarstellung = true;
					$Eigentuemerliste['wurzel']->children[] = $gml_id;
					$eigentuemer->bestehtausrechtsverhaeltnissenzu = '-';
				}
				else{
					$Eigentuemerliste[$rechtsverhaeltnis]->children[] = $gml_id;
					$eigentuemer->bestehtausrechtsverhaeltnissenzu = '-';
					$this->writeRechtsverhaeltnisChildren($rechtsverhaeltnis, $Eigentuemerliste);
				}
			}
		}
		else{
			$Eigentuemerliste['wurzel']->children[] = $gml_id;
			$eigentuemer->bestehtausrechtsverhaeltnissenzu = '-';
		}
	}
  
  function getNamen($formvars, $ganze_gemkg_ids, $eingeschr_gemkg_ids, $ganze_flur_ids, $eingeschr_flur_ids){
		if(!$formvars['exakt']){
			$n1 = '%'.$formvars['name1'].'%';
			$n2 = '%'.$formvars['name2'].'%';
		}
		else{
			$n1 = $formvars['name1'];
			$n2 = $formvars['name2'];
		}
		$n3 = '%'.$formvars['name3'].'%';
		$n4 = '%'.$formvars['name4'].'%';
		$n5 = '%'.$formvars['name5'].'%';
		$n6 = '%'.$formvars['name6'].'%';
		$n7 = '%'.$formvars['name7'].'%';
		$n8 = '%'.$formvars['name8'].'%';
		$n9 = '%'.$formvars['name9'].'%';
		$n10 = '%'.$formvars['name10'].'%';
		$gml_id = $formvars['gml_id'];
		$bezirk = $formvars['bezirk'];
		$blatt = $formvars['blatt'];		
		$gemkgschl = $formvars['GemkgID'];
		$flur = $formvars['FlurID'];
		$limitAnzahl = $formvars['anzahl'];
		$limitStart = $formvars['offset'];
		$caseSensitive = $formvars['caseSensitive'];
		$order = $formvars['order'];
		if($order == '')$order = 'nachnameoderfirma, vorname';
			
    $sql = "
			set enable_seqscan = off;set enable_mergejoin = off;set enable_hashjoin = off;
			SELECT distinct 
				p.gml_id, 
				p.nachnameoderfirma, 
				p.vorname, 
				p.namensbestandteil, 
				p.akademischergrad, 
				p.geburtsname, 
				p.geburtsdatum, 
				p.sterbedatum,
				array_to_string(p.hat, ',') as hat, 
				anschrift.strasse, 
				anschrift.hausnummer, 
				anschrift.postleitzahlpostzustellung, 
				anschrift.ort_post, 'OT '||anschrift.ortsteil as ortsteil, 
				anschrift.bestimmungsland, 
				g.buchungsblattnummermitbuchstabenerweiterung as blatt, 
				b.schluesselgesamt as bezirk
			FROM 
				alkis.ax_person p 
				LEFT JOIN alkis.ax_anschrift anschrift ON anschrift.gml_id = p.hat[1] -- da die meisten Eigentümer nur eine Anschrift haben, diese gleiche in dieser Abfrage mit abfragen
				LEFT JOIN alkis.ax_namensnummer n ON n.benennt = p.gml_id 
				LEFT JOIN alkis.ax_eigentuemerart_namensnummer w ON w.wert = n.eigentuemerart 
				LEFT JOIN alkis.ax_buchungsblatt g ON n.istbestandteilvon = g.gml_id 
				LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk 
				LEFT JOIN alkis.ax_buchungsstelle s ON s.istbestandteilvon = g.gml_id 
				LEFT JOIN alkis.ax_flurstueck f ON f.istgebucht = s.gml_id OR f.gml_id = ANY(s.verweistauf) OR f.istgebucht = ANY(s.an) 
			WHERE 1=1 ";
    if($n1 != '%%' AND $n1 != '')$sql.=" AND lower(nachnameoderfirma) LIKE lower('".$n1."') ";
		if($n2 != '%%' AND $n2 != '')$sql.=" AND lower(vorname) LIKE lower('".$n2."') ";
		if($n3 != '%%')$sql.=" AND lower(geburtsname) LIKE lower('".$n3."') ";
		if($n4 != '%%')$sql.=" AND geburtsdatum = '".$n4."' ";
		if($n5 != '%%')$sql.=" AND lower(strasse) LIKE lower('".$n5."') ";
		if($n6 != '%%')$sql.=" AND lower(replace(hausnummer, ' ', '')) LIKE lower(replace('".$n6."', ' ', '')) ";
		if($n7 != '%%')$sql.=" AND lower(postleitzahlpostzustellung) LIKE lower('".$n7."') ";
		if($n8 != '%%')$sql.=" AND lower(ort_post) LIKE lower('".$n8."') ";
		if($n9 != '%%')$sql.=" AND lower(namensbestandteil) LIKE lower('".$n9."') ";
		if($n10 != '%%')$sql.=" AND lower(akademischergrad) LIKE lower('".$n10."') ";
		if($gml_id != '')$sql.=" AND p.gml_id = '".$gml_id."'";

    if($bezirk!='') {
      $sql.=" AND b.schluesselgesamt = '".$bezirk."'";
    }
    if($blatt != ''){
      $sql.=" AND g.buchungsblattnummermitbuchstabenerweiterung= '".$blatt."'";
    }   
    if ($gemkgschl>0) {
      $sql.=" AND f.land||f.gemarkungsnummer = '".$gemkgschl."'";
    }    
    if ($flur>0) {
      $sql.=" AND f.flurnummer = ".$flur;
    }
		if (value_of($formvars, 'newpathwkt') != ''){
			# Suche im Suchpolygon
			$sql .=' AND st_intersects(f.wkb_geometry, (st_transform(st_geomfromtext(\'' . $formvars['newpathwkt'] . '\', ' . $formvars['user_epsg'] . '), ' . EPSGCODE_ALKIS . ')))';
		}
		if($ganze_gemkg_ids[0] != '' OR count_or_0($eingeschr_gemkg_ids) > 0){
			$sql.=" AND (FALSE ";
			if($ganze_gemkg_ids[0] != ''){
				$sql.="OR f.land||f.gemarkungsnummer IN ('".implode("','", $ganze_gemkg_ids)."')";
			}
			if (count($eingeschr_gemkg_ids) > 0) {
				foreach ($ganze_flur_ids as $eingeschr_gemkg_id => $ganze_fluren) {
					$sql.=" OR (f.land||f.gemarkungsnummer = '" . $eingeschr_gemkg_id . "' AND flurnummer IN (" . implode(',', $ganze_fluren) . "))";
				}
				foreach ($eingeschr_flur_ids as $eingeschr_gemkg_id => $eingeschr_fluren) {
					foreach ($eingeschr_fluren as $eingeschr_flur => $flurstuecke) {
						$sql.=" OR (f.land||f.gemarkungsnummer = '" . $eingeschr_gemkg_id . "' AND flurnummer = " . $eingeschr_flur . "  AND f.zaehler || coalesce('/' || f.nenner, '') IN ('" . implode("','", $flurstuecke) . "'))";
					}
				}
			}
			$sql.=")";
		}
		$sql.= $this->build_temporal_filter(array('f', 'p', 'anschrift', 'n', 'g', 'b'));
		if ($formvars['alleiniger_eigentuemer']) {
			$sql.= "
				AND NOT EXISTS (
					SELECT
					FROM 
						alkis.ax_buchungsstelle s2 
						JOIN alkis.ax_buchungsblatt g2 ON s2.istbestandteilvon = g2.gml_id 
						JOIN alkis.ax_namensnummer n2 ON n2.istbestandteilvon = g2.gml_id 
						JOIN alkis.ax_person p2 ON n2.benennt = p2.gml_id AND p2.gml_id != p.gml_id
					WHERE 
						(f.istgebucht = s2.gml_id OR f.gml_id = ANY(s2.verweistauf) OR f.istgebucht = ANY(s2.an)) " .
						$this->build_temporal_filter(array('p2', 'n2', 'g2', 's2')) . "
				)";
		}
    $sql .= " ORDER BY ". $order;
    if ($limitStart!='' OR $limitAnzahl != '') {
      $sql .= " LIMIT ";
      if ($limitStart!='' AND $limitAnzahl != '') {
        $sql .= $limitAnzahl . " OFFSET " . $limitStart;
      }
      if ($limitStart!='' AND $limitAnzahl=='') {
        $sql .= " ALL OFFSET " . $limitStart;
      }
      if ($limitStart == '' AND $limitAnzahl != '') {
        $sql .= $limitAnzahl;
      }
    }
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	$i = 0;
      while($rs=pg_fetch_assoc($ret[1])) {
      	$namen[$i]=$rs;
	      $namen[$i]['name1'] = $rs['nachnameoderfirma'];
	      if($rs['vorname'] != '')$namen[$i]['name1'] .= ', '.$rs['vorname']; 
				if($rs['namensbestandteil'] != '')$namen[$i]['name1'] .= ', '.$rs['namensbestandteil']; 
				if($rs['akademischergrad'] != '')$namen[$i]['name1'] .= ', '.$rs['akademischergrad']; 				
	      $namen[$i]['name2'] = $rs['geburtsdatum'];
				if ($rs['geburtsname'] != '') {
					$namen[$i]['name2'] .= ' geb. '.$rs['geburtsname'];
				}
				if ($rs['sterbedatum'] != '') {
					$namen[$i]['name2'] .= ' &#10015;'.$rs['sterbedatum'];
				}
				
				$anschriften_gml_ids = explode(',', $rs['hat']);
				$anschriften = array();
				if(count($anschriften_gml_ids) > 1){
					$anschriften = $this->getAnschriften($anschriften_gml_ids, $without_temporal_filter);
				}
				else{
					$anschriften[0]['postleitzahlpostzustellung'] = $rs['postleitzahlpostzustellung'];
					$anschriften[0]['ort_post'] = $rs['ort_post'];
					$anschriften[0]['ortsteil'] = $rs['ortsteil'];
					$anschriften[0]['bestimmungsland'] = $rs['bestimmungsland'];
					$anschriften[0]['strasse'] = $rs['strasse'];
					$anschriften[0]['hausnummer'] = $rs['hausnummer'];
				}
				foreach($anschriften as $anschrift){
					$namen[$i]['name3'] .= $anschrift['strasse'].' '.$anschrift['hausnummer'].'<br>';
					$namen[$i]['name4'] .= $anschrift['postleitzahlpostzustellung'].' '.$anschrift['ort_post'].' '.$anschrift['ortsteil'].' '.$anschrift['bestimmungsland'].'<br>';
				}
        $i++;
      }
      $ret[1]=$namen;
    }
    return $ret;
  }

  function getForstamt($FlurstKennz) {
    $sql ="SELECT distinct d.stelle as schluessel, d.bezeichnung as name FROM alkis.ax_dienststelle as d, alkis.ax_flurstueck as f";
    $sql.=" WHERE d.stellenart = 1400 AND d.stelle = ANY(f.zustaendigestelle_stelle) AND f.flurstueckskennzeichen = '" . $FlurstKennz . "'";
		$sql.= $this->build_temporal_filter(array('d', 'f', 'd'));
		#echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      if (pg_num_rows($queryret[1])>0) {
        $rs=pg_fetch_assoc($queryret[1]);
        $Forstamt=$rs;
      }
      else {
        $Forstamt['name']='ungebucht';
      }
      $ret[1]=$Forstamt;
    }
    return $ret;
  }
	
	function getAmtsgerichtby($flurstkennz, $bezirke){
		$sql ="
			SELECT distinct 
				a.bezeichnung as name,
				a.stelle as schluessel
			FROM
				alkis.ax_buchungsblattbezirk b,
				alkis.ax_dienststelle a
			WHERE
				b.gehoertzu_land=a.land AND
				b.gehoertzu_stelle=a.stelle AND
				a.stellenart=1000 AND
				b.schluesselgesamt IN ('" . implode("', '", array_map(function($e){return $e['schluessel'];},	$bezirke)) . "')
				" . $this->build_temporal_filter(array('b', 'a')) . "
		";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
			while ($rs=pg_fetch_assoc($queryret[1])) {
				$ret[1][] = $rs;
			}
    }
    return $ret;
	}
  
  function getGemarkungName($GemkgSchl) {
    $sql ="SELECT bezeichnung as gemkgname FROM alkis.ax_gemarkung WHERE schluesselgesamt = '".$GemkgSchl."'";
		$sql.= $this->build_temporal_filter(array('ax_gemarkung'));
    $this->debug->write("<p>postgres.sql getGemarkungName Abfragen des Gemarkungsnamen:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_assoc($queryret[1]);
      $ret[1]=$rs['gemkgname'];
    }
    return $ret;
  }
	
	function getGrundbuchblattliste($bezirk){
		$sql = "SELECT buchungsblattnummermitbuchstabenerweiterung as blatt FROM alkis.ax_buchungsblatt WHERE land||bezirk = '".$bezirk."' AND (blattart = 1000 OR blattart = 2000 OR blattart = 3000) ";
		$sql.= $this->build_temporal_filter(array('ax_buchungsblatt'));
		$sql.= " ORDER BY rtrim(ltrim(buchungsblattnummermitbuchstabenerweiterung,'PF0'),'ABCDEFGHIJKLMNOPQRSTUVWXYZ')::integer";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_assoc($ret[1])){
      	$liste['blatt'][]=$rs['blatt'];
    	}
    }
    return $liste;
	}
	
	function getGrundbuchblattlisteByGemkgIDs($bezirk, $ganze_gemkg_ids, $eingeschr_gemkg_ids, $ganze_flur_ids, $eingeschr_flur_ids){
		$sql = "SELECT DISTINCT buchungsblattnummermitbuchstabenerweiterung as blatt, rtrim(ltrim(buchungsblattnummermitbuchstabenerweiterung,'PF0'),'ABCDEFGHIJKLMNOPQRSTUVWXYZ')::integer ";
		$sql.="FROM alkis.ax_flurstueck f ";
		$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON f.istgebucht = s.gml_id OR f.istgebucht = ANY(s.an) OR f.gml_id = ANY(s.verweistauf) ";		
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		$sql.="WHERE g.land||g.bezirk = '".$bezirk."' AND (blattart = 1000 OR blattart = 2000 OR blattart = 3000) AND (FALSE ";		
		if($ganze_gemkg_ids[0] != ''){
			$sql.="OR f.land||f.gemarkungsnummer IN ('".implode("','", $ganze_gemkg_ids)."')";
		}
		if (count($eingeschr_gemkg_ids) > 0) {
			foreach ($ganze_flur_ids as $eingeschr_gemkg_id => $ganze_fluren) {
				$sql.=" OR (f.land||f.gemarkungsnummer = '" . $eingeschr_gemkg_id . "' AND flurnummer IN (" . implode(',', $ganze_fluren) . "))";
			}
			foreach ($eingeschr_flur_ids as $eingeschr_gemkg_id => $eingeschr_fluren) {
				foreach ($eingeschr_fluren as $eingeschr_flur => $flurstuecke) {
					$sql.=" OR (f.land||f.gemarkungsnummer = '" . $eingeschr_gemkg_id . "' AND flurnummer = " . $eingeschr_flur . "  AND f.zaehler || coalesce('/' || f.nenner, '') IN ('" . implode("','", $flurstuecke) . "'))";
				}
			}
		}
		$sql.= ")";
		$sql.= $this->build_temporal_filter(array('f', 's', 'g'));
		$sql.= " ORDER BY rtrim(ltrim(buchungsblattnummermitbuchstabenerweiterung,'PF0'),'ABCDEFGHIJKLMNOPQRSTUVWXYZ')::integer";
		#echo $sql;
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_assoc($ret[1])){
      	$liste['blatt'][]=$rs['blatt'];
    	}
    }
    return $liste;
	}
  
  function getGrundbuchbezirksliste(){
  	$sql ="SELECT schluesselgesamt as grundbuchbezschl, bezeichnung FROM alkis.ax_buchungsblattbezirk WHERE 1=1";
		$sql.= $this->build_temporal_filter(array('ax_buchungsblattbezirk'));
		$sql.= $this->build_temporal_filter_fachdatenverbindung(array('ax_buchungsblattbezirk'));
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_assoc($ret[1])){
      	$liste['schluessel'][]=$rs['grundbuchbezschl'];
      	$liste['bezeichnung'][]=$rs['bezeichnung'];
      	$liste['beides'][]=$rs['bezeichnung'].' ('.$rs['grundbuchbezschl'].')';
    	}
    }
    return $liste;
  }
  
	
  function getGrundbuchbezirkslisteByGemkgIDs($ganze_gemkg_ids, $eingeschr_gemkg_ids) {
		$sql ="set enable_mergejoin = off;SELECT DISTINCT b.schluesselgesamt as grundbuchbezschl, b.bezeichnung ";
		$sql.="FROM alkis.ax_flurstueck f ";	
		$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON f.istgebucht = s.gml_id ";
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		$sql.="LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk ";
		$sql.="WHERE (g.blattart = 1000 OR g.blattart = 2000 OR g.blattart = 3000) AND (FALSE ";
		if($ganze_gemkg_ids[0] != ''){
			$sql.="OR f.land||f.gemarkungsnummer IN ('".implode("','", $ganze_gemkg_ids)."')";
		}
		if(count($eingeschr_gemkg_ids) > 0){
			foreach($eingeschr_gemkg_ids as $eingeschr_gemkg_id => $fluren){
				$sql.=" OR (f.land||f.gemarkungsnummer = '".$eingeschr_gemkg_id."' AND flurnummer IN (".implode(',', $fluren)."))";
			}
		}
		$sql.= ")";
		$sql.= $this->build_temporal_filter(array('f', 's', 'g', 'b'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_assoc($ret[1])){
      	$liste['schluessel'][]=$rs['grundbuchbezschl'];
      	$liste['bezeichnung'][]=$rs['bezeichnung'];
      	$liste['beides'][]=$rs['bezeichnung'].' ('.$rs['grundbuchbezschl'].')';
    	}
    }
    return $liste;
  }
    
  function getGrundbuchbezirke($FlurstKennz, $hist_alb = false) {
		$sql ="
			SELECT distinct 
				b.schluesselgesamt as Schluessel, 
				b.bezeichnung AS Name ";
		if ($hist_alb) {
			$sql .= "FROM alkis.ax_historischesflurstueckohneraumbezug f ";
			$istgebucht = 'isthistgebucht';
		}
		else {
			$sql.="FROM alkis.ax_flurstueck f ";
			$istgebucht = 'istgebucht';
		}
		//$sql.="LEFT JOIN alkis.ax_buchungsstelle s2 ON array[f." . $istgebucht . "] <@ s2.an ";
		//$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON f." . $istgebucht . " = s.gml_id OR array[f." . $istgebucht . "] <@ s.an OR array[f." . $istgebucht . "] <@ s2.an AND array[s2.gml_id] <@ s.an ";
		$sql.="
				JOIN alkis.ax_buchungsstelle s ON f." . $istgebucht . " = s.gml_id OR ARRAY[f.gml_id] <@ s.verweistauf OR (s.buchungsart != 2103 AND ARRAY[f." . $istgebucht . "] <@ s.an) 
				LEFT JOIN alkis.ax_buchungsart_buchungsstelle art ON s.buchungsart = art.wert 
				LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id 
				LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk 
			WHERE 
				f.flurstueckskennzeichen = '" . $FlurstKennz . "'";
		if(!$hist_alb) $sql.= $this->build_temporal_filter(array('f', 's', 'g', 'b'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0] OR pg_num_rows($ret[1])==0){
      $Bezirk['name']="nicht gefunden";
      $Bezirk['schluessel']="0";
    }
    else{
			while ($rs=pg_fetch_assoc($ret[1])) {
				$bezirke[] = $rs;
			}
    }
    return $bezirke;
  }
  
  function getHausNrListe($GemID, $StrID) {
    $sql = "
			SELECT 
				concat_ws('-', lmh.land || lmh.regierungsbezirk || lmh.kreis || lmh.gemeinde, lmh.lage, lmh.hausnummer) AS id, 
				TRIM(" . HAUSNUMMER_TYPE . "(lmh.hausnummer)) AS nrtext
			FROM 
				alkis.ax_lagebezeichnungmithausnummer lmh,
				array_remove(string_to_array(regexp_replace(regexp_replace(" . HAUSNUMMER_TYPE . "(lmh.hausnummer), '(\d+)(\D+)', '\\1~\\2', 'g'), '(\D+)(\d+)', '\\1~\\2', 'g'), '~'), '') AS r
			WHERE 
				lmh.gemeinde = '" . substr($GemID, -3) . "'
				AND lmh.lage IN ('" . implode("', '", explode(", ", $StrID)) . "')
				AND lmh.kreis = '" . substr($GemID, 3, 2) . "'";
    $sql.= $this->build_temporal_filter(array('lmh'));
		$sql.= " 
			GROUP BY lmh.land, lmh.regierungsbezirk, lmh.kreis, lmh.gemeinde, lmh.lage, lmh.hausnummer, r
			ORDER BY r[1]::int, trim(r[2]) NULLS FIRST, r[3]::int NULLS FIRST";
    #echo $sql;
    $this->debug->write("<p>postgres getHausNrListe Abfragen der Strassendaten:<br>" . $sql, $this->debug_level);
    $queryret = $this->execSQL($sql, 4, 0);
    while ($rs = pg_fetch_assoc($queryret[1])) {
      $Liste['HausID'][] = $rs['id'];
      $Liste['HausNr'][] = $rs['nrtext'];
    }
    return $Liste;
  }
    
		
	# Hier bitte nicht auf die Idee kommen, die Strassen ohne die Flurstücke abfragen zu können. 
	# Die Flurstücke müssen miteinbezogen werden, damit nur Straßen mit Flurstücksbezug aufgelistet werden	
  function getStrassenListe($GemID, $GemkgID) {
    $sql = "
			SELECT 
				'000'::varchar AS gemeinde, 
				'0'::varchar AS strasse, 
				'--Auswahl--'::varchar AS strassenname
			UNION ALL
				SELECT 
					lke.gemeinde, 
					string_agg(distinct lke.lage, ', ') AS strasse, 
					lke.bezeichnung AS strassenname
				FROM 
					alkis.ax_lagebezeichnungkatalogeintrag lke
					JOIN (
						SELECT  
							lmh.kreis as lmh_kreis, 
							lmh.gemeinde as lmh_gemeinde, 
							lmh.lage as lmh_lage,
              loh.kreis as loh_kreis, 
							loh.gemeinde as loh_gemeinde, 
							loh.lage as loh_lage
						FROM 
							alkis.ax_flurstueck f
							LEFT JOIN alkis.ax_lagebezeichnungmithausnummer lmh ON lmh.gml_id = ANY(f.weistauf)
							LEFT JOIN alkis.ax_lagebezeichnungohnehausnummer loh ON loh.gml_id = ANY(f.zeigtauf)
					WHERE ";
					if ($GemID != '') {
						$sql.= " 
							f.land = '" . substr($GemID, 0, 2) . "' AND f.gemeindezugehoerigkeit_kreis = '" . substr($GemID, 3, 2) . "' AND f.gemeindezugehoerigkeit_gemeinde = '" . substr($GemID, -3) . "'";
					}
					elseif ($GemkgID != '') {
						$sql.= " 
							f.land || f.gemarkungsnummer = '" . $GemkgID . "'";
					}
					$sql.= 
						$this->build_temporal_filter(array('f', 'lmh', 'loh')) . "
					) lb ON (lke.gemeinde, lke.lage, lke.kreis) IN ( (lb.lmh_gemeinde, lb.lmh_lage, lb.lmh_kreis),
                                                           (lb.loh_gemeinde, lb.loh_lage, lb.loh_kreis) )";
    $sql.= $this->build_temporal_filter(array('lke'));
    $sql.= $this->build_temporal_filter_fachdatenverbindung(array('lke'));
    $sql.= " GROUP BY lke.gemeinde, lke.bezeichnung ORDER BY gemeinde, strassenname, strasse";
    #echo $sql;
    $this->debug->write("<p>postgres getStrassenListe Abfragen der Strassendaten:<br>" . $sql, $this->debug_level);
    $queryret = $this->execSQL($sql, 4, 0);
    while ($rs = pg_fetch_assoc($queryret[1])) {
			$Liste['Gemeinde'][] = $rs['gemeinde'];
			$Liste['StrID'][] = $rs['strasse'];
			$Liste['name'][] = $rs['strassenname'];
    }
    return $Liste;
  }
	
  function getStrassenListe_not_unique($GemID,$GemkgID) {		
	# diese Funktion wird verwendet, wenn die Strassennamen pro Gemeinde nicht eindeutig sind
	# gleiche Straßennamen werden dann einzeln und mit Gemarkungsnamen in Klammern dahinter gelistet
  	$sql ="set enable_seqscan = off;SELECT '000' AS gemeinde,'0' AS strasse,'--Auswahl--' AS strassenname, '' as gemkgname";
    $sql.=" UNION";
    $sql.=" SELECT DISTINCT g.gemeinde, s.lage as strasse, s.bezeichnung as strassenname, array_to_string(array_agg(distinct gem.bezeichnung), ', ') as gemkgname";
    $sql.=" FROM alkis.ax_gemeinde as g, alkis.ax_gemarkung as gem, alkis.ax_flurstueck as f";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(f.weistauf)";
		$sql.=" LEFT JOIN alkis.ax_lagebezeichnungohnehausnummer lo ON lo.gml_id = ANY(f.zeigtauf)";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON f.gemeindezugehoerigkeit_gemeinde = s.gemeinde AND l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND s.lage = l.lage OR (lo.kreis=s.kreis AND lo.gemeinde=s.gemeinde AND lo.lage=s.lage)";
		$sql.=" WHERE s.lage IS NOT NULL AND g.gemeinde = f.gemeindezugehoerigkeit_gemeinde AND g.kreis=f.gemeindezugehoerigkeit_kreis AND f.gemarkungsnummer = gem.gemarkungsnummer ";
    if ($GemID!='') {
      $sql.=" AND g.schluesselgesamt='".$GemID."'";
    }
    if ($GemkgID!='') {
      $sql.=" AND f.land||f.gemarkungsnummer='".$GemkgID."'";
    }
		$sql.= $this->build_temporal_filter(array('g', 'gem', 'f', 'l', 'lo', 's'));
		$sql.= $this->build_temporal_filter_fachdatenverbindung(array('s'));
		$sql.=" GROUP BY g.gemeinde, s.bezeichnung, s.lage";
    $sql.=" ORDER BY gemeinde, strassenname, strasse";
    #echo $sql;
    $this->debug->write("<p>postgres getStrassenListe Abfragen der Strassendaten:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    $i = 0;
    while ($rs=pg_fetch_assoc($queryret[1])) {
			$Liste['Gemeinde'][]=$rs['gemeinde'];
			$Liste['StrID'][]=$rs['strasse'];
			$Liste['Gemarkung'][]=$rs['gemkgname'];
			$Liste['gemkgschl'][]=$rs['gemkgschl'];
			$namen[]=$rs['strassenname'];		# eigentlichen Strassennamen sichern
			if($namen[$i-1] == $rs['strassenname'] AND $Liste['Gemarkung'][$i-1] != $rs['gemkgname']){
				$Liste['name'][$i-1]=$namen[$i-1].' ('.$Liste['Gemarkung'][$i-1].')';
				$Liste['name'][$i]=$rs['strassenname'].' ('.$rs['gemkgname'].')';
			}
			else{
				$Liste['name'][]=$rs['strassenname'];
			}
      $i++;
    }
    return $Liste;
  }	
        
  function getFlurenListeByGemkgIDByFlurID($GemkgID,$FlurID, $history_mode = 'aktuell'){
		# ax_gemarkungsteilflur kann nicht verwendet werden, da dies eine Katalogtabelle ist und Objekte in diesen nicht beendet werden
		switch ($history_mode) {
			case 'aktuell' : {	
				$sql = "
					SELECT distinct 
						substring(flurstueckskennzeichen, 7, 3)::integer as flurnummer,
						substring(flurstueckskennzeichen, 7, 3) AS FlurID,
						substring(flurstueckskennzeichen, 7, 3) AS Name,
						substring(flurstueckskennzeichen, 1, 9) AS GemFlurID 
					FROM 
						alkis.ax_flurstueck 
					WHERE 
						flurstueckskennzeichen LIKE '" . $GemkgID . "%'" . 
						(!empty($FlurID)? ' AND flurnummer IN (' . implode(',', $FlurID) . ')' : '') . 
						$this->build_temporal_filter(array('ax_flurstueck')) . "
					ORDER BY 
						FlurID";
			}break;
			case 'historisch' : {
				// die Fluren aller historischen Flurstücke abfragen
				$sql = "
					SELECT 
						substring(flurstueckskennzeichen, 7, 3)::integer as flurnummer,
						substring(flurstueckskennzeichen, 7, 3) AS FlurID,
						substring(flurstueckskennzeichen, 7, 3) AS Name,
						substring(flurstueckskennzeichen, 1, 9) AS GemFlurID
					FROM 
						alkis.ax_flurstueck
					WHERE 
						flurstueckskennzeichen LIKE '" . $GemkgID . "%'
					GROUP BY 
						flurstueckskennzeichen
					HAVING 
						bool_and(endet IS NOT NULL)
					UNION 
					SELECT 
						substring(flurstueckskennzeichen, 7, 3)::integer as flurnummer,
						substring(flurstueckskennzeichen, 7, 3) AS FlurID,
						substring(flurstueckskennzeichen, 7, 3) AS Name,
						substring(flurstueckskennzeichen, 1, 9) AS GemFlurID
					FROM 
						alkis.ax_historischesflurstueckohneraumbezug hf
					WHERE 
						hf.flurstueckskennzeichen LIKE '" . $GemkgID . "%'
					ORDER BY 
						FlurID;";						
			}break;
			case 'beides' : {
				$sql = "
					SELECT 
						substring(flurstueckskennzeichen, 7, 3)::integer as flurnummer,
						substring(flurstueckskennzeichen, 7, 3) AS FlurID,
						substring(flurstueckskennzeichen, 7, 3) AS Name,
						substring(flurstueckskennzeichen, 1, 9) AS GemFlurID
					FROM 
						alkis.ax_flurstueck
					WHERE 
					flurstueckskennzeichen LIKE '" . $GemkgID . "%'" . 
					(!empty($FlurID)? ' AND flurnummer IN (' . implode(',', $FlurID) . ')' : '') . "
					UNION
					SELECT 
						substring(flurstueckskennzeichen, 7, 3)::integer as flurnummer,
						substring(flurstueckskennzeichen, 7, 3) AS FlurID,
						substring(flurstueckskennzeichen, 7, 3) AS Name,
						substring(flurstueckskennzeichen, 1, 9) AS GemFlurID
					FROM 
						alkis.ax_historischesflurstueckohneraumbezug hf
					WHERE 
						hf.flurstueckskennzeichen LIKE '" . $GemkgID . "%'
					ORDER BY 
						FlurID";
			}
		}
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_assoc($queryret[1])) {
      $Liste['FlurID'][]=$rs['flurid'];
      $Liste['name'][]=intval($rs['name']);
      $Liste['GemFlurID'][]=$rs['gemflurid'];
    }
    return $Liste;
  }
			
	function check_poly_in_flur($polygon, $epsg){
		$sql = "SELECT f.land||f.gemarkungsnummer, f.flurnummer FROM alkis.ax_flurstueck f WHERE st_intersects(wkb_geometry, st_transform(st_geomfromtext('".$polygon."', ".$epsg."), ".EPSGCODE_ALKIS."))";
		$sql.= $this->build_temporal_filter(array('f'));
  	return $this->execSQL($sql,4, 1);
	}
  

##########################################################################
# ALK Funktionen
##########################################################################
	
	function getGemeindeName($Gemeinde){
    $this->debug->write("<br>postgres.php->database->getGemeindeName, Abfrage des Gemeindenamens",4);
    $sql ='SELECT g.gemeindename AS name FROM alkis.pp_gemeinde AS g';
    $sql.=" WHERE land::text||regierungsbezirk::text||kreis::text||lpad(gemeinde::text, 3, '0') = '".$gemeinde."'";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschlieï¿½enden Rechtecks um die Gemeinde.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_assoc($ret[1]);
      $ret[1]=$rs;
    }
    return $ret;
  }
	
	function getMERfromGemeinde($gemeinde, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromGemarkung, Abfrage des Maximalen umschliessenden Rechtecks um die Gemeinde",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkis.pp_gemeinde";
    $sql.=" WHERE land::text||regierungsbezirk::text||kreis::text||lpad(gemeinde::text, 3, '0') = '".$gemeinde."'";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschliessenden Rechtecks um die Gemeinde.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_assoc($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Gemeinde nicht in Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }
	
	function getMERfromGemarkung($Gemarkung, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromGemarkung, Abfrage des Maximalen umschliessenden Rechtecks um die Gemarkung",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkis.pp_gemarkung";
    $sql.=" WHERE schluesselgesamt = '".$Gemarkung."'";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschliessenden Rechtecks um die Gemarkung.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_assoc($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Gemarkung nicht in Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }
	
	function getMERfromFlur($Gemarkung,$Flur, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromFlur, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Flur",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(the_geom, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkis.pp_flur";
    $sql.=" WHERE land||gemarkung = '".$Gemarkung."'";
    $sql.=" AND flurnummer = ".(int)$Flur;
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschliessenden Rechtecks um die Flur.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_assoc($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Flur nicht in Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }

  function getGeomfromFlurstuecke($flurstkennz, $epsgcode) {
    $sql =" SELECT 
							st_astext(st_transform(st_union(wkb_geometry), ".$epsgcode.")) AS wkt
						FROM 
							alkis.ax_flurstueck AS f
						WHERE 
							f.flurstueckskennzeichen IN ('".implode("','", $flurstkennz)."')"
							.$this->build_temporal_filter(array('f'));
    $ret=$this->execSQL($sql, 4, 0);
    $rs=pg_fetch_assoc($ret[1]);
    return $rs['wkt'];
  }	
	
  function getMERfromFlurstuecke($flurstkennz, $epsgcode, $without_temporal_filter = false) {
    $this->debug->write("<br>postgres.php->database->getMERfromFlurstuecke, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Flurstï¿½cke",4);
    $sql = "
			SELECT 
				MIN(st_xmin(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS minx,
				MAX(st_xmax(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS maxx,
				MIN(st_ymin(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS miny,
				MAX(st_ymax(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS maxy,
				bool_and(endet IS NOT NULL) as hist
			FROM 
				alkis.ax_flurstueck AS f
			WHERE 1=1";
    $anzflst = count_or_0($flurstkennz);
    if ($anzflst>0) {
      $sql.=" AND f.flurstueckskennzeichen IN ('".$flurstkennz[0]."'";
      for ($i=1;$i<$anzflst;$i++) {
        $sql.=",'".$flurstkennz[$i]."'";
      }
      $sql.=")";
    }
		if (!$without_temporal_filter) {
			$sql.= $this->build_temporal_filter(array('f'));
		}
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschliessenden Rechtecks um die Flurstücke.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_assoc($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Flurstück nicht in Postgres Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
				if ($rs['hist'] == 't') {
					$this->gui->add_message('info', 'Achtung! Zoom auf historische Flurstücke!');
				}
        $ret[1]=$rs;
      }
    }
    return $ret;
  }
  
	function getMERfromGebaeude($Gemeinde, $Strasse, $Hausnr, $epsgcode) {
		if ($Hausnr != '') {
			$hausnummern = explode(', ', $Hausnr);
			foreach($hausnummern as $hausnummer){
				$hnr = explode('-', $hausnummer);
				$kreis = substr($hnr[0], 3, 2);
				$gemeinde = substr($hnr[0], 5, 3);
				$lage = $hnr[1];
				$nummer = strtolower($hnr[2]);
				$expr[] = "('" . $gemeinde . "', '" . $lage . "', '" . $nummer . "', '" . $kreis . "')";
			}
			$filter = " (lmh.gemeinde, lmh.lage, lmh.hausnummer, lmh.kreis) IN (" . implode(', ', $expr) . ")";
		}
		else {
			$strassen = explode(', ', $Strasse);
			$kreis = substr($Gemeinde, 3, 2);
			$gemeinde = substr($Gemeinde, 5, 3);
			foreach($strassen as $lage){
				$expr[] = "('" . $gemeinde . "', '" . $lage . "', '" . $kreis . "')";
			}
			$filter = " (lmh.gemeinde, lmh.lage, lmh.kreis) IN (" . implode(', ', $expr) . ")";
		}
    $sql ="
			SELECT
			  min(st_xmin(env)) AS minx, 
			  max(st_xmax(env)) AS maxx,
			  min(st_ymin(env)) AS miny, 
			  max(st_ymax(env)) AS maxy,
				'''' || array_to_string(array_agg(gml_ids), ''',''') || '''' as gml_ids
			FROM
			  alkis.ax_gebaeude g,
			  st_envelope(st_transform(g.wkb_geometry, " . $epsgcode . ")) AS env,
				unnest(g.zeigtauf) as gml_ids
			WHERE
			  g.zeigtauf && ARRAY (
			  SELECT
			    gml_id
			  FROM
			    alkis.ax_lagebezeichnungmithausnummer lmh
			  WHERE
			   " . $filter . "
			   " . $this->build_temporal_filter(array('lmh')) . "
			  )
			 " . $this->build_temporal_filter(array('g'));
		#echo $sql;
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret[0]) {
			$ret[1] = 'Fehler beim Abfragen des umschliessenden Rechtecks um die Geb&auml;ude.<br>' . $ret[1];
		}
		else {
			$rs = pg_fetch_assoc($ret[1]);
			if ($rs['minx'] == 0) {
				$ret[0] = 1;
			}
			$ret[1] = $rs;
		}
		return $ret;
	}


##################################################
# Funktionen der Metadaten
##################################################

  function insertMetadata($md) {
    #2005-11-29_pk
    #$this->begintransaction(); funzt so nicht, da in Transaktion nicht die zukï¿½nftige id des Datensatzes abgefragt werden kann
    #$mdfileid,$mdlang,$mddatest,$mdcontact,$spatrepinfo,$refsysinfo,$mdextinfo,$dataidinfo,$continfo,$distinfo,$idtype,$restitle,$datalang,$idabs,$themekeywords,$placekeywords,$tpcat,$reseddate,$validfrom,$validtill,$westbl,$eastbl,$southbl,$northbl,$identcode,$rporgname,$postcode,$city,$delpoint,$adminarea,$country,$linkage,$servicetype,$spatialtype,$serviceversion,$vector_scale,$databinding,$solution,$status,$onlinelinke,$cyclus,$sparefsystem,$sformat,$sformatversion,$download,$onlinelink,$accessrights
    $sql ="INSERT INTO md_metadata";
    $sql.=" (mdfileid,mdlang,mddatest,mdcontact,spatrepinfo,refsysinfo,mdextinfo,dataidinfo";
    $sql.=",continfo,distinfo,idtype,restitle,datalang,idabs,tpcat";
    $sql.=",reseddate,validfrom,validtill,westbl,eastbl,southbl,northbl,identcode,rporgname,postcode,city,delpoint";
    $sql.=",adminarea,country,linkage,servicetype,spatialtype,serviceversion,vector_scale,databinding,solution,status";
    $sql.=",onlinelinke,cyclus,sparefsystem,sformat,sformatversion,download,onlinelink,accessrights,the_geom)";
    $sql.=" VALUES ('".$md['mdfileid']."','".$md['mdlang']."','".$md['mddatest']."'";
    $sql.=",".$md['mdcontact'].",".$md['spatrepinfo'].",".$md['refsysinfo'];
    $sql.=",".$md['mdextinfo'].",".$md['dataidinfo'].",".$md['continfo'];
    $sql.=",".$md['distinfo'].",'".$md['idtype']."','".$md['restitle']."'";
    $sql.=",'".$md['datalang']."','".$md['idabs']."','".$md['tpcat']."','".$md['reseddate']."'";
    $sql.=",'".$md['validfrom']."','".$md['validtill']."','".$md['westbl']."'";
    $sql.=",'".$md['eastbl']."','".$md['southbl']."','".$md['northbl']."'";
    $sql.=",'".$md['identcode']."','".$md['rporgname']."',".$md['postcode'];
    $sql.=",'".$md['city']."','".$md['delpoint']."','".$md['adminarea']."','".$md['country']."'";
    $sql.=",'".$md['linkage']."','".$md['servicetype']."','".$md['spatialtype']."'";
    $sql.=",'".$md['serviceversion']."',".$md['vector_scale'].",'".$md['databinding']."'";
    $sql.=",'".$md['solution']."','".$md['status']."','".$md['onlinelinke']."'";
    $sql.=",'".$md['cyclus']."','".$md['sparefsystem']."','".$md['sformat']."'";
    $sql.=",'".$md['sformatversion']."','".$md['download']."','".$md['onlinelink']."'";
    $sql.=",'".$md['accessrights']."',st_geometryfromtext('".$md['umring']."',".EPSGCODE."))";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $errmsg.='Fehler beim Eintragen der Metadaten in die Datenbank.<br>'.$ret[1];
    }
    else {
      # Abfragen der eben erzeugten Metadaten_id
      $ret=$this->getMetadataByMdFileID($md['mdfileid']);
      if ($ret[0]) {
        $errmsg.='Fehler beim Abfragen der neuen Metadatenid.<br>'.$ret[1];
      }
      else {
        $metadata_id=$ret[1]['id'];
        # Eintragen der Zuordnungen der Keywords zum Metadatensatz
        $keywordids=explode(", ",$md['selectedthemekeywordids']);
        for ($i=0;$i<count($keywordids);$i++) {
          $sql ="INSERT INTO md_keywords2metadata (keyword_id,metadata_id)";
          $sql.=" VALUES ('".$keywordids[$i]."','".$metadata_id."')";
          $ret=$this->execSQL($sql, 4, 0);
          if ($ret[0]) {
            $errmsg.='<br>Fehler beim Eintragen des Schlï¿½sselwï¿½rter zum Metadatensatz.';
          }
        }
        $keywordids=explode(",",$md['selectedplacekeywordids']);
        for ($i=0;$i<count($keywordids);$i++) {
          $sql ="INSERT INTO md_keywords2metadata (keyword_id,metadata_id)";
          $sql.=" VALUES ('".$keywordids[$i]."','".$metadata_id."')";
          $ret=$this->execSQL($sql, 4, 0);
          if ($ret[0]) {
            $errmsg.='<br>Fehler beim Eintragen des Schlï¿½sselwortes zum Metadatensatz.';
          }
        }
      } # end of erfolgreiches Abfragen der Metadatenid
    } # ende of erfolgreiches Eintragen des Metadatensatzes
    if ($errmsg!='') {
      $ret[1]="Der Metadatensatz wurde nicht eingetragen.<br>".$errmsg;
      #$this->rollbacktransaction();
      echo $ret[1];
    }
    else {
      $ret[1]="Der Metadatensatz wurde erfolgreich eingetragen.<br>";
      #$this->committransaction();
    }
    return $ret;
  }

  function updateMetadata($md){
    $sql ="UPDATE md_metadata SET mdfileid='".$md['mdfileid']."',mdlang='".$md['mdlang']."'";
    $sql.=",mddatest='".$md['mddatest']."',mdcontact='".$md['mdcontact']."',spatrepinfo='".$md['spatrepinfo']."'";
    $sql.=",refsysinfo='".$md['refsysinfo']."',mdextinfo='".$md['mdextinfo']."',dataidinfo='".$md['dataidinfo']."'";
    $sql.=",continfo='".$md['continfo']."',distinfo='".$md['distinfo']."',idtype='".$md['idtype']."'";
    $sql.=",restitle='".$md['restitle']."',datalang='".$md['datalang']."',idabs='".$md['idabs']."'";
    $sql.=",themekeywords='".$md['themekeywords']."',placekeywords='".$md['placekeywords']."'";
    $sql.=",tpcat='".$md['tpcat']."',reseddate='".$md['reseddate']."',validfrom='".$md['validfrom']."'";
    $sql.=",validtill='".$md['validtill']."',westbl='".$md['westbl']."',eastbl='".$md['eastbl']."'";
    $sql.=",southbl='".$md['southbl']."',northbl='".$md['northbl']."',identcode='".$md['identcode']."'";
    $sql.=",rporgname='".$md['rporgname']."',postcode=".(int)$md['postcode'].",city='".$md['city']."'";
    $sql.=",delpoint='".$md['delpoint']."',adminarea='".$md['adminarea']."',country='".$md['country']."'";
    $sql.=",linkage='".$md['linkage']."',servicetype='".$md['servicetype']."',spatialtype='".$md['spatialtype']."'";
    $sql.=",serviceversion='".$md['serviceversion']."',vector_scale=".(int)$md['vector_scale'];
    $sql.=",databinding='".$md['databinding']."',solution='".$md['solution']."',status='".$md['status']."'";
    $sql.=",onlinelinke='".$md['onlinelinke']."',cyclus='".$md['cyclus']."',sparefsystem='".$md['sparefsystem']."'";
    $sql.=",sformat='".$md['sformat']."',sformatversion='".$md['sformatversion']."',download='".$md['download']."'";
    $sql.=",onlinelink='".$md['onlinelink']."',accessrights='".$md['accessrights']."'";
    $sql.=" WHERE mdfileid='".$md['mdfileid']."'";
    return $this->execSQL($sql, 4, 0);
  }

  function getKeywords($id,$keyword,$keytyp,$thesaname,$metadata_id,$order) {
    # letzte Aenderung 2005-11-29 pk
    if (is_array($id)) { $idliste=$id; }  else { $idliste=array($id); }
    $anzid=count($idliste);
    $sql ="SELECT k.id,k.keyword,k.keytyp,k.thesaname FROM md_keywords AS k";
    if ($metadata_id!='') {
      $sql.=",md_keywords2metadata AS k2m WHERE k2m.keyword_id=k.id AND k2m.metadata_id=".(int)$metadata_id;
    }
    else {
      $sql.=" WHERE (1=1)";
    }
    if ($idliste[0]!='') {
      $sql.=" AND k.id IN (".$idliste[0];
      for ($i=1;$i<$anzid;$id++) {
        $sql.=",".$id[$i];
      }
      $sql.=")";
    }
    if ($keyword!='') {
      $sql.=" AND k.keyword LIKE '".$keyword."'";
    }
    if ($keytyp!='') {
      $sql.=" AND k.keytyp='".$keytyp."'";
    }
    if ($thesaname!='') {
      $sql.=" AND k.thesaname='".$thesaname."'";
    }
    $sql.=" ORDER BY " . replace_semicolon($order);
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      # Fehler beim Abfragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnten die Schlagwï¿½rter nicht abgefragt werden!\n'.$ret[1];
    }
    else {
      while($rs=pg_fetch_assoc($ret[1])) {
        $keywords['id'][]=$rs['id'];
        $keywords['keyword'][]=$rs['keyword'];
      }
      $ret[1]=$keywords;
    }
    return $ret;

  }

  function getMetadataByMdFileID($mdfileid){
    $sql ="SELECT * FROM md_metadata";
    $sql.=" WHERE mdfileid = '".$mdfileid."'";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $ret[1]=pg_fetch_array($ret[1]);
    }
    return $ret;
  }

	function getMetadata($md) {
		# ToDo überarbeiten fuer Postgres Version 12
		$sql = "
			SELECT
				oid, *
			FROM
				md_metadata
			WHERE
				true" .
				($md['oid'] != '' 			? " AND oid = " . $md['oid'] : '') .
				($md['mdfileid'] != '' 	? " AND mdfileid = " . $md['mdfileid'] : '') . "
		";
		$ret = $this->execSQL($sql, 4, 0);
		if ($ret[0]==0) {
			while ($rs=pg_fetch_array($ret[1])) {
				$mdresult[] = $rs;
			}
			$ret[1] = $mdresult;
		}
		return $ret;
	}

	##################################################
	# functions for administrative Grenzen
	##################################################
  function truncateAdmKreise() {
    $sql ="TRUNCATE adm_landkreise";
    return $this->execSQL($sql, 4, 0);
  }

	function insertAdmKreis($colnames,$row) {
		$sql = "
			INSERT INTO adm_landkreis (" .
				implode(', ', $colnames) . "
			) VALUES (" .
				implode(
					', ',
					array_map(
						function($r) {
							return "'" . $r . "'";
						},
						$row
					)
				) . "
			)
		";
		return $this->execSQL($sql, 4, 0);
	}

##################################################
# Funktionen der Anwendung kvwmap
##################################################

/*
# Werden in der postgis-Datenbank z.Z. nicht verwendet

  function getFilteredUsedLayer($layername) {
    # liefert die idï¿½s der Zuordnung zwischen Layern und Stellen (used_layer_id),
    # die mit einem Polygon gefiltert werden sollen
    $sql ="SELECT DISTINCT ul.used_layer_id,l.data,ul.stelle_id FROM polygon AS p, polygon_used_layer AS pul";
    $sql.=", used_layer AS ul, layer AS l WHERE p.polygon_id = pul.polygon_id";
    $sql.=" AND pul.used_layer_id = ul.used_layer_id AND ul.layer_id = l.layer_id";
    $sql.=" AND l.name = '".$layername."'";
    return $this->execSQL($sql, 4, 0);
  }

  function getFilterPolygons($used_layer_id) {
    # liefert Shapdateinamen und Namen des Polygons mit denen ein Filter
    # fï¿½r used_layer_id berechnet werden soll
    $sql ="SELECT p.polygonname,p.datei,p.feldname FROM polygon AS p, polygon_used_layer AS pul";
    $sql.=" WHERE p.polygon_id = pul.polygon_id";
    $sql.=" AND pul.used_layer_id=".$used_layer_id;
    return $this->execSQL($sql, 4, 0);
  }

  function setFilter($used_layer_id,$filter) {
    $sql ="UPDATE used_layer SET filter='".$filter."'";
    $sql.=" WHERE used_layer_id=".$used_layer_id;
    return $this->execSQL($sql, 4, 0);
  }

*/

####################################################
# database Funktionen
###########################################################

  function begintransaction() {
    # Starten einer Transaktion
    # initiates a transaction block, that is, all statements
    # after BEGIN command will be executed in a single transaction
    # until an explicit COMMIT or ROLLBACK is given
    if ($this->blocktransaction == 0) {
      $ret=$this->execSQL('BEGIN', 4, 0);
    }
    return $ret;
  }

  function rollbacktransaction() {
    # Rückgängigmachung aller bisherigen ï¿½nderungen in der Transaktion
    # und Abbrechen der Transaktion
    # rolls back the current transaction and causes all the updates
    # made by the transaction to be discarded
    if ($this->blocktransaction == 0) {
      $ret=$this->execSQL('ROLLBACK',4 , 0);
    }
    return $ret;
  }

  function committransaction() {
    # Gueltigmachen und Beenden der Transaktion
    # commits the current transaction. All changes made by the transaction
    # become visible to others and are guaranteed to be durable if a crash occurs
    if ($this->blocktransaction == 0) {
      $ret = $this->execSQL('COMMIT', 4, 0);
    }
    return $ret;
  }

  function vacuum() {
  	if (!$this->vacuumOff) {
  		return $this->execSQL('VACUUM',4, 1);
  	}
  }

  function getAffectedRows($query) {
#    echo '<br>query:'.$query;
    $anzRows=pg_affected_rows($query);
#    echo ' anzRows:'.$anzRows;
    return $anzRows;
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

	function drop_table($schema_name, $table_name) {
		$sql = "
			DROP TABLE IF EXISTS " . $schema_name . "." . $table_name . "
		";
		#echo '<br>SQL: ' . $sql;
		$ret = $this->execSQL($sql, 4, 0);
		return $ret;
	}

	function drop_schema($schema_name, $cascade = false) {
		$sql = "
			DROP SCHEMA IF EXISTS " . $schema_name .
			($cascade ? ' CASCADE' : '') . "
		";
		$ret = $this->execSQL($sql, 4,0);
		return $ret;
	}
}