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
# Klasse jagdkataster #
#############################

class jagdkataster {

  ################### Liste der Funktionen ########################################################################################################
  # jagdkataster($database)
  ##################################################################################################################################################

  function jagdkataster($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }

  function zoomTojagdbezirk($oid, $border) {
  	$sql = 'SELECT MIN(XMIN(ENVELOPE(the_geom))) AS minx, MAX(XMAX(ENVELOPE(the_geom))) AS maxx';
    $sql.= ', MIN(YMIN(ENVELOPE(the_geom))) AS miny, MAX(YMAX(ENVELOPE(the_geom))) AS maxy';
    $sql.= ' FROM jagdbezirke WHERE oid = '.$oid;
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
  	$sql = 'SELECT oid, * FROM jagdbezirke WHERE (1 = 1)';
  	if($formvars['search_nummer']){
  		$sql.= ' AND (id like \''.$formvars['search_nummer'].'\'';
  		$sql.= ' OR jb_zuordnung like \''.$formvars['search_nummer'].'\')';  		
  	}
  	if($formvars['search_name']){
  		$sql.= ' AND lower(name) like lower(\''.$formvars['search_name'].'\')';
  	}
  	if($formvars['search_art']){
  		$sql.= ' AND art = \''.$formvars['search_art'].'\'';
  	}
  	if($formvars['search_art']!='' AND $formvars['search_art']!='ejb' AND $formvars['search_art']!='gjb' AND $formvars['search_status']!='both'){
	  	$sql.= ' AND status = \''.$formvars['search_status'].'\'';
	  }	  	
  	$sql.= ' LIMIT 100';
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
  	  #$sql = "SELECT * FROM jagdpaechter2bezirke as jpb, jagdpaechter as jp WHERE jpb.paechterid = jp.id AND jpb.bezirkid = ".$jagdbezirk." ORDER BY jp.nachname ASC";
  	  $sql = "SELECT jp.* FROM jagdpaechter2bezirke as jpb, jagdpaechter as jp, jagdbezirke jb WHERE jpb.paechterid = jp.id AND CAST(jpb.bezirkid AS text) = jb.concode AND jb.oid= '".$jagdbezirk."' order by jp.nachname;";
  	  #echo $sql;
  	  $ret = $this->database->execSQL($sql, 4, 0);
	  	while($rs = pg_fetch_array($ret[1])){
	  		$paechter[] = $rs;
	 	}
		return $paechter;
    }
  }

	function getjagdbezirkfrompaechter($paechterid){
		$sql = "SELECT jb.oid, jb.id, jb.name, jb.art FROM jagdpaechter2bezirke as jpb, jagdbezirke jb WHERE CAST(jpb.bezirkid AS text) = jb.concode AND jpb.paechterid = ".$paechterid;
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		while($rs = pg_fetch_array($ret[1])){
		  $bezirkliste[] = $rs;
		}
		return $bezirkliste;
	}

	function eintragenNeueDaten($id, $name, $art, $oid){
		if($oid != ''){
			$sql = "UPDATE jagdbezirke SET";
			$sql.= " id = '".$id."',";
			$sql.= " name = '".$name."',";
			$sql.= " art = '".$art."'";
			$sql.= " WHERE oid = ".$oid;
		}
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 1);
		if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Flaeche nicht eingetragen werden!\n'.$ret[1];
    }
    return $ret;
  }

  function eintragenNeueFlaeche($umring, $nummer, $name, $art, $flaeche, $jb_zuordnung, $status, $oid = ''){
  	$valid[0] = 't';
  	if($umring != ''){
	  	$sql = "SELECT IsValid(GeometryFromText('".$umring."', ".$this->clientepsg."))";
	  	$ret = $this->database->execSQL($sql, 4, 0);
	  	$valid = pg_fetch_array($ret[1]);
  	}
  	if($valid[0] == 't'){
			if($oid != ''){
				$sql = "UPDATE jagdbezirke SET";
				if($umring != ''){$sql.= " the_geom = Transform(GeometryFromText('".$umring."', ".$this->clientepsg."), ".$this->layerepsg."),";}
				$sql.= " name = '".$name."',";
				$sql.= " flaeche = ".$flaeche.",";
				$sql.= " jb_zuordnung = '".$jb_zuordnung."',";
				$sql.= " status = '".$status."',";
				$sql.= " art = '".$art."'";
				$sql.= " WHERE oid = ".$oid;
			}
			else{
				if($umring != ''){
					$sql = "INSERT INTO jagdbezirke (id, the_geom, name, art, flaeche, jb_zuordnung, status)";
					$sql.= " VALUES('".$nummer."', Transform(GeometryFromText('".$umring."', ".$this->clientepsg."), ".$this->layerepsg."), '".$name."', '".$art."', ".$flaeche.", '".$jb_zuordnung."', '".$status."')";
				}
				else{
					$sql = "INSERT INTO jagdbezirke (id, name, art, flaeche, jb_zuordnung, status)";
					$sql.= " VALUES('".$nummer."', '".$name."', '".$art."', ".$flaeche.", '".$jb_zuordnung."', '".$status."')";
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
	    $ret[1]='\nDie Flaeche konnte nicht eingetragen werden, da sie fehlerhaft ist!\n';
    }
    return $ret;
  }

	function getjagdbezirk($oid){
		$sql = "SELECT oid, *, assvg(Transform(the_geom, ".$this->clientepsg."), 0, 8) AS svggeom, astext(Transform(the_geom, ".$this->clientepsg.")) AS wktgeom FROM jagdbezirke WHERE oid = ".$oid;
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		$jagdbezirk = pg_fetch_array($ret[1]);
		return $jagdbezirk;
	}

	function deletejagdbezirk($oid){
		$sql = "DELETE FROM jagdbezirke WHERE oid = ".$oid;
		$ret = $this->database->execSQL($sql, 4, 1);
	}
	
	function copyjagdbezirk($oid){
		$sql = "INSERT INTO jagdbezirke SELECT * FROM jagdbezirke WHERE oid = ".$oid;
		$ret = $this->database->execSQL($sql, 4, 1);
		return pg_last_oid($ret[1]);
	}

	function getIntersectedFlurst($formvars){
		if($formvars['oid'] == ''){		# mehrere Jagdbezirke
			$checkbox_names = explode('|', $formvars['checkbox_names']);
	    for($i = 0; $i < count($checkbox_names); $i++){
	      if($formvars[$checkbox_names[$i]] == 'on'){
	        $element = explode('_', $checkbox_names[$i]);     #  check_oid
	        $oids[] = $element[1];
	      }
	    }
		}
		else{				# ein Jagdbezirk
			$oids[] = $formvars['oid']; 
		}
		$sql = "SELECT alb.gemkgschl, gemkgname, alb.flurstkennz, st_area(alkobj_e_fla.the_geom) AS flurstflaeche, st_area(st_intersection(alkobj_e_fla.the_geom, jagdbezirke.the_geom)) AS schnittflaeche, jagdbezirke.name, jagdbezirke.art, alb.flaeche AS albflaeche";
		$sql.= " FROM alb_v_gemarkungen, alknflst, alkobj_e_fla, jagdbezirke, alb_flurstuecke AS alb";
		$sql.= " WHERE alb_v_gemarkungen.gemkgschl = CAST(alknflst.gemkgschl AS integer) AND alknflst.objnr = alkobj_e_fla.objnr";
		$sql.= " AND jagdbezirke.oid IN (".implode(',', $oids).")";
		$sql.= " AND alkobj_e_fla.the_geom && jagdbezirke.the_geom AND intersects(alkobj_e_fla.the_geom, jagdbezirke.the_geom)";
		$sql.= " AND st_area(st_intersection(alkobj_e_fla.the_geom, jagdbezirke.the_geom)) > 1";
		$sql.= " AND alb.flurstkennz = alknflst.flurstkennz ORDER BY jagdbezirke.name";
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		while($rs = pg_fetch_array($ret[1])){
			$rs['anteil'] = round($rs['schnittflaeche'] * 100 / $rs['flurstflaeche'], 2);
			$rs['albflaeche'] = round($rs['albflaeche'], 2);
			$explosion = explode('-', $rs['flurstkennz']);
			$rs['flur'] = $explosion[1];
			$rs['zaehlernenner'] = substr($explosion[2],0,-3);
			
			
			# --- Eigentümer ---
			$flst = new flurstueck($rs['flurstkennz'], $this->database);
			$flst->Grundbuecher=$flst->getGrundbuecher();
			for($g = 0; $g < count($flst->Grundbuecher); $g++){
      	$flst->Buchungen=$flst->getBuchungen($flst->Grundbuecher[$g]['bezirk'],$flst->Grundbuecher[$g]['blatt'],0);
      	for($b = 0; $b < count($flst->Buchungen); $b++){
	        $Eigentuemerliste = $flst->getEigentuemerliste($flst->Buchungen[$b]['bezirk'],$flst->Buchungen[$b]['blatt'],$flst->Buchungen[$b]['bvnr']);
	        $anzEigentuemer=count($Eigentuemerliste);
	        for($e=0;$e<$anzEigentuemer;$e++){
	        	$rs['eigentuemer'][$e] = rtrim($Eigentuemerliste[$e]->Name[0], ',');
	        }
      	}
			}
			# --- Eigentümer ---
			
			
			$flurstuecke[] = $rs;
		}
		return $flurstuecke;
	}

	function getflurstgeometryfromnamen($formvars, $type){
		$flurstueck=new flurstueck('',$this->database);
    $ret=$flurstueck->getFlurstByLfdNrName($formvars['lfd_nr_name'],$formvars['anzahl']);
    if ($ret[0]) {
      $this->Fehlermeldung='<br>Es konnten keine Namen abgefragt werden'.$ret[1];
    }
    else {
      $this->FlurstListe = $ret[1];
      if (count($this->FlurstListe)==0) {
        $this->Fehlermeldung='<br>Es konnten keine Namen gefunden werden, bitte ändern Sie die Anfrage!';
      }
      else {
      	if($type == 'wkt'){
        	$sql ="SELECT astext(memgeomunion(the_geom)) FROM (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflst as f";
      	}
      	elseif($type == 'svg'){
      		$sql ="SELECT assvg(memgeomunion(the_geom),0,8) FROM (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflst as f";
      	}
    		$sql.=" WHERE o.objnr=f.objnr AND f.flurstkennz IN ('".$this->FlurstListe[0]."'";
    		for ($i = 1; $i < count($this->FlurstListe); $i++) {
		      $sql.=",'".$this->FlurstListe[$i]."'";
		    }
		    $sql.=")) as foo";
		    $ret = $this->database->execSQL($sql, 4, 0);
		    $geom = pg_fetch_array($ret[1]);
		    return $geom[0];
      } # ende Ergebnisanzahl größer 0
    } # ende Abfrage war erfolgreich
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
}
?>
