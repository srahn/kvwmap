<?php
###################################################################
# kvwmap - Kartenserver f�r Kreisverwaltungen                     #
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
  
  ###################### Liste der Funktionen ####################################
  #
  # 
  ################################################################################

  function spatial_processor($rolle, $database, $pgdatabase) {
    global $debug;
    $this->debug = $debug;
    $this->database = $database;
    $this->pgdatabase = $pgdatabase;
		$this->rolle = $rolle;
  }
  
  function split_multi_geometries($wktgeom, $layer_epsg, $client_epsg){
  	$sql = "select ST_geometryN(st_transform(st_geomfromtext('".$wktgeom."', ".$client_epsg."), ".$layer_epsg."),"; 
		$sql.= "generate_series(1, ST_NumGeometries(st_geomfromtext('".$wktgeom."', ".$client_epsg."))))";
		$ret = $this->pgdatabase->execSQL($sql,4, 0);
		if (!$ret[0]) {
      while($rs=pg_fetch_row($ret[1])) {
        $geoms[]=$rs;
      }
      return $geoms;
    }
  }
  
  function union($geom_1, $geom_2, $type){
  	if($type == 'wkt'){
  		//$sql = "SELECT st_astext(validize_polygon(st_union(st_geomfromtext('".$geom_1."'), st_geomfromtext('".$geom_2."'))))";
  		$sql = "SELECT st_astext(ST_SnapToGrid(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), ST_SnapToGrid(st_geomfromtext('".$geom_2."'), 0.0001)), 0.0001))";
  	}
  	else{
  		//$sql = "SELECT st_assvg(validize_polygon(st_union(st_geomfromtext('".$geom_1."'), st_geomfromtext('".$geom_2."'))),0,8)";
  		$sql = "SELECT st_assvg(ST_SnapToGrid(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), ST_SnapToGrid(st_geomfromtext('".$geom_2."'), 0.0001)), 0.0001),0,8)";
  	}
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }
  
  function difference($geom_1, $geom_2, $type){
  	if($type == 'wkt'){
  		$sql = "SELECT st_astext(ST_SnapToGrid(st_difference(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), ST_SnapToGrid(st_geomfromtext('".$geom_2."'), 0.0001)), 0.0001))";
  	}
  	else{
  		$sql = "SELECT st_assvg(ST_SnapToGrid(st_difference(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), ST_SnapToGrid(st_geomfromtext('".$geom_2."'), 0.0001)), 0.0001),0,8)";
  	}
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
  	#return $sql;
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }
  
  function area($geom, $unit){
  	$sql = "SELECT round(st_area(st_geomfromtext('".$geom."'))::numeric, 2)";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    if($unit == 'hektar'){
    	$rs[0] = $rs[0]/10000;
    	$rs[0] = round($rs[0],1);
    }
    return $rs[0].'~'.$rs[0];
  }
  
	function length($geom){
  	$sql = "SELECT round(st_length(st_geomfromtext('".$geom."'))::numeric, 2)";
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0].'~'.$rs[0];
  }
	
	function translate($geom, $type, $x, $y){
  	if($type == 'wkt'){
  		$sql = "SELECT st_astext(st_translate(st_geomfromtext('".$geom."'), ".$x.", ".$y."))";
  	}
  	else{
  		$sql = "SELECT st_assvg(st_translate(st_geomfromtext('".$geom."'), ".$x.", ".$y."),0,8)";
  	}
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
  	#return $sql;
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }
  
  function process_query($formvars){
  	$formvars['fromwhere'] = stripslashes($formvars['fromwhere']);
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
		    # Geg:	curExtent ... Koordinaten des aktuellen Ausschnittes (ms_newRectObj)
		    #				curSRID		...	Urspr�ngliche SRID (EPSG-Code als int)
		    #				newSRID		...	Neue SRID (EPSG-Code als int) 
		    $curExtent=$this->rolle->oGeorefExt;
		    $curSRID=$this->rolle->epsg_code;
		    $newSRID=$formvars['newSRID'];
				
				$epsg_codes = read_epsg_codes($this->pgdatabase);
				$user_epsg = $epsg_codes[$newSRID];
				if($user_epsg['minx'] != ''){							// Koordinatensystem ist räumlich eingegrenzt
					if($curSRID != 4326){
						$projFROM = ms_newprojectionobj("init=epsg:".$curSRID);
						$projTO = ms_newprojectionobj("init=epsg:4326");
						$curExtent->project($projFROM, $projTO);			// $curExtent wird in 4326 transformiert
					}
					// Vergleich der Extents und ggfs. Anpassung
					if($user_epsg['minx'] > $curExtent->minx)$curExtent->minx = $user_epsg['minx'];
					if($user_epsg['miny'] > $curExtent->miny)$curExtent->miny = $user_epsg['miny'];
					if($user_epsg['maxx'] < $curExtent->maxx)$curExtent->maxx = $user_epsg['maxx'];
					if($user_epsg['maxy'] < $curExtent->maxy)$curExtent->maxy = $user_epsg['maxy'];
					$projFROM = ms_newprojectionobj("init=epsg:4326");
					$projTO = ms_newprojectionobj("init=epsg:".$newSRID);
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
			
			case 'get_closest_line':{
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->get_closest_line($polywkt1, 'svg', $formvars['fromwhere']);
					$result .= '||';
					$result .= $this->get_closest_line($polywkt1, 'wkt', $formvars['fromwhere']);
				}
				else{
					$result = $this->get_closest_line($polywkt1, $formvars['resulttype'], $formvars['fromwhere']);
				}
			}break;
			
			case 'translate':{
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->translate($polywkt1, 'svg', $formvars['translate_x'], $formvars['translate_y']);
					$result .= '||';
					$result .= $this->translate($polywkt1, 'wkt', $formvars['translate_x'], $formvars['translate_y']);
				}
				else{
					$result = $this->translate($polywkt1, $formvars['resulttype'], $formvars['translate_x'], $formvars['translate_y']);
				}
			}break;
			
			case 'buffer':{
				if($formvars['width'] == ''){$formvars['width'] = 50;}
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->buffer($polywkt1, 'svg', $formvars['width']);
					$result .= '||';
					$result .= $this->buffer($polywkt1, 'wkt', $formvars['width']);
				}
				else{
					$result = $this->buffer($polywkt1, $formvars['resulttype'], $formvars['width']);
				}
			}break;
			
			case 'buffer_ring':{
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->buffer_ring($polywkt1, 'svg', $formvars['width']);
					$result .= '||';
					$result .= $this->buffer_ring($polywkt1, 'wkt', $formvars['width']);
				}
				else{
					$result = $this->buffer_ring($polywkt1, $formvars['resulttype'], $formvars['width']);
				}
			}break;
		
			case 'add_buffered_line':{
				if($formvars['width'] == ''){$formvars['width'] = 50;}
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->add_buffered_line($polywkt1, $polywkt2, 'svg', $formvars['width']);
					$result .= '||';
					$result .= $this->add_buffered_line($polywkt1, $polywkt2, 'wkt', $formvars['width']);
				}
				else{
					$result = $this->add_buffered_line($polywkt1, $polywkt2, $formvars['resulttype'], $formvars['width']);
				}
			}break;
			
			case 'add_parallel_polygon':{
				if($formvars['width'] == ''){$formvars['width'] = 50;}
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->add_parallel_polygon($polywkt1, $polywkt2, 'svg', $formvars['width']);
					$result .= '||';
					$result .= $this->add_parallel_polygon($polywkt1, $polywkt2, 'wkt', $formvars['width']);
				}
				else{
					$result = $this->add_parallel_polygon($polywkt1, $polywkt2, $formvars['resulttype'], $formvars['width']);
				}
			}break;
		
			case 'add':{
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->union($polywkt1, $polywkt2, 'svg');
					$result .= '||';
					$result .= $this->union($polywkt1, $polywkt2, 'wkt');
				}
				else{
					$result = $this->union($polywkt1, $polywkt2, $formvars['resulttype']);
				}
			}break;
			
			case 'subtract':{
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->difference($polywkt1, $polywkt2, 'svg');
					$result .= '||';
					$result .= $this->difference($polywkt1, $polywkt2, 'wkt');
				}
				else{
					$result = $this->difference($polywkt1, $polywkt2, $formvars['resulttype']);
				}
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
				$querygeometryWKT = $this->queryMap($formvars['input_coord'], $formvars['pixsize'], $formvars['layer_id'], $formvars['fromwhere'], $formvars['columnname']);
				if($querygeometryWKT == ''){
					break;
				}
				if($polywkt1 == ''){
					$polywkt1 = $querygeometryWKT;
				}
				if($formvars['resulttype'] == 'svgwkt'){
					$result = $this->union($polywkt1, $querygeometryWKT, 'svg');
					$result .= '||';
					$result .= $this->union($polywkt1, $querygeometryWKT, 'wkt');
				}
				else{
					$result = $this->union($polywkt1, $polywkt2, $formvars['resulttype']);
				}
			}break;
			
			case 'subtract_geometry':{
				$querygeometryWKT = $this->queryMap($formvars['input_coord'], $formvars['pixsize'], $formvars['layer_id'], $formvars['fromwhere'], $formvars['columnname']);
				if($querygeometryWKT == ''){
					break;
				}
				if($polywkt1 == ''){
					$polywkt1 = $querygeometryWKT;
				}
				$diff = $polywkt1;
				if($formvars['resulttype'] == 'svgwkt'){
					//$result = $this->difference($diff, $querygeometryWKT, 'svg');
					$result = $this->difference($diff, $querygeometryWKT, 'svg');
					$result .= '||';
					//$result .= $this->difference($diff, $querygeometryWKT, 'wkt');
					$result .= $this->difference($diff, $querygeometryWKT, 'wkt');
				}
				else{
					$result = $this->difference($diff, $polywkt2, $formvars['resulttype']);
				}
			}break;
			
		}
		if(!in_array($formvars['operation'], array('area', 'length', 'transformPoint', 'transform'))){
			if($formvars['resulttype'] != 'wkt'){
				$result = $this->transformCoordsSVG($result);
			}
			$result .= '~update_geometry();';
		}
		echo $result;
	}
	
	function queryMap($input_coord, $pixsize, $layer_id, $fromwhere, $columnname) {
		# pixsize wird übergeben, weil sie aus dem Geometrieeditor anders sein kann, da es dort eine andere Kartengröße geben kann
    # Abfragebereich berechnen
    $corners=explode(';',$input_coord);
    $lo=explode(',',$corners[0]); # linke obere Ecke in Bildkoordinaten von links oben gesehen
    $ru=explode(',',$corners[1]); # reche untere Ecke des Auswahlbereiches in Bildkoordinaten von links oben gesehen
    $width=$pixsize*($ru[0]-$lo[0]); # Breite des Auswahlbereiches in m
    $height=$pixsize*($ru[1]-$lo[1]); # H�he des Auswahlbereiches in m
    #echo 'Abfragerechteck im Bild: '.$lo[0].' '.$lo[1].' '.$ru[0].' '.$ru[1];
    # linke obere Ecke im Koordinatensystem in m
    $minx=$this->rolle->oGeorefExt->minx+$pixsize*$lo[0]; # x Wert
    $miny=$this->rolle->oGeorefExt->miny+$pixsize*($this->rolle->nImageHeight-$ru[1]); # y Wert
    $maxx=$minx+$width;
    $maxy=$miny+$height;
    $rect=ms_newRectObj();
    $rect->setextent($minx,$miny,$maxx,$maxy);
    $geom = $this->getgeometrybyquery($rect, $layer_id, $fromwhere, $columnname);
    return $geom;
  }
  
  function buffer($geom_1, $type, $width){
  	if(substr_count($geom_1, ',') == 1){			# wenn Polygon nur aus einem Eckpunkt besteht -> in POINT umwandeln -> Kreis entsteht
  		$geom_1 = $this->pointfrompolygon($geom_1);
  	}
  	if($type == 'wkt'){
  		$sql = "select st_astext(st_buffer(st_geomfromtext('".$geom_1."'), ".$width."))";
  	}
  	else{
  		$sql = "select st_assvg(st_buffer(st_geomfromtext('".$geom_1."'), ".$width."),0,5)";
  	}
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }
  
  function buffer_ring($geom_1, $type, $width){
  	if($type == 'wkt'){
  		$sql = "select st_astext(st_difference(st_buffer(st_geomfromtext('".$geom_1."'), ".$width."), st_geomfromtext('".$geom_1."')))";
  	}
  	else{
  		$sql = "select st_assvg(st_difference(st_buffer(st_geomfromtext('".$geom_1."'), ".$width."), st_geomfromtext('".$geom_1."')),0,5)";
  	}
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }
	
	function add_buffered_line($geom_1, $geom_2, $type, $width){
  	if(substr_count($geom_2, ',') == 0){			# wenn Linestring nur aus einem Eckpunkt besteht -> in POINT umwandeln -> Kreis entsteht
  		$geom_2 = $this->pointfromlinestring($geom_2);
  	}
		if($geom_1 == ''){
			$geom_1 = 'GEOMETRYCOLLECTION EMPTY';
		}
  	if($type == 'wkt'){
  		//$sql = "SELECT st_astext(validize_polygon(st_union(st_geomfromtext('".$geom_1."'), st_geomfromtext('".$geom_2."'))))";
  		$sql = "SELECT st_astext(ST_SnapToGrid(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), ST_SnapToGrid(st_buffer(st_geomfromtext('".$geom_2."'), ".$width."), 0.0001)), 0.0001))";
  	}
  	else{
  		//$sql = "SELECT st_assvg(validize_polygon(st_union(st_geomfromtext('".$geom_1."'), st_geomfromtext('".$geom_2."'))),0,8)";
  		$sql = "SELECT st_assvg(ST_SnapToGrid(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), ST_SnapToGrid(st_buffer(st_geomfromtext('".$geom_2."'), ".$width."), 0.0001)), 0.0001),0,8)";
  	}
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }
	
	function add_parallel_polygon($geom_1, $geom_2, $type, $width){
		if($geom_1 == ''){
			$geom_1 = 'GEOMETRYCOLLECTION EMPTY';
		}
  	if($type == 'wkt'){
  		//$sql = "SELECT st_astext(validize_polygon(st_union(st_geomfromtext('".$geom_1."'), st_geomfromtext('".$geom_2."'))))";
  		//$sql = "SELECT st_astext(ST_SnapToGrid(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), st_concavehull(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_2."'), 0.0001), ST_SnapToGrid(st_offsetcurve(st_geomfromtext('".$geom_2."'), ".$width."), 0.0001)), 0.96)), 0.0001))";
			$sql = "SELECT st_astext(ST_SnapToGrid(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), geom), 0.0001)) FROM st_dump((SELECT ST_Polygonize(st_union(ST_Boundary(ST_Buffer(the_geom, ".$width.", 'endcap=flat join=round')), the_geom)) AS buffer_sides FROM (SELECT ST_GeomFromText('".$geom_2."') AS the_geom) AS table1));";
  	}
  	else{
  		//$sql = "SELECT st_assvg(validize_polygon(st_union(st_geomfromtext('".$geom_1."'), st_geomfromtext('".$geom_2."'))),0,8)";
  		//$sql = "SELECT st_assvg(ST_SnapToGrid(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), st_concavehull(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_2."'), 0.0001), ST_SnapToGrid(st_offsetcurve(st_geomfromtext('".$geom_2."'), ".$width."), 0.0001)), 0.96)), 0.0001),0,8)";
			$sql = "SELECT st_assvg(ST_SnapToGrid(st_union(ST_SnapToGrid(st_geomfromtext('".$geom_1."'), 0.0001), geom), 0.0001),0,8) FROM st_dump((SELECT ST_Polygonize(st_union(ST_Boundary(ST_Buffer(the_geom, ".$width.", 'endcap=flat join=round')), the_geom)) AS buffer_sides FROM (SELECT ST_GeomFromText('".$geom_2."') AS the_geom) AS table1));";
  	}
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgef�hrt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }

  
  function get_closest_line($input_coord, $type, $fromwhere){
  	$coord1 = explode(';',$input_coord);
  	$coord2 = explode(',',$coord1[0]);
  	$worldx = $this->rolle->oGeorefExt->minx+$this->rolle->pixsize*$coord2[0]; # x Wert
    $worldy = $this->rolle->oGeorefExt->miny+$this->rolle->pixsize*($this->rolle->nImageHeight-$coord2[1]); # y Wert
    $point = 'POINT('.$worldx.' '.$worldy.')';  	
    
  	if($type == 'wkt'){
  		$sql = "SELECT '".$point."'";
  	}																				# wkt liefert nur den Punkt, svg die Linie
  	else{
  		$sql = "select st_assvg(snapline(linefrompoly(the_geom),st_geomfromtext('".$point."',2398)),0,5) AS Segment ";
  	}
 		$sql .= $fromwhere;
  	$sql .= " AND st_within(st_geomfromtext('".$point."',2398), the_geom)"; 	
  	$ret = $this->pgdatabase->execSQL($sql,4, 0);
    if ($ret[0]) {
      $rs = '\nAuf Grund eines Datenbankfehlers konnte die Operation nicht durchgeführt werden!\n'.$ret[1];
    }
    else {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs[0];
  }
  
  function getgeometrybyquery($rect, $layer_id, $fromwhere, $columnname) {
  	$dbmap = new db_mapObj($this->rolle->stelle_id, $this->rolle->user_id);
  	if($layer_id != ''){
    	if($layer_id < 0){	# Rollenlayer
				$layerset = $dbmap->read_RollenLayer(-$layer_id);
    	}
    	else{	# normaler Layer
	    	$layerset = $this->rolle->getLayer($layer_id);
	    }
    }
    else return NULL;
    switch ($layerset[0]['toleranceunits']) {
      case 'pixels' : $pixsize=$this->rolle->pixsize; break;
      case 'meters' : $pixsize=1; break;
      default : $pixsize=$this->rolle->pixsize;
    }
    $rand=$layerset[0]['tolerance']*$pixsize;
    
    switch ($layerset[0]['connectiontype']){
    	case 6 : {
	      #Abfrage eines postgislayers
	      # Aktueller EPSG in der die Abfrage ausgef�hrt wurde
	      $client_epsg=$this->rolle->epsg_code;
	      # EPSG-Code des Layers der Abgefragt werden soll
	      $layer_epsg=$layerset[0]['epsg_code'];
	      # Bildung der Where-Klausel f�r die r�umliche Abfrage mit der searchbox
	      $searchbox_wkt ="POLYGON((";
	      $searchbox_wkt.=strval($rect->minx)." ".strval($rect->miny).",";
	      $searchbox_wkt.=strval($rect->maxx)." ".strval($rect->miny).",";
	      $searchbox_wkt.=strval($rect->maxx)." ".strval($rect->maxy).",";
	      $searchbox_wkt.=strval($rect->minx)." ".strval($rect->maxy).",";
	      $searchbox_wkt.=strval($rect->minx)." ".strval($rect->miny)."))";
	      
	      if($columnname == ''){
	      	$columnname = 'the_geom';
	      }
	      
	      # Wenn das Koordinatenssystem des Views anders ist als vom Layer wird die Suchbox und die Suchgeometrie
	      # in epsg des layers transformiert
	      if ($client_epsg!=$layer_epsg) {
	        $sql_where =" AND ".$columnname." && st_transform(st_geomfromtext('".$searchbox_wkt."',".$client_epsg."),".$layer_epsg.")";
	      }
	      else {
	        $sql_where =" AND ".$columnname." && st_geomfromtext('".$searchbox_wkt."',".$client_epsg.")";
	      }
	      
	      # Wenn es sich bei der Suche um eine punktuelle Suche handelt, wird die where Klausel um eine
	      # Umkreissuche mit dem Suchradius weiter eingeschr�nkt.
	      if ($rect->minx==$rect->maxx AND $rect->miny==$rect->maxy) {
	        # Behandlung der Suchanfrage mit Punkt, exakte Suche im Kreis
	        if ($client_epsg!=$layer_epsg) {
	          $sql_where.=" AND st_distance(".$columnname.",st_transform(st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."),".$layer_epsg."))";
	        }
	        else {
	          $sql_where.=" AND st_distance(".$columnname.",st_geomfromtext('POINT(".$rect->minx." ".$rect->miny.")',".$client_epsg."))";
	        }
	        $sql_where.=" <= ".$rand;
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
	      # 2006-06-12 sr   Filter zur Where-Klausel hinzugef�gt
	      if($layerset[0]['Filter'] != ''){
	      	$layerset[0]['Filter'] = str_replace('$userid', $this->rolle->user_id, $layerset[0]['Filter']);
	        $sql_where .= " AND ".$layerset[0]['Filter'];
	      }
	      # Ersetzen des Platzhalters f�r die r�umliche Einschr�nkung der Sachdatenabfrage
	      # durch die Geometrie des Abfragefensters
	   
	   			   
	 			if($fromwhere != ''){
	 				if ($client_epsg!=$layer_epsg) {
		        $sql = "SELECT st_astext(st_transform(st_union(".$columnname."),".$client_epsg.")) AS geomwkt ".$fromwhere." ".$sql_where;
		      }
		      else {
		        $sql = "SELECT st_astext(st_union(".$columnname.")) AS geomwkt ".$fromwhere." ".$sql_where;
		      }  
	 			}
	   
	   		# order by wieder einbauen
        $sql .= $layerset[0]['attributes']['orderby'];
	   
	      # Anh�ngen des Begrenzers zur Einschr�nkung der Anzahl der Ergebniszeilen
	      $sql.=' LIMIT '.MAXQUERYROWS;
	      $ret=$this->pgdatabase->execSQL($sql,4, 0);
	      #echo $sql;
	      if (!$ret[0]) {
	        while ($rs=pg_fetch_array($ret[1])) {
	          $layerset[0]['shape'][]=$rs;
	        }
	      }
	      return $layerset[0]['shape'][0]['geomwkt'];
    	}break; # ende Layer ist aus postgis
    	
    	case 9 : {
    		# Abfrage eines WFS-Layers
		    $projFROM = ms_newprojectionobj("init=epsg:".$this->rolle->epsg_code);
        $projTO = ms_newprojectionobj("init=epsg:".$layerset[0]['epsg_code']);
    		$rect->project($projFROM, $projTO);
    		$searchbox_minx=strval($rect->minx-$rand);
	      $searchbox_miny=strval($rect->miny-$rand);
	      $searchbox_maxx=strval($rect->maxx+$rand);
	      $searchbox_maxy=strval($rect->maxy+$rand);
	      $request = $layerset[0]['connection'].'&service=wfs&version=1.0.0&request=getfeature&typename='.$layerset[0]['wms_name'].'&bbox='.$searchbox_minx.','.$searchbox_miny.','.$searchbox_maxx.','.$searchbox_maxy;
        $this->debug->write("<br>WFS-Request: ".$request,4);
	      $gml = url_get_contents($request);
        #$this->debug->write("<br>WFS-Response: ".$gml,4);
	      $wkt = $this->composeMultipolygonWKTStringFromGML($gml, $layerset[0]['wfs_geom']);
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
  
  function composeMultilineWKTStringFromSVGPath($path){
  	$WKT = 'MULTILINESTRING(';
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
  	if($geom_attribute != ''){			# Attribut welches die Geometrie repr�sentiert (erforderlich, wenn es mehrere Geometrien pro Feature gibt)
	  	$start = strpos($gml, '<'.$geom_attribute.'>');
	  	$end = strpos($gml, '</'.$geom_attribute.'>');
	  	$geom = substr($gml, $start, $end-$start);
  	}
  	else{
  		$geom = $gml;
  	}
    $polygons = explode('<gml:Polygon', $geom);
    for($i = 1; $i < count($polygons); $i++){
    	$wkt_polygon[$i-1] = 'geomfromtext(\'POLYGON(';
    	$rings = explode('<gml:coordinates', $polygons[$i]);
      for($j = 1; $j < count($rings); $j++){
      	if($j > 1){$wkt_polygon[$i-1] .= ',';}
      	$wkt_polygon[$i-1] .= '(';
      	$start = strpos($rings[$j], '>')+1;
      	$end = strpos($rings[$j], '</gml:coordinates>');
      	$coords = substr($rings[$j], $start, $end-$start);
      	$coords = str_replace(' ', '_', trim($coords));
      	$coords = str_replace(',', ' ', $coords);
      	$coords = str_replace('_', ',', $coords);
      	$wkt_polygon[$i-1] .= $coords;
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
	 
}

?>