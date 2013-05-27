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
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de                #
###################################################################
#############################
# Klasse dbf #
#############################

class shape {
    
  ################### Liste der Funktionen ########################################################################################################
  # dbf($database)
  ##################################################################################################################################################

  function shape() {
    global $debug;
    $this->debug=$debug;
  }
  
  function create_shape_rollenlayer($formvars, $stelle, $user, $database, $pgdatabase){
  	$_files = $_FILES;
  	if($_files['zipfile']['name']){     # eine Zipdatei wurde ausgewählt
      $nachDatei = UPLOADPATH.$_files['zipfile']['name'];
      if(move_uploaded_file($_files['zipfile']['tmp_name'],$nachDatei)){
				$files = unzip($nachDatei, false, false, true);
				$firstfile = explode('.', $files[0]);
				$file = $firstfile[0];
				if(file_exists(UPLOADPATH.$file.'.dbf') OR file_exists(UPLOADPATH.$file.'.DBF')){
					$tablename = 'a'.strtolower($file).rand(1,1000000);
		      $command = POSTGRESBINPATH.'shp2pgsql -I -s '.$formvars['epsg'].' -W LATIN1 -c '.UPLOADPATH.$file.' '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' > '.UPLOADPATH.$file.'.sql'; 
		      exec($command);
		      #echo $command;
		      exec(POSTGRESBINPATH.'psql -f '.UPLOADPATH.$file.'.sql '.$pgdatabase->dbName.' '.$pgdatabase->user);
		      #echo POSTGRESBINPATH.'psql -f '.UPLOADPATH.$file.'.sql '.$pgdatabase->dbName.' '.$pgdatabase->user;
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
				      $groupid = $dbmap->newGroup('Eigene Shapes');
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
				
				    $classdata[0] = ' ';
				    $classdata[1] = -$layer_id;
				    $classdata[2] = '';
				    $classdata[3] = 0;
				    $class_id = $dbmap->new_Class($classdata);
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
        $command = POSTGRESBINPATH.'shp2pgsql -A "'.$alterstring.'" -a ';
      }
      else {
        $command = POSTGRESBINPATH.'shp2pgsql -A "'.$alterstring.'" '.$this->formvars['table_option'].' ';
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
      
      exec(POSTGRESBINPATH.'psql -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' -U '.$database->user);
      #echo POSTGRESBINPATH.'psql -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' -U '.$database->user;
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
      $command = POSTGRESBINPATH.'shp2pgsql -W LATIN1 '.$this->formvars['table_option'].' ';
      if($this->formvars['srid'] != ''){
        $command .= '-s '.$this->formvars['srid'].' ';
      }
      if($this->formvars['gist'] != ''){
        $command .= '-I ';
      }
      $command.= UPLOADPATH.$this->formvars['dbffile'].' '.$this->formvars['table_name'].' > '.UPLOADPATH.$this->formvars['table_name'].'.sql'; 
      exec($command);
      #echo $command;
      exec(POSTGRESBINPATH.'psql -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' '.$database->user);
      #echo POSTGRESBINPATH.'psql -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' '.$database->user;
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
  
  function shp_export($formvars, $stelle, $mapdb){
  	$this->formvars = $formvars;
    $this->layerdaten = $stelle->getqueryablePostgisLayers(NULL);
    if($this->formvars['selected_layer_id']){
      $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $stelle->pgdbhost);
      $path = $mapdb->getPath($this->formvars['selected_layer_id']);
      $privileges = $stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
      $newpath = $stelle->parse_path($layerdb, $path, $privileges);
      $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
    }
  }
  
	function shp_export_exportieren($formvars, $stelle, $user){
  	$this->formvars = $formvars;
  	$layerset = $user->rolle->getLayer($this->formvars['selected_layer_id']);
    $mapdb = new db_mapObj($stelle->id,$user->id);
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $stelle->pgdbhost);
    $path = $mapdb->getPath($this->formvars['selected_layer_id']);
    $privileges = $stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
    for($i = 0; $i < count($this->attributes['name']); $i++){
    	if($this->formvars['check_'.$this->attributes['name'][$i]]){
    		$selection[$this->attributes['name'][$i]] = 1;
    	}
    }
    $sql = $stelle->parse_path($layerdb, $path, $selection);		# parse_path wird hier benutzt um die Auswahl der Attribute auf das Pfad-SQL zu übertragen
    
    if($this->attributes['table_alias_name'][$this->attributes['the_geom']] != ''){
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
    		$select = str_replace(', '.$this->attributes['the_geom'], ', st_transform('.$this->attributes['the_geom'].', '.$this->formvars['epsg'].') as '.$this->attributes['the_geom'], $select);
    		$select = str_replace(','.$this->attributes['the_geom'], ',st_transform('.$this->attributes['the_geom'].', '.$this->formvars['epsg'].') as '.$this->attributes['the_geom'], $select);
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
    	$sql.= " AND st_transform(".$the_geom.", ".$user->rolle->epsg_code.") && st_geomfromtext('".$this->formvars['newpathwkt']."', ".$user->rolle->epsg_code.") AND ST_INTERSECTS(st_transform(".$the_geom.", ".$user->rolle->epsg_code."), st_geomfromtext('".$this->formvars['newpathwkt']."', ".$user->rolle->epsg_code."))";
    }
    # Filter
    $filter = $mapdb->getFilter($this->formvars['selected_layer_id'], $stelle->id);
    if($filter != ''){
    	$sql .= ' AND '.$filter;
    }
		# Where-Klausel aus Sachdatenabfrage-SQL anhängen
  	if($this->formvars['sql_'.$this->formvars['selected_layer_id']]){
  		$where = substr($this->formvars['sql_'.$this->formvars['selected_layer_id']], strrpos(strtolower($this->formvars['sql_'.$this->formvars['selected_layer_id']]), 'where')+5);
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
    $temp_table = 'public.shp_export_'.rand(1, 10000);
    $sql = 'CREATE TABLE '.$temp_table.' AS '.$sql;		# temporäre Tabelle erzeugen, damit das/die Schema/ta berücksichtigt werden
    $ret = $layerdb->execSQL($sql,4, 0);
    $sql = 'SELECT * FROM '.$temp_table;
    $ret = $layerdb->execSQL($sql,4, 0);
    if (!$ret[0]) {
      $count = pg_num_rows($ret[1]);
      showAlert('Abfrage erfolgreich. Es wurden '.$count.' Zeilen geliefert.');
      $this->formvars['layer_name'] = umlaute_umwandeln($this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace('.', '_', $this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace('(', '_', $this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace(')', '_', $this->formvars['layer_name']);
      $this->formvars['layer_name'] = str_replace('/', '_', $this->formvars['layer_name']);
      $folder = 'shp_Export_'.$this->formvars['layer_name'].rand(0,10000);
      mkdir(IMAGEPATH.$folder);                       # Ordner erzeugen
      $command = 'export PGCLIENTENCODING=LATIN1;';
      $command.= POSTGRESBINPATH.'pgsql2shp -u '.$layerdb->user;
      if($layerdb->passwd != '')$command.= ' -P '.$layerdb->passwd;
      $command.= ' -f '.IMAGEPATH.$folder.'/'.$this->formvars['layer_name'].' '.$layerdb->dbName.' '.$temp_table; 
      exec($command);
      #echo $command;
      exec(ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*'); # Ordner zippen
      #echo ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*';
      $this->formvars['filename'] = TEMPPATH_REL.$folder.'.zip';
      #rmdir(IMAGEPATH.$folder);         # Ordner löschen
      $sql = 'DROP TABLE '.$temp_table;		# temp. Tabelle wieder löschen
      $ret = $layerdb->execSQL($sql,4, 0);
      $currenttime=date('Y-m-d H:i:s',time());
    	$user->rolle->setConsumeShape($currenttime,$this->formvars['selected_layer_id'],$count);
      return $this->formvars['filename'];
    }
    else{
      showAlert('Abfrage fehlgeschlagen.');
    }
  }
 
}
?>
