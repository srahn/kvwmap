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

	function pgdatabase() {
	  global $debug;
    $this->debug=$debug;
    $this->loglevel=LOG_LEVEL;
 		$this->defaultloglevel=LOG_LEVEL;
 		global $log_postgres;
    $this->logfile=$log_postgres;
 		$this->defaultlogfile=$log_postgres;
    $this->ist_Fortfuehrung=1;
    $this->type='postgresql';
    $this->commentsign='--';
    # Wenn dieser Parameter auf 1 gesetzt ist werden alle Anweisungen
    # START TRANSACTION, ROLLBACK und COMMIT unterdrï¿½ckt, so daï¿½ alle anderen SQL
    # Anweisungen nicht in Transactionsblï¿½cken ablaufen.
    # Kann zur Steigerung der Geschwindigkeit von groï¿½en Datenbestï¿½nden verwendet werden
    # Vorsicht: Wenn Fehler beim Einlesen passieren, ist der Datenbestand inkonsistent
    # und der Einlesevorgang muss wiederholt werden bis er fehlerfrei durchgelaufen ist.
    # Dazu Fehlerausschriften bearchten.
    $this->blocktransaction=0;
  }
	
  function open() {
  	if($this->port == '') $this->port = 5432;
    #$this->debug->write("<br>Datenbankverbindung öffnen: Datenbank: ".$this->dbName." User: ".$this->user,4);
		$connect_string = 'dbname='.$this->dbName.' port='.$this->port.' user='.$this->user.' password='.$this->passwd;
		if($this->host != 'localhost' AND $this->host != '127.0.0.1')$connect_string .= ' host='.$this->host;		// das beschleunigt den Connect extrem
    $this->dbConn=pg_connect($connect_string);
    $this->debug->write("Datenbank mit Connection_ID: ".$this->dbConn." geöffnet.",4);
    # $this->version = pg_version($this->dbConn); geht erst mit PHP 5
    $this->version = POSTGRESVERSION;
    return $this->dbConn;
  }

  function setClientEncoding() {
    $sql ="SET CLIENT_ENCODING TO '".POSTGRES_CHARSET."';";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    return $ret[1];    	
  }  

  function close() {
    $this->debug->write("<br>PostgreSQL Verbindung mit ID: ".$this->dbConn." schließen.",4);
    return pg_close($this->dbConn);
  }
	
	function read_epsg_codes($order = true){
    global $supportedSRIDs;
    $sql ="SELECT spatial_ref_sys.srid, srtext, alias, minx, miny, maxx, maxy FROM spatial_ref_sys ";
    $sql.="LEFT JOIN spatial_ref_sys_alias ON spatial_ref_sys_alias.srid = spatial_ref_sys.srid";
    # Wenn zu unterstützende SRIDs angegeben sind, ist die Abfrage diesbezüglich eingeschränkt
    $anzSupportedSRIDs = count($supportedSRIDs);
    if ($anzSupportedSRIDs > 0) {
      $sql.=" WHERE spatial_ref_sys.srid IN (".implode(',', $supportedSRIDs).")";
    }
    if($order)$sql.=" ORDER BY spatial_ref_sys.srid";
    #echo $sql;		
    $ret = $this->execSQL($sql, 4, 0);		
    if($ret[0]==0){
			$i = 0;
      while($row = pg_fetch_assoc($ret[1])){
      	if($row['alias'] != ''){
      		$row['srtext'] = $row['alias'];
      	}
      	else{
	        $explosion = explode('[', $row['srtext']);
	        if(strlen($explosion[1]) > 30){
	          $explosion[1] = substr($explosion[1], 0, 30);
	        }
	        $row['srtext'] = $explosion[1];
      	}
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
      $rs=pg_fetch_array($ret[1]);
      $curExtent->minx=$rs['minx'];
      $curExtent->miny=$rs['miny'];
      $curExtent->maxx=$rs['maxx'];
      $curExtent->maxy=$rs['maxy'];
      $ret[1]=$curExtent;
    }
    
    /*$projFROM = ms_newprojectionobj("init=epsg:".$curSRID);
		$projTO = ms_newprojectionobj("init=epsg:".$newSRID);
		$curExtent->project($projFROM, $projTO);
		$ret[0] = 0;
		$ret[1] = $curExtent;*/
    return $ret;
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
      #echo "<br>".$sql;
      $sql = "SET datestyle TO 'German';".$sql;
      if($this->schema != ''){
      	$sql = "SET search_path = ".$this->schema.", public;".$sql;
      }
      $query=pg_query($this->dbConn,$sql);
      //$query=0;
      if ($query==0) {
				$errormessage = pg_last_error($this->dbConn);
				header('error: true');		// damit ajax-Requests das auch mitkriegen
        $ret[0]=1;
        $ret[1]="Fehler bei SQL Anweisung:<br><br>\n\n".$sql."\n\n<br><br>".$errormessage;
        echo "<br><b>".$ret[1]."</b>";
        $this->debug->write("<br><b>".$ret[1]."</b>",$debuglevel);
        if ($logsql) {
          $this->logfile->write($this->commentsign." ".$ret[1]);
        }
      }
      else {
      	# Abfrage wurde erfolgreich ausgeführt
        $ret[0]=0;
        $ret[1]=$query;
        $this->debug->write("<br>".$sql,$debuglevel);
        # 2006-07-04 pk $logfile ersetzt durch $this->logfile
        if ($logsql) {
          $this->logfile->write($sql.';');
        }
      }
      $ret[2]=$sql;
    }
    else {
      # Es werden keine SQL-Kommandos ausgeführt
      # Die Funktion liefert ret[0]=0, und zeigt damit an, daß kein Datenbankfehler aufgetreten ist,
      $ret[0]=0;
      # jedoch hat $ret[1] keine query_ID sondern auch den Wert 0
      $ret[1]=0;
      # Wenn $this->loglevel != 0 wird die sql-Anweisung in die logdatei geschrieben
      # zusätzlich immer in die debugdatei
      # 2006-07-04 pk $logfile ersetzt durch $this->logfile
      if ($logsql) {
        $this->logfile->write($sql.';');
      }
      $this->debug->write("<br>".$sql,$debuglevel);
    }

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
				$filter .= ' AND '.$tablename.'.beginnt <= \''.$timestamp.'\' and (\''.$timestamp.'\' < '.$tablename.'.endet or '.$tablename.'.endet IS NULL) ';
			}
		}
		return $filter;
	}

  function transformPoly($polygon,$curSRID,$newSRID) {
    $sql ="SELECT st_astext(st_transform(st_geomfromtext('".$polygon."', ".$curSRID."), ".$newSRID."))";
    $ret=$this->execSQL($sql, 4, 0);
    if($ret[0] == 0){
      $rs=pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }

	function transformPoint($point, $curSRID, $newSRID, $coordtype){
		$sql ="SELECT st_X(point) AS x, st_Y(point) AS y";
    $sql.=" FROM (SELECT st_transform(st_geomfromtext('POINT(".$point.")',".$curSRID."),".$newSRID.") AS point) AS foo";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
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
  
  function getFieldsfromSelect($select){
  	$distinctpos = strpos(strtolower($select), 'distinct');
  	if($distinctpos !== false && $distinctpos < 10){
  		$offset = $distinctpos+8;
  	}
  	else{
    	$offset = 7;
  	}
    $select = $this->eliminate_star($select, $offset);
  	if(substr_count(strtolower($select), ' from ') > 1){
  		$whereposition = strpos($select, ' WHERE ');
  		$withoutwhere = substr($select, 0, $whereposition);
  		$fromposition = strpos($withoutwhere, ' FROM ');
  	}
  	else{
  		$whereposition = strpos(strtolower($select), ' where ');
  		$withoutwhere = substr($select, 0, $whereposition);
  		$fromposition = strpos(strtolower($withoutwhere), ' from ');
  	}
    $sql = $select." LIMIT 0";
    $ret = $this->execSQL($sql, 4, 0);
    if($ret[0]==0){
      $frompos = $fromposition;
      $attributesstring = substr($select, $offset, $frompos-$offset);
      //$fieldstring = explode(',', $attributesstring);
      $fieldstring = get_select_parts($attributesstring);
      
      for($i = 0; $i < pg_num_fields($ret[1]); $i++){
        # Attributname
        $fieldname = pg_field_name($ret[1], $i);
        $fields['name'][] = $fieldname;

        # "richtiger" Name in der Tabelle
        $name_pair = $this->check_real_attribute_name($fieldstring[$i], $fieldname);
        if($name_pair != ''){
          $fields['real_name'][$name_pair['name']] = $name_pair['real_name'];
        }
        else{
          $fields['real_name'][$fieldname] = $fieldname;
        }

        # Tabellenname des Attributs
        if(PHPVERSION >= 580){
        	$tablename = pg_field_table($ret[1], $i);
        	$table['alias'] = '';
        }
        else{
	        $table = $this->pg_field_table2($fieldname, $fieldstring[$i], $select);
	        $tablename = $table['name'];
					if($tablename == NULL AND $name_pair != ''){
	          $table = $this->pg_field_table2($name_pair['real_name'], $fieldstring[$i], $select);
	          $tablename = $table['name'];
	        }
        }
        if($tablename != NULL){
          $all_table_names[] = $tablename;
        }
        $fields['table_name'][] = $tablename;
        $fields['table_name'][$fieldname] = $tablename;
        if($table['alias'] == ''){
        	$table['alias'] = $this->get_table_alias($tablename, $fromposition, $withoutwhere);
        }
        if($table['alias']){
        	$fields['table_alias_name'][$fieldname] = $table['alias'];
        }
        else{
        	$fields['table_alias_name'][$fieldname] = $tablename;
        }

        # Attributtyp
        $fieldtype = pg_field_type($ret[1], $i);
				if($name_pair != '' AND $name_pair['no_real_attribute']) $fieldtype = 'not_saveable';
        $fields['type'][] = $fieldtype;
        # Geometrietyp
        if($fieldtype == 'geometry'){
          $fields['geomtype'][$fieldname] = $this->get_geom_type($fields['real_name'][$fieldname], $tablename);
          $fields['the_geom'] = $fieldname;
        }
        
        # Constraints
        $constraints = $this->pg_table_constraints($tablename);
        $constraintstring = '';
	      if($fieldtype != 'geometry'){
	        # testen ob es für ein Attribut ein constraint gibt, das wie enum wirkt
	        for($j = 0; $j < count($constraints); $j++){
	          if(strpos($constraints[$j], '('.$fieldname.')')){
	            $options = explode("'", $constraints[$j]);
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
	              
        $attr_info = $this->get_attribute_information($tablename, $fields['real_name'][$fieldname]);
        # nullable
        $fields['nullable'][] = $attr_info['is_nullable']; 
        
        # Länge des Feldes
        if($attr_info['numeric_precision'] != ''){
        	$fields['length'][] = $attr_info['numeric_precision'];
        }
        else{
        	$fields['length'][] = $attr_info['character_maximum_length'];
      	}        
        
        # Länge des Dezimalteils eines numeric-Feldes
        $fields['decimal_length'][] = $attr_info['numeric_scale'];
        
        # Default-Wert
        $fields['default'][] = $attr_info['column_default'];
				
				# Unique
				if($attr_info['indisunique'] == 't'){
        	$constraintstring = 'UNIQUE';
        }
				
				# Primary Key
        if($attr_info['indisprimary'] == 't'){
        	$constraintstring = 'PRIMARY KEY';
        }
        
        $fields['constraints'][] = $constraintstring;
        
      }
      
      if($all_table_names != NULL){   
	      $all_table_names = array_unique($all_table_names);
	      foreach($all_table_names as $tablename){
	        $fields['oids'][] = $this->check_oid($tablename);   # testen ob Tabelle oid hat
	        //$fields['all_alias_table_names'][] = $this->get_table_alias($tablename, $fromposition, $withoutwhere);
	      }
	      $fields['all_table_names'] = $all_table_names;
      }
            
      return $fields;
    }
    else return NULL;
  }
     
  function get_attribute_information($tablename, $columnname){
  	if($columnname != '' AND $tablename != ''){
  		$sql = "SELECT is_nullable, character_maximum_length, column_default, numeric_precision, numeric_scale, bool_or(indisunique) as indisunique, bool_or(indisprimary) as indisprimary, pg_get_serial_sequence('".$tablename."', '".$columnname."') as serial ";
  		$sql.= "FROM information_schema.columns LEFT JOIN pg_class LEFT JOIN pg_index ON indrelid = pg_class.oid LEFT JOIN pg_attribute ON pg_attribute.attrelid = pg_class.oid ON pg_class.oid = table_name::regclass AND pg_attribute.attnum = any(pg_index.indkey) AND attname = column_name ";
  		$sql.= "WHERE column_name = '".$columnname."' AND table_name = '".$tablename."' AND table_schema = '".$this->schema."' ";
			$sql.= "GROUP BY is_nullable, character_maximum_length, column_default, numeric_precision, numeric_scale";
			#echo $sql.'<br>';
  		$ret1 = $this->execSQL($sql, 4, 0);
	  	if($ret1[0]==0){
	      $attr_info = pg_fetch_assoc($ret1[1]);
	      if($attr_info['is_nullable'] == 'NO' AND $attr_info['serial'] == '' AND substr($attr_info['column_default'], 0, 7) != 'nextval'){$attr_info['is_nullable'] = '0';}else{$attr_info['is_nullable'] = '1';}
	      if($attr_info['character_maximum_length'] == NULL){$attr_info['character_maximum_length'] = 'NULL';}
	      if($attr_info['numeric_scale'] == ''){$attr_info['numeric_scale'] = 'NULL';}	      
	      if($attr_info['column_default'] != '' AND $attr_info['serial'] == '' AND substr($attr_info['column_default'], 0, 7) != 'nextval'){
		      $attr_info['column_default'] = 'SELECT '.$attr_info['column_default'];
		     	#echo $sql.'<br>';
	  			#$ret1 = $this->execSQL($sql, 4, 0);
	  			#if($ret1[0]==0){
	      		#$defaultvalue = pg_fetch_row($ret1[1]);
	      		#$attr_info['column_default'] = $defaultvalue[0];
	  			#}
	  		}
	  		else{
	  			$attr_info['column_default'] = '';
	  		}
	    }
  	}
  	else{
  		$attr_info['is_nullable'] = 'NULL';
  		$attr_info['character_maximum_length'] = 'NULL';
  		$attr_info['column_default'] = '';
  		$attr_info['numeric_scale'] = 'NULL';
  	}
  	return $attr_info;
  }


  function get_geom_type($geomcolumn, $tablename){
  	if($geomcolumn != '' AND $tablename != ''){
	    $sql = "SELECT GeometryType(".$geomcolumn.") FROM ".$tablename." WHERE ".$geomcolumn." IS NOT NULL LIMIT 1";
	    $ret1 = $this->execSQL($sql, 4, 0);
	    if($ret1[0]==0){
	      $geom_type = pg_fetch_row($ret1[1]);
	      if($geom_type[0] == ''){
	      	$sql = "SELECT type FROM geometry_columns WHERE f_table_name = '".$tablename."' AND f_geometry_column = '".$geomcolumn."'";
	    		$ret1 = $this->execSQL($sql, 4, 0);
	    		if($ret1[0]==0){
	      		$geom_type = pg_fetch_row($ret1[1]);
	    		}
	      }
	    }
	    return $geom_type[0];
  	}
  	else{
  		return NULL;
  	}
  }

  function check_oid($tablename){
    $sql = 'SELECT oid from '.$tablename.' limit 0';
    if($this->schema != ''){
    	$sql = "SET search_path = ".$this->schema.", public;".$sql;
    }
    $this->debug->write("<p>file:kvwmap class:postgresql->check_oid:<br>".$sql,4);
    @$query=pg_query($sql);
    if ($query==0) {
      return false;
    }
    else{
      return true;
    }
  }
  
  function eliminate_star($query, $offset){
  	if(substr_count(strtolower($query), ' from ') > 1){
  		$whereposition = strpos($query, ' WHERE ');
  		$withoutwhere = substr($query, 0, $whereposition);
  		$fromposition = strpos($withoutwhere, ' FROM ');
  	}
  	else{
  		$whereposition = strpos(strtolower($query), ' where ');
  		if($whereposition){
  			$withoutwhere = substr($query, 0, $whereposition);
  		}
  		else{
  			$withoutwhere = $query;
  		}
  		$fromposition = strpos(strtolower($withoutwhere), ' from ');
  	}
    $select = substr($query, $offset, $fromposition-$offset);
    $from = substr($query, $fromposition);
    $column = explode(',', $select);
    $column = get_select_parts($select);
    for($i = 0; $i < count($column); $i++){
      if(strpos(trim($column[$i]), '*') === 0 OR strpos($column[$i], '.*') !== false){
        $sql .= "SELECT ".$column[$i]." ".$from." LIMIT 0";
        $ret = $this->execSQL($sql, 4, 0);
        if($ret[0]==0){
        	$tablename = str_replace('*', '', trim($column[$i]));
          $columns = $tablename.pg_field_name($ret[1], 0);
          for($j = 1; $j < pg_num_fields($ret[1]); $j++){
            $columns .= ', '.$tablename.pg_field_name($ret[1], $j);
          }
          $query = str_replace(trim($column[$i]), $columns, $query);
        }
      }
    }
    return $query;
  }

  function check_real_attribute_name($fieldstring, $fieldname){
	    # testen ob Attributname durch 'as' umbenannt wurde
	    if(strpos(strtolower($fieldstring), ' as '.$fieldname)){
	      $fieldstring = trim($fieldstring);
	      $explosion = explode(' ', $fieldstring);
	      $klammerstartpos = strrpos($explosion[0], '(');
	      if($klammerstartpos !== false){										# eine Funktion wurde auf das Attribut angewendet
	        $klammerendpos = strpos($explosion[0], ')');
	        if($klammerendpos){
	        	$name_pair['real_name'] = substr($explosion[0], $klammerstartpos+1, $klammerendpos-$klammerstartpos-1);
	        	$name_pair['name'] = $explosion[count($explosion)-1];
	        	$name_pair['no_real_attribute'] = true;
	        }
	      }
	      elseif(strpos(strtolower($fieldstring), '||')){		# irgendwas zusammengesetztes mit ||
	      	$explosion2 = explode('||', $fieldstring);
	      	for($i = 0; $i < count($explosion2); $i++){
	      		if(strpos($explosion2[$i], "'") === false){
	      			$realname = explode('.', $explosion2[$i]);
	      			$name_pair['real_name'] = $realname[count($realname)-1];
	          	$name_pair['name'] = $explosion[count($explosion)-1];
	          	$name_pair['no_real_attribute'] = true;
	          	break;
	      		}
	      	}
	      }
	      else{ # 'irgendein String' as ...
	        $fieldname = explode('.', $explosion[0]);
	        if(strpos($fieldname[count($fieldname)-1], "'") !== false){
	          $name_pair['no_real_attribute'] = true;
	        }
	        else{		# tabellenname.attributname
	          $name_pair['real_name'] = $fieldname[count($fieldname)-1];
	          $name_pair['name'] = $explosion[count($explosion)-1];
	        }
	      }
	      return $name_pair;
	    }
	    else{
	      return NULL;
	    }
  }

  function get_table_alias($tablename, $fromposition, $withoutwhere){
    $tablealias = $tablename;
    $from = substr($withoutwhere, $fromposition);
    $tablestring = substr($from, 5);
    $tables = explode(',', trim($tablestring));
    $i = 0;
    $found = false;
    while($found == false AND $i < count($tables)){
      $tableexplosion = explode(' ', trim($tables[$i]));
      if(count($tableexplosion) > 1){
	      for($j = 0; $j < count($tableexplosion); $j++){
	      	if($tablename == $tableexplosion[$j]){
	      		if(strtolower($tableexplosion[$j+1]) == 'as'){			# Umbenennung mit AS
	      			$found = true;
	        		$tablealias = $tableexplosion[$j+2];
	      		}
	      		elseif(strtolower($tableexplosion[$j+1]) != 'on' AND strtolower($tableexplosion[$j+1]) != 'left'){	# Umbenennung ohne AS, wie z.B. beim LEFT JOIN
	      			$found = true;
	        		$tablealias = $tableexplosion[$j+1];
	      		}
	      	}
	      }
      }
      $i++;
    }
    return $tablealias;
  }

  function getfrom($query){
  	if(substr_count(strtolower($query), ' from ') > 1){
  		# wenn Sub-Selects vorhanden sind, mï¿½ssen from und where in der Hauptabfrage groï¿½ geschrieben sein
  		$whereposition = strpos($query, ' WHERE ');
	    if($whereposition != false){
	      $withoutwhere = substr($query, 0, $whereposition);
	      $fromposition = strpos($withoutwhere, ' FROM ');
	      $from = substr($withoutwhere, $fromposition+6);
	    }
	    else{
	      $fromposition = strpos($query, ' FROM ');
	      $from = substr($query, $fromposition+6);
	    }
  	}
  	else{
  		$whereposition = strpos(strtolower($query), ' where ');
	    if($whereposition != false){
	      $withoutwhere = substr($query, 0, $whereposition);
	      $fromposition = strpos(strtolower($withoutwhere), ' from ');
	      $from = substr($withoutwhere, $fromposition+6);
	    }
	    else{
	      $fromposition = strpos(strtolower($query), ' from ');
	      $from = substr($query, $fromposition+6);
	    }
  	}
  	return $from;
  }

  function pg_field_table2($columname, $fieldstring, $query){    # gibts in php 4 noch nicht, deswegen hier so handisch
   	$from = $this->getfrom($query);
    $tables = explode(',', trim($from));
    $sql = "SELECT table_name FROM information_schema.columns WHERE column_name = '".$columname."'";
    $sql.= " AND table_name IN (";
    for($i = 0; $i < count($tables); $i++){
    	$tableparts = explode(' ', $tables[$i]);
    	for($j = 0; $j < count($tableparts); $j++){
				$sql.= "'".pg_escape_string($tableparts[$j])."', ";
    	}
    }
    $schema = str_replace(',', "','", $this->schema);
    $sql.= "'bla') AND table_schema IN ('".$schema."')";
    #echo $sql.'<br><br>';
    $ret = $this->execSQL($sql,4, 0);
    if(pg_num_rows($ret[1]) == 1){
      $rs = pg_fetch_row($ret[1]);
      $tablename = $rs[0];
    }
    else{     # Tabellenname lï¿½ï¿½t sich nicht eindeutig identifizieren (entweder durch Umbenennung oder weil es mehrere Tabellen mit diesem Attribut gibt)
      $klammerstartpos = strrpos($fieldstring, '(');
      if($klammerstartpos !== false){
        return NULL;
      }
      else{
      	if(strpos($fieldstring, '.') !== false AND strpos($fieldstring, "'") === false){
        	$explosion = explode('.', trim($fieldstring));
        	$tablealias = $explosion[0];
        	if(strpos($tablealias, "'") === false){
        		$tablename = $tablealias;
        	}
	        $sql = "SELECT * FROM information_schema.tables where table_name = '".$tablename."'";
	        $ret = $this->execSQL($sql,4, 0);
			    if(pg_num_rows($ret[1]) == 0){
		        $tables = explode(',', $from);
		        $i = 0;
		        $found = false;
		        while($found == false AND $i < count($tables)){
		          $tableexplosion = explode(' ', trim($tables[$i]));
		          if(count($tableexplosion) > 1){
			          for($j = 0; $j < count($tableexplosion); $j++){
			          	if($tablealias == $tableexplosion[$j]){
			          		if(strtolower($tableexplosion[$j-1]) == 'as'){			# Umbenennung mit AS
			          			$found = true;
			            		$tablename = $tableexplosion[$j-2];
			          		}
			          		else{																								# Umbenennung ohne AS, wie z.B. beim LEFT JOIN
			          			$found = true;
			            		$tablename = $tableexplosion[$j-1];
			          		}
			          	}
			          }
		          }
		          $i++;
		        }
			    }
      	}
      }
    }
    $table['alias'] = $tablealias;
    $table['name'] = $tablename;
    return $table;
  }

  function pg_table_constraints($table){
  	if($table != ''){
	    $sql = "SELECT consrc FROM pg_constraint, pg_class WHERE contype = 'check'";
	    $sql.= " AND pg_class.oid = pg_constraint.conrelid AND pg_class.relname = '".$table."'";
	    $ret = $this->execSQL($sql, 4, 0);
	    if($ret[0]==0){
	      while($row = pg_fetch_array($ret[1])){
	        $constraints[] = $row['consrc'];
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
    $sql = 'UPDATE u_polygon SET the_geom = st_transform(st_geomfromtext(\''.$wkt_string.'\','.$srid.'), '.EPSGCODE.') WHERE id = '.$poly_id;
    #echo $sql;
    $ret = $this->execSQL($sql, 4, 0);
    return $ret;
  }

  function insertpolygon($wkt_string, $srid){
    $sql = 'INSERT into u_polygon (the_geom) VALUES (st_transform(st_geomfromtext(\''.$wkt_string.'\','.$srid.'), '.EPSGCODE.'))';
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
      $poly = pg_fetch_array($ret[1]);
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
      $rect= ms_newRectObj();
      # Abfragen und zuordnen der Koordinaten der Box
      $rs=pg_fetch_array($ret[1]);
      if ($rs['maxx']-$rs['minx']==0) {
        $rs['maxx']=$rs['maxx']+1;
        $rs['minx']=$rs['minx']-1;
      }
      if ($rs['maxy']-$rs['miny']==0) {
        $rs['maxy']=$rs['maxy']+1;
        $rs['miny']=$rs['miny']-1;
      }
      $rect->minx=$rs['minx']; $rect->miny=$rs['miny'];
      $rect->maxx=$rs['maxx']; $rect->maxy=$rs['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
  }

	function getWKTBBox($wkt, $fromsrid, $tosrid) {
    $sql ="SELECT st_xmin(geom) AS minx, st_ymin(geom) AS miny, st_xmax(geom) AS maxx, st_ymax(geom) AS maxy ";
    $sql.=" FROM (select st_extent(st_transform(st_geomfromtext('".$wkt."', ".$fromsrid."), ".$tosrid.")) as geom) as foo";
    $ret=$this->execSQL($sql,4, 0);
    if($ret[0] == 0){
      $rect= ms_newRectObj();
      $rs=pg_fetch_array($ret[1]);
      $rect->minx=$rs['minx']-30; 
			$rect->miny=$rs['miny']-30;
      $rect->maxx=$rs['maxx']+30; 
			$rect->maxy=$rs['maxy']+30;
      return $rect;
    }
  }
  
	function getBezeichnungFromPosition($position, $epsgcode) {
    $this->debug->write("<p>kataster.php Flur->getBezeichnungFromPosition:",4);
		$sql ="SELECT gm.bezeichnung as gemeindename, fl.gemeinde, gk.bezeichnung as gemkgname, fl.land::text||fl.gemarkungsnummer::text as gemkgschl, fl.flurnummer as flur, CASE WHEN fl.nenner IS NULL THEN fl.zaehler::text ELSE fl.zaehler::text||'/'||fl.nenner::text end as flurst, s.bezeichnung as strasse, l.hausnummer ";
    $sql.="FROM alkis.ax_gemarkung as gk, alkis.ax_gemeinde as gm, alkis.ax_flurstueck as fl ";
		$sql.="LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(fl.weistauf) ";
		$sql.="LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND s.lage = lpad(l.lage,5,'0') ";
    $sql.="WHERE gk.gemarkungsnummer = fl.gemarkungsnummer AND gm.kreis = fl.kreis AND gm.gemeinde = fl.gemeinde ";
    $sql.=" AND ST_WITHIN(st_transform(st_geomfromtext('POINT(".$position['rw']." ".$position['hw'].")',".$epsgcode."), ".EPSGCODE_ALKIS."),fl.wkb_geometry) ";
		$sql.= $this->build_temporal_filter(array('gk', 'gm', 'fl'));
    #echo $sql;
    $ret=$this->execSQL($sql,4, 0);
    if ($ret[0]!=0) {
      $ret[1]='Fehler bei der Abfrage der Datenbank.'.$ret[1];
    }
    else {
      if (pg_num_rows($ret[1])>0) {
        $ret[1]=pg_fetch_array($ret[1]);
      }
    }
    return $ret;
  }
	
  function getGrundbuecher($FlurstKennz, $hist_alb = false, $fiktiv = false, $without_temporal_filter = false) {
		if(rolle::$hist_timestamp != '')$sql = 'SET enable_mergejoin = OFF;';
    $sql.="SET enable_seqscan = OFF;SELECT distinct g.land * 10000 + g.bezirk as bezirk, g.buchungsblattnummermitbuchstabenerweiterung AS blatt, g.blattart ";
		if($hist_alb) $sql.="FROM alkis.ax_historischesflurstueckohneraumbezug f ";
		else $sql.="FROM alkis.ax_flurstueck f ";
		if($fiktiv){
			$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON ARRAY[f.istgebucht] <@ s.an ";
		}
		else{
			$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON f.istgebucht = s.gml_id OR ARRAY[f.gml_id] <@ s.verweistauf ";
		}
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		$sql.="WHERE f.flurstueckskennzeichen = '".$FlurstKennz."' ";
		#$sql.="AND (g.blattart = 1000 OR g.blattart = 2000 OR g.blattart = 3000) ";
		if(!$hist_alb AND !$without_temporal_filter) $sql.= $this->build_temporal_filter(array('f', 's', 'g'));
		$sql.="ORDER BY blatt";
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Grundbuch[]=$rs;
    }
    $ret[1]=$Grundbuch;
		if($Grundbuch[0]['blattart'] == 5000){			# wenn es ein fiktives Blatt ist, die untergeordneten Buchungsstellen abfragen
			$ret = $this->getGrundbuecher($FlurstKennz, $hist_alb, true);
			$ret['fiktiv'] = true;
		}
    return $ret;
  }
  
  function getBuchungenFromGrundbuch($FlurstKennz,$Bezirk,$Blatt,$hist_alb = false, $fiktiv = false, $buchungsstelle = NULL, $without_temporal_filter = false) {
    $sql ="set enable_seqscan = off;SELECT DISTINCT gem.bezeichnung as gemarkungsname, g.land * 10000 + g.bezirk as bezirk, g.bezirk as gbezirk, g.buchungsblattnummermitbuchstabenerweiterung AS blatt, g.blattart, s.gml_id, s.laufendenummer AS bvnr, ltrim(s.laufendenummer, '~>')::integer, s.buchungsart, art.bezeichner as bezeichnung, f.flurstueckskennzeichen as flurstkennz, s.zaehler::text||'/'||s.nenner::text as anteil, s.nummerimaufteilungsplan as auftplannr, s.beschreibungdessondereigentums as sondereigentum "; 
		if($FlurstKennz!='') {
			if($hist_alb) $sql.="FROM alkis.ax_historischesflurstueckohneraumbezug f ";
			else $sql.="FROM alkis.ax_flurstueck f ";  
			$sql.="LEFT JOIN alkis.ax_gemarkung gem ON f.land = gem.land AND f.gemarkungsnummer = gem.gemarkungsnummer ";
			if($fiktiv){
				$sql.="JOIN alkis.ax_buchungsstelle s ON ARRAY[f.istgebucht] <@ s.an ";
			}
			else $sql.="JOIN alkis.ax_buchungsstelle s ON f.istgebucht = s.gml_id OR ARRAY[f.gml_id] <@ s.verweistauf ";
			
			$sql.="LEFT JOIN alkis.ax_buchungsstelle_buchungsart art ON s.buchungsart = art.wert ";
			$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		}
		else{
			$sql.="FROM alkis.ax_buchungsblatt g ";
			$sql.="LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk ";
			$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON s.istbestandteilvon = g.gml_id ";
			$sql.="LEFT JOIN alkis.ax_flurstueck f ON f.istgebucht = s.gml_id OR f.gml_id = ANY(s.verweistauf) OR f.istgebucht = ANY(s.an) ";
			$sql.="LEFT JOIN alkis.ax_gemarkung gem ON f.land = gem.land AND f.gemarkungsnummer = gem.gemarkungsnummer ";
			$sql.="LEFT JOIN alkis.ax_buchungsstelle_buchungsart art ON s.buchungsart = art.wert ";		
		}
		$sql.="WHERE 1=1 ";
		if ($Bezirk!='') {
      $sql.=" AND b.schluesselgesamt=".$Bezirk;
		}
		if ($Blatt!='') {
			$sql.=" AND g.buchungsblattnummermitbuchstabenerweiterung='".$Blatt."'";
		}
    if ($FlurstKennz!='') {
      $sql.=" AND f.flurstueckskennzeichen='".$FlurstKennz."'";
    }
		if ($buchungsstelle!='') {
      $sql.=" AND s.gml_id='".$buchungsstelle."'";
    }		
		if(!$hist_alb AND !$without_temporal_filter) $sql.= $this->build_temporal_filter(array('f', 's', 'g'));
    $sql.=" ORDER BY g.bezirk,g.buchungsblattnummermitbuchstabenerweiterung,ltrim(s.laufendenummer, '~>')::integer,f.flurstueckskennzeichen";
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Buchung[]=$rs;
    }
    $ret[1]=$Buchung;
    return $ret;
  }
 
  function getGemeindeListeByGemIDByGemkgSchl($ganzeGemID, $GemkgID){
    $sql ="SELECT DISTINCT pp.schluesselgesamt as GemkgID, pp.gemarkungsname as Name, gem.bezeichnung as gemeindename, gem.schluesselgesamt as gemeinde ";
    $sql.="FROM alkis.ax_gemeinde AS gem, alkis.pp_gemarkung as pp ";
    $sql.="WHERE pp.gemeinde=gem.gemeinde AND pp.kreis=gem.kreis ";
		if($ganzeGemID[0]!='' OR $GemkgID[0]!=''){
			$sql.="AND (FALSE ";
			if($ganzeGemID[0]!=''){
				$sql.=" OR gem.schluesselgesamt IN (".implode(',', $ganzeGemID).")";
			}
			if($GemkgID[0]!=''){
				$sql.=" OR pp.schluesselgesamt IN (".implode(',', $GemkgID).")";
			}
			$sql.=")";
		}
    $sql.=" ORDER BY pp.gemarkungsname";
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['GemkgID'][]=$rs['gemkgid'];
      $Liste['Name'][]=$rs['name'];
      $Liste['gemeinde'][]=$rs['gemeinde'];
      $Liste['Bezeichnung'][]=$rs['name']." (".$rs['gemkgid'].") ".$rs['gemeindename'];
    }
    return $Liste;
  }
    
  function getGemeindeListeByKreisGemeinden($Gemeinden){
    $sql ="SELECT DISTINCT g.schluesselgesamt AS id, g.bezeichnung AS name";
    $sql.=" FROM alkis.ax_gemeinde AS g WHERE 1=1";
    if(is_array($Gemeinden)){
			$sql.=" AND g.schluesselgesamt IN (".implode(',', $Gemeinden).")";
    }
		$sql.= $this->build_temporal_filter(array('g'));
    $sql.=" ORDER BY bezeichnung";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_array($ret[1])) {
      $GemeindeListe['ID'][]=$rs['id'];
      $GemeindeListe['Name'][]=$rs['name'];
    }
    return $GemeindeListe;
  }
  
  function getFlurstuecksListe($GemID,$GemkgID,$FlurID, $historical = false){
		if(!$historical){
			$sql ="SELECT flurstueckskennzeichen as flurstkennz, zaehler, nenner";
			$sql.=" FROM alkis.ax_flurstueck WHERE 1=1";
			if ($GemkgID>0) {
				$sql.=" AND land*10000 + gemarkungsnummer= ".$GemkgID;
			}
			if ($FlurID!='') {
				$sql.=" AND flurnummer=".$FlurID;
			}
			$sql.= $this->build_temporal_filter(array('ax_flurstueck'));
			$sql.=" ORDER BY flurstueckskennzeichen";
		}
		else{
			$sql = "SELECT distinct flurstueckskennzeichen as flurstkennz, zaehler, nenner ";
			$sql.= "FROM alkis.ax_flurstueck, alkis.ax_fortfuehrungsfall WHERE 1=1 ";
			$sql.= "AND land*10000 + gemarkungsnummer = ".$GemkgID." ";
			$sql.= "AND flurnummer = ".$FlurID." ";
			$sql.= "AND flurstueckskennzeichen = ANY(zeigtaufaltesflurstueck) ";
			$sql.= "AND NOT flurstueckskennzeichen = ANY(zeigtaufneuesflurstueck) ";
			$sql.= "AND ax_flurstueck.endet IS NOT NULL ";
			$sql.= "UNION ";
			$sql.= "SELECT flurstueckskennzeichen as flurstkennz, zaehler, nenner ";
			$sql.= "FROM alkis.ax_historischesflurstueckohneraumbezug WHERE 1=1 ";
			$sql.= "AND land*10000 + gemarkungsnummer = ".$GemkgID." ";
			$sql.= "AND flurnummer = ".$FlurID." ";
			$sql.= "ORDER BY flurstkennz";
		}
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlstID'][]=$rs['flurstkennz'];
      $FlstNr=intval($rs['zaehler']);
      if ($rs['nenner']!='') { $FlstNr.="/".intval($rs['nenner']); }
      $Liste['FlstNr'][]=$FlstNr;
    }
    return $Liste;
  }
	
	function getFlurstueckByLatLng($latitude, $longitude) {
		$sql  = "SELECT flst.land, flst.kreis, flst.gemeinde, flst.gemarkungsnummer, gemkg.bezeichnung AS gemarkungname, flst.flurnummer, flst.zaehler, flst.nenner, lpad(flst.land::text,2,'0')||lpad(flst.gemarkungsnummer::text,4,'0')||'-'||lpad(flst.flurnummer::text,3,'0')||'-'||lpad(flst.zaehler::text,5,'0')||'/'||CASE WHEN flst.nenner IS NULL THEN '000' ELSE lpad(flst.nenner::text,3,'0') END||'.00' AS flurstkennz, flst.flurstueckskennzeichen, flst.zaehler::text||CASE WHEN flst.nenner IS NULL THEN '' ELSE '/'||flst.nenner::text END AS flurstuecksnummer FROM alkis.ax_flurstueck AS flst, alkis.ax_gemarkung AS gemkg WHERE (flst.land::text||lpad(flst.gemarkungsnummer::text,4,'0'))::integer = gemkg.schluesselgesamt AND flst.gemarkungsnummer = gemkg.gemarkungsnummer AND ST_within(ST_transform(ST_GeomFromText('POINT(".$longitude." ".$latitude.")', 4326), ST_srid(flst.wkb_geometry)), flst.wkb_geometry);";
		$sql.= $this->build_temporal_filter(array('flst', 'gemkg'));
		#echo $sql.'<br>';
    $queryret = $this->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($queryret[1]);		
		return $rs;
	}
  
  function getFlurstKennzListeByGemSchlByStrSchl($GemeindeSchl,$StrassenSchl,$HausNr) {
  	$sql.=" SELECT f.flurstueckskennzeichen as flurstkennz";
    $sql.=" FROM alkis.ax_gemeinde as g, alkis.ax_flurstueck as f";
    $sql.=" JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(f.weistauf)";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND l.lage = lpad(s.lage,5,'0')";
    $sql.=" WHERE g.gemeinde = l.gemeinde";
    if ($HausNr!='') {
    	if($HausNr == 'ohne'){
    		$HausNr = '';
    	}
    	if(strpos($HausNr, ', ') !== false){							# wenn mehrere Hausnummern:					1, 2, 3a, 4
    		$HausNr = str_replace(", ", "','", $HausNr);		# Hochkommas dazwischen hinzufï¿½gen: 1','2','3a','4
    		$sql.=" AND g.schluesselgesamt||'-'||l.lage||'-'||TRIM(".HAUSNUMMER_TYPE."(l.hausnummer)) IN ('".$HausNr."')";		# und noch die ï¿½uï¿½eren:      			 '1','2','3a','4'
    	}
    	else{
      	$sql.=" AND g.schluesselgesamt||'-'||l.lage||'-'||TRIM(".HAUSNUMMER_TYPE."(l.hausnummer))='".$HausNr."'";
    	}
    }
    else{
    	$sql.=" AND g.schluesselgesamt=".(int)$GemeindeSchl;
    	$sql.=" AND l.lage='".$StrassenSchl."'";
    }
		$sql.= $this->build_temporal_filter(array('g', 'f', 'l', 's'));
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
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
    $sql.="WHERE f.flurstueckskennzeichen IS NOT NULL AND b.schluesselgesamt = ".$bezirk." AND g.buchungsblattnummermitbuchstabenerweiterung = '".$blatt."'";
		$sql.= $this->build_temporal_filter(array('b', 'g', 's', 'f'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
        $FlurstKennz[]=$rs['flurstkennz'];
      }
      $ret[1]=$FlurstKennz;
    }
    return $FlurstKennz;
  }
  
  function getALBData($FlurstKennz, $without_temporal_filter = false){		
		$sql ="SELECT distinct f.gml_id, 0 as hist_alb, lpad(f.flurnummer::text, 3, '0') as flurnr, f.amtlicheflaeche as flaeche, f.abweichenderrechtszustand, zaehler, nenner, k.schluesselgesamt AS kreisid, k.bezeichnung as kreisname, gem.schluesselgesamt as gemkgschl, gem.bezeichnung as gemkgname, g.schluesselgesamt as gemeinde, g.bezeichnung as gemeindename,d.stelle as finanzamt, d.bezeichnung AS finanzamtname, zeitpunktderentstehung::date as entsteh, f.beginnt::timestamp, f.endet::timestamp ";
		$sql.="FROM alkis.ax_kreisregion AS k, alkis.ax_gemeinde as g, alkis.ax_gemarkung AS gem, alkis.ax_flurstueck AS f ";
		$sql.="LEFT JOIN alkis.ax_dienststelle as d ON d.stellenart = 1200 AND d.stelle::integer = ANY(f.stelle) ";
		$sql.="WHERE f.gemarkungsnummer=gem.gemarkungsnummer AND f.land = gem.land AND f.kreis = g.kreis AND f.gemeinde = g.gemeinde AND f.kreis = k.kreis AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		if(!$without_temporal_filter)$sql.= $this->build_temporal_filter(array('k', 'g', 'gem', 'f'));
		else{
			$sql.= " UNION ";
			$sql.= "SELECT distinct f.gml_id, 1 as hist_alb, lpad(f.flurnummer::text, 3, '0') as flurnr, f.amtlicheflaeche as flaeche, '' as abweichenderrechtszustand, zaehler, nenner, 0 AS kreisid, '' as kreisname, gem.schluesselgesamt as gemkgschl, gem.bezeichnung as gemkgname, g.schluesselgesamt as gemeinde, g.bezeichnung as gemeindename, '' as finanzamt, '' AS finanzamtname, zeitpunktderentstehung::date as entsteh, f.beginnt::timestamp, f.endet::timestamp ";
			$sql.= "FROM alkis.ax_historischesflurstueckohneraumbezug as f ";
			$sql.= "LEFT JOIN alkis.ax_gemarkung AS gem ON f.gemarkungsnummer=gem.gemarkungsnummer AND f.land = gem.land ";
			$sql.= "LEFT JOIN alkis.pp_gemarkung ppg ON gem.land = ppg.land AND gem.gemarkungsnummer = ppg.gemarkung ";
			$sql.= "LEFT JOIN alkis.ax_gemeinde g ON f.gemeinde=g.gemeinde AND ppg.kreis = g.kreis ";
			$sql.= "WHERE f.flurstueckskennzeichen='".$FlurstKennz."'";
			$sql.=" order by endet DESC";		# damit immer die jüngste Version eines Flurstücks gefunden wird
		}		
    #echo $sql.'<br><br>';
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=0;
      $ret[1]=$queryret[1];
    }
    else{
			$rs=pg_fetch_array($queryret[1]);
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
			if($GemeindenStelle['ganze_gemeinde'] != NULL)$sql.=" OR (g.land::text||g.regierungsbezirk::text||lpad(g.kreis::text, 2, '0')||lpad(g.gemeinde::text, 3, '0'))::integer IN (".implode(',', array_keys($GemeindenStelle['ganze_gemeinde'])).")";
			if($GemeindenStelle['ganze_gemarkung'] != NULL)$sql.=" OR f.land*10000 + f.gemarkungsnummer IN (".implode(',', array_keys($GemeindenStelle['ganze_gemarkung'])).")";
			if($GemeindenStelle['eingeschr_gemarkung'] != NULL){
				foreach($GemeindenStelle['eingeschr_gemarkung'] as $eingeschr_gemkg_id => $fluren){
					$sql.=" OR (f.land*10000 + f.gemarkungsnummer = ".$eingeschr_gemkg_id." AND flurnummer IN (".implode(',', $fluren)."))";
				}
			}
			$sql .= ")";
		}		
		$sql.="UNION ";
		$sql.="SELECT f.flurstueckskennzeichen as flurstkennz FROM alkis.ax_flurstueck AS f ";
		$sql.="WHERE f.flurstueckskennzeichen IN ('".implode("','", $FlurstKennz)."') ";
		if($GemeindenStelle != NULL){
			$sql.="AND (FALSE";
			if($GemeindenStelle['ganze_gemeinde'] != NULL)$sql.=" OR (f.land::text||f.regierungsbezirk::text||lpad(f.kreis::text, 2, '0')||lpad(f.gemeinde::text, 3, '0'))::integer IN (".implode(',', array_keys($GemeindenStelle['ganze_gemeinde'])).")";
			if($GemeindenStelle['ganze_gemarkung'] != NULL)$sql.=" OR f.land*10000 + f.gemarkungsnummer IN (".implode(',', array_keys($GemeindenStelle['ganze_gemarkung'])).")";
			if($GemeindenStelle['eingeschr_gemarkung'] != NULL){
				foreach($GemeindenStelle['eingeschr_gemarkung'] as $eingeschr_gemkg_id => $fluren){
					$sql.=" OR (f.land*10000 + f.gemarkungsnummer = ".$eingeschr_gemkg_id." AND flurnummer IN (".implode(',', $fluren)."))";
				}
			}
			$sql .= ")";
		}
    $this->debug->write("<p>postgresql.php getFlurstuecksKennzByGemeindeIDs() Abfragen erlaubten Flurstückskennzeichen nach Gemeindeids:<br>".$sql,4);
		#echo $sql;
    $query=pg_query($sql);
    if ($query==0) {
      $ret[0]=1; $ret[1]="Fehler bei der Abfrage der zur Anzeige erlaubten Flurstücke";
      $this->debug->write("<br>Abbruch in postgresql.php getFlurstuecksKennzByGemeindeIDs Zeile: ".__LINE__."<br>sql: ".$sql,4);
      return $ret;
    }
    while($rs=pg_fetch_array($query)) {
      $ret[1][]=$rs["flurstkennz"];
    }
    return $ret;
  }
  
  function getStrassen($FlurstKennz) {
    $sql ="set enable_seqscan = off;SELECT DISTINCT g.schluesselgesamt as gemeinde, g.bezeichnung as gemeindename, l.lage as strasse, s.bezeichnung as strassenname ";
    $sql.="FROM alkis.ax_gemeinde as g, alkis.ax_flurstueck as f ";
    $sql.="JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(f.weistauf) ";
    $sql.="LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND s.lage = l.lage ";
    $sql.="WHERE g.gemeinde = l.gemeinde AND g.kreis = l.kreis AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('g', 'f', 'l', 's'));
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      while($rs=pg_fetch_array($queryret[1])) {
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
      $rs=pg_fetch_array($queryret[1]);
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
    $sql.="WHERE l.lage='".$Strasse."' AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'l'));
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      while($rs=pg_fetch_array($queryret[1])) {
        $HausNr[]=$rs[0];
      }
      $ret[1]=$HausNr;
    }
    return $ret;
  }

  function getLage($FlurstKennz) {
    # liefert die Lage des Flurstückes
    $sql = "SELECT l.unverschluesselt, s.bezeichnung ";
		$sql.= "FROM alkis.ax_flurstueck as f ";
		$sql.= "JOIN alkis.ax_lagebezeichnungohnehausnummer l ON l.gml_id = ANY(f.zeigtauf)  ";
		$sql.= "LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND l.lage=s.lage ";
		$sql.= "WHERE f.flurstueckskennzeichen = '".$FlurstKennz."'";
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
        while($rs=pg_fetch_array($queryret[1])) {
          $Lage[]= $rs['unverschluesselt'].$rs['bezeichnung'];
        }
      }
      $ret[1]=$Lage;
    }
    return $ret;
  }
  
  function getNutzung($FlurstKennz) {
    $sql ="SELECT round((st_area_utm(st_intersection(n.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric * amtlicheflaeche / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID."))::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche, nas.nutzungsartengruppe::text||nas.nutzungsart::text||nas.untergliederung1::text||nas.untergliederung2::text as nutzungskennz, nag.gruppe||' '||coalesce(na.nutzungsart, '')||' '||coalesce(nu1.untergliederung1, '')||' '||coalesce(nu2.untergliederung2, '') as bezeichnung, n.info, n.zustand, n.name, amtlicheflaeche";
		$sql.=" FROM alkis.ax_flurstueck f, alkis.n_nutzung n";
		$sql.=" left join alkis.n_nutzungsartenschluessel nas on n.nutzungsartengruppe = nas.nutzungsartengruppe and n.werteart1 = nas.werteart1 and n.werteart2 = nas.werteart2";
		$sql.=" left join alkis.n_nutzungsartengruppe nag on nas.nutzungsartengruppe = nag.schluessel";
		$sql.=" left join alkis.n_nutzungsart na on nas.nutzungsartengruppe = na.nutzungsartengruppe and nas.nutzungsart = na.schluessel";
		$sql.=" left join alkis.n_untergliederung1 nu1 on nas.nutzungsartengruppe = nu1.nutzungsartengruppe and nas.nutzungsart = nu1.nutzungsart and nas.untergliederung1 = nu1.schluessel";
		$sql.=" left join alkis.n_untergliederung2 nu2 on nas.nutzungsartengruppe = nu2.nutzungsartengruppe and nas.nutzungsart = nu2.nutzungsart and nas.untergliederung1 = nu2.untergliederung1 and nas.untergliederung2 = nu2.schluessel";
		$sql.=" WHERE st_intersects(n.wkb_geometry,f.wkb_geometry) = true";
		$sql.=" AND st_area_utm(st_intersection(n.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001";
		$sql.=" AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f','n'));
		#echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0] OR pg_num_rows($queryret[1])==0) {
      # keine Eintragungen zu Nutzungen gefunden
      return $queryret;
    }
    $summe = 0;
		$groesste = 0;
		$i = 0;
    while($rs=pg_fetch_array($queryret[1])) {
			$summe += $rs['flaeche'];
			if($groesste < $rs['flaeche']){
				$groesste = $rs['flaeche'];
				$index = $i;
			}
      $Nutzungen[]=$rs;
			$i++;
    }
		$diff = $Nutzungen[$i-1]['amtlicheflaeche'] - $summe;
		$Nutzungen[$index]['flaeche'] += $diff;
    $ret[0]=0;
    $ret[1]=$Nutzungen;
    return $ret;
  }

	function getSonstigesrecht($FlurstKennz) {
    $sql ="SELECT round((st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  a.bezeichner as art, fo.name";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_sonstigesrecht fo ";
		$sql.=" LEFT JOIN alkis.ax_sonstigesrecht_artdf a ON a.wert=fo.artderfestlegung";		
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $Sonstigesrecht[]=$rs;
      }
    }
    $ret[1]=$Sonstigesrecht;
    return $ret;
  }
	
	function getDenkmalschutzrecht($FlurstKennz) {
    $sql ="SELECT round((sum(st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche))::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  a.bezeichner as art, fo.name";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_denkmalschutzrecht fo ";
		$sql.=" LEFT JOIN alkis.ax_denkmalschutzrecht_artdf a ON a.wert=fo.artderfestlegung";		
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		$sql.=" GROUP BY a.bezeichner, fo.name, f.amtlicheflaeche ";
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $Denkmalschutzrecht[]=$rs;
      }
    }
    $ret[1]=$Denkmalschutzrecht;
    return $ret;
  }
	
	function getBauBodenrecht($FlurstKennz) {
    $sql ="SELECT distinct round((st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  a.bezeichner as art, fo.bezeichnung, s.bezeichnung as stelle";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_bauraumoderbodenordnungsrecht fo ";
		$sql.=" LEFT JOIN alkis.ax_bauraumoderbodenordnungsrecht_artderfestlegung a ON a.wert=fo.artderfestlegung";
		$sql.=" LEFT JOIN alkis.ax_dienststelle s ON s.stelle = fo.stelle";
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $BauBodenrecht[]=$rs;
      }
    }
    $ret[1]=$BauBodenrecht;
    return $ret;
  }
	
	function getNaturUmweltrecht($FlurstKennz) {
    $sql ="SELECT round((st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  a.bezeichner as art";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_naturumweltoderbodenschutzrecht fo ";
		$sql.=" LEFT JOIN alkis.ax_naturumweltoderbodenschutzrecht_artdf a ON a.wert=fo.artderfestlegung";		
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $NaturUmweltrecht[]=$rs;
      }
    }
    $ret[1]=$NaturUmweltrecht;
    return $ret;
  }
	
	function getSchutzgebiet($FlurstKennz) {
    $sql ="SELECT round((st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  coalesce(a.bezeichner, b.bezeichner) as art";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_schutzzone fo ";
		$sql.=" LEFT JOIN alkis.ax_schutzgebietnachwasserrecht c ON fo.istteilvon = c.gml_id";
		$sql.=" LEFT JOIN alkis.ax_schutzgebietnachwasserrecht_artdf a ON a.wert = c.artderfestlegung";
		$sql.=" LEFT JOIN alkis.ax_schutzgebietnachnaturumweltoderbodenschutzrecht d ON fo.istteilvon = d.gml_id";
		$sql.=" LEFT JOIN alkis.ax_schutzgebietnachnaturumweltoderbodenschutzrecht_artdf b ON b.wert = d.artderfestlegung";
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $Schutzgebiet[]=$rs;
      }
    }
    $ret[1]=$Schutzgebiet;
    return $ret;
  }
		
	function getWasserrecht($FlurstKennz) {
    $sql ="SELECT round((st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  a.bezeichner as art, '' as bezeichnung";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_klassifizierungnachwasserrecht fo ";
		$sql.=" LEFT JOIN alkis.ax_klassifizierungnachwasserrecht_artdf a ON a.wert=fo.artderfestlegung";
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		$sql.=" UNION SELECT round((st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  a.bezeichner as art, s.bezeichnung";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_anderefestlegungnachwasserrecht fo ";
		$sql.=" LEFT JOIN alkis.ax_anderefestlegungnachwasserrecht_artdf a ON a.wert=fo.artderfestlegung";
		$sql.=" LEFT JOIN alkis.ax_dienststelle s ON s.stelle = fo.stelle";
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $Strassenrecht[]=$rs;
      }
    }
    $ret[1]=$Strassenrecht;
    return $ret;
  }
	
	function getStrassenrecht($FlurstKennz) {
    $sql ="SELECT round((st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  a.bezeichner as art, bezeichnung";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_klassifizierungnachstrassenrecht fo ";
		$sql.=" LEFT JOIN alkis.ax_klassifizierungnachstrassenrecht_artdf a ON a.wert=fo.artderfestlegung";
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $Strassenrecht[]=$rs;
      }
    }
    $ret[1]=$Strassenrecht;
    return $ret;
  }
	
	function getForstrecht($FlurstKennz) {
    $sql ="SELECT round((st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.")::numeric / st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") * f.amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche,  a.bezeichner as art, b.bezeichner as funktion ";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_forstrecht fo ";
		$sql.=" LEFT JOIN alkis.ax_forstrecht_artderfestlegung a ON a.wert=fo.artderfestlegung";
		$sql.=" LEFT JOIN alkis.ax_forstrecht_besonderefunktion b ON b.wert=fo.besonderefunktion";
    $sql.=" WHERE st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(fo.wkb_geometry,f.wkb_geometry), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $Forstrecht[]=$rs;
      }
    }
    $ret[1]=$Forstrecht;
    return $ret;
  }
	
	function getStrittigeGrenze($FlurstKennz) {
    $sql ="SELECT bf.gml_id";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_besondereflurstuecksgrenze bf ";
    $sql.=" WHERE st_covers(f.wkb_geometry, bf.wkb_geometry) = true  AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.=" AND 1000 = ANY(artderflurstuecksgrenze)";
		$sql.= $this->build_temporal_filter(array('f', 'bf'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $strittigeGrenze[]=$rs;
      }
    }
    $ret[1]=$strittigeGrenze;
    return $ret;
  }

  function getKlassifizierung($FlurstKennz) {
    $sql ="SELECT amtlicheflaeche, round((fl_geom / flstflaeche * amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche, fl_geom, flstflaeche, n.wert, objart, ARRAY_TO_STRING(ARRAY[k.kurz, b.kurz, z.kurz, e1.kurz, e2.kurz, s.kurz, n.bodenzahlodergruenlandgrundzahl || '/' || n.wert], ' ') as label ";
		$sql.=" FROM (SELECT amtlicheflaeche, st_area_utm(st_intersection(n.wkb_geometry, st_intersection(be.wkb_geometry,f.wkb_geometry)), 25833, 6384000, 38) as fl_geom, st_area_utm(f.wkb_geometry, 25833, 6384000, 38) as flstflaeche, n.bodenzahlodergruenlandgrundzahl, n.ackerzahlodergruenlandzahl as wert, n.kulturart as objart, n.kulturart, n.bodenart, n.entstehungsartoderklimastufewasserverhaeltnisse, n.zustandsstufeoderbodenstufe, n.sonstigeangaben";
    $sql.=" FROM alkis.ax_flurstueck f, alkis.ax_bewertung be, alkis.ax_bodenschaetzung n ";		
    $sql.=" WHERE st_intersects(n.wkb_geometry,f.wkb_geometry) = true AND st_intersects(be.wkb_geometry,f.wkb_geometry) = true AND st_area_utm(st_intersection(n.wkb_geometry, st_intersection(be.wkb_geometry,f.wkb_geometry)), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.", ".M_QUASIGEOID.") > 0.001 AND f.flurstueckskennzeichen='".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('f', 'be', 'n'));
		$sql.=" ) as n";
		$sql.=" LEFT JOIN alkis.ax_bodenschaetzung_kulturart k ON k.wert=n.kulturart";
		$sql.=" LEFT JOIN alkis.ax_bodenschaetzung_bodenart b ON b.wert=n.bodenart";
		$sql.=" LEFT JOIN alkis.ax_bodenschaetzung_entstehungsartoderklimastufe e1 ON e1.wert=n.entstehungsartoderklimastufewasserverhaeltnisse[1]";
		$sql.=" LEFT JOIN alkis.ax_bodenschaetzung_entstehungsartoderklimastufe e2 ON e2.wert=n.entstehungsartoderklimastufewasserverhaeltnisse[2]";
		$sql.=" LEFT JOIN alkis.ax_bodenschaetzung_zustandsstufe z ON z.wert=n.zustandsstufeoderbodenstufe";
		$sql.=" LEFT JOIN alkis.ax_bodenschaetzung_sonstigeangaben s ON s.wert=n.sonstigeangaben[1]";
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
			$summe_amt = 0;
			$summe_geom = 0;
			$groesste = 0;
			$i = 0;
      while($rs=pg_fetch_array($ret[1])){
				$summe_amt += $rs['flaeche'];
				$summe_geom += $rs['fl_geom'];
				if($groesste < $rs['fl_geom']){
					$groesste = $rs['fl_geom'];
					$index = $i;
				}
        $Klassifizierung[]=$rs;
				$i++;
      }
			$Klassifizierung['nicht_geschaetzt'] = round(($Klassifizierung[$i-1]['flstflaeche'] - $summe_geom) * $Klassifizierung[$i-1]['amtlicheflaeche'] / $Klassifizierung[$i-1]['flstflaeche']);			
			$summe_amt += $Klassifizierung['nicht_geschaetzt'];
			$diff = $Klassifizierung[$i-1]['amtlicheflaeche'] - $summe_amt;
			$Klassifizierung[$index]['flaeche'] += $diff;
    }
    $ret[1]=$Klassifizierung;
    return $ret;
  }
	  
	function getNachfolger($FlurstKennz) {
		$sql = "SELECT DISTINCT ON (nachfolger) nachfolger, c.endet FROM (";
    $sql.= "SELECT unnest(zeigtaufneuesflurstueck) as nachfolger FROM alkis.ax_fortfuehrungsfall WHERE ARRAY['".$FlurstKennz."'::varchar] <@ zeigtaufaltesflurstueck AND NOT ARRAY['".$FlurstKennz."'::varchar] <@ zeigtaufneuesflurstueck) as foo ";
		$sql.= "LEFT JOIN alkis.ax_flurstueck c ON c.flurstueckskennzeichen = nachfolger ORDER BY nachfolger, c.endet DESC";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
			if(pg_num_rows($queryret[1]) == 0){		# kein Fortführungsfall unter ALKIS -> Suche in ALB-Historie
				$sql = "SELECT nachfolger, bool_and(CASE WHEN b.flurstueckskennzeichen IS NULL THEN NULL ELSE TRUE END) as hist_alb, min(CASE WHEN c.endet IS NULL THEN '' ELSE c.endet END) as endet FROM (";
				$sql.= "SELECT unnest(a.nachfolgerflurstueckskennzeichen) as nachfolger FROM alkis.ax_historischesflurstueckohneraumbezug as a WHERE a.flurstueckskennzeichen = '".$FlurstKennz."') as foo ";
				$sql.= "LEFT JOIN alkis.ax_historischesflurstueckohneraumbezug b ON b.flurstueckskennzeichen = nachfolger ";
				$sql.= "LEFT JOIN alkis.ax_flurstueck c ON c.flurstueckskennzeichen = nachfolger ";			# falls ein Nachfolger in ALKIS historisch ist (endet IS NOT NULL)
				$sql.= "GROUP BY nachfolger ORDER BY nachfolger";																														# damit aber immer nur die jüngste Version eines Flurstücks gefunden wird
				$queryret=$this->execSQL($sql, 4, 0);	
				while($rs=pg_fetch_array($queryret[1])){
					$Nachfolger[]=$rs;
				}
			}
			else{
				while($rs=pg_fetch_array($queryret[1])){
					$Nachfolger[]=$rs;
				}
			}
      $ret[0]=0;
      $ret[1]=$Nachfolger;
    }
    return $ret;
  }

  function getVorgaenger($FlurstKennz) {
    $sql = "SELECT unnest(zeigtaufaltesflurstueck) as vorgaenger, array_to_string(array_agg(bezeichner), ';') as anlass FROM alkis.ax_fortfuehrungsfall, alkis.ax_fortfuehrungsanlaesse WHERE ARRAY['".$FlurstKennz."'::varchar] <@ zeigtaufneuesflurstueck AND NOT ARRAY['".$FlurstKennz."'::varchar] <@ zeigtaufaltesflurstueck AND wert = ANY(ueberschriftimfortfuehrungsnachweis) GROUP BY zeigtaufaltesflurstueck ORDER BY vorgaenger";
    $queryret=$this->execSQL($sql, 4, 0);
    if($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else{
			if(pg_num_rows($queryret[1]) == 0){			# kein Vorgänger unter ALKIS -> Suche in ALB-Historie
				$sql = "SELECT flurstueckskennzeichen as vorgaenger, TRUE as hist_alb FROM alkis.ax_historischesflurstueckohneraumbezug WHERE ARRAY['".$FlurstKennz."'::varchar] <@ nachfolgerflurstueckskennzeichen ORDER BY vorgaenger";
				$queryret=$this->execSQL($sql, 4, 0);
				while($rs=pg_fetch_array($queryret[1])) {
					$Vorgaenger[]=$rs;
				}
			}
			else{
				while($rs=pg_fetch_array($queryret[1])) {
					$Vorgaenger[]=$rs;
				}
			}
      $ret[0]=0;
      $ret[1]=$Vorgaenger;
    }
    return $ret;
  }
	
	function getVersionen($table, $gml_ids){
		$sql = "SELECT beginnt::timestamp, endet::timestamp, bezeichner as anlass, '".$table."' as table FROM alkis.".$table." LEFT JOIN alkis.ax_fortfuehrungsanlaesse ON wert = NULLIF(anlass, '')::integer WHERE gml_id IN ('".implode("','", $gml_ids)."') ORDER BY beginnt";
		$queryret=$this->execSQL($sql, 4, 0);
		while($rs=pg_fetch_assoc($queryret[1])) {
			$versionen[]=$rs;
		}
		return $versionen;
	}
  
  function getEigentuemerliste($FlurstKennz,$Bezirk,$Blatt,$BVNR, $without_temporal_filter = false) {
    $sql = "SELECT distinct case when bestehtausrechtsverhaeltnissenzu is not null or n.beschriebderrechtsgemeinschaft is not null or n.artderrechtsgemeinschaft is not null then true else false end as order1, coalesce(n.laufendenummernachdin1421, lpad(split_part(n.nummer, '.', 1), 4, '0')||'.'||lpad(split_part(n.nummer, '.', 2), 2, '0')||'.'||lpad(split_part(n.nummer, '.', 3), 2, '0')||'.'||lpad(split_part(n.nummer, '.', 4), 2, '0'), '0') as order2, CASE WHEN n.beschriebderrechtsgemeinschaft is null and n.artderrechtsgemeinschaft is null THEN n.laufendenummernachdin1421 ELSE NULL END AS namensnr, n.gml_id as n_gml_id, p.gml_id, p.nachnameoderfirma, p.vorname, p.akademischergrad, p.namensbestandteil, p.geburtsname, p.geburtsdatum::date, anschrift.gml_id as anschrift_gml_id, anschrift.strasse, anschrift.hausnummer, anschrift.postleitzahlpostzustellung, anschrift.ort_post, 'OT '||anschrift.ortsteil as ortsteil, anschrift.bestimmungsland, w.bezeichner as Art, n.zaehler||'/'||n.nenner as anteil, coalesce(NULLIF(n.beschriebderrechtsgemeinschaft, ''),adrg.artderrechtsgemeinschaft) as zusatz_eigentuemer ";
		$sql.= "FROM alkis.ax_buchungsstelle s ";
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		$sql.="LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk ";
		$sql.= "LEFT JOIN alkis.ax_namensnummer n ON n.istbestandteilvon = g.gml_id ";
		$sql.= "LEFT JOIN alkis.lk_ax_artderrechtsgemeinschaft adrg ON n.artderrechtsgemeinschaft = adrg.wert ";
		$sql.= "LEFT JOIN alkis.ax_namensnummer_eigentuemerart w ON w.wert = n.eigentuemerart ";
		$sql.= "LEFT JOIN alkis.ax_person p ON n.benennt = p.gml_id ";
		$sql.= "LEFT JOIN alkis.ax_anschrift anschrift ON anschrift.gml_id = ANY(p.hat) ";
		$sql.= $this->build_temporal_filter(array('anschrift'));
		$sql.= " WHERE 1=1"; 
    if ($Bezirk!="") {
      $sql.=" AND b.schluesselgesamt=".(int)$Bezirk;
    }
    if ($Blatt!="") {
      $sql.=" AND g.buchungsblattnummermitbuchstabenerweiterung='".$Blatt."'";
    }
    if ($BVNR!="") {
      $sql.=" AND s.laufendenummer='".$BVNR."'";
    }
		if(!$without_temporal_filter)$sql.= $this->build_temporal_filter(array('s', 'g', 'b', 'n', 'p'));
    $sql.= " ORDER BY order1, order2;";
    #echo $sql.'<br><br>';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0] OR pg_num_rows($ret[1])==0) { return; }
    while ($rs=pg_fetch_array($ret[1])) {
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
      
      $Eigentuemer = new eigentuemer($Grundbuch,$rs['namensnr']);

			$Eigentuemer->gml_id = $rs['gml_id'];
      $Eigentuemer->lfd_nr=$rs['lfd_nr_name'];
      $Eigentuemer->Name[0]=$rs['nachnameoderfirma'];
      if($rs['vorname'] != '')$Eigentuemer->Name[0] .= ', '.$rs['vorname']; 
			if($rs['namensbestandteil'] != '')$Eigentuemer->Name[0] .= ', '.$rs['namensbestandteil']; 
			if($rs['akademischergrad'] != '')$Eigentuemer->Name[0] .= ', '.$rs['akademischergrad']; 				
      $Eigentuemer->Name[1] = $rs['geburtsdatum'];
			if($rs['geburtsname'] != '')$Eigentuemer->Name[1] = 'geb. '.$rs['geburtsname'].' '.$Eigentuemer->Name[1];
      $Eigentuemer->Name[2] = $rs['strasse'].' '.$rs['hausnummer'];
      $Eigentuemer->Name[3] = $rs['postleitzahlpostzustellung'].' '.$rs['ort_post'].' '.$rs['ortsteil'];
			$Eigentuemer->postleitzahlpostzustellung = $rs['postleitzahlpostzustellung'];
			$Eigentuemer->ort_post = $rs['ort_post'];
			$Eigentuemer->ortsteil = $rs['ortsteil'];
			$Eigentuemer->Name[4] = $Eigentuemer->bestimmungsland = $rs['bestimmungsland'];			
			$Eigentuemer->strasse = $rs['strasse'];
			$Eigentuemer->hausnummer = $rs['hausnummer'];
      $Eigentuemer->Anteil=$rs['anteil'];
			$Eigentuemer->anschrift_gml_id=$rs['anschrift_gml_id'];
			$Eigentuemer->zusatz_eigentuemer=$rs['zusatz_eigentuemer'];
			$Eigentuemer->n_gml_id=$rs['n_gml_id'];
      $Eigentuemerliste[]=$Eigentuemer;
    }
    $retListe[0]=0;
    $retListe[1]=$Eigentuemerliste;
    return $retListe;
  }
  
  function getNamen($formvars, $ganze_gemkg_ids, $eingeschr_gemkg_ids){
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
		$bezirk = $formvars['bezirk'];
		$blatt = $formvars['blatt'];		
		$gemkgschl = $formvars['GemkgID'];
		$flur = $formvars['FlurID'];
		$limitAnzahl = $formvars['anzahl'];
		$limitStart = $formvars['offset'];
		$caseSensitive = $formvars['caseSensitive'];
		$order = $formvars['order'];
			
    $sql = "set enable_seqscan = off;set enable_mergejoin = off;set enable_hashjoin = off;SELECT distinct p.nachnameoderfirma, p.vorname, p.namensbestandteil, p.akademischergrad, p.geburtsname, p.geburtsdatum, anschrift.strasse, anschrift.hausnummer, anschrift.postleitzahlpostzustellung, anschrift.ort_post, 'OT '||anschrift.ortsteil as ortsteil, anschrift.bestimmungsland, g.buchungsblattnummermitbuchstabenerweiterung as blatt, b.schluesselgesamt as bezirk ";
		$sql.= "FROM alkis.ax_person p ";
		$sql.= "LEFT JOIN alkis.ax_anschrift anschrift ON anschrift.gml_id = ANY(p.hat) ";
		$sql.= "LEFT JOIN alkis.ax_namensnummer n ON n.benennt = p.gml_id ";
		$sql.= "LEFT JOIN alkis.ax_namensnummer_eigentuemerart w ON w.wert = n.eigentuemerart ";
		$sql.= "LEFT JOIN alkis.ax_buchungsblatt g ON n.istbestandteilvon = g.gml_id ";
		$sql.= "LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk ";
		$sql.= "LEFT JOIN alkis.ax_buchungsstelle s ON s.istbestandteilvon = g.gml_id ";
		$sql.= "LEFT JOIN alkis.ax_flurstueck f ON f.istgebucht = s.gml_id OR f.gml_id = ANY(s.verweistauf) OR f.istgebucht = ANY(s.an) ";
		$sql.= " WHERE 1=1 ";
    if($n1 != '%%' AND $n1 != '')$sql.=" AND lower(nachnameoderfirma) LIKE lower('".$n1."') ";
		if($n2 != '%%' AND $n2 != '')$sql.=" AND lower(vorname) LIKE lower('".$n2."') ";
		if($n3 != '%%')$sql.=" AND lower(geburtsname) LIKE lower('".$n3."') ";
		if($n4 != '%%')$sql.=" AND geburtsdatum = '".$n4."' ";
		if($n5 != '%%')$sql.=" AND lower(strasse) LIKE lower('".$n5."') ";
		if($n6 != '%%')$sql.=" AND lower(replace(hausnummer, ' ', '')) LIKE lower(replace('".$n6."', ' ', '')) ";
		if($n7 != '%%')$sql.=" AND lower(postleitzahlpostzustellung) LIKE lower('".$n7."') ";
		if($n8 != '%%')$sql.=" AND lower(ort_post) LIKE lower('".$n8."') ";

    if($bezirk!='') {
      $sql.=" AND b.schluesselgesamt=".(int)$bezirk;
    }
    if($blatt != ''){
      $sql.=" AND g.buchungsblattnummermitbuchstabenerweiterung= '".$blatt."'";
    }   
    if ($gemkgschl>0) {
      $sql.=" AND f.land*10000 + f.gemarkungsnummer = ".$gemkgschl;
    }    
    if ($flur>0) {
      $sql.=" AND f.flurnummer = ".$flur;
    }
		if($ganze_gemkg_ids[0] != '' OR count($eingeschr_gemkg_ids) > 0){
			$sql.=" AND (FALSE ";
			if($ganze_gemkg_ids[0] != ''){
				$sql.="OR f.land*10000 + f.gemarkungsnummer IN (".implode(',', $ganze_gemkg_ids).")";
			}
			if(count($eingeschr_gemkg_ids) > 0){
				foreach($eingeschr_gemkg_ids as $eingeschr_gemkg_id => $fluren){
					$sql.=" OR (f.land*10000 + f.gemarkungsnummer = ".$eingeschr_gemkg_id." AND flurnummer IN (".implode(',', $fluren)."))";
				}
			}
			$sql.=")";
		}
		$sql.= $this->build_temporal_filter(array('p', 'anschrift', 'n', 'g', 'b'));
    if($order != ''){
    	$sql.=" ORDER BY ".$order;
    }
    if ($limitStart!='' OR $limitAnzahl!='') {
      $sql.=" LIMIT ";
      if ($limitStart!='' AND $limitAnzahl!='') {
        $sql.=$limitAnzahl." OFFSET ".$limitStart;
      }
      if ($limitStart!='' AND $limitAnzahl=='') {
        $sql.=" ALL OFFSET ".$limitStart;
      }
      if ($limitStart=='' AND $limitAnzahl!='') {
        $sql.=$limitAnzahl;
      }
    }
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	$i = 0;
      while($rs=pg_fetch_array($ret[1])) {
      	$namen[$i]=$rs;
	      $namen[$i]['name1'] = $rs['nachnameoderfirma'];
	      if($rs['vorname'] != '')$namen[$i]['name1'] .= ', '.$rs['vorname']; 
				if($rs['namensbestandteil'] != '')$namen[$i]['name1'] .= ', '.$rs['namensbestandteil']; 
				if($rs['akademischergrad'] != '')$namen[$i]['name1'] .= ', '.$rs['akademischergrad']; 				
	      $namen[$i]['name2'] = $rs['geburtsdatum'];
				if($rs['geburtsname'] != '')$namen[$i]['name2'] .= ' geb. '.$rs['geburtsname'];
	      $namen[$i]['name3'] = $rs['strasse'].' '.$rs['hausnummer'];
	      $namen[$i]['name4'] = $rs['postleitzahlpostzustellung'].' '.$rs['ort_post'].' '.$rs['ortsteil'].' '.$rs['bestimmungsland'];
        $i++;
      }
      $ret[1]=$namen;
    }
    return $ret;
  }

  function getForstamt($FlurstKennz) {
    $sql ="SELECT distinct d.stelle as schluessel, d.bezeichnung as name FROM alkis.ax_dienststelle as d, alkis.ax_flurstueck as f";
    $sql.=" WHERE d.stellenart = 1400 AND d.stelle::integer = ANY(f.stelle) AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
		$sql.= $this->build_temporal_filter(array('d', 'f'));
		#echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      if (pg_num_rows($queryret[1])>0) {
        $rs=pg_fetch_array($queryret[1]);
        $Forstamt=$rs;
      }
      else {
        $Forstamt['name']='ungebucht';
      }
      $ret[1]=$Forstamt;
    }
    return $ret;
  }
	
	function getAmtsgerichtby($flurstkennz, $bezirk){
		$sql ="SELECT a.bezeichnung as name, a.stelle as schluessel";
		$sql.=" FROM alkis.ax_buchungsblattbezirk b , alkis.ax_dienststelle a";
		$sql.=" WHERE b.land=a.land AND b.stelle=a.stelle AND a.stellenart=1000";
		$sql.=" AND b.schluesselgesamt = ".$bezirk['schluessel'];
		$sql.= $this->build_temporal_filter(array('b', 'a'));
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $ret[1]=pg_fetch_array($queryret[1]);
    }
    return $ret;
	}
  
  function getGemarkungName($GemkgSchl) {
    $sql ="SELECT bezeichnung as gemkgname FROM alkis.ax_gemarkung WHERE land*10000 + gemarkungsnummer = ".$GemkgSchl;
		$sql.= $this->build_temporal_filter(array('ax_gemarkung'));
    $this->debug->write("<p>postgres.sql getGemarkungName Abfragen des Gemarkungsnamen:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=$rs['gemkgname'];
    }
    return $ret;
  }
	
	function getGrundbuchblattliste($bezirk){
		$sql = "SELECT buchungsblattnummermitbuchstabenerweiterung as blatt FROM alkis.ax_buchungsblatt WHERE land*10000 + bezirk = ".$bezirk." AND (blattart = 1000 OR blattart = 2000 OR blattart = 3000) ";
		$sql.= $this->build_temporal_filter(array('ax_buchungsblatt'));
		$sql.= " ORDER BY rtrim(ltrim(buchungsblattnummermitbuchstabenerweiterung,'PF0'),'ABCDEFGHIJKLMNOPQRSTUVWXYZ')::integer";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
      	$liste['blatt'][]=$rs['blatt'];
    	}
    }
    return $liste;
	}
	
	function getGrundbuchblattlisteByGemkgIDs($bezirk, $ganze_gemkg_ids, $eingeschr_gemkg_ids){
		$sql = "SELECT DISTINCT buchungsblattnummermitbuchstabenerweiterung as blatt, rtrim(ltrim(buchungsblattnummermitbuchstabenerweiterung,'PF0'),'ABCDEFGHIJKLMNOPQRSTUVWXYZ')::integer ";
		$sql.="FROM alkis.ax_flurstueck f ";
		$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON f.istgebucht = s.gml_id OR f.istgebucht = ANY(s.an) OR f.gml_id = ANY(s.verweistauf) ";		
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		$sql.="WHERE g.land*10000 + g.bezirk = ".$bezirk." AND (blattart = 1000 OR blattart = 2000 OR blattart = 3000) AND (FALSE ";		
		if($ganze_gemkg_ids[0] != ''){
			$sql.="OR f.land*10000 + f.gemarkungsnummer IN (".implode(',', $ganze_gemkg_ids).")";
		}
		if(count($eingeschr_gemkg_ids) > 0){
			foreach($eingeschr_gemkg_ids as $eingeschr_gemkg_id => $fluren){
				$sql.=" OR (f.land*10000 + f.gemarkungsnummer = ".$eingeschr_gemkg_id." AND flurnummer IN (".implode(',', $fluren)."))";
			}
		}
		$sql.= ")";
		$sql.= $this->build_temporal_filter(array('f', 's', 'g'));
		$sql.= " ORDER BY rtrim(ltrim(buchungsblattnummermitbuchstabenerweiterung,'PF0'),'ABCDEFGHIJKLMNOPQRSTUVWXYZ')::integer";
		#echo $sql;
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
      	$liste['blatt'][]=$rs['blatt'];
    	}
    }
    return $liste;
	}
  
  function getGrundbuchbezirksliste(){
  	$sql ="SELECT schluesselgesamt as grundbuchbezschl, bezeichnung FROM alkis.ax_buchungsblattbezirk WHERE 1=1";
		$sql.= $this->build_temporal_filter(array('ax_buchungsblattbezirk'));
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
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
			$sql.="OR f.land*10000 + f.gemarkungsnummer IN (".implode(',', $ganze_gemkg_ids).")";
		}
		if(count($eingeschr_gemkg_ids) > 0){
			foreach($eingeschr_gemkg_ids as $eingeschr_gemkg_id => $fluren){
				$sql.=" OR (f.land*10000 + f.gemarkungsnummer = ".$eingeschr_gemkg_id." AND flurnummer IN (".implode(',', $fluren)."))";
			}
		}
		$sql.= ")";
		$sql.= $this->build_temporal_filter(array('f', 's', 'g', 'b'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
      	$liste['schluessel'][]=$rs['grundbuchbezschl'];
      	$liste['bezeichnung'][]=$rs['bezeichnung'];
      	$liste['beides'][]=$rs['bezeichnung'].' ('.$rs['grundbuchbezschl'].')';
    	}
    }
    return $liste;
  }
    
  function getGrundbuchbezirke($FlurstKennz, $hist_alb = false) {
		$sql ="SELECT b.schluesselgesamt as Schluessel, b.bezeichnung AS Name ";
		if($hist_alb) $sql.="FROM alkis.ax_historischesflurstueckohneraumbezug f ";
		else $sql.="FROM alkis.ax_flurstueck f ";  
		//$sql.="LEFT JOIN alkis.ax_buchungsstelle s2 ON array[f.istgebucht] <@ s2.an ";
		//$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON f.istgebucht = s.gml_id OR array[f.istgebucht] <@ s.an OR array[f.istgebucht] <@ s2.an AND array[s2.gml_id] <@ s.an ";
		$sql.="JOIN alkis.ax_buchungsstelle s ON f.istgebucht = s.gml_id ";
		$sql.="LEFT JOIN alkis.ax_buchungsstelle_buchungsart art ON s.buchungsart = art.wert ";
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id "; 
		$sql.="LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk ";
		$sql.="WHERE f.flurstueckskennzeichen = '".$FlurstKennz."'";
		if(!$hist_alb) $sql.= $this->build_temporal_filter(array('f', 's', 'g', 'b'));
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0] OR pg_num_rows($ret[1])==0){
      $Bezirk['name']="nicht gefunden";
      $Bezirk['schluessel']="0";
    }
    else{
      $Bezirk=pg_fetch_array($ret[1]);
    }
    return $Bezirk;
  }
  
  function getHausNrListe($GemID,$StrID,$HausNr,$PolygonWKTString,$order) {
    # 2006-01-31
    $order='ordernr, nrtext';
    # Abfragen der Hausnummern
    $sql ="SELECT id,nrtext, to_number(ordernr, '999999') as ordernr FROM (";
    $sql.="SELECT DISTINCT CASE WHEN TRIM(nr)='' THEN 'ohne' ELSE LOWER(id) END AS id, CASE WHEN TRIM(nr)='' THEN 'ohne Nr' ELSE TRIM(nr) END AS nrtext";
    $sql.=",(CASE WHEN TRIM(ordernr)='' THEN '0' ELSE SPLIT_PART(TRIM(ordernr),' ',1) END) as ordernr FROM (";
    $sql.=" SELECT DISTINCT '".$GemID."-".$StrID."-'||TRIM(".HAUSNUMMER_TYPE."(l.hausnummer)) AS id, ".HAUSNUMMER_TYPE."(l.hausnummer) AS nr, l.hausnummer AS ordernr";
    $sql.=" FROM alkis.ax_gemeinde as g, alkis.ax_lagebezeichnungmithausnummer l";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND l.lage = lpad(s.lage,5,'0')";
    $sql.=" WHERE g.gemeinde = l.gemeinde";
    if ($GemID!='') {
      $sql.=" AND g.schluesselgesamt=".(int)$GemID;
    }
    if ($StrID!='') {
      $sql.=" AND l.lage='".$StrID."'";
    }
		$sql.= $this->build_temporal_filter(array('g', 'l', 's'));
    $sql.=") AS foo ";
    $sql.=") AS foofoo ORDER BY ".$order;
    #echo $sql;
    $this->debug->write("<p>postgres getHausNrListe Abfragen der Strassendaten:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['HausID'][]=$rs['id'];
      $Liste['HausNr'][]=$rs['nrtext'];
    }
    return $Liste;
  }
    
  function getStrassenListe($GemID,$GemkgID,$PolygonWKTString) {		
	# Hier bitte nicht auf die Idee kommen, die Strassen ohne die Flurstücke abfragen zu können. 
	# Die Flurstücke müssen miteinbezogen werden, weil wir ja auch über die Gemarkung auswählen wollen.	
  	$sql ="set enable_seqscan = off;SELECT -1 AS gemeinde,'-1' AS strasse,'--Auswahl--' AS strassenname, '' as gemkgname";
    $sql.=" UNION";
    $sql.=" SELECT DISTINCT g.gemeinde, l.lage as strasse, s.bezeichnung as strassenname, gem.bezeichnung as gemkgname";
    $sql.=" FROM alkis.ax_gemeinde as g, alkis.ax_gemarkung as gem, alkis.ax_flurstueck as f";
    $sql.=" JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(f.weistauf)";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND s.lage = lpad(l.lage,5,'0')";
		$sql.=" WHERE g.gemeinde = f.gemeinde AND g.kreis=f.kreis AND f.gemarkungsnummer = gem.gemarkungsnummer AND f.gemeinde = l.gemeinde";
    if ($GemID!='') {
      $sql.=" AND g.schluesselgesamt=".(int)$GemID;
    }
    if ($GemkgID!='') {
      $sql.=" AND f.land*10000 + f.gemarkungsnummer=".(int)$GemkgID;
    }
		$sql.= $this->build_temporal_filter(array('g', 'gem', 'f', 'l', 's'));
    $sql.=" ORDER BY gemeinde, strassenname";
    #echo $sql;
    $this->debug->write("<p>postgres getStrassenListe Abfragen der Strassendaten:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    $i = 0;
    while ($rs=pg_fetch_array($queryret[1])) {
    	if($namen[$i-1] == $rs['strassenname'] AND $Liste['StrID'][$i-1] == $rs['strasse']){
    		# Strasse doppelt drin -> ï¿½berspringen
    		$i = $i-1;
    	}
    	else{
	      $Liste['Gemeinde'][]=$rs['gemeinde'];
	      $Liste['StrID'][]=$rs['strasse'];
	      $Liste['Gemarkung'][]=$rs['gemkgname'];
	      $namen[]=$rs['strassenname'];		# eigentlichen Strassennamen sichern
	      if($Liste['Name'][$i-1] == $rs['strassenname']){
	      	$Liste['Name'][$i-1]=$Liste['Name'][$i-1].' ('.$Liste['Gemarkung'][$i-1].')';
	      	$Liste['Name'][$i]=$rs['strassenname'].' ('.$rs['gemkgname'].')';
	      }
	      else{
	      	$Liste['Name'][]=$rs['strassenname'];
	      }
    	}
      $i++;
    }
    return $Liste;
  }
        
  function getFlurenListeByGemkgIDByFlurID($GemkgID,$FlurID, $historical = false){
		if(!$historical){
			$sql ="SELECT gemarkungsteilflur AS FlurID, lpad(gemarkungsteilflur::text, 3, '0') AS Name";
			$sql.=",schluesselgesamt AS GemFlurID FROM alkis.ax_gemarkungsteilflur WHERE anlass != '300700'";
			
			if ($GemkgID>0) {
				$sql.=" AND land*10000 + gemarkung=".(int)$GemkgID;
			}
			if ($FlurID[0]>0) {
				$sql.=" AND gemarkungsteilflur IN (".implode(',', $FlurID).")";
			}
			$sql.= $this->build_temporal_filter(array('ax_gemarkungsteilflur'));
			$sql.=" ORDER BY gemarkungsteilflur";
		}
		else{		// die Fluren aller historischen Flurstücke abfragen
			$sql = "SELECT distinct flurnummer, lpad(flurnummer::text, 3, '0') AS FlurID, lpad(flurnummer::text, 3, '0') AS Name, land*10000000 + gemarkungsnummer*1000 + flurnummer AS GemFlurID ";
			$sql.= "FROM alkis.ax_historischesflurstueckohneraumbezug WHERE 1=1 AND land*10000 + gemarkungsnummer = ".(int)$GemkgID." ";
			$sql.= "UNION ";
			$sql.= "SELECT flurnummer, lpad(flurnummer::text, 3, '0') AS FlurID, lpad(flurnummer::text, 3, '0') AS Name, land*10000000 + gemarkungsnummer*1000 + flurnummer AS GemFlurID ";
			$sql.= "FROM alkis.ax_flurstueck, alkis.ax_fortfuehrungsfall WHERE ax_flurstueck.endet is NOT NULL AND land*10000 + gemarkungsnummer = ".(int)$GemkgID." ";
			$sql.= "AND flurstueckskennzeichen = ANY(zeigtaufaltesflurstueck) ";
			$sql.= "AND NOT flurstueckskennzeichen = ANY(zeigtaufneuesflurstueck) ";
			$sql.= "ORDER BY flurnummer";
		}
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlurID'][]=$rs['flurid'];
      $Liste['Name'][]=intval($rs['name']);
      $Liste['GemFlurID'][]=$rs['gemflurid'];
    }
    return $Liste;
  }
			
	function check_poly_in_flur($polygon, $epsg){
		$sql = "SELECT f.land * 10000 + f.gemarkungsnummer, f.flurnummer FROM alkis.ax_flurstueck f WHERE st_intersects(wkb_geometry, st_transform(st_geomfromtext('".$polygon."', ".$epsg."), ".EPSGCODE_ALKIS."))";
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
      $rs=pg_fetch_array($ret[1]);
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
      $rs=pg_fetch_array($ret[1]);
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
    $sql.=" WHERE land*10000 + gemarkung = ".$Gemarkung;
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschliessenden Rechtecks um die Gemarkung.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
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
    $sql.=" WHERE land*10000 + gemarkung = ".$Gemarkung;
    $sql.=" AND flurnummer = ".(int)$Flur;
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschliessenden Rechtecks um die Flur.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
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
	
	
  function getMERfromFlurstuecke($flurstkennz, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromFlurstuecke, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Flurstï¿½cke",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkis.ax_flurstueck AS f";
    $sql.=" WHERE 1=1";
    $anzflst=count($flurstkennz);
    if ($anzflst>0) {
      $sql.=" AND f.flurstueckskennzeichen IN ('".$flurstkennz[0]."'";
      for ($i=1;$i<$anzflst;$i++) {
        $sql.=",'".$flurstkennz[$i]."'";
      }
      $sql.=")";
    }
		$sql.= $this->build_temporal_filter(array('f'));
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschliessenden Rechtecks um die Flurstücke.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Flurstïück nicht in Postgres Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }
  
  function getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromGebaeude, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Gebaeude",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkis.ax_gemeinde gem, alkis.ax_gebaeude g";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(g.zeigtauf) "; 
		$sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde";
		$sql.=" AND l.lage = lpad(s.lage,5,'0')";
		$sql.=" WHERE gem.gemeinde = l.gemeinde";
    if ($Hausnr!='') {
    	$Hausnr = str_replace(", ", ",", $Hausnr);
    	$Hausnr = strtolower(str_replace(",", "','", $Hausnr));    	
      $sql.=" AND gem.schluesselgesamt||'-'||l.lage||'-'||TRIM(LOWER(l.hausnummer)) IN ('".$Hausnr."')";
    }
    else{
	    $sql.=" AND gem.schluesselgesamt=".(int)$Gemeinde;
	    if ($Strasse!='') {
	      $sql.=" AND l.lage='".$Strasse."'";
	    }
    }
		$sql.= $this->build_temporal_filter(array('gem', 'g', 'l', 's'));
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschliessenden Rechtecks um die Gebäude.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Geb&auml;ude nicht in Postgres Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
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

  function getMetadataQuickSearch($md){
    $sql ="SELECT DISTINCT m.oid,m.* FROM md_metadata AS m, md_keywords AS k, md_keywords2metadata AS k2m";
    $sql.=" WHERE m.id=k2m.metadata_id AND k2m.keyword_id=k.id";
    if ($md['was']!='') {
      $sql.=" AND (";
      $sql.="restitle LIKE '%".$md['was']."%'";
      $sql.=" OR (k.keyword LIKE '%".$md['was']."%' AND k.keytyp='theme')";
      $sql.=")";
    }
    if ($md['wer']!='') {
      $sql.=" AND (rporgname LIKE '%".$md['wer']."%'";
      $sql.="   OR linkage LIKE '%".$md['wer']."%')";
    }
    if ($md['wo']!='') {
      $sql.=" AND (k.keyword LIKE '%".$md['wo']."%' AND k.keytyp='place')";
    }
    if ($md['vonwann']!='') {
      $sql.=" AND validtill >= '".$md['vonwann']."'";
    }
    if ($md['biswann']!='') {
      $sql.=" AND validfrom <= '".$md['biswann']."'";
    }
    if ($md['northbl']!='') {
      # Umringspolygon fï¿½r die Suche in der Datenbank aus den ï¿½bergebenen Koordinaten zusammensetzen
      $md['umring'] ='POLYGON(('.$md['eastbl'].' '.$md['southbl'].','.$md['westbl'].' '.$md['southbl'];
      $md['umring'].=','.$md['westbl'].' '.$md['northbl'].','.$md['eastbl'].' '.$md['northbl'];
      $md['umring'].=','.$md['eastbl'].' '.$md['southbl'].'))';
      # sql-Teil fï¿½r rï¿½umliche Abfrage bilden
      $sql.=" AND the_geom && st_geometryfromtext('".$md['umring']."',".EPSGCODE.") AND st_intersects(the_geom,st_geometryfromtext('".$md['umring']."',".EPSGCODE."))";
    }
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
        # Abfragen und Zuweisen der Keywortbezeichnungen
        $theme=$this->getKeywords('','','theme','',$rs['id'],'keyword');
        $themes=$theme[1]['keyword'];
        $rs['themekeywords']=$themes[0];
        for ($i=1;$i<count($themes);$i++) {
          $rs['themekeywords'].=', '.$themes[$i];
        }
        $place=$this->getKeywords('','','place','',$rs['id'],'keyword');
        $places=$place[1]['keyword'];
        $rs['placekeywords']=$places[0];
        for ($i=1;$i<count($places);$i++) {
          $rs['placekeywords'].=', '.$places[$i];
        }
        $mdresult[]=$rs;
      }
      $ret[1]=$mdresult;
    }
    return $ret;
  }

  function getKeywords($id,$keyword,$keytyp,$thesaname,$metadata_id,$order) {
    # letzte ï¿½nderung 2005-11-29 pk
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
    $sql.=" ORDER BY ".$order;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      # Fehler beim Abfragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnten die Schlagwï¿½rter nicht abgefragt werden!\n'.$ret[1];
    }
    else {
      while($rs=pg_fetch_array($ret[1])) {
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
    $sql ="SELECT oid,* FROM md_metadata WHERE (1=1)";
    if ($md['oid']!='') {
      $sql.=" AND oid=".(int)$md['oid'];
    }
    if ($md['mdfileid']!='') {
      $sql.=" AND mdfileid=".(int)$md['mdfileid'];
    }
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
        $mdresult[]=$rs;
      }
      $ret[1]=$mdresult;
    }
    return $ret;
  }

##################################################
# Funktionen fï¿½r administrative Grenzen
##################################################
  function truncateAdmKreise() {
    $sql ="TRUNCATE adm_landkreise";
    return $this->execSQL($sql, 4, 0);
  }

  function insertAdmKreis($colnames,$row) {
    $sql ="INSERT INTO adm_landkreis";
    $sql.=" ('".$colnames[0];
    for ($i=1;$i<count($row);$i++) { $sql.=",".$colnames[$i];}
    $sql.=")";
    $sql.=" VALUES ('".$row[0]."'";
    for ($i=1;$i<count($row);$i++) { $sql.=",'".$row[$i]."'";}
    $sql.=")";
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
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('START TRANSACTION',4, 1);
    }
    return $ret;
  }

  function rollbacktransaction() {
    # Rï¿½ckgï¿½ngigmachung aller bisherigen ï¿½nderungen in der Transaktion
    # und Abbrechen der Transaktion
    # rolls back the current transaction and causes all the updates
    # made by the transaction to be discarded
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('ROLLBACK',4, 1);
    }
    return $ret;
  }

  function committransaction() {
    # Gï¿½ltigmachen und Beenden der Transaktion
    # commits the current transaction. All changes made by the transaction
    # become visible to others and are guaranteed to be durable if a crash occurs
    if ($this->blocktransaction==0) {
      $ret=$this->execSQL('COMMIT',4, 1);
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

}





















