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
		$this->delimiters = array("\t", ';', ' ', ',');		# erlaubte Trennzeichen
  }

################# Import #################
	
  function create_import_rollenlayer($formvars, $type, $stelle, $user, $database, $pgdatabase){
		$_files = $_FILES;
		$file = $_files['file1']['name'];
		switch ($type){
			case 'Shape' : {
				$custom_tables = $this->import_custom_shape($formvars, $pgdatabase);
				$formvars['epsg'] = $custom_tables[0]['epsg'];
				if($file == '')$file = $formvars['shapefile'];
			}break;
			case 'point' : {
				$file = basename($formvars['file1']);
				$custom_tables = $this->import_custom_pointlist($formvars, $pgdatabase);
			}break;
			case 'GPX' : {
				$formvars['epsg'] = 4326;
				$custom_tables = $this->import_custom_gpx($formvars, $pgdatabase);				
			}break;
			case 'OVL' : {
				$formvars['epsg'] = 4326;
				$custom_tables = $this->import_custom_ovl($formvars, $pgdatabase);
			}break;
			case 'DXF' : {
				$custom_tables = $this->import_custom_dxf($formvars, $pgdatabase);
			}break;
			case 'GeoJSON' : {
				$custom_tables = $this->import_custom_geojson($pgdatabase);
				$formvars['epsg'] = $custom_tables[0]['epsg'];
			}break;
		}
		if($custom_tables != NULL){
			foreach($custom_tables as $custom_table){				# ------ Rollenlayer erzeugen ------- #
				$layer_id = $this->create_rollenlayer(
					$database,
					$pgdatabase,
					$stelle,
					$user,
					$file." (".date('d.m. H:i',time()).")".str_repeat(' ', $custom_table['datatype']),
					CUSTOM_SHAPE_SCHEMA,
					$custom_table,
					$formvars['epsg']
				);
			}
			return -$layer_id;
		}
	}

	function create_rollenlayer($database, $pgdatabase, $stelle, $user, $layername, $schema, $custom_table, $epsg) {
		$dbmap = new db_mapObj($stelle->id, $user->id);
		$group = $dbmap->getGroupbyName('Eigene Importe');
		if($group != ''){
			$groupid = $group['id'];
		}
		else{
			$groupid = $dbmap->newGroup('Eigene Importe', 1);
		}
		$user->rolle->set_one_Group($user->id, $stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
		$this->formvars['user_id'] = $user->id;
		$this->formvars['stelle_id'] = $stelle->id;
		$this->formvars['aktivStatus'] = 1;
		$this->formvars['Name'] = $layername;
		$this->formvars['Gruppe'] = $groupid;
		$this->formvars['Typ'] = 'import';
		$this->formvars['Datentyp'] = $custom_table['datatype'];
		$select = 'oid, the_geom';
		if($custom_table['labelitem'] != '')$select .= ', '.$custom_table['labelitem'];
		$this->formvars['Data'] = 'the_geom from (SELECT '.$select.' FROM '.CUSTOM_SHAPE_SCHEMA.'.'.$custom_table['tablename'].' WHERE 1=1 '.$custom_table['where'].')as foo using unique oid using srid='.$epsg;
		$this->formvars['query'] = 'SELECT * FROM '.$custom_table['tablename'].' WHERE 1=1'.$custom_table['where'];
		$connectionstring ='user='.$pgdatabase->user;
		if($pgdatabase->passwd != '')$connectionstring.=' password='.$pgdatabase->passwd;
		$connectionstring.=' dbname='.$pgdatabase->dbName;
    if($pgdatabase->host != 'localhost') $connectionstring .= ' host=' . $pgdatabase->host;
		$this->formvars['connection'] = $connectionstring;
		$this->formvars['connectiontype'] = 6;
		$this->formvars['epsg_code'] = $epsg;
		$this->formvars['transparency'] = 65;
		if($custom_table['labelitem'] != '')$this->formvars['labelitem'] = $custom_table['labelitem'];
		$layer_id = $dbmap->newRollenLayer($this->formvars);
		$layerdb = $dbmap->getlayerdatabase(-$layer_id, $this->Stelle->pgdbhost);
		$layerdb->setClientEncoding();
		$path = $this->formvars['query'];
		$attributes = $dbmap->load_attributes($layerdb, $path);
		$dbmap->save_postgis_attributes(-$layer_id, $attributes, '', '');
		$attrib['name'] = ' ';
		$attrib['layer_id'] = -$layer_id;
		$attrib['expression'] = '';
		$attrib['order'] = 0;
		$class_id = $dbmap->new_Class($attrib);
		$this->formvars['class'] = $class_id;
		$color = $user->rolle->readcolor();
		$style['colorred'] = $color['red'];
		$style['colorgreen'] = $color['green'];
		$style['colorblue'] = $color['blue'];
		$style['outlinecolorred'] = 0;
		$style['outlinecolorgreen'] = 0;
		$style['outlinecolorblue'] = 0;
		switch ($custom_table['datatype']) {
			case 0 :{
				$style['size'] = 8;
				$style['maxsize'] = 8;
				$style['symbolname'] = 'circle';
			}break;
			case 1 :{
				$style['width'] = 2;
				$style['minwidth'] = 1;
				$style['maxwidth'] = 3;
				$style['symbolname'] = NULL;
			}break;
			case 2 :{
				$style['size'] = 1;
				$style['maxsize'] = 2;
				$style['symbolname'] = NULL;
			}
		}
		$style['backgroundcolor'] = NULL;
		$style['minsize'] = NULL;
		$style['angle'] = 360;
		$style_id = $dbmap->new_Style($style);	
		$dbmap->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
		if($custom_table['labelitem'] != ''){
			$label['font'] = 'arial';
			$label['color'] = '0 0 0';
			$label['outlinecolor'] = '255 255 255';
			$label['size'] = 8;
			$label['minsize'] = 6;
			$label['maxsize'] = 10;
			$label['position'] = 9;
			$new_label_id = $dbmap->new_Label($label);				
			$dbmap->addLabel2Class($class_id, $new_label_id, 0);
		}
    return $layer_id;
	}

	function load_custom_pointlist($formvars){
		$_files = $_FILES;
		if($_files['file1']['name']){
			$this->pointfile = UPLOADPATH.$_files['file1']['name'];
			if(move_uploaded_file($_files['file1']['tmp_name'], $this->pointfile)){
				$rows = file($this->pointfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				$delimiters = implode($this->delimiters);
				while(count($this->delimiters) > 0 AND count($this->columns) < 2){
					$this->delimiter = array_shift($this->delimiters);
					$i = 0;
					while(trim($rows[$i], "$delimiters\n\r") == ''){	// Leerzeilen überspringen bis zur ersten Zeile mit Inhalt
						$i++;
					}					
					$this->columns = explode($this->delimiter, utf8_encode($rows[$i]));
					echo '<br>';
				}
			}
		}
	}
	
	function import_custom_pointlist($formvars, $pgdatabase){
		$rows = file($formvars['file1'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($formvars['file1']), 0, 15))).rand(1,1000000);
		$i = 0;
		while(trim($rows[$i], $formvars['delimiter']."\n\r") == ''){	// Leerzeilen überspringen bis zur ersten Zeile mit Inhalt
			$i++;
		}
		$columns = explode($formvars['delimiter'], $rows[$i]);
		for($i = 0; $i < count($columns); $i++){
			if($formvars['column'.$i] == 'x' AND !is_numeric(str_replace(',', '.', $columns[$i])))$headlines = true;		// die erste Zeile enthält die Spaltenüberschriften
		}
		$sql = "CREATE TABLE ".CUSTOM_SHAPE_SCHEMA.".".$tablename." (";
		$komma = false;
		for($i = 0; $i < count($columns); $i++){
			if($formvars['column'.$i] != 'x' AND $formvars['column'.$i] != 'y'){
				if($komma)$sql.= ", ";
				$j = $i+1;
				if($headlines){
					if(is_numeric(substr($columns[$i], 0, 1)))$columns[$i] = '_'.$columns[$i];
					$column = strtolower(umlaute_umwandeln(utf8_encode($columns[$i])));
					$sql.= $column." varchar";
					if($formvars['column'.$i] == 'label')$labelitem = $column;
				}
				else{
					$sql.= "spalte".$j." varchar";
					if($formvars['column'.$i] == 'label')$labelitem = 'spalte'.$j;
				}
				$komma = true;
			}
		}
		$sql.= ")WITH (OIDS=TRUE);";
		$sql.= "SELECT AddGeometryColumn('".CUSTOM_SHAPE_SCHEMA."', '".$tablename."', 'the_geom', ".$formvars['epsg'].", 'POINT', 2);";
		$sql.= "CREATE INDEX ".$tablename."_gist_idx ON ".CUSTOM_SHAPE_SCHEMA.".".$tablename." USING gist (the_geom );";
		$i = 0;
		foreach($rows as $row){
			if($headlines AND $i == 0 OR trim($row, $formvars['delimiter']."\n\r") == ''){$i++;continue;}				// Überschriftenzeile und Leerzeilen auslassen
			$columns = explode($formvars['delimiter'], $row);
			$sql.= "INSERT INTO ".CUSTOM_SHAPE_SCHEMA.".".$tablename." VALUES(";
			$komma = false;
			for($i = 0; $i < count($columns); $i++){
				if($formvars['column'.$i] != 'x' AND $formvars['column'.$i] != 'y'){
					if($komma)$sql.= ", ";
					$sql.= "E'".addslashes(utf8_encode($columns[$i]))."'";
					$komma = true;
				}
				else{
					$$formvars['column'.$i] = $columns[$i];			# Hier werden $x und $y gesetzt (nicht das doppelte $ wegnehmen!)
				}
			}
			$x = str_replace(',', '.', $x);
			$y = str_replace(',', '.', $y);
			if($komma)$sql.= ", ";
			if(!is_numeric($x) OR !is_numeric($y))$sql.= "NULL);";
			else $sql.= "st_geomfromtext('POINT(".$x." ".$y.")', ".$formvars['epsg']."));";
			$i++;
		}
		#echo $sql;
		$ret = $pgdatabase->execSQL($sql,4, 0);
		if(!$ret[0]){
			$custom_table['datatype'] = 0;
			$custom_table['tablename'] = $tablename;
			$custom_table['labelitem'] = $labelitem;
			return array($custom_table);
		}
	}
	
	function import_custom_ovl($formvars, $pgdatabase){
		$_files = $_FILES;
		if($_files['file1']['name']){
			$this->ovlfile = UPLOADPATH.$_files['file1']['name'];
			if(move_uploaded_file($_files['file1']['tmp_name'], $this->ovlfile)){
				$rows = file($this->ovlfile);
				$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($formvars['file1']), 0, 15))).rand(1,1000000);		
				foreach($rows as $row){
					$kvp = explode('=', $row);
					if($kvp[1] != '')$kvps[$kvp[0]] = $kvp[1];
					if($kvp[0] == 'XKoord' OR $kvp[0] == 'XKoord0'){
						$geom = $kvp[1];
						$geom_start = true;
						$komma = false;
					}
					elseif($geom_start){
						if($komma){
							$geom.= ',';
							$komma = false;
						}
						else{
							$geom.= ' ';
							$komma = true;
						}
						$geom.= $kvp[1];
						if($startpoint == '')$startpoint = $geom;
					}
				}
				switch($kvps['Typ']){
					case 2 : case 6:	{
						$geomtype = 'POINT';
						$geom = 'POINT('.$geom.')';
						$custom_table['datatype'] = 0;
					}break;
					
					case 3 :	{
						$geomtype = 'LINESTRING';
						$geom = 'LINESTRING('.$geom.')';
						$custom_table['datatype'] = 1;
					}break;
					case 4 :	{
						$geomtype = 'POLYGON';
						$geom .= ','.$startpoint;			// Polygonring schliessen
						$geom = 'POLYGON(('.$geom.'))';
						$custom_table['datatype'] = 2;
					}break;
				}				
				$sql = "CREATE TABLE ".CUSTOM_SHAPE_SCHEMA.".".$tablename." (";
				$sql.= "label varchar";
				$sql.= ")WITH (OIDS=TRUE);";
				$sql.= "SELECT AddGeometryColumn('".CUSTOM_SHAPE_SCHEMA."', '".$tablename."', 'the_geom', 4326, '".$geomtype."', 2);";
				
				$sql.= "INSERT INTO ".CUSTOM_SHAPE_SCHEMA.".".$tablename." VALUES(";
				$sql.= "'".$kvps['Text']."'";
				$sql.= ", st_geomfromtext('".$geom."', 4326));";
				#echo $sql;
				$ret = $pgdatabase->execSQL($sql,4, 0);
				if(!$ret[0]){
					$custom_table['tablename'] = $tablename;
					$custom_table['labelitem'] = 'label';
					return array($custom_table);
				}
			}
		}
	}
	
	function get_shp_epsg($file, $pgdatabase){
		global $supportedSRIDs;
		if(file_exists($file.'.prj')){
			$prj = file_get_contents($file.'.prj');
			# 1. Versuch: Suche nach AUTHORITY
			for($i = 0; $i < count($supportedSRIDs); $i++){
				if(strpos($prj, 'AUTHORITY["EPSG","'.$supportedSRIDs[$i].'"]') > 0)return $supportedSRIDs[$i];
			}
			# 2. Versuch: Abgleich bestimmter Parameter im prj-String mit spatial_ref_sys_alias
			$datum = get_first_word_after($prj, 'DATUM[', '"', '"');
			$projection = get_first_word_after($prj, 'PROJECTION[', '"', '"');
			$false_easting = get_first_word_after($prj, 'False_Easting"', ',', ']');
			$central_meridian = get_first_word_after($prj, 'Central_Meridian"', ',', ']');
			$scale_factor = get_first_word_after($prj, 'Scale_Factor"', ',', ']');
			$unit = get_first_word_after($prj, 'UNIT[', '"', '"', true);
			$sql = "SELECT srid FROM spatial_ref_sys_alias
							WHERE '".$datum."' = ANY(datum) 
							AND '".$projection."' = ANY(projection) 
							AND ".$false_easting." = false_easting 
							AND ".$central_meridian." = central_meridian 
							AND ".$scale_factor." = scale_factor 
							AND '".$unit."' = ANY(unit)";
			$ret = $pgdatabase->execSQL($sql,4, 0);
			if(!$ret[0])$result = pg_fetch_row($ret[1]);
			return $result[0];
		}
		else return false;
	}
	
	function import_custom_shape($formvars, $pgdatabase){
		if($formvars['shapefile'] == ''){
			$_files = $_FILES;
			if($_files['file1']['name']){     # eine Zipdatei wurde ausgewählt
				$nachDatei = UPLOADPATH.$_files['file1']['name'];
				if(move_uploaded_file($_files['file1']['tmp_name'],$nachDatei)){
					$files = unzip($nachDatei, false, false, true);
					$firstfile = explode('.', $files[0]);
					$formvars['shapefile'] = $firstfile[0];
					# EPSG-Code aus prj-Datei ermitteln
					if($formvars['epsg'] == '')$formvars['epsg'] = $this->get_shp_epsg(UPLOADPATH.$firstfile[0], $pgdatabase);
					if($formvars['epsg'] == ''){
						$this->shapefile = $formvars['shapefile'];		# EPSG-Code konnte nicht aus prj-Datei ermittelt werden, Dateiname merken und EPSG-Code nachfragen
						return;
					}
				}
			}
		}
		$encoding = $this->getEncoding(UPLOADPATH.$formvars['shapefile'].'.dbf');
		$custom_table = $this->load_shp_into_pgsql($pgdatabase, UPLOADPATH, $formvars['shapefile'], $formvars['epsg'], CUSTOM_SHAPE_SCHEMA, 'a'.strtolower(umlaute_umwandeln(substr($formvars['shapefile'], 0, 15))).rand(1,1000000), $encoding);
		$custom_table[0]['epsg'] = $formvars['epsg'];
		return $custom_table;
	}

	function load_shp_into_pgsql($pgdatabase, $uploadpath, $file, $epsg, $schemaname, $tablename, $encoding = 'LATIN1') {
		if(file_exists($uploadpath . $file . '.dbf') OR file_exists($uploadpath . $file . '.DBF')){
	    $command = POSTGRESBINPATH .
				'shp2pgsql' .
				' -g the_geom' .
				' -I' .
				' -s ' . $epsg .
				' -W ' . $encoding .
				' -c "' . $uploadpath . $file . '"' .
				' ' . $schemaname . '.' . $tablename .
				' > "' . $uploadpath . $file . '.sql"';
	    exec($command, $output, $ret);
			if($ret == 1){	# bei Fehlschlag, das andere Encoding probieren
				if($encoding == 'UTF-8')$new_encoding = 'LATIN1';
				else $new_encoding = 'UTF-8';
				$command = str_replace($encoding, $new_encoding, $command);
				exec($command, $output, $ret);
			}
	   	#echo $command;
			$command = POSTGRESBINPATH .
				'psql' .
				' -h ' . $pgdatabase->host .
				' -f "' . $uploadpath . $file . '.sql"' .
				' ' . $pgdatabase->dbName . ' ' . $pgdatabase->user;
			if ($pgdatabase->passwd != '')
				$command = 'export PGPASSWORD="' . $pgdatabase->passwd . '"; ' . $command;
	    exec($command);
	   	#echo $command;
	    $sql = "
	      ALTER TABLE " . $schemaname . "." . $tablename . "
	      SET WITH OIDS;
	      SELECT
	        geometrytype(the_geom) AS geometrytype
	      FROM
	        " . $schemaname . "." . $tablename . "
	      LIMIT 1;
	    ";
	    $ret = $pgdatabase->execSQL($sql,4, 0);
	    if(!$ret[0]){
	      $result = pg_fetch_assoc($ret[1]);
	      if(strpos($result['geometrytype'], 'POINT') !== false){
	      	$datatype = 0;
	      }elseif(strpos($result['geometrytype'], 'LINESTRING') !== false){
	      	$datatype = 1;
	      }elseif(strpos($result['geometrytype'], 'POLYGON') !== false){
	      	$datatype = 2;
	      }
				$custom_table['datatype'] = $datatype;
				$custom_table['tablename'] = $tablename;
				return array($custom_table);
			}
		}
	}

	function import_custom_gpx($formvars, $pgdatabase){
		$_files = $_FILES;	
		if($_files['file1']['name']){
      $importfile = UPLOADPATH.$_files['file1']['name'];
      if(move_uploaded_file($_files['file1']['tmp_name'],$importfile)){
				if(file_exists($importfile)){
					if($formvars['tracks']){
						$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($importfile), 0, 15))).rand(1,1000000);
						$this->ogr2ogr_import(CUSTOM_SHAPE_SCHEMA, $tablename, $formvars['epsg'], $importfile, $pgdatabase, 'tracks', NULL, NULL, 'UTF8');
						$sql = '
							ALTER TABLE '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' SET WITH OIDS;
							ALTER TABLE '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' RENAME "desc" TO desc_;
							ALTER TABLE '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' RENAME "number" TO number_;
						';
						$ret = $pgdatabase->execSQL($sql,4, 0);
						$custom_table['datatype'] = 1;
						$custom_table['tablename'] = $tablename;
						$custom_tables[] = $custom_table;
					}
					if($formvars['waypoints']){
						$tablename = 'a'.strtolower(umlaute_umwandeln(basename($importfile))).rand(1,1000000);
						$this->ogr2ogr_import(CUSTOM_SHAPE_SCHEMA, $tablename, $formvars['epsg'], $importfile, $pgdatabase, 'waypoints', NULL, NULL, 'UTF8');
						$sql = '
							ALTER TABLE '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' SET WITH OIDS;
							ALTER TABLE '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' RENAME "desc" TO desc_;
							ALTER TABLE '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' RENAME "time" TO time_;
						';
						$ret = $pgdatabase->execSQL($sql,4, 0);
						$custom_table['datatype'] = 0;
						$custom_table['tablename'] = $tablename;
						$custom_tables[] = $custom_table;
					}
		      if(!$ret[0]){
						return $custom_tables;
					}
				}
			}
		}
	}
		
	function import_custom_dxf($formvars, $pgdatabase){
		$_files = $_FILES;	
		if($_files['file1']['name']){
      $importfile = UPLOADPATH.$_files['file1']['name'];
      if(move_uploaded_file($_files['file1']['tmp_name'],$importfile)){
				if(file_exists($importfile)){
					$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($importfile), 0, 15))).rand(1,1000000);
					$this->ogr2ogr_import(CUSTOM_SHAPE_SCHEMA, $tablename, $formvars['epsg'], $importfile, $pgdatabase, NULL);
					$sql = '
						ALTER TABLE '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' SET WITH OIDS;
						
						SELECT geometrytype(the_geom), count(*) FROM '.CUSTOM_SHAPE_SCHEMA.'.'.$tablename.' GROUP BY geometrytype(the_geom);
					';
					$ret = $pgdatabase->execSQL($sql,4, 0);
					if(!$ret[0]){
						$geom_types = array('POINT' => 0, 'LINESTRING' => 1, 'POLYGON' => 2);
						while($result = pg_fetch_assoc($ret[1])){
							if($result['count'] > 0 AND $geom_types[$result['geometrytype']] !== NULL){
								$custom_table['datatype'] = $geom_types[$result['geometrytype']];
								$custom_table['tablename'] = $tablename;
								$custom_table['where'] = " AND geometrytype(the_geom) = '".$result['geometrytype']."'";
								$custom_tables[] = $custom_table;
							}
						}
						return $custom_tables;
					}
				}
			}
		}
	}
	
	function import_custom_geojson($pgdatabase){
		return $this->geojson_import($pgdatabase, CUSTOM_SHAPE_SCHEMA, NULL);
	}
	
	function geojson_import($pgdatabase, $schema, $tablename){
		$_files = $_FILES;	
		if($_files['file1']['name']){
      $importfile = UPLOADPATH.$_files['file1']['name'];
      if(move_uploaded_file($_files['file1']['tmp_name'],$importfile)){
				if(file_exists($importfile)){
					$json = json_decode(file_get_contents($importfile));
					if($json->crs->properties->name != '')$epsg = array_pop(explode('EPSG::', $json->crs->properties->name));
					else $epsg = 4326;
					if($tablename == NULL)$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($importfile), 0, 15))).rand(1,1000000);
					$this->ogr2ogr_import($schema, $tablename, $epsg, $importfile, $pgdatabase, NULL, NULL, NULL, 'UTF8');
					$sql = '
						ALTER TABLE '.$schema.'.'.$tablename.' SET WITH OIDS; 
						SELECT geometrytype(the_geom) AS geometrytype FROM '.$schema.'.'.$tablename.' LIMIT 1;';
					$ret = $pgdatabase->execSQL($sql,4, 0);
					if(!$ret[0]){
						$result = pg_fetch_assoc($ret[1]);
						if(strpos($result['geometrytype'], 'POINT') !== false){
							$datatype = 0;
						}elseif(strpos($result['geometrytype'], 'LINESTRING') !== false){
							$datatype = 1;
						}elseif(strpos($result['geometrytype'], 'POLYGON') !== false){
							$datatype = 2;
						}
					}
					$custom_tables[0]['datatype'] = $datatype;
					$custom_tables[0]['tablename'] = $tablename;
					$custom_tables[0]['epsg'] = $epsg;
		      if(!$ret[0]){
						return $custom_tables;
					}
				}
			}
		}
	}	
	
  function shp_import_speichern($formvars, $database){
   	$this->formvars = $formvars;
    if(file_exists(UPLOADPATH.$this->formvars['dbffile'])){
			$importfile = basename($this->formvars['dbffile'], '.dbf');
			include_(CLASSPATH.'dbf.php');
      $this->dbf = new dbf();
      $this->dbf->header = $this->dbf->get_dbf_header(UPLOADPATH.$this->formvars['dbffile']);
      $this->dbf->header = $this->dbf->get_sql_types($this->dbf->header);
			$sql = 'SELECT ';
      for($i = 0; $i < count($this->dbf->header); $i++){
        if($this->formvars['check_'.$this->dbf->header[$i][0]]){
					if($this->formvars['primary_key'] != $this->formvars['sql_name_'.$this->dbf->header[$i][0]]){
						if($i > 0)$sql .= ', ';
						$sql .= $this->formvars['dbf_name_'.$this->dbf->header[$i][0]].' as '.strtolower($this->formvars['sql_name_'.$this->dbf->header[$i][0]]);
					}
        }
      }
			$sql .= ' FROM '.$importfile;
			$options = $this->formvars['table_option'];
			$options.= ' -nlt PROMOTE_TO_MULTI -lco FID=gid';
			$encoding = $this->getEncoding(UPLOADPATH.$this->formvars['dbffile']);
			$ret = $this->ogr2ogr_import($this->formvars['schema_name'], $this->formvars['table_name'], $formvars['epsg'], UPLOADPATH.$importfile.'.shp', $database, NULL, $sql, $options, $encoding);
			
      
      // # erzeugte SQL-Datei anpassen
      // if($this->formvars['table_option'] == '-u') {
        // $oldsqld = UPLOADPATH.$this->formvars['table_name'].'.sql';
        // # Shared lock auf die Quelldatei
        // $oldsql = fopen($oldsqld, "r");
        // flock($oldsql, 1) or die("Kann die Quelldatei $oldsqld nicht locken.");
        // # Exclusive lock auf die Zieldatei     
        // $newsql = fopen($oldsqld.".new", "w");
        // flock($newsql, 2) or die("Kann die Zieldatei $newsql nicht locken.");
				// # Zeilenweises einlesen der SQL-Datei $oldsqld in das array *sqlold zum weiteren Umformen
        // $sqlold = file($oldsqld);
				// # Anzahl der Zeilen bestimmen
				// $anzzei = count($sqlold);
				// # Schleife für jede Zeile durchlaufen
				// for ($i = 0; $i < $anzzei; $i++) {
				// # Neuer SQL-Befehl $sqlnew wird gelesen
					// $sqlnew = $sqlold[$i];
				// # Wenn der SQL-Befehl mit INSERT beginnt, dann weiterverarbeiten
          // if (substr($sqlnew,0,6) == "INSERT") {
  			// # alte Befehlszeile wird bei jedem Leerzeichen gesplittet  
            // $old = explode(" ",$sqlnew);
  			// # Feldbezeichner werden herausgelesen, sind durch Kommata getrennt
            // $feld = explode(",",$old[3]);
  			// # da Feldbezeichner in der INSERT-Anweisung eingeklammert sind werden die oeffnende und schliessende Klammer entfernt
            // for ($j=0; $j < count($feld); $j++) {
              // $feld[$j] = trim($feld[$j],"()");
            // }
  			// # heraussuchen, an welcher Stelle der primary_key steht
            // $primkey = array_search($this->formvars['primary_key'],$feld);
  			// # Werte extrahieren, sind duch Kommata getrennt
  			// # Achtung, kommen in den Werten Kommata vor, so wird hier ein fehlerhaftes Statement erzeugt, da die Anzahl der Felder nicht mehr mit der Anzahl der Werte uebereinstimmt
            // $wert = explode(",",$old[5]);
  			// # Bereinigen der Werte
            // for ($j=0; $j < count($wert); $j++) {
              // $wert[$j] = trim($wert[$j]);
              // $wert[$j] = trim($wert[$j],"(;)");
            // }
  			// # SQL-Anweisung neu schreiben
            // $sqlnew = "UPDATE ".$this->formvars['table_name']." SET ";
  			// # den Feldbezeichnern die Werte zuweisen
            // for ($j=0; $j < count($feld); $j++) {
              // $sqlnew .= $feld[$j]." = ". $wert[$j];
    		// # Wertzuweisungen mit Komma voneinander trennen
              // if ($j < count($feld)-1) {
                // $sqlnew .= ", ";
              // }
            // }
  			// # Bindungung hinzufuegen 
            // $sqlnew .= " WHERE ".$feld[$primkey]." = ".$wert[$primkey].";";
          // }
  			// # SQL-Anweisung in die neue Datei $newsql schreiben  
          // fwrite($newsql,$sqlnew);
        // }
        // fclose($oldsql);
        // unlink($oldsqld);
        // rename($oldsqld.".new", $oldsqld);
        // fclose($newsql);
      // }
			
			if($ret == 0){
				$sql = 'ALTER TABLE '.$this->formvars['schema_name'].'.'.$this->formvars['table_name'].' SET WITH OIDS;SELECT count(*) FROM '.$this->formvars['schema_name'].'.'.$this->formvars['table_name'];
				$ret = $database->execSQL($sql,4, 0);
				if (!$ret[0]) {
					$count = pg_fetch_row($ret[1]);
					$alert = 'Import erfolgreich.';
					if($this->formvars['table_option'] == ''){
						$alert.= ' Die Tabelle '.$this->formvars['schema_name'].'.'.$this->formvars['table_name'].' wurde erzeugt.';
					}
					$alert .= ' Die Tabelle enthält jetzt '.$count[0].' Datensätze.';
					showAlert($alert);
				}
			}
      else{
        showAlert('Import fehlgeschlagen.');
      }
    }
  }
  
  function shp_import($formvars, $pgdatabase){
		include_(CLASSPATH.'dbf.php');
  	$_files = $_FILES;
    $this->formvars = $formvars;
    if($_files['zipfile']['name']){     # eine Zipdatei wurde ausgewählt
      $this->formvars['zipfile'] = $_files['zipfile']['name'];
      $nachDatei = UPLOADPATH.$_files['zipfile']['name'];
      if(move_uploaded_file($_files['zipfile']['tmp_name'],$nachDatei)){
        $files = unzip($nachDatei, false, false, true);
        $firstfile = explode('.', $files[0]);
				$this->formvars['epsg'] = $this->get_shp_epsg(UPLOADPATH.$firstfile[0], $pgdatabase);
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
		if($_files['ukofile']['name']){     
		  $formvars['ukofile'] = $_files['ukofile']['name'];
		  $nachDatei = UPLOADPATH.$_files['ukofile']['name'];
		  if(move_uploaded_file($_files['ukofile']['tmp_name'],$nachDatei)){
				$dateinamensteil = explode('.', $nachDatei);
				if(strtolower($dateinamensteil[1]) == 'zip'){
					$files = unzip($nachDatei, false, false, true);
				}
				else $files = array($_files['ukofile']['name']);
				for($i = 0; $i < count($files); $i++){
					$wkt = file_get_contents(UPLOADPATH.$files[$i]);
					$wkt = substr($wkt, strpos($wkt, 'KOO ')+4);
					$wkt = str_replace(chr(13), '', $wkt);
					$wkt = 'MULTIPOLYGON((('.$wkt;
					$wkt = str_replace(chr(13).'FL+'.chr(13).'KOO ', ')),((', $wkt);
					$wkt = str_replace(chr(10).'FL+'.chr(10).'KOO ', ')),((', $wkt);
					$wkt = str_replace(chr(10).'FL-'.chr(10).'KOO ', '),(', $wkt);
					$wkt = str_replace(chr(10).'KOO ', ',', $wkt);
					$wkt.= ')))';
					$sql = "INSERT INTO uko_polygon (username, userid, dateiname, the_geom) VALUES('".$username."', ".$userid.", '".$_files['ukofile']['name']."', st_transform(st_geomfromtext('".$wkt."', ".$formvars['epsg']."), ".$this->uko_srid.")) RETURNING oid";
					$ret = $database->execSQL($sql,4, 1);
					if ($ret[0])$this->success = false;
					else{
						$this->success = true;
						$rs=pg_fetch_array($ret[1]);
						$oids[] = $rs[0];
					}
				}
				return $oids;
		  }
		}
	}
  
	
################### Export ########################

	function export($formvars, $stelle, $user, $mapdb){
		$this->formvars = $formvars;
		$this->layerdaten = $stelle->getqueryablePostgisLayers(NULL, 1);
		if ($this->formvars['selected_layer_id']) {
			$this->layerset = $user->rolle->getLayer($this->formvars['selected_layer_id']);
			$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $stelle->pgdbhost);
			$path = $this->layerset[0]['pfad'];
			$privileges = $stelle->get_attributes_privileges($this->formvars['selected_layer_id'], true);
			$newpath = $stelle->parse_path($layerdb, $path, $privileges);
			$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
		}
	}
	
	function ogr2ogr_export($sql, $exportformat, $exportfile, $layerdb){
		$command = 'export PGCLIENTENCODING=UTF-8;'.OGR_BINPATH.'ogr2ogr -f '.$exportformat.' -lco ENCODING=UTF-8 -sql "'.$sql.'" '.$exportfile.' PG:"dbname='.$layerdb->dbName.' user='.$layerdb->user;
		if($layerdb->passwd != '')$command.= ' password='.$layerdb->passwd;
		if($layerdb->port != '')$command.=' port='.$layerdb->port;
		if($layerdb->host != '') $command .= ' host=' . $layerdb->host;
		exec($command.'"');
	}
	
	function ogr2ogr_import($schema, $tablename, $epsg, $importfile, $database, $layer, $sql = NULL, $options = NULL, $encoding = 'LATIN1'){
		$command = 'export PGCLIENTENCODING='.$encoding.';'.OGR_BINPATH.'ogr2ogr ';
		if($options != NULL)$command.= $options;
		$command.= ' -f PostgreSQL -lco GEOMETRY_NAME=the_geom -nln '.$tablename.' -a_srs EPSG:'.$epsg;
		if($sql != NULL)$command.= ' -sql "'.$sql.'"';
		$command.= ' -append PG:"dbname='.$database->dbName.' user='.$database->user.' active_schema='.$schema;
		if($database->passwd != '')$command.= ' password='.$database->passwd;
		if($database->port != '')$command.=' port='.$database->port;
		if($database->host != '') $command .= ' host=' . $database->host;
		$command .= '" "'.$importfile.'" '.$layer;
		#echo $command;
		exec($command, $output, $ret);
		return $ret;
	}
	
	function getEncoding($dbf){
		$folder = dirname($dbf);
		$command = OGR_BINPATH.'ogr2ogr -f CSV '.$folder.'/test.csv '.$dbf;
		exec($command, $output, $ret);
		$command = 'file '.$folder.'/test.csv';
		exec($command, $output, $ret);
		unlink($folder.'/test.csv');
		if(strpos($output[0], 'UTF') !== false)$encoding = 'UTF-8';
		if(strpos($output[0], 'ISO-8859') !== false)$encoding = 'LATIN1';
		if(strpos($output[0], 'ASCII') !== false)$encoding = 'LATIN1';
		return $encoding;
	}
	
	function create_csv($result, $attributes, $groupnames){
		# Gruppennamen in die erste Zeile schreiben
		if($groupnames != ''){
			foreach($result[0] As $key => $value){
				$i = $attributes['indizes'][$key];
				if($attributes['type'][$i] != 'geometry' AND $attributes['name'][$i] != 'lock'){
					$groupname = explode(';', $attributes['group'][$i]);
					$csv .= $groupname[0].';';
				}
			}
			$csv .= chr(13).chr(10);
		}
		
    # Spaltenüberschriften schreiben
    # Excel is zu blöd für 'ID' als erstes Attribut
		if(substr($attributes['alias'][0], 0, 2) == 'ID'){
      $attributes['alias'][0] = str_replace('ID', 'id', $attributes['alias'][0]);
    }
    if(substr($attributes['name'][0], 0, 2) == 'ID'){
      $attributes['name'][0] = str_replace('ID', 'id', $attributes['name'][0]);
    }
    foreach($result[0] As $key => $value){
			$i = $attributes['indizes'][$key];
    	if($attributes['type'][$i] != 'geometry' AND $attributes['name'][$i] != 'lock'){
	      if($attributes['alias'][$i] != ''){
	        $names[] = $attributes['alias'][$i];
	      }
	      else{
	        $names[] = $attributes['name'][$i];
	      }
    	}
    }
    $csv .= implode(';', $names).chr(13).chr(10);
 
    # Daten schreiben
    for($i = 0; $i < count($result); $i++){
			foreach($result[$i] As $key => $value){
				$j = $attributes['indizes'][$key];
      	if($attributes['type'][$j] != 'geometry' AND $attributes['name'][$i] != 'lock'){
					if($attributes['form_element_type'][$j] == 'Zahl'){
						$value = tausenderTrenner($value);
					}
					else{
						if($attributes['form_element_type'][$j] == 'Auswahlfeld'){
							if(is_array($attributes['dependent_options'][$j])){
								$enum_value = $attributes['enum_value'][$j][$i];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
								$enum_output = $attributes['enum_output'][$j][$i];		# mehrere Datensätze und ein abhängiges Auswahlfeld --> verschiedene Auswahlmöglichkeiten
							}
							else{
								$enum_value = $attributes['enum_value'][$j];
								$enum_output = $attributes['enum_output'][$j];
							}
							for($o = 0; $o < count($enum_value); $o++){
								if($value == $enum_value[$o]){
									$value = $enum_output[$o];
									break;
								}
							}
						}
						else{
							if($attributes['form_element_type'][$j] == 'Autovervollständigungsfeld'){
								$value = $attributes['enum_output'][$j][$i];
							}
							if($attributes['type'][$j] == 'bool'){
								$value = str_replace('t', "ja", $value);	
								$value = str_replace('f', "nein", $value);
							}
						}
						$value = str_replace(';', ",", $value);
						if(strpos($value, chr(10)) !== false OR strpos($value, chr(13)) !== false){		# Zeilenumbruch => Wert in Anführungszeichen setzen
							$value = str_replace('"', "'", $value);
							$value = '"'.$value.'"';
						}
						$strpos = strpos($value, '/');
						if ($strpos !== false AND $strpos < 3) {		# Excel-Datumsproblem
							$value = $value."\t";
						}
						if(in_array($attributes['type'][$j], array('numeric', 'float4', 'float8'))){
							$value = str_replace('.', ",", $value);				#  Excel-Datumsproblem
						}
					}
					$values[$i][] = $value;
      	}
      }
      $csv .= implode(';', $values[$i]).chr(13).chr(10);
    }
    
    $currenttime=date('Y-m-d H:i:s',time());
		return utf8_decode($csv);
	}
  
	function create_uko($layerdb, $table, $column, $epsg){
		$sql.= "SELECT st_astext(st_multi(st_union(st_transform(".$column.", ".$epsg.")))) as geom FROM ".$table;
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
	
	function create_ovl($datentyp, $layerdb, $table, $column, $epsg){
		$ovl_type = array(MS_LAYER_POINT => 6, MS_LAYER_LINE => 3, MS_LAYER_POLYGON => 4);
		$sql.= "SELECT st_astext(";
		if($datentyp == MS_LAYER_POLYGON)$sql.= "ST_MakePolygon(st_exteriorring(geom))) as geom ";
		else $sql.= "geom) as geom ";
		$sql.= "FROM (select (st_dump(st_union(st_transform(".$column.", ".$epsg.")))).geom as geom FROM ".$table.") as foo";
		#echo $sql;
		$ret = $layerdb->execSQL($sql,4, 1);
		if(!$ret[0]){
			$i = 0;
			while($rs=pg_fetch_assoc($ret[1])){
				$wkt = str_replace('POLYGON((', '', $rs['geom']);
				$wkt = str_replace('LINESTRING(', '', $wkt);
				$wkt = str_replace('POINT(', '', $wkt);
				$wkt = str_replace(')', '', $wkt);
				$coords = explode(',', $wkt);
				$coord_count = count($coords);
				if($datentyp == MS_LAYER_POLYGON)$coord_count = $coord_count - 1;
				$ovl[$i] = '[Overlay]'.chr(10).'Symbols=1'.chr(10).'[MapLage]'.chr(10).'[Symbol 1]'.chr(10).'Typ='.$ovl_type[$datentyp].chr(10).'Group=1'.chr(10).'Dir=100'.chr(10).'Art=1'.chr(10).'Col=1'.chr(10).'Zoom=1'.chr(10).'Size=103'.chr(10).'Area=4'.chr(10).'Punkte='.$coord_count.chr(10);				
				for($c = 0; $c < $coord_count; $c++){
					$coords_part = explode(' ', $coords[$c]);
					$ovl[$i] .= 'XKoord'.$c.'='.$coords_part[0].chr(10);
					$ovl[$i] .= 'YKoord'.$c.'='.$coords_part[1].chr(10);
				}
				$i++;
			}
		}
		return $ovl;
  }
	
	function export_exportieren($formvars, $stelle, $user){
		$currenttime = date('Y-m-d H:i:s',time());
  	$this->formvars = $formvars;
  	$layerset = $user->rolle->getLayer($this->formvars['selected_layer_id']);
    $mapdb = new db_mapObj($stelle->id,$user->id);
    $layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $stelle->pgdbhost);
		$sql = str_replace('$hist_timestamp', rolle::$hist_timestamp, $layerset[0]['pfad']);
    $privileges = $stelle->get_attributes_privileges($this->formvars['selected_layer_id'], true);
    $this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
		$filter = $mapdb->getFilter($this->formvars['selected_layer_id'], $stelle->id);
		
		# Where-Klausel aus Sachdatenabfrage-SQL
		$where = substr(strip_pg_escape_string($this->formvars['sql_'.$this->formvars['selected_layer_id']]), strrpos(strtolower(strip_pg_escape_string($this->formvars['sql_'.$this->formvars['selected_layer_id']])), 'where'));
		
		# order by rausnehmen
  	$orderbyposition = strrpos(strtolower($sql), 'order by');
		$lastfromposition = strrpos(strtolower($sql), 'from');
  	if($orderbyposition !== false AND $orderbyposition > $lastfromposition){
	  	$orderby = ' '.substr($sql, $orderbyposition);
	  	$sql = substr($sql, 0, $orderbyposition);
  	}
		# group by rausnehmen
		$groupbyposition = strpos(strtolower($sql), 'group by');
		if($groupbyposition !== false){
			$groupby = ' '.substr($sql, $groupbyposition);
			$sql = substr($sql, 0, $groupbyposition);
  	}
		
		# Zusammensammeln der Attribute, die abgefragt werden müssen
    for ($i = 0; $i < count($this->attributes['name']); $i++) {
    	if ($this->formvars['check_'.$this->attributes['name'][$i]] or $this->formvars['all'] == 1) {		# Entweder das Attribut oder alle wurde angehakt
    		$selection[$this->attributes['name'][$i]] = 1;
				$selected_attributes[] = $this->attributes['name'][$i];						# Zusammensammeln der angehakten Attribute, denn nur die sollen weiter unten auch exportiert werden
				$selected_attr_types[] = $this->attributes['type'][$i];
    	}
			if (strpos($where, 'query.'.$this->attributes['name'][$i])) {			# oder es kommt in der Where-Bedingung des Sachdatenabfrage-SQLs vor
				$selection[$this->attributes['name'][$i]] = 1;
			}
			if (strpos($orderby, $this->attributes['name'][$i])) {						# oder es kommt im ORDER BY des Layer-Query vor
				$selection[$this->attributes['name'][$i]] = 1;
			}
			if (strpos($filter, $this->attributes['name'][$i])) {						# oder es kommt im Filter des Layers vor
				$selection[$this->attributes['name'][$i]] = 1;
			}
			if ($this->formvars['download_documents'] != '' AND $this->attributes['form_element_type'][$i] == 'Dokument') {			# oder das Attribut ist vom Typ "Dokument" und die Dokumente sollen auch exportiert werden
				$selection[$this->attributes['name'][$i]] = 1;
			}
    }
		
    $sql = $stelle->parse_path($layerdb, $sql, $selection);		# parse_path wird hier benutzt um die Auswahl der Attribute auf das Pfad-SQL zu übertragen
		
		# oid auch abfragen
		$distinctpos = strpos(strtolower($sql), 'distinct');
		if ($distinctpos !== false && $distinctpos < 10) {
			$pfad = substr(trim($sql), $distinctpos+8);
			$distinct = true;
		}
		else {
			$pfad = substr(trim($sql), 7);
		}
		$j = 0;
		foreach ($this->attributes['all_table_names'] as $tablename) {
			if(($tablename == $layerset[0]['maintable']) AND $this->attributes['oids'][$j]){		# hat Haupttabelle oids?
				$pfad = $this->attributes['table_alias_name'][$tablename].'.oid AS '.$tablename.'_oid, '.$pfad;
				if($groupby != '')$groupby .= ','.$this->attributes['table_alias_name'][$tablename].'.oid';
			}
			$j++;
		}
		if ($distinct == true){
			$pfad = 'DISTINCT '.$pfad;
		}
		$sql = "SELECT " . $pfad;

		# Bedingungen
  	if($where != ''){		# Where-Klausel aus Sachdatenabfrage-SQL (abgefragter Extent, Suchparameter oder oids)
  		$orderbyposition = strpos(strtolower($where), 'order by');
  		if($orderbyposition)$where = substr($where, 0, $orderbyposition);
	    $sql = "SELECT * FROM (".$sql.$groupby.") as query ".$where;
  	}
    elseif($filter != ''){		# Filter muss nur dazu, wenn kein $where vorhanden, also keine Abfrage gemacht wurde, sondern der gesamte Layer exportiert werden soll (Filter ist ja schon im $where enthalten)
			$filter = str_replace('$userid', $user->id, $filter);
    	$sql = "SELECT * FROM (".$sql.$groupby.") as query WHERE ".$filter;
    }
    if($this->formvars['newpathwkt']){	# über Polygon einschränken
    	$sql.= " AND ST_INTERSECTS(".$this->attributes['the_geom'].", st_transform(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$user->rolle->epsg_code."), ".$layerset[0]['epsg_code']."))";
    }
    $sql.= $orderby;
		#echo $sql;
		
    $temp_table = 'shp_export_'.rand(1, 10000);
    $sql = 'CREATE TABLE public.'.$temp_table.' AS '.$sql;		# temporäre Tabelle erzeugen, damit das/die Schema/ta berücksichtigt werden
    $ret = $layerdb->execSQL($sql,4, 0);
				
		for($s = 0; $s < count($selected_attributes); $s++){
			# Transformieren der Geometrie
			if($this->attributes['the_geom'] == $selected_attributes[$s])$selected_attributes[$s] = 'st_transform('.$selected_attributes[$s].', '.$this->formvars['epsg'].') as '.$selected_attributes[$s];
			# das Abschneiden bei nicht in der Länge begrenzten Textspalten verhindern
			if($this->formvars['export_format'] == 'Shape'){
				if(in_array($selected_attr_types[$s], array('text', 'varchar')))$selected_attributes[$s] = $selected_attributes[$s].'::varchar(254)';
			}
		}
    $sql = 'SELECT '.implode(', ', $selected_attributes).' FROM public.'.$temp_table;		# auf die ausgewählten Attribute einschränken
    $ret = $layerdb->execSQL($sql,4, 0);
    if(!$ret[0]){
      $count = pg_num_rows($ret[1]);
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
					$this->ogr2ogr_export($sql, '"ESRI Shapefile"', $exportfile.'.shp', $layerdb);
					if(!file_exists($exportfile.'.cpg')){		// ältere ogr-Versionen erzeugen die cpg-Datei nicht
						$fp = fopen($exportfile.'.cpg', 'w');
						fwrite($fp, 'UTF-8');
						fclose($fp);
					}
					$zip = true;
				}break;
				
				case 'GML' : {
					$this->ogr2ogr_export($sql, 'GML', $exportfile.'.xml', $layerdb);
					$zip = true;
				}break;
				
				case 'KML' : {
					$exportfile = $exportfile.'.kml';
					$this->ogr2ogr_export($sql, 'KML', $exportfile, $layerdb);
					$contenttype = 'application/vnd.google-earth.kml+xml';
				}break;
				
				case 'GeoJSON' : {
					$exportfile = $exportfile.'.json';
					$this->ogr2ogr_export($sql, 'GeoJSON', $exportfile, $layerdb);
				}break;
				
				case 'CSV' : {
					while($rs=pg_fetch_assoc($ret[1])){
						$result[] = $rs;
					}
					$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, $result, true, $stelle->id, true);
					$csv = $this->create_csv($result, $this->attributes, $formvars['export_groupnames']);
					$exportfile = $exportfile.'.csv';
					$fp = fopen($exportfile, 'w');
					fwrite($fp, $csv);
					fclose($fp);
					$contenttype = 'application/vnd.ms-excel';
					$user->rolle->setConsumeCSV($currenttime,$this->formvars['selected_layer_id'], $count);
				}break;
				
				case 'UKO' : {
					$uko = $this->create_uko($layerdb, $temp_table, $this->attributes['the_geom'], $this->formvars['epsg']);
					$exportfile = $exportfile.'.uko';
					$fp = fopen($exportfile, 'w');
					fwrite($fp, $uko);
					fclose($fp);
					$contenttype = 'text/uko';
				}break;
				
				case 'OVL' : {
					$ovl = $this->create_ovl($layerset[0]['Datentyp'], $layerdb, $temp_table, $this->attributes['the_geom'], $this->formvars['epsg']);
					for($i = 0; $i < count($ovl); $i++){
						$exportfile2 = $exportfile.'_'.$i.'.ovl';
						$fp = fopen($exportfile2, 'w');
						fwrite($fp, $ovl[$i]);
						fclose($fp);
					}
					$zip = true;
				}break;
			}
			# Dokumente auch mit dazupacken
			if($this->formvars['download_documents'] != ''){
				if($result == NULL){
					while($rs=pg_fetch_assoc($ret[1])){
						$result[] = $rs;
					}
				}
				for($i = 0; $i < count($result); $i++){
					foreach($result[$i] As $key => $value){
						$j = $this->attributes['indizes'][$key];
						if($this->attributes['form_element_type'][$j] == 'Dokument' AND $value != ''){
							$parts = explode('&original_name=', $value);
							if($parts[1] == '')$parts[1] = basename($parts[0]);		# wenn kein Originalname da, Dateinamen nehmen
							if(file_exists($parts[0])){
								if(file_exists(IMAGEPATH.$folder.'/'.$parts[1])){		# wenn schon eine Datei mit dem Originalnamen existiert, wird der Dateiname angehängt
									$file_parts = explode('.', $parts[1]);
									$parts[1] = $file_parts[0].'_'.basename($parts[0]);
								}
								copy($parts[0], IMAGEPATH.$folder.'/'.$parts[1]);
							}
							$zip = true;
						}
					}
				}
			}
			# bei Bedarf zippen
			if($zip){
				exec(ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*'); # Ordner zippen
				#echo ZIP_PATH.' '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*';
				$exportfile = IMAGEPATH.$folder.'.zip';
				$contenttype = 'application/octet-stream';
			}
			# temp. Tabelle wieder löschen
      $sql = 'DROP TABLE '.$temp_table;
      $ret = $layerdb->execSQL($sql,4, 0);
    	if($this->formvars['export_format'] != 'CSV')$user->rolle->setConsumeShape($currenttime,$this->formvars['selected_layer_id'],$count);
			
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
