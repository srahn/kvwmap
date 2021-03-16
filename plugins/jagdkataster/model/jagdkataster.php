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
# Klasse jagdkataster #
#############################

class jagdkataster {

  ################### Liste der Funktionen ########################################################################################################
  # jagdkataster($database)
  ##################################################################################################################################################

  function __construct($database, $layer) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
		$this->oid_column = $layer[0]['oid'];
  }

  function zoomTojagdbezirk($oid, $border) {
  	$sql = 'SELECT MIN(st_xmin(st_envelope(st_transform(the_geom, '.$this->clientepsg.')))) AS minx, MAX(st_xmax(st_envelope(st_transform(the_geom, '.$this->clientepsg.')))) AS maxx';
    $sql.= ', MIN(st_ymin(st_envelope(st_transform(the_geom, '.$this->clientepsg.')))) AS miny, MAX(st_ymax(st_envelope(st_transform(the_geom, '.$this->clientepsg.')))) AS maxy';
    $sql.= ' FROM jagdkataster.jagdbezirke WHERE ' . $this->oid_column . ' = ' . $oid;
    $ret = $this->database->execSQL($sql, 4, 0);
		$rs = pg_fetch_array($ret[1]);
		$rect = ms_newRectObj();
    $rect->minx=$rs['minx'];
    $rect->maxx=$rs['maxx'];
    $rect->miny=$rs['miny'];
    $rect->maxy=$rs['maxy'];
    $randx=($rect->maxx-$rect->minx)*$border/100;
    $randy=($rect->maxy-$rect->miny)*$border/100;
    $rect->minx -= $randx;
    $rect->miny -= $randy;
    $rect->maxx += $randx;
    $rect->maxy += $randy;
    return $rect;
  }

  function suchen($formvars){
  	$sql = 'SELECT * FROM jagdkataster.jagdbezirke WHERE (1 = 1)';
  	if($formvars['search_nummer']){
  		$sql.= ' AND (id like \''.$formvars['search_nummer'].'\'';
  		$sql.= ' OR jb_zuordnung like \''.$formvars['search_nummer'].'\')';  		
  	}
  	if($formvars['jagd_search_name']){
  		$sql.= ' AND lower(name) like lower(\''.$formvars['jagd_search_name'].'\')';
  	}
  	if($formvars['search_art']){
  		$sql.= ' AND art = \''.$formvars['search_art'].'\'';
  	}
  	if($formvars['search_art']!='' AND $formvars['search_art']!='ejb' AND $formvars['search_art']!='gjb' AND $formvars['search_status']!='both'){
	  	$sql.= ' AND status = \''.$formvars['search_status'].'\'';
	  }
  	if($formvars['search_art']=='ejb' AND $formvars['search_verzicht']!='both' AND $formvars['search_verzicht']!=''){
	  	$sql.= ' AND verzicht = \''.$formvars['search_verzicht'].'\'';
	  }
	  if($formvars['order'] != '')$sql.= ' order by '.$formvars['order'];	  	
  	#echo $sql;
  	$ret = $this->database->execSQL($sql, 4, 0);
		while($rs = pg_fetch_array($ret[1])){
			$jagdbezirke[] = $rs;
		}
		return $jagdbezirke;
  }

  function pruefeEingabedaten($newpathwkt,$nummer) {
    $ret[1]='';
    $ret[0]=0;
    #if ( $newpathwkt == ''){
    #  $ret[1]='\nEs muss ein Polygon mit Flaecheninhalt beschrieben werden!';
    #  $ret[0]=1;
    #}
    if((in_array($this->jagdbezirk['art'], array('jbe', 'jbf', 'agf', 'atf'))) AND $nummer == ''){
    $ret[1]='\nGeben Sie eine Nummer an!';
      $ret[0]=1;
    }
    return $ret;
  }

  function get_paechter($jagdbezirk){
  	if ($jagdbezirk!='') {
  	  #$sql = "SELECT * FROM jagdkataster.jagdpaechter2bezirke as jpb, jagdkataster.jagdpaechter as jp WHERE jpb.paechterid = jp.id AND jpb.bezirkid = ".$jagdbezirk." ORDER BY jp.nachname ASC";
  	  $sql = "
			SELECT 
				jp.* 
			FROM 
				jagdkataster.jagdpaechter2bezirke as jpb, 
				jagdkataster.jagdpaechter as jp, 
				jagdkataster.jagdbezirke jb 
			WHERE 
				jpb.paechterid = jp.id AND 
				CAST(jpb.bezirkid AS text) = jb.concode AND 
				jb.gid= '".$jagdbezirk."' 
			order by jp.nachname;";
  	  #echo $sql;
  	  $ret = $this->database->execSQL($sql, 4, 0);
	  	while($rs = pg_fetch_array($ret[1])){
	  		$paechter[] = $rs;
	 	}
		return $paechter;
    }
  }

	function getjagdbezirkfrompaechter($paechterid){
		$sql = "
			SELECT 
				jb.gid,
				jb.gid as jagdbezirke_oid, 
				jb.id, 
				jb.name, 
				jb.art 
			FROM 
				jagdkataster.jagdpaechter2bezirke as jpb, 
				jagdkataster.jagdbezirke jb 
			WHERE 
				CAST(jpb.bezirkid AS text) = jb.concode 
				AND jpb.paechterid = ".sprintf("%.0f", $paechterid);
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		while($rs = pg_fetch_array($ret[1])){
		  $bezirkliste[] = $rs;
		}
		return $bezirkliste;
	}

	function eintragenNeueDaten($id, $name, $art, $oid){
		if($oid != ''){
			$sql = "UPDATE jagdkataster.jagdbezirke SET";
			$sql.= " id = '".$id."',";
			$sql.= " name = '".$name."',";
			$sql.= " art = '".$art."'";
			$sql.= " WHERE " . $this->oid_column . " = ".sprintf("%.0f", $oid);
		}
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 1);
		if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
    }
    return $ret;
  }

  function eintragenNeueFlaeche($umring, $nummer, $name, $art, $flaeche, $jb_zuordnung, $status, $verzicht, $oid = ''){
  	if($umring != ''){
	  	$sql = "SELECT st_IsValidReason(st_geometryfromtext('".$umring."', ".$this->clientepsg."))";
	  	$ret = $this->database->execSQL($sql, 4, 0);
	  	$valid = pg_fetch_array($ret[1]);
  	}
  	if($valid[0] == 'Valid Geometry'){
			if($oid != ''){
				$sql = "UPDATE jagdkataster.jagdbezirke SET";
				if($umring != ''){$sql.= " the_geom = st_multi(st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".$this->layerepsg.")),";}
				$sql.= " id = '".$nummer."',";
				$sql.= " name = '".$name."',";
				$sql.= " flaeche = ".(float)$flaeche.",";
				$sql.= " jb_zuordnung = '".$jb_zuordnung."',";
				$sql.= " status = '".$status."',";
				$sql.= " verzicht = '".$verzicht."',";
				$sql.= " art = '".$art."'";
				$sql.= " WHERE " . $this->oid_column . " = ".sprintf("%.0f", $oid);
			}
			else{
				if($umring != ''){
					$sql = "INSERT INTO jagdkataster.jagdbezirke (id, the_geom, name, art, flaeche, jb_zuordnung, status, verzicht)";
					$sql.= " VALUES('".$nummer."', st_multi(st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".$this->layerepsg.")), '".$name."', '".$art."', ".$flaeche.", '".$jb_zuordnung."', '".$status."', '".$verzicht."')";
				}
				else{
					$sql = "INSERT INTO jagdkataster.jagdbezirke (id, name, art, flaeche, jb_zuordnung, status, verzicht)";
					$sql.= " VALUES('".$nummer."', '".$name."', '".$art."', ".$flaeche.", '".$jb_zuordnung."', '".$status."', '".$verzicht."')";
				}
			}
			#echo $sql;
			$ret = $this->database->execSQL($sql, 4, 1);
			if ($ret[0]) {
	      # Fehler beim Eintragen in Datenbank
	      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
	    }
  	}
    else{
    	# Fehlerhafte Geometrie
    	$ret[0] = 1;
	    $ret[1]='\nDie Flaeche konnte nicht eingetragen werden, da sie fehlerhaft ist!\n\n'.$valid[0];
    }
    return $ret;
  }

	function getjagdbezirk($oid){
		$sql = "
			SELECT 
				*, 
				gid as jagdbezirke_oid,
				gid as jagdbezirk_paechter_oid,
				st_assvg(st_transform(the_geom, ".$this->clientepsg."), 0, 8) AS svggeom, 
				st_astext(st_transform(the_geom, ".$this->clientepsg.")) AS wktgeom 
			FROM 
				jagdkataster.jagdbezirke 
			WHERE 
				" . $this->oid_column . " = ".sprintf("%.0f", $oid);
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		$jagdbezirk = pg_fetch_array($ret[1]);
		return $jagdbezirk;
	}

	function deletejagdbezirk($formvars){
		if($formvars['oid'] == ''){		# mehrere Jagdbezirke
			$checkbox_names = explode('|', $formvars['checkbox_names_'.$formvars['chosen_layer_id']]);
	    for($i = 0; $i < count($checkbox_names); $i++){
	      if($formvars[$checkbox_names[$i]] == 'on'){
	        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
					$oids[] = $element[3];
	      }
	    }
		}
		else{				# ein Jagdbezirk
			$oids[] = $formvars['oid']; 
		}
		$sql = "
			DELETE FROM 
				jagdkataster.jagdbezirke 
			WHERE 
				" . $this->oid_column . " IN (".implode(',', $oids).")";
		$ret = $this->database->execSQL($sql, 4, 1);
	}
	
	function copyjagdbezirk($oid){
		$sql = "
			INSERT INTO 
				jagdkataster.jagdbezirke 
			SELECT 
				* 
			FROM 
				jagdkataster.jagdbezirke 
			WHERE 
				" . $this->oid_column . " = ".sprintf("%.0f", $oid);
		$ret = $this->database->execSQL($sql, 4, 1);
		return pg_last_oid($ret[1]);
	}

	function getEigentuemerListe($formvars){
		if($formvars['oid'] == ''){		# mehrere Jagdbezirke
			$checkbox_names = explode('|', $formvars['checkbox_names']);
	    for($i = 0; $i < count($checkbox_names); $i++){
	      if($formvars[$checkbox_names[$i]] == 'on'){
	        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
					$oids[] = $element[3];
	      }
	    }
		}
		else{				# ein Jagdbezirk
			$oids[] = $formvars['oid']; 
		}
		$ret = $this->getEigentuemerListeFromJagdbezirke($oids);
		while($rs = pg_fetch_array($ret[1])){
			$summe = $summe + $rs['albflaeche'];
			$eigentuemer[] = $rs;
		}
		$eigentuemer['albsumme'] = $summe;
		return $eigentuemer;
	}
		
	function getIntersectedFlurst($formvars){
		if($formvars['oid'] == ''){		# mehrere Jagdbezirke
			$checkbox_names = explode('|', $formvars['checkbox_names_'.$formvars['chosen_layer_id']]);
	    for($i = 0; $i < count($checkbox_names); $i++){
	      if($formvars[$checkbox_names[$i]] == 'on'){
	        $element = explode(';', $checkbox_names[$i]);     #  check;table_alias;table;oid
					$oids[] = $element[3];
	      }
	    }
		}
		else{				# ein Jagdbezirk
			$oids[] = $formvars['oid']; 
		}
		$ret = $this->getIntersectedFlurstWithJagdbezirke($oids);
		while($rs = pg_fetch_array($ret[1])){
			$rs['anteil'] = round($rs['schnittflaeche'] * 100 / $rs['flurstflaeche'], 2);
			$rs['schnittflaeche'] = $rs['schnittflaeche'] / $rs['flurstflaeche'] * $rs['albflaeche'];
			$rs['albflaeche'] = round($rs['albflaeche'], 2);
      $rs['zaehlernenner'] = $rs['zaehler'];
			if($rs['nenner'] != '')$rs['zaehlernenner'] .= '/'.$rs['nenner'];
			
			# --- Eigentümer ---
			$flst = new flurstueck($rs['flurstkennz'], $this->database);
			$flst->Grundbuecher=$flst->getGrundbuecher();
			#for($g = 0; $g < count($flst->Grundbuecher); $g++){
      	#$flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
				$flst->Buchungen=$flst->getBuchungen(NULL,NULL,0);
      	for($b = 0; $b < count($flst->Buchungen); $b++){
	        $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
	        foreach($Eigentuemerliste as $eigentuemer){
						if($eigentuemer->Nr != ''){
							$rs['eigentuemer'][] = $eigentuemer->vorname.' '.$eigentuemer->nachnameoderfirma;						
							$rs['eigentuemer_nr'][] = $eigentuemer->Nr;
						}
	        }
      	}
				if($flst->Grundbuecher[$g]['zusatz_eigentuemer'] != ''){
					$rs['eigentuemer'][] = $flst->Grundbuecher[$g]['zusatz_eigentuemer'];						
					$rs['eigentuemer_nr'][] = ' - ';
				}
			#}
			# --- Eigentümer ---
			$flurstuecke[] = $rs;
		}
		return $flurstuecke;
	}

	function getflurstBBox($FlurstListe, $epsgcode) {
    $alk=new ALK();
    $alk->database=$this->database;
    $ret=$alk->getMERfromFlurstuecke($FlurstListe,$epsgcode);
    if ($ret[0]) {
      $this->Fehlermeldung='Es konnten keine Flurstücke gefunden werden.<br>'.$ret[1];
    }
    else {
      $rect=$ret[1];
    }
    return $rect;
  }
	
	function getIntersectedFlurstWithJagdbezirke($oids){
		$sql = "
			SELECT 
				f.land||f.gemarkungsnummer as gemkgschl, 
				f.flurnummer as flur, 
				f.zaehler, 
				f.nenner, 
				g.bezeichnung as gemkgname, 
				f.flurstueckskennzeichen as flurstkennz, 
				st_area_utm(f.wkb_geometry, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.") AS flurstflaeche, 
				st_area_utm(st_intersection(f.wkb_geometry, st_transform(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.")), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.") AS schnittflaeche, 
				jagdbezirke.name, 
				jagdbezirke.art, 
				f.amtlicheflaeche AS albflaeche 
			FROM 
				alkis.ax_gemarkung AS g, 
				jagdkataster.jagdbezirke, 
				alkis.ax_flurstueck AS f
			WHERE 
				f.gemarkungsnummer = g.gemarkungsnummer AND 
				jagdbezirke." . $this->oid_column . " IN (".implode(',', $oids).") AND 
				f.wkb_geometry && st_transform(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.") AND 
				st_intersects(f.wkb_geometry, st_transform(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.")) AND 
				st_area_utm(st_intersection(f.wkb_geometry, st_transform(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.")), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.") > 1 
				" . $this->database->build_temporal_filter(array('g', 'f')) . "
			ORDER BY 
				jagdbezirke.name";
		return $this->database->execSQL($sql, 4, 0);
	}
	
	function getEigentuemerListeFromJagdbezirke($oids){
		$sql = "
			SELECT 
				round((st_area_utm(st_union(the_geom_inter), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.")*100/j_flaeche)::numeric, 2) as anteil_alk, 
				round(sum(flaeche*(st_area_utm(the_geom_inter, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.")/st_area_utm(the_geom, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.")))::numeric, 1) AS albflaeche, 
				eigentuemer 
			FROM(
				SELECT distinct 
					st_area_utm(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.") as j_flaeche, 
					f.amtlicheflaeche as flaeche, 
					array_to_string(array(
						SELECT distinct 
							array_to_string(array[p.nachnameoderfirma, p.vorname], ' ') as name 
						FROM 
							alkis.ax_flurstueck ff 
							LEFT JOIN alkis.ax_buchungsstelle s ON ff.istgebucht = s.gml_id OR ARRAY[ff.gml_id::char] <@ s.verweistauf OR ARRAY[ff.istgebucht] <@ s.an 
							LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id 
							LEFT JOIN alkis.ax_buchungsblattbezirk b ON g.land = b.land AND g.bezirk = b.bezirk 
							LEFT JOIN alkis.ax_namensnummer n ON n.istbestandteilvon = g.gml_id 
							LEFT JOIN alkis.ax_eigentuemerart_namensnummer w ON w.wert = n.eigentuemerart 
							LEFT JOIN alkis.ax_person p ON n.benennt = p.gml_id 
						WHERE 
							n.laufendenummernachdin1421 IS NOT NULL AND 
							f.flurstueckskennzeichen = ff.flurstueckskennzeichen 
							" . $this->database->build_temporal_filter(array('ff', 's', 'g', 'b', 'n', 'p')) . "
						order by name),' || '
					) as eigentuemer,
					st_intersection(f.wkb_geometry, st_transform(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.")) AS the_geom_inter, 
					f.wkb_geometry as the_geom
				FROM 
					alkis.ax_gemarkung AS g, 
					jagdkataster.jagdbezirke, 
					alkis.ax_flurstueck AS f
				WHERE 
					f.gemarkungsnummer = g.gemarkungsnummer 
					" . $this->database->build_temporal_filter(array('f')) . " AND 
					jagdbezirke." . $this->oid_column . " IN (".implode(',', $oids).") AND 
					f.wkb_geometry && st_transform(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.") AND 
					st_intersects(f.wkb_geometry, st_transform(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.")) AND 
					st_area_utm(st_intersection(f.wkb_geometry, st_transform(jagdbezirke.the_geom, ".EPSGCODE_ALKIS.")), ".EPSGCODE_ALKIS.", ".EARTH_RADIUS.") > 1
			) as foo
			group by 
				eigentuemer, 
				j_flaeche";
		return $this->database->execSQL($sql, 4, 0);
	}
		
}
?>
