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
# Klasse Bodenrichtwertzone #
#############################

class bodenrichtwertzone {
    
  ################### Liste der Funktionen ########################################################################################################
  # bodenrichtwertzone($database)
  # copyZonenToNewStichtag
  # eintragenNeueZone($gemeinde_id,$zonennr,$standort,$richtwert,$bodenwert,$erschliessungsart,$sanierungsgebiete,$sichtbarkeit,$datum,$umring,$textposition)
  # getStichtage()
  # pruefeBWEingabedaten
  ##################################################################################################################################################

  function bodenrichtwertzone($database, $layer_epsg, $client_epsg) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    $this->client_epsg=$client_epsg;
    $this->layer_epsg = $layer_epsg;
  }
  
  
  function getBBoxAsRectObj($oid) {
    # ermittelt die Boundingbox der Bodenrichtwertzone $oid
    $sql ='SELECT st_xmin(st_extent(st_transform(the_geom, '.$this->client_epsg.'))) AS minx,st_ymin(st_extent(st_transform(the_geom, '.$this->client_epsg.'))) AS miny';
    $sql.=',st_xmax(st_extent(st_transform(the_geom, '.$this->client_epsg.'))) AS maxx,st_ymax(st_extent(st_transform(the_geom, '.$this->client_epsg.'))) AS maxy';
    $sql.=' FROM bw_zonen WHERE oid='.$oid;
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      $ret[1].='Fehler bei der Abfrage der Boundingbox der Bodenrichtwertzone! \n';
    }
    else {
      # Abfrage fehlerfrei
      # Erzeugen eines RectObject
      $rect= ms_newRectObj();
      # Abfragen und zuordnen der Koordinaten der Box
      $rs=pg_fetch_array($ret[1]);
      # Wenn die Box eine Kantenlänge von 0 hat, wird sie etwas aufgeweitet um 100m.
      if ($rs['maxx']-$rs['minx']==0) {
        $rs['maxx']=$rs['maxx']+100;
        $rs['minx']=$rs['minx']-100;        
      }
      if ($rs['maxy']-$rs['miny']==0) {
        $rs['maxy']=$rs['maxy']+100;
        $rs['miny']=$rs['miny']-100;        
      }
      $rect->minx=$rs['minx']; $rect->miny=$rs['miny'];
      $rect->maxx=$rs['maxx']; $rect->maxy=$rs['maxy'];
      $ret[1]=$rect;
    }
    return $ret;
  }

  function deleteBodenrichtwertzonen($oidliste){
    $this->debug->write('file:bodenrichtwerte.php class:bodenrichtwerte function:deleteBodenrichtwertzonen<br>Löschen von Bodenrichtwertzonen aus<br>PostGIS:',4);
    $sql ="DELETE FROM bw_zonen";
    $sql.=" WHERE oid IN (".$oidliste[0];
    for ($i=1;$i<count($oidliste);$i++) {
      $sql.=",".$oidliste[$i];
    }
    $sql.=")";
    $ret=$this->database->execSQL($sql,4, 1);    
    if ($ret[0]) {
      $ret[1]='Fehler beim Löschen der Bodenrichtwerte in der Datenbank.<br>'.$ret[1];
    }
    return $ret;
  }
  
  function getBodenrichtwertzonen($oid){
    # Prüfen der Suchparameter
    # Es muss ein gültiges Polygon vorhanden sein.
    $this->debug->write('file:bodenrichtwerte.php class:bodenrichtwerte function:getBodenrichtwertzonen<br>Abfragen des Umrings und der Textpunkte aus<br>PostGIS:',4);
    $sql ="SELECT *,";
    $sql.=" asText(st_transform(the_geom, ".$this->client_epsg.")) AS wkt_umring, asSVG(st_transform(the_geom, ".$this->client_epsg.")) AS svg_umring,";
    $sql .=" asText(st_transform(textposition, ".$this->client_epsg.")) AS wkt_textposition";
    $sql.=" FROM bw_zonen";
    $sql.=" WHERE 1=1";
    if ($oid!='') {
      $sql.=" AND oid=".(int)$oid;
    }
    $ret=$this->database->execSQL($sql,4, 0);    
    if ($ret[0]) {
      $errmsg.='Fehler bei der Abfrage der Daten(Bodenrichtwerte):<br>'.$ret[1];
    }
    else {
      while ($rs=pg_fetch_array($ret[1])) {
        $zonen[]=$rs;
      }
      $ret[1]=$zonen;
    }
    $this->zonen=$zonen;
    return $ret;
  }
  
  function getStichtage() {
    # Liefert alle bisher für Bodenrichtwertzonen erfassten Stichtage
    $sql ='SELECT DISTINCT stichtag FROM bw_zonen ORDER BY stichtag DESC';
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]) {
      # Fehler beim Abfragen der Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnten die Stichtage nicht abgefragt werden!\n'.$ret[1];
    }
    else {
      while ($rs=pg_fetch_array($ret[1])) {
        $stichtag[]=$rs['stichtag'];
      }
      $ret[1]=$stichtag;
    }
    return $ret;
  }
  
  function pruefeBWEingabedaten($formvars) {
    $ret[0]=0;
    # Abfragen ob umring ein geschlossenes Polygon ist & weitere formular-variabeln belegt sind. 
    if ($formvars['umring']=='') {
      $ret[1]='\nGeben Sie einen Umring an.';
      $ret[0]=1;
    }
    if ($formvars['textposition']=='') {
      $ret[1].='\nGeben Sie die Position für die Textanzeige an.';
      $ret[0]=1;
    }
    if ($formvars['bodenrichtwert']=='') {
      $ret[1].='\nGeben Sie den Bodenwert an.';
      $ret[0]=1;
    }
    if ($formvars['stichtag']=='' OR !(intval($formvars['stichtag']) > 1900)) {
      $ret[1].='\nGeben Sie die 4-stellige Jahreszahl des Stichtags an.';
      $ret[0]=1;
    }
    return $ret;
  }

  function eintragenNeueZone($formvars) {
  	$formvars['bodenrichtwert'] = str_replace(',', '.', $formvars['bodenrichtwert']);
  	$formvars['geschossflaechenzahl'] = str_replace(',', '.', $formvars['geschossflaechenzahl']);
  	$formvars['grundflaechenzahl'] = str_replace(',', '.', $formvars['grundflaechenzahl']);
  	$formvars['baumassenzahl'] = str_replace(',', '.', $formvars['baumassenzahl']);
  	$formvars['oertliche_bezeichnung'] = str_replace(chr(10), '', $formvars['oertliche_bezeichnung']);
  	$formvars['oertliche_bezeichnung'] = str_replace(chr(13), '', $formvars['oertliche_bezeichnung']);
    $this->debug->write('<br>file:bodenrichtwerte.php class:bodenrichtwertzone function eintragenNeueZone<br>Einfügen der Daten zu einer Richtwertzone in<br>PostGIS',4);
  	$sql ="INSERT INTO bw_zonen (";
  	if($formvars['stichtag']){$sql.= "stichtag";} 
		if($formvars['gemeinde']){$sql.= ",gemeinde";} 
		if($formvars['gemarkung']){$sql.= ",gemarkung";} 
		if($formvars['ortsteilname']){$sql.= ",ortsteilname";} 
		if($formvars['postleitzahl']){$sql.= ",postleitzahl";}
		if($formvars['zonentyp']){$sql.= ",zonentyp";} 
		if($formvars['gutachterausschuss']){$sql.= ",gutachterausschuss";} 
		if($formvars['bodenrichtwertnummer']){$sql.= ",bodenrichtwertnummer";} 
		if($formvars['oertliche_bezeichnung']){$sql.= ",oertliche_bezeichnung";} 
		if($formvars['bodenrichtwert']){$sql.= ",bodenrichtwert";}
  	if($formvars['bedarfswert']){$sql.= ",bedarfswert";} 
		if($formvars['basiskarte']){$sql.=",basiskarte";} 
		if($formvars['entwicklungszustand']){$sql.= ",entwicklungszustand";} 
		if($formvars['beitragszustand']){$sql.= ",beitragszustand";} 
		if($formvars['nutzungsart']){$sql.= ",nutzungsart";} 
		if($formvars['ergaenzende_nutzung']){$sql.= ",ergaenzende_nutzung";} 
		if($formvars['bauweise']){$sql.= ",bauweise";} 
		if($formvars['geschosszahl']){$sql.= ",geschosszahl";} 
		if($formvars['grundflaechenzahl']){$sql.= ",grundflaechenzahl";} 
		if($formvars['geschossflaechenzahl']){$sql.= ",geschossflaechenzahl";} 
		if($formvars['baumassenzahl']){$sql.= ",baumassenzahl";} 
		if($formvars['flaeche']){$sql.= ",flaeche";} 
		if($formvars['tiefe']){$sql.= ",tiefe";} 
		if($formvars['breite']){$sql.= ",breite";} 
		if($formvars['wegeerschliessung']){$sql.= ",wegeerschliessung";} 
  	if($formvars['erschliessung']){$sql.= ",erschliessung";}
		if($formvars['ackerzahl']){$sql.= ",ackerzahl";} 
		if($formvars['gruenlandzahl']){$sql.= ",gruenlandzahl";} 
		if($formvars['aufwuchs']){$sql.= ",aufwuchs";}
  	if($formvars['bodenart']){$sql.= ",bodenart";} 
		if($formvars['verfahrensgrund']){$sql.= ",verfahrensgrund";} 
		if($formvars['verfahrensgrund_zusatz']){$sql.= ",verfahrensgrund_zusatz";} 
		if($formvars['bemerkungen']){$sql.= ",bemerkungen";} 
  	$sql.=", the_geom, textposition) VALUES (";
  	if($formvars['stichtag']){$sql.= "'31.12.".$formvars['stichtag']."' ";}
    if($formvars['gemeinde']){$sql.= ",".$formvars['gemeinde'];}
    if($formvars['gemarkung']){$sql.= ",".$formvars['gemarkung'];}
    if($formvars['ortsteilname']){$sql.= ",'".$formvars['ortsteilname']."' ";}
    if($formvars['postleitzahl']){$sql.= ",".$formvars['postleitzahl'];}
    if($formvars['zonentyp']){$sql.= ",'".$formvars['zonentyp']."' ";}
    if($formvars['gutachterausschuss']){$sql.= ",'".$formvars['gutachterausschuss']."'";}
    if($formvars['bodenrichtwertnummer']){$sql.= ",".$formvars['bodenrichtwertnummer'];}
    if($formvars['oertliche_bezeichnung']){$sql.= ",'".$formvars['oertliche_bezeichnung']."' ";}
    if($formvars['bodenrichtwert']){$sql.= ",".$formvars['bodenrichtwert'];}
  	if($formvars['bedarfswert']){$sql.= ",".$formvars['bedarfswert'];}
    if($formvars['basiskarte']){$sql.= ",'".$formvars['basiskarte']."' ";}
    if($formvars['entwicklungszustand']){$sql.= ",'".$formvars['entwicklungszustand']."' ";}
    if($formvars['beitragszustand']){$sql.= ",'".$formvars['beitragszustand']."' ";}
    if($formvars['nutzungsart']){$sql.= ",'".$formvars['nutzungsart']."' ";}
    if($formvars['ergaenzende_nutzung']){$sql.= ",'".$formvars['ergaenzende_nutzung']."' ";}
    if($formvars['bauweise']){$sql.= ",'".$formvars['bauweise']."' ";}
    if($formvars['geschosszahl']){$sql.= ",'".$formvars['geschosszahl']."' ";}
    if($formvars['grundflaechenzahl']){$sql.= ",".$formvars['grundflaechenzahl'];}
    if($formvars['geschossflaechenzahl']){$sql.= ",".$formvars['geschossflaechenzahl'];}
    if($formvars['baumassenzahl']){$sql.= ",".$formvars['baumassenzahl'];}
    if($formvars['flaeche']){$sql.= ",'".$formvars['flaeche']."' ";}
    if($formvars['tiefe']){$sql.= ",'".$formvars['tiefe']."' ";}
    if($formvars['breite']){$sql.= ",'".$formvars['breite']."' ";}
    if($formvars['wegeerschliessung']){$sql.= ",'".$formvars['wegeerschliessung']."' ";}
  	if($formvars['erschliessung']){$sql.= ",'".$formvars['erschliessung']."' ";}
    if($formvars['ackerzahl']){$sql.= ",'".$formvars['ackerzahl']."' ";}
    if($formvars['gruenlandzahl']){$sql.= ",'".$formvars['gruenlandzahl']."' ";}
    if($formvars['aufwuchs']){$sql.= ",'".$formvars['aufwuchs']."' ";}
  	if($formvars['bodenart']){$sql.= ",'".$formvars['bodenart']."' ";}
    if($formvars['verfahrensgrund']){$sql.= ",'".$formvars['verfahrensgrund']."' ";}
    if($formvars['verfahrensgrund_zusatz']){$sql.= ",'".$formvars['verfahrensgrund_zusatz']."' ";}
    if($formvars['bemerkungen']){$sql.= ",'".$formvars['bemerkungen']."' ";}	
  	$sql.=",st_transform(st_geometryfromtext('".$formvars['umring']."',".$this->client_epsg."), ".$this->layer_epsg.")";
    $sql.=",st_transform(st_geometryfromtext('".$formvars['textposition']."',".$this->client_epsg."), ".$this->layer_epsg."))";
    # echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Zone nicht eingetragen werden!\n'.$ret[1];
    }
    return $ret; 
  }

  function aktualisierenZone($oid,$formvars) {
  	$formvars['bodenrichtwert'] = str_replace(',', '.', $formvars['bodenrichtwert']);
  	$formvars['geschossflaechenzahl'] = str_replace(',', '.', $formvars['geschossflaechenzahl']);
  	$formvars['grundflaechenzahl'] = str_replace(',', '.', $formvars['grundflaechenzahl']);
  	$formvars['baumassenzahl'] = str_replace(',', '.', $formvars['baumassenzahl']);
  	$formvars['oertliche_bezeichnung'] = str_replace(chr(10), '', $formvars['oertliche_bezeichnung']);
  	$formvars['oertliche_bezeichnung'] = str_replace(chr(13), '', $formvars['oertliche_bezeichnung']);
    $this->debug->write('<br>file:bodenrichtwerte.php class:bodenrichtwertzone function aktualisierenZone<br>Einfügen der Daten zu einer Richtwertzone in<br>PostGIS',4);
    $sql = "UPDATE bw_zonen SET ";
    if($formvars['gemeinde']){$sql.= "gemeinde = ".(int)$formvars['gemeinde'].", ";}
    if($formvars['gemarkung']){$sql.= "gemarkung = ".(int)$formvars['gemarkung'].", ";}
    if($formvars['ortsteilname']){$sql.= "ortsteilname = '".$formvars['ortsteilname']."', ";}
    if($formvars['postleitzahl']){$sql.= "postleitzahl = ".(int)$formvars['postleitzahl'].", ";}
    if($formvars['zonentyp']){$sql.= "zonentyp = '".$formvars['zonentyp']."', ";}
    if($formvars['gutachterausschuss']){$sql.= "gutachterausschuss = '".$formvars['gutachterausschuss']."', ";}
    if($formvars['bodenrichtwertnummer']){$sql.= "bodenrichtwertnummer = ".(int)$formvars['bodenrichtwertnummer'].", ";}
    if($formvars['oertliche_bezeichnung']){$sql.= "oertliche_bezeichnung = '".$formvars['oertliche_bezeichnung']."', ";}
    if($formvars['bodenrichtwert']){$sql.= "bodenrichtwert = ".(int)$formvars['bodenrichtwert'].", ";}
  	if($formvars['bedarfswert']){$sql.= "bedarfswert = ".(int)$formvars['bedarfswert'].", ";}
    if($formvars['stichtag']){$sql.= "stichtag = '31.12.".$formvars['stichtag']."', ";}
    if($formvars['basiskarte']){$sql.="basiskarte = '".$formvars['basiskarte']."', ";}
    if($formvars['entwicklungszustand']){$sql.= "entwicklungszustand = '".$formvars['entwicklungszustand']."', ";}
    if($formvars['beitragszustand']){$sql.= "beitragszustand = '".$formvars['beitragszustand']."', ";}
    if($formvars['nutzungsart']){$sql.= "nutzungsart = '".$formvars['nutzungsart']."', ";}
    if($formvars['ergaenzende_nutzung']){$sql.= "ergaenzende_nutzung = '".$formvars['ergaenzende_nutzung']."', ";}
    if($formvars['bauweise']){$sql.= "bauweise = '".$formvars['bauweise']."', ";}
    if($formvars['geschosszahl']){$sql.= "geschosszahl = '".$formvars['geschosszahl']."', ";}
    if($formvars['grundflaechenzahl'] == '')$sql.= "grundflaechenzahl = NULL, ";
    else $sql.= "grundflaechenzahl = '".$formvars['grundflaechenzahl']."', ";
    if($formvars['geschossflaechenzahl'] == '')$sql.= "geschossflaechenzahl = NULL, ";
    else $sql.= "geschossflaechenzahl = '".$formvars['geschossflaechenzahl']."', ";
    if($formvars['baumassenzahl'] == '')$sql.= "baumassenzahl = NULL, ";
    else $sql.= "baumassenzahl = '".$formvars['baumassenzahl']."', ";
    if($formvars['flaeche'] == '')$sql.= "flaeche = NULL, ";
    else $sql.= "flaeche = '".$formvars['flaeche']."', ";
    if($formvars['tiefe'] == '')$sql.= "tiefe = NULL, ";
    else $sql.= "tiefe = '".$formvars['tiefe']."', ";
    if($formvars['breite'] == '')$sql.= "breite = NULL, ";
    else $sql.= "breite = '".$formvars['breite']."', ";
    if($formvars['wegeerschliessung']){$sql.= "wegeerschliessung = '".$formvars['wegeerschliessung']."', ";}
  	if($formvars['erschliessung']){$sql.= "erschliessung = '".$formvars['erschliessung']."', ";}
  	if($formvars['ackerzahl']){$sql.= "ackerzahl = '".$formvars['ackerzahl']."', ";}
  	if($formvars['gruenlandzahl']){$sql.= "gruenlandzahl = '".$formvars['gruenlandzahl']."', ";}
    if($formvars['aufwuchs']){$sql.= "aufwuchs = '".$formvars['aufwuchs']."', ";}
  	if($formvars['bodenart']){$sql.= "bodenart = '".$formvars['bodenart']."', ";}
    $sql.= "verfahrensgrund = '".$formvars['verfahrensgrund']."', ";
    $sql.= "verfahrensgrund_zusatz = '".$formvars['verfahrensgrund_zusatz']."', ";
    $sql.= "bemerkungen = '".$formvars['bemerkungen']."', ";
    $sql.= "the_geom = st_transform(st_geometryfromtext('".$formvars['umring']."',".$this->client_epsg."), ".$this->layer_epsg.")";
    $sql.= ", textposition = st_transform(st_geometryfromtext('".$formvars['textposition']."',".$this->client_epsg."), ".$this->layer_epsg.")";
    $sql.=" WHERE oid=".(int)$oid;
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
      # Fehler beim Eintragen in Datenbank
      $ret[1]='\nAuf Grund eines Datenbankfehlers konnte die Zone nicht aktualisiert werden!\n'.$ret[1];
    }
    return $ret;
  }
  
  function copyZonenToNewStichtag($oldStichtag,$newStichtag) {
    # Prüfen der Eingangsparameter
    if ($oldStichtag=='') {
      $errmsg='Es wurde kein alter Stichtag angegeben.';      
    }
    if ($newStichtag=='') {
      $errmsg='Es wurde kein neuer Stichtag angegeben.';
    }
    if ($oldStichtag==$newStichtag) {
      $errmsg='Der alte und neue Stichtag sind gleich.';
    }
    if ($errmsg!='') {
      $ret[0]=1; $ret[1]=$errmsg;
    }
    else {
      # SQL-Einfügeanfrage stellen
      $this->debug->write('Kopieren der Zonen von einem Stichtag zu einem neuen.',4);
      $sql.="INSERT INTO bw_zonen";
      $sql.=" SELECT gemeinde, gemarkung, ortsteilname, postleitzahl, zonentyp, gutachterausschuss, bodenrichtwertnummer, oertliche_bezeichnung, bodenrichtwert, '".$newStichtag."', basiskarte, entwicklungszustand, beitragszustand, nutzungsart, ergaenzende_nutzung, bauweise, geschosszahl, grundflaechenzahl, geschossflaechenzahl, baumassenzahl, flaeche, tiefe, breite, wegeerschliessung, ackerzahl, gruenlandzahl, aufwuchs, verfahrensgrund, verfahrensgrund_zusatz, bemerkungen, textposition, the_geom";
      $sql.=" FROM bw_zonen WHERE stichtag = '".$oldStichtag."'";
      $ret=$this->database->execSQL($sql,4, 1);
      if ($ret[0]) {
        # Fehler beim Eintragen in Datenbank
        $ret[1]='\nAuf Grund eines Datenbankfehlers konnten die Zone nicht kopiert werden!\n'.$ret[1];
      }
    }
    return $ret;
  }
  
}
?>
