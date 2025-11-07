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
#  spatial_processor.php  Klasse zur Ausf�hrung von geometrischen Funktionen       #
###################################################################


#-----------------------------------------------------------------------------------------------------------------

class spatial_processor {
  
  function __construct($rolle, $database, $pgdatabase) {
    global $debug;
    $this->debug = $debug;
    $this->database = $database;
    $this->pgdatabase = $pgdatabase;
		$this->rolle = $rolle;
  }

	function getGeomFromGeoJSON($geojson, $epsg) {
		$sql = "
			SELECT 
				ST_AsSVG(geom) as svg,
				round(ST_Length(geom)) as length
			FROM
				(SELECT
					ST_Transform(
							ST_GeomFromGeoJSON('" . $geojson . "'),
							" . $epsg . "
					) as geom
				) as foo
		";
		$ret = $this->pgdatabase->execSQL($sql,4, 0);
		if (!$ret[0]) {
      $rs = pg_fetch_assoc($ret[1]);
			return json_encode($rs);
    }
	}
  
  function split_multi_geometries($wktgeom, $layer_epsg, $client_epsg, $geomtype){
  	$sql = "select " . (substr($geomtype, 0, 5) == 'MULTI'? 'st_multi' : '') . "(ST_geometryN(st_transform(st_geomfromtext('".$wktgeom."', ".$client_epsg."), ".$layer_epsg."),"; 
		$sql.= "generate_series(1, ST_NumGeometries(st_geomfromtext('".$wktgeom."', ".$client_epsg.")))))";
		$ret = $this->pgdatabase->execSQL($sql,4, 0);
		if (!$ret[0]) {
      while($rs=pg_fetch_row($ret[1])) {
        $geoms[]=$rs;
      }
      return $geoms;
    }
  }
		
	function split($geom_1, $geom_2){
  	$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom, 0, 15) as svg FROM (SELECT st_collect(geom) as geom from (select (st_dump(st_split(st_geomfromtext('".$geom_1."'), st_geomfromtext('".$geom_2."')))).geom as geom) as foo) as fooo";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_assoc($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }
	
	function union($geom_1, $geom_2, $normalize = true) {
		$union = "
			st_union(
				st_geomfromtext('" . $geom_1 . "'),
				st_geomfromtext('" . $geom_2 . "')
			)
		";
		
		if(
				(	NORMALIZE_AREA_THRESHOLD == 0 AND 
					NORMALIZE_ANGLE_THRESHOLD == 0 AND 
					NORMALIZE_POINT_DISTANCE_THRESHOLD == 0 AND 
					NORMALIZE_NULL_AREA == 0)
				OR
					strpos($geom_2, 'LINESTRING') !== false
		){
			$normalize = false;
		}

		if ($normalize) {
			$union = "st_makevalid(
					gdi_normalize_geometry(
						" . $union . ",
						" . NORMALIZE_AREA_THRESHOLD . ",
						" . NORMALIZE_ANGLE_THRESHOLD . ",
						" . NORMALIZE_POINT_DISTANCE_THRESHOLD . ",
						" . NORMALIZE_NULL_AREA . "
					)
				)
			";
		}

		$sql = "
			SELECT
				st_astext(geom) as wkt,
				st_assvg(geom, 0, 15) as svg
			FROM
				( SELECT " . $union . " as geom ) as foo
		";
		#echo $sql;
		$ret = $this->pgdatabase->execSQL($sql,4, 0, true);
		if ($ret[0]) {
			$rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
		}
		else {
			$rs = pg_fetch_assoc($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
		}		
	}

	/*
	* Funktion liefert den Teil von geom1, der nicht von geom2 überlappt wird
	* und normalisiert das Ergebnis mit der Funktion gdi_normalize_geometry
	* von Gaspare Sganga https://gasparesganga.com/labs/postgis-normalize-geometry/
	* mit den in den Datenbanken-Konstanten NORMALIZE* angegebenen Werten
	*/
	function difference($geom_1, $geom_2, $normalize = true) {
		$diff = "
			st_difference(
				st_geomfromtext('" . $geom_1 . "'),
				st_geomfromtext('" . $geom_2 . "')
			)
		";
		
		if (
				(	NORMALIZE_AREA_THRESHOLD == 0 AND 
					NORMALIZE_ANGLE_THRESHOLD == 0 AND 
					NORMALIZE_POINT_DISTANCE_THRESHOLD == 0 AND 
					NORMALIZE_NULL_AREA == 0)
				OR
					strpos($geom_2, 'LINESTRING') !== false
		) {
			$normalize = false;
		}

		if ($normalize) {
			$diff = "st_makevalid(
					gdi_normalize_geometry(
						" . $diff . ",
						" . NORMALIZE_AREA_THRESHOLD . ",
						" . NORMALIZE_ANGLE_THRESHOLD . ",
						" . NORMALIZE_POINT_DISTANCE_THRESHOLD . ",
						" . NORMALIZE_NULL_AREA . "
					)
				)
			";
		}

		$sql = "
			SELECT
				st_astext(geom) as wkt,
				st_assvg(geom, 0, 15) as svg
			FROM
				( SELECT " . $diff . " as geom ) as foo
		";
		#echo 'SQL zur Berechnung der geometrischen Differenz: ' . $sql;
		$ret = $this->pgdatabase->execSQL($sql,4, 0, true);
		if ($ret[0]) {
			$rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
		}
		else {
			$rs = pg_fetch_assoc($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
		}
	}

  function area($geom, $unit){
  	$sql = "SELECT round(st_area_utm(st_geomfromtext('".$geom."'), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.")::numeric, 2)";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0, true);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    if($unit == 'hektar'){
    	$rs[0] = $rs[0]/10000;
    	$rs[0] = round($rs[0],1);
    }
		$rs[0] = str_replace('.', ',', $rs[0]);
    return $rs[0].'█'.$rs[0];
  }
  
	function length($geom){
  	$sql = "SELECT round(st_length_utm(st_geomfromtext('".$geom."'), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.")::numeric, 2)";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
		$rs[0] = str_replace('.', ',', $rs[0]);
    return $rs[0].'█'.$rs[0];
  }
	
	function translate($geom, $x, $y){
  	$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom, 0, 15) as svg FROM (SELECT st_translate(st_geomfromtext('".$geom."'), ".$x.", ".$y.") as geom) as foo";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }

	function rotate($geom, $angle){
  	$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom, 0, 15) as svg FROM (SELECT st_rotate(st_geomfromtext('".$geom."'), RADIANS(".$angle."), st_centroid(st_geomfromtext('".$geom."'))) as geom) as foo";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }
	
	function reverse($geom){
  	$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom, 0, 15) as svg FROM (SELECT st_reverse(st_geomfromtext('".$geom."')) as geom) as foo";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }
	
	function centroid($geom){
  	$sql = "SELECT st_x(geom) as x, st_y(geom) as y FROM (SELECT st_centroid(st_geomfromtext('".$geom."')) as geom) as foo";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
			$result = $rs['x'] . ' ' . $rs['y'];
			return $result;
    }
  }
  
  function process_query($formvars){
		$formvars['fromwhere'] = str_replace("''", "'", $formvars['fromwhere']);
		$formvars['fromwhere'] = str_replace('$HIST_TIMESTAMP', rolle::$hist_timestamp, $formvars['fromwhere']);	
		if($formvars['path2'] != ''){
      $this->debug->write("path2:".$formvars['path2']."\n",4);
			if($formvars['geotype'] == 'line'){
				$polywkt2 = $this->composeLineWKTStringFromSVGPath($formvars['path2']);
			}
			else{
				$polywkt2 = $this->composeMultipolygonWKTStringFromSVGPath($formvars['path2']);
			}
		}
		if($formvars['path1'] != ''){
			$polywkt1 = $formvars['path1'];
  	}
    
    $this->debug->write("Starte operation: ".$formvars['operation']."\n",4);
		switch($formvars['operation']){
			case 'isvalid':{
				$result = $this->isvalid($polywkt1);
			} break;
			
			case 'transformPoint':{
		    # Transformation eines Punktes in ein anderes Koordinatensystem  
		    $point = $formvars['point'];
		    $newSRID=$formvars['newSRID'];
		    $curSRID=$formvars['curSRID'];
		    $coordtype=$formvars['coordtype'];
		    $ret=$this->pgdatabase->transformPoint($point, $curSRID, $newSRID, $coordtype);
		    if ($ret[0]) {
		    	$result = 'Fehler bei der Abfrage in PostGIS!';
		    }
		    else {
		    	$result=$ret[1];
		    }
			}break;
			
			case 'transform':{
		    # Transformation des aktuellen Kartenausschnittes
		    # in das Koordinatensystem des aktuellen EPSG-Codes
		    # Geg:	curExtent ... Koordinaten des aktuellen Ausschnittes (RectObj)
		    #				curSRID		...	Urspr�ngliche SRID (EPSG-Code als int)
		    #				newSRID		...	Neue SRID (EPSG-Code als int) 
		    $curExtent=$this->rolle->oGeorefExt;
		    $curSRID=$this->rolle->epsg_code;
		    $newSRID=$formvars['newSRID'];
				
				$epsg_codes = read_epsg_codes($this->pgdatabase);
				$user_epsg = $epsg_codes[$newSRID];
				if($user_epsg['minx'] != ''){							// Koordinatensystem ist räumlich eingegrenzt
					if($curSRID != 4326){
						$projFROM = new projectionObj("init=epsg:".$curSRID);
						$projTO = new projectionObj("init=epsg:4326");
						$curExtent->project($projFROM, $projTO);			// $curExtent wird in 4326 transformiert
					}
					// Vergleich der Extents und ggfs. Anpassung
					if($user_epsg['minx'] > $curExtent->minx)$curExtent->minx = $user_epsg['minx'];
					if($user_epsg['miny'] > $curExtent->miny)$curExtent->miny = $user_epsg['miny'];
					if($user_epsg['maxx'] < $curExtent->maxx)$curExtent->maxx = $user_epsg['maxx'];
					if($user_epsg['maxy'] < $curExtent->maxy)$curExtent->maxy = $user_epsg['maxy'];
					$projFROM = new projectionObj("init=epsg:4326");
					$projTO = new projectionObj("init=epsg:".$newSRID);
					$curExtent->project($projFROM, $projTO);				// Transformation in das System des Nutzers
					$result=$curExtent->minx.' '.$curExtent->miny.', '.$curExtent->maxx.' '.$curExtent->maxy;
				}
				else{				
					$ret=$this->pgdatabase->transformRect($curExtent,$curSRID,$newSRID);
					if ($ret[0]) {
						$result = 'Fehler bei der Abfrage in PostGIS!';
					}
					else {
						$newExtent=$ret[1];
						$result=$newExtent->minx.' '.$newExtent->miny.', '.$newExtent->maxx.' '.$newExtent->maxy;
					}
				}
			}break;
						
			case 'translate':{
				$result = $this->translate($polywkt1, $formvars['translate_x'], $formvars['translate_y']);
			}break;

			case 'rotate':{
				$result = $this->rotate($polywkt1, $formvars['angle']);
			}break;
			
			case 'reverse':{
				$result = $this->reverse($polywkt1);
			}break;

			case 'centroid':{
				$result = $this->centroid($polywkt1);
			}break;
			
			case 'buffer':{
				$result = $this->buffer($polywkt1, $formvars['width'], $formvars['segment_count']);
			}break;
			
			case 'buffer_ring':{
				$result = $this->buffer_ring($polywkt1, $formvars['width']);
			}break;
			
			case 'add_buffer_within_polygon':{
				$result = $this->add_buffer_within_polygon($polywkt1, $polywkt2, $formvars);
			}break;			
		
			case 'add_buffered_line':{
				$result = $this->add_buffered_line($polywkt1, $polywkt2, $formvars['width'] ?: 50);
			}break;

			case 'add_buffered_vertices':{
				$result = $this->add_buffered_vertices($polywkt1, $formvars);
			}break;
			
			case 'add_parallel_polygon':{
				$result = $this->add_parallel_polygon($polywkt1, $polywkt2, $formvars['width'] ?: 50, $formvars['side'], $formvars['subtract']);
			}break;
		
			case 'split':{
				$result = $this->split($polywkt1, $polywkt2);
				$formvars['code2execute'] .= '
					if ("' . $polywkt1 . '" != enclosingForm.pathwkt.value) {
						top.message("Teilung erfolgreich. Zum Abspeichern Schnittlinie bearbeiten oder in neue Objekte aufteilen.");
					}
				';
			}break;
		
			case 'add':{
				$result = $this->union($polywkt1, $polywkt2);
			}break;
			
			case 'subtract':{
				$result = $this->difference($polywkt1, $polywkt2);
			}break;
			
			case 'area':{
				if($polywkt1 != ''){
					$result = $this->area($polywkt1, $formvars['unit']);
				}
				else{
					$result = $this->area($polywkt2, $formvars['unit']);
				}
			}break;
			
			case 'length':{
				if($polywkt1 != ''){
					$result = $this->length($polywkt1);
				}
				else{
					$result = $this->length($polywkt2);
				}
			}break;
			
			case 'add_geometry':{
				$querygeometryWKT = $this->queryMap($formvars['input_coord'], $formvars['pixsize'], $formvars['geom_from_layer'], $formvars['singlegeom']);
				if($querygeometryWKT == '') {
					header('warning: true');
					echo 'Keine Geometrie zum Hinzufügen an der Kartenposition gefunden.';
					break;
				}
				if($polywkt1 == ''){
					$polywkt1 = 'POINT EMPTY';
				}
				$result = $this->union($polywkt1, $querygeometryWKT);
			}break;
			
			case 'subtract_geometry':{
				$querygeometryWKT = $this->queryMap($formvars['input_coord'], $formvars['pixsize'], $formvars['geom_from_layer'], $formvars['singlegeom']);
				if ($querygeometryWKT == '') {
					header('warning: true');
					echo 'Keine Geometrie zum Abziehen an der Kartenposition gefunden.';
					break;
				}
				if($polywkt1 == ''){
					$polywkt1 = $querygeometryWKT;
				}
				$diff = $polywkt1;
				$result = $this->difference($diff, $querygeometryWKT);
			}break;
			
		}
		if($result != '' AND !in_array($formvars['operation'], array('isvalid', 'area', 'length', 'transformPoint', 'transform', 'centroid'))){
			if($formvars['resulttype'] != 'wkt'){
				$result = $this->transformCoordsSVG($result);
			}
			$formvars['code2execute'] = 'update_geometry();' . $formvars['code2execute'];
		}
		if ($formvars['code2execute'] != '') {
			$result .= '█'.$formvars['code2execute'];
		}
		echo $result;
	}
	
	function queryMap($input_coord, $pixsize, $layer_id, $singlegeom, $aggregate_function = 'st_union') {
		# pixsize wird übergeben, weil sie aus dem Geometrieeditor anders sein kann, da es dort eine andere Kartengröße geben kann
		# Abfragebereich berechnen
		if ($input_coord) {
			$corners = explode(';' ,$input_coord);
			$lo = explode(',', $corners[0]); # linke obere Ecke in Bildkoordinaten von links oben gesehen
			$ru = explode(',', $corners[1]); # reche untere Ecke des Auswahlbereiches in Bildkoordinaten von links oben gesehen
			$width = $pixsize * ($ru[0] - $lo[0]); # Breite des Auswahlbereiches in m
			$height = $pixsize * ($ru[1] - $lo[1]); # H�he des Auswahlbereiches in m
			#echo 'Abfragerechteck im Bild: '.$lo[0].' '.$lo[1].' '.$ru[0].' '.$ru[1];
			# linke obere Ecke im Koordinatensystem in m
			$minx = $this->rolle->oGeorefExt->minx + $pixsize * $lo[0]; # x Wert
			$miny = $this->rolle->oGeorefExt->miny + $pixsize * ($this->rolle->nImageHeight - $ru[1]); # y Wert
			$maxx = $minx + $width;
			$maxy = $miny + $height;
			$rect = rectObj($minx, $miny, $maxx, $maxy);
		}
		$geom = $this->getgeometrybyquery($rect, $layer_id, $singlegeom, $aggregate_function);
		return $geom;
	}
 
  function buffer($geom_1, $width = 50, $segment_count = 8){
  	if(substr_count($geom_1, ',') == 1){			# wenn Polygon nur aus einem Eckpunkt besteht -> in POINT umwandeln -> Kreis entsteht
  		$geom_1 = $this->pointfrompolygon($geom_1);
  	}
  	$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom,0, 15) as svg FROM (select st_buffer(st_geomfromtext('" . $geom_1 . "'), " . $width . ", " . $segment_count . ") as geom) as foo";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_assoc($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }
  
	function buffer_ring($geom_1, $width){
		$sql = "
			SELECT
				st_astext(geom) as wkt,
				st_assvg(geom,0, 15) as svg
			FROM
				(
					SELECT
						st_difference(
							st_buffer(
								st_geomfromtext('" . $geom_1 . "'),
								" . $width . ",
								16
							),
							st_geomfromtext('" . $geom_1 . "')
						) as geom
				) as foo
		";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_assoc($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }
		
	function add_buffer_within_polygon($geom_1, $geom_2, $formvars){
		$querygeometryWKT = $this->queryMap($formvars['input_coord'], $formvars['pixsize'], $formvars['geom_from_layer'], false);
  	if(substr_count($geom_2, ',') == 0){			# wenn Linestring nur aus einem Eckpunkt besteht -> in POINT umwandeln -> Kreis entsteht
  		$geom_2 = $this->pointfromlinestring($geom_2);
  	}
		if($geom_1 == ''){
			$geom_1 = 'GEOMETRYCOLLECTION EMPTY';
		}
  	$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom, 0, 15) as svg FROM (SELECT (st_union(st_geomfromtext('".$geom_1."'), st_intersection(st_geomfromtext('".$querygeometryWKT."'),  st_buffer(st_geomfromtext('".$formvars['path3']."'), (select st_distance(st_geomfromtext('".$formvars['path3']."'), st_geomfromtext('".$geom_2."'))), 16)))) as geom) as foo";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_assoc($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }

	function add_buffered_vertices($geom_1, $formvars) {
		$querygeometryWKT = $this->queryMap($formvars['input_coord'], $formvars['pixsize'], $formvars['geom_from_layer'], false, 'st_collect');
		if($querygeometryWKT == '') {
			header('warning: true');
			echo 'Keine Geometrie zum Hinzufügen an der Kartenposition gefunden.';
			return;
		}
		if($geom_1 == ''){
			$geom_1 = 'GEOMETRYCOLLECTION EMPTY';
		}
		$sql = "
			SELECT 
				st_astext(geom) as wkt, 
				st_assvg(geom, 0, 15) as svg 
			FROM (
				SELECT
					st_union(st_geomfromtext('".$geom_1."'), st_buffer(st_union(geom), " . $formvars['width'] . ")) as geom
				FROM (
					SELECT
						(st_dumppoints(st_geomfromtext('" . $querygeometryWKT . "'))).geom as geom					
				) f
			) ff
		";
		#echo $sql;
		$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_assoc($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
	}
	
	function add_buffered_line($geom_1, $geom_2, $width){
  	if(substr_count($geom_2, ',') == 0){			# wenn Linestring nur aus einem Eckpunkt besteht -> in POINT umwandeln -> Kreis entsteht
  		$geom_2 = $this->pointfromlinestring($geom_2);
  	}
		if($geom_1 == ''){
			$geom_1 = 'GEOMETRYCOLLECTION EMPTY';
		}
		if($width < 0){		# eine negative Breite bewirkt das Abziehen der Puffergeometrie von der aktuellen Geometrie
			$width = $width * -1;
			$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom, 0, 15) as svg FROM (SELECT st_difference(st_geomfromtext('".$geom_1."'), st_buffer(st_geomfromtext('".$geom_2."'), ".$width.")) as geom) as foo";
		}
		else{
			$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom, 0, 15) as svg FROM (SELECT st_union(st_geomfromtext('".$geom_1."'), st_buffer(st_geomfromtext('".$geom_2."'), ".$width.")) as geom) as foo";
		}
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_assoc($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }
	
	function add_parallel_polygon($geom_1, $geom_2, $width, $side, $subtract){
		if($geom_1 == ''){
			$geom_1 = 'GEOMETRYCOLLECTION EMPTY';
		}
		if($subtract == 1)$operation = 'ST_Difference';	else $operation = 'ST_Union';
		if($side == 'right'){
			$width = -1 * $width;
			$reverse = 'ST_Reverse';			// bei rechts die Richtung des Linienzugs umdrehen, damit das Polygon mit ST_Polygonize korrekt gebildet werden kann
		}
		$sql = "SELECT st_astext(geom) as wkt, st_assvg(geom, 0, 15) as svg 
						FROM (
							SELECT ".$operation."(
								st_geomfromtext('".$geom_1."'),  
								ST_CollectionExtract(
									ST_Polygonize(
										ARRAY[
											offset_line,
											st_makeline(ST_StartPoint(offset_line), ST_StartPoint(the_geom)),
											the_geom,
											st_makeline(ST_EndPoint(offset_line), ST_EndPoint(the_geom))
										]
									)
							 ,3)
							) as geom
							FROM (SELECT ".$reverse."(ST_OffsetCurve(the_geom, ".$width.", 'join=round')) as offset_line, the_geom 
										FROM (SELECT ST_GeomFromText('".$geom_2."') AS the_geom) as foo
							) as fooo
						) as foooo";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
			$result = $rs['svg'] . '||' . $rs['wkt'];
			return $result;
    }
  }

	function getgeometrybyquery($rect, $layer_id, $singlegeom, $aggregate_function) {
		$dbmap = new db_mapObj($this->rolle->stelle_id, $this->rolle->user_id);
		if ($layer_id != '') {
			if ($layer_id < 0) { # Rollenlayer
				$layerset = $dbmap->read_RollenLayer(-$layer_id);
			}
			else { # normaler Layer
				$layerset = $this->rolle->getLayer($layer_id);
			}
		}
		else {
			return NULL;
		}
		switch ($layerset[0]['toleranceunits']) {
			case 'pixels' : $pixsize = $this->rolle->pixsize; break;
			case 'meters' : $pixsize = 1; break;
			default : $pixsize=$this->rolle->pixsize;
		}
		$rand = $layerset[0]['tolerance'] * $pixsize;

		switch ($layerset[0]['connectiontype']) {
			case 6 : {
				#Abfrage eines postgislayers
				# Aktueller EPSG in der die Abfrage ausgeführt wurde
				$client_epsg = $this->rolle->epsg_code;
				# EPSG-Code des Layers der Abgefragt werden soll
				$layer_epsg = $layerset[0]['epsg_code'];

				$data = $layerset[0]['Data'];
				$data_explosion = explode(' ', $data);
				$columnname = $data_explosion[0];
				$select = getDataParts($data)['select'];
				$fromwhere = $select;
				# order by rausnehmen
				$orderby = '';
				$orderbyposition = strrpos(strtolower($select), 'order by');
				$lastfromposition = strrpos(strtolower($select), 'from');
				if ($orderbyposition !== false AND $orderbyposition > $lastfromposition) {
					$fromwhere = substr($select, 0, $orderbyposition);
					$orderby = ' ' . substr($select, $orderbyposition);
				}

				# Bildung der Where-Klausel für die räumliche Abfrage mit der searchbox
				$searchbox_wkt ="POLYGON((";
				$searchbox_wkt.=strval($rect->minx)." ".strval($rect->miny).",";
				$searchbox_wkt.=strval($rect->maxx)." ".strval($rect->miny).",";
				$searchbox_wkt.=strval($rect->maxx)." ".strval($rect->maxy).",";
				$searchbox_wkt.=strval($rect->minx)." ".strval($rect->maxy).",";
				$searchbox_wkt.=strval($rect->minx)." ".strval($rect->miny)."))";
				#echo '<br>wkt: ' . $searchbox_wkt;

				if ($columnname == '') {
					$columnname = 'the_geom';
				}
				if ($rect) {
					# Wenn das Koordinatenssystem des Views anders ist als vom Layer wird die Suchbox und die Suchgeometrie
					# in epsg des layers transformiert
					if ($client_epsg!=$layer_epsg) {
						$sql_where =" AND ".$columnname." && st_transform(st_geomfromtext('".$searchbox_wkt."',".$client_epsg."),".$layer_epsg.")";
					}
					else {
						$sql_where =" AND ".$columnname." && st_geomfromtext('".$searchbox_wkt."',".$client_epsg.")";
					}

					# Wenn es sich bei der Suche um eine punktuelle Suche handelt, wird die where Klausel um eine
					# Umkreissuche mit dem Suchradius weiter eingeschränkt.
					if ($rect->minx==$rect->maxx AND $rect->miny==$rect->maxy) {
						# Behandlung der Suchanfrage mit Punkt, exakte Suche im Kreis
						if ($client_epsg!=$layer_epsg) {
							$sql_where.=" AND st_distance(".$columnname.",st_transform(st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."),".$layer_epsg."))";
						}
						else {
							$sql_where.=" AND st_distance(".$columnname.",st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."))";
						}
						$sql_where.=" <= ".$rand;
						$punktuell = true;
					}
					else {
						# Behandlung der Suchanfrage mit Rechteck, exakte Suche im Rechteck
						if ($client_epsg!=$layer_epsg) {
							$sql_where.=" AND st_intersects(".$columnname.",st_transform(st_geomfromtext('".$searchbox_wkt."',".$client_epsg."),".$layer_epsg."))";
						}
						else {
							$sql_where.=" AND st_intersects(".$columnname.",st_geomfromtext('".$searchbox_wkt."',".$client_epsg."))";
						}
					}
				}
				
	      # 2006-06-12 sr   Filter zur Where-Klausel hinzugefügt
	      if($layerset[0]['Filter'] != ''){
	      	$layerset[0]['Filter'] = str_replace('$USER_ID', $this->rolle->user_id, $layerset[0]['Filter']);
	        $sql_where .= " AND ".$layerset[0]['Filter'];
	      }

				#$fromwhere = pg_escape_string('from (' . $fromwhere . ') as foo where 1=1');
				$fromwhere = 'from (' . $fromwhere . ') as foo where 1=1';

				if (strpos(strtolower($fromwhere), ' where ') === false) {
					$fromwhere .= ' where (1=1)';
				}
				if ($fromwhere != '') {
					if ($singlegeom == 'true') {
						$fromwhere = preg_replace('/ ([a-z_]*\.)?'.$columnname.'/', ' (st_dump($0)).geom as ' . $columnname, $fromwhere);		# Einzelgeometrien abfragen
					}
					if (!$punktuell) {
						# bei punktueller Abfrage wird immer nur eine Objektgeometrie geholt, bei Rechteck-Abfrage die Vereinigung aller getroffenen Geometrien
						$columnname = $aggregate_function . "(".$columnname.")";
					}
					$columnname = 'ST_Force2D(' . $columnname . ')';
					$sql = "
						SELECT ST_AsText(" . ($client_epsg != $layer_epsg ? "ST_Transform(" . $columnname . ", " . $client_epsg . ")" : $columnname) . ") AS geomwkt
						" . $fromwhere . "
						" . $sql_where . "
					";
				}

				# order by wieder einbauen
				if ($punktuell) {
					$sql .= $orderby;
				}

				# Anhängen des Begrenzers zur Einschränkung der Anzahl der Ergebniszeilen
				$sql .= ' LIMIT ' . MAXQUERYROWS;
				$ret = $this->pgdatabase->execSQL($sql,4, 0);
				#echo '<br>SQL: ' . $sql;
				if ($ret[0]) {
					echo $ret['msg'];
				}
				else {
					while ($rs = pg_fetch_array($ret[1])) {
						$layerset[0]['shape'][]=$rs;
					}
				}
				#echo '<br>geomwkt: ' . $layerset[0]['shape'][0]['geomwkt'];
				return $layerset[0]['shape'][0]['geomwkt'];
			} break; # ende Layer ist aus postgis
    	
    	case 9 : {
    		# Abfrage eines WFS-Layers
		    $projFROM = new projectionObj("init=epsg:".$this->rolle->epsg_code);
        $projTO = new projectionObj("init=epsg:".$layerset[0]['epsg_code']);
    		$rect->project($projFROM, $projTO);
    		$searchbox_minx=strval($rect->minx-$rand);
	      $searchbox_miny=strval($rect->miny-$rand);
	      $searchbox_maxx=strval($rect->maxx+$rand);
	      $searchbox_maxy=strval($rect->maxy+$rand);
				$request = $layerset[0]['connection'].'&service=WFS&version=1.1.0&request=getfeature&srsName=EPSG:'.$layerset[0]['epsg_code'].'&typename='.$layerset[0]['wms_name'].'&bbox='.$searchbox_minx.','.$searchbox_miny.','.$searchbox_maxx.','.$searchbox_maxy;
				$request .= ',EPSG:'.$layerset[0]['epsg_code'];
        $this->debug->write("<br>WFS-Request: ".$request,4);
	      $gml = url_get_contents($request, $layerset[0]['wms_auth_username'], $layerset[0]['wms_auth_password']);
        #$this->debug->write("<br>WFS-Response: ".$gml,4);
				if ($layerset[0]['Datentyp'] == 2) {
					$wkt = $this->composeMultipolygonWKTStringFromGML($gml, $layerset[0]['wfs_geom']);
				}
				elseif ($layerset[0]['Datentyp'] == 1) {
					$wkt = $this->composeMultilineWKTStringFromGML($gml, $layerset[0]['wfs_geom']);
				}
	      #$this->debug->write("<br>WKT von GML-Geometrie: ".$wkt,4);
	      if($layerset[0]['epsg_code'] != $this->rolle->epsg_code)$wkt = $this->pgdatabase->transformPoly($wkt, $layerset[0]['epsg_code'], $this->rolle->epsg_code);
	      return $wkt;
    	}break;
    }
  }
	
	function pointfrompolygon($polygeom){
		$parts = explode(',', $polygeom);
		$point = str_replace('POLYGON(', 'POINT', $parts[0]).')';
		return $point;
	}
	
	function pointfromlinestring($linegeom){
		$parts = explode(')', $linegeom);
		$point = str_replace('LINESTRING', 'POINT', $parts[0]).')';
		return $point;
	}
	
	function composeMultipolygonWKTStringFromSVGPath($path){
    $WKT = 'MULTIPOLYGON(';
    $explosion = explode('M', $path);
    for($i = 0; $i < count($explosion); $i++){
      if($explosion[$i] != ''){
        if($i > 1){
          $WKT .= ',';
        }
        $WKT .= '((';
        $polygon = explode(' ', $explosion[$i]);
        $WKT .= $polygon[1].' '.$polygon[2];
        for($j = 3; $j < count($polygon)-1; $j=$j+2){
          if($polygon[$j] != ''){
            $WKT .= ','.$polygon[$j].' '.$polygon[$j+1];
          }
        }
        $WKT .= '))';
      }
    }
    $WKT .= ')';
    return $WKT;
  }
	
	function composePolygonWKTStringFromSVGPath($path){
    $WKT = 'POLYGON(';
    $explosion = explode('M', $path);
    for($i = 0; $i < count($explosion); $i++){
      if($explosion[$i] != ''){
        if($i > 1){
          $WKT .= ',';
        }
        $WKT .= '(';
        $polygon = explode(' ', $explosion[$i]);
        $WKT .= $polygon[1].' '.$polygon[2];
        for($j = 3; $j < count($polygon)-1; $j=$j+2){
          if($polygon[$j] != ''){
            $WKT .= ','.$polygon[$j].' '.$polygon[$j+1];
          }
        }
        $WKT .= ')';
      }
    }
    $WKT .= ')';
    return $WKT;
  }	
  
	function isvalid($pathwkt) {
		if ($pathwkt != '') {
			if ($pathwkt == "\\") {
				$msg .= 'Die Geometrie des Polygons ist fehlerhaft! Laden Sie den Geometrieeditor neu und vermeiden Sie Selbstüberschneidungen beim Zeichnen.';
			}
			else {
				$sql = "
					SELECT st_isvalid(st_geomfromtext('" . $pathwkt . "'))
				";
				#echo '<br>Sql: ' . $sql;
				$ret = $this->pgdatabase->execSQL($sql, 4, 0);
				if($ret['success']){
					$valid = pg_fetch_row($ret[1]);
					if ($valid[0] == 'f') {
						$sql = "SELECT st_isvalidreason(st_geomfromtext('".$pathwkt."'))";
						$ret = $this->pgdatabase->execSQL($sql, 4, 0);
						$reason = pg_fetch_row($ret[1]);
						$msg = 'Die Geometrie des Polygons ist fehlerhaft:<br>' . $reason[0];
					}
				}
				else{
					echo $ret['msg'];
				}
			}
		}
		return ($msg != '' ? "message('" . $msg . "');" : "");
	}

  function validize_polygon_svg($path){
  	$explosion = explode('M', $path);
    for($i = 0; $i < count($explosion); $i++){
      if($explosion[$i] != ''){
        $polygon = explode(' ', $explosion[$i]);
        $poly = '';
        for($j = 1; $j < count($polygon)-1; $j=$j+2){
        	if($polygon[$j+1] == $polygon[$j+3]){		# Division durch 0 abfangen
        		$cur_ascent = 0;
        	}
        	else{
        		$cur_ascent = ($polygon[$j]-$polygon[$j+2])/($polygon[$j+1]-$polygon[$j+3]);
        	}
        	if($first_ascent == ''){$first_ascent = $cur_ascent;}
        	#echo $cur_ascent.'-';
        	if(abs($last_ascent - $cur_ascent) < 0.001){
        		array_splice($polygon, $j, 2);		# Koordinate aus Polygonarray rausnehmen
        		$poly = '';												# Polygonstring leeren
        		$j = -1;													# wieder vorn vorn anfangen, das Polygonarray zu durchlaufen 
        	}
        	else{
        		$last_ascent = $cur_ascent;
          	if($polygon[$j] != ''){
            	$poly .= ' '.$polygon[$j].' '.$polygon[$j+1];			# Koordinate in Polgonstring aufnehmen
          	}
        	}
        }
        if(count($polygon) > 8){							# wenn mehr als 2 Punkte im Polygon, wird es ins Gesamtpolygon aufgenommen
        	$svg .= 'M'.$poly.' ';
        }
      }
    }
    return $svg;
  }
  
  function validize_polygon_wkt($path){
  	if($path == 'GEOMETRYCOLLECTION EMPTY'){
  		return $path;
  	}
  	if(substr($path, 0, 4) == "MULT"){
  		$path = substr($path, 15, strlen($path)-18);
	  	$wkt = 'MULTIPOLYGON(';
	  	$explosion1 = explode(')),((', $path);
	  	for($k = 0; $k < count($explosion1); $k++){
	  		$subpoly = '';
		  	$explosion = explode('),(', $explosion1[$k]);
		    for($i = 0; $i < count($explosion); $i++){
	        $polygon = explode(',', $explosion[$i]);
					$poly = '';
	        $komma = false;
	        for($j = 0; $j < count($polygon); $j++){
	        	$point1 = explode(' ', $polygon[$j]);
	        	$point2 = explode(' ', $polygon[$j+1]);
	        	if($point1[1] == $point2[1]){
	        		$cur_ascent = 0;
	        	}
	        	else{ 
	        		$cur_ascent = ($point1[0]-$point2[0])/($point1[1]-$point2[1]);
	        	}
	        	if((($last_ascent < 0 AND $cur_ascent > 0) OR ($cur_ascent < 0 AND $last_ascent > 0)) AND abs($last_ascent - $cur_ascent) < 0.001){
	        		array_splice($polygon, $j, 1);		# Koordinate aus Polygonarray rausnehmen
	        		$poly = '';												# Polygonstring leeren
	        		$j = -1;													# wieder vorn vorn anfangen, das Polygonarray zu durchlaufen
	        		$komma = false; 
	        	}
	        	else{
	        		$last_ascent = $cur_ascent;
	        		if($komma == true){
	          		$poly .= ',';
	          	}
	            $poly .= $point1[0].' '.$point1[1];
	            $komma = true;
	        	}
	        }
	        if(count($polygon) > 4){							# wenn mehr als 2 Punkte im Polygon, wird es ins Gesamtpolygon aufgenommen
	        	if($i > 0){
		          $subpoly .= ',';
		        }
	        	$subpoly .= '('.$poly.')';
	        }
		    }
		    if($subpoly != ''){
		    	if($k > 0){
          	$wkt .= ',';
	        }
	        $wkt .= '('.$subpoly.')';
		    }
	  	}
	  	$wkt .= ')';
  	}
  	else{
  		$path = substr($path, 9, strlen($path)-11);
	  	$wkt = 'POLYGON(';
	  	$explosion = explode('),(', $path);
	    for($i = 0; $i < count($explosion); $i++){
        if($i > 0){
          $wkt .= ',';
        }
        $wkt .= '(';
        $polygon = explode(',', $explosion[$i]);
        $komma = false;
        for($j = 0; $j < count($polygon); $j++){
        	$point1 = explode(' ', $polygon[$j]);
        	$point2 = explode(' ', $polygon[$j+1]); 
        	if($point1[1] == $point2[1]){
        		$cur_ascent = 0;
        	}
        	else{ 
        		$cur_ascent = ($point1[0]-$point2[0])/($point1[1]-$point2[1]);
        	}
        	if(abs($last_ascent - $cur_ascent) < 0.001){
        	}
        	else{
        		$last_ascent = $cur_ascent;
        		if($komma == true){
          		$wkt .= ',';
          	}
            $wkt .= $point1[0].' '.$point1[1];
            $komma = true;
        	}
        }
        $wkt .= ')';
	    }
	    $wkt .= ')';
  	}
    return $wkt;
  }
  	
	function composeLineWKTStringFromSVGPath($path){
  	$WKT = 'LINESTRING';
    $explosion = explode('M', $path);
    for($i = 0; $i < count($explosion); $i++){
      if($explosion[$i] != ''){
        if($i > 1){
          $WKT .= ',';
        }
        $WKT .= '(';
        $polygon = explode(' ', $explosion[$i]);
        $WKT .= $polygon[1].' '.$polygon[2];
        for($j = 3; $j < count($polygon)-1; $j=$j+2){
          if($polygon[$j] != ''){
            $WKT .= ','.$polygon[$j].' '.$polygon[$j+1];
          }
        }
        $WKT .= ')';
      }
    }
    return $WKT;
  }

	function composeMultipointWKTStringFromSVGPath($svgpath) {
		if ($svgpath != '') {
			$wkt = "MULTIPOINT((";
			$coord = explode(' ', $svgpath);
			$wkt = $wkt . $coord[1] . " " . $coord[2];
			for ($i = 3; $i < count($coord) - 1; $i++){
				if ($coord[$i] != ""){
					$wkt = $wkt . "),(" . $coord[$i] . " " . $coord[$i+1];
				}
				$i++;
			}
			$wkt = $wkt . "))";
		}
		return $wkt;
	}
  
  function transformCoordsSVG($path){
	if($path != ''){
		$part = explode('||',$path);
		# SVG Part
		$svgresult = transformCoordsSVG($part[0]);
		if(count($part) > 1){
		  $result = $svgresult.'||'.$part[1];
		}
		else{
		  $result = $svgresult;
		}
		return $result;
	}
	else return NULL;
  }
  
  function composeMultipolygonWKTStringFromGML($gml, $geom_attribute){
    $polygons = explode('<gml:Polygon', $gml);
    for($i = 1; $i < count($polygons); $i++){
    	$wkt_polygon[$i-1] = 'st_geomfromtext(\'POLYGON(';
    	$rings = explode('<gml:posList', $polygons[$i]);
      for($j = 1; $j < count($rings); $j++){
      	if($j > 1){$wkt_polygon[$i-1] .= ',';}
      	$wkt_polygon[$i-1] .= '(';
      	$start = strpos($rings[$j], '>')+1;
      	$end = strpos($rings[$j], '</gml:posList>');
      	$coords = substr($rings[$j], $start, $end-$start);
				$coord_array = explode(' ', $coords);
				$wkt_coords = $coord_array[0].' '.$coord_array[1];
      	for($k = 2; $k < count($coord_array)-1; $k=$k+2){
					$wkt_coords .= ','.$coord_array[$k].' '.$coord_array[$k+1];
				}
      	$wkt_polygon[$i-1] .= $wkt_coords;
      	$wkt_polygon[$i-1] .= ')';
      }
      $wkt_polygon[$i-1] .= ')\')';
    }
    $sql = "SELECT st_astext(st_union(ARRAY[".implode(',', $wkt_polygon)."]))";
  	$ret=$this->pgdatabase->execSQL($sql,4, 0);
  	if(!$ret[0]){
  		$rs=pg_fetch_array($ret[1]);
  		return $rs[0];	
    }
  }
	
	function composePolygonArrayWKTStringFromGML($gml, $geom_attribute, $epsg){
    $polygons = explode('<gml:Polygon', $gml);
    for($i = 1; $i < count($polygons); $i++){
    	$wkt_polygon[$i-1] = 'st_geomfromtext(\'POLYGON(';
    	$rings = explode('<gml:posList', $polygons[$i]);
      for($j = 1; $j < count($rings); $j++){
      	if($j > 1){$wkt_polygon[$i-1] .= ',';}
      	$wkt_polygon[$i-1] .= '(';
      	$start = strpos($rings[$j], '>')+1;
      	$end = strpos($rings[$j], '</gml:posList>');
      	$coords = substr($rings[$j], $start, $end-$start);
				$coord_array = explode(' ', $coords);
				$wkt_coords = $coord_array[0].' '.$coord_array[1];
      	for($k = 2; $k < count($coord_array)-1; $k=$k+2){
					$wkt_coords .= ','.$coord_array[$k].' '.$coord_array[$k+1];
				}
      	$wkt_polygon[$i-1] .= $wkt_coords;
      	$wkt_polygon[$i-1] .= ')';
      }
      $wkt_polygon[$i-1] .= ')\', '.$epsg.')';
    }
    return 'ARRAY['.implode(',', $wkt_polygon).']';
  }
	
  function composeMultilineWKTStringFromGML($gml, $geom_attribute){
    $polygons = explode('<gml:LineString', $gml);
    for($i = 1; $i < count($polygons); $i++){
    	$wkt_linestring[$i-1] = 'st_geomfromtext(\'LINESTRING';
    	$rings = explode('<gml:posList', $polygons[$i]);
      for($j = 1; $j < count($rings); $j++){
      	if($j > 1){$wkt_linestring[$i-1] .= ',';}
      	$wkt_linestring[$i-1] .= '(';
      	$start = strpos($rings[$j], '>')+1;
      	$end = strpos($rings[$j], '</gml:posList>');
      	$coords = substr($rings[$j], $start, $end-$start);
				$coord_array = explode(' ', $coords);
				$wkt_coords = $coord_array[0].' '.$coord_array[1];
      	for($k = 2; $k < count($coord_array)-1; $k=$k+2){
					$wkt_coords .= ','.$coord_array[$k].' '.$coord_array[$k+1];
				}
      	$wkt_linestring[$i-1] .= $wkt_coords;
      	$wkt_linestring[$i-1] .= ')';
      }
      $wkt_linestring[$i-1] .= '\')';
    }
    $sql = "SELECT st_astext(st_union(ARRAY[".implode(',', $wkt_linestring)."]))";
  	$ret=$this->pgdatabase->execSQL($sql,4, 0);
  	if(!$ret[0]){
  		$rs=pg_fetch_array($ret[1]);
  		return $rs[0];	
    }
  }	
	
	function composeMultipointWKTStringFromGML($gml, $geom_attribute){
    $points = explode('<gml:Point', $gml);
    for($i = 1; $i < count($points); $i++){
			$start = strpos($points[$i], '<gml:pos>')+9;
			$end = strpos($points[$i], '</gml:pos>');
      $coords = substr($points[$i], $start, $end-$start);
      $wkt_point[$i-1] = 'st_geomfromtext(\'POINT('.$coords.')\')';
    }
    $sql = "SELECT st_astext(st_union(ARRAY[".implode(',', $wkt_point)."]))";
  	$ret=$this->pgdatabase->execSQL($sql,4, 0);
  	if(!$ret[0]){
  		$rs=pg_fetch_array($ret[1]);
  		return $rs[0];	
    }
  }
	 
}

?>