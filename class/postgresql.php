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
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de                #
###################################################################
##################################################
# Klasse Datenbank fï¿½r ALB Modell und PostgreSQL #
##################################################
class pgdatabase extends pgdatabase_core {
  var $ist_Fortfuehrung;
  var $debug;
  var $loglevel;
  var $defaultloglevel;
  var $logfile;
  var $defaultlogfile;
  var $commentsign;
  var $blocktransaction;

  # Fï¿½r eine Liste der verfï¿½gbaren Funktionen siehe:
  # https://kvwmap.geoinformatik.uni-rostock.de/PHPDoc/apidoc/pgdatabase.html
  # Liste ggf. mit https://kvwmap.geoinformatik.uni-rostock.de/PHPDoc/ aktualisieren

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

  function getFlurstByNutzungen($gemkgschl, $nutzung, $anzahl){
  	$sql = "SELECT f.flurstkennz, f.flaeche, gk.gemkgname, nutz.flaeche AS nutzflaeche, '21-' || nutz.nutzungsart AS nutzkennz, art.bezeichnung ";
  	$sql.= "FROM alb_v_gemarkungen AS gk, alb_flurstuecke AS f ";
  	$sql.= "LEFT JOIN alb_f_nutzungen nutz LEFT JOIN alb_v_nutzungsarten art ON nutz.nutzungsart = art.nutzungsart ON nutz.flurstkennz = f.flurstkennz ";
    $sql.= "WHERE f.gemkgschl=gk.gemkgschl ";
    if($gemkgschl){
    	$sql.= "AND gk.gemkgschl='".$gemkgschl."' ";
    }
    if($nutzung){
	    $sql.=" AND art.bezeichnung LIKE '".$nutzung."'";
    }
    $sql .= " ORDER BY nutz.nutzungsart";
    if($anzahl){
	    $sql.=" Limit ".$anzahl;
    }
    $query=$this->execSQL($sql, 4, 0);
    while($ret=pg_fetch_array($query[1])){
    	$rs[1][] = $ret;
    }
    return $rs;
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
      $ret[1]=utf8_encode($rs['x'].' '.$rs['y']);
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
	        if($tablename == NULL AND $name_pair['real_name'] != ''){
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
        if($name_pair['no_real_attribute']) $fieldtype = 'not_saveable';
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
  		$sql = "SELECT is_nullable, character_maximum_length, column_default, numeric_precision, numeric_scale, indisprimary, pg_get_serial_sequence('".$tablename."', '".$columnname."') as serial ";
  		$sql.= "FROM information_schema.columns LEFT JOIN pg_class LEFT JOIN pg_index ON indrelid = pg_class.oid LEFT JOIN pg_attribute ON pg_attribute.attrelid = pg_class.oid ON pg_class.oid = table_name::regclass AND pg_attribute.attnum = any(pg_index.indkey) AND attname = column_name ";
  		$sql.= "WHERE column_name = '".$columnname."' AND table_name = '".$tablename."' AND table_schema = '".$this->schema."' ";
  		$ret1 = $this->execSQL($sql, 4, 0);
	  	if($ret1[0]==0){
	      $attr_info = pg_fetch_assoc($ret1[1]);
	      if($attr_info['is_nullable'] == 'NO' AND $attr_info['serial'] == '' AND substr($attr_info['column_default'], 0, 7) != 'nextval'){$attr_info['is_nullable'] = '0';}else{$attr_info['is_nullable'] = '1';}
	      if($attr_info['character_maximum_length'] == NULL){$attr_info['character_maximum_length'] = 'NULL';}
	      if($attr_info['numeric_scale'] < 1){$attr_info['numeric_scale'] = 'NULL';}	      
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
	      else{ # tabellenname.attributname
	        $fieldname = explode('.', $explosion[0]);
	        if(strpos($fieldname[count($fieldname)-1], "'") !== false){
	          return '';
	        }
	        else{
	          $name_pair['real_name'] = $fieldname[count($fieldname)-1];
	          $name_pair['name'] = $explosion[count($explosion)-1];
	        }
	      }
	      return $name_pair;
	    }
	    else{
	      return '';
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
      	$sql.= "'".str_replace("'", "\'", $tableparts[$j])."', ";
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
      $rect->minx=$rs['minx']; $rect->miny=$rs['miny'];
      $rect->maxx=$rs['maxx']; $rect->maxy=$rs['maxy'];
      return $rect;
    }
  }
  
  function read_epsg_codes(){
    global $supportedSRIDs;
    $sql ="SELECT spatial_ref_sys.srid, srtext, alias FROM spatial_ref_sys ";
    $sql.="LEFT JOIN spatial_ref_sys_alias ON spatial_ref_sys_alias.srid = spatial_ref_sys.srid";
    # Wenn zu unterstï¿½tzende SRIDs angegeben sind, ist die Abfrage diesbezï¿½glich eingeschrï¿½nkt
    $anzSupportedSRIDs = count($supportedSRIDs);
    if ($anzSupportedSRIDs > 0) {
      $sql.=" WHERE auth_srid IN (".$supportedSRIDs[0];
      for ($i=1;$i<$anzSupportedSRIDs;$i++) {
        $sql.=",".$supportedSRIDs[$i];
      }
      $sql.=")";
    }
    $sql.=" ORDER BY spatial_ref_sys.srid";
    #echo $sql;
    $ret = $this->execSQL($sql, 4, 0);
    if($ret[0]==0){
      while($row = pg_fetch_array($ret[1])){
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
        $epsg_codes[] = $row;
      }
    }
    return $epsg_codes;
  }

  function getbaudaten($searchvars){
    if($searchvars['distinct'] == 1){
      $sql = 'SELECT DISTINCT feld1, feld2, feld3, feld8, feld11, feld20 FROM bau_akten WHERE 1 = 1';
    }
    else{
      $sql = 'SELECT * FROM bau_akten WHERE 1 = 1';
    }
    if($searchvars['jahr'] != ''){$sql .= " AND Feld1 = '".$searchvars['jahr']."'";}
    if($searchvars['obergruppe'] != ''){$sql .= " AND Feld2 = '".$searchvars['obergruppe']."'";}
    if($searchvars['nummer'] != ''){$sql .= " AND Feld3 = '".$searchvars['nummer']."'";}
    if($searchvars['vorhaben'] != ''){$sql .= " AND Feld8 LIKE '%".$searchvars['vorhaben']."%'";}
    if($searchvars['verfahrensart'] != ''){$sql .= " AND Feld9 LIKE '%".$searchvars['verfahrensart']."%'";}
    if($searchvars['gemarkung'] != ''){$sql .= " AND '13'||Feld12 = '".$searchvars['gemarkung']."'";}
    if($searchvars['flur'] != ''){$sql .= " AND trim(trim(leading '0' from Feld13)) = '".$searchvars['flur']."'";}
    if($searchvars['flurstueck'] != ''){$sql .= " AND trim(trim(leading '0' from Feld14)) LIKE '".$searchvars['flurstueck']."'";}
    if($searchvars['vorname'] != ''){$sql .= " AND Feld19 LIKE '%".$searchvars['vorname']."%'";}
    if($searchvars['nachname'] != ''){$sql .= " AND Feld20 LIKE '%".$searchvars['nachname']."%'";}
    if($searchvars['strasse'] != ''){$sql .= " AND Feld21 LIKE '%".$searchvars['strasse']."%'";}
    if($searchvars['hausnummer'] != ''){$sql .= " AND Feld22 LIKE '%".$searchvars['hausnummer']."%'";}
    if($searchvars['plz'] != ''){$sql .= " AND Feld23 LIKE '%".$searchvars['plz']."%'";}
    if($searchvars['ort'] != ''){$sql .= " AND Feld24 LIKE '%".$searchvars['ort']."%'";}
    if($searchvars['vonJahr'] != ''){$sql .= " AND Feld1 BETWEEN '".$searchvars['vonJahr']."' AND '".$searchvars['bisJahr']."'";}
    if($searchvars['withlimit'] == 'true'){$sql .= ' LIMIT '.$searchvars['limit'].' OFFSET '.$searchvars['offset'];}
    #echo $sql;
    $ret = $this->execSQL($sql, 4, 0);
    if($ret[0]==0){
      while($row = pg_fetch_array($ret[1])){
        $baudata[] = $row;
      }
    }
    return $baudata;
  }

  function readvorhaben(){
    $sql = 'SELECT vorhaben FROM bau_vorhaben';
    $ret = $this->execSQL($sql, 4, 0);
    if($ret[0]==0){
      while($row = pg_fetch_array($ret[1])){
        $vorhaben[] = $row;
      }
    }
    return $vorhaben;
  }

  function readverfahrensart(){
    $sql = 'SELECT verfahrensart FROM bau_verfahrensart';
    $ret = $this->execSQL($sql, 4, 0);
    if($ret[0]==0){
      while($row = pg_fetch_array($ret[1])){
        $verfahrensart[] = $row;
      }
    }
    return $verfahrensart;
  }

  function readaktualitaet(){
  	$sql = "SET datestyle TO 'German';";
	$sql.= "SELECT max(datum::date) FROM tabelleninfo WHERE thema = 'probaug'"; 
    $ret = $this->execSQL($sql, 4, 0);
    if($ret[0]==0){
      $datum = pg_fetch_array($ret[1]);
    }
    return $datum[0];
  }

  function readLastUpdateDate($DatumGrundausstattung) {
    $sql ="SELECT to_char(grundausstattung,'YYYYMMDD') AS ga_datum";
    $sql.=",to_char(ffzeitraum_bis,'YYYYMMDDHH24MISS') AS bis_letzer_zeitraum, to_char(ffzeitraum_bis,'DD.MM.YYYY') AS lastupdate";
    $sql.=" FROM alb_fortfuehrung";
    if ($DatumGrundausstattung!='') {
    	$sql.=" WHERE to_char(grundausstattung,'YYYYMMDD')='".$DatumGrundausstattung."'";
    }
    $sql.=" ORDER BY ff_timestamp DESC LIMIT 1";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $ret[1]=pg_fetch_array($ret[1]);
    }
    return $ret;
  }

  function insertAbgabeZeitraum($DatumGrundausstattung,$zeitraumvon,$zeitraumbis) {
    $sql ="INSERT INTO alb_fortfuehrung";
    $sql.=" (grundausstattung,ffzeitraum_von,ffzeitraum_bis,ff_timestamp)";
    $sql.=" VALUES ('".$DatumGrundausstattung."','".$zeitraumvon."','".$zeitraumbis;
    $sql.="','".date('Y-m-d H:i:s',time())."')";
    return $this->execSQL($sql, 4, 0);
  }

  function insertGrundbuch($Bezirk,$Blatt,$AktualitaetsNr,$Pruefzeichen) {
    $sql ="INSERT INTO alb_".$this->tableprefix."grundbuecher (SELECT '".$Bezirk."','".$Blatt."','".$Pruefzeichen."','".$AktualitaetsNr."'";
    $sql.=" WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."grundbuecher";
    $sql.=" WHERE Bezirk='".$Bezirk."' AND blatt='".$Blatt."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNeueGrundbuecher() {
    $sql ="INSERT INTO alb_grundbuecher SELECT * FROM alb_".$this->tableprefix."grundbuecher";
    $sql.=" WHERE alb_".$this->tableprefix."grundbuecher.aktualitaetsnr NOT LIKE 'hist'";
    return $this->execSQL($sql, 4, 0);
  }

  function getGrundbuecher($FlurstKennz) {
    $sql ="SELECT DISTINCT gb.bezirk,gb.blatt,gb.zusatz_eigentuemer";
    $sql.=" FROM alb_g_buchungen AS b,alb_grundbuecher AS gb";
    $sql.=" WHERE b.bezirk=gb.bezirk AND b.blatt=gb.blatt";
    $sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    $sql.=" AND b.flurstkennz='".$FlurstKennz."' ORDER BY gb.bezirk,gb.blatt";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Grundbuch[]=$rs;
    }
    $ret[1]=$Grundbuch;
    return $ret;
  }
  
  function getGrundbuecherALKIS($FlurstKennz) {
    $sql ="SELECT distinct bezirk.schluesselgesamt AS bezirk, blatt.buchungsblattnummermitbuchstabenerweiterung AS blatt";
		$sql.=" FROM alkis.ax_flurstueck f, alkis.alkis_beziehungen flst2buchung, alkis.ax_buchungsstelle buchung, alkis.alkis_beziehungen buchung2blatt, alkis.ax_buchungsblattbezirk bezirk, alkis.ax_buchungsblatt blatt";   
		$sql.=" WHERE flst2buchung.beziehungsart::text = 'istGebucht'::text AND f.gml_id = flst2buchung.beziehung_von AND flst2buchung.beziehung_zu = buchung.gml_id";
		$sql.=" AND buchung2blatt.beziehungsart::text = 'istBestandteilVon'::text AND buchung2blatt.beziehung_von = buchung.gml_id AND buchung2blatt.beziehung_zu = blatt.gml_id AND blatt.land = bezirk.land AND blatt.bezirk = bezirk.bezirk";
		$sql.=" AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Grundbuch[]=$rs;
    }
    $ret[1]=$Grundbuch;
    return $ret;
  }

  function updateGrundbuch($Bezirk,$Blatt,$Zusatz_Eigentuemer,$Bestandsflaeche) {
    $sql ="UPDATE alb_".$this->tableprefix."grundbuecher SET bezirk='".$Bezirk."'";
    if ($Zusatz_Eigentuemer!="") { $sql.=",zusatz_eigentuemer='".$Zusatz_Eigentuemer."'"; }
    if ($Bestandsflaeche!="")    { $sql.=",bestandsflaeche=".(int)$Bestandsflaeche;            }
    $sql.=" WHERE bezirk='".$Bezirk."' AND TRIM(blatt) LIKE '".$Blatt."'";
    return $this->execSQL($sql, 4, 0);
  }

  function getAnzGrundbuecher($AktualitaetsNr) {
    if ($AktualitaetsNr=="hist") { $prefix=$this->tableprefix; }
    $sql ="SELECT count(*) AS anzahl FROM alb_".$prefix."grundbuecher";
    if ($AktualitaetsNr!="") { $sql.=" WHERE aktualitaetsnr='".$AktualitaetsNr."'"; }
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  /**
  * Lï¿½schen der Grundbï¿½cher
  *
  * Diese Funktion lï¿½scht alle zu aktualisierenden Grundbï¿½cher aus der Tabelle alb_grundbuecher.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  string  $historische_loeschen Wenn der Parameter auf 1 gestetzt ist, werden die in der temporï¿½ren Tabelle alb_x_grundbuecher als historisch gekennzeichneten Grundbuecher im Bestand gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    setGrundbuecherHist(),insertGrundbuecher(), $postgres, $alb
  */
  function deleteGrundbuecher($historische_loeschen) {
    $sql ="DELETE FROM alb_grundbuecher";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."grundbuecher";
    }
    $sql.=" WHERE alb_grundbuecher.bezirk=alb_".$this->tableprefix."grundbuecher.bezirk";
    $sql.=" AND TRIM(alb_grundbuecher.blatt) LIKE TRIM(alb_".$this->tableprefix."grundbuecher.blatt)";
    if (!$historische_loeschen) {
    	$sql.=" AND alb_".$this->tableprefix."grundbuecher.aktualitaetsnr NOT LIKE 'hist'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  /**
  * Kennzeichnet Grundbï¿½cher als historisch
  *
  * Diese Funktion kennzeichnet alle Grundbï¿½cher im Bestand als historisch, die in der Fortfï¿½hrungsdatei als solche gekennzeichnet waren.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    deleteGrundbuecher(),insertGrundbuecher(), $postgres, $alb
  */
  function setGrundbuecherHist() {
  	$sql ="UPDATE alb_grundbuecher SET aktualitaetsnr='hist' FROM alb_".$this->tableprefix."grundbuecher";
    $sql.=" WHERE alb_grundbuecher.bezirk=alb_".$this->tableprefix."grundbuecher.bezirk";
    $sql.=" AND TRIM(alb_grundbuecher.blatt) LIKE TRIM(alb_".$this->tableprefix."grundbuecher.blatt)";
    $sql.=" AND alb_".$this->tableprefix."grundbuecher.aktualitaetsnr = 'hist'";
    return $this->execSQL($sql, 4, 0);
  }

  # 2006-07-04 pk (Statement geï¿½ndert fï¿½r das ignorieren von Doppelten Werten)
  # 2006-09-06 pk bugfix Buchungsart angehï¿½ngt
  function insertGrundstueck($Bezirk,$Blatt,$BVNR,$Buchungsart) {
    $sql ="INSERT INTO alb_".$this->tableprefix."g_grundstuecke (";
    $sql.="SELECT '".$Bezirk."','".$Blatt."','".$BVNR."','".$Buchungsart."'";
    $sql.=" WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."g_grundstuecke";
    $sql.=" WHERE bezirk='".$Bezirk."' AND blatt='".$Blatt."' AND bvnr='".$BVNR."'))";
    return $this->execSQL($sql, 4, 0);
  }

  /**
  * Lï¿½schen der Grundstï¿½cke der zu ï¿½ndernden Grundbï¿½cher
  *
  * Diese Funktion lï¿½scht alle Grundstuecke von zu aktualisierenden Grundbï¿½chern in der Tabelle alb_g_grundstuecke.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  string  $historische_loeschen Wenn der Parameter auf 1 gestetzt ist, werden die in der temporï¿½ren Tabelle als historisch gekennzeichneten Grundbï¿½cher fï¿½r Lï¿½schung der Grundstï¿½cke verwendet.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertGrundstueck(), updateGrundstueck(), $postgres, $alb
  */
  function deleteGrundstueckeByGrundbuecher($historische_loeschen) {
    $sql ="DELETE FROM alb_g_grundstuecke";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."grundbuecher";
    }
    $sql.=" WHERE alb_g_grundstuecke.bezirk=alb_".$this->tableprefix."grundbuecher.bezirk";
    $sql.=" AND TRIM(alb_g_grundstuecke.blatt) LIKE TRIM(alb_".$this->tableprefix."grundbuecher.blatt)";
    if (!$historische_loeschen) {
    	$sql.=" AND alb_".$this->tableprefix."grundbuecher.aktualitaetsnr NOT LIKE 'hist'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function updateGrundstueck($Bezirk,$Blatt,$BVNR,$Anteil,$AuftPlanNr,$Sondereigentum) {
    $sql ="UPDATE alb_".$this->tableprefix."g_grundstuecke SET bezirk='".$Bezirk."'";
    if ($Anteil!="")         { $sql.=",anteil='".$Anteil."'";                 }
    if ($AuftPlanNr!="")     { $sql.=",auftplannr='".$AuftPlanNr."'";         }
    if ($Sondereigentum!="") { $sql.=",sondereigentum='".$Sondereigentum."'"; }
    $sql.=" WHERE bezirk='".$Bezirk."' AND TRIM(blatt) LIKE '".$Blatt."' AND bvnr='".$BVNR."'";
    return $this->execSQL($sql, 4, 0);
  }

  function updateNewGrundstuecke() { # z.Z. nicht genutzt
    $sql ="UPDATE alb_g_grundstuecke SET";
    $sql.=" buchungsart=alb_".$this->tableprefix."g_grundstuecke.buchungsart";
    $sql.=",anteil=alb_".$this->tableprefix."g_grundstuecke.anteil";
    $sql.=",auftplannr=alb_".$this->tableprefix."g_grundstuecke.auftplannr";
    $sql.=",sondereigentum=alb_".$this->tableprefix."g_grundstuecke.sondereigentum";
#Eingefï¿½gt 11.04.2006 H. Riedel
    $sql.=" FROM alb_".$this->tableprefix."g_grundstuecke";
    $sql.=" WHERE alb_g_grundstuecke.bezirk=alb_".$this->tableprefix."g_grundstuecke.bezirk";
    $sql.=" AND TRIM(alb_g_grundstuecke.blatt) LIKE TRIM(alb_".$this->tableprefix."g_grundstuecke.blatt)";
    $sql.=" AND alb_g_grundstuecke.bvnr=alb_".$this->tableprefix."g_grundstuecke.bvnr";
    return $this->execSQL($sql, 4, 0);
  }

  function deleteNewGrundstuecke() {
    $sql ="DELETE FROM alb_g_grundstuecke";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."g_grundstuecke";
    }
    $sql.=" WHERE alb_g_grundstuecke.bezirk=alb_".$this->tableprefix."g_grundstuecke.bezirk";
    $sql.=" AND TRIM(alb_g_grundstuecke.blatt) LIKE TRIM(alb_".$this->tableprefix."g_grundstuecke.blatt)";
    $sql.=" AND alb_g_grundstuecke.bvnr=alb_".$this->tableprefix."g_grundstuecke.bvnr";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewGrundstuecke() {
    $sql ="INSERT INTO alb_g_grundstuecke SELECT * FROM alb_".$this->tableprefix."g_grundstuecke";
    return $this->execSQL($sql, 4, 0);
  }

  function insertBuchung($FlurstKennz,$Bezirk,$Blatt,$BVNR,$ErbbaurechtsHinw) {
    $sql="INSERT INTO alb_".$this->tableprefix."g_buchungen";
    $sql.=" (flurstkennz,bezirk,blatt,bvnr,ErbbaurechtsHinw)";
    $sql.=" VALUES ('".$FlurstKennz."','".$Bezirk."','".$Blatt."','".$BVNR."','".$ErbbaurechtsHinw."')";
    return $this->execSQL($sql, 4, 0);
  }

  function getBuchungen($FlurstKennz,$keine_historischen) {
    $sql ="SELECT b.bezirk,b.blatt,gb.pruefzeichen,b.bvnr,g.buchungsart,ba.bezeichnung";
    $sql.=" FROM alb_g_buchungen AS b,alb_g_grundstuecke AS g, alb_v_buchungsarten AS ba,alb_grundbuecher AS gb";
    $sql.=" WHERE b.bezirk=g.bezirk AND b.blatt=g.blatt AND b.bvnr=g.bvnr";
    $sql.=" AND b.bezirk=gb.bezirk AND b.blatt=gb.blatt AND g.buchungsart=ba.buchungsart";
    $sql.=" AND b.flurstkennz='".$FlurstKennz."' ORDER BY b.bezirk,b.blatt,b.bvnr";
    if ($keine_historischen) {
      $sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    }
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Buchung[]=$rs;
    }
    $ret[1]=$Buchung;
    return $ret;
  }

  function getBuchungenFromGrundbuch($FlurstKennz,$Bezirk,$Blatt,$keine_historischen) {
    # 28.11.2006 H.Riedel, AktualitaetsNr hinzugefuegt
    # $sql ="SELECT b.bezirk,b.blatt,gb.pruefzeichen,b.bvnr,g.buchungsart,ba.bezeichnung,b.flurstkennz";
    $sql ="SELECT b.bezirk,b.blatt,gb.pruefzeichen,b.bvnr,g.buchungsart,ba.bezeichnung,b.flurstkennz,gb.aktualitaetsnr, g.anteil, g.auftplannr, g.sondereigentum, b.erbbaurechtshinw ,gb.zusatz_eigentuemer";
    $sql.=" FROM alb_g_buchungen AS b,alb_g_grundstuecke AS g, alb_v_buchungsarten AS ba,alb_grundbuecher AS gb";
    $sql.=" WHERE b.bezirk=g.bezirk AND b.blatt=g.blatt AND b.bvnr=g.bvnr";
    $sql.=" AND b.bezirk=gb.bezirk AND b.blatt=gb.blatt AND g.buchungsart=ba.buchungsart";
    if ($keine_historischen) {
      $sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    }
    if ($Bezirk!='') {
      $sql.=" AND gb.bezirk='".$Bezirk."'";
    }
    if ($Blatt!='') {
      $sql.=" AND gb.blatt='".$Blatt."'";
    }
    if ($FlurstKennz!='') {
      $sql.=" AND b.flurstkennz='".$FlurstKennz."'";
    }
    $sql.=" ORDER BY b.bezirk,b.blatt,b.bvnr,b.flurstkennz";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Buchung[]=$rs;
    }
    $ret[1]=$Buchung;
    return $ret;
  }
  
  function getBuchungenFromGrundbuchALKIS($FlurstKennz,$Bezirk,$Blatt,$keine_historischen) {
  	// max(namensnummer.beschriebderrechtsgemeinschaft) weil es bei einer Buchung mit Zusatz zum Eigentï¿½mer einen weiteren Eintrag mit diesem Zusatz in ax_namensnummer gibt
  	// ohne Aggregation wï¿½rden sonst 2 Buchungen von der Abfrage zurï¿½ckgeliefert werden 
    $sql ="SELECT DISTINCT bezirk.schluesselgesamt AS bezirk, blatt.buchungsblattnummermitbuchstabenerweiterung AS blatt, buchung.laufendenummer AS bvnr, buchung.buchungsart, ba.bezeichner as bezeichnung, f.flurstueckskennzeichen as flurstkennz, buchung.zaehler::text||buchung.nenner::text as anteil, buchung.nummerimaufteilungsplan as auftplannr, buchung.beschreibungdessondereigentums as sondereigentum, max(namensnummer.beschriebderrechtsgemeinschaft) as zusatz_eigentuemer"; 
		$sql.=" FROM alkis.ax_flurstueck f, alkis.alkis_beziehungen flst2buchung, alkis.ax_buchungsstelle buchung, alkis.ax_buchungsstelle_buchungsart ba, alkis.alkis_beziehungen buchung2blatt, alkis.ax_buchungsblattbezirk bezirk, alkis.ax_buchungsblatt blatt, alkis.alkis_beziehungen blatt2namensnummer, alkis.ax_namensnummer namensnummer";
		$sql.=" WHERE flst2buchung.beziehungsart::text = 'istGebucht'::text AND f.gml_id = flst2buchung.beziehung_von AND flst2buchung.beziehung_zu = buchung.gml_id AND buchung2blatt.beziehungsart::text = 'istBestandteilVon'::text AND buchung2blatt.beziehung_von = buchung.gml_id AND buchung2blatt.beziehung_zu = blatt.gml_id AND blatt.land = bezirk.land AND blatt.bezirk = bezirk.bezirk AND buchung.buchungsart = ba.wert AND blatt2namensnummer.beziehungsart::text = 'istBestandteilVon'::text AND blatt2namensnummer.beziehung_zu = blatt.gml_id AND blatt2namensnummer.beziehung_von = namensnummer.gml_id";
    if ($Bezirk!='') {
      $sql.=" AND bezirk.schluesselgesamt='".$Bezirk."'";
    }
    if ($Blatt!='') {
      $sql.=" AND blatt.buchungsblattnummermitbuchstabenerweiterung='".$Blatt."'";
    }
    if ($FlurstKennz!='') {
      $sql.=" AND f.flurstueckskennzeichen='".$FlurstKennz."'";
    }
    $sql.=" GROUP BY bezirk.schluesselgesamt,blatt.buchungsblattnummermitbuchstabenerweiterung,buchung.laufendenummer,f.flurstueckskennzeichen, buchung.buchungsart, ba.bezeichner, buchung.zaehler, buchung.nenner, buchung.nummerimaufteilungsplan, buchung.beschreibungdessondereigentums";
    $sql.=" ORDER BY bezirk.schluesselgesamt,blatt.buchungsblattnummermitbuchstabenerweiterung,buchung.laufendenummer,f.flurstueckskennzeichen";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Buchung[]=$rs;
    }
    $ret[1]=$Buchung;
    return $ret;
  }

  function insertNewBuchungen() {
    $sql ="INSERT INTO alb_g_buchungen SELECT * FROM alb_".$this->tableprefix."g_buchungen";
    return $this->execSQL($sql, 4, 0);
  }

  function deleteNewBuchungen() {
    $sql ="DELETE FROM alb_g_buchungen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."g_buchungen";
    }
    $sql.=" WHERE alb_g_buchungen.bezirk=alb_".$this->tableprefix."g_buchungen.bezirk";
    $sql.=" AND TRIM(alb_g_buchungen.blatt) LIKE TRIM(alb_".$this->tableprefix."g_buchungen.blatt)";
    $sql.=" AND alb_g_buchungen.bvnr = alb_".$this->tableprefix."g_buchungen.bvnr";
    $sql.=" AND alb_g_buchungen.flurstkennz = alb_".$this->tableprefix."g_buchungen.flurstkennz";
    return $this->execSQL($sql, 4, 0);
  }

  /**
  * Lï¿½schen der Buchungen der zu ï¿½ndernden Grundbï¿½cher
  *
  * Diese Funktion lï¿½scht alle Buchungen von zu aktualisierenden Grundbï¿½chern in der Tabelle alb_g_buchungen.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean  $historische_loeschen Wenn der Parameter auf 1 gestetzt ist, werden die in der temporï¿½ren Tabelle als historisch gekennzeichneten Grundbï¿½cher fï¿½r Lï¿½schung der Buchungen verwendet.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertBuchung(), deleteBuchungenByFlurstuecke, $postgres, $alb
  */
  function deleteBuchungenByGrundbuecher($historische_loeschen) {
    $sql ="DELETE FROM alb_g_buchungen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."grundbuecher";
    }
    $sql.=" WHERE alb_g_buchungen.bezirk=alb_".$this->tableprefix."grundbuecher.bezirk";
    $sql.=" AND TRIM(alb_g_buchungen.blatt) = TRIM(alb_".$this->tableprefix."grundbuecher.blatt)";
    if (!$historische_loeschen) {
    	$sql.=" AND alb_".$this->tableprefix."grundbuecher.aktualitaetsnr NOT LIKE 'hist'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function deleteBuchungenByHistFlurstuecke() {
    $sql ="DELETE FROM alb_g_buchungen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_g_buchungen.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function getFreiText($FlurstKennz) {
    $sql ="SELECT lfdnr,text FROM alb_f_texte";
    $sql.=" WHERE flurstkennz='".$FlurstKennz."' ORDER BY lfdnr";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($queryret[1])) {
      $FreiText[]=$rs;
    }
    $ret[0]=0;
    $ret[1]=$FreiText;
    return $ret;
  }

  function is_ALK_Flurstueck($FlurstKennz) {
    $isALK=0;
    $sql ="SELECT 1 FROM alknflst WHERE flurstkennz = '".$FlurstKennz."'";
    $this->debug->write("<p><b>kataster flurstueck->is_ALK_Flurstueck Abfragen ob Flurstueck in ALK enthalten:</b><br>".$sql,4);
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret[0]=0; }
    if (pg_num_rows($ret[1])==1) {
      $isALK=1;
    }
    return $isALK;
  }

  function is_FlurstKennz($FlurstKennz){
    $sql ="SELECT flurstkennz as FlurstKennz FROM alb_flurstuecke WHERE flurstkennz = '".$FlurstKennz."'";
    $this->debug->write("<p><b>kataster flurstueck->is_FlurstKennz Abfragen ob FlurstKennz gï¿½ltig:</b><br>".$sql,4);
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret[0]=0; }
    if (pg_num_rows($ret[1])==1) {
      return $ret=1;
    }
    return $ret=0;
  }

  function is_FlurstZaehler($KennzTeil){
    $sql ="SELECT flurstkennz AS FlurstKennz FROM alb_flurstuecke WHERE flurstkennz LIKE '".$KennzTeil."%'";
    $this->debug->write("<p><b>kataster flurstueck->is_FlurstZaehler Abfrage ob FlurstKennz mit Zaehler gï¿½ltig:</b><br>".$sql,4);
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_array($ret[1])) {
      $FlstListe[]=$rs['FlurstKennz'];
    }
    return $FlstListe;
  }

  function insertFlurstueck($FlurstKennz,$GemkgSchl,$FlurNr,$Pruefzeichen) {
    $sql ="INSERT INTO alb_".$this->tableprefix."flurstuecke";
    $sql.=" (flurstkennz,gemkgschl,flurnr,pruefzeichen)";
    $sql.=" VALUES ('".$FlurstKennz."','".$GemkgSchl."','".$FlurNr."','".$Pruefzeichen."')";
    return $this->execSQL($sql, 4, 0);
  }


  function getGemeindeListeByGemIDByGemkgSchl($GemID,$GemkgID,$order){
    $sql ="SELECT DISTINCT gmk.gemkgschl AS GemkgID,gmk.gemkgname AS Name,gem.gemeindename, gem.gemeinde";
    $sql.=" FROM alb_v_gemarkungen AS gmk,alb_v_gemeinden AS gem WHERE gmk.gemeinde=gem.gemeinde";
    if ($GemID[0]!='') {
      $sql.=" AND gmk.Gemeinde IN (".$GemID[0];
      for ($i=1;$i<count($GemID);$i++) {
        $sql.=",".$GemID[$i];
      }
      $sql.=")";
    }
    if ($GemkgID[0]!='') {
      $sql.=" AND gmk.GemkgSchl IN (".$GemkgID[0];
      for ($i=1;$i<count($GemkgID);$i++) {
        $sql.=",".$GemkgID[$i];
      }
      $sql.=")";
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
    }
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
  
  function getGemeindeListeByGemIDByGemkgSchlALKIS($GemID,$GemkgID,$order){
    $sql ="SELECT DISTINCT gmk.schluesselgesamt AS GemkgID,gmk.bezeichnung AS Name,gem.bezeichnung as gemeindename, gem.schluesselgesamt as gemeinde";
    $sql.=" FROM alkis.ax_gemarkung AS gmk, alkis.ax_gemeinde AS gem, alkis.gemeinde_gemarkung as g_g ";
    $sql.="WHERE g_g.gemeinde=gem.gemeinde AND g_g.gemarkung=gmk.gemarkungsnummer";
    if ($GemID[0]!='') {
      $sql.=" AND gem.schluesselgesamt IN (".$GemID[0];
      for ($i=1;$i<count($GemID);$i++) {
        $sql.=",".$GemID[$i];
      }
      $sql.=")";
    }
    if ($GemkgID[0]!='') {
      $sql.=" AND gmk.schluesselgesamt IN (".$GemkgID[0];
      for ($i=1;$i<count($GemkgID);$i++) {
        $sql.=",".$GemkgID[$i];
      }
      $sql.=")";
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
    }
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
  
  

  function getGemeindeListeByKreisGemeinden($Gemeinden,$order){
    # 2006-01-26 pk
    $sql ="SELECT DISTINCT g.gemeinde AS id,g.gemeindename AS name";
    $sql.=" FROM alb_v_gemeinden AS g WHERE 1=1";
    if (is_array($Gemeinden)) {
      if ($Gemeinden[0]['ID']!=0 AND $Gemeinden[0]['ID']!='') {
        $sql.=" AND g.gemeinde IN (".$Gemeinden[0]['ID'];
        for ($i=1;$i<count($Gemeinden);$i++) {
          $sql.=",".$Gemeinden[$i]['ID'];
        }
        $sql.=")";
      }
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
    }
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_array($ret[1])) {
      $GemeindeListe['ID'][]=$rs['id'];
      $GemeindeListe['Name'][]=$rs['name'];
    }
    return $GemeindeListe;
  }
  
  function getGemeindeListeByKreisGemeindenALKIS($Gemeinden,$order){
    $sql ="SELECT DISTINCT g.schluesselgesamt AS id, g.bezeichnung AS name";
    $sql.=" FROM alkis.ax_gemeinde AS g WHERE 1=1";
    if (is_array($Gemeinden)) {
      if ($Gemeinden[0]['ID']!=0 AND $Gemeinden[0]['ID']!='') {
        $sql.=" AND g.schluesselgesamt IN (".$Gemeinden[0]['ID'];
        for ($i=1;$i<count($Gemeinden);$i++) {
          $sql.=",".$Gemeinden[$i]['ID'];
        }
        $sql.=")";
      }
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
    }
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_array($ret[1])) {
      $GemeindeListe['ID'][]=$rs['id'];
      $GemeindeListe['Name'][]=$rs['name'];
    }
    return $GemeindeListe;
  }

  function getGemeindeName($Gemeinde){
    $this->debug->write("<br>postgres.php->database->getGemeindeName, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Gemeinde",4);
    $sql ='SELECT g.gemeindename AS name FROM alb_v_gemeinden AS g';
    $sql.=' WHERE g.gemeinde = '.$Gemeinde;
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

  function getFlstFlaeche($FlurstKennz) {
    $sql = "SELECT flaeche AS flaeche FROM alb_flurstuecke WHERE flurstkennz = '".$FlurstKennz."'";
    $ret = $this->execSQL($sql, 4, 0);
    if ($ret[0]) { return 0; }
    $rs=pg_fetch_array($ret[1]);
    $ret[1]=$rs['flaeche'];
    return $ret;
  }

   function getFlstKoordinaten($FlurstKennz) {
    $sql = "SELECT st_astext(o.the_geom) AS koordinaten FROM alkobj_e_fla AS o,alknflst AS f";
    $sql.=" WHERE o.objnr=f.objnr AND f.flurstkennz='".$FlurstKennz."'";
    #echo $sql;
    return $this->execSQL($sql, 4, 0);
  }

  function getAnzFlurstuecke() {
    $sql ="SELECT count(*) AS anzahl FROM alb_flurstuecke";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function getAnzNewFlurstuecke() {
    $sql ="SELECT count(*) AS anzahl FROM alb_".$this->tableprefix."flurstuecke WHERE status='0' OR status='2'";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

	function getFlurstuecksListeALK($GemID,$GemkgID,$FlurID,$order, $historical = false){
		$sql ="SELECT *,SUBSTRING(flurstkennz,12,5) AS zaehler,SUBSTRING(flurstkennz,18,3) AS nenner";
    $sql.=" FROM alknflst WHERE 1=1";
    if ($GemkgID>0) {
      $sql.=" AND gemkgschl= '".$GemkgID."'";
    }
    if ($FlurID!='') {
      $sql.=" AND SUBSTRING(flurstkennz,8,3)='".$FlurID."'";
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
    }
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlstID'][]=$rs['flurstkennz'];
      $FlstNr=intval($rs['zaehler']);
      if ($rs['nenner']!='000') { $FlstNr.="/".intval($rs['nenner']); }
      $Liste['FlstNr'][]=$FlstNr;
    }
    return $Liste;
	}

  function getFlurstuecksListe($GemID,$GemkgID,$FlurID,$order, $historical = false){
    $sql ="SELECT *,SUBSTRING(flurstkennz,12,5) AS zaehler,SUBSTRING(flurstkennz,18,3) AS nenner";
    if($historical == 1){
    	$sql.=" FROM alb_flurstuecke WHERE status = 'H'";
    }
    else{
    	$sql.=" FROM alb_flurstuecke WHERE status != 'H'";
    }
    if ($GemkgID>0) {
      $sql.=" AND gemkgschl= ".$GemkgID;
    }
    if ($FlurID!='-1') {
      $sql.=" AND flurnr='".$FlurID."'";
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
    }
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlstID'][]=$rs['flurstkennz'];
      $FlstNr=intval($rs['zaehler']);
      if ($rs['nenner']!='000') { $FlstNr.="/".intval($rs['nenner']); }
      $Liste['FlstNr'][]=$FlstNr;
    }
    return $Liste;
  }
  
  function getFlurstuecksListeALKIS($GemID,$GemkgID,$FlurID,$order, $historical = false){
    //$sql ="SELECT land||gemarkungsnummer||'-'||lpad(flurnummer, 3, '0')||'-'||lpad(zaehler, 5, '0')||'/'||case when nenner IS NULL THEN '000.00' ELSE lpad(nenner, 3, '0')||'.00' END as flurstkennz, zaehler, nenner";
    $sql ="SELECT flurstueckskennzeichen as flurstkennz, zaehler, nenner";
    $sql.=" FROM alkis.ax_flurstueck WHERE 1=1";
    if ($GemkgID>0) {
      $sql.=" AND land*10000 + gemarkungsnummer= ".$GemkgID;
    }
    if ($FlurID!='') {
      $sql.=" AND flurnummer=".intval($FlurID);
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
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
		if (ALKIS) {
			$sql  = "SELECT flst.land, flst.kreis, flst.gemeinde, flst.gemarkungsnummer, gemkg.bezeichnung AS gemarkungname, flst.flurnummer, flst.zaehler, flst.nenner, lpad(flst.land::text,2,'0')||lpad(flst.gemarkungsnummer::text,4,'0')||'-'||lpad(flst.flurnummer::text,3,'0')||'-'||lpad(flst.zaehler::text,5,'0')||'/'||CASE WHEN flst.nenner IS NULL THEN '000' ELSE lpad(flst.nenner::text,3,'0') END||'.00' AS flurstkennz, flst.flurstueckskennzeichen, flst.zaehler::text||CASE WHEN flst.nenner IS NULL THEN '' ELSE '/'||flst.nenner::text END AS flurstuecksnummer FROM alkis.ax_flurstueck AS flst, alkis.ax_gemarkung AS gemkg WHERE (flst.land::text||lpad(flst.gemarkungsnummer::text,4,'0'))::integer = gemkg.schluesselgesamt AND flst.gemarkungsnummer = gemkg.gemarkungsnummer AND ST_within(ST_transform(ST_GeomFromText('POINT(".$longitude." ".$latitude.")', 4326), ST_srid(flst.wkb_geometry)), flst.wkb_geometry);";
		}
		else {
		# Achtung hier ist die Spalte gemarkungname mit '' belegt	
			$sql  = "SELECT *, lpad(land::text,2,'0')||lpad(gemarkungsnummer::text,4,'0')||lpad(flurnummer::text,3,'0')||lpad(zaehler::text,5,'0')||CASE WHEN nenner=0 THEN '____' ELSE lpad(nenner::text,4,'0') END||'_' AS flurstueckskennzeichen, zaehler::text||CASE WHEN nenner IS NULL THEN '' ELSE '/'||nenner::text END AS flurstuecksnummer FROM (SELECT substr(alknflst.flurstkennz::text, 1, 2)::integer AS land, NULL AS kreis, NULL AS gemeinde, '' AS gemarkungname, substr(alknflst.flurstkennz::text, 3, 4)::integer AS gemarkungsnummer, substr(alknflst.flurstkennz::text, 8, 3)::integer AS flurnummer, substr(alknflst.flurstkennz::text, 12, 5)::integer AS zaehler, substr(alknflst.flurstkennz::text, 18, 3)::integer AS nenner, alknflst.flurstkennz
   FROM alkobj_e_fla, alknflst
  WHERE alknflst.objnr::text = alkobj_e_fla.objnr::text AND ST_within(ST_transform(ST_GeomFromText('POINT(".$longitude." ".$latitude.")', 4326), ST_srid(the_geom)), the_geom)) AS flurstuecke";
		}
		#echo $sql.'<br>';
    $queryret = $this->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($queryret[1]);		
		return $rs;
	}

  function getFlurstKennzListeByGemSchlByStrSchl($GemeindeSchl,$StrassenSchl,$HausNr) {
    $sql ="SELECT alb_f_adressen.flurstkennz FROM alb_f_adressen, alb_flurstuecke";
    $sql.=" WHERE alb_flurstuecke.flurstkennz = alb_f_adressen.flurstkennz";
    $sql.=" AND status != 'H'";
    if ($HausNr!='') {
    	if($HausNr == 'ohne'){
    		$HausNr = '';
    	}
    	if(strpos($HausNr, ', ') !== false){							# wenn mehrere Hausnummern:					1, 2, 3a, 4
    		$HausNr = str_replace(", ", "','", $HausNr);		# Hochkommas dazwischen hinzufï¿½gen: 1','2','3a','4
    		$sql.=" AND gemeinde||'-'||strasse||'-'||TRIM(".HAUSNUMMER_TYPE."(hausnr)) IN ('".$HausNr."')";		# und noch die ï¿½uï¿½eren:      			 '1','2','3a','4'
    	}
    	else{
      	$sql.=" AND gemeinde||'-'||strasse||'-'||TRIM(".HAUSNUMMER_TYPE."(hausnr))='".$HausNr."'";
    	}
    }
    else{
    	$sql.=" AND gemeinde=".(int)$GemeindeSchl;
    	$sql.=" AND strasse='".$StrassenSchl."'";
    }
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
  
  function getFlurstKennzListeByGemSchlByStrSchlALKIS($GemeindeSchl,$StrassenSchl,$HausNr) {
  	$sql.=" SELECT f.flurstueckskennzeichen as flurstkennz";
    $sql.=" FROM alkis.ax_flurstueck as f, alkis.ax_gemeinde as g, alkis.alkis_beziehungen v";
    $sql.=" JOIN alkis.ax_lagebezeichnungmithausnummer l ON v.beziehung_zu=l.gml_id";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde";
    $sql.=" AND l.lage = lpad(s.lage,5,'0')";
    $sql.=" WHERE v.beziehung_von=f.gml_id AND v.beziehungsart='weistAuf' AND g.gemeinde = l.gemeinde";
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
  

  function getFlurstueckeByLfdNrName($lfd_nr_name,$limitStart,$limitAnzahl) {
    $sql ="SELECT DISTINCT b.flurstkennz FROM alb_g_buchungen AS b,alb_g_eigentuemer AS e";
    $sql.=" WHERE b.bezirk=e.bezirk AND b.blatt=e.blatt";
    $sql.=" AND e.lfd_nr_name = ".$lfd_nr_name;
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
    $sql ="SELECT DISTINCT b.flurstkennz FROM alb_g_buchungen AS b";
    $sql.=" WHERE b.bezirk = ".$bezirk." AND b.blatt = '".$blatt."'";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
        $FlurstKennz[]=$rs['flurstkennz'];
      }
      $ret[1]=$FlurstKennz;
    }
    return $FlurstKennz;
  }
  
  function getFlurstueckeByGrundbuchblattALKIS($bezirk, $blatt) {
    $sql ="SELECT DISTINCT f.flurstueckskennzeichen as flurstkennz";
		$sql.=" FROM alkis.ax_flurstueck f, alkis.alkis_beziehungen flst2buchung, alkis.ax_buchungsstelle buchung, alkis.alkis_beziehungen buchung2blatt, alkis.ax_buchungsblattbezirk bezirk, alkis.ax_buchungsblatt blatt";  
		$sql.=" WHERE flst2buchung.beziehungsart::text = 'istGebucht'::text";
		$sql.=" AND f.gml_id = flst2buchung.beziehung_von";
		$sql.=" AND flst2buchung.beziehung_zu = buchung.gml_id";
		$sql.=" AND buchung2blatt.beziehungsart::text = 'istBestandteilVon'::text"; 
		$sql.=" AND buchung2blatt.beziehung_von = buchung.gml_id";
		$sql.=" AND buchung2blatt.beziehung_zu = blatt.gml_id";
		$sql.=" AND blatt.land = bezirk.land AND blatt.bezirk = bezirk.bezirk";
    $sql.=" AND bezirk.schluesselgesamt = ".$bezirk." AND blatt.buchungsblattnummermitbuchstabenerweiterung = '".$blatt."'";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
        $FlurstKennz[]=$rs['flurstkennz'];
      }
      $ret[1]=$FlurstKennz;
    }
    return $FlurstKennz;
  }

  /**
  * Lï¿½schen und neu Einfï¿½gen der Flurstï¿½cke, fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Flurstï¿½cke in der Tabelle alb_flurstuecke, die in alb_x_flurstuecke stehen.
  * und fï¿½gt alle Flurstï¿½cke aus ï¿½nderungsdatei wieder ein, auï¿½er die historischen, denn die sind schon drin, oder sollen nicht rein.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die historischen Flurstï¿½cke im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    updateFlurstueck(), insertFlurstueck(), $postgres, $alb
  */
  function replaceFlurstuecke() {
    # lï¿½schen aller Flurstuecke in der ALB Datenbank zu den neue Angaben existieren.
    $sql ="DELETE FROM alb_flurstuecke";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_flurstuecke.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden in alb_flurstï¿½cke nicht gelï¿½scht.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      # Einfï¿½gen aller Flurstï¿½cke, die neu sind oder geï¿½ndert.
      $sql ="INSERT INTO alb_flurstuecke SELECT * FROM alb_".$this->tableprefix."flurstuecke";
      $sql.=" WHERE alb_".$this->tableprefix."flurstuecke.status='0'";
      $sql.=" OR alb_".$this->tableprefix."flurstuecke.status='2'";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function updateFlurstueck($FlurstKennz,$Status,$Entsteh,$LetzFF,$Flaeche,$AktuNr,$Karte,$BauBlock,$KoorRW,$KoorHW,$Forstamt,$Finanzamt) {
    $sql ="UPDATE alb_".$this->tableprefix."flurstuecke SET flurstkennz='".$FlurstKennz."'";
    if ($Status!="")    { $sql.=",status='".$Status."'";     }
    if ($Entsteh!="")   { $sql.=",entsteh='".$Entsteh."'";   }
    if ($LetzFF!="")    { $sql.=",letzff='".$LetzFF."'";     }
    if ($Flaeche!="")   { $sql.=",flaeche=".$Flaeche;        }
    if ($AktuNr!="")    { $sql.=",aktunr=".$AktuNr;          }
    if ($Karte!="")     { $sql.=",karte='".$Karte."'";       }
    if ($BauBlock!="")  { $sql.=",baublock='".$BauBlock."'"; }
    if ($KoorRW!="")    {
      $sql.=",koorrw=".$KoorRW;
      $sql.=",the_geom=st_geometryfromtext('POINT(".$KoorRW." ".$KoorHW.")',".EPSGCODE.")";
    }
    if ($KoorHW!="")    { $sql.=",koorhw=".$KoorHW;          }
    if ($Forstamt!="")  { $sql.=",forstamt=".$Forstamt;      }
    if ($Finanzamt!="") { $sql.=",finanzamt=".$Finanzamt;    }
    $sql.=" WHERE flurstkennz='".$FlurstKennz."'";
    return $this->execSQL($sql, 4, 0);
  }

  /**
  * Lï¿½schen der Flurstï¿½cke, die historisch sind
  *
  * Diese Funktion lï¿½scht alle historischen Flurstï¿½cke in der Tabelle alb_flurstuecke, die in alb_x_flurstuecke mit 'H' gekennzeichnet sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    updateFlurstueck(), insertFlurstueck(), $postgres, $alb
  */
  function deleteHistFlurstuecke() {
    $sql ="DELETE FROM alb_flurstuecke";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_flurstuecke.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  /**
  * Setzt den Status von historischen Flurstï¿½cken auf 'H'
  *
  * Diese Funktion setzt alle Flurstï¿½cke in der Tabelle alb_flurstuecke auf Status = 'H', die in der Fortfï¿½hrung als historisch angegeben sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    updateFlurstueck(), insertFlurstueck(), $postgres, $alb
  */
  function setFlurstueckeHist() {
  	$sql ="UPDATE alb_flurstuecke SET status='H' FROM alb_".$this->tableprefix."flurstuecke";
    $sql.=" WHERE alb_flurstuecke.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
  	return $this->execSQL($sql, 4, 0);
  }

  function getALBData($FlurstKennz) {
    $sql ="SELECT f.flurnr,f.pruefzeichen,f.status,f.entsteh,f.letzff,f.flaeche,f.aktunr,f.karte,f.baublock";
	  $sql.=",f.koorrw,f.koorhw,f.forstamt,SUBSTRING(f.flurstkennz FROM 12 FOR 5) AS zaehler,SUBSTRING(f.flurstkennz FROM 18 FOR 3) AS nenner";
	  $sql.=",k.kreis AS kreisid,k.kreisname AS kreisname,gk.gemkgschl,gk.gemkgname,g.gemeinde,g.gemeindename";
	  $sql.=",fa.finanzamt,fa.name AS finanzamtname FROM alb_v_kreise AS k,alb_v_gemeinden AS g,alb_v_gemarkungen AS gk";
	  $sql.=",alb_flurstuecke AS f LEFT JOIN alb_v_finanzaemter AS fa ON f.finanzamt=fa.finanzamt";
	  $sql.=" WHERE CAST(f.gemkgschl AS text)=CAST(gk.gemkgschl AS text)";
	  $sql.=" AND SUBSTRING(CAST(gk.gemeinde AS text) FROM 1 FOR 5) = CAST(k.kreis AS text) AND gk.gemeinde=g.gemeinde";
	  $sql.=" AND f.flurstkennz='".$FlurstKennz."'";
    #echo $sql; 
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=0;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=$rs;
    }
    return $ret;
  }
  
  function getALBDataALKIS($FlurstKennz) {
    $sql ="SELECT lpad(f.flurnummer::text, 3, '0') as flurnr, f.amtlicheflaeche as flaeche, zaehler, nenner, k.schluesselgesamt AS kreisid, k.bezeichnung as kreisname, f.land::text||f.gemarkungsnummer::text as gemkgschl, g_g.gemarkungsname as gemkgname, g.schluesselgesamt as gemeinde, g_g.gemeindename";
	  //$sql.=",f.pruefzeichen,f.status,f.entsteh,f.letzff,f.aktunr,f.karte,f.baublock,f.koorrw,f.koorhw,f.forstamt,fa.finanzamt,fa.name AS finanzamtname,";
	  $sql.=" FROM alkis.ax_kreisregion AS k, alkis.ax_gemeinde as g, alkis.gemeinde_gemarkung AS g_g, alkis.ax_flurstueck AS f";
	  //$sql.="LEFT JOIN alb_v_finanzaemter AS fa ON f.finanzamt=fa.finanzamt";
	  $sql.=" WHERE f.gemarkungsnummer=g_g.gemarkung AND f.kreis = k.kreis AND f.flurstueckskennzeichen='".$FlurstKennz."'";
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=0;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=$rs;
    }
    return $ret;
  }

  function getPruefKZ($FlurstKennz) {
    $sql = "SELECT pruefzeichen FROM alb_flurstuecke WHERE flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=0;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=$rs['pruefzeichen'];
    }
    return $ret;
  }

  function getFlurstuecksKennzByGemeindeIDs($Gemeinde_ID, $FlurstKennz){
    $sql ="SELECT f.flurstkennz FROM alb_flurstuecke AS f, alb_v_gemarkungen AS gk";
    $sql.=" WHERE f.gemkgschl=gk.gemkgschl AND gk.gemeinde IN ('".$Gemeinde_ID[0]['ID']."'";
    for($i = 1; $i < count($Gemeinde_ID); $i++){
      $sql .= ", '".$Gemeinde_ID[$i]['ID']."'";
    }
    $sql .= ")";
    $sql.=" AND f.flurstkennz IN ('".$FlurstKennz[0]."'";
    for ($i=1;$i<count($FlurstKennz);$i++) {
      $sql.=", '".$FlurstKennz[$i]."'";
    }
    $sql.=")";
    $this->debug->write("<p>postgresql.php getFlurstuecksKennzByGemeindeIDs() Abfragen erlaubten Flurstï¿½ckskennzeichen nach Gemeindeids:<br>".$sql,4);
    $query=pg_query($sql);
    if ($query==0) {
      $ret[0]=1; $ret[1]="Fehler bei der Abfrage der zur Anzeige erlaubten Flurstï¿½cke";
      $this->debug->write("<br>Abbruch in postgresql.php getFlurstuecksKennzByGemeindeIDs Zeile: ".__LINE__."<br>sql: ".$sql,4);
      return $ret;
    }
    while($rs=pg_fetch_array($query)) {
      $ret[1][]=$rs["flurstkennz"];
    }
    return $ret;
  }
  
  function getFlurstuecksKennzByGemeindeIDsALKIS($Gemeinde_ID, $FlurstKennz){
    $sql ="SELECT f.flurstueckskennzeichen as flurstkennz FROM alkis.ax_flurstueck AS f, alkis.gemeinde_gemarkung AS g_g";
    $sql.=" WHERE f.gemarkungsnummer=g_g.gemarkung AND g_g.gemeinde IN ('".$Gemeinde_ID[0]['ID']."'";
    for($i = 1; $i < count($Gemeinde_ID); $i++){
      $sql .= ", '".$Gemeinde_ID[$i]['ID']."'";
    }
    $sql .= ")";
    $sql.=" AND f.flurstueckskennzeichen IN ('".$FlurstKennz[0]."'";
    for ($i=1;$i<count($FlurstKennz);$i++) {
      $sql.=", '".$FlurstKennz[$i]."'";
    }
    $sql.=")";
    $this->debug->write("<p>postgresql.php getFlurstuecksKennzByGemeindeIDs() Abfragen erlaubten Flurstï¿½ckskennzeichen nach Gemeindeids:<br>".$sql,4);
    $query=pg_query($sql);
    if ($query==0) {
      $ret[0]=1; $ret[1]="Fehler bei der Abfrage der zur Anzeige erlaubten Flurstï¿½cke";
      $this->debug->write("<br>Abbruch in postgresql.php getFlurstuecksKennzByGemeindeIDs Zeile: ".__LINE__."<br>sql: ".$sql,4);
      return $ret;
    }
    while($rs=pg_fetch_array($query)) {
      $ret[1][]=$rs["flurstkennz"];
    }
    return $ret;
  }

  function getFlurstuecksKennzByRaumbezug($FlurstKennz,$Raumbezug,$Wert) {
    $sql ="SELECT f.flurstkennz FROM alb_flurstuecke AS f, alb_v_gemarkungen AS gk, alb_v_gemeinden AS g";
    $sql.=" WHERE f.gemkgschl=gk.gemkgschl AND gk.gemeinde=g.gemeinde";
    $sql.=" AND f.flurstkennz IN ('".$FlurstKennz[0]."'";
    for ($i=1;$i<count($FlurstKennz);$i++) {
      $sql.=", '".$FlurstKennz[$i]."'";
    }
    $sql.=")";
    switch ($Raumbezug) {
      case "Kreis" : {
        $sql.=" AND SUBSTRING(gk.gemeinde,1,5)=".(int)$Wert;
      } break;
     # Der Fall Amt wird zur Zeit nicht unterstï¿½tzt, weil er in keiner Tabelle in postgres enthalten ist.
     # case "Amt" : {
     #   $sql.=" AND g.AMT_LANG_I=".(int)$Wert;
     # } break;
      case "Gemeinde" : {
        $sql.=" AND gk.gemeinde=".(int)$Wert;
      } break;
    }
    $sql.=" ORDER BY f.flurstkennz";
    $this->debug->write("<p>alb.php getFlurstKennzByRaumbezug() Abfragen der Einschrï¿½nkungen des Raumbezuges fï¿½r ALB Daten:<br>".$sql,4);
    $query=pg_query($sql);
    if ($query==0) {
      $ret[0]=1; $ret[1]="Fehler bei der Abfrage der zur Anzeige erlaubten Flurstï¿½cke";
      $this->debug->write("<br>Abbruch in alb.php getFlurstKennzByRaumbezug Zeile: ".__LINE__."<br>sql: ".$sql,4);
      return $ret;
    }
    while($rs=pg_fetch_array($query)) {
      $ret[1][]=$rs["flurstkennz"];
    }
    return $ret;
  }

  function getFlurkarte($FlurstKennz) {
    $sql = "SELECT karte AS flurkarte FROM alb_flurstuecke WHERE flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=0;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      if ($rs['flurkarte']=="") {
        $ret[1]="0";
      }
      else {
        $ret[1]=$rs['flurkarte'];
      }
    }
    return $ret;
  }

  function insertHinweis($FlurstKennz,$Hinweis) {
    $sql="INSERT INTO alb_".$this->tableprefix."f_hinweise";
    $sql.=" (flurstkennz,hinwzflst)";
    $sql.=" VALUES ('".$FlurstKennz."','".$Hinweis."')";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewHinweise() {
    $sql ="INSERT INTO alb_f_hinweise SELECT * FROM alb_".$this->tableprefix."f_hinweise";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Hinweise zu Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Hinweise in der Tabelle alb_f_hinweise, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Hinweise zu historischen Flurstï¿½cken im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertNewHinweise(), insertHinweise(), getHinweise(), $postgres, $alb
  */
  function deleteOldHinweise() {
    $sql ="DELETE FROM alb_f_hinweise";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
    #12.10.2006 H. Riedel
#      $sql.=" USING alb_".$this->tableprefix."f_hinweise";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    #12.10.2006 H. Riedel
#    $sql.=" WHERE alb_f_hinweise.flurstkennz=alb_".$this->tableprefix."f_hinweise.flurstkennz";
    $sql.=" WHERE alb_f_hinweise.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function getHinweise($FlurstKennz) {
    $sql ="SELECT h.hinwzflst,h.bezeichnung FROM alb_f_hinweise AS hzf,alb_v_hinweise AS h";
    $sql.=" WHERE h.hinwzflst=hzf.hinwzflst AND hzf.flurstkennz='".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]==0) {
      $ret[0]=0;
      while($rs = pg_fetch_array($queryret[1])){
      	$hinweise[] = $rs;
      }
      $ret[1] = $hinweise;
    }
    return $ret;
  }

  function insertAdresse($FlurstKennz,$Gemeinde,$Strasse,$HausNr) {
    $sql ="INSERT INTO alb_".$this->tableprefix."f_adressen";
    $sql.=" (flurstkennz,gemeinde,strasse,hausnr)";
    $sql.=" VALUES ('".$FlurstKennz."','".$Gemeinde."','".$Strasse."','".$HausNr."')";
    return $this->execSQL($sql, 4, 0);
  }

  function getStrassen($FlurstKennz) {
    # Abfrage der Adressenangabe zum Flurstï¿½ck
    # 1. Abfragen der Strassen die am Flurstï¿½ck liegen
    $sql ="SELECT DISTINCT gem.gemeindename,str.strasse,str.strassenname";
    $sql.=" FROM alb_f_adressen AS adr,alb_v_gemeinden AS gem,alb_v_strassen AS str";
    $sql.=" WHERE adr.gemeinde=gem.gemeinde AND adr.strasse=str.strasse";
    $sql.=" AND str.gemeinde=gem.gemeinde";
    $sql.=" AND adr.flurstkennz = '".$FlurstKennz."'";
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
  
  function getStrassenALKIS($FlurstKennz) {
    # Abfrage der Adressenangabe zum Flurstï¿½ck
    # 1. Abfragen der Strassen die am Flurstï¿½ck liegen
    $sql ="SELECT DISTINCT g.bezeichnung as gemeindename, l.lage as strasse, s.bezeichnung as strassenname";
    $sql.=" FROM alkis.ax_flurstueck as f, alkis.ax_gemeinde as g, alkis.alkis_beziehungen v";
    $sql.=" JOIN alkis.ax_lagebezeichnungmithausnummer l ON v.beziehung_zu=l.gml_id";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde";
    $sql.=" AND s.lage = lpad(l.lage,5,'0')";
    $sql.=" WHERE v.beziehung_von=f.gml_id AND v.beziehungsart='weistAuf' AND g.gemeinde = l.gemeinde";
    $sql.=" AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
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

  function getStrIDByName($GemID,$StrName) {
    $sql ="SELECT DISTINCT strasse FROM alb_v_strassen WHERE gemeinde= ".$GemID;
    $sql.=" AND strassenname LIKE '".$StrName."'%";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      while($rs=pg_fetch_array($queryret[1])) {
        $StrID[]=$rs;
      }
      $ret[1]=$StrID;
    }
    return $ret;
  }
  
	function getStrNameByID($GemID,$StrID) {
    $sql ="SELECT DISTINCT strassenname FROM alb_v_strassen WHERE gemeinde= ".$GemID;
    $sql.=" AND strasse = '".$StrID."'";
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
  
	function getStrNameByIDALKIS($GemID,$StrID) {
    $sql ="SELECT bezeichnung FROM alkis.ax_lagebezeichnungkatalogeintrag WHERE schluesselgesamt = '".$GemID.str_pad($StrID, 5, '0', STR_PAD_LEFT)."'";
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
    $sql ="SELECT DISTINCT ".HAUSNUMMER_TYPE."(adr.hausnr) AS hausnr";
    $sql.=" FROM alb_f_adressen AS adr,alb_v_gemeinden AS gem,alb_v_strassen AS str";
    $sql.=" WHERE adr.gemeinde=gem.gemeinde AND adr.strasse=str.strasse";
    $sql.=" AND str.strasse = '".$Strasse."'";
    $sql.=" AND adr.flurstkennz = '".$FlurstKennz."'";
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
  
  function getHausNummernALKIS($FlurstKennz,$Strasse) {
    # Abfragen der Hausnummern zu den jeweiligen Strassen
    $sql ="SELECT DISTINCT ".HAUSNUMMER_TYPE."(l.hausnummer) AS hausnr";
    $sql.=" FROM alkis.ax_flurstueck as f, alkis.alkis_beziehungen v";
    $sql.=" JOIN alkis.ax_lagebezeichnungmithausnummer l ON v.beziehung_zu=l.gml_id";
    $sql.=" WHERE v.beziehung_von=f.gml_id AND v.beziehungsart='weistAuf'";
    $sql.=" AND l.lage='".$Strasse."'";
    $sql.=" AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
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

  function insertNewAdressen() {
    $sql ="INSERT INTO alb_f_adressen SELECT * FROM alb_".$this->tableprefix."f_adressen";
    return $this->execSQL($sql, 4, 0);
  }

  function deleteHistAdressen() {
    $sql ="DELETE FROM alb_f_adressen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_adressen.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Adressen von Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Adressen in der Tabelle alb_f_adressen, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Adressen von historischen Flurstï¿½cke im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertNewAdressen(), deleteHistAdressen(), insertAdresse(), $postgres, $alb
  */
  function deleteOldAdressen() {
    $sql ="DELETE FROM alb_f_adressen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Adressen aus alb_f_adressen
#      $sql.=" USING alb_".$this->tableprefix."f_adressen";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Adressen aus alb_f_adressen
#   $sql.=" WHERE alb_f_adressen.flurstkennz=alb_".$this->tableprefix."f_adressen.flurstkennz";
    $sql.=" WHERE alb_f_adressen.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function insertLage($FlurstKennz,$lfdNr,$Lage) {
    $sql ="INSERT INTO alb_".$this->tableprefix."f_lage";
    $sql.=" (flurstkennz,lfdnr,lagebezeichnung)";
    $sql.=" VALUES ('".$FlurstKennz."','".$lfdNr."','".$Lage."')";
    return $this->execSQL($sql, 4, 0);
  }

  function getLage($FlurstKennz) {
    # liefert die Lage des Flurstï¿½ckes
    $sql = "SELECT lagebezeichnung FROM alb_f_lage WHERE flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      if (pg_num_rows($queryret[1])>0) {
        while($rs=pg_fetch_array($queryret[1])) {
          $Lage[]= $rs['lagebezeichnung'];
        }
      }
      $ret[1]=$Lage;
    }
    return $ret;
  }
  
   function getLageALKIS($FlurstKennz) {
    # liefert die Lage des Flurstï¿½ckes
    $sql = "SELECT l.unverschluesselt, s.bezeichnung";
		$sql.= " FROM alkis.ax_flurstueck as f, alkis.alkis_beziehungen v";
		$sql.= " JOIN alkis.ax_lagebezeichnungohnehausnummer l ON l.gml_id=v.beziehung_zu";
		$sql.= " LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde";
		$sql.= " AND l.lage=s.lage";
		$sql.= " WHERE v.beziehung_von=f.gml_id";
		$sql.= " AND v.beziehungsart='zeigtAuf'";
		$sql.= " AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
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

  function insertNewLagen() {
    $sql ="INSERT INTO alb_f_lage SELECT * FROM alb_".$this->tableprefix."f_lage";
    return $this->execSQL($sql, 4, 0);
  }

  function deleteAddressLagen() {
    $sql ="DELETE FROM alb_f_lage";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_f_adressen";
    }
    $sql.=" WHERE alb_f_lage.flurstkennz=alb_f_adressen.flurstkennz";
    return $this->execSQL($sql, 4, 0);
  }

  function deleteHistLagen() {
    $sql ="DELETE FROM alb_f_lage";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_lage.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Lagebezeichnungen von Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Lagebezeichnungen in der Tabelle alb_f_lage, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Lagebezeichnungen von historischen Flurstï¿½cke im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    deleteHistLagen(), insertLage(), insertNewLagen(), getLage(), $postgres, $alb
  */
  function deleteOldLagen() {
    $sql ="DELETE FROM alb_f_lage";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Lagebezeichnungen aus alb_f_lage
#      $sql.=" USING alb_".$this->tableprefix."f_lage";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Lagebezeichnungen aus alb_f_lage
#    $sql.=" WHERE alb_f_lage.flurstkennz=alb_".$this->tableprefix."f_lage.flurstkennz";
    $sql.=" WHERE alb_f_lage.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function insertNutzung($FlurstKennz,$Nutzungsart,$NutzungFlaeche) {
    $sql ="INSERT INTO alb_".$this->tableprefix."f_nutzungen";
    $sql.=" (flurstkennz,nutzungsart,flaeche)";
    $sql.=" VALUES ('".$FlurstKennz."','".$Nutzungsart."','".$NutzungFlaeche."')";
    return $this->execSQL($sql, 4, 0);
  }

  function getNutzung($FlurstKennz) {
    $sql ="SELECT nutz.flaeche AS flaeche,'21-' || nutz.nutzungsart AS nutzungskennz";
    $sql.=",art.bezeichnung,art.abkuerzung FROM";
    $sql.=" alb_f_nutzungen AS nutz,alb_v_nutzungsarten AS art WHERE nutz.nutzungsart=art.nutzungsart";
    $sql.=" AND flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0] OR pg_num_rows($queryret[1])==0) {
      # keine Eintragungen zu Nutzungen gefunden
      return $queryret;
    }
    # Nutzungen zum Flurstï¿½ck wurden erfolgreich abgefragt
    while($rs=pg_fetch_array($queryret[1])) {
      $Nutzungen[]=$rs;
    }
    $ret[0]=0;
    $ret[1]=$Nutzungen;
    return $ret;
  }
  
  function getNutzungALKIS($FlurstKennz) {
    $sql ="SELECT round(st_area(st_intersection(n.wkb_geometry,f.wkb_geometry))::numeric,0) AS flaeche, c.class as nutzungskennz, case when c.label is null then m.title else m.title||' - '||c.label end as bezeichnung, n.info, n.zustand, n.name, m.gruppe,c.label, c.blabla";
		$sql.=" FROM alkis.ax_flurstueck f, alkis.nutzung n";
		$sql.=" JOIN alkis.nutzung_meta m ON m.nutz_id=n.nutz_id";
		$sql.=" LEFT JOIN alkis.nutzung_class c ON c.nutz_id=n.nutz_id AND c.class=n.class";
		$sql.=" WHERE st_intersects(n.wkb_geometry,f.wkb_geometry) = true";
		$sql.=" AND st_area(st_intersection(n.wkb_geometry,f.wkb_geometry)) > 0.05";
		$sql.=" AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
		#echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0] OR pg_num_rows($queryret[1])==0) {
      # keine Eintragungen zu Nutzungen gefunden
      return $queryret;
    }
    # Nutzungen zum Flurstï¿½ck wurden erfolgreich abgefragt
    while($rs=pg_fetch_array($queryret[1])) {
      $Nutzungen[]=$rs;
    }
    $ret[0]=0;
    $ret[1]=$Nutzungen;
    return $ret;
  }

  function deleteHistNutzungen() {
    $sql ="DELETE FROM alb_f_nutzungen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_nutzungen.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewNutzungen() {
    $sql ="INSERT INTO alb_f_nutzungen SELECT * FROM alb_".$this->tableprefix."f_nutzungen";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Nutzungsarten von Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Nutzungen in der Tabelle alb_f_nutzungen, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Nutzungen von historischen Flurstï¿½cke im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertNewNutzungen(), insertNutzung(), deleteHistNutzung(), $postgres, $alb
  */
  function deleteOldNutzungen($historische_loeschen) {
    $sql ="DELETE FROM alb_f_nutzungen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Nutzungsarten aus alb_f_nutzungen
#      $sql.=" USING alb_".$this->tableprefix."f_nutzungen";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Nutzungsarten aus alb_f_nutzungen
#    $sql.=" WHERE alb_f_nutzungen.flurstkennz=alb_".$this->tableprefix."f_nutzungen.flurstkennz";
    $sql.=" WHERE alb_f_nutzungen.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function insertKlassifizierung($FlurstKennz,$TabKenn,$Klass,$KlassFlaeche,$KlassAngabe) {
    $sql ="INSERT INTO alb_".$this->tableprefix."f_klassifizierungen";
    $sql.=" (flurstkennz,tabkenn,klass,flaeche,angaben)";
    $sql.=" VALUES ('".$FlurstKennz."','".$TabKenn."','".$Klass."','".$KlassFlaeche."','".$KlassAngabe."')";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewKlassifizierungen() {
    $sql ="INSERT INTO alb_f_klassifizierungen SELECT * FROM alb_".$this->tableprefix."f_klassifizierungen";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Klassifizierungen von Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Klassifizierungen in der Tabelle alb_f_klassifizierungen, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Klassifizierungen von historischen Flurstï¿½cken im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertNewKlassifizierungen(), insertKlassifizierung(), getKlassifizierung(), $postgres, $alb
  */
  function deleteOldKlassifizierungen() {
    $sql ="DELETE FROM alb_f_klassifizierungen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Klassifizierungen aus alb_f_klassifizierungen
#      $sql.=" USING alb_".$this->tableprefix."f_klassifizierungen";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Klassifizierungen aus alb_f_klassifizierungen
#    $sql.=" WHERE alb_f_klassifizierungen.flurstkennz=alb_".$this->tableprefix."f_klassifizierungen.flurstkennz";
    $sql.=" WHERE alb_f_klassifizierungen.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function getKlassifizierung($FlurstKennz) {
    $sql ="SELECT k.tabkenn,fk.flaeche,fk.angaben,k.klass,k.bezeichnung,k.abkuerzung";
    $sql.=" FROM alb_f_klassifizierungen AS fk,alb_v_klassifizierungen AS k";
    $sql.=" WHERE fk.klass=k.klass AND fk.tabkenn = k.tabkenn AND fk.flurstkennz='".$FlurstKennz."' ORDER BY tabkenn";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_array($ret[1])) {
        $Klassifizierung[]=$rs;
      }
      $sql ="SELECT flurstkennz, SUM(flaeche) as summe FROM alb_f_klassifizierungen AS k";
      $sql.=" WHERE tabkenn = '32' AND k.flurstkennz='".$FlurstKennz."' GROUP BY flurstkennz";
      $this->debug->write("<br>kataster.php->flurstueck->getKlassifizierung Abfrage der Flï¿½chensumme zu Klassifizierungen zum Flurstï¿½ck<br>".$sql,4);
      $ret=$this->execSQL($sql, 4, 0);
      if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
      $rs=pg_fetch_array($ret[1]);
      $Klassifizierung['summe']=$rs['summe'];
    }
    $ret[1]=$Klassifizierung;
    return $ret;
  }
  
  function getEMZfromALK($FlurstKennz){
	  $sql = "select DISTINCT st_intersection(k.the_geom,st_intersection(o.the_geom,f.the_geom)) AS st_intersection_geom,round(st_area(st_intersection(k.the_geom,st_intersection(o.the_geom,f.the_geom)))) as flaeche,substring(t.label from 18 for 3) AS wert,'0000'||substring(t.label from 5) AS label, area(f.the_geom) as flstflaeche, o.objart";
		$sql.=" FROM alkobj_e_fla as o, alkobj_e_fla as f, alkobj_e_fla as k, alknflst as fl, alkobj_t_pkt as t";
		$sql.=" WHERE o.the_geom && f.the_geom and o.the_geom && k.the_geom";
		$sql.=" AND fl.flurstkennz = '".$FlurstKennz."'" ;
		$sql.=" AND fl.objnr = f.objnr";
		$sql.=" AND (o.objart = '222' OR o.objart = '223')";
		$sql.=" AND k.objart='901'";
		$sql.=" AND st_area(st_intersection(k.the_geom,st_intersection(o.the_geom,f.the_geom))) > 1";
		$sql.=" AND o.objnr=t.objnr ORDER BY o.objart";
		$ret=$this->execSQL($sql, 4, 0);
    if($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if(pg_num_rows($ret[1]) > 0) {
      while($rs=pg_fetch_array($ret[1])){
        $emz[]=$rs;
      }
    }
    return $emz;
  }
  

  function insertText($FlurstKennz,$lfdNr,$freierText) {
    $sql ="INSERT INTO alb_".$this->tableprefix."f_texte";
    $sql.=" (flurstkennz,lfdnr,text)";
    $sql.=" VALUES ('".$FlurstKennz."','".$lfdNr."','".$freierText."')";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewTexte() {
    $sql ="INSERT INTO alb_f_texte SELECT * FROM alb_".$this->tableprefix."f_texte";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Texte von Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Texte in der Tabelle alb_f_texte, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Texte von historischen Flurstï¿½cken im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertNewTexte(), insertText(), deleteHistTexte(), $postgres, $alb
  */
  function deleteOldTexte() {
    $sql ="DELETE FROM alb_f_texte";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Texte aus alb_f_texte
#      $sql.=" USING alb_".$this->tableprefix."f_texte";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Texte aus alb_f_texte
#    $sql.=" WHERE alb_f_texte.flurstkennz=alb_".$this->tableprefix."f_texte.flurstkennz";
    $sql.=" WHERE alb_f_texte.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function deleteHistTexte() {
    $sql ="DELETE FROM alb_f_texte";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_texte.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }


  function insertAnlieger($FlurstKennz,$Kennung,$AnlFlstKennz,$AnlFlstPruefz) {
    # 2005-12-02_pk
    $sql ="INSERT INTO alb_".$this->tableprefix."f_anlieger";
    $sql.=" (flurstkennz,kennung,anlflstkennz,anlflstpruefz)";
    $sql.=" VALUES ('".$FlurstKennz."','".$Kennung."','".$AnlFlstKennz."','".$AnlFlstPruefz."')";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewAnlieger() {
    $sql ="INSERT INTO alb_f_anlieger SELECT * FROM alb_".$this->tableprefix."f_anlieger";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Anliegerinformationen von Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Anliegerinformationen in der Tabelle alb_f_anlieger, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Anlieger von historischen Flurstï¿½cken im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertNewAnlieger(), insertAnlieger(), deleteHistAnlieger(), $postgres, $alb
  */
  function deleteOldAnlieger() {
    $sql ="DELETE FROM alb_f_anlieger";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Anlieger aus alb_f_anlieger
#      $sql.=" USING alb_".$this->tableprefix."f_anlieger";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Anlieger aus alb_f_anlieger
#    $sql.=" WHERE alb_f_anlieger.flurstkennz=alb_".$this->tableprefix."f_anlieger.flurstkennz";
    $sql.=" WHERE alb_f_anlieger.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function deleteHistAnlieger() {
    $sql ="DELETE FROM alb_f_anlieger";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_anlieger.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function insertBaulast($FlurstKennz,$BlattNr) {
    $sql="INSERT INTO alb_".$this->tableprefix."f_baulasten";
    $sql.=" (flurstkennz,blattnr)";
    $sql.=" VALUES ('".$FlurstKennz."','".$BlattNr."')";
    return $this->execSQL($sql, 4, 0);
  }

  function getBaulasten($FlurstKennz) {
    $sql ="SELECT blattnr FROM alb_f_baulasten AS bl";
    $sql.=" WHERE bl.flurstkennz='".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      while($rs=pg_fetch_array($queryret[1])) {
        $Baulasten[]=$rs;
      }
      $ret[0]=0;
      $ret[1]=$Baulasten;
    }
    return $ret;
  }

  function deleteHistBaulasten() {
    $sql ="DELETE FROM alb_f_baulasten";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_baulasten.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewBaulasten() {
    $sql ="INSERT INTO alb_f_baulasten SELECT * FROM alb_".$this->tableprefix."f_baulasten";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Baulasteneintrï¿½ge von Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Baulasteneintrï¿½ge in der Tabelle alb_f_baulasten, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Baulasteneintrï¿½ge von historischen Flurstï¿½cke im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertNewBaulasten(), insertBaulast(), deleteHistBaulasten(), $postgres, $alb
  */
  function deleteOldBaulasten() {
    $sql ="DELETE FROM alb_f_baulasten";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Baulasten aus alb_f_baulasten
#      $sql.=" USING alb_".$this->tableprefix."f_baulasten";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
# 08.02.2007 H.Riedel, unvollstï¿½ndiges lï¿½schen der Baulasten aus alb_f_baulasten
#    $sql.=" WHERE alb_f_baulasten.flurstkennz=alb_".$this->tableprefix."f_baulasten.flurstkennz";
    $sql.=" WHERE alb_f_baulasten.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function insertVerfahren($FlurstKennz,$AusfStelle,$VerfNr,$VerfBem) {
    $sql ="INSERT INTO alb_".$this->tableprefix."f_verfahren";
    $sql.=" (flurstkennz,ausfstelle,verfnr,verfbem)";
    $sql.=" VALUES ('".$FlurstKennz."','".$AusfStelle."','".$VerfNr."','".$VerfBem."')";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewVerfahren() {
    $sql ="INSERT INTO alb_f_verfahren SELECT * FROM alb_".$this->tableprefix."f_verfahren";
    return $this->execSQL($sql, 4, 0);
  }

 /**
  * Lï¿½scht die Verfahrensangaben von Flurstï¿½cken fï¿½r die ï¿½nderungen vorhanden sind
  *
  * Diese Funktion lï¿½scht alle Verfahrensangaben in der Tabelle alb_f_verfahren, die an Flurstï¿½cke in Tabelle alb_x_flurstuecke gebunden sind.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  boolean $historische_loeschen Wenn diese Variable auf 0 gesetzt ist, werden die Verfahren von historischen Flurstï¿½cke im aktuellen Bestand nicht gelï¿½scht.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertNewVerfahren(), deleteHistVerfahren(), getVerfahren(), insertVerfahren(), $postgres, $alb
  */
  function deleteOldVerfahren() {
    $sql ="DELETE FROM alb_f_verfahren";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
    #12.10.2006 H. Riedel
#      $sql.=" USING alb_".$this->tableprefix."f_verfahren";
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    #12.10.2006 H. Riedel
#    $sql.=" WHERE alb_f_verfahren.flurstkennz=alb_".$this->tableprefix."f_verfahren.flurstkennz";
    $sql.=" WHERE alb_f_verfahren.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    if (!$historische_loeschen) { // Wenn historische nicht gelï¿½scht werden sollen
      // Flurstï¿½cke, die in der Tabelle alb_x_flurstï¿½cke mit status H ausgestattet sind werden nicht berï¿½cksichtigt.
      $sql.=" AND alb_".$this->tableprefix."flurstuecke.status != 'H'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function getVerfahren($FlurstKennz) {
    $sql ="SELECT st.ausfstelle AS ausfstelleid,st.name AS ausfstellename";
    $sql.=",v.flurstkennz,v.verfnr,v.verfbem AS verfbemid,b.bezeichnung AS verfbemerkung";
    $sql.=" FROM alb_f_verfahren AS v LEFT JOIN alb_v_bemerkgzumverfahren AS b ON v.verfbem=b.verfbem";
    $sql.=",alb_v_ausfuehrendestellen AS st WHERE v.ausfstelle=st.ausfstelle";
    $sql.=" AND v.flurstkennz='".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]==0) {
      $ret[0]=0;
      while($rs=pg_fetch_array($queryret[1])) {
        $Verfahren[]=$rs;
      }
      $ret[1]=$Verfahren;
    }
    return $ret;
  }

  function deleteHistVerfahren() {
    $sql ="DELETE FROM alb_f_verfahren";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_verfahren.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function getHistorie($Vorgaenger,$Nachfolger) {
    $sql ="SELECT * FROM alb_".$this->tableprefix."f_historie";
    $sql.=" WHERE vorgaenger = '".$Vorgaenger."' AND nachfolger = '".$Nachfolger."'";
    return $this->execSQL($sql, 4, 0);
  }

  function getAnzHistorien($Vorgaenger,$Nachfolger) {
    $sql ="SELECT count(*) AS anzahl FROM alb_".$this->tableprefix."f_historie";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $anzahl=0;
    }
    else {
      $rs=pg_fetch_array($queryret[1]);
      $anzahl=$rs['anzahl'];
    }
  }

  function insertHistorie($Vorgaenger,$Nachfolger) {
    $sql ="INSERT INTO alb_".$this->tableprefix."f_historie (vorgaenger,nachfolger)";
    $sql.=" (SELECT '".$Vorgaenger."','".$Nachfolger."' WHERE NOT EXISTS (";
    $sql.=" SELECT 1 FROM alb_".$this->tableprefix."f_historie";
    $sql.=" WHERE vorgaenger='".$Vorgaenger."' AND nachfolger='".$Nachfolger."'))";
    return $this->execSQL($sql, 4, 0);
  }

	function getNachfolger($FlurstKennz) {
    $sql = "SELECT nachfolger, status FROM alb_f_historie, alb_flurstuecke WHERE nachfolger = flurstkennz AND vorgaenger = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      while($rs=pg_fetch_array($queryret[1])) {
        $Nachfolger[]=$rs;
      }
      $ret[0]=0;
      $ret[1]=$Nachfolger;
    }
    return $ret;
  }

  function getVorgaenger($FlurstKennz) {
    $sql = "SELECT vorgaenger FROM alb_f_historie WHERE nachfolger = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      while($rs=pg_fetch_array($queryret[1])) {
        $Vorgaenger[]=$rs;
      }
      $ret[0]=0;
      $ret[1]=$Vorgaenger;
    }
    return $ret;
  }

  function deleteNewHistorien() {
    $sql ="DELETE FROM alb_f_historie";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."f_historie";
    }
    $sql.=" WHERE alb_f_historie.vorgaenger = alb_".$this->tableprefix."f_historie.vorgaenger";
    $sql.=" AND alb_f_historie.nachfolger = alb_".$this->tableprefix."f_historie.nachfolger";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewHistorien() {
    $sql ="INSERT INTO alb_f_historie SELECT * FROM alb_".$this->tableprefix."f_historie";
    return $this->execSQL($sql, 4, 0);
  }

  function deleteHistHistorie() {
    $sql ="DELETE FROM alb_f_historie";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_historie.nachfolger=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function deleteTempHistVorgaenger() {
    $sql ="DELETE FROM alb_".$this->tableprefix."f_historie";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_".$this->tableprefix."f_historie.nachfolger=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function insertEigentuemer($Bezirk,$Blatt,$NamensNr,$Eigentuemerart,$Anteilsverhaeltnis,$lfd_Nr_Name) {
    $sql ="INSERT INTO alb_".$this->tableprefix."g_eigentuemer (";
    $sql.="SELECT '".$Bezirk."','".$Blatt."','".$NamensNr."','".$Eigentuemerart."','".$Anteilsverhaeltnis."','".$lfd_Nr_Name."'";
    $sql.=" WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."g_eigentuemer";
    $sql.=" WHERE bezirk='".$Bezirk."' AND blatt='".$Blatt."' AND namensnr='".$NamensNr."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function getEigentuemerliste($FlurstKennz,$Bezirk,$Blatt,$BVNR) {
    # 28.11.2006 H.Riedel, Eigentï¿½merart hinzugefï¿½gt
    # $sql = "SELECT e.namensnr,n.name1,n.name2,n.name3,n.name4,e.anteilsverhaeltnis AS Anteil";
    $sql = "SELECT e.namensnr,n.lfd_nr_name,n.name1,n.name2,n.name3,n.name4,e.anteilsverhaeltnis AS anteil,e.eigentuemerart AS Art";
    $sql.= " FROM alb_g_eigentuemer AS e,alb_g_namen AS n,alb_g_grundstuecke AS g,alb_g_buchungen AS b";
    $sql.= " WHERE e.lfd_nr_name=n.lfd_nr_name AND e.bezirk = g.bezirk AND e.blatt=g.blatt";
    $sql.= " AND g.bezirk=b.bezirk AND g.blatt=b.blatt AND g.bvnr=b.bvnr";
    if ($Bezirk!="") {
      $sql.=" AND b.bezirk=".(int)$Bezirk;
    }
    if ($Blatt!="") {
      $sql.=" AND b.blatt='".$Blatt."'";
    }
    if ($BVNR!="") {
      $sql.=" AND b.bvnr='".$BVNR."'";
    }
    $sql.= " AND b.flurstkennz = '".$FlurstKennz."' ORDER BY namensnr;";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0] OR pg_num_rows($ret[1])==0) { return $ret; }
    while ($rs=pg_fetch_array($ret[1])) {
      $Grundbuch = new grundbuch("","",$this->debug);
      
      $part = explode('.', $rs['namensnr']);
      if($part[1] != ''){
      	$part[1] = intval($part[1]);
      }
      $rs['namensnr'] = implode('.', $part);
      
      $Eigentuemer = new eigentuemer($Grundbuch,$rs['namensnr']);

      $Eigentuemer->lfd_nr=$rs['lfd_nr_name'];

      $plztest = explode('     ', $rs['name3']);
      if($plztest[1] != ''){
        $plztest[1] = '  PLZPF '.trim($plztest[1]);
        $plztest[2] = 'PF '.trim($plztest[2]);
        $rs['name3'] = implode('   ', $plztest);
      }

      $Eigentuemer->Name[]=$rs['name1'];
      $Eigentuemer->Name[] = $rs['name2'];
      $Eigentuemer->Name[] = $rs['name3'];
      $Eigentuemer->Name[] = $rs['name4'];
      $Eigentuemer->Anteil=$rs['anteil'];
      $Eigentuemerliste[]=$Eigentuemer;
    }
    $retListe[0]=0;
    $retListe[1]=$Eigentuemerliste;
    return $retListe;
  }
  
  function getEigentuemerlisteALKIS($FlurstKennz,$Bezirk,$Blatt,$BVNR) {
    $sql = "SELECT distinct namensnummer.laufendenummernachdin1421 AS namensnr, person.nachnameoderfirma, person.vorname, person.akademischergrad, person.geburtsname, person.geburtsdatum, anschrift.strasse, anschrift.hausnummer, anschrift.postleitzahlpostzustellung, anschrift.ort_post, eigentuemerart as Art, namensnummer.zaehler||'/'||namensnummer.nenner as anteil";
		$sql.= " FROM alkis.ax_flurstueck f, alkis.alkis_beziehungen flst2buchung, alkis.ax_buchungsstelle buchung,alkis.alkis_beziehungen buchung2blatt,alkis.ax_buchungsblattbezirk bezirk,alkis.ax_buchungsblatt blatt,alkis.alkis_beziehungen blatt2namensnummer,alkis.ax_namensnummer namensnummer,alkis.alkis_beziehungen namensnummer2person,alkis.ax_person person";
		$sql.= " LEFT JOIN alkis.alkis_beziehungen person2anschrift ON person2anschrift.beziehung_von = person.gml_id AND person2anschrift.beziehungsart::text = 'hat'::text";
		$sql.= " LEFT JOIN alkis.ax_anschrift anschrift ON person2anschrift.beziehung_zu = anschrift.gml_id ";
		$sql.= " WHERE flst2buchung.beziehungsart::text = 'istGebucht'::text";
		$sql.= " AND f.gml_id = flst2buchung.beziehung_von";
		$sql.= " AND flst2buchung.beziehung_zu = buchung.gml_id";
		$sql.= " AND buchung2blatt.beziehungsart::text = 'istBestandteilVon'::text"; 
		$sql.= " AND buchung2blatt.beziehung_von = buchung.gml_id";
		$sql.= " AND buchung2blatt.beziehung_zu = blatt.gml_id";
		$sql.= " AND blatt.land = bezirk.land AND blatt.bezirk = bezirk.bezirk";
		$sql.= " AND blatt2namensnummer.beziehungsart::text = 'istBestandteilVon'::text"; 
		$sql.= " AND blatt2namensnummer.beziehung_zu = blatt.gml_id";
		$sql.= " AND blatt2namensnummer.beziehung_von = namensnummer.gml_id";
		$sql.= " AND namensnummer2person.beziehungsart::text = 'benennt'::text";   
		$sql.= " AND namensnummer2person.beziehung_von = namensnummer.gml_id";
		$sql.= " AND namensnummer2person.beziehung_zu = person.gml_id";
    if ($Bezirk!="") {
      $sql.=" AND bezirk.schluesselgesamt=".(int)$Bezirk;
    }
    if ($Blatt!="") {
      $sql.=" AND blatt.buchungsblattnummermitbuchstabenerweiterung='".$Blatt."'";
    }
    if ($BVNR!="") {
      $sql.=" AND buchung.laufendenummer='".$BVNR."'";
    }
    $sql.= " AND f.flurstueckskennzeichen = '".$FlurstKennz."' ORDER BY namensnr;";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0] OR pg_num_rows($ret[1])==0) { return $ret; }
    while ($rs=pg_fetch_array($ret[1])) {
      $Grundbuch = new grundbuch("","",$this->debug);
      
      $part = explode('.', $rs['namensnr']);
      $rs['namensnr'] = intval($part[0]);
      if(intval($part[1]) != 0){
      	$rs['namensnr'] .= '.'.intval($part[1]);
      }
      
      $Eigentuemer = new eigentuemer($Grundbuch,$rs['namensnr']);

      $Eigentuemer->lfd_nr=$rs['lfd_nr_name'];

      $Eigentuemer->Name[0]=$rs['nachnameoderfirma'];
      if($rs['vorname'] != '')$Eigentuemer->Name[0] .= ', '.$rs['vorname']; 
      $Eigentuemer->Name[1] = $rs['geburtsname'].' '.$rs['geburtsdatum'];
      $Eigentuemer->Name[2] = $rs['strasse'].' '.$rs['hausnummer'];
      $Eigentuemer->Name[3] = $rs['postleitzahlpostzustellung'].' '.$rs['ort_post'];
      $Eigentuemer->Anteil=$rs['anteil'];
      $Eigentuemerliste[]=$Eigentuemer;
    }
    $retListe[0]=0;
    $retListe[1]=$Eigentuemerliste;
    return $retListe;
  }

  function insertNewEigentuemer() {
    $sql ="INSERT INTO alb_g_eigentuemer (bezirk,blatt,namensnr,eigentuemerart,anteilsverhaeltnis,lfd_nr_name)";
    $sql.=" SELECT e.bezirk,e.blatt,e.namensnr,e.eigentuemerart,e.anteilsverhaeltnis,n.lfd_nr_name_alt";
    $sql.=" FROM alb_".$this->tableprefix."g_eigentuemer AS e,alb_".$this->tableprefix."g_namen AS n";
    $sql.=" WHERE e.lfd_nr_name=n.lfd_nr_name";
    return $this->execSQL($sql, 4, 0);
  }

  function deleteNewEigentuemer() {
    $sql ="DELETE FROM alb_g_eigentuemer";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."g_eigentuemer";
    }
    $sql.=" WHERE alb_g_eigentuemer.bezirk=alb_".$this->tableprefix."g_eigentuemer.bezirk";
    $sql.=" AND TRIM(alb_g_eigentuemer.blatt) = TRIM(alb_".$this->tableprefix."g_eigentuemer.blatt)";
    $sql.=" AND alb_g_eigentuemer.namensnr = alb_".$this->tableprefix."g_eigentuemer.namensnr";
    return $this->execSQL($sql, 4, 0);
  }

  /**
  * Lï¿½schen der Zuordnungen der Eigentï¿½mer zu Grundbï¿½chern, die zu ï¿½ndern sind
  *
  * Diese Funktion lï¿½scht alle Zuordnungen von Eigentï¿½mern zu Grundbï¿½chern in der Tabelle alb_g_eigentuemer, die aktualisiert werden sollen.
  *
  * Reihenfolge: ï¿½bersichtssatz - Kommentar - Tags.
  *
  * @param  string  $historische_loeschen Wenn der Parameter auf 1 gestetzt ist, werden die in der temporï¿½ren Tabelle als historisch gekennzeichneten Grundbï¿½cher fï¿½r Lï¿½schung der Eigentï¿½merzuordnung verwendet.
  * @return array liefert zweidimensionales Array zurï¿½ck,
  *                 Wenn array[0]=0 enthï¿½lt array[1] die query_id der Abfrage mit der das Resultset ausgewertet werden kann.
  *                 Wenn array[0]=1 liegt ein Fehler vor und array[1] enthï¿½lt eine Fehlermeldung.
  * @see    insertEigentuemer(), updateEigentuemer(), $postgres, $alb
  */
  function deleteEigentuemerByGrundbuecher($historische_loeschen) {
    $sql ="DELETE FROM alb_g_eigentuemer";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."grundbuecher";
    }
    $sql.=" WHERE alb_g_eigentuemer.bezirk=alb_".$this->tableprefix."grundbuecher.bezirk";
    $sql.=" AND TRIM(alb_g_eigentuemer.blatt) = TRIM(alb_".$this->tableprefix."grundbuecher.blatt)";
    if (!$historische_loeschen) {
    	$sql.=" AND alb_".$this->tableprefix."grundbuecher.aktualitaetsnr NOT LIKE 'hist'";
    }
    return $this->execSQL($sql, 4, 0);
  }

  function updateEigentuemer() {
    $sql ="UPDATE alb_g_namen SET";
    $sql.=" name1=alb_".$this->tableprefix."g_namen.name1,name2=alb_".$this->tableprefix."g_namen.name2";
    $sql.=",name3=alb_".$this->tableprefix."g_namen.name3,name4=alb_".$this->tableprefix."g_namen.name4";
#Eingefï¿½gt 11.04.2006 H. Riedel
    $sql.=" FROM alb_g_eigentuemer, alb_".$this->tableprefix."g_namen, alb_".$this->tableprefix."g_eigentuemer";
    $sql.=" WHERE alb_g_eigentuemer.namensNr=alb_".$this->tableprefix."g_eigentuemer.namensNr";
    $sql.=" AND TRIM(alb_g_eigentuemer.blatt) = TRIM(alb_".$this->tableprefix."g_eigentuemer.blatt)";
    $sql.=" AND alb_g_eigentuemer.bezirk=alb_".$this->tableprefix."g_eigentuemer.bezirk";
    $sql.=" AND alb_".$this->tableprefix."g_namen.lfd_nr_name=alb_".$this->tableprefix."g_eigentuemer.lfd_nr_name";
    $sql.=" AND alb_g_namen.lfd_nr_name=alb_g_eigentuemer.lfd_nr_name";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="UPDATE alb_g_eigentuemer SET";
      $sql.=" eigentuemerart=alb_".$this->tableprefix."g_eigentuemer.eigentuemerart";
      $sql.=",anteilsverhaeltnis=alb_".$this->tableprefix."g_eigentuemer.anteilsverhaeltnis";
#Eingefï¿½gt 11.04.2006 H. Riedel
      $sql.=" FROM alb_g_namen, alb_".$this->tableprefix."g_namen, alb_".$this->tableprefix."g_eigentuemer";
      $sql.=" WHERE alb_g_eigentuemer.namensNr=alb_".$this->tableprefix."g_eigentuemer.namensNr";
      $sql.=" AND TRIM(alb_g_eigentuemer.blatt) = TRIM(alb_".$this->tableprefix."g_eigentuemer.blatt)";
      $sql.=" AND alb_g_eigentuemer.bezirk=alb_".$this->tableprefix."g_eigentuemer.bezirk AND alb_".$this->tableprefix."g_namen.lfd_nr_name=alb_".$this->tableprefix."g_eigentuemer.lfd_nr_name";
      $sql.=" AND alb_g_namen.lfd_nr_name=alb_g_eigentuemer.lfd_nr_name";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function insertName($lfd_Nr_Name,$Satzunterart,$Namen) {
    $sql ="INSERT INTO alb_".$this->tableprefix."g_namen";
    $sql.=" (lfd_nr_name,name".$Satzunterart.")";
    $sql.=" VALUES ('".$lfd_Nr_Name."','".addslashes($Namen)."')";
    return $this->execSQL($sql, 4, 0);
  }

  function updateName($lfd_Nr_Name,$Satzunterart,$Namen) {
    $sql ="UPDATE alb_".$this->tableprefix."g_namen SET name".$Satzunterart."='".$Namen."'";
    $sql.=" WHERE lfd_nr_name=".(int)$lfd_Nr_Name;
    return $this->execSQL($sql, 4, 0);
  }

  function getLastLfdNrName() {
    $sql ="SELECT lfd_nr_name FROM alb_g_namen";
    $sql.=" ORDER BY lfd_nr_name DESC LIMIT 1";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $rs=pg_fetch_array($queryret[1]);
      $ret[0]=0;
      $ret[1]=$rs['lfd_nr_name'];
    }
    return $ret;
  }

  function setSequenzLfdNrName($lastLfdNrName) {
    $sql ="SELECT setval('alb_g_namen_lfd_nr_name_seq',".$lastLfdNrName.")";
    return $this->execSQL($sql, 4, 0);
  }

  function insertNewNamen() {
    # Abfragen der letzten laufenden Nummern fï¿½r Namen
    $ret=$this->getLastLfdNrName();
    if ($ret[0]==0) {
      $lastLfdNrName=$ret[1];
      # Setzen der fortlaufenden Sequenz fï¿½r lfd_nr_name
      $ret=$this->setSequenzLfdNrName($lastLfdNrName);
      if ($ret[0]==0) {
        # Einfï¿½gen der neuen Namen aus temporï¿½rer Tabelle in Bestand
        # fortlaufende Nummer wird ï¿½ber sequenz automatisch weitergezï¿½hlt
        $sql ="INSERT INTO alb_g_namen (name1,name2,name3,name4)";
        $sql.=" SELECT name1,name2,name3,name4 FROM alb_".$this->tableprefix."g_namen";
        $sql.=" WHERE lfd_nr_name_alt = 0";
        $ret=$this->execSQL($sql, 4, 0);
      }
    }
    return $ret;
  }

  function getNamen($n1,$n2,$n3,$n4,$bezirk,$blatt,$gemkgschl,$flur,$limitAnzahl,$limitStart,$caseSensitive, $order) {
    # pg unterstï¿½tzt mit LIKE CASE SENSITIVEN Stringvergleiche
    $name[1]=$n1; $name[2]=$n2; $name[3]=$n3; $name[4]=$n4;
    $sql ="SELECT DISTINCT n.lfd_nr_name,name1,name2,name3,name4,e.bezirk,e.blatt,e.namensnr";
    $sql.=" FROM alb_g_namen AS n,alb_g_eigentuemer AS e, alb_grundbuecher as gb";
    if ($gemkgschl>0) {
      $sql.=",alb_g_buchungen AS b";
    }
    $sql.=" WHERE n.lfd_nr_name=e.lfd_nr_name";
    $sql.=" AND gb.bezirk = e.bezirk"; 
		$sql.=" AND gb.blatt = e.blatt";
		$sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    for ($i=1;$i<5;$i++) {
      if ($name[$i]!='%%') {
      	$name[$i]=$name[$i];
        if ($caseSensitive) {
          $sql.=" AND name".$i." LIKE '".$name[$i]."'";
        }
        else {
          $sql.=" AND lower(name".$i.") LIKE lower('".$name[$i]."')";
        }
      }
    }
    if($bezirk!='') {
      $sql.=" AND e.bezirk = ".$bezirk;
    }
    if($blatt != ''){
      $sql.=" AND e.blatt = '".$blatt."'";
    }   
    if ($gemkgschl>0) {
      $sql.=" AND e.bezirk=b.bezirk AND e.blatt=b.blatt";
      $sql.=" AND (b.flurstkennz LIKE ";
      $sql.=" '".$gemkgschl[0]."%'";
      for($i = 1; $i < count($gemkgschl); $i++){
        $sql.=" OR b.flurstkennz LIKE '".$gemkgschl[$i]."%'";
      }
      $sql.=")";
    }    
    if ($flur>0) {
      $sql.=" AND e.bezirk=b.bezirk AND e.blatt=b.blatt";
      $sql.=" AND substring(TRIM(b.flurstkennz) from 8 for 3) = '".$flur."'";
    }
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
      while($rs=pg_fetch_array($ret[1])) {
        $namen[]=$rs;
      }
      $ret[1]=$namen;
    }
    return $ret;
  }
  
  function getNamenALKIS($n1,$n2,$n3,$n4,$bezirk,$blatt,$gemkgschl,$flur,$limitAnzahl,$limitStart,$caseSensitive, $order) {
    $name[1]=$n1; $name[2]=$n2; $name[3]=$n3; $name[4]=$n4;    
    $sql = "SELECT distinct namensnummer.laufendenummernachdin1421 AS lfd_nr_name, person.nachnameoderfirma, person.vorname, person.akademischergrad, person.geburtsname, person.geburtsdatum, anschrift.strasse, anschrift.hausnummer, anschrift.postleitzahlpostzustellung, anschrift.ort_post, blatt.buchungsblattnummermitbuchstabenerweiterung as blatt, bezirk.schluesselgesamt as bezirk";
		$sql.= " FROM alkis.ax_flurstueck f, alkis.alkis_beziehungen flst2buchung, alkis.ax_buchungsstelle buchung,alkis.alkis_beziehungen buchung2blatt,alkis.ax_buchungsblattbezirk bezirk,alkis.ax_buchungsblatt blatt,alkis.alkis_beziehungen blatt2namensnummer,alkis.ax_namensnummer namensnummer,alkis.alkis_beziehungen namensnummer2person,alkis.ax_person person";
		$sql.= " LEFT JOIN alkis.alkis_beziehungen person2anschrift ON person2anschrift.beziehung_von = person.gml_id";
		$sql.= " LEFT JOIN alkis.ax_anschrift anschrift ON person2anschrift.beziehung_zu = anschrift.gml_id ";
		$sql.= " WHERE flst2buchung.beziehungsart::text = 'istGebucht'::text";
		$sql.= " AND f.gml_id = flst2buchung.beziehung_von";
		$sql.= " AND flst2buchung.beziehung_zu = buchung.gml_id";
		$sql.= " AND buchung2blatt.beziehungsart::text = 'istBestandteilVon'::text"; 
		$sql.= " AND buchung2blatt.beziehung_von = buchung.gml_id";
		$sql.= " AND buchung2blatt.beziehung_zu = blatt.gml_id";
		$sql.= " AND blatt.land = bezirk.land AND blatt.bezirk = bezirk.bezirk";
		$sql.= " AND blatt2namensnummer.beziehungsart::text = 'istBestandteilVon'::text"; 
		$sql.= " AND blatt2namensnummer.beziehung_zu = blatt.gml_id";
		$sql.= " AND blatt2namensnummer.beziehung_von = namensnummer.gml_id";
		$sql.= " AND namensnummer2person.beziehungsart::text = 'benennt'::text";   
		$sql.= " AND namensnummer2person.beziehung_von = namensnummer.gml_id";
		$sql.= " AND namensnummer2person.beziehung_zu = person.gml_id";
		$sql.= " AND person2anschrift.beziehungsart::text = 'hat'::text";
    if($name[1] != '%%')$sql.=" AND lower(nachnameoderfirma||', '||vorname) LIKE lower('".$name[1]."') ";
    if($name[2] != '%%')$sql.=" AND lower(geburtsname||', '||geburtsdatum) LIKE lower('".$name[2]."') ";
    if($name[3] != '%%')$sql.=" AND (lower(strasse) LIKE lower('".$name[3]."') OR lower(strasse||' '||hausnummer) LIKE lower('".$name[3]."'))";
    if($name[4] != '%%')$sql.=" AND (postleitzahlpostzustellung LIKE '".$name[4]."' OR lower(postleitzahlpostzustellung||' '||ort_post) LIKE lower('".$name[4]."'))";

    if($bezirk!='') {
      $sql.=" AND bezirk.schluesselgesamt=".(int)$bezirk;
    }
    if($blatt != ''){
      $sql.=" AND blatt.buchungsblattnummermitbuchstabenerweiterung= '".$blatt."'";
    }   
    if ($gemkgschl>0) {
      $sql.=" AND f.land*10000 + f.gemarkungsnummer IN (".implode(',', $gemkgschl).")";
    }    
    if ($flur>0) {
      $sql.=" AND lpad(f.flurnummer::text, 3, '0') = '".$flur."'";
    }
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
	      $namen[$i]['name2'] = $rs['geburtsname'].' '.$rs['geburtsdatum'];
	      $namen[$i]['name3'] = $rs['strasse'].' '.$rs['hausnummer'];
	      $namen[$i]['name4'] = $rs['postleitzahlpostzustellung'].' '.$rs['ort_post'];
        $i++;
      }
      $ret[1]=$namen;
    }
    return $ret;
  }

  function updateLfdNrName() {
    $sql ="UPDATE alb_".$this->tableprefix."g_namen SET lfd_nr_name_alt=alb_g_namen.lfd_nr_name";
#Eingefï¿½gt 11.04.2006 H. Riedel
    $sql.=" FROM alb_g_namen";
    $sql.=" WHERE alb_g_namen.name4=alb_".$this->tableprefix."g_namen.name4 AND alb_g_namen.name3=alb_".$this->tableprefix."g_namen.name3";
    $sql.=" AND alb_g_namen.name2=alb_".$this->tableprefix."g_namen.name2 AND alb_g_namen.name1=alb_".$this->tableprefix."g_namen.name1";
    return $this->execSQL($sql, 4, 0);
  }

  function getAnzKatasteraemter() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_katasteraemter";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertKatasteramt($Katasteramt,$ArtAmt,$Name) {
    # 2005-12-27 pk, identische ignorieren
    $sql ="INSERT INTO alb_".$this->tableprefix."v_katasteraemter";
    $sql.=" (SELECT '".$Katasteramt."','".$ArtAmt."','".$Name."' WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."v_katasteraemter";
    $sql.=" WHERE katasteramt='".$Katasteramt."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceKatasteraemter() {
    $sql ="DELETE FROM alb_v_katasteraemter";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_katasteraemter";
    }
    $sql.=" WHERE alb_v_katasteraemter.katasteramt=alb_".$this->tableprefix."v_katasteraemter.katasteramt";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_katasteraemter SELECT * FROM alb_".$this->tableprefix."v_katasteraemter";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzForstaemter() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_forstaemter";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertForstamt($Forstamt,$Name) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_forstaemter (SELECT '".$Forstamt."','".$Name."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_forstaemter WHERE forstamt='".$Forstamt."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function getForstamt($FlurstKennz) {
    $sql ="SELECT a.forstamt AS schluessel,a.name FROM alb_flurstuecke AS f,alb_v_forstaemter AS a";
    $sql.=" WHERE f.forstamt=a.forstamt AND f.flurstkennz = '".$FlurstKennz."'";
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

  function replaceForstaemter() {
    $sql ="DELETE FROM alb_v_forstaemter";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_forstaemter";
    }
    $sql.=" WHERE alb_v_forstaemter.forstamt=alb_".$this->tableprefix."v_forstaemter.forstamt";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_forstaemter SELECT * FROM alb_".$this->tableprefix."v_forstaemter";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzFinanzaemter() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_finanzaemter";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertFinanzamt($Finanzamt,$Name) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_finanzaemter (SELECT '".$Finanzamt."','".$Name."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_finanzaemter WHERE finanzamt='".$Finanzamt."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function getFinanzamt($FlurstKennz) {
    $sql ="SELECT fa.finanzamt AS schluessel,fa.name AS name FROM alb_flurstuecke AS f,alb_v_finanzaemter AS fa";
    $sql.=" WHERE fa.finanzamt=f.finanzamt AND f.flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      if ($rs['schluessel']=='') {
        $Finanzamt['name']="ungebucht";
      }
      else {
        $ret[1]=$Finanzamt;
      }
    }
    return $ret;
  }

  function replaceFinanzaemter() {
    $sql ='DELETE FROM alb_v_finanzaemter';
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=' USING alb_'.$this->tableprefix.'v_finanzaemter';
    }
    $sql.=' WHERE alb_v_finanzaemter.finanzamt=alb_'.$this->tableprefix.'v_finanzaemter.finanzamt';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ='INSERT INTO alb_v_finanzaemter SELECT * FROM alb_'.$this->tableprefix.'v_finanzaemter';
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzAmtsgerichte() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_amtsgerichte";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertAmtsgericht($Amtsgericht,$Name) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_amtsgerichte (SELECT '".$Amtsgericht."','".$Name."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_amtsgerichte WHERE amtsgericht='".$Amtsgericht."'))";
    return $this->execSQL($sql, 4, 0);
  }

	function getAmtsgerichtbyFlurst($flurstkennz){
		$sql ="SELECT DISTINCT gb.amtsgericht AS schluessel,a.name FROM alb_g_buchungen AS b,alb_flurstuecke AS f";
    $sql.=",alb_v_grundbuchbezirke AS gb,alb_v_amtsgerichte AS a";
    $sql.=" WHERE gb.grundbuchbezschl=b.bezirk AND b.flurstkennz=f.flurstkennz";
    $sql.=" AND gb.amtsgericht=a.amtsgericht AND f.flurstkennz='".$flurstkennz."'";
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
	
	function getAmtsgerichtbyBezirkALKIS($bezirk){
		$sql ="SELECT a.bezeichnung as name, a.stelle as schluessel";
		$sql.=" FROM alkis.ax_buchungsblattbezirk b , alkis.ax_dienststelle a";
		$sql.=" WHERE b.land=a.land AND b.stelle=a.stelle AND a.stellenart=1000";
		$sql.=" AND b.schluesselgesamt = ".$bezirk['schluessel'];
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

  function getAmtsgericht($GemkgSchl) {
    $sql ="SELECT DISTINCT gb.amtsgericht AS schluessel,a.name FROM alb_g_buchungen AS b,alb_flurstuecke AS f";
    $sql.=",alb_v_grundbuchbezirke AS gb,alb_v_amtsgerichte AS a";
    $sql.=" WHERE gb.grundbuchbezschl=b.bezirk AND b.flurstkennz=f.flurstkennz";
    $sql.=" AND gb.amtsgericht=a.amtsgericht AND f.gemkgschl=".(int)$GemkgSchl;
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
  
  function replaceAmtsgerichte() {
    $sql ="DELETE FROM alb_v_amtsgerichte";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_amtsgerichte";
    }
    $sql.=" WHERE alb_v_amtsgerichte.amtsgericht=alb_".$this->tableprefix."v_amtsgerichte.amtsgericht";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_amtsgerichte SELECT * FROM alb_".$this->tableprefix."v_amtsgerichte";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzAusfuehrendeStellen() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_ausfuehrendestellen";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertAusfuehrendeStelle($AusfStelle,$Name) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_ausfuehrendestellen (SELECT '".$AusfStelle."','".$Name."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_ausfuehrendestellen WHERE ausfstelle='".$AusfStelle."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceAusfuehrendeStellen() {
    $sql ='DELETE FROM alb_v_ausfuehrendestellen';
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=' USING alb_'.$this->tableprefix.'v_ausfuehrendestellen';
    }
    $sql.=' WHERE alb_v_ausfuehrendestellen.ausfstelle = alb_'.$this->tableprefix.'v_ausfuehrendestellen.ausfstelle';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ='INSERT INTO alb_v_ausfuehrendestellen SELECT * FROM alb_'.$this->tableprefix.'v_ausfuehrendestellen';
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzKreise() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_kreise";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertKreis($Kreis,$Name) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_kreise (SELECT '".$Kreis."','".$Name."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_kreise WHERE kreis='".$Kreis."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function getKreisName($KreisSchl) {
    $sql = "SELECT kreisname AS kreisname FROM alb_v_kreise WHERE kreis =".(int)$KreisSchl;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]='';
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=$rs['kreisname'];
    }
    return $ret;
  }

  function replaceKreise() {
    $sql ="DELETE FROM alb_v_kreise";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_kreise";
    }
    $sql.=" WHERE alb_v_kreise.kreis = alb_".$this->tableprefix."v_kreise.kreis";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_kreise SELECT * FROM alb_".$this->tableprefix."v_kreise";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzGemeinden() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_gemeinden";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertGemeinde($Gemeinde,$Name) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_gemeinden (SELECT '".$Gemeinde."','".$Name."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_gemeinden WHERE gemeinde='".$Gemeinde."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceGemeinden() {
    $sql ="DELETE FROM alb_v_gemeinden";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_gemeinden";
    }
    $sql.=" WHERE alb_v_gemeinden.gemeinde = alb_".$this->tableprefix."v_gemeinden.gemeinde";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_gemeinden SELECT * FROM alb_".$this->tableprefix."v_gemeinden";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzGemarkungen() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_gemarkungen";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertGemarkung($GemkgSchl,$Gemeinde,$Amtsgericht,$GemkgName) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_gemarkungen (SELECT '".$GemkgSchl."','".$Gemeinde."','".$Amtsgericht."'";
    $sql.=",'".$GemkgName."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_gemarkungen WHERE gemkgschl=".$GemkgSchl."))";
    return $this->execSQL($sql, 4, 0);
  }

  function getGemarkungName($GemkgSchl) {
    $sql ="SELECT gemkgname FROM alb_v_gemarkungen WHERE gemkgschl=".(int)$GemkgSchl;
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
  
  function getGemarkungNameALKIS($GemkgSchl) {
    $sql ="SELECT bezeichnung as gemkgname FROM alkis.ax_gemarkung WHERE land*10000 + gemarkungsnummer = ".$GemkgSchl;
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

  function replaceGemarkungen() {
    $sql ="DELETE FROM alb_v_gemarkungen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_gemarkungen";
    }
    $sql.=" WHERE alb_v_gemarkungen.gemkgschl = alb_".$this->tableprefix."v_gemarkungen.gemkgschl";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_gemarkungen SELECT * FROM alb_".$this->tableprefix."v_gemarkungen";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzGrundbuchbezirke() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_grundbuchbezirke";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertGrundbuchbezirk($GrundbuchbezSchl,$Amtsgericht,$Bezeichnung) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_grundbuchbezirke (SELECT '".$GrundbuchbezSchl."','".$Amtsgericht."','".$Bezeichnung."'";
    $sql.=" WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."v_grundbuchbezirke";
    $sql.=" WHERE grundbuchbezschl='".$GrundbuchbezSchl."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function getAktualitaetsNr($FlurstKennz) {
    $sql = "SELECT aktunr FROM alb_flurstuecke WHERE flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]='';
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=$rs['aktunr'];
    }
    return $ret;
  }

	function getGrundbuchblattliste($bezirk){
		$sql = "SELECT * FROM alb_grundbuecher WHERE bezirk = ".$bezirk." AND aktualitaetsnr != 'hist' ORDER BY blatt";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
      	$liste['blatt'][]=$rs['blatt'];
    	}
    }
    return $liste;
	}
	
	function getGrundbuchblattlisteALKIS($bezirk){
		$sql = "SELECT buchungsblattnummermitbuchstabenerweiterung as blatt FROM alkis.ax_buchungsblatt WHERE land*10000 + bezirk = ".$bezirk." ORDER BY blatt";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
      	$liste['blatt'][]=$rs['blatt'];
    	}
    }
    return $liste;
	}

  function getGrundbuchbezirksliste(){
  	$sql ="SELECT * FROM alb_v_grundbuchbezirke";
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
  
  function getGrundbuchbezirkslisteALKIS(){
  	$sql ="SELECT schluesselgesamt as grundbuchbezschl, bezeichnung FROM alkis.ax_buchungsblattbezirk";
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

  function getGrundbuchbezirkslisteByGemkgIDs($gemkg_ids){
  	$sql = "SELECT DISTINCT g.* FROM alb_v_grundbuchbezirke as g, alb_g_buchungen as b, alb_flurstuecke AS f ";
		$sql.= "WHERE b.bezirk = g.grundbuchbezschl ";
		$sql.= "AND f.flurstkennz = b.flurstkennz ";
		$sql.= "AND f.gemkgschl IN (".$gemkg_ids[0];
		for($i = 1; $i < count($gemkg_ids); $i++){
			$sql.= ",".$gemkg_ids[$i];
		}
		$sql.= ")";
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
  
  function getGrundbuchbezirkslisteByGemkgIDsALKIS($gemkg_ids) {
		$sql ="SELECT DISTINCT bezirk.schluesselgesamt as grundbuchbezschl, bezirk.bezeichnung";
		$sql.=" FROM alkis.ax_flurstueck f, alkis.alkis_beziehungen flst2buchung, alkis.ax_buchungsstelle buchung, alkis.alkis_beziehungen buchung2blatt, alkis.ax_buchungsblattbezirk bezirk, alkis.ax_buchungsblatt blatt";  
		$sql.=" WHERE flst2buchung.beziehungsart::text = 'istGebucht'::text";
		$sql.=" AND f.gml_id = flst2buchung.beziehung_von";
		$sql.=" AND flst2buchung.beziehung_zu = buchung.gml_id";
		$sql.=" AND buchung2blatt.beziehungsart::text = 'istBestandteilVon'::text"; 
		$sql.=" AND buchung2blatt.beziehung_von = buchung.gml_id";
		$sql.=" AND buchung2blatt.beziehung_zu = blatt.gml_id";
		$sql.=" AND blatt.land = bezirk.land AND blatt.bezirk = bezirk.bezirk";
		$sql.=" AND f.land*10000 + f.gemarkungsnummer IN (".implode(',', $gemkg_ids).")";
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
  

  function getGrundbuchbezirke($FlurstKennz) {
		$sql ="SELECT b.bezirk AS Schluessel,g.bezeichnung AS Name FROM alb_g_buchungen AS b,alb_v_grundbuchbezirke AS g, alb_grundbuecher AS gb";
    $sql.=" WHERE b.bezirk=g.grundbuchbezschl AND b.flurstkennz = '".$FlurstKennz."'";
    $sql.=" AND b.bezirk=gb.bezirk AND b.blatt=gb.blatt";
    $sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $Bezirk['Name']="nicht gefunden";
      $Bezirk['Schluessel']="0";
    }
    else {
      if (pg_num_rows($ret[1])==0) {
        $Bezirk['Name']="nicht gefunden";
        $Bezirk['Schluessel']="0";
      }
      else {
        $Bezirk=pg_fetch_array($ret[1]);
      }
    }
    return $Bezirk;
  }
  
  function getGrundbuchbezirkeALKIS($FlurstKennz) {
		$sql ="SELECT bezirk.schluesselgesamt as Schluessel, bezirk.bezeichnung AS Name";
		$sql.=" FROM alkis.ax_flurstueck f, alkis.alkis_beziehungen flst2buchung, alkis.ax_buchungsstelle buchung, alkis.alkis_beziehungen buchung2blatt, alkis.ax_buchungsblattbezirk bezirk, alkis.ax_buchungsblatt blatt";  
		$sql.=" WHERE flst2buchung.beziehungsart::text = 'istGebucht'::text";
		$sql.=" AND f.gml_id = flst2buchung.beziehung_von";
		$sql.=" AND flst2buchung.beziehung_zu = buchung.gml_id";
		$sql.=" AND buchung2blatt.beziehungsart::text = 'istBestandteilVon'::text"; 
		$sql.=" AND buchung2blatt.beziehung_von = buchung.gml_id";
		$sql.=" AND buchung2blatt.beziehung_zu = blatt.gml_id";
		$sql.=" AND blatt.land = bezirk.land AND blatt.bezirk = bezirk.bezirk";
		$sql.=" AND f.flurstueckskennzeichen = '".$FlurstKennz."'";
		#echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $Bezirk['Name']="nicht gefunden";
      $Bezirk['Schluessel']="0";
    }
    else {
      if (pg_num_rows($ret[1])==0) {
        $Bezirk['Name']="nicht gefunden";
        $Bezirk['Schluessel']="0";
      }
      else {
        $Bezirk=pg_fetch_array($ret[1]);
      }
    }
    return $Bezirk;
  }

  function replaceGrundbuchbezirke() {
    $sql ="DELETE FROM alb_v_grundbuchbezirke";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_grundbuchbezirke";
    }
    $sql.=" WHERE alb_v_grundbuchbezirke.grundbuchbezschl = alb_".$this->tableprefix."v_grundbuchbezirke.grundbuchbezschl";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_grundbuchbezirke SELECT * FROM alb_".$this->tableprefix."v_grundbuchbezirke";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzStrassen() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_strassen";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertStrasse($Gemeinde,$Strasse,$Name) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_strassen (SELECT '".$Gemeinde."','".$Strasse."','".$Name."'";
    $sql.=" WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."v_strassen";
    $sql.=" WHERE gemeinde='".$Gemeinde."' AND strasse='".$Strasse."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceStrassen() {
    # 2006-01-23
    $sql ="DELETE FROM alb_v_strassen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_strassen";
    }
    $sql.=" WHERE alb_v_strassen.gemeinde = alb_".$this->tableprefix."v_strassen.gemeinde";
    $sql.=" AND alb_v_strassen.strasse = alb_".$this->tableprefix."v_strassen.strasse";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_strassen SELECT * FROM alb_".$this->tableprefix."v_strassen";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzEigentuemerarten() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_eigentuemerarten";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertEigentuemerart($Eigentuemerart,$Bezeichnung) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_eigentuemerarten (SELECT '".$Eigentuemerart."','".$Bezeichnung."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_eigentuemerarten WHERE eigentuemerart='".$Eigentuemerart."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceEigentuemerArten() {
    $sql ='DELETE FROM alb_v_eigentuemerarten';
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=' USING alb_'.$this->tableprefix.'v_eigentuemerarten';
    }
    $sql.=' WHERE alb_v_eigentuemerarten.eigentuemerart = alb_'.$this->tableprefix.'v_eigentuemerarten.eigentuemerart';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ='INSERT INTO alb_v_eigentuemerarten SELECT * FROM alb_'.$this->tableprefix.'v_eigentuemerarten';
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzBuchungsarten() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_buchungsarten";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertBuchungsart($Buchungsart,$Bezeichnung) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_buchungsarten (SELECT '".$Buchungsart."','".$Bezeichnung."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_buchungsarten WHERE buchungsart='".$Buchungsart."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceBuchungsarten() {
    $sql ='DELETE FROM alb_v_buchungsarten';
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=' USING alb_'.$this->tableprefix.'v_buchungsarten';
    }
    $sql.=' WHERE alb_v_buchungsarten.buchungsart = alb_'.$this->tableprefix.'v_buchungsarten.buchungsart';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ='INSERT INTO alb_v_buchungsarten SELECT * FROM alb_'.$this->tableprefix.'v_buchungsarten';
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzHinweise() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_hinweise";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertHinweisart($HinwZFlst,$Bezeichnung) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_hinweise (SELECT '".$HinwZFlst."','".$Bezeichnung."' WHERE NOT EXISTS (SELECT 1 FROM";
    $sql.=" alb_".$this->tableprefix."v_hinweise WHERE hinwzflst='".$HinwZFlst."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceHinweise() {
    $sql ='DELETE FROM alb_v_hinweise';
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=' USING alb_'.$this->tableprefix.'v_hinweise';
    }
    $sql.=' WHERE alb_v_hinweise.hinwzflst = alb_'.$this->tableprefix.'v_hinweise.hinwzflst';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ='INSERT INTO alb_v_hinweise SELECT * FROM alb_'.$this->tableprefix.'v_hinweise';
      return $this->execSQL($sql, 4, 0);
    }
  }

  function deleteHistHinweise() {
    $sql ="DELETE FROM alb_f_hinweise";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_hinweise.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function getAnzNutzungsarten() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_nutzungsarten";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertNutzungsart($Nutzungsart,$Bezeichnung,$Abkuerzung) {
    $Bezeichnung=trim($Bezeichnung);
    $Abkuerzung=trim($Abkuerzung);
    $sql ="INSERT INTO alb_".$this->tableprefix."v_nutzungsarten (SELECT '".$Nutzungsart."','".$Bezeichnung."','".$Abkuerzung."'";
    $sql.=" WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."v_nutzungsarten WHERE nutzungsart='".$Nutzungsart."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceNutzungsarten() {
    $sql ="DELETE FROM alb_v_nutzungsarten";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."v_nutzungsarten";
    }
    $sql.=" WHERE alb_v_nutzungsarten.nutzungsart = alb_".$this->tableprefix."v_nutzungsarten.nutzungsart";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ="INSERT INTO alb_v_nutzungsarten SELECT * FROM alb_".$this->tableprefix."v_nutzungsarten";
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzKlassifizierungen() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_klassifizierungen";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertKlassifizierungsart($TabKenn,$Klass,$Bezeichnung,$Abkuerzung) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_klassifizierungen (SELECT '".$TabKenn."','".$Klass."','".$Bezeichnung."','".$Abkuerzung."'";
    $sql.=" WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."v_klassifizierungen WHERE tabkenn='".$TabKenn."'";
    $sql.=" AND klass='".$Klass."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceKlassifizierungen() {
    $sql ='DELETE FROM alb_v_klassifizierungen';
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=' USING alb_'.$this->tableprefix.'v_klassifizierungen';
    }
    $sql.=' WHERE alb_v_klassifizierungen.klass = alb_'.$this->tableprefix.'v_klassifizierungen.klass';
    $sql.=' AND alb_v_klassifizierungen.tabkenn = alb_'.$this->tableprefix.'v_klassifizierungen.tabkenn';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ='INSERT INTO alb_v_klassifizierungen SELECT * FROM alb_'.$this->tableprefix.'v_klassifizierungen';
      return $this->execSQL($sql, 4, 0);
    }
  }

  function deleteHistKlassifizierungen() {
    $sql ="DELETE FROM alb_f_klassifizierungen";
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alb_".$this->tableprefix."flurstuecke";
    }
    $sql.=" WHERE alb_f_klassifizierungen.flurstkennz=alb_".$this->tableprefix."flurstuecke.flurstkennz";
    $sql.=" AND alb_".$this->tableprefix."flurstuecke.status = 'H'";
    return $this->execSQL($sql, 4, 0);
  }

  function getAnzBemerkungenZumVerfahren() {
    $sql ="SELECT count(*) AS anzahl FROM alb_v_bemerkgzumverfahren";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $ret[1]=$rs['anzahl'];
    }
    return $ret;
  }

  function insertBemerkgZumVerfahren($VerfBemerkung,$Bezeichnung) {
    $sql ="INSERT INTO alb_".$this->tableprefix."v_bemerkgzumverfahren (SELECT '".$VerfBemerkung."','".$Bezeichnung."'";
    $sql.=" WHERE NOT EXISTS (SELECT 1 FROM alb_".$this->tableprefix."v_bemerkgzumverfahren";
    $sql.=" WHERE verfbem='".$VerfBemerkung."'))";
    return $this->execSQL($sql, 4, 0);
  }

  function replaceBemerkungenZumVerfahren() {
    $sql ='DELETE FROM alb_v_bemerkgzumverfahren';
    #Eingefï¿½gt 11.04.2006 H. Riedel
    if(POSTGRESVERSION >= '810'){
      $sql.=' USING alb_'.$this->tableprefix.'v_bemerkgzumverfahren';
    }
    $sql.=' WHERE alb_v_bemerkgzumverfahren.verfbem = alb_'.$this->tableprefix.'v_bemerkgzumverfahren.verfbem';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ='INSERT INTO alb_v_bemerkgzumverfahren SELECT * FROM alb_'.$this->tableprefix.'v_bemerkgzumverfahren';
      return $this->execSQL($sql, 4, 0);
    }
  }

  function updateFluren() {
    $sql ='DELETE FROM alb_z_fluren';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      return $ret;
    }
    else {
      $sql ='INSERT INTO alb_z_fluren SELECT DISTINCT gemkgschl, flurnr FROM alb_flurstuecke';
      return $this->execSQL($sql, 4, 0);
    }
  }

  function getAnzFluren() {
    $sql ='SELECT COUNT(*) AS anzahl FROM alb_z_fluren';
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      $rs=pg_fetch_array($ret[1]);
      $anzahl=$rs['anzahl'];
    }
    return $anzahl;
  }

  # Funktion zum Auffï¿½llen einer temporï¿½ren Tabelle mit Eintrï¿½gen aller Adressen, die in der ALK und ALB vorkommen.
  function updateTempAdressTable() {
    # 2006-01-02 pk
    # Leeren der Tabelle
    $sql ='TRUNCATE alb_tmp_adressen';
    $this->debug->write('<br>postgres.php: updateAdressTable<br>Lï¿½schen der temporï¿½ren Adresstabelle<br>'.$sql,4);
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { return 'Abbruch in '.$PHP_SELF.' updateAdressTable() Zeile: '.__LINE__.'<br>'.$sql; }

    # Abfragen aller Adressen aus dem ALB (alb_f_adressen) und schreiben in temporï¿½re Tabelle
    $sql ="INSERT INTO alb_tmp_adressen (SELECT DISTINCT 'ALB' AS quelle,g.gemeinde,g.gemeindename,s.strasse,s.strassenname,TRIM(a.hausnr) AS hausnr";
    $sql.=" FROM alb_f_adressen AS a,alb_v_gemeinden AS g,alb_v_strassen AS s";
    $sql.=" WHERE a.gemeinde=g.gemeinde AND a.strasse=s.strasse AND a.gemeinde = s.gemeinde)";
    $this->debug->write('<br>postgres.php updateAdressTable<br>Auffï¿½llen der temporï¿½ren Adresstabelle mit Adressen des ALB<br>'.$sql,4);
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { return 'Abbruch in '.$PHP_SELF.' updateAdressTable() Zeile: '.__LINE__.'<br>'.$sql; }

    # aller Adressen aus der ALK (alknhaus) und schreiben in temporï¿½re Tabelle
    $sql ="INSERT INTO alb_tmp_adressen (SELECT DISTINCT 'ALK' AS quelle,h.gemeinde,g.gemeindename,h.strasse,s.strassenname,h.hausnr";
    $sql.=" FROM alknhaus AS h, alb_v_gemeinden AS g, alb_v_strassen AS s";
    $sql.=" WHERE h.gemeinde=g.gemeinde AND h.gemeinde=s.gemeinde AND h.strasse=s.strasse)";
    $this->debug->write('<br>postgres.php updateAdressTable<br>Auffï¿½llen der temporï¿½ren Adresstabelle mit Adressen der ALK<br>'.$sql,4);
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { return 'Abbruch in '.$PHP_SELF.' updateAdressTable() Zeile: '.__LINE__.'<br>'.$sql; }
  }

  function getAdressenListe($GemID,$PolygonWKTString,$order) {
    # 2006-01-11
    # Abfragen der Adressen
    $sql ="SELECT a.quelle,a.gemeinde,a.strasse,a.strassenname,a.hausnr";
    $sql.=" FROM alb_tmp_adressen AS a,alknhaus AS alkh,alkobj_e_fla AS hfl";
    $sql.=" WHERE hfl.folie='011'";
    # $sql.=" AND hfl.the_geom && st_geometryfromtext('".$PolygonWKTString."',".EPSGCODE.")";
    if ($PolygonWKTString!='') {
      $sql.=" AND st_intersects(hfl.the_geom,st_geometryfromtext('".$PolygonWKTString."',".EPSGCODE."))";
    }
    $sql.=" AND hfl.objnr=alkh.objnr AND alkh.gemeinde=a.gemeinde AND alkh.strasse=a.strasse AND alkh.hausnr=a.hausnr";
    if ($GemID!='') {
      $sql.=" AND alkh.gemeinde=".(int)$GemID;
    }
    $sql.=" UNION";
    $sql.=" SELECT a.quelle,a.gemeinde,a.strasse,a.strassenname,a.hausnr";
    $sql.=" FROM alkobj_e_fla AS ffl, alknflst AS alkf, alb_f_adressen AS albf, alb_tmp_adressen AS a";
    $sql.=" WHERE ffl.folie='001'";
    # $sql.=" AND ffl.the_geom && st_geometryfromtext('".$PolygonWKTString."',".EPSGCODE.")";
    if ($PolygonWKTString!='') {
      $sql.=" AND st_intersects(ffl.the_geom,st_geometryfromtext('".$PolygonWKTString."',".EPSGCODE."))";
    }
    $sql.=" AND ffl.objnr=alkf.objnr AND alkf.flurstkennz=albf.flurstkennz";
    $sql.=" AND albf.gemeinde=a.gemeinde AND albf.strasse=a.strasse AND albf.hausnr=a.hausnr";
    if ($GemID!='') {
      $sql.=" AND albf.gemeinde=".(int)$GemID;
    }
    if ($order!='') {
      $sql.=' ORDER BY '.$order;
    }
    $this->debug->write("<p>postgres getAdressenListe Abfragen der Strassendaten:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['Quelle'][]=$rs['quelle'];
      $Liste['Gemeinde'][]=$rs['gemeinde'];
      $Liste['StrID'][]=$rs['strasse'];
      $Liste['Name'][]=$rs['strassenname'];
      $Liste['HausNr'][]=$rs['hausnr'];
    }
    return $Liste;
  }

  function getHausNrListe($GemID,$StrID,$HausNr,$PolygonWKTString,$order) {
    # 2006-01-31
    $order='ordernr';
    # Abfragen der Hausnummern
    $sql ="SELECT id,nrtext,ordernr FROM (";
    # Subselect zum Zusammenfassen und Sortieren der Bestandteile der Abfrage
    $sql.="SELECT DISTINCT CASE WHEN TRIM(nr)='' THEN 'ohne' ELSE LOWER(id) END AS id, CASE WHEN TRIM(nr)='' THEN 'ohne Nr' ELSE TRIM(nr) END AS nrtext";
    $sql.=",CAST (CASE WHEN TRIM(ordernr)='' THEN '0' ELSE SPLIT_PART(TRIM(ordernr),' ',1) END AS numeric) as ordernr FROM (";
    # Abfrage der leeren Auswahlzeile
    $sql.="SELECT '-1' AS id,'--Auswahl--' AS nr, '-1' AS ordernr";
    $sql.=" UNION";
    # Abfrage der Hausnummern aus dem ALK-Bestand
    $sql.=" SELECT '".$GemID."-".$StrID."-'||TRIM(".HAUSNUMMER_TYPE."(alk.hausnr)) AS id,".HAUSNUMMER_TYPE."(alk.hausnr) AS nrtext,alk.hausnr AS ordernr";
    $sql.=" FROM alknhaus AS alk,alkobj_e_fla AS alkfl,alb_v_strassen AS s";
    $sql.=" WHERE alkfl.folie='011'";
    # $sql.=" AND alkfl.the_geom && st_geometryfromtext('".$PolygonWKTString."',".EPSGCODE.")";
    if ($PolygonWKTString!='') {
      $sql.=" AND st_intersects(alkfl.the_geom,st_geometryfromtext('".$PolygonWKTString."',".EPSGCODE."))";
    }
    $sql.=" AND alk.objnr=alkfl.objnr AND alk.gemeinde=s.gemeinde AND alk.strasse=s.strasse";
    if ($GemID!='') {
      $sql.=" AND alk.gemeinde=".(int)$GemID;
    }
    if ($StrID!='') {
      $sql.=" AND alk.strasse='".$StrID."'";
    }
    # Ende
    $sql.=" UNION";
    # Abfrage der Hausnummern aus dem ALB-Bestand
    # Anfang
    $sql.=" SELECT '".$GemID."-".$StrID."-'||alb.hausnr AS id, ".HAUSNUMMER_TYPE."(alb.hausnr) AS nrtext,alb.hausnr AS ordernr FROM alb_f_adressen AS alb, alb_flurstuecke as f WHERE (1=1) AND f.flurstkennz = alb.flurstkennz AND f.status != 'H' ";
    if ($GemID!='') {
      $sql.=" AND alb.gemeinde=".(int)$GemID;
    }
    if ($StrID!='') {
      $sql.=" AND alb.strasse='".$StrID."'";
    }
    # ende
    $sql.=") AS foo ORDER BY ".$order;
    $sql.=") AS foofoo";
    #echo $sql;
    $this->debug->write("<p>postgres getHausNrListe Abfragen der Strassendaten:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['HausID'][]=$rs['id'];
      $Liste['HausNr'][]=$rs['nrtext'];
    }
    return $Liste;
  }
  
  function getHausNrListeALKIS($GemID,$StrID,$HausNr,$PolygonWKTString,$order) {
    # 2006-01-31
    $order='ordernr';
    # Abfragen der Hausnummern
    $sql ="SELECT id,nrtext, to_number(ordernr, '999999') as ordernr FROM (";
    $sql.="SELECT DISTINCT CASE WHEN TRIM(nr)='' THEN 'ohne' ELSE LOWER(id) END AS id, CASE WHEN TRIM(nr)='' THEN 'ohne Nr' ELSE TRIM(nr) END AS nrtext";
    $sql.=",(CASE WHEN TRIM(ordernr)='' THEN '0' ELSE SPLIT_PART(TRIM(ordernr),' ',1) END) as ordernr FROM (";
    $sql.=" SELECT DISTINCT '".$GemID."-".$StrID."-'||TRIM(".HAUSNUMMER_TYPE."(l.hausnummer)) AS id, ".HAUSNUMMER_TYPE."(l.hausnummer) AS nr, l.hausnummer AS ordernr";
    $sql.=" FROM alkis.ax_flurstueck as f, alkis.ax_gemeinde as g, alkis.alkis_beziehungen v";
    $sql.=" JOIN alkis.ax_lagebezeichnungmithausnummer l ON v.beziehung_zu=l.gml_id";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde";
    $sql.=" AND l.lage = lpad(s.lage,5,'0')";
    $sql.=" WHERE v.beziehung_von=f.gml_id AND v.beziehungsart='weistAuf' AND g.gemeinde = l.gemeinde";
    if ($GemID!='') {
      $sql.=" AND g.schluesselgesamt=".(int)$GemID;
    }
    if ($StrID!='') {
      $sql.=" AND l.lage='".$StrID."'";
    }
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
  

  function getStrassenListe($GemID,$PolygonWKTString,$order) {
    # 2006-01-30
    # Abfragen der Strassen
    $sql ="SELECT gemeinde,strasse,strassenname,gemkgname FROM (";
    # Subselect zum Zusammenfassen und Sortieren der Bestandteile der Abfrage
    $sql.="SELECT DISTINCT gemeinde,strasse,strassenname,ordertext,gemkgname FROM (";
    # Abfage zum Einfï¿½gen der leeren Auswahlzeile
    $sql.="SELECT -1 AS gemeinde,'-1' AS strasse,'--Auswahl--' AS strassenname, 'aaa' AS ordertext, '' as gemkgname";
    $sql.=" UNION";
    # Abfrage der Strassen aus dem ALB-Bestand
    $sql.=" SELECT s.gemeinde,s.strasse,s.strassenname,s.strassenname AS ordertext, g.gemkgname";
    $sql.=" FROM alb_f_adressen AS a,alb_v_strassen AS s, alb_flurstuecke as f, alb_v_gemarkungen as g";
    $sql.=" WHERE f.status != 'H' AND f.flurstkennz = a.flurstkennz AND g.gemkgschl = f.gemkgschl AND a.gemeinde=s.gemeinde AND a.strasse=s.strasse";
    if ($GemID!='') {
      $sql.=" AND a.gemeinde=".(int)$GemID;
    }
    $sql.=") AS foo ORDER BY gemeinde,ordertext";
    $sql.=") AS foofoo";
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
  
  function getStrassenListeALKIS($GemID,$GemkgID,$PolygonWKTString,$order) {
  	$sql ="SELECT -1 AS gemeinde,'-1' AS strasse,'--Auswahl--' AS strassenname, '' as gemkgname";
    $sql.=" UNION";
    $sql.=" SELECT DISTINCT g.gemeinde, l.lage as strasse, s.bezeichnung as strassenname, gem.bezeichnung as gemkgname";
    $sql.=" FROM alkis.ax_flurstueck as f, alkis.ax_gemeinde as g, alkis.ax_gemarkung as gem, alkis.alkis_beziehungen v";
    $sql.=" JOIN alkis.ax_lagebezeichnungmithausnummer l ON v.beziehung_zu=l.gml_id";
    $sql.=" LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde";
    $sql.=" AND s.lage = lpad(l.lage,5,'0')";
    $sql.=" WHERE v.beziehung_von=f.gml_id AND v.beziehungsart='weistAuf' AND g.gemeinde = l.gemeinde";
    $sql.=" AND f.gemarkungsnummer = gem.gemarkungsnummer";
    if ($GemID!='') {
      $sql.=" AND g.schluesselgesamt=".(int)$GemID;
    }
    if ($GemkgID!='') {
      $sql.=" AND f.land*10000 + f.gemarkungsnummer=".(int)$GemkgID;
    }
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
  
    
  function getStrassenListeByGemkg($GemkgID,$PolygonWKTString,$order) {
    # 2006-01-30
    # Abfragen der Strassen
    $sql ="SELECT gemeinde,strasse,strassenname,gemkgname, gemkgschl FROM (";
    # Subselect zum Zusammenfassen und Sortieren der Bestandteile der Abfrage
    $sql.="SELECT DISTINCT gemeinde,strasse,strassenname,ordertext,gemkgname, gemkgschl FROM (";
    # Abfage zum Einfï¿½gen der leeren Auswahlzeile
    $sql.="SELECT -1 AS gemeinde,'-1' AS strasse,'--Auswahl--' AS strassenname, 'aaa' AS ordertext, '' as gemkgname, 0 as gemkgschl";
    $sql.=" UNION";
    # Abfrage der Strassen aus dem ALB-Bestand
    $sql.=" SELECT s.gemeinde,s.strasse,s.strassenname,s.strassenname AS ordertext, g.gemkgname, g.gemkgschl";
    $sql.=" FROM alb_f_adressen AS a,alb_v_strassen AS s, alb_flurstuecke as f, alb_v_gemarkungen as g";
    $sql.=" WHERE f.flurstkennz = a.flurstkennz AND g.gemkgschl = f.gemkgschl AND a.gemeinde=s.gemeinde AND a.strasse=s.strasse";
    if ($GemkgID!='') {
      $sql.=" AND g.gemkgschl=".(int)$GemkgID;
    }
    $sql.=") AS foo ORDER BY gemeinde,ordertext";
    $sql.=") AS foofoo";
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

	function getFlurenListeByGemkgIDByFlurIDALK($GemkgID,$order, $historical = false){
    $sql ="SELECT DISTINCT SUBSTRING(flurstkennz,8,3) AS flurid";
    $sql.=",gemkgschl || SUBSTRING(flurstkennz,8,3) AS gemflurid FROM alknflst WHERE 1=1";
    if ($GemkgID>0) {
      $sql.=" AND gemkgschl= '".$GemkgID."'";
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
    }
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlurID'][]=$rs['flurid'];
      $Liste['Name'][]=intval($rs['flurid']);
      $Liste['GemFlurID'][]=$rs['gemflurid'];
    }
    return $Liste;
  }

  function getFlurenListeByGemkgIDByFlurID($GemkgID,$FlurID,$order, $historical = false){
    $sql ="SELECT DISTINCT flurnr AS FlurID,flurnr AS Name";
    $sql.=",gemkgschl || flurnr AS GemFlurID FROM alb_flurstuecke ";
    if($historical == 1){
    	$sql.="WHERE status = 'H'";
    }
    else{
    	$sql.="WHERE status != 'H'";
    }
    if ($GemkgID>0) {
      $sql.=" AND gemkgschl=".(int)$GemkgID;
    }
    if ($FlurID[0]>0) {
      $sql.=" AND gemkgschl || flurnr IN (".$FlurID[0];
      for ($i=1;$i<count($FlurID);$i++) {
      $sql.=",".$FlurID[$i];
      }
      $sql.=")";
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
    }
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlurID'][]=$rs['flurid'];
      $Liste['Name'][]=intval($rs['name']);
      $Liste['GemFlurID'][]=$rs['gemflurid'];
    }
    return $Liste;
  }
  
  function getFlurenListeByGemkgIDByFlurIDALKIS($GemkgID,$FlurID,$order, $historical = false){
    $sql ="SELECT lpad(gemarkungsteilflur::text, 3, '0') AS FlurID, lpad(gemarkungsteilflur::text, 3, '0') AS Name";
    $sql.=",schluesselgesamt AS GemFlurID FROM alkis.ax_gemarkungsteilflur WHERE 1=1 ";
    
    if ($GemkgID>0) {
      $sql.=" AND land*10000 + gemarkung=".(int)$GemkgID;
    }
    if ($FlurID[0]>0) {
      $sql.=" AND schluesselgesamt IN (".$FlurID[0];
      for ($i=1;$i<count($FlurID);$i++) {
      $sql.=",".$FlurID[$i];
      }
      $sql.=")";
    }
    if ($order!='') {
      $sql.=" ORDER BY ".$order;
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
  

##########################################################################
# ALK Funktionen
##########################################################################

  #2005-11-30_pk
  function getMERfromFlurstuecke($flurstkennz, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromFlurstuecke, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Flurstï¿½cke",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkobj_e_fla as o,alknflst AS f";
    $sql.=" WHERE o.objnr=f.objnr";
    $anzflst=count($flurstkennz);
    if ($anzflst>0) {
      $sql.=" AND f.flurstkennz IN ('".$flurstkennz[0]."'";
      for ($i=1;$i<$anzflst;$i++) {
        $sql.=",'".$flurstkennz[$i]."'";
      }
      $sql.=")";
    }
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschlieï¿½enden Rechtecks um die Flurstï¿½cke.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Flurstï¿½ck nicht in Postgres Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }
  
  function getMERfromFlurstueckeALKIS($flurstkennz, $epsgcode) {
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
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschlieï¿½enden Rechtecks um die Flurstï¿½cke.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Flurstï¿½ck nicht in Postgres Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }

  # 2006-01-31 pk
  function getMERfromGebaeude($Gemeinde,$Strasse,$Hausnr, $epsgcode){
    $this->debug->write("<br>postgres.php->database->getMERfromGebaeude, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Gebaeude",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkobj_e_fla as o,alknhaus AS h";
    $sql.=" WHERE o.objnr=h.objnr";
    if ($Hausnr!='') {
    	$Hausnr = str_replace(", ", ",", $Hausnr);
    	$Hausnr = strtolower(str_replace(",", "','", $Hausnr));    	
      $sql.=" AND h.gemeinde||'-'||h.strasse||'-'||TRIM(LOWER(h.hausnr)) IN ('".$Hausnr."')";
    }
    else{
	    $sql.=" AND h.gemeinde=".(int)$Gemeinde;
	    if ($Strasse!='') {
	      $sql.=" AND h.strasse='".$Strasse."'";
	    }
    }
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschlieï¿½enden Rechtecks um die Gebï¿½ude.<br>'.$ret[1];
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
  
  function getMERfromGebaeudeALKIS($Gemeinde,$Strasse,$Hausnr, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromGebaeude, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Gebaeude",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(wkb_geometry, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkis.ax_gemeinde gem, alkis.ax_gebaeude g";
    $sql.=" LEFT JOIN alkis.alkis_beziehungen v ON g.gml_id=v.beziehung_von"; 
		$sql.=" LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON v.beziehung_zu=l.gml_id";
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
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschlieï¿½enden Rechtecks um die Gebï¿½ude.<br>'.$ret[1];
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

  # 2006-01-31 pk
  function getMERfromGemeinde($Gemeinde, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromGemeinde, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Gemeinde",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkobj_e_fla as o,alknflur AS fl,alb_v_gemarkungen AS g";
    $sql.=" WHERE o.objnr=fl.objnr AND fl.gemkgschl::integer=g.gemkgschl";
    $sql.=" AND g.gemeinde=".(int)$Gemeinde;
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschlieï¿½enden Rechtecks um die Gemeinde.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Gemeinde nicht in ALK Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }

  # 2006-02-01 pk
  function getMERfromGemarkung($Gemkgschl, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromGemarkung, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Gemarkung",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkobj_e_fla as o,alknflur AS fl";
    $sql.=" WHERE o.objnr=fl.objnr AND CAST(fl.gemkgschl AS Integer)=".(int)$Gemkgschl;
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschlieï¿½enden Rechtecks um die Gemarkung.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Gemarkung nicht in ALK Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }

  # 2006-02-01 pk
  function getMERfromFlur($Gemarkung,$Flur, $epsgcode) {
    $this->debug->write("<br>postgres.php->database->getMERfromFlur, Abfrage des Maximalen umschlieï¿½enden Rechtecks um die Flur",4);
    $sql ="SELECT MIN(st_xmin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS minx,MAX(st_xmax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxx";
    $sql.=",MIN(st_ymin(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS miny,MAX(st_ymax(st_envelope(st_transform(o.the_geom, ".$epsgcode.")))) AS maxy";
    $sql.=" FROM alkobj_e_fla as o,alknflur AS fl";
    $sql.=" WHERE o.objnr=fl.objnr AND fl.gemkgschl='".$Gemarkung."'";
    $sql.=" AND fl.flur='".$Flur."'";
    # echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen des Umschlieï¿½enden Rechtecks um die Flur.<br>'.$ret[1];
    }
    else {
      $rs=pg_fetch_array($ret[1]);
      if ($rs['minx']==0) {
        $ret[0]=1;
        $ret[1]='Flur nicht in ALK Datenbank '.$this->dbName.' vorhanden.';
      }
      else {
        $ret[1]=$rs;
      }
    }
    return $ret;
  }

  function insertALKUpdateMessage($anzalb_flurstuecke,$anzgebaeude,$anznutzungen,$anzausgestaltungen) {
    $sql ="INSERT INTO alk_fortfuehrung";
    $sql.=" (anzalb_flurstuecke,anzgebaeude,anznutzungen,anzausgestaltungen)";
    $sql.=" VALUES ('".$anzalb_flurstuecke."','".$anzgebaeude."','".$anznutzungen."','".$anzausgestaltungen."')";
    return $this->execSQL($sql, 4, 0);
  }

  function truncateALKFlurst() {
    $sql ="TRUNCATE alk_flurst";
    return $this->execSQL($sql, 4, 0);
  }

  function insertALKFlurst($colnames,$row) {
    $sql ="INSERT INTO alk_flurst";
    $sql.=" (".$colnames[0];
    for ($i=1;$i<count($row);$i++) { $sql.=",".$colnames[$i]; }
    $sql.=")";
    $sql.=" VALUES ('".$row[0]."'";
    for ($i=1;$i<count($row);$i++) { $sql.=",'".$row[$i]."'"; }
    $sql.=")";
    return $this->execSQL($sql, 4, 0);
  }

  function truncateALKGebaeude() {
    $sql ="TRUNCATE alk_gebaeude";
    return $this->execSQL($sql, 4, 0);
  }

  function insertALKGebaeude($colnames,$row) {
    $sql ="INSERT INTO alk_gebaeude";
    $sql.=" (".$colnames[0];
    for ($i=1;$i<count($row);$i++) { $sql.=",".$colnames[$i];}
    $sql.=")";
    $sql.=" VALUES ('".$row[0]."'";
    for ($i=1;$i<count($row);$i++) { $sql.=",'".$row[$i]."'";}
    $sql.=")";
    return $this->execSQL($sql, 4, 0);
  }

  function truncateALKNutzungen() {
    $sql ="TRUNCATE alk_nutzung";
    return $this->execSQL($sql, 4, 0);
  }

  function insertALKNutzungen($colnames,$row) {
    $sql ="INSERT INTO alk_nutzung";
    $sql.=" (".$colnames[0];
    for ($i=1;$i<count($row);$i++) { $sql.=",".$colnames[$i];}
    $sql.=")";
    $sql.=" VALUES ('".$row[0]."'";
    for ($i=1;$i<count($row);$i++) { $sql.=",'".$row[$i]."'"; }
    $sql.=")";
    return $this->execSQL($sql, 4, 0);
  }

  function truncateALKAusgestaltungen() {
    $sql ="TRUNCATE alk_ausgest";
    return $this->execSQL($sql, 4, 0);
  }

  function insertALKAusgestaltungen($colnames,$row) {
    $sql ="INSERT INTO alk_ausgest";
    $sql.=" (".$colnames[0];
    for ($i=1;$i<count($row);$i++) { $sql.=",".$colnames[$i];}
    $sql.=")";
    $sql.=" VALUES ('".$row[0]."'";
    for ($i=1;$i<count($row);$i++) { $sql.=",'".$row[$i]."'";}
    $sql.=")";
    return $this->execSQL($sql, 4, 0);
  }

  function truncateAll() {
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_katasteraemter",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_gemarkungen",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_grundbuchbezirke",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_kreise",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_gemeinden",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_strassen",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_amtsgerichte",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_eigentuemerarten",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_buchungsarten",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_forstaemter",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_finanzaemter",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_hinweise",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_nutzungsarten",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_klassifizierungen",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_ausfuehrendestellen",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."v_bemerkgzumverfahren",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."flurstuecke",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."grundbuecher",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_adressen",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_anlieger",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_baulasten",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_hinweise",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_historie",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_klassifizierungen",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_lage",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_nutzungen",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_texte",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."f_verfahren",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."g_buchungen",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."g_eigentuemer",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."g_grundstuecke",4,0);
    if ($ret[0]) { return $ret; }
    $ret=$this->execSQL("TRUNCATE alb_".$this->tableprefix."g_namen",4,0);
    if ($ret[0]) { return $ret; }
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





















