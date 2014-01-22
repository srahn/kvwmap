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
  	$sql = 'SELECT MIN(st_xmin(st_envelope(st_transform(the_geom, '.$this->clientepsg.')))) AS minx, MAX(st_xmax(st_envelope(st_transform(the_geom, '.$this->clientepsg.')))) AS maxx';
    $sql.= ', MIN(st_ymin(st_envelope(st_transform(the_geom, '.$this->clientepsg.')))) AS miny, MAX(st_ymax(st_envelope(st_transform(the_geom, '.$this->clientepsg.')))) AS maxy';
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
		$sql = "SELECT jb.oid, jb.id, jb.name, jb.art FROM jagdpaechter2bezirke as jpb, jagdbezirke jb WHERE CAST(jpb.bezirkid AS text) = jb.concode AND jpb.paechterid = ".(int)$paechterid;
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
			$sql.= " WHERE oid = ".(int)$oid;
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
  	$valid[0] = 't';
  	if($umring != ''){
	  	$sql = "SELECT st_IsValid(st_geometryfromtext('".$umring."', ".$this->clientepsg."))";
	  	$ret = $this->database->execSQL($sql, 4, 0);
	  	$valid = pg_fetch_array($ret[1]);
  	}
  	if($valid[0] == 't'){
			if($oid != ''){
				$sql = "UPDATE jagdbezirke SET";
				if($umring != ''){$sql.= " the_geom = st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".$this->layerepsg."),";}
				$sql.= " name = '".$name."',";
				$sql.= " flaeche = ".(float)$flaeche.",";
				$sql.= " jb_zuordnung = '".$jb_zuordnung."',";
				$sql.= " status = '".$status."',";
				$sql.= " verzicht = '".$verzicht."',";
				$sql.= " art = '".$art."'";
				$sql.= " WHERE oid = ".(int)$oid;
			}
			else{
				if($umring != ''){
					$sql = "INSERT INTO jagdbezirke (id, the_geom, name, art, flaeche, jb_zuordnung, status, verzicht)";
					$sql.= " VALUES('".$nummer."', st_transform(st_geometryfromtext('".$umring."', ".$this->clientepsg."), ".$this->layerepsg."), '".$name."', '".$art."', ".$flaeche.", '".$jb_zuordnung."', '".$status."', '".$verzicht."')";
				}
				else{
					$sql = "INSERT INTO jagdbezirke (id, name, art, flaeche, jb_zuordnung, status, verzicht)";
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
	    $ret[1]='\nDie Flaeche konnte nicht eingetragen werden, da sie fehlerhaft ist!\n';
    }
    return $ret;
  }

	function getjagdbezirk($oid){
		$sql = "SELECT oid, *, st_assvg(st_transform(the_geom, ".$this->clientepsg."), 0, 8) AS svggeom, st_astext(st_transform(the_geom, ".$this->clientepsg.")) AS wktgeom FROM jagdbezirke WHERE oid = ".(int)$oid;
		#echo $sql;
		$ret = $this->database->execSQL($sql, 4, 0);
		$jagdbezirk = pg_fetch_array($ret[1]);
		return $jagdbezirk;
	}

	function deletejagdbezirk($oid){
		$sql = "DELETE FROM jagdbezirke WHERE oid = ".(int)$oid;
		$ret = $this->database->execSQL($sql, 4, 1);
	}
	
	function copyjagdbezirk($oid){
		$sql = "INSERT INTO jagdbezirke SELECT * FROM jagdbezirke WHERE oid = ".(int)$oid;
		$ret = $this->database->execSQL($sql, 4, 1);
		return pg_last_oid($ret[1]);
	}

	function getEigentuemerListe($formvars){
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
		$ret = $this->database->getEigentuemerListeFromJagdbezirke($oids);
		while($rs = pg_fetch_array($ret[1])){
			$summe = $summe + $rs['albflaeche'];
			$eigentuemer[] = $rs;
		}
		$eigentuemer['albsumme'] = $summe;
		return $eigentuemer;
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
		$ret = $this->database->getIntersectedFlurstWithJagdbezirke($oids);
		while($rs = pg_fetch_array($ret[1])){
			$rs['anteil'] = round($rs['schnittflaeche'] * 100 / $rs['flurstflaeche'], 2);
			$rs['albflaeche'] = round($rs['albflaeche'], 2);
      if ($rs['nenner']!='') {
        $rs['nenner']="/".$rs['nenner'];
      }
			$rs['zaehlernenner'] = $rs['zaehler'].$rs['nenner'];
			
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
        	$sql ="SELECT st_astext(st_union(the_geom)) FROM (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflst as f";
      	}
      	elseif($type == 'svg'){
      		$sql ="SELECT st_assvg(st_union(the_geom),0,8) FROM (select o.objnr as oid,o.the_geom from alkobj_e_fla AS o,alknflst as f";
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
