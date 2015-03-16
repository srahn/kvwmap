<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
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
#############################
#############################

class data_import_export {
    
  function data_import_export() {
    global $debug;
    $this->debug=$debug;
  }

################# Import #################
	
  function create_shape_rollenlayer($formvars, $stelle, $user, $database, $pgdatabase){
  	$_files = $_FILES;
  	if($_files['zipfile']['name']){     # eine Zipdatei wurde ausgewählt
      $nachDatei = UPLOADPATH.$_files['zipfile']['name'];
      if(move_uploaded_file($_files['zipfile']['tmp_name'],$nachDatei)){
				$files = unzip($nachDatei, false, false, true);
				$firstfile = explode('.', $files[0]);
				$file = $firstfile[0];
				if(file_exists(UPLOADPATH.$file.'.dbf') OR file_exists(UPLOADPATH.$file.'.DBF')){
					$tablename = 'a'.strtolower(umlaute_umwandeln($file)).rand(1,1000000);
		      $command = POSTGRESBINPATH.'shp2pgsql -g the_geom -I -s '.$formvars['epsg'].' -W LATIN1 -c "'.UPLOADPATH.$file.'" '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' > "'.UPLOADPATH.$file.'.sql"'; 
		      exec($command);
		     	#echo $command;
					$command = POSTGRESBINPATH.'psql -f "'.UPLOADPATH.$file.'.sql" '.$pgdatabase->dbName.' '.$pgdatabase->user;
					if($pgdatabase->passwd != '')$command = 'export PGPASSWORD="'.$pgdatabase->passwd.'"; '.$command;
		      exec($command);
		     	#echo $command;
		      $sql = 'SELECT count(*) FROM '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename;
		      $ret = $pgdatabase->execSQL($sql,4, 0);
		      if(!$ret[0]){
			      $sql = file_get_contents(UPLOADPATH.$file.'.sql');
			      if(strpos($sql, 'POINT') !== false){
			      	$datatype = 0;
			      }elseif(strpos($sql, 'LINESTRING') !== false){
			      	$datatype = 1;
			      }elseif(strpos($sql, 'POLYGON') !== false){
			      	$datatype = 2;
			      }
			      # ------ Rollenlayer erzeugen ------- #
			      $result_colors = read_colors($database);
			      $dbmap = new db_mapObj($stelle->id, $user->id);
				    $group = $dbmap->getGroupbyName('Eigene Shapes');
				    if($group != ''){
				      $groupid = $group['id'];
				    }
				    else{
				      $groupid = $dbmap->newGroup('Eigene Shapes', 1);
				    }
				
				    $this->formvars['user_id'] = $user->id;
				    $this->formvars['stelle_id'] = $stelle->id;
				    $this->formvars['aktivStatus'] = 1;
				    $this->formvars['Name'] = $file." (".date('d.m. H:i',time()).")";;
				    $this->formvars['Gruppe'] = $groupid;
				    $this->formvars['Typ'] = 'import';
				    $this->formvars['Datentyp'] = $datatype;
				    $this->formvars['Data'] = 'the_geom from '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename;
				    $connectionstring ='user='.$pgdatabase->user;
	    			if($pgdatabase->passwd != '')$connectionstring.=' password='.$pgdatabase->passwd;
				    $connectionstring.=' dbname='.$pgdatabase->dbName;
				    $this->formvars['connection'] = $connectionstring;
				    $this->formvars['connectiontype'] = 6;
				    $this->formvars['epsg_code'] = $formvars['epsg'];
				    $this->formvars['transparency'] = 65;
				
						$layer_id = $dbmap->newRollenLayer($this->formvars);
				
						$attrib['name'] = ' ';
						$attrib['layer_id'] = -$layer_id;
						$attrib['expression'] = '';
						$attrib['order'] = 0;
						
				    $class_id = $dbmap->new_Class($attrib);
				    $this->formvars['class'] = $class_id;
							    
				    $style['colorred'] = $result_colors[rand(0,10)]['red'];
	      		$style['colorgreen'] = $result_colors[rand(0,10)]['green'];
	      		$style['colorblue'] = $result_colors[rand(0,10)]['blue'];
				    
				    $style['outlinecolorred'] = 0;
				    $style['outlinecolorgreen'] = 0;
				    $style['outlinecolorblue'] = 0;
				    switch ($datatype) {
					    case 0 :{
					    	$style['size'] = 8;
					    	$style['maxsize'] = 8;
					    	$style['symbol'] = 9;
					    }break;
					    case 1 :{
					    	$style['size'] = 1;
					    	$style['maxsize'] = 2;
					    	$style['symbol'] = NULL;
					    }break;
					    case 2 :{
					    	$style['size'] = 1;
					    	$style['maxsize'] = 2;
					    	$style['symbol'] = NULL;
					    }
				    }
				    $style['symbolname'] = NULL;
				    $style['backgroundcolor'] = NULL;
				    $style['minsize'] = NULL;
				    
				    $style['angle'] = 360;
				    $style_id = $dbmap->new_Style($style);
				
				    $dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
				    $user->rolle->set_one_Group($user->id, $stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
		      }
				}
      }
    }
    return -$layer_id;
  }
  
   function shp_import_speichern($formvars, $database){
   	$this->formvars = $formvars;
    if(file_exists(UPLOADPATH.$this->formvars['dbffile'])){
			include_(CLASSPATH.'dbf.php');
      $this->dbf = new dbf();
      $this->dbf->header = $this->dbf->get_dbf_header(UPLOADPATH.$this->formvars['dbffile']);
      $this->dbf->header = $this->dbf->get_sql_types($this->dbf->header);
      for($i = 0; $i < count($this->dbf->header); $i++){
        if($i > 0){
          $alterstring .= ';';
        }
        if($this->formvars['check_'.$this->dbf->header[$i][0]]){
          $alterstring .= $this->formvars['dbf_name_'.$this->dbf->header[$i][0]].' as '.strtolower($this->formvars['sql_name_'.$this->dbf->header[$i][0]]).' '.$this->formvars['sql_type_'.$this->dbf->header[$i][0]];
          if($this->formvars['primary_key'] == $this->dbf->header[$i][0]){
            $alterstring .= ' PRIMARY KEY';
          }
        }
        else{
          $alterstring .= $this->formvars['dbf_name_'.$this->dbf->header[$i][0]].' as NULL';
        }
      }
      if($this->formvars['table_option'] == '-u') {
        $command = POSTGRESBINPATH.'shp2pgsql -g the_geom -A "'.$alterstring.'" -a ';
      }
      else {
        $command = POSTGRESBINPATH.'shp2pgsql -g the_geom -A "'.$alterstring.'" '.$this->formvars['table_option'].' ';
      }
      if($this->formvars['srid'] != ''){
        $command .= '-s '.$this->formvars['srid'].' ';
      }
      if($this->formvars['gist'] != ''){
        $command .= '-I ';
      }
      if($this->formvars['oids'] != ''){
        $command .= '-o ';
      }
      if($this->formvars['primary_key'] == 'gid'){
        $command .= '-P ';
      }
      $command.= UPLOADPATH.$this->formvars['dbffile'].' '.$this->formvars['table_name'].' > '.UPLOADPATH.$this->formvars['table_name'].'.sql'; 
      exec($command);
      #echo $command;
      
      # erzeugte SQL-Datei anpassen
      if($this->formvars['table_option'] == '-u') {
        $oldsqld = UPLOADPATH.$this->formvars['table_name'].'.sql';
        # Shared lock auf die Quelldatei
        $oldsql = fopen($oldsqld, "r");
        flock($oldsql, 1) or die("Kann die Quelldatei $oldsqld nicht locken.");
        # Exclusive lock auf die Zieldatei     
        $newsql = fopen($oldsqld.".new", "w");
        flock($newsql, 2) or die("Kann die Zieldatei $newsql nicht locken.");
				# Zeilenweises einlesen der SQL-Datei $oldsqld in das array *sqlold zum weiteren Umformen
        $sqlold = file($oldsqld);
				# Anzahl der Zeilen bestimmen
				$anzzei = count($sqlold);
				# Schleife für jede Zeile durchlaufen
				for ($i = 0; $i < $anzzei; $i++) {
				# Neuer SQL-Befehl $sqlnew wird gelesen
					$sqlnew = $sqlold[$i];
				# Wenn der SQL-Befehl mit INSERT beginnt, dann weiterverarbeiten
          if (substr($sqlnew,0,6) == "INSERT") {
  			# alte Befehlszeile wird bei jedem Leerzeichen gesplittet  
            $old = explode(" ",$sqlnew);
  			# Feldbezeichner werden herausgelesen, sind durch Kommata getrennt
            $feld = explode(",",$old[3]);
  			# da Feldbezeichner in der INSERT-Anweisung eingeklammert sind werden die oeffnende und schliessende Klammer entfernt
            for ($j=0; $j < count($feld); $j++) {
              $feld[$j] = trim($feld[$j],"()");
            }
  			# heraussuchen, an welcher Stelle der primary_key steht
            $primkey = array_search($this->formvars['primary_key'],$feld);
  			# Werte extrahieren, sind duch Kommata getrennt
  			# Achtung, kommen in den Werten Kommata vor, so wird hier ein fehlerhaftes Statement erzeugt, da die Anzahl der Felder nicht mehr mit der Anzahl der Werte uebereinstimmt
            $wert = explode(",",$old[5]);
  			# Bereinigen der Werte
            for ($j=0; $j < count($wert); $j++) {
              $wert[$j] = trim($wert[$j]);
              $wert[$j] = trim($wert[$j],"(;)");
            }
  			# SQL-Anweisung neu schreiben
            $sqlnew = "UPDATE ".$this->formvars['table_name']." SET ";
  			# den Feldbezeichnern die Werte zuweisen
            for ($j=0; $j < count($feld); $j++) {
              $sqlnew .= $feld[$j]." = ". $wert[$j];
    		# Wertzuweisungen mit Komma voneinander trennen
              if ($j < count($feld)-1) {
                $sqlnew .= ", ";
              }
            }
  			# Bindungung hinzufuegen 
            $sqlnew .= " WHERE ".$feld[$primkey]." = ".$wert[$primkey].";";
          }
  			# SQL-Anweisung in die neue Datei $newsql schreiben  
          fwrite($newsql,$sqlnew);
        }
        fclose($oldsql);
        unlink($oldsqld);
        rename($oldsqld.".new", $oldsqld);
        fclose($newsql);
      }
      
			$command = POSTGRESBINPATH.'psql -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' -U '.$database->user;
			if($database->passwd != '')$command = 'export PGPASSWORD='.$database->passwd.'; '.$command;
      exec($command);
      #echo $command;
			
      $sql = 'SELECT count(*) FROM '.$this->formvars['table_name'];
      $ret = $database->execSQL($sql,4, 0);
      if (!$ret[0]) {
        $count = pg_fetch_array($ret[1]);
        $alert = 'Import erfolgreich.';
        if($this->formvars['table_option'] == '-c'){
        	$alert.= ' Die Tabelle '.$this->formvars['table_name'].' wurde erzeugt.';
        }
        $alert .= ' Die Tabelle enthält jetzt '.$count[0].' Datensätze.';
        showAlert($alert);
      }
      else{
        showAlert('Import fehlgeschlagen.');
      }
    }
  }
  
  function shp_import($formvars){
		include_(CLASSPATH.'dbf.php');
  	$_files = $_FILES;
    $this->formvars = $formvars;
    if($_files['zipfile']['name']){     # eine Zipdatei wurde ausgewählt
      $this->formvars['zipfile'] = $_files['zipfile']['name'];
      $nachDatei = UPLOADPATH.$_files['zipfile']['name'];
      if(move_uploaded_file($_files['zipfile']['tmp_name'],$nachDatei)){
        $files = unzip($nachDatei, false, false, true);
        $firstfile = explode('.', $files[0]);
        $file = $firstfile[0].'.dbf';
        if(!file_exists(UPLOADPATH.$file)){
        	$file = $firstfile[0].'.DBF';
        }
        $this->dbf = new dbf();
        $this->dbf->file = '';
        $this->dbf->file = $file;
      
        if($this->dbf->file != ''){
          if(file_exists(UPLOADPATH.$this->dbf->file)){   
            $this->dbf->header = $this->dbf->get_dbf_header(UPLOADPATH.$this->dbf->file);
            $this->dbf->header = $this->dbf->get_sql_types($this->dbf->header);
          }  
        }
      }
    }
  }
  
  function simple_shp_import_speichern($formvars, $database){
  	$this->formvars = $formvars;
    if(file_exists(UPLOADPATH.$this->formvars['dbffile'])){      
      $command = POSTGRESBINPATH.'shp2pgsql -g the_geom -W LATIN1 '.$this->formvars['table_option'].' ';
      if($this->formvars['srid'] != ''){
        $command .= '-s '.$this->formvars['srid'].' ';
      }
      if($this->formvars['gist'] != ''){
        $command .= '-I ';
      }
      $command.= UPLOADPATH.$this->formvars['dbffile'].' '.$this->formvars['table_name'].' > '.UPLOADPATH.$this->formvars['table_name'].'.sql'; 
      exec($command);
      #echo $command;
			
			$command = POSTGRESBINPATH.'psql -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' '.$database->user;
			if($database->passwd != '')$command = 'export PGPASSWORD='.$database->passwd.'; '.$command;
      exec($command);
      #echo $command;
      $sql = 'SELECT count(*) FROM '.$this->formvars['table_name'];
      $ret = $database->execSQL($sql,4, 0);
      if (!$ret[0]) {
        $count = pg_fetch_array($ret[1]);
        $alert = 'Import erfolgreich.';
        if($this->formvars['table_option'] == '-c'){
        	$alert.= ' Die Tabelle '.$this->formvars['table_name'].' wurde erzeugt.';
        	$sql = 'INSERT INTO shp_import_tables (tabellenname) VALUES (\''.$this->formvars['table_name'].'\')';
        	$ret = $database->execSQL($sql,4, 1);
        }
        $alert .= ' Die Tabelle enthält jetzt '.$count[0].' Datensätze.';
        showAlert($alert);
      }
      else{
        showAlert('Import fehlgeschlagen.');
      }
    }
  }
  
  function simple_shp_import($formvars, $database){
  	$this->shp_import($formvars);
  	$sql = 'SELECT DISTINCT * FROM shp_import_tables';
  	$ret = $database->execSQL($sql,4, 0);
    while($rs = pg_fetch_array($ret[1])){
    	$this->tables[] = $rs;
    }
  }
	
	function get_ukotable_srid($database){
		$sql = "select srid from geometry_columns where f_table_name = 'uko_polygon'";
		$ret = $database->execSQL($sql,4, 1);
		if(!$ret[0]){
			$rs=pg_fetch_array($ret[1]);
			$this->uko_srid = $rs[0];
		}
  }
  
	function uko_importieren($formvars, $username, $userid, $database){
		$_files = $_FILES;
		if($_files['ukofile']['name']){     # eine UKOdatei wurde ausgewählt
		  $formvars['ukofile'] = $_files['ukofile']['name'];
		  $nachDatei = UPLOADPATH.$_files['ukofile']['name'];
		  if(move_uploaded_file($_files['ukofile']['tmp_name'],$nachDatei)){
			$wkt = file_get_contents($nachDatei);
			$wkt = substr($wkt, strpos($wkt, 'KOO ')+4);
			$wkt = 'MULTIPOLYGON((('.$wkt;
			$wkt = str_replace(chr(10).'FL+'.chr(10).'KOO ', ')),((', $wkt);
			$wkt = str_replace(chr(10).'FL-'.chr(10).'KOO ', '),(', $wkt);
			$wkt = str_replace(chr(10).'KOO ', ',', $wkt);
			$wkt.= ')))';
			$sql = "INSERT INTO uko_polygon (username, userid, dateiname, the_geom) VALUES('".$username."', ".$userid.", '".$_files['ukofile']['name']."', st_transform(st_geomfromtext('".$wkt."', ".$formvars['epsg']."), ".$this->uko_srid.")) RETURNING id";
			$ret = $database->execSQL($sql,4, 1);
			if ($ret[0])$this->success = false;
			else {
				$this->success = true;
				$rs=pg_fetch_array($ret[1]);
				return $rs[0];
			}
		  }
		}
	}
  
	
################### Export ########################

  function export($formvars, $stelle, $mapdb){
  	$this->formvars = $formvars;
    $this->layerdaten = $stelle->getqueryablePostgisLayers(NULL, 1);
    if($this->formvars['selected_layer_id']){
      $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $stelle->pgdbhost);
      $path = $mapdb->getPath($this->formvars['selected_layer_id']);
      $privileges = $stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
      $newpath = $stelle->parse_path($layerdb, $path, $privileges);
      $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
    }
  }
	
	function ogr2ogr($sql, $exportformat, $exportfile, $layerdb){
		$command = 'ogr2ogr -f '.$exportformat.' -sql "'.$sql.'" '.$exportfile.' PG:"dbname='.$layerdb->dbName.' user='.$layerdb->user;
		if($layerdb->passwd != '')$command.= ' password='.$layerdb->passwd;
		if($layerdb->port != '')$command.=' port='.$layerdb->port;
		exec($command.'"');
	}
	
	function create_csv($result, $attributes){
    # Spaltenüberschriften schreiben
    # Excel is zu blöd für 'ID' als erstes Attribut
    if($attributes['alias'][0] == 'ID'){
      $attributes['alias'][0] = 'id';
    }
    if($attributes['name'][0] == 'ID'){
      $attributes['name'][0] = 'id';
    }
    foreach($result[0] As $key => $value){
			$i = $attributes['indizes'][$key];
    	if($attributes['type'][$i] != 'geometry' AND $attributes['name'][$i] != 'lock'){
	      if($attributes['alias'][$i] != ''){
	        $name = $attributes['alias'][$i];
	      }
	      else{
	        $name = $attributes['name'][$i];
	      }
	      $csv .= $name.';';
    	}
    }
    $csv .= chr(13).chr(10);
 
    # Daten schreiben
    for($i = 0; $i < count($result); $i++){
			foreach($result[$i] As $key => $value){
				$j = $attributes['indizes'][$key];
      	if($attributes['type'][$j] != 'geometry' AND $attributes['name'][$i] != 'lock'){
      		$csv .= '"';
	        if(in_array($attributes['type'][$j], array('numeric', 'float4', 'float8'))){
	        	$value = str_replace('.', ",", $value);	
	        }
					if($attributes['type'][$j] == 'bool'){
						$value = str_replace('t', "ja", $value);	
						$value = str_replace('f', "nein", $value);
					}
	        $value = str_replace(';', ",", $value);
	        $value = str_replace(chr(10), " ", $value);
	        $value = str_replace(chr(13), "", $value);
	        $csv .= $value.'";';
      	}
      }
      $csv .= chr(13).chr(10);
    }
    
    $currenttime=date('Y-m-d H:i:s',time());
    #$this->user->rolle->setConsumeCSV($currenttime,$this->formvars['chosen_layer_id'],count($result)); TODO!!!

		return utf8_decode($csv);
	}
  
	function create_uko($layerdb, $table, $column){
		$sql.= "SELECT st_astext(st_multi(st_union(".$column."))) as geom FROM ".$table;
		#echo $sql;
		$ret = $layerdb->execSQL($sql,4, 1);
		if(!$ret[0]){
			$rs=pg_fetch_array($ret[1]);
			$uko = $rs['geom'];
			$uko = str_replace('MULTIPOLYGON(((', 'TYP UPO 2'.chr(10).'KOO ', $uko);
			$uko = str_replace(')),((', chr(10).'FL+'.chr(10).'KOO ', $uko);
			$uko = str_replace('),(', chr(10).'FL-'.chr(10).'KOO ', $uko);
			$uko = str_replace(',', chr(10).'KOO ', $uko);
			$uko = str_replace(')))', '', $uko);			
			return $uko;
		}
  }
	
	function export_exportieren($formvars, $stelle, $user){
  	$this->formvars = $formvars;
  	$layerset = $user->rolle->getLayer($this->formvars['selected_layer_id']);
    $mapdb = new db_mapObj($stelle->id,$user->id);
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $stelle->pgdbhost);
		$path = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset[0]['pfad']);
    $privileges = $stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
    for($i = 0; $i < count($this->attributes['name']); $i++){
    	if($this->formvars['check_'.$this->attributes['name'][$i]]){
    		$selection[$this->attributes['name'][$i]] = 1;
    	}
    }
    $sql = $stelle->parse_path($layerdb, $path, $selection);		# parse_path wird hier benutzt um die Auswahl der Attribute auf das Pfad-SQL zu übertragen
    
    if($this->attributes['table_alias_name'][$this->attributes['the_geom']] != $this->attributes['table_name'][$this->attributes['the_geom']]){
    	$the_geom = $this->attributes['table_alias_name'][$this->attributes['the_geom']].'.'.$this->attributes['the_geom'];
    }
    else{
    	$the_geom = $this->attributes['the_geom'];
    }
    
		# Transformieren von the_geom im Select-Teil
    if($this->formvars['epsg']){
    	$select = substr($sql, 0, strrpos(strtolower($sql), 'from'));
    	$rest = substr($sql, strrpos(strtolower($sql), 'from'));
    	 if(strpos($select, '.'.$this->attributes['the_geom']) !== false){		// table.the_geom muss ersetzt werden
    		 $select = str_replace($the_geom, 'st_transform('.$the_geom.', '.$this->formvars['epsg'].') as '.$this->attributes['the_geom'], $select);
    	 }
    	 else{		// nur the_geom muss ersetzt werden
    		$select = str_replace(',  '.$this->attributes['the_geom'], ', st_transform('.$this->attributes['the_geom'].', '.$this->formvars['epsg'].') as '.$this->attributes['the_geom'], $select);    		
    		$select = str_replace(', '.$this->attributes['the_geom'], ', st_transform('.$this->attributes['the_geom'].', '.$this->formvars['epsg'].') as '.$this->attributes['the_geom'], $select);
    		$select = str_replace(','.$this->attributes['the_geom'], ',st_transform('.$this->attributes['the_geom'].', '.$this->formvars['epsg'].') as '.$this->attributes['the_geom'], $select);
				$select = str_replace(' '.$this->attributes['the_geom'].',', ' st_transform('.$this->attributes['the_geom'].', '.$this->formvars['epsg'].') as '.$this->attributes['the_geom'].',', $select);
    	}
    	$sql = $select.$rest;
    }
    # order by rausnehmen
  	$orderbyposition = strpos(strtolower($sql), 'order by');
  	if($orderbyposition !== false){
	  	$orderby = ' '.substr($sql, $orderbyposition);
	  	$sql = substr($sql, 0, $orderbyposition);
  	}
		# group by rausnehmen
		$groupbyposition = strpos(strtolower($sql), 'group by');
		if($groupbyposition !== false){
			$groupby = ' '.substr($sql, $groupbyposition);
			$sql = substr($sql, 0, $groupbyposition);
  	}
  	# über Polygon einschränken
    if($this->formvars['newpathwkt']){
    	$sql.= " AND ".$the_geom." && st_transform(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$user->rolle->epsg_code."), ".$layerset[0]['epsg_code'].") AND ST_INTERSECTS(".$the_geom.", st_transform(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$user->rolle->epsg_code."), ".$layerset[0]['epsg_code']."))";
    }
    # Filter
    $filter = $mapdb->getFilter($this->formvars['selected_layer_id'], $stelle->id);
    if($filter != ''){
    	$sql .= ' AND '.$filter;
    }
		# Where-Klausel aus Sachdatenabfrage-SQL anhängen
  	if($this->formvars['sql_'.$this->formvars['selected_layer_id']]){
  		$where = substr(stripslashes($this->formvars['sql_'.$this->formvars['selected_layer_id']]), strrpos(strtolower(stripslashes($this->formvars['sql_'.$this->formvars['selected_layer_id']])), 'where')+5);
  		$orderbyposition = strpos(strtolower($where), 'order by');
  		if($orderbyposition)$where = substr($where, 0, $orderbyposition);
	    if(strpos($where, 'query.') !== false){
	    	if($this->formvars['epsg']){
	    		$where = str_replace('), '.$layerset[0]['epsg_code'].')', '), '.$this->formvars['epsg'].')', $where);		# die räumliche Einschränkung das Such-SQLs auf den neuen EPSG-Code anpassen
	    	}
	    	$sql = "SELECT * FROM (".$sql.$groupby.") as query WHERE 1=1 AND ".$where;
	    }
	    else{
	    	$sql = $sql." AND ".$where;
	    }
  	}
    $sql.= $orderby;
		#echo $sql;
    $temp_table = 'shp_export_'.rand(1, 10000);
    $sql = 'CREATE TABLE public.'.$temp_table.' AS '.$sql;		# temporäre Tabelle erzeugen, damit das/die Schema/ta berücksichtigt werden
    $ret = $layerdb->execSQL($sql,4, 0);
    $sql = 'SELECT * FROM public.'.$temp_table;
    $ret = $layerdb->execSQL($sql,4, 0);
    if(!$ret[0]){
      #$count = pg_num_rows($ret[1]);
      #showAlert('Abfrage erfolgreich. Es wurden '.$count.' Zeilen geliefert.');
      $this->formvars['layer_name'] = umlaute_umwandeln($this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace('.', '_', $this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace('(', '_', $this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace(')', '_', $this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace('/', '_', $this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace('[', '_', $this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace(']', '_', $this->formvars['layer_name']);
      $folder = 'Export_'.$this->formvars['layer_name'].rand(0,10000);
			$old = umask(0);
      mkdir(IMAGEPATH.$folder, 0777);                       # Ordner erzeugen
			umask($old); 
			$zip = false;
			$exportfile = IMAGEPATH.$folder.'/'.$this->formvars['layer_name'];
			switch($this->formvars['export_format']){
				case 'Shape' : { 
					$command = POSTGRESBINPATH.'pgsql2shp -r -u '.$layerdb->user;
					if($layerdb->passwd != '')$command.= ' -P "'.$layerdb->passwd.'"';
					if($layerdb->port != '')$command.=' -p '.$layerdb->port;
					$command.= ' -f '.$exportfile.' '.$layerdb->dbName.' '.$temp_table; 
					exec($command);
					#echo $command;
					$fp = fopen($exportfile.'.cpg', 'w');
					fwrite($fp, 'UTF-8');
					fclose($fp);
					$zip = true;
				}break;
				
				case 'GML' : {
					$this->ogr2ogr($sql, 'GML', $exportfile.'.xml', $layerdb);
					$zip = true;
				}break;
				
				case 'KML' : {
					$exportfile = $exportfile.'.kml';
					$this->ogr2ogr($sql, 'KML', $exportfile, $layerdb);
					$contenttype = 'application/vnd.google-earth.kml+xml';
				}break;
				
				case 'CSV' : {
					while($rs=pg_fetch_assoc($ret[1])){
						$result[] = $rs;
					}
					$csv = $this->create_csv($result, $this->attributes);
					$exportfile = $exportfile.'.csv';
					$fp = fopen($exportfile, 'w');
					fwrite($fp, $csv);
					fclose($fp);
					$contenttype = 'application/vnd.ms-excel';
				}break;
				
				case 'UKO' : {
					$uko = $this->create_uko($layerdb, $temp_table, $this->attributes['the_geom']);
					$exportfile = $exportfile.'.uko';
					$fp = fopen($exportfile, 'w');
					fwrite($fp, $uko);
					fclose($fp);
					$contenttype = 'text/uko';
				}break;
			}
			if($zip){
				exec(ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*'); # Ordner zippen
				#echo ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*';
				$exportfile = IMAGEPATH.$folder.'.zip';
				$contenttype = 'application/octet-stream';
			}
      $sql = 'DROP TABLE '.$temp_table;		# temp. Tabelle wieder löschen
      $ret = $layerdb->execSQL($sql,4, 0);
      $currenttime=date('Y-m-d H:i:s',time());
    	$user->rolle->setConsumeShape($currenttime,$this->formvars['selected_layer_id'],$count);
			
	    ob_end_clean();
			header('Content-type: '.$contenttype);
			header("Content-disposition:  attachment; filename=".basename($exportfile));
			header("Content-Length: ".filesize($exportfile));
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			readfile($exportfile);
    }
    else{
      showAlert('Abfrage fehlgeschlagen.');
    }
  }
 
}
?>
