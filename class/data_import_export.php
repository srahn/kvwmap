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

	function __construct() {
		global $debug;
		$this->debug=$debug;
		$this->delimiters = array("\t", ';', ' ', ','); # erlaubte Trennzeichen
	}

	################# Import #################
	function process_import_file($upload_id, $file_number, $filename, $stelle, $user, $pgdatabase, $epsg, $filetype = NULL, $formvars = NULL) {
		$this->pgdatabase = $pgdatabase;
		$this->epsg_codes = read_epsg_codes($pgdatabase);
		$file_name_parts[0] = substr($filename, 0, strrpos($filename, '.'));
		$file_name_parts[1] = substr($filename, strrpos($filename, '.')+1);
		if ($filetype == NULL) {
			$filetype = strtolower($file_name_parts[1]);
		}
		$this->unique_column = 'gid';
		if ($formvars['chosen_layer_id']) {		
			# Daten-Import in einen vorhandenen Layer
			$layerset = $user->rolle->getLayer($formvars['chosen_layer_id']);
			if ($user->layer_data_import_allowed AND $layerset[0]['privileg'] > 0) {
				$mapDB = new db_mapObj($stelle->id, $user->id);
				$database = $mapDB->getlayerdatabase($formvars['chosen_layer_id'], $stelle->pgdbhost);
				$schema = $layerset[0]['schema'];
				$table = $layerset[0]['maintable'];
				$adjustments = false;
			}
			else {
				echo 'Der Daten-Import in diesen Layer ist nicht erlaubt.';
				return;
			}
		}
		else {		
			# Daten-Import in einen neuen Rollenlayer
			$database = $pgdatabase;
			$schema = CUSTOM_SHAPE_SCHEMA;
			$table = 'a'.strtolower(umlaute_umwandeln(substr(basename($filename), 0, 15))). date("_Y_m_d_H_i_s", time());
			$adjustments = true;
		}
		switch ($filetype) {
			case 'shp' : case 'dbf' : case 'shx' : {
				$custom_tables = $this->import_custom_shape($file_name_parts, $user, $database, $schema, $table, $epsg, $adjustments);
				$epsg = $custom_tables[0]['epsg'];
			} break;
			case 'gpkg' : {
				$this->unique_column = 'ogc_fid';
				$custom_tables = $this->import_custom_file($filename, $user, $database, $schema, $table, $epsg, false, $adjustments);
			} break;			
			case 'kml' : case 'kmz' : {
				$epsg = 4326;
				$this->unique_column = 'ogc_fid';
				$custom_tables = $this->import_custom_file($filename, $user, $database, $schema, $table, $epsg, false, $adjustments);
			} break;
			case 'dxf' : {
				$this->unique_column = 'ogc_fid';
				$custom_tables = $this->import_custom_file($filename, $user, $database, $schema, $table, $epsg, true, $adjustments);
			} break;			
			case 'gpx' : {
				$epsg = 4326;
				$this->unique_column = 'ogc_fid';
				$custom_tables = $this->import_custom_gpx($filename, $pgdatabase, $epsg);
			} break;
			case 'ovl' : {
				$epsg = 4326;
				$custom_tables = $this->import_custom_ovl($filename, $pgdatabase, $epsg);
			} break;
			case 'uko' : {
				$custom_tables = $this->import_custom_uko($filename, $pgdatabase, $epsg);
			} break;
			case 'json' : case 'geojson' : {
				$this->unique_column = 'ogc_fid';
				$custom_tables = $this->import_custom_geojson($filename, $pgdatabase, $epsg);
			} break;
			case 'geotif' : case 'tiff' : case 'tif' : {
				$custom_tables = $this->import_custom_geotif($filename, $pgdatabase, $epsg);
			} break;
			case 'point' : {
				$custom_tables = $this->import_custom_pointlist($formvars, $pgdatabase);
			} break;
		}
		if ($custom_tables != NULL) {
			if ($custom_tables[0]['error'] != '') {
				if ($filetype != 'point') {
					echo $custom_tables[0]['error'];
				}
			}
			else {
				if ($formvars['chosen_layer_id'] == '') {
					foreach ($custom_tables as $custom_table){				# ------ Rollenlayer erzeugen ------- #
						$layer_id = -$this->create_rollenlayer(
							$pgdatabase,
							$stelle,
							$user,
							basename($filename) . " (" . date('d.m. H:i',time()) . ")" . str_repeat(' ', $custom_table['datatype']),
							$custom_table,
							$epsg ?: $custom_table['epsg'],
							$this->unique_column
						);
					}
				}
				else {
					$layer_id = $formvars['chosen_layer_id'];
				}
				return $layer_id;
			}
		}
		else {
			if ($this->ask_epsg) $this->create_epsg_form($upload_id, $file_number, basename($filename));
		}
	}

	function create_epsg_form($upload_id, $file_number, $filename){
		echo "
			<div id=\"serverResponse".$file_number."\">
				<table>
					<tr>
						<td>
							<span class=\"fett\">".$filename.": Bitte EPSG-Code angeben:</span><br>
							<select id=\"epsg".$filename."\" onchange=\"restartProcessing(".$upload_id.", ".$file_number.", '".$filename."')\">
								<option value=\"\">--Auswahl--</option>
								";
								foreach($this->epsg_codes as $epsg_code){
									echo '<option value="'.$epsg_code['srid'].'">';
									echo $epsg_code['srid'].': '.$epsg_code['srtext'];
									echo "</option>\n";
								}
					echo "
							</select>
						</td>
					</tr>
				</table>
			</div>
		";
	}

	function create_rollenlayer($pgdatabase, $stelle, $user, $layername, $custom_table, $epsg, $unique_column) {
		$dbmap = new db_mapObj($stelle->id, $user->id);
		$group = $dbmap->getGroupbyName('Eigene Importe');
		if ($group != '') {
			$groupid = $group['id'];
		}
		else {
			$groupid = $dbmap->newGroup('Eigene Importe', 1);
		}
		$user->rolle->set_one_Group($user->id, $stelle->id, $groupid, 1); # der Rolle die Gruppe zuordnen
		$this->formvars['user_id'] = $user->id;
		$this->formvars['stelle_id'] = $stelle->id;
		$this->formvars['aktivStatus'] = 1;
		$this->formvars['Name'] = $layername;
		$this->formvars['Gruppe'] = $groupid;
		$this->formvars['Typ'] = 'import';
		$this->formvars['Datentyp'] = $custom_table['datatype'];
		$this->formvars['epsg_code'] = $epsg;
		if ($custom_table['datatype'] == 3){ # Raster
			$this->formvars['Data'] = $custom_table['data'];
			$this->formvars['transparency'] = 100;
			$this->formvars['connectiontype'] = 0;
		}
		else {
			$this->formvars['Data'] = 'the_geom from (SELECT * FROM ' . CUSTOM_SHAPE_SCHEMA . '.' . $custom_table['tablename'] . ' WHERE 1=1 ' . $custom_table['where'] . ') as foo using unique ' . $unique_column . ' using srid=' . $epsg;
			$this->formvars['query'] = 'SELECT * FROM ' . $custom_table['tablename'] . ' WHERE 1=1' . $custom_table['where'];
			$this->formvars['connection_id'] = $pgdatabase->connection_id;
			$this->formvars['connectiontype'] = 6;
			if($custom_table['datatype'] == MS_LAYER_POLYGON)$this->formvars['transparency'] = $user->rolle->result_transparency;
			else $this->formvars['transparency'] = 100;
			if($custom_table['labelitem'] != '')$this->formvars['labelitem'] = $custom_table['labelitem'];
		}
		$layer_id = $dbmap->newRollenLayer($this->formvars);

		if ($custom_table['datatype'] != 3){	# kein Raster
			$layerdb = $dbmap->getlayerdatabase(-$layer_id, $this->Stelle->pgdbhost);
			$path = $this->formvars['query'];
			$attributes = $dbmap->load_attributes($layerdb, $path);
			$dbmap->save_postgis_attributes(-$layer_id, $attributes, '', '');
			$dbmap->addRollenLayerStyling($layer_id, $custom_table['datatype'], $custom_table['labelitem'], $user, 'import');
		}
    return $layer_id;
	}

	function get_shp_epsg($file, $pgdatabase){
		if(file_exists($file.'.prj')){
			$prj = file_get_contents($file.'.prj');
			return $this->get_epsg_from_wkt($prj, $pgdatabase);
		}
		else return false;
	}

	function get_gdal_epsg($raster_file, $pgdatabase){
		if(file_exists($raster_file)){
			$output = rand(0, 100000);
			$command = OGR_BINPATH.'gdalsrsinfo -o wkt '.$raster_file.' > '.IMAGEPATH.$output.'.info';
			exec($command);
			$wkt = file_get_contents(IMAGEPATH.$output.'.info');
			return $this->get_epsg_from_wkt($wkt, $pgdatabase);
		}
		else return false;
	}

	function get_epsg_from_wkt($wkt, $pgdatabase){
		global $supportedSRIDs;
		# 1. Versuch: Suche nach AUTHORITY			// erstmal rausgenommen, weil es auch mehrere AUTHORITY-Einträge geben kann
		// for($i = 0; $i < count($supportedSRIDs); $i++){
			// if(strpos($wkt, 'AUTHORITY["EPSG","'.$supportedSRIDs[$i].'"]') > 0)return $supportedSRIDs[$i];
		// }
		# 2. Versuch: Abgleich bestimmter Parameter im prj-String mit spatial_ref_sys_alias
		$datum = get_first_word_after($wkt, 'DATUM[', '"', '"');
		$projection = get_first_word_after($wkt, 'PROJECTION[', '"', '"');
		if($projection == '')$projection_sql = 'AND projection IS NULL'; else $projection_sql = "AND '".pg_escape_string($projection)."' = ANY(projection)";
		$false_easting = get_first_word_after($wkt, 'False_Easting"', ',', ']');
		if($false_easting == '')$false_easting_sql = 'AND false_easting IS NULL'; else $false_easting_sql = "AND false_easting = ".$false_easting;
		$central_meridian = get_first_word_after($wkt, 'Central_Meridian"', ',', ']');
		if($central_meridian == '')$central_meridian_sql = 'AND central_meridian IS NULL'; else $central_meridian_sql = "AND central_meridian = ".$central_meridian;
		$scale_factor = get_first_word_after($wkt, 'Scale_Factor"', ',', ']');
		if($scale_factor == '')$scale_factor_sql = 'AND scale_factor IS NULL'; else $scale_factor_sql = "AND scale_factor = ".$scale_factor;
		$unit = get_first_word_after($wkt, 'UNIT[', '"', '"', true);
		$sql = "SELECT srid FROM spatial_ref_sys_alias
						WHERE '".pg_escape_string($datum)."' = ANY(datum)
						".$projection_sql."
						".$false_easting_sql."
						".$central_meridian_sql."
						".$scale_factor_sql."
						AND '".pg_escape_string($unit)."' = ANY(unit)";
		$ret = $pgdatabase->execSQL($sql,4, 0);
		if(!$ret[0])$result = pg_fetch_row($ret[1]);
		return $result[0];
	}

	function load_shp_into_pgsql($pgdatabase, $uploadpath, $file, $epsg, $schemaname, $tablename, $encoding = 'LATIN1', $adjustments = true) {
		if (file_exists($uploadpath . $file . '.dbf')) {
			$filename = $uploadpath . $file . '.dbf';
		}
		elseif (file_exists($uploadpath . $file . '.DBF')) {
			$filename = $uploadpath . $file . '.DBF';
		}
		else {
			return;
		}
		$ret = $this->ogr2ogr_import($schemaname, $tablename, $epsg, $filename, $pgdatabase, NULL, $sql, '-lco FID=gid', $encoding, true);
		if (file_exists('.esri.gz')) {
			unlink('.esri.gz');
		}
		if ($ret !== 0) {
			$custom_table['error'] = $ret;
			return array($custom_table);
		}
		else {
			if ($adjustments) {
				$this->adjustGeometryType($pgdatabase, $schemaname, $tablename, $epsg);
				$sql = "
					SELECT convert_column_names('" . $schemaname . "', '" . $tablename . "');
					" . $this->rename_reserved_attribute_names($schemaname, $tablename);
			}
			$sql .= "
				SELECT geometrytype(the_geom) AS geometrytype FROM " . pg_quote($schemaname) . "." . pg_quote($tablename) . " LIMIT 1;
			";
			$ret = $pgdatabase->execSQL($sql,4, 0);
			if ($ret[0]) {
				$custom_table['error'] = $ret;
			}
			else {
				$rs = pg_fetch_assoc($ret[1]);
				$custom_table['datatype'] = geometrytype_to_datatype($rs['geometrytype']);
				$custom_table['tablename'] = $tablename;
			}
			return array($custom_table);
		}
	}

	function rename_reserved_attribute_names($schema, $table) {
		$reserved_words = array('desc', 'number', 'end', 'inner');
		foreach ($reserved_words as $word) {
			$sql .= "
				SELECT rename_if_exists('" . $schema . "', '" . $table . "', '" . $word . "');";
		}
		return $sql;
	}

	function import_custom_geotif($filename, $pgdatabase, $epsg){
		$custom_rasterfile = CUSTOM_RASTER.basename($filename);
		if(copy($filename, $custom_rasterfile)){
			if($epsg == NULL)$epsg = $this->get_gdal_epsg($custom_rasterfile, $pgdatabase);
			if($epsg == NULL){
				$this->ask_epsg = true;		# EPSG-Code konnte nicht ermittelt werden => nachfragen
				return;
			}
			$custom_table[0]['epsg'] = $epsg;
			$custom_table[0]['datatype'] = 3;
			$custom_table[0]['data'] = basename(CUSTOM_RASTER).'/'.basename($filename);
			return $custom_table;
		}
	}

	function import_custom_shape($filenameparts, $user, $database, $schema, $table, $epsg, $adjustments){
		if ($filenameparts[0] != '') {
			if (
				(
					file_exists($filenameparts[0] . '.shp') AND
					file_exists($filenameparts[0] . '.dbf') AND
					file_exists($filenameparts[0] . '.shx')
				) OR
				(
					file_exists($filenameparts[0] . '.SHP') AND
					file_exists($filenameparts[0] . '.DBF') AND
					file_exists($filenameparts[0] . '.SHX')
				)
			) {
				$formvars['shapefile'] = $filenameparts[0];
				if ($epsg == NULL) {
					$epsg = $this->get_shp_epsg($filenameparts[0], $this->pgdatabase);		# EPSG-Code aus prj-Datei ermitteln
				}
				if ($epsg == NULL){
					$this->ask_epsg = true;		# EPSG-Code konnte nicht aus prj-Datei ermittelt werden => nachfragen
					return;
				}
			}
			else {
				return;
			}
			$encoding = $this->getEncoding($filenameparts[0] . '.dbf');
			
			$custom_table = $this->load_shp_into_pgsql($database, '', $formvars['shapefile'], $epsg, $schema, $table, $encoding, $adjustments);
			if ($custom_table != NULL) {
				exec('rm ' . UPLOADPATH . $user->id . '/' . basename($formvars['shapefile']) . '.*');	# aus Sicherheitsgründen rm mit Uploadpfad davor
			}
			$custom_table[0]['epsg'] = $epsg;
			return $custom_table;
		}
	}
	
	function import_custom_file($filename, $user, $database, $schema, $table, $epsg, $ask_epsg, $adjustments){
		if(file_exists($filename)){
			if($epsg == NULL AND $ask_epsg){
				$this->ask_epsg = true;		# EPSG-Code nachfragen
				return;
			}
			$ret = $this->ogr2ogr_import($schema, $table, $epsg, $filename, $database, NULL, NULL, NULL, 'UTF-8');
			if ($ret !== 0) {
				$custom_table['error'] = $ret;
				return array($custom_table);
			}
			else {
				if ($adjustments) {
					$sql = $this->rename_reserved_attribute_names($schema, $table);
				}				
				$sql = "
					SELECT
						replace(geometrytype(the_geom), 'MULTI', '') as geometrytype,
						max(st_srid(the_geom)) as epsg,
						count(*)
					FROM
						" . $schema . "." . $table . "
					GROUP BY replace(geometrytype(the_geom), 'MULTI', '')
				";
				$ret = $database->execSQL($sql,4, 0);
				if (!$ret[0]) {
					$geom_types = array('POINT' => 0, 'LINESTRING' => 1, 'POLYGON' => 2);
					while ($result = pg_fetch_assoc($ret[1])){
						if ($result['count'] > 0 AND $geom_types[$result['geometrytype']] !== NULL) {
							$custom_table['datatype'] = $geom_types[$result['geometrytype']];
							$custom_table['tablename'] = $table;
							$custom_table['where'] = " AND replace(geometrytype(the_geom), 'MULTI', '') = '".$result['geometrytype']."'";
							$custom_table['epsg'] = $result['epsg'];
							$custom_tables[] = $custom_table;
						}
					}
					return $custom_tables;
				}
			}
		}
	}	

	function import_custom_gpx($filename, $pgdatabase, $epsg){
		if(file_exists($filename)){
			# tracks
			$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($filename), 0, 15))). date("_Y_m_d_H_i_s", time());
			$ret = $this->ogr2ogr_import(CUSTOM_SHAPE_SCHEMA, $tablename, $epsg, $filename, $pgdatabase, 'tracks', NULL, NULL, 'UTF8');
			if ($ret !== 0) {
				$custom_table['error'] = $ret;
				return array($custom_table);
			}
			else{
				$sql = "
					UPDATE 
						" . CUSTOM_SHAPE_SCHEMA . "." . $tablename . "
					SET 
						the_geom  = ST_CollectionExtract(st_makevalid(the_geom), 2);
						
					UPDATE 
						" . CUSTOM_SHAPE_SCHEMA . "." . $tablename . "
					SET 
						the_geom = st_multi(geom)
					FROM (
						SELECT 
							line_id, ST_MakeLine(geom) as geom 
						FROM (
							SELECT ogc_fid as line_id, (ST_DumpPoints(the_geom)).geom
							FROM " . CUSTOM_SHAPE_SCHEMA . "." . $tablename . ") my_points
							WHERE NOT ST_Equals(geom, ST_GeomFromText('POINT(0 0)', 4326))
						GROUP BY
							line_id
					) t
					WHERE ogc_fid = t.line_id;					
					SELECT convert_column_names('" . CUSTOM_SHAPE_SCHEMA . "', '" . $tablename . "');
					" . $this->rename_reserved_attribute_names(CUSTOM_SHAPE_SCHEMA, $tablename);
					
				$ret = $pgdatabase->execSQL($sql,4, 0);
				$custom_table['datatype'] = 1;
				$custom_table['tablename'] = $tablename;
				$custom_tables[] = $custom_table;
				# waypoints
				$tablename = 'a'.strtolower(umlaute_umwandeln(basename($filename))). date("_Y_m_d_H_i_s", time());
				$this->ogr2ogr_import(CUSTOM_SHAPE_SCHEMA, $tablename, $epsg, $filename, $pgdatabase, 'waypoints', NULL, NULL, 'UTF8');
				$sql = $this->rename_reserved_attribute_names(CUSTOM_SHAPE_SCHEMA, $tablename);
				$ret = $pgdatabase->execSQL($sql,4, 0);
				$custom_table['datatype'] = 0;
				$custom_table['tablename'] = $tablename;
				$custom_tables[] = $custom_table;
				if(!$ret[0]){
					return $custom_tables;
				}
			}
		}
	}

	function import_custom_ovl($filename, $pgdatabase, $epsg){
		if(file_exists($filename)){
			$rows = file($filename);
			$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($filename), 0, 15))). date("_Y_m_d_H_i_s", time());
			$i = -1;
			foreach($rows as $row){
				$kvp = explode('=', $row);
				if($kvp[0] == 'Typ'){		# ein neues Polygon oder Linie
					$i++;
					$geom_start = false;
					$objects[$i]['Typ'] = $kvp[1];
				}
				if($kvp[0] == 'Text'){
					$objects[$i]['Text'] = $kvp[1];
				}
				if($kvp[0] == 'XKoord' OR $kvp[0] == 'XKoord0'){
					$objects[$i]['geom'] = $kvp[1];
					$geom_start = true;
					$komma = false;
				}
				elseif($geom_start AND (substr($row, 0, 6) == 'YKoord' OR substr($row, 0, 6) == 'XKoord')){
					if($komma){
						$objects[$i]['geom'].= ',';
						$komma = false;
					}
					else{
						$objects[$i]['geom'].= ' ';
						$komma = true;
					}
					$objects[$i]['geom'].= $kvp[1];
					if($objects[$i]['startpoint'] == '')$objects[$i]['startpoint'] = $objects[$i]['geom'];
				}
			}
			for ($i = 0; $i < count($objects); $i++){
				switch($objects[0]['Typ']){			# alle Objekte müssen vom gleichen Typ sein
					case 2 : case 6:	{
						$geomtype = 'POINT';
						$objects[$i]['geom'] = 'POINT('.$objects[$i]['geom'].')';
						$custom_table['datatype'] = 0;
					} break;

					case 3 :	{
						$geomtype = 'LINESTRING';
						$objects[$i]['geom'] = 'LINESTRING('.$objects[$i]['geom'].')';
						$custom_table['datatype'] = 1;
					} break;
					case 4 :	{
						$geomtype = 'POLYGON';
						$objects[$i]['geom'] .= ', '.$objects[$i]['startpoint'];			// Polygonring schliessen
						$objects[$i]['geom'] = 'POLYGON(('.$objects[$i]['geom'].'))';
						$custom_table['datatype'] = 2;
					} break;
				}
				$inserts[] = "(
					'" . $objects[$i]['Text'] . "',
					st_geomfromtext('" . $objects[$i]['geom'] . "', " . $epsg . ")
				)";
			}
			$sql = "
				CREATE TABLE " . CUSTOM_SHAPE_SCHEMA . "." . $tablename . " (
					gid serial NOT NULL,
					label varchar
				);
				SELECT AddGeometryColumn(
					'" . CUSTOM_SHAPE_SCHEMA . "',
					'" . $tablename . "',
					'the_geom',
					" . $epsg . ",
					'" . $geomtype . "',
					2
				);
				INSERT INTO " . CUSTOM_SHAPE_SCHEMA . "." . $tablename . " (label, the_geom)
				VALUES " . implode(', ', $inserts) . ";
			";
			#echo $sql;
			$ret = $pgdatabase->execSQL($sql,4, 0);
			if (!$ret[0]) {
				$custom_table['tablename'] = $tablename;
				$custom_table['labelitem'] = 'label';
				return array($custom_table);
			}
		}
	}

	function import_custom_geojson($filename, $pgdatabase){
		return $this->geojson_import($filename, $pgdatabase, CUSTOM_SHAPE_SCHEMA, NULL);
	}

	function import_geojson($pgdatabase, $schema, $tablename){		# Admin-Import
		$_files = $_FILES;
		if ($_files['file1']['name']) {
			$filename = UPLOADPATH.$_files['file1']['name'];
			if (move_uploaded_file($_files['file1']['tmp_name'],$filename)) {
				return $this->geojson_import($filename, $pgdatabase, $schema, $tablename);
			}
		}
	}

	function geojson_import($filename, $pgdatabase, $schema, $tablename){
		if (file_exists($filename)) {
			$json = json_decode(file_get_contents($filename));
			if(strpos($json->crs->properties->name, 'EPSG:') !== false)$epsg = trim(array_pop(explode('EPSG:', $json->crs->properties->name)), ':');
			else $epsg = 4326;
			if($tablename == NULL)$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($filename), 0, 15))). date("_Y_m_d_H_i_s", time());
			$ret = $this->ogr2ogr_import($schema, $tablename, $epsg, $filename, $pgdatabase, NULL, NULL, NULL, 'UTF8');
			if ($ret !== 0) {
				$custom_table['error'] = $ret;
				return array($custom_table);
			}
			else {
				$sql = "
					SELECT convert_column_names('" . $schema . "', '" . $tablename . "');
					SELECT geometrytype(the_geom) AS geometrytype FROM " . $schema . "." . $tablename . " LIMIT 1
				";
				$ret = $pgdatabase->execSQL($sql,4, 0);
				if (!$ret[0]) {
					$rs = pg_fetch_assoc($ret[1]);
					$datatype = geometrytype_to_datatype($rs['geometrytype']);
				}
				$custom_tables[0]['datatype'] = $datatype;
				$custom_tables[0]['tablename'] = $tablename;
				$custom_tables[0]['epsg'] = $epsg;
				if (!$ret[0]) {
					return $custom_tables;
				}
			}
		}
	}

	function load_custom_pointlist($user){
		$_files = $_FILES;
		if($_files['file1']['name']){
			$user_upload_folder = UPLOADPATH.$user->id.'/';
			@mkdir($user_upload_folder);
			$this->pointfile = $user_upload_folder.$_files['file1']['name'];
			if(move_uploaded_file($_files['file1']['tmp_name'], $this->pointfile)){
				$rows = file($this->pointfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				$delimiters = implode($this->delimiters);
				while(@count($this->delimiters) > 0 AND @count($this->columns) < 2){
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
		$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($formvars['file1']), 0, 15))). date("_Y_m_d_H_i_s", time());
		$i = 0;
		while(trim($rows[$i], $formvars['delimiter']."\n\r") == ''){	// Leerzeilen überspringen bis zur ersten Zeile mit Inhalt
			$i++;
		}
		$columns = explode($formvars['delimiter'], $rows[$i]);
		for($i = 0; $i < count($columns); $i++){
			if($formvars['column'.$i] == 'x' AND !is_numeric(str_replace(',', '.', $columns[$i])))$headlines = true;		// die erste Zeile enthält die Spaltenüberschriften
		}

		for ($i = 0; $i < count($columns); $i++) {
			$j = $i+1;
			if ($headlines) {
				$table_column = strtolower(umlaute_umwandeln(utf8_encode($columns[$i])));
			}
			else{
				$table_column = 'spalte' . $j;
			}

			if ($formvars['column' . $i] == 'label') {
				$labelitem = $table_column;
			}
			if (in_array($formvars['column' . $i], ['x', 'y'])) {
				$index[$formvars['column' . $i]] = $i;
			}
			$table_columns[] = $table_column;
		}
		$sql = '
			CREATE TABLE ' . CUSTOM_SHAPE_SCHEMA . '.' . $tablename  .' (
				gid serial,
				"' . implode('" varchar, "', $table_columns) . "\" varchar
			);
			SELECT AddGeometryColumn('" . CUSTOM_SHAPE_SCHEMA . "', '" . $tablename . "', 'the_geom', " . $formvars['epsg'] . ", 'POINT', 2);
			CREATE INDEX " . $tablename . "_gist_idx ON " . CUSTOM_SHAPE_SCHEMA . "." . $tablename . " USING gist (the_geom );";
		$i = 0;
		foreach ($rows as $row) {
			if ($headlines AND $i == 0 OR trim($row, $formvars['delimiter']."\n\r") == '') {
				// Überschriftenzeile und Leerzeilen auslassen
				$i++;continue;
			}
			$values = explode($formvars['delimiter'], $row);
			$x = str_replace(',', '.', $values[$index['x']]);
			$y = str_replace(',', '.', $values[$index['y']]);
			array_walk($values, function(&$value, $key){$value = "E'" . pg_escape_string(utf8_encode($value)) . "'";});
			$sql.= '
				INSERT INTO ' . CUSTOM_SHAPE_SCHEMA . '.' . $tablename .
				'("' . implode('", "', $table_columns) . '", the_geom)
				VALUES(
				' . implode(', ', $values) . ',
				' . ((!is_numeric($x) OR !is_numeric($y))? "NULL);" : "st_geomfromtext('POINT(" . $x . " " . $y . ")', " . $formvars['epsg'] . "));");
			$i++;
		}
		#echo $sql;
		$ret = $pgdatabase->execSQL($sql,4, 0);
		if(!$ret[0]){
			$custom_table['datatype'] = 0;
			$custom_table['tablename'] = $tablename;
			$custom_table['labelitem'] = $labelitem;
		}
		else {
			$pgdatabase->gui->add_message('error', 'Import fehlgeschlagen: Bitte prüfen Sie die Formatierung der Punktliste.');
			$custom_table['error'] = $ret['msg'];
		}
		return array($custom_table);
	}

	function getGeometryType($database, $schema, $table){
		$sql = "
			select 
				type 
			from 
				geometry_columns 
			WHERE 
				f_table_schema = '" . $schema . "' AND 
				f_table_name = '" . $table . "';
		";
		$ret = $database->execSQL($sql,4, 0);
		if (!$ret[0]) {
			$rs = pg_fetch_assoc($ret[1]);
			return $rs['type'];
		}
	}

	function shp_import_speichern($formvars, $database, $upload_path = UPLOADPATH, $encoding = '') {
		global $GUI;
		$this->formvars = $formvars;
		if (file_exists($upload_path . $this->formvars['dbffile'])) {
			$importfile = basename($this->formvars['dbffile'], '.dbf');
			include_(CLASSPATH.'dbf.php');
			$this->dbf = new dbf();
			$this->dbf->header = $this->dbf->get_dbf_header($upload_path.$this->formvars['dbffile']);
			$this->dbf->header = $this->dbf->get_sql_types($this->dbf->header);
			if ($this->formvars['import_all_columns']) {
				$sql = '';
			}
			else {
				for($i = 0; $i < count($this->dbf->header); $i++){
					if($this->formvars['check_'.$this->dbf->header[$i][0]]){
						if($this->formvars['primary_key'] != $this->formvars['sql_name_'.$this->dbf->header[$i][0]]){
							if($i > 0)$sql .= ', ';
							$columns[] = '"' . $this->formvars['dbf_name_'.$this->dbf->header[$i][0]].'" as "'.strtolower($this->formvars['sql_name_'.$this->dbf->header[$i][0]]) . '"';
						}
					}
				}
				$sql = 'SELECT ' . implode(', ', $columns) . ' FROM "'.$importfile.'"';
			}
			$options = $this->formvars['table_option'];
			$options.= ' -lco FID=gid';
			if ($encoding == '') {
				$encoding = $this->getEncoding($upload_path.$this->formvars['dbffile']);
			}
			$geom_type = $this->getGeometryType($database, $this->formvars['schema_name'], $this->formvars['table_name']);
			$multi = true;
			if (in_array($geom_type, ['POINT', 'LINESTRING', 'POLYGON'])) {
				$multi = false;
			}
			$ret = $this->ogr2ogr_import($this->formvars['schema_name'], $this->formvars['table_name'], $this->formvars['epsg'], $upload_path.$importfile.'.shp', $database, NULL, $sql, $options, $encoding, $multi);

      // # erzeugte SQL-Datei anpassen
      // if($this->formvars['table_option'] == '-u') {
        // $oldsqld = $upload_path.$this->formvars['table_name'].'.sql';
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

			if ($ret == '') {
				$table = $this->formvars['schema_name'] . "." . $this->formvars['table_name'];
				$sql = "
					SELECT
						count(*),
						max(geometrytype(the_geom)) AS geometrytype
					FROM
						" . $table . ";
				";
				#echo '<br>Sql: ' . $sql; exit;
				$ret = $database->execSQL($sql,4, 0);
				if (!$ret[0]) {
					$rs = pg_fetch_assoc($ret[1]);
					$alert = 'Import erfolgreich.';
					if($this->formvars['table_option'] == ''){
						$alert.= ' Die Tabelle '.$this->formvars['schema_name'].'.'.$this->formvars['table_name'].' wurde erzeugt.';
					}
					$alert .= ' Die Tabelle enthält jetzt ' . $rs['count'] . ' Datensätze.';
					$result = array(
						'success' => true,
						'datatype' => geometrytype_to_datatype($rs['geometrytype'])
					);
					showAlert($alert);
				}
			}
      else {
				$result = array(
					'success' => false,
					'err_msg' => $ret[1]
				);
				$GUI->add_message('error', $ret);
			}
		}
		else {
			$result = array(
				'success' => false,
				'datatype' => 'Fehler beim hochladen oder weiterverarbeiten. DBF-Datei ' . $upload_path . $this->formvars['dbffile'] . ' auf Server erwartet, aber nicht  gefunden.'
			);
		}
		return $result;
	}

	function shp_import($formvars, $pgdatabase){
		include_(CLASSPATH.'dbf.php');
		$_files = $_FILES;
		$this->formvars = $formvars;
		if ($_files['zipfile']['name']) {
			# eine Zipdatei wurde ausgewählt
			$this->formvars['zipfile'] = $_files['zipfile']['name'];
			$nachDatei = UPLOADPATH.$_files['zipfile']['name'];
			if (move_uploaded_file($_files['zipfile']['tmp_name'],$nachDatei)) {
				$files = unzip($nachDatei, false, false, true);
				$firstfile = explode('.', $files[0]);
				$this->formvars['epsg'] = $this->get_shp_epsg(UPLOADPATH.$firstfile[0], $pgdatabase);
				$file = $firstfile[0].'.dbf';
				if (!file_exists(UPLOADPATH.$file)){
					$file = $firstfile[0].'.DBF';
				}
				$this->dbf = new dbf();
				$this->dbf->file = '';
				$this->dbf->file = $file;
				if ($this->dbf->file != ''){
					if (file_exists(UPLOADPATH.$this->dbf->file)) {
						$this->dbf->header = $this->dbf->get_dbf_header(UPLOADPATH.$this->dbf->file);
						$this->dbf->header = $this->dbf->get_sql_types($this->dbf->header);
					}
				}
			}
		}
	}

	function import_custom_uko($filename, $pgdatabase, $epsg){
		if(file_exists($filename)){
			if($epsg == NULL){
				$this->ask_epsg = true;		# EPSG-Code nachfragen
				return;
			}
			$tablename = 'a'.strtolower(umlaute_umwandeln(substr(basename($filename), 0, 15))). date("_Y_m_d_H_i_s", time());
			$wkt = file_get_contents($filename);
			$wkt = substr($wkt, strpos($wkt, 'KOO ')+4);
			$wkt = str_replace(chr(13), '', $wkt);
			$wkt = 'MULTIPOLYGON((('.$wkt;
			$wkt = str_replace(chr(13).'FL+'.chr(13).'KOO ', ')),((', $wkt);
			$wkt = str_replace(chr(10).'FL+'.chr(10).'KOO ', ')),((', $wkt);
			$wkt = str_replace(chr(10).'FL-'.chr(10).'KOO ', '),(', $wkt);
			$wkt = str_replace(chr(10).'KOO ', ',', $wkt);
			$wkt.= ')))';
			$sql = "
				CREATE TABLE " . CUSTOM_SHAPE_SCHEMA . "." . $tablename . " (
					gid serial NOT NULL,
					id serial
				);
				SELECT AddGeometryColumn(
					'" . CUSTOM_SHAPE_SCHEMA . "',
					'" . $tablename . "',
					'the_geom',
					" . $epsg . ",
					'MULTIPOLYGON',
					2
				);
				INSERT INTO " . CUSTOM_SHAPE_SCHEMA . "." . $tablename . " (the_geom)
				VALUES (st_geomfromtext('" . $wkt . "', " . $epsg . "))
			";
			$ret = $pgdatabase->execSQL($sql,4, 1);
			if (!$ret[0]){
				$custom_table['tablename'] = $tablename;
				$custom_table['epsg'] = $epsg;
				$custom_table['datatype'] = 2;
				return array($custom_table);
			}
		}
	}

################### Export ########################
	function export($formvars, $stelle, $user, $mapdb) {
		#echo '<br>export formvars: ' . print_r($formvars, true);
		$this->formvars = $formvars;
		$this->layerdaten = $stelle->getqueryableVectorLayers(NULL, $user->id, NULL, NULL, NULL, NULL, false, true);
		if ($this->formvars['selected_layer_id']) {
			$this->layerset = $user->rolle->getLayer($this->formvars['selected_layer_id']);
			$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $stelle->pgdbhost);
			$path = $this->layerset[0]['pfad'];
			$privileges = $stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
			if ($this->layerset['connectiontype'] == 6) {
				$newpath = $stelle->parse_path($layerdb, $path, $privileges);
			}
			$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames']);
		}
	}

	function ogr2ogr_export($sql, $exportformat, $exportfile, $layerdb) {
		$formvars_nln = ($this->formvars['layer_name'] != '' ? '-nln ' . $this->formvars['layer_name'] : '');
		$formvars_nlt = ($this->formvars['geomtype'] != '' ? '-nlt ' . $this->formvars['geomtype'] : '');
		$command = 'export PGDATESTYLE="ISO, MDY";'
			. 'export '
			. 'PGCLIENTENCODING=UTF-8;'
			. OGR_BINPATH . 'ogr2ogr '
			. '-f ' . $exportformat . ' '
			. '-lco ENCODING=UTF-8 '
			. '--config DXF_WRITE_HATCH NO '
			. '-sql "' . str_replace(["\t", chr(10), chr(13)], [' ', ''], $sql) . '" '
			. $formvars_nln . ' '
			. $formvars_nlt . ' '
			. $exportfile . ' '
			. 'PG:"' . $layerdb->get_connection_string(true) . ' active_schema=' . $layerdb->schema . '"';
		$errorfile = rand(0, 1000000);
		$command .= ' 2> ' . IMAGEPATH . $errorfile . '.err';
		$output = array();
		#echo '<br>' . $command;
		exec($command, $output, $ret);
		if ($ret != 0) {
			exec("sed -i -e 's/".$database->passwd."/xxxx/g' " . IMAGEPATH . $errorfile . '.err');		# falls das DB-Passwort in der Fehlermeldung vorkommt => ersetzen
			$ret = 'Fehler beim Exportieren !<br><a href="' . IMAGEURL . $errorfile . '.err" target="_blank">Fehlerprotokoll</a>';
		}
		return $ret;
	}

	function ogr2ogr_import($schema, $tablename, $epsg, $importfile, $database, $layer, $sql = NULL, $options = NULL, $encoding = 'LATIN1', $multi = false) {
		$command = '';
		if ($options != NULL) {
			$command.= $options;
		}
		$command .= ' -f PostgreSQL -lco GEOMETRY_NAME=the_geom -lco launder=NO -lco FID=' . $this->unique_column . ' -lco precision=NO ' . ($multi? '-nlt PROMOTE_TO_MULTI' : '') . ' -nln ' . $tablename . ($epsg ? ' -a_srs EPSG:' . $epsg : '');
		if ($sql != NULL) {
			$command .= ' -sql \'' . $sql . '\'';
		}
		$command .= ' PG:"' . $database->get_connection_string(true) . ' active_schema=' . $schema . '"';
		$command .= ' "' . $importfile . '" ' . $layer;
		if (OGR_BINPATH == '') {
			$gdal_container_connect = 'gdalcmdserver:8080/t/?tool=ogr2ogr&param=';
			$url = $gdal_container_connect . urlencode(trim($command));
			#echo 'url:   ' . $url . '<br><br>';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,300);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
			curl_close($ch);
			$result = json_decode($output);
			$ret = $result->exitCode;
			if ($ret != 0 OR $result->stderr != '') {
				$ret = 'Fehler beim Importieren der Datei ' . basename($importfile) . '!<br>' . $result->stderr;
			}
		}
		else {
			$command = 'export PGCLIENTENCODING=' . $encoding . ';' . OGR_BINPATH . 'ogr2ogr ' . $command;
			$command .= ' 2> ' . IMAGEPATH . $tablename . '.err';
			$output = array();
			#echo '<p>command: ' . $command;
			exec($command, $output, $ret);
			$err_file = file_get_contents(IMAGEPATH . $tablename . '.err');
			if ($ret != 0 OR strpos($err_file, 'statement failed') !== false) {
				# versuche noch mal mit UTF-8
				$command = str_replace('PGCLIENTENCODING='.$encoding, 'PGCLIENTENCODING=UTF-8', $command);
				#echo '<p>command mit UTF-8: ' . $command;
				exec($command, $output, $ret);
				if ($ret != 0) {
					# falls das DB-Passwort in der Fehlermeldung vorkommt => ersetzen
					exec("sed -i -e 's/" . $database->passwd."/xxxx/g' " . IMAGEPATH . $tablename . '.err');
					$ret = 'Fehler beim Importieren der Datei ' . basename($importfile) . '!<br><a href="' . IMAGEURL . $tablename . '.err" target="_blank">Fehlerprotokoll</a>'; 
				}
			}
		}
		return $ret;
	}
		
	function adjustGeometryType($database, $schema, $table, $epsg){
		$sql = "
			SELECT count(*) FROM " . $schema . "." . $table . " WHERE ST_NumGeometries(the_geom) > 1
		";
		$ret = $database->execSQL($sql,4, 0);
		if (!$ret[0]) {
			$rs = pg_fetch_row($ret[1]);
			if ($rs[0] == 0) {
				$sql = "
					SELECT replace(ST_GeometryType(the_geom), 'ST_Multi', '') FROM " . $schema . "." . $table . " LIMIT 1 
				";
				$ret = $database->execSQL($sql,4, 0);
				if (!$ret[0]) {
					$rs = pg_fetch_row($ret[1]);
					$sql = "
						ALTER TABLE " . $schema . "." . $table . " ALTER the_geom TYPE geometry(" . $rs[0] . ", " . $epsg . ") USING ST_GeometryN(the_geom, 1)
					";
					$ret = $database->execSQL($sql,4, 0);
				}
			}
		}
	}	

	function getEncoding($dbf) {
		$folder = dirname($dbf);
		$command = OGR_BINPATH . 'ogr2ogr -f CSV "' . $folder . '/test.csv" "' . $dbf . '"';
		#echo '<br>Command ogr2ogr: ' . $command;
		exec($command, $output, $ret);
		$command = 'file ' . $folder . '/test.csv';
		#echo '<br>Command file: ' . $command;
		exec($command, $output, $ret);
		if (file_exists($folder . '/test.csv')) {
			unlink($folder . '/test.csv');
		}
		#echo '<br>output: ' . $output[0];
		if (strpos($output[0], 'UTF') !== false) 			$encoding = 'UTF-8';
		if (strpos($output[0], 'ISO-8859') !== false) $encoding = 'LATIN1';
		if (strpos($output[0], 'ASCII') !== false) 		$encoding = 'LATIN1';
		#echo '<br>encoding: ' . $encoding;
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
							for($o = 0; $o < @count($enum_value); $o++){
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
						if(substr($attributes['type'][$j], 0, 1) == '_'){		# Arrays ohne geschweifte Klammern und mit Zeilenumbrüchen
							$value = trim($value, '{}');
							$value = str_replace(',', chr(13), $value);
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
						if(is_numeric($value)){
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

	function create_uko($layerdb, $sql, $column, $epsg, $exportfile){
		$sql = "SELECT st_astext(st_multi(st_union(st_transform(".$column.", ".$epsg.")))) as geom FROM (".$sql.") as foo";
		#echo $sql;
		$ret = $layerdb->execSQL($sql,4, 1);
		if(!$ret[0]){
			$rs=pg_fetch_array($ret[1]);
			$uko = WKT2UKO($rs['geom']);
			$fp = fopen($exportfile, 'w');
			fwrite($fp, $uko);
			fclose($fp);
		}
  }

	function create_ovl($datentyp, $layerdb, $query_sql, $column, $epsg){
		$ovl_type = array(MS_LAYER_POINT => 6, MS_LAYER_LINE => 3, MS_LAYER_POLYGON => 4);
		$sql = "SELECT st_astext(";
		if($datentyp == MS_LAYER_POLYGON)$sql.= "ST_MakePolygon(st_exteriorring(geom))) as geom ";
		else $sql.= "geom) as geom ";
		$sql.= "FROM (select (st_dump(st_union(st_transform(".$column.", ".$epsg.")))).geom as geom FROM (".$query_sql.") as foo) as foo";
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
		global $language;
		global $GUI;
		global $kvwmap_plugins;
		$currenttime = date('Y-m-d H:i:s',time());
		$this->formvars = $formvars;
		$export_rollen_layer = ((int)$this->formvars['selected_layer_id'] < 0);
		$layerset = ($export_rollen_layer ? $user->rolle->getRollenLayer((int) $this->formvars['selected_layer_id'] * -1) : $user->rolle->getLayer($this->formvars['selected_layer_id']));
		$mapdb = new db_mapObj($stelle->id,$user->id);
		$layerdb = $mapdb->getlayerdatabase($this->formvars['selected_layer_id'], $stelle->pgdbhost);
		$sql = replace_params(
			$layerset[0]['pfad'],
			rolle::$layer_params,
			$user->id,
			$stelle->id,
			rolle::$hist_timestamp,
			$user->rolle->language
		);
		$privileges = $stelle->get_attributes_privileges($this->formvars['selected_layer_id']);
		$this->attributes = $mapdb->read_layer_attributes($this->formvars['selected_layer_id'], $layerdb, $privileges['attributenames'], false, true);
		if ($export_rollen_layer) {
			include_once(CLASSPATH . 'LayerAttribute.php');
			$attribute = new LayerAttribute($GUI);
			$layerset[0]['oid'] = $attribute->get_oid($this->attributes);
			$layerset[0]['maintable'] = $this->attributes['table_name'][$layerset[0]['oid']];
		}
		if ($layerset[0]['connectiontype'] == 9) {
			$folder = 'Export_' . $this->formvars['layer_name'] . rand(0,10000);
			mkdir(IMAGEPATH . $folder, 0777);
			$exportfile = IMAGEPATH . $folder . '/' . str_replace(' ', '_', $layerset[0]['Name']) . '.json';
			if ($this->formvars['epsg'] != '') {
				$t_epsg = $this->formvars['epsg'];
			}
			elseif ($layerset[0]['epsg'] != '') {
				$t_epsg = $layerset[0]['epsg'];
			}
			else {
				$t_epsg = '4326';
			}
			$contenttype = 'application/vnd.geo+json';
			$command = 'ogr2ogr -f GeoJSON ' . $exportfile . ' -t_srs "epsg:' . $t_epsg . '" "WFS:' . $layerset[0]['connection'] . 'Service=WFS&Request=GetFeature&Version=2.0.0&TypeName=' . $layerset[0]['wms_name'] . '"';
			$errorfile = rand(0, 1000000);
			$command .= ' 2> ' . IMAGEPATH . $errorfile . '.err';
			$output = array();
			#echo '<br>' . $command; exit;
			exec($command, $output, $ret);
			if ($ret != 0) {
				$ret = 'Fehler beim Exportieren !<br><br>Befehl:<div class="code">'.$command.'</div><a href="' . IMAGEURL . $errorfile . '.err" target="_blank">Fehlerprotokoll</a>';
			}
		}
		else {
			#echo '<br>connectiontype: ' . $layerset[0]['connectiontype'];
			#echo '<br>name: ' . $layerset[0]['Name']; exit;
			$filter = $mapdb->getFilter($this->formvars['selected_layer_id'], $stelle->id);

			# Where-Klausel aus Sachdatenabfrage-SQL
			$where = substr(
				$this->formvars['sql_' . $this->formvars['selected_layer_id']],
				strrpos(strtolower($this->formvars['sql_' . $this->formvars['selected_layer_id']]), 'where')
			);

			# order by rausnehmen
			$orderbyposition = strrpos(strtolower($sql), 'order by');
			$lastfromposition = strrpos(strtolower($sql), 'from');
			if ($orderbyposition !== false AND $orderbyposition > $lastfromposition) {
				$orderby = ' '.substr($sql, $orderbyposition);
				$sql = substr($sql, 0, $orderbyposition);
			}
			# group by rausnehmen
			$groupbyposition = strpos(strtolower($sql), 'group by');
			if ($groupbyposition !== false) {
				$groupby = ' '.substr($sql, $groupbyposition);
				$sql = substr($sql, 0, $groupbyposition);
	  	}

			# Zusammensammeln der Attribute, die abgefragt werden müssen
			for ($i = 0; $i < count($this->attributes['name']); $i++) {
				if ($this->formvars['check_'.$this->attributes['name'][$i]]  or $this->formvars['all'] == 1) {		# Entweder das Attribut wurde angehakt
					$selection[$this->attributes['name'][$i]] = 1;
					$selected_attributes[] = $this->attributes['name'][$i];						# Zusammensammeln der angehakten Attribute, denn nur die sollen weiter unten auch exportiert werden
					$selected_attr_types[] = $this->attributes['type'][$i];
					$selected_attr_length[] = $this->attributes['length'][$i];
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

			$sql = $stelle->parse_path($layerdb, $sql, $selection, $this->attributes);		# parse_path wird hier benutzt um die Auswahl der Attribute auf das Pfad-SQL zu übertragen

			# oid auch abfragen
			$distinctpos = strpos(strtolower($sql), 'distinct');
			if ($distinctpos !== false && $distinctpos < 10) {
				$pfad = substr(trim($sql), $distinctpos + 8);
				$distinct = true;
			}
			else {
				$pfad = substr(trim($sql), 7);
			}
			$pfad = pg_quote($this->attributes['table_alias_name'][$layerset[0]['maintable']]) . '.' . $layerset[0]['oid'] . ' AS ' . pg_quote($layerset[0]['maintable'] . '_oid') . ', ' . $pfad;
			if ($groupby != '') {
				$groupby .= ',' . pg_quote($this->attributes['table_alias_name'][$layerset[0]['maintable']]).'.'.$layerset[0]['oid'];
			}
			if ($distinct == true) {
				$pfad = "
					DISTINCT "
					. $pfad . "
				";
			}
			$sql = "
				SELECT "
					. $pfad . "
				";

			# Bedingungen
			if ($where != '') {
				# Where-Klausel aus Sachdatenabfrage-SQL (abgefragter Extent, Suchparameter oder oids)
				$orderbyposition = strpos(strtolower($where), 'order by');
				if ($orderbyposition) {
					$where = substr($where, 0, $orderbyposition);
				}
			}
			elseif ($filter != '') {		# Filter muss nur dazu, wenn kein $where vorhanden, also keine Abfrage gemacht wurde, sondern der gesamte Layer exportiert werden soll (Filter ist ja schon im $where enthalten)
				$filter = str_replace('$userid', $user->id, $filter);
	    	$where = 'WHERE ' . $filter;
			}
			else {
				$where = 'WHERE true ';
			}
			if ($this->formvars['newpathwkt']){
				# über Polygon einschränken
				if ($this->formvars['within'] == 1) {
					$where .= " AND st_within(".$this->attributes['the_geom'].", st_buffer(st_transform(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$user->rolle->epsg_code."), ".$layerset[0]['epsg_code']."), 0.0001))";
				}
				else {
					$where .= " AND st_intersects(".$this->attributes['the_geom'].", st_transform(st_geomfromtext('".$this->formvars['newpathwkt']."', ".$user->rolle->epsg_code."), ".$layerset[0]['epsg_code']."))";
				}
			}
			$sql = "
				SELECT *
				FROM
					("
						. $sql
						. $groupby . "
					) as query "
				. $where
				. $orderby . "
			";
			$data_sql = $sql;
			#echo '<br>Frage Daten ab mit SQL: '. $sql;

			$temp_table = 'shp_export_'.rand(1, 1000000);

			# temporäre Tabelle erzeugen, falls Argumentliste durch das SQL zu lang
	    $sql = "
				CREATE TABLE public." . $temp_table . " AS "
				. $sql . "
			";
			#echo '<p>SQL zum Anlegen der temporären Tabelle: ' . $sql . '-';
			$ret = $layerdb->execSQL($sql,4, 0);
			for ($s = 0; $s < count($selected_attributes); $s++) {
				$selected_attributes[$s] = pg_quote($selected_attributes[$s]);
				# Transformieren der Geometrie
				if (trim($this->attributes['the_geom'], "'\"") == trim($selected_attributes[$s], "'\"")) {
					$selected_attributes[$s] = 'st_transform(' . $selected_attributes[$s] . ', ' . $this->formvars['epsg'] . ') ';
					if ($this->formvars['precision'] != '') {
						$selected_attributes[$s] = 'st_snaptogrid(' . $selected_attributes[$s] . ', 0.' . str_repeat('0', $this->formvars['precision'] - 1) . '1) ';
					}
					$selected_attributes[$s] .= 'as ' . $this->attributes['the_geom'];
				}
				# das Abschneiden bei nicht in der Länge begrenzten Textspalten verhindern
				if ($this->formvars['export_format'] == 'Shape') {
					if (in_array($selected_attr_types[$s], array('text', 'varchar')) AND ($selected_attr_length[$s] == '' OR $selected_attr_length[$s] > 254)) {
						$selected_attributes[$s] = $selected_attributes[$s] . '::varchar(254)';
					}
				}
			}

			# auf die ausgewählten Attribute einschränken
			$sql = "
				SELECT
					" . implode(', ', $selected_attributes) . "
				FROM
					public." . $temp_table . "
			";
			#echo '<p>SQL zur Abfrage der zu exportierenden Attribute; ' . $sql;
			$ret = $layerdb->execSQL($sql,4, 0);
			if (!$ret[0]) {
				$count = pg_num_rows($ret[1]);
				if ($this->formvars['layer_name'] == '') {
					$this->formvars['layer_name'] = $layerset[0]['Name'];
				}

				#showAlert('Abfrage erfolgreich. Es wurden '.$count.' Zeilen geliefert.');
				$this->formvars['layer_name'] = replace_params($this->formvars['layer_name'], rolle::$layer_params);
				$this->formvars['layer_name'] = umlaute_umwandeln($this->formvars['layer_name']);
				$this->formvars['layer_name'] = str_replace(['.', '(', ')', '/', '[', ']', '<', '>'], '_', $this->formvars['layer_name']);
				$this->formvars['geomtype'] = $this->attributes['geomtype'][$this->attributes['the_geom']];
				$folder = 'Export_'.$this->formvars['layer_name'].rand(0,10000);
				$old = umask(0);
	      mkdir(IMAGEPATH.$folder, 0777);                       # Ordner erzeugen
				umask($old);
				$zip = false;
				$exportfile = IMAGEPATH.$folder.'/'.$this->formvars['layer_name'];
				switch($this->formvars['export_format']){
					case 'Shape' : {
						$err = $this->ogr2ogr_export(addslashes($sql), '"ESRI Shapefile"', $exportfile . '.shp', $layerdb);
						if (!file_exists($exportfile.'.cpg')) {
							// ältere ogr-Versionen erzeugen die cpg-Datei nicht
							$fp = fopen($exportfile.'.cpg', 'w');
							fwrite($fp, 'UTF-8');
							fclose($fp);
						}
						$zip = true;
					} break;

					case 'GeoPackage' : {
						$exportfile = $exportfile.'.gpkg';
						$err = $this->ogr2ogr_export($sql, 'GPKG', $exportfile, $layerdb);
					} break;

					case 'DXF' : {
						$exportfile = $exportfile.'.dxf';
						$err = $this->ogr2ogr_export($sql, 'DXF', $exportfile, $layerdb);
					} break;

					case 'GML' : {
						$err = $this->ogr2ogr_export($sql, 'GML', $exportfile.'.xml', $layerdb);
						$zip = true;
					} break;

					case 'KML' : {
						$exportfile = $exportfile.'.kml';
						$err = $this->ogr2ogr_export($sql, 'KML', $exportfile, $layerdb);
						$contenttype = 'application/vnd.google-earth.kml+xml';
					} break;

					case 'GeoJSON' : {
						$exportfile = $exportfile.'.json';
						$err = $this->ogr2ogr_export($sql, 'GeoJSON', $exportfile, $layerdb);
						$contenttype = 'application/vnd.geo+json';
					} break;

					case 'GeoJSONPlus': {
						$exportfile = $exportfile.'.json';

						$err = $this->ogr2ogr_export($sql, 'GeoJSON', $exportfile, $layerdb);

						if (in_array('mobile', $kvwmap_plugins)) {
							$sql = "
								SELECT
									coalesce(max(version), 1) AS version
								FROM
									" . $layerset[0]['schema'] . "." . $layerset[0]['maintable'] . "_deltas
							";
							#echo '<p>SQL zur Abfrage der letzten Version in Delta Tabelle: ' . $sql;
							$ret = $layerdb->execSQL($sql,4, 0);
							if (!$ret[0]) {
								$rs = pg_fetch_assoc($ret[1]);
								$cmd = "sed -i 's/\"type\": \"FeatureCollection\"/\"type\": \"FeatureCollection\",\\n\"lastDeltaVersion\": " . $rs['version'] . "/g' " . $exportfile;
								exec($cmd, $output, $return);
							}
						}
					} break;

					case 'CSV' : {
						while ($rs=pg_fetch_assoc($ret[1])){
							$result[] = $rs;
						}
						$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, $result, true, $stelle->id, (count($result) > 2500 ? true : false));
						$csv = $this->create_csv($result, $this->attributes, $formvars['export_groupnames']);
						$exportfile = $exportfile.'.csv';
						$fp = fopen($exportfile, 'w');
						fwrite($fp, $csv);
						fclose($fp);
						$contenttype = 'application/vnd.ms-excel';
						$user->rolle->setConsumeCSV($currenttime,$this->formvars['selected_layer_id'], $count);
					} break;

					case 'UKO' : {
						$exportfile = $exportfile.'.uko';
						$this->create_uko($layerdb, $sql, $this->attributes['the_geom'], $this->formvars['epsg'], $exportfile);
						$contenttype = 'text/uko';
					} break;

					case 'OVL' : {
						$ovl = $this->create_ovl($layerset[0]['Datentyp'], $layerdb, $sql, $this->attributes['the_geom'], $this->formvars['epsg']);
						for($i = 0; $i < count($ovl); $i++){
							$exportfile2 = $exportfile.'_'.$i.'.ovl';
							$fp = fopen($exportfile2, 'w');
							fwrite($fp, $ovl[$i]);
							fclose($fp);
						}
						$zip = true;
					} break;
				}
				# Dokumente auch mit dazupacken
				if ($this->formvars['download_documents'] != '') {
					if ($result == NULL) {
						while ($rs=pg_fetch_assoc($ret[1])){
							$result[] = $rs;
						}
					}
					$this->attributes = $mapdb->add_attribute_values($this->attributes, $layerdb, $result, true, $stelle->id, (count($result) > 2500 ? true : false));
					for ($i = 0; $i < count($result); $i++) {
						$doc_zip = $this->copy_documents_to_export_folder($result[$i], $this->attributes, $layerset[0]['maintable'], $folder, $layerset[0]['document_path'], $layerset[0]['document_url']);
						$zip = $zip || $doc_zip;
					}
				}
				# Bei Bedarf auch Metadatendatei mit dazupacken
				if ($this->formvars['with_metadata_document'] != '' AND $layerset[0]['metalink'] != '') {
					$metadata_file = IMAGEPATH . $folder. '/' . basename($layerset[0]['metalink']);
					if (file_put_contents($metadata_file, file_get_contents($layerset[0]['metalink'], false, stream_context_create(array('ssl' => array('verify_peer' => false)))))) {
						# echo '<br>Metadatendatei heruntergeladen von: ' . $layerset[0]['metalink'];
						# echo '<br>und gespeichert unter: ' . $metadata_file;
					}
					else { ?>
						Download der Metadatendatei des Layers ist fehlgeschlagen!<br>Tragen Sie den Metadatenlink des Layers korrekt ein oder sorgen Sie für eine korrekte Internetverbindung zwischen dem Server und der Quelle des Dokumentes.<br>Informieren Sie Ihrem Administrator bei wiederholtem Auftreten dieses Fehlers.
						<p><a href="index.php?go=Daten_Export">Weiter mit Daten-Export</a>
						<p><a href="index.php?go=neu Laden">Zur Karte</a><?php
						exit;
					}
				}

				# bei Bedarf zippen
				if ($zip) {
					# Beim Zippen gehen die Umlaute in den Dateinamen kaputt, deswegen vorher umwandeln
					array_walk(searchdir(IMAGEPATH.$folder, true), function($item, $key){
						$pathinfo = pathinfo($item);
						rename($item, $pathinfo['dirname'].'/'.umlaute_umwandeln($pathinfo['filename']).'.'.$pathinfo['extension']);
					});
					exec(ZIP_PATH.' -j '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*'); # Ordner zippen
					#echo '<p>' . ZIP_PATH.' -j '.IMAGEPATH.$folder.' '.IMAGEPATH.$folder.'/*';
					$exportfile = IMAGEPATH.$folder.'.zip';
					$contenttype = 'application/octet-stream';
				}
				# temp. Tabelle wieder löschen
				$sql = 'DROP TABLE ' . $temp_table;
				$ret = $layerdb->execSQL($sql,4, 0);
				if ($this->formvars['export_format'] != 'CSV') {
					$user->rolle->setConsumeShape($currenttime, $this->formvars['selected_layer_id'], $count);
				}
				if ($err == '') {
					// Update timestamp formular_element_types having option export
					$time_attributes = array();
					foreach ($this->attributes['name'] AS $key => $value) {
						if (
							$this->attributes['form_element_type'][$value] == 'Time' AND
							trim(strtolower($this->attributes['options'][$value])) == 'export'
						) {
							$time_attributes[] = $value . " = '" . $currenttime . "'";
						}
					};
					if (!$layerset[0]['maintable_is_view'] AND count($time_attributes) > 0) {
						$update_table = $layerset[0]['schema'] . '.' . $layerset[0]['maintable'];
						$sql = "
							UPDATE
								" . $update_table . " AS update_table
							SET
								" . implode(", ", $time_attributes) . "
							FROM
								(" . $data_sql . ") AS data_table
							WHERE
								update_table." . $layerset[0]['oid'] . " = data_table." . $layerset[0]['oid'] . "
						";
						#echo '<br>sql: ' . $sql;
						$ret = $layerdb->execSQL($sql, 4, 0, true);
						if ($ret[0]) {
							$err = 'Speicherung der Zeitstempel ' . implode(", ", $time_attributes) . ' fehlgeschlagen.<br>' . sql_err_msg('Die Datenbank meldet:', $sql, $ret[1], 'error_div_' . rand(1, 99999));
						}
					}
				}
			}
			else {
				$err = 'Abfrage fehlgeschlagen!';
			}
		}

		if ($err == '') {
			ob_end_clean();
			header('Content-type: ' . $contenttype);
			header("Content-disposition:	attachment; filename=" . basename($exportfile));
			#header("Content-Length: ".filesize($exportfile));			# hat bei großen Datenmengen dazu geführt, dass der Download abgeschnitten wird
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			readfile($exportfile);
		}
		else {
			$GUI->add_message('error', $err);
			$GUI->daten_export();
		}
	}

	function copy_documents_to_export_folder($result, $attributes, $maintable, $folder, $doc_path, $doc_url, $recursion_depth = 0){
		global $GUI;
		$zip = false;
		foreach ($result As $key => $value) {
			$j = $attributes['indizes'][$key];
			if ($recursion_depth < 1 AND in_array($attributes['form_element_type'][$j], ['SubFormEmbeddedPK', 'SubFormPK'])) {
				$GUI->getSubFormResultSet($attributes, $j, $maintable, $result);
				foreach ($GUI->qlayerset[0]['shape'] as $sub_result) {
					$zip2 = $this->copy_documents_to_export_folder($sub_result, $GUI->qlayerset[0]['attributes'], $GUI->qlayerset[0]['maintable'], $folder, $doc_path, $doc_url, $recursion_depth + 1);
					$zip = $zip || $zip2;
				}
			}
			if ($value != '') {
				if (substr($attributes['type'][$j], 0, 1) == '_' AND is_numeric(substr($attributes['type'][$j], 1))) {		# Array aus Datentypen
					$array_elements = (!is_array($value)? json_decode($value, true) : $value);
					foreach ($array_elements as $array_element) {
						$zip2 = $this->copy_documents_to_export_folder($array_element, $attributes['type_attributes'][$j], $GUI->qlayerset[0]['maintable'], $folder, $doc_path, $doc_url);
						$zip = $zip || $zip2;
					}
				}
				if (is_numeric($attributes['type'][$j])) {		# Datentyp
					$datatype_elements = (!is_array($value)? json_decode($value, true) : $value);
					$zip2 = $this->copy_documents_to_export_folder($datatype_elements, $attributes['type_attributes'][$j], $GUI->qlayerset[0]['maintable'], $folder, $doc_path, $doc_url);
					$zip = $zip || $zip2;
				}
				if ($attributes['form_element_type'][$j] == 'Dokument') {
					$docs = array($value);
					if (substr($attributes['type'][$j], 0, 1) == '_') {		# Array aus Dokumenten
						$docs = json_decode($value);
					}
					foreach ($docs as $doc) {
						$parts = explode('&original_name=', $doc);
						if ($parts[1] == '') {
							# wenn kein Originalname da, Dateinamen nehmen
							$parts[1] = basename($parts[0]);
						}
						if ($doc_url != '') {
							$parts[0] = url2filepath($parts[0], $doc_path, $doc_url);
						}
						if (file_exists($parts[0])) {
							if (file_exists(IMAGEPATH . $folder . '/' . $parts[1])) {
								# wenn schon eine Datei mit dem Originalnamen existiert, wird der Dateiname angehängt
								$file_parts = explode('.', $parts[1]);
								$parts[1] = $file_parts[0].'_'.basename($parts[0]);
							}
							copy($parts[0], IMAGEPATH.$folder.'/'.$parts[1]);
						}
					}
					$zip = true;
				}
			}
		}
		return $zip;
	}

}
?>
