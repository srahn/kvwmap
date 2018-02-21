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

  function database() {
    global $debug;
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
  }

  function login_user($username, $passwort){
  	$sql = "SELECT login_name FROM user WHERE login_name = '".addslashes($username)."' AND passwort = '".md5($passwort)."'";
  	$sql.=' AND (("'.date('Y-m-d h:i:s').'" >= start AND "'.date('Y-m-d h:i:s').'" <= stop)';
    $sql.=' OR ';
    $sql.='(start="0000-00-00 00:00:00" AND stop="0000-00-00 00:00:00"))';		# Zeiteinschränkung wird nicht berücksichtigt.
  	#echo $sql;
		$this->execSQL("SET NAMES '".MYSQL_CHARSET."'",0,0);
  	$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $ret = mysql_fetch_array($ret[1]);
    if($ret[0] != ''){
    	return true;
    }
    else{
    	return false;
    }
  }

  
  function read_colors(){
  	$sql = "SELECT * FROM colors";
  	#echo $sql;
  	$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    if($ret[0]==0){
      while($row = mysql_fetch_array($ret[1])){
        $colors[] = $row;
      }
    }
    return $colors;
  }
  
  function read_color($id){
  	$sql = "SELECT * FROM colors WHERE id = ".$id;
  	#echo $sql;
  	$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    if($ret[0]==0){
      $color = mysql_fetch_array($ret[1]);
    }
    return $color;
  }

	function create_new_gast($gast_stelle){
		$loginname = "";
		$laenge=10;
		$string="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		mt_srand((double)microtime()*1000000);
		for ($i=1; $i <= $laenge; $i++) {
			$loginname .= substr($string, mt_rand(0,strlen($string)-1), 1);
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
				'" . $gast_stelle . 
				"'
			);
		";
		#echo '<br>sql: ' . $sql;
		$query = mysql_query($sql);

		# ID des Gastnutzers abfragen
		$sql = "
			SELECT LAST_INSERT_ID();
		";
		$query = mysql_query($sql);
		$row = mysql_fetch_row($query);
		$new_user_id = $row[0];

		# ID des Defaultnutzers abfragen
		$sql = "
			SELECT
				`default_user_id`
			FROM
				`stelle` s JOIN
				`rolle` r ON (s.ID = r.stelle_id AND s.default_user_id = r.user_id)
			WHERE
				`ID` = " . $gast_stelle . "
		";
		#echo '<br>sql: ' . $sql;
		$query = mysql_query($sql);
		if (mysql_num_rows($query) > 0) {
			$row = mysql_fetch_assoc($query);
			$default_user_id = $row['default_user_id'];
		}
		else {
			$default_user_id = 0;
		}
		#echo '<br>Default user id: ' . $default_user_id;

		if ($default_user_id > 0) {
			# Rolleneinstellungen vom Defaultnutzer verwenden
			$row = mysql_fetch_assoc($query);
			$rolle_select_sql = "
				SELECT " .
					$new_user_id . ", " .
					$gast_stelle . ",
					`nImageWidth`, `nImageHeight`,
					`auto_map_resize`,
					`minx`, `miny`, `maxx`, `maxy`,
					`nZoomFactor`,
					`selectedButton`,
					`epsg_code`,
					`epsg_code2`,
					`coordtype`,
					`active_frame`,
					`last_time_id`,
					`gui`,
					`language`,
					`hidemenue`,
					`hidelegend`,
					`fontsize_gle`,
					`highlighting`,
					`buttons`,
					`scrollposition`,
					`result_color`,
					`always_draw`,
					`runningcoords`,
					`showmapfunctions`,
					`showlayeroptions`,
					`singlequery`,
					`querymode`,
					`geom_edit_first`,
					`overlayx`, `overlayy`,
					`instant_reload`,
					`menu_auto_close`,
					`layer_params`,
					`visually_impaired`
				FROM
					`rolle`
				WHERE
					`user_id` = " . $default_user_id . " AND
					`stelle_id` = " . $gast_stelle . "
			";
		}
		else {
			# Default - Rolleneinstellungen verwenden
			$rolle_select_sql = "
				SELECT " .
					$new_user_id . ", " .
					$gast_stelle . ",
					'800', '600',
					1,
					minxmax, minymax, maxxmax, maxymax,
					'2',
					'recentre',
					`epsg_code`,
					NULL,
					'dec',
					NULL,
					'0000-00-00 00:00:00',
					'gui.php',
					'german',
					'0',
					'0',
					'15',
					'1',
					'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure',
					'0',
					'1',
					'1',
					'1',
					'1',
					'1',
					'1',
					'0',
					'0',
					'400', '150',
					'1',
					'0',
					'0',
					NULL
				FROM
					stelle
				WHERE
					ID = " . $gast_stelle . "
			";
		}

		# Rolle für Gastnutzer eintragen
		$sql = "
			INSERT INTO `rolle` (
				`user_id`,
				`stelle_id`,
				`nImageWidth`, `nImageHeight`,
				`auto_map_resize`,
				`minx`, `miny`, `maxx`, `maxy`,
				`nZoomFactor`,
				`selectedButton`,
				`epsg_code`,
				`epsg_code2`,
				`coordtype`,
				`active_frame`,
				`last_time_id`,
				`gui`,
				`language`,
				`hidemenue`,
				`hidelegend`,
				`fontsize_gle`,
				`highlighting`,
				`buttons`,
				`scrollposition`,
				`result_color`,
				`always_draw`,
				`runningcoords`,
				`showmapfunctions`,
				`showlayeroptions`,
				`singlequery`,
				`querymode`,
				`geom_edit_first`,
				`overlayx`, `overlayy`,
				`instant_reload`,
				`menu_auto_close`,
				`layer_params`,
				`visually_impaired`
			) " .
			$rolle_select_sql . "
		";
		#echo '<br>sql: ' . $sql;
		$query = mysql_query($sql);

		include(CLASSPATH . 'stelle.php');
		include(CLASSPATH . 'rolle.php');
		$stelle = new stelle($gast_stelle, $this);
		$rolle = new rolle(NULL, $gast_stelle, $this);
		$layers = $stelle->getLayers(NULL);
		$rolle->setGroups($new_user_id, array($gast_stelle), $layers['ID'], '0');

		# Menüeinstellungen der Rolle eintragen
		if ($default_user_id > 0) {
			# Menueeinstellungen von Defaultrolle abfragen
			$menue2rolle_select_sql = "
				SELECT " .
					$new_user_id . ", " .
					$gast_stelle . ",
					`menue_id`,
					`status`
				FROM
					`u_menue2rolle`
				WHERE
					`stelle_id` = " . $gast_stelle . " AND
					`user_id` = " . $default_user_id . "
			";
		}
		else {
			# Menueeinstellungen mit status 0 von stelle abfragen
			$menue2rolle_select_sql = "
				SELECT " .
					$new_user_id . ", " .
					$gast_stelle . ",
					`menue_id`,
					'0'
				FROM
					`u_menue2stelle`
				WHERE
					`stelle_id` = " . $gast_stelle . "
			";
		}
		$sql = "
			INSERT INTO `u_menue2rolle` (
				`user_id`,
				`stelle_id`,
				`menue_id`,
				`status`
			) " .
			$menue2rolle_select_sql . "
		";
		#echo '<br>sql: ' . $sql;
		$query = mysql_query($sql);

		if ($default_user_id > 0) {
			# Layereinstellungen von Defaultrolle abfragen
			$rolle2used_layer_select_sql = "
				SELECT " .
					$new_user_id . ", " .
					$gast_stelle . ",
					`layer_id`,
					`aktivStatus`,
					`queryStatus`,
					`showclasses`,
					`logconsume`
				FROM
					u_rolle2used_layer
				WHERE
					user_id = " . $default_user_id . " AND
					stelle_id = " . $gast_stelle . "
			";
		}
		else {
			# Layereinstellungen von Defaultlayerzuordnung abfragen
			$rolle2used_layer_select_sql = "
				SELECT " .
					$new_user_id . ", " .
					$gast_stelle . ",
					`Layer_ID`,
					`start_aktiv`,
					`start_aktiv`,
					1,
					0
				FROM
					used_layer
				WHERE
					Stelle_ID = " . (int)$gast_stelle . "
			";
		}

		# Layereinstellungen der Rolle eintragen
		$sql = "
			INSERT INTO `u_rolle2used_layer` (
				`user_id`,
				`stelle_id`,
				`layer_id`,
				`aktivStatus`,
				`queryStatus`,
				`showclasses`,
				`logconsume`
			) " . 
			$rolle2used_layer_select_sql . "
		";
		#echo '<br>sql: ' . $sql;
		$query = mysql_query($sql);

		if ($default_user_id > 0) {
			$sql = "
				UPDATE
					u_groups2rolle AS n,
					u_groups2rolle AS d
				SET
					n.status = d.status
				WHERE
					n.stelle_id = d.stelle_id AND
					n.id = d.id AND
					n.stelle_id = " . $gast_stelle . " AND
					n.user_id = " . $new_user_id . " AND
					d.user_id = " . $default_user_id . "
			";
		}
		else {
			$sql = "
				UPDATE
					u_groups2rolle,
					u_rolle2used_layer,
					layer
				SET
					u_groups2rolle.status = 1
				WHERE
					u_groups2rolle.user_id = " . $new_user_id . " AND
					u_groups2rolle.stelle_id = " . $gast_stelle . " AND
					u_rolle2used_layer.user_id = " . $new_user_id . " AND
					u_rolle2used_layer.stelle_id = " . $gast_stelle. " AND
					u_rolle2used_layer.aktivStatus = '1' AND
					u_rolle2used_layer.layer_id = layer.Layer_ID AND
					layer.Gruppe = u_groups2rolle.id
			";
		}
		#echo '<br>sql: ' . $sql;
		$query = mysql_query($sql);

		# Gespeicherte Themeneinstellungen von default user übernehmen
		if ($default_user_id > 0) {
			$sql = "
				INSERT INTO `rolle_saved_layers` (
					`user_id`,
					`stelle_id`,
					`name`,
					`layers`,
					`query`
				)
				SELECT " .
					$new_user_id . "," .
					$gast_stelle . ",
					`name`,
					`layers`,
					`query`
				FROM
					`rolle_saved_layers`
				WHERE
					`user_id` = " . $default_user_id . " AND
					`stelle_id` = " . $gast_stelle . "
			";
		}
		#echo '<br>Sql: ' . $sql;
		$query = mysql_query($sql);

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
    $ret[1]=mysql_fetch_assoc($ret[1]);
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
	function generate_layer($schema, $table, $group_id = 0, $connection, $epsg = 25832, $geometrie_column = 'the_geom', $geometrietyp = '', $layertyp = '2') {
		#echo '<br>Create Layer: ' . $table['name'];
		if ($geometrietyp != '') $geometrie_column = "({$geometrie_column}).{$geometrietyp}";
		if ($group_id == 0) $group_id = '@group_id';
		if ($connection == '') $connection = '@connection';
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
	`connection`,
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
	'{$connection}',
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

	function generate_layer_attribute($attribute, $options) {
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
	NULL -- mandatory
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
INSERT INTO datatype (
	`Name`,
	`Datentyp`,
	`Gruppe`,
	`pfad`,
	`maintable`,
	`Data`,
	`schema`,
	`connection`,
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
	'{$datatype['type']}',
	'5',
	@group_id,
	'SELECT * FROM {$datatype['type']} WHERE 1=1',
	'{$datatype['type']}', -- maintable
	'geom from (select oid, position AS geom FROM {$schema}.{$datatype['type']}) as foo using unique oid using srid={$epsg}', -- Data
	'{$schema}', -- schema
	@connection, -- connection
	'6', -- connectiontype
	'3',
	'pixels',
	'{$epsg}',
	'1',
	'60',
	'EPSG:{$epsg}',
	'{$table['name']}', -- wms_name
	'1.1.0',
	'image/png',
	'60',
	'1',
	'Diese Tabelle enthält alle Objekte aus der Tabelle {$datatype['type']}.',
	'2'
);
SET @last_database_id_{$datatype['attribute_type_oid']} = LAST_INSERT_ID();
";
		return $sql;
	}

	function generate_datatype_attribute($attribute, $options) {
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
	@last_layer_id_{$attribute['datatype_oid']},
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

	function create_insert_dump($table, $extra, $sql){
		#echo '<br>Create_insert_dump for table: ' . $table;
		#echo '<br>sql: ' . $sql;
		#echo '<br>extra: ' . $extra;
		# Funktion liefert das Ergebnis einer SQL-Abfrage als INSERT-Dump für die Tabelle "$table" 
		# über $extra kann ein Feld angegeben werden, welches nicht mit in das INSERT aufgenommen wird
		# dieses Feld wird jedoch auch mit abgefragt und separat zurückgeliefert
		$this->debug->write("<p>file:kvwmap class:database->create_insert_dump :<br>".$sql,4);
    $query = mysql_query($sql);
    if ($query==0) {
			$this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0;
		}

    $feld_anzahl = mysql_num_fields($query);
		#echo '<br>Anzahl Felder: ' . $feld_anzahl;
    for ($i = 0; $i < $feld_anzahl; $i++) {
    	$meta = mysql_fetch_field($query, $i);
			#echo '<br>Meta name: ' . $meta->name;
    	# array mit feldnamen
    	$felder[$i] = $meta->name;
    	if ($meta->name == 'connectiontype'){
    		$connectiontype = $i;
    	}
    	if($meta->name == 'connection'){
    		$connection = $i;
    	}
    }

    while ($rs = mysql_fetch_array($query)) {
    	$insert = '';
    	if ($rs[$connectiontype] == 6) {
    		$rs[$connection] = '@connection';
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
	    			$insert .= addslashes($rs[$i]);
	    		}
	    		else {
	    			if (mysql_field_type($query, $i) != 'string' AND mysql_field_type($query, $i) != 'blob' AND $rs[$i] == '') {
	    				$insert .= "NULL";
	    			} else{
    					$insert .= "'".addslashes($rs[$i])."'";
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
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }

    $feld_anzahl = mysql_num_fields($query);
    for($i = 0; $i < $feld_anzahl; $i++){
    	$meta = mysql_fetch_field($query,$i);
    	# array mit feldnamen
    	$felder[$i] = $meta->name;
    	# array mit indizes der primary-keys
    	if($meta->primary_key == 1){
    		$keys[] = $i;
    	}
    }
    while($rs = mysql_fetch_array($query)){
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
    $this->debug->write("<br>MySQL Verbindung öffnen mit Host: ".$this->host." User: ".$this->user,4);
    $this->dbConn=mysql_connect($this->host,$this->user,$this->passwd);
    $this->debug->write("Datenbank mit ID: ".$this->dbConn." und Name: ".$this->dbName." auswählen.",4);
    return mysql_select_db($this->dbName,$this->dbConn);
  }

  function close() {
    $this->debug->write("<br>MySQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    if (LOG_LEVEL>0){
    	$this->logfile->close();
    }
    return mysql_close($this->dbConn);
  }

	function exec_file($filename, $search, $replace, $replace_constants = false) {
    if($file = file_get_contents($filename)){
			foreach(explode(';'.chr(10), $file) as $query2){		// verschiedene Varianten des Zeilenumbruchs berücksichtigen
				foreach(explode(';'.chr(13), $query2) as $query){
					$query_to_execute = '';
					$query = trim($query);
					if($search != NULL)$query = str_replace($search, $replace, $query);
					foreach(explode(chr(10), $query) as $line){
						if(strpos($line, "--") !== 0 && strpos($line, "#") !== 0){					// Zeilen mit Kommentarzeichen ignorieren
							$query_to_execute .= $line;
						}
					}
					#echo $query_to_execute.'<br><br>';
					if (!empty($query_to_execute)) {
						$query_to_execute = str_replace('$EPSGCODE_ALKIS', EPSGCODE_ALKIS, $query_to_execute);
						$query_to_execute = str_replace(':alkis_epsg', EPSGCODE_ALKIS, $query_to_execute);

						if ($replace_constants) {
							foreach (get_defined_constants(true)['user'] AS $key => $value) {
								$query_to_execute = str_replace('$' . $key, $value, $query_to_execute);
							}
						}

						$ret=$this->execSQL($query_to_execute, 0, 0);
						if($ret[0] == 1){
							return $ret;
						}
					}
				}
			}
    }
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

  function getAffectedRows($query) {
    return mysql_affected_rows();
  }

  function setFortfuehrung($ist_Fortfuehrung) {
    $this->ist_Fortfuehrung=$ist_Fortfuehrung;
    if ($this->ist_Fortfuehrung) {
      $this->tableprefix=TEMPTABLEPREFIX;
    }
    else {
      $this->tableprefix="";
    }
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

  function execSQL($sql,$debuglevel, $loglevel) {
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
      $query=mysql_query($sql,$this->dbConn);
      #echo $sql;
      if ($query==0) {
        $ret[0]=1;
        $ret[1]="<b>Fehler bei SQL Anweisung:</b><br>".$sql."<br>".mysql_error($this->dbConn);
        $this->debug->write($ret[1],$debuglevel);
        if ($logsql) {
          $this->logfile->write("#".$ret[1]);
        }
      }
      else {
        $ret[0]=0;
        $ret[1]=$query;
        if ($logsql) {
          $this->logfile->write($sql.';');
        }
        $this->debug->write(date('H:i:s')."<br>".$sql,$debuglevel);
      }
      $ret[2]=$sql;
    }
    else {
    	if ($logsql) {
    		$this->logfile->write($sql.';');
    	}
    	$this->debug->write("<br>".$sql,$debuglevel);
    }
    return $ret;
  }
}