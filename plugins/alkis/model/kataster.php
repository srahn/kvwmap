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
#  kataster.php  Klassenbibliothek für Klassen zum Kataster       #
###################################################################
# Liste der Klassen:
#########################
# Flur
# Adresse
# Gebaeude
# Nutzung
# Ausgestaltung
# Festpunkte
# Finanzamt
# Forstamt
# Kreis
# Gemeinde
# Amtsgericht
# Gemarkung
# Eigentuemer
# Grundstueck
# Grundbuch
# Flurstueck
# Vermessungsstelle
#########################
#-----------------------------------------------------------------------------------------------------------------

class Flur {
  var $FlurID;
  var $database;

	function __construct($GemID,$GemkgID,$FlurID,$database) {
    # constructor
    global $debug;
    $this->debug=$debug;
    $this->GemID=$GemID;
    $this->GemkgID=$GemkgID;
    $this->FlurID=$FlurID;
    $this->database=$database;
    $this->LayerName=LAYERNAME_FLUR;
  }
	
	function getBezeichnungFromPosition($position, $epsgcode){
		$this->debug->write("<p>kataster.php Flur->getBezeichnungFromPosition:",4);
		$sql ="SELECT gm.bezeichnung as gemeindename, fl.gemeindezugehoerigkeit_gemeinde gemeinde, gk.bezeichnung as gemkgname, fl.land::text||fl.gemarkungsnummer::text as gemkgschl, fl.flurnummer as flur, CASE WHEN fl.nenner IS NULL THEN fl.zaehler::text ELSE fl.zaehler::text||'/'||fl.nenner::text end as flurst, s.bezeichnung as strasse, l.hausnummer ";
    $sql.="FROM alkis.ax_gemarkung as gk, alkis.ax_gemeinde as gm, alkis.ax_flurstueck as fl ";
		$sql.="LEFT JOIN alkis.ax_lagebezeichnungmithausnummer l ON l.gml_id = ANY(fl.weistauf) ";
		$sql.="LEFT JOIN alkis.ax_lagebezeichnungkatalogeintrag s ON l.kreis=s.kreis AND l.gemeinde=s.gemeinde AND s.lage = lpad(l.lage,5,'0') ";
    $sql.="WHERE gk.gemarkungsnummer = fl.gemarkungsnummer AND gm.kreis = fl.gemeindezugehoerigkeit_kreis AND gm.gemeinde = fl.gemeindezugehoerigkeit_gemeinde ";
    $sql.=" AND ST_WITHIN(st_transform(st_geomfromtext('POINT(".$position['rw']." ".$position['hw'].")',".$epsgcode."), ".EPSGCODE_ALKIS."),fl.wkb_geometry) ";
		$sql.= $this->database->build_temporal_filter(array('gk', 'gm', 'fl'));
    #echo $sql;
    $ret=$this->database->execSQL($sql,4, 0);
    if ($ret[0]!=0) {
      $ret[1]='Fehler bei der Abfrage der Datenbank.'.$ret[1];
    }
    else {
      $ret[1]=pg_fetch_assoc($ret[1]);
    }
    return $ret;
  }

  function getFlurListe($GemkgID,$FlurID, $history_mode = 'aktuell') {
    # Abfragen der Fluren
    $Liste=$this->database->getFlurenListeByGemkgIDByFlurID($GemkgID,$FlurID, $history_mode);
    return $Liste;
  }
  
} # ende der Klasse flur

#-----------------------------------------------------------------------------------------------------------------
#############
#-> Adresse #
#############
class adresse {
  var $GemeindeSchl;
  var $StrassenSchl;
  var $HausNr;
  var $dbConn;
  var $debug;
  var $database;

  function __construct($GemeindeSchl,$StrassenSchl,$HausNr,$database) {
    global $debug;
    $this->debug=$debug;
    $this->GemeindeSchl=$GemeindeSchl;
    $this->StrassenSchl=$StrassenSchl;
    $this->HausNr=$HausNr;
    $this->database=$database;
  }

  function setDBConn($dbConn) {
    $this->dbConn=$dbConn;
  }
	
  function getFlurstKennzListe($exclude_lmh_gml_ids = '') {
    # liefert FlurstKennz zur Adressangaben aus dem ALB Bestand
    $ret=$this->database->getFlurstKennzListeByGemSchlByStrSchl($this->GemeindeSchl, $this->StrassenSchl, $this->HausNr, $exclude_lmh_gml_ids);
    if ($ret[0]) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
    else {
      return $ret[1];
    }
  }

  function getStrassenListe($GemID,$GemkgID) {
    # Funktion liefert eine Liste der Strassen innerhalb der GemID
		if (STRASSENNAMEN_EINDEUTIG) {
			$StrassenListe = $this->database->getStrassenListe($GemID, $GemkgID);
		}
		else {
			$StrassenListe = $this->database->getStrassenListe_not_unique($GemID, $GemkgID);
		}
    return $StrassenListe;
  }
  
  function getHausNrListe($GemID,$StrID,$HausNr,$extent,$order) {
    # 2006-01-11 pk
    # Funktion liefert die Hausnummern zu einer GemID, StrID Kombination
    # und bei Bedarf auch im angegebenen extent zurück
    $PolygonWKTString=rectObj2WKTPolygon($extent);
    $HausNrListe=$this->database->getHausNrListe($GemID,$StrID);
    # liefert ein Array mit HausNr und Nr_Quelle jeweils mit einem Array für die Listen zurück
    return $HausNrListe;
  }
  
	function getStrNamefromID($GemID,$StrID) {
    $ret=$this->database->getStrNameByID($GemID,$StrID);
    if ($ret[0]==0 AND count_or_0($ret[1])>0) {
      # liefert die erste gefundene Strasse zurück
      return $ret[1];
    }
    else {
      return 0;
    }
  }
}


#-----------------------------------------------------------------------------------------------------------------
#####################
#-> Klasse Gemeinde #
#####################

class gemeinde {
  var $GemeindeSchl;
  var $GemeindeName;
  var $KreisSchl;
  var $debug;
  var $database;

  ###################### Liste der Funktionen ####################################
  #
  # function gemeinde($GemeindeSchl)  - Construktor
  # function getTableDef()
  # function getColNames()
  # function getKreisSchl()
  # function getGemeindeName()
  # function getGemeindeListe($Gemeinden,$order)
  # function getGemeindeListeByExtent($extent)
  # function getGemeindeListeByPermission($GemListe)
  # function getDataSourceName()
  # function getMER($layer)
  # function updateGemeinden()
  #
  ################################################################################


  function __construct($GemeindeSchl,$database) {
    global $debug; $this->debug=$debug;
    $this->GemeindeSchl=$GemeindeSchl;
    $this->KreisSchl=$this->getKreisSchl();
    $this->LayerName=LAYERNAME_GEMEINDEN;
    $this->database=$database;
  }

  function getKreisSchl() {
    return substr($this->GemeindeSchl,0,5);
  }

  function getGemeindeName($Gemeinde) {
    $ret=$this->database->getGemeindeName($Gemeinde);
    if ($ret[0]) {
      $Name='Gemeindename nicht gefunden.<br>'.$ret[1];
    }
    else {
      $Name=$ret[1]['name'];
    }
    return $Name;
  }

  function getGemeindeListe($Gemeinden) {
    $GemeindeListe=$this->database->getGemeindeListeByKreisGemeinden($Gemeinden);
    return $GemeindeListe;
  }
  
} # ende der klasse gemeinde


#-----------------------------------------------------------------------------------------------------------------
####################
# Klasse Gemarkung #
####################
class gemarkung {
  var $GemkgSchl;
  var $GemkgName;
  var $GemeindeSchl;
  var $AmtsgerichtSchl;
  var $debug;
  var $database;


  function __construct($GemkgSchl,$database) {
    global $debug;
    $this->debug=$debug;
    $this->GemkgSchl=$GemkgSchl;
    $this->database=$database;
    $this->LayerName=LAYERNAME_GEMARKUNGEN;
  }

  
  function getGemkgName() {
    $ret=$this->database->getGemarkungName($this->GemkgSchl);
    if ($ret[0] AND DBWRITE) {
      return $ret[1];
    }
    return $ret[1];
  }


  function getGemarkungListe($ganzeGemID, $GemkgID) {
    # Abfragen der aktuellen Gemarkungen mit seinen GemeindeNamen
    $Liste=$this->database->getGemarkungListe($ganzeGemID, $GemkgID);
    return $Liste;
  }
	
  function getGemarkungListeAll($ganzeGemID, $GemkgID) {
    # Abfragen aller Gemarkungen (auch der untergegangenen) mit seinen GemeindeNamen
    $Liste=$this->database->getGemarkungListeAll($ganzeGemID, $GemkgID);
    return $Liste;
  }	
  
} # end of class Gemarkung

#-----------------------------------------------------------------------------------------------------------------
#################
#-> Eigentuemer #
################
class eigentuemer {
  var $Grundbuch;
  var $NamensNr;
  var $Name;
  var $debug;
  ###################### Liste der Funktionen ####################################
 #
 # function eigentuemer($Grundbuch,$NamensNr)  - Construktor
 # function getEigentuemerName()
 #
 ################################################################################

  function __construct($Grundbuch,$NamensNr, $database = NULL) {
    global $debug;
    $this->debug=$debug;
    $this->Grundbuch=$Grundbuch;
    $this->NamensNr=$NamensNr;
    $this->database=$database;
    /*$NrTeil=explode('.',$NamensNr);
    $this->Nr=$NrTeil[0];
    if ($NrTeil[1]!='') {
      $this->Nr.='.'.intval($NrTeil[1]);
    }
    */
    $this->Nr = $NamensNr;
  }

  function getAdressaenderungen($gml_id) {
    $sql ="SELECT gml_id, hat, datum, user_id, coalesce(strasse1, strasse) as strasse,  coalesce(hausnummer1, hausnummer) as hausnummer, coalesce(postleitzahlpostzustellung1, postleitzahlpostzustellung) as postleitzahlpostzustellung, coalesce(ort_post1, ort_post) as ort_post, coalesce(ortsteil1, ortsteil) as ortsteil FROM ";
		$sql.="(SELECT p.*, a.strasse as strasse1, a.hausnummer as hausnummer1, a.postleitzahlpostzustellung as postleitzahlpostzustellung1, a.ort_post as ort_post1, a.ortsteil as ortsteil1, at.strasse, at.hausnummer, at.postleitzahlpostzustellung, at.ort_post, at.ortsteil FROM alkis.ax_person_temp p ";
		$sql.="LEFT JOIN alkis.ax_anschrift_temp at ON p.hat = at.gml_id ";
		$sql.="LEFT JOIN alkis.ax_anschrift a ON p.hat = a.gml_id ";
    $sql.="WHERE p.gml_id = '".$gml_id."') as foo";
    #echo $sql;
  	$ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    $rs=pg_fetch_array($ret[1]);
    return $rs;
  }
}

#-----------------------------------------------------------------------------------------------------------------
####################
# Klasse Grundbuch #
####################
class grundbuch {
  var $Bezirk;
  var $Blatt;

  function __construct($Bezirk,$Blatt,$database) {
    global $debug;
    $this->debug=$debug;
    $this->Bezirk=$Bezirk;
    $this->Blatt=$Blatt;
    $this->database=$database;
  }

  function getBuchungen($flurstkennz,$bvnr,$erbaurechtshinweise,$keine_historischen, $buchungsstelle = NULL) {
    $ret=$this->database->getBuchungenFromGrundbuch('',$this->Bezirk,$this->Blatt, NULL, NULL, $buchungsstelle);
    if ($ret[0]) {
      $ret[1]='Fehler bei der Datenbank abfrage<br>'.$ret[1];
    }
    else {
      # Zuordnen der Gemeinde und Gemarkungsnamen zu den Flurstücken.
      $buchungen=$ret[1];
      $anzBuchungen=count($buchungen);
      for($i=0;$i<$anzBuchungen;$i++) {
				//$buchungenret=$this->database->getGemarkungName(substr($buchungen[$i]['flurstkennz'],0,6));
				$buchungen[$i]['gemkgname']=$buchungen[$i]['gemarkungsname'];
				$buchungen[$i]['flur']=intval(substr($buchungen[$i]['flurstkennz'],6,3));
				$buchungen[$i]['zaehler']=intval(substr($buchungen[$i]['flurstkennz'],9,5));
				$buchungen[$i]['nenner']=intval(substr($buchungen[$i]['flurstkennz'],14,6));
				$buchungen[$i]['flurstuecksnr']=$buchungen[$i]['zaehler'];
				if ($buchungen[$i]['nenner']>0) {
					$buchungen[$i]['flurstuecksnr'].='/'.$buchungen[$i]['nenner'];
				}
      }
      $ret[1]=$buchungen;
    }
    return $ret;
  }

  function grundbuchblattSuchParameterPruefen() {
    if ($this->Bezirk=='' OR $this->Bezirk==0) {
      $errmsg.='<br>Die Nummer für Bezirk ist leer.';
    }
    if (strlen($this->Bezirk)<5) {
      $errmsg.='<br>Die Bezirksnummer ist keine 6 Zeichen lang.';
    }
    if ($this->Blatt=='' OR $this->Blatt===0) {
      $errmsg.='<br>Die Blattnummer ist leer.';
    }
    if ($errmsg!='') {
      $ret[0]=1; $ret[1]=$errmsg;
    }
    else {
      $ret[0]=0; $ret[1]='<br>Keine Fehler in den Angaben zu Bezirk und Blattnummer gefunden.';
    }
    return $ret;
  }

  function getGrundbuchbezirksliste(){
  	return $this->database->getGrundbuchbezirksliste();
  }

  function getGrundbuchbezirkslisteByGemkgIDs($ganze_gemkg_ids, $eingeschr_gemkg_ids){
  	return $this->database->getGrundbuchbezirkslisteByGemkgIDs($ganze_gemkg_ids, $eingeschr_gemkg_ids);
  }
  
  function getGrundbuchblattliste($bezirk){
  	return $this->database->getGrundbuchblattliste($bezirk);
  }
	
	function getGrundbuchblattlisteByGemkgIDs($bezirk, $ganze_gemkg_ids, $eingeschr_gemkg_ids, $ganze_flur_ids, $eingeschr_flur_ids){
  	return $this->database->getGrundbuchblattlisteByGemkgIDs($bezirk, $ganze_gemkg_ids, $eingeschr_gemkg_ids, $ganze_flur_ids, $eingeschr_flur_ids);
  }
}

#-----------------------------------------------------------------------------------------------------------------
################
#-> Flurstueck #
################
class flurstueck {
  var $Amtsgericht;
  var $FlurstKennz;
  var $Infotext;
  var $Gemarkung;
  var $GemkgSchl;
  var $Finanzamt;
  var $Entstehung;
  var $Flaeche;
  var $Grundstuecke;
  var $debug;
  var $Zaehler;
  var $Nenner;
  var $Lagebezeichnung;
  var $Adresse;
  var $datadourcename;
  var $database;

  function __construct($FlurstKennz,$database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
    if ($FlurstKennz!='') {
      $this->FlurstKennz=$FlurstKennz;
      $this->GemkgSchl= $this->getGemkgSchl();
      $this->FlurID=$this->getFlurID();
    }
    $this->LayerName=LAYERNAME_FLURSTUECKE;
		$this->spatial_ref_code = EPSGCODE_ALKIS . ", " . EARTH_RADIUS;
  }
	
	function getFlstHistorie(){
		$sql = "
		select
			fkz,
			concat_ws(
				'/',
				substring(fkz from 10 for 5)::int,
				nullif(substring(fkz from 15 for 4), '____')::int
			) as name,
			coalesce(CASE WHEN date_part('DOY', zde) = 1 THEN date_part('year', zde)::text ELSE zde::text END, 'n.n.') as zde,
			to_json(nkz) as nkz
		FROM
			unnest(alkis.fkz_child_tree('" . $this->FlurstKennz . "')) ct (fkz)
			left join alkis.pp_flurstueckshistorie f(fkz,zde,vkz,nkz) using(fkz)
		;
			";
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
		while ($rs = pg_fetch_assoc($ret[1])) {
			$flst_historie[] = $rs;
		}
    return $flst_historie;
	}
	
	function outputEigentuemerText($eigentuemer, $adressAenderungen = NULL, $indent, $database = NULL){
		if($eigentuemer->Nr != '' OR $eigentuemer->zusatz_eigentuemer != ''){
			$Eigentuemer .= $indent;
			if($eigentuemer->vorname != '')$Eigentuemer .= $eigentuemer->vorname.' ';
			$Eigentuemer .= $eigentuemer->nachnameoderfirma;
			if($eigentuemer->namensbestandteil != '')$Eigentuemer .= ', '.$eigentuemer->namensbestandteil;
			if($eigentuemer->akademischergrad != '')$Eigentuemer .= ', '.$eigentuemer->akademischergrad;
			$Eigentuemer .= ' ';
			if($eigentuemer->geburtsname != '')$Eigentuemer .= 'geb. '.$eigentuemer->geburtsname.' ';
			$Eigentuemer .= $eigentuemer->geburtsdatum;
			if ($eigentuemer->sterbedatum != '') {
				$Eigentuemer .= ' &#10015;' . $eigentuemer->sterbedatum;
			}
			foreach($eigentuemer->anschriften as $anschrift){
				$Eigentuemer .= ' '.$anschrift['strasse'].' '.$anschrift['hausnummer'].' ';
				$Eigentuemer .= $anschrift['postleitzahlpostzustellung'].' '.$anschrift['ort_post'].' '.$anschrift['ortsteil'].' ';
			}
			$Eigentuemer .= $eigentuemer->zusatz_eigentuemer;
			if($eigentuemer->Anteil != '')$Eigentuemer .= '  zu '.$eigentuemer->Anteil;
			$Eigentuemer .= "\n";
			return str_replace('"', '\'', $Eigentuemer);
		}
	}
	
	function outputEigentuemerNamensnummer($eigentuemer, $adressAenderungen = NULL, $indent, $database = NULL){
		if($eigentuemer->Nr != '' OR $eigentuemer->zusatz_eigentuemer != ''){
			$Eigentuemer .= $eigentuemer->Nr.' ';
			$Eigentuemer .= "\n";
			return $Eigentuemer;
		}
	}	
	
	function outputEigentuemerShort($eigentuemer, $adressAenderungen = NULL, $indent = NULL, $database = NULL){
		$Eigentuemer .= '<tr><td colspan="2"><table cellpadding="0" cellspacing="0"><tr><td valign="top" style="padding-right: 4">'.$eigentuemer->Nr.'</td><td valign="top" style="padding-right: 4">';
		$Eigentuemer .= '<a target="root" href="index.php?go=Namen_Auswaehlen_Suchen&gml_id='.$eigentuemer->gml_id.'&withflurst=on&anzahl='.MAXQUERYROWS.'&csrf_token=' . $_SESSION['csrf_token'] . '">'.$eigentuemer->vorname.' '.$eigentuemer->nachnameoderfirma;
		if($eigentuemer->namensbestandteil != '')$Eigentuemer .= ', '.$eigentuemer->namensbestandteil;
		if($eigentuemer->akademischergrad != '')$Eigentuemer .= ', '.$eigentuemer->akademischergrad;
		$Eigentuemer .= ' ';
		if($eigentuemer->geburtsname != '')$Eigentuemer .= 'geb. '.$eigentuemer->geburtsname.' ';
		$Eigentuemer .= $eigentuemer->geburtsdatum;
		if ($eigentuemer->sterbedatum != '') {
			$Eigentuemer .= ' &#10015;' . $eigentuemer->sterbedatum;
		}
		$Eigentuemer .= '</a>';
		if($eigentuemer->zusatz_eigentuemer != ''){
			$Eigentuemer .= '</td></tr><tr><td colspan="2">'.$eigentuemer->zusatz_eigentuemer; if($eigentuemer->Anteil != '')$Eigentuemer .= ' zu '.$eigentuemer->Anteil; $Eigentuemer .= '</td></tr><tr><td>';
		}
		elseif($eigentuemer->Anteil)$Eigentuemer .= '&nbsp;&nbsp;&nbsp;zu '.$eigentuemer->Anteil.'<br>';
		$Eigentuemer .= '</td></tr></table></td></tr>';
		return $Eigentuemer;
	}
	
	function outputEigentuemerLong($eigentuemer, $adressAenderungen, $indent = NULL, $database){
		if($eigentuemer->nachnameoderfirma != ''){
			$Eigentuemer .= '<tr>
												<td colspan="2">
													<table>
														<tr>
															<td valign="top">'.$eigentuemer->Nr.'&nbsp;&nbsp;&nbsp;</td>
															<td valign="top">
																<table border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td>
																			<a target="root" href="index.php?go=Namen_Auswaehlen_Suchen&gml_id='.$eigentuemer->gml_id.'&withflurst=on&anzahl='.MAXQUERYROWS.'&csrf_token=' . $_SESSION['csrf_token'] . '">';
			if($eigentuemer->vorname != '')$Eigentuemer .= $eigentuemer->vorname.' ';
			$Eigentuemer .= $eigentuemer->nachnameoderfirma;
			if($eigentuemer->namensbestandteil != '')$Eigentuemer .= ', '.$eigentuemer->namensbestandteil;
			if($eigentuemer->akademischergrad != '')$Eigentuemer .= ', '.$eigentuemer->akademischergrad;
			$Eigentuemer .= '</a><br>';
			if($eigentuemer->geburtsname != '')$Eigentuemer .= 'geb. '.$eigentuemer->geburtsname.' ';
			$Eigentuemer .= $eigentuemer->geburtsdatum;
			if ($eigentuemer->sterbedatum != '') {
				$Eigentuemer .= '<br>&#10015;' . $eigentuemer->sterbedatum;
			}
			if($eigentuemer->anschriften){
				foreach($eigentuemer->anschriften as $anschrift){
					$Eigentuemer .= '<table style="margin-top: 2px" cellspacing="0" cellpadding="0">
														<tr>
															<td>';
					$Eigentuemer .= $anschrift['strasse'].' '.$anschrift['hausnummer'].'<br>';
					$Eigentuemer .= $anschrift['postleitzahlpostzustellung'].' '.$anschrift['ort_post'].' '.$anschrift['ortsteil'];
					if($anschrift['bestimmungsland'] != ''){
						$Eigentuemer .= '<br>'.$anschrift['bestimmungsland'];
					}	
					$Eigentuemer .= '</td>';
					# Adressänderungen
					if($adressAenderungen){
						$Eigentuemer .= '<td style="padding-left: 30px">';
						$adressaenderungen =  $eigentuemer->getAdressaenderungen($eigentuemer->gml_id);
						$aendatum=substr($adressaenderungen['datum'],0,10);
						if($adressaenderungen['user_id'] != ''){
							$user = new user(NULL, $adressaenderungen['user_id'], $database);
							$Eigentuemer .= '<span class="fett"><u>Aktualisierte Anschrift ('.$aendatum.' - '.$user->Name.'):</u></span><br>';
							$Eigentuemer .= '&nbsp;&nbsp;<span class="fett">'.$adressaenderungen['strasse'].' '.$adressaenderungen['hausnummer'].'</span><br>';
							$Eigentuemer .= '&nbsp;&nbsp;<span class="fett">'.$adressaenderungen['postleitzahlpostzustellung'].' '.$adressaenderungen['ort_post'].' '.$adressaenderungen['ortsteil'].'</span><br>';
						}
						if($eigentuemer->Nr != ''){
							if($adressaenderungen['user_id'] == '')$Eigentuemer .= '<img src="'.GRAPHICSPATH.'pfeil_links.gif" width="12" height="12" border="0">&nbsp;<a class="buttonlink" href="javascript:void(0);" onclick="ahah(\'index.php\', \'go=neuer_Layer_Datensatz&reload=true&selected_layer_id='.LAYER_ID_ADRESSAENDERUNGEN_PERSON.'&attributenames[0]=gml_id&attributenames[1]=hat&values[0]='.urlencode($eigentuemer->gml_id).'&values[1]='.urlencode($eigentuemer->anschrift_gml_id).'&embedded=true&fromobject=subform_ax_person_temp'.$eigentuemer->gml_id.'&targetlayer_id=0&targetattribute=leer\', new Array(document.getElementById(\'subform_ax_person_temp'.$eigentuemer->gml_id.'\')), new Array(\'sethtml\'));"><span> Anschrift aktualisieren</span></a>';
							else	$Eigentuemer .= '<img src="'.GRAPHICSPATH.'pfeil_links.gif" width="12" height="12" border="0">&nbsp;<a class="buttonlink" href="javascript:ahah(\'index.php\', \'go=Layer-Suche_Suchen&reload=true&selected_layer_id='.LAYER_ID_ADRESSAENDERUNGEN_PERSON.'&value_gml_id='.urlencode($eigentuemer->gml_id).'&operator_gml_id==&attributenames[0]=user_id&values[0]='.$this->user->id.'&embedded=true&fromobject=subform_ax_person_temp'.$eigentuemer->gml_id.'&targetlayer_id=0&targetattribute=leer\', new Array(document.getElementById(\'subform_ax_person_temp'.$eigentuemer->gml_id.'\')), \'\');">Anschrift &auml;ndern</a>';
						}
						$Eigentuemer .= '</td>';
					}
					$Eigentuemer .= '</tr></table>';
				}
		}
			$Eigentuemer .=	   '</td>
														<tr>
															<td colspan="2"><div id="subform_ax_person_temp'.$eigentuemer->gml_id.'" style="display:inline"></div></td>
														</tr>
														</tr>
													</table>
													</td>
												</tr>';
			$Eigentuemer .= '</table></td></tr>';
		}
		if($eigentuemer->zusatz_eigentuemer != ''){
			$Eigentuemer .=	 '<tr>
													<td>&nbsp;</td><td>'.$eigentuemer->zusatz_eigentuemer; if($eigentuemer->Anteil != '')$Eigentuemer .= ' zu '.$eigentuemer->Anteil;
			$Eigentuemer .=	   '</td>
												</tr>';
		}
		elseif($eigentuemer->Anteil != ''){
			$Eigentuemer .=	 '<tr>
													<td></td>
													<td>zu '.$eigentuemer->Anteil.'</td>
												</tr>';
		}		
		return $Eigentuemer;
	}	
	
	function outputEigentuemer($gml_id, $Eigentuemerliste, $type, $adressAenderungen = NULL, $indent = NULL, $database = NULL){
		if($gml_id != 'wurzel')$style = 'style="border-left: 1px solid lightgrey"';
		$eigentuemer = $Eigentuemerliste[$gml_id];
		$Eigentuemer .= $this->{'outputEigentuemer'.$type}($eigentuemer, $adressAenderungen, $indent, $database);
		if($eigentuemer->children != ''){
			if(in_array($type, array('Text','Namensnummer')))$indent = $indent.'  ';
			else $Eigentuemer .= '<tr><td '.$style.'>&nbsp;&nbsp;</td><td><table>';
			foreach($eigentuemer->children as $child){
				$Eigentuemer .= $this->outputEigentuemer($child, $Eigentuemerliste, $type, $adressAenderungen, $indent, $database);
			}
			if(!in_array($type, array('Text','Namensnummer')))$Eigentuemer .= '</table></td></tr>';
		}
		return $Eigentuemer;
	}
	
	function orderEigentuemer($gml_id, &$Eigentuemerliste, $order){
		# Diese funktion durchläuft den Rechtsverhältnisbaum und vergibt für jeden Eigentümer eine order, die sich fortlaufend erhöht.
		# Anschliessend kann man die Eigentümerliste an Hand dieser order sortieren und erhält damit eine lineare Liste ohne Verschachtelung.
		$Eigentuemerliste[$gml_id]->order = $order;
		if($Eigentuemerliste[$gml_id]->children != ''){
			foreach($Eigentuemerliste[$gml_id]->children as $child){
				$order = $this->orderEigentuemer($child, $Eigentuemerliste, $order+1);
			}
		}
		return $order;
	}
		
	function getSonstigesrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getSonstigesrecht Abfrage des Sonstigesrechts zum Flurstück<br>".$sql,4);
		$sql = "
			SELECT
				round(
					(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche,
				a.beschreibung as art,
				fo.name
			FROM
				alkis.ax_flurstueck f,
				alkis.ax_sonstigesrecht fo JOIN
				st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN
				alkis.ax_artderfestlegung_sonstigesrecht a ON a.wert=fo.artderfestlegung
			WHERE
				st_intersects(fo.wkb_geometry,f.wkb_geometry) AND
				ar.flaeche > 0.001 AND
				f.flurstueckskennzeichen = '" . $this->FlurstKennz . "'
		";
		$sql .= $this->database->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $Sonstigesrecht[]=$rs;
      }
    }
    return $Sonstigesrecht;
  }
	
	function getDenkmalschutzrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getDenkmalschutzrecht Abfrage des Denkmalschutzrechts zum Flurstück<br>".$sql,4);
		$sql = "
			SELECT
				round(
					sum(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche,
				a.beschreibung as art,
				fo.name
			FROM
				alkis.ax_flurstueck f,
				alkis.ax_denkmalschutzrecht fo JOIN
				st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN
				alkis.ax_artderfestlegung_denkmalschutzrecht a ON a.wert = fo.artderfestlegung
			WHERE
				st_intersects(fo.wkb_geometry,f.wkb_geometry) AND
				ar.flaeche > 0.001 AND
				f.flurstueckskennzeichen = '" . $this->FlurstKennz . "'
		";
		$sql .= $this->database->build_temporal_filter(array('f', 'fo'));
		$sql.=" GROUP BY a.beschreibung, fo.name, f.amtlicheflaeche ";
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $Denkmalschutzrecht[]=$rs;
      }
    }
    return $Denkmalschutzrecht;
  }
	
	function getBauBodenrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getBauBodenrecht Abfrage des BauBodenrechts zum Flurstück<br>".$sql,4);
		$sql = "
			SELECT distinct
				round(
					(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche,
				a.beschreibung as art,
				fo.bezeichnung,
				s.bezeichnung as stelle
			FROM
				alkis.ax_flurstueck f,
				alkis.ax_bauraumoderbodenordnungsrecht fo JOIN
				st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN
				alkis.ax_artderfestlegung_bauraumoderbodenordnungsrecht a ON a.wert=fo.artderfestlegung LEFT JOIN
				alkis.ax_dienststelle s ON s.stelle = fo.stelle
			WHERE
				st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND
				ar.flaeche > 0.001 AND
				f.flurstueckskennzeichen = '" . $this->FlurstKennz . "'
		";
		$sql .= $this->database->build_temporal_filter(array('f', 'fo', 's'));
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $BauBodenrecht[]=$rs;
      }
    }
    return $BauBodenrecht;
  }
		
	function getNaturUmweltrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getNaturUmweltrecht Abfrage des NaturUmweltrechts zum Flurstück<br>".$sql,4);
    $sql ="
			SELECT
				round(
					(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche,
				a.beschreibung as art
		FROM
			alkis.ax_flurstueck f,
			alkis.ax_naturumweltoderbodenschutzrecht fo JOIN
			st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN
			alkis.ax_artderfestlegung_naturumweltoderbodenschutzrecht a ON a.wert=fo.artderfestlegung
		WHERE
			st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND
			ar.flaeche > 0.001 AND
			f.flurstueckskennzeichen = '" . $this->FlurstKennz . "'
		";
		$sql .= $this->database->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $NaturUmweltrecht[]=$rs;
      }
    }
    return $NaturUmweltrecht;
  }
	
	function getSchutzgebiet() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getSchutzgebiet Abfrage des Schutzgebiets zum Flurstück<br>".$sql,4);
		$sql ="
			SELECT
				round(
					(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche,
				coalesce(a.beschreibung, b.beschreibung) as art
			FROM
				alkis.ax_flurstueck f, 
				alkis.ax_schutzzone fo JOIN
				st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN
				alkis.ax_schutzgebietnachwasserrecht c ON c.gml_id = ANY(fo.istteilvon) LEFT JOIN
				alkis.ax_artderfestlegung_schutzgebietnachwasserrecht a ON a.wert = c.artderfestlegung LEFT JOIN
				alkis.ax_schutzgebietnachnaturumweltoderbodenschutzrecht d ON d.gml_id = ANY(fo.istteilvon) LEFT JOIN
				alkis.ax_artderfestlegung_schutzgebietnachnaturumweltoderbodensc b ON b.wert = d.artderfestlegung
			WHERE
				st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND
				ar.flaeche > 0.001 AND 
				f.flurstueckskennzeichen='" . $this->FlurstKennz . "'
		";
		$sql.= $this->database->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $Schutzgebiet[]=$rs;
      }
    }
    return $Schutzgebiet;
  }
	
	function getWasserrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getWasserrecht Abfrage des Wasserrechts zum Flurstück<br>".$sql,4);
		$sql ="
			SELECT
				round(
					(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche,
				a.beschreibung as art,
				'' as bezeichnung
			FROM
				alkis.ax_flurstueck f,
				alkis.ax_klassifizierungnachwasserrecht fo JOIN
				st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN
				alkis.ax_artderfestlegung_klassifizierungnachwasserrecht a ON a.wert=fo.artderfestlegung
			WHERE
				st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND
				ar.flaeche > 0.001 AND 
				f.flurstueckskennzeichen='" . $FlurstKennz . "'
				" . $this->database->build_temporal_filter(array('f', 'fo')) . "
			UNION ALL 
			SELECT
				round(
					(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche,
				a.beschreibung as art,
				s.bezeichnung
			FROM
				alkis.ax_flurstueck f,
				alkis.ax_anderefestlegungnachwasserrecht fo JOIN
				st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN
				alkis.ax_artderfestlegung_anderefestlegungnachwasserrecht a ON a.wert=fo.artderfestlegung LEFT JOIN
				alkis.ax_dienststelle s ON s.stelle = fo.stelle
			WHERE
				st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND
				ar.flaeche > 0.001 AND 
				f.flurstueckskennzeichen='" . $this->FlurstKennz . "'
		";
		$sql.= $this->database->build_temporal_filter(array('f', 'fo', 's'));
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $Wasserrecht[]=$rs;
      }
    }
    return $Wasserrecht;
  }
	
	function getStrassenrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getStrassenrecht Abfrage des Strassenrechts zum Flurstück<br>".$sql,4);
		$sql ="
			SELECT
				round(
					(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche,
				a.beschreibung as art,
				bezeichnung
			FROM
				alkis.ax_flurstueck f,
				alkis.ax_klassifizierungnachstrassenrecht fo JOIN
				st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN
				alkis.ax_artderfestlegung_klassifizierungnachstrassenrecht a ON a.wert=fo.artderfestlegung
			WHERE
				st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND
				ar.flaeche > 0.001 AND 
				f.flurstueckskennzeichen='" . $this->FlurstKennz . "'
		";
		$sql.= $this->database->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $Strassenrecht[]=$rs;
      }
    }
    return $Strassenrecht;
  }
		
	function getForstrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getForstrecht Abfrage des Forstrechts zum Flurstück<br>".$sql,4);
		$sql = "
			SELECT 
				round(
					(
						ar.flaeche::numeric /
						st_area(f.wkb_geometry) *	f.amtlicheflaeche
					)::numeric,
					CASE
						WHEN amtlicheflaeche > 0.5
						THEN 0
						ELSE 2
					END
				) AS flaeche, 
				a.beschreibung as art, 
				b.beschreibung as funktion 
			FROM 
				alkis.ax_flurstueck f, 
				alkis.ax_forstrecht fo JOIN
				st_area(st_intersection(fo.wkb_geometry, f.wkb_geometry)) as ar (flaeche) ON true LEFT JOIN 
				alkis.ax_artderfestlegung_forstrecht a ON a.wert=fo.artderfestlegung LEFT JOIN 
				alkis.ax_besonderefunktion_forstrecht b ON b.wert=fo.besonderefunktion 
			WHERE 
				st_intersects(fo.wkb_geometry,f.wkb_geometry) = true AND
				ar.flaeche > 0.001 AND 
				f.flurstueckskennzeichen='" . $this->FlurstKennz . "'";
		$sql.= $this->database->build_temporal_filter(array('f', 'fo'));
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $Forstrecht[]=$rs;
      }
    }
    return $Forstrecht;
  }
	
	function getStrittigeGrenze() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getStrittigeGrenze Abfrage der strittigen Grenzen zum Flurstück<br>".$sql,4);
		$sql ="
			SELECT 
				bf.gml_id
			FROM 
				alkis.ax_flurstueck f, 
				alkis.ax_besondereflurstuecksgrenze bf 
			WHERE 
				st_covers(f.wkb_geometry, bf.wkb_geometry) AND 
				f.flurstueckskennzeichen='" . $this->FlurstKennz . "' AND 
				1000 = ANY(artderflurstuecksgrenze)";
		$sql.= $this->database->build_temporal_filter(array('f', 'bf'));
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
      while($rs=pg_fetch_assoc($ret[1])) {
        $strittigeGrenze[]=$rs;
      }
    }
    return $strittigeGrenze;
  }	

  function getKlassifizierung() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getKlassifizierung Abfrage der Klassifizierungen zum Flurstück<br>".$sql,4);
		$sql ="
			SELECT 
				amtlicheflaeche, 
				round(sum(fl_geom / flstflaeche * amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche, 
				sum(fl_geom) as fl_geom, 
				flstflaeche, 
				n.wert, 
				objart, 
				ARRAY_TO_STRING(
					ARRAY[
						split_part(split_part(k.beschreibung, '(', 2), ')', 1), 
						split_part(split_part(b.beschreibung, '(', 2), ')', 1), 
						split_part(split_part(z.beschreibung, '(', 2), ')', 1), 
						split_part(split_part(e1.beschreibung, '(', 2), ')', 1), 
						split_part(split_part(e2.beschreibung, '(', 2), ')', 1), 
						split_part(split_part(s.beschreibung, '(', 2), ')', 1), 
						n.bodenzahlodergruenlandgrundzahl || '/' || n.wert
					], ' '
				) as label 
			FROM 
				(SELECT 
					amtlicheflaeche, 
					a.flaeche as fl_geom,
					flstflaeche, 
					ltrim(n.bodenzahlodergruenlandgrundzahl, '0') as bodenzahlodergruenlandgrundzahl, 
					ltrim(n.ackerzahlodergruenlandzahl, '0') as wert, 
					n.nutzungsart as objart, 
					n.nutzungsart, 
					n.bodenart, 
					n.entstehungsart, 
					n.klimastufe, 
					n.wasserverhaeltnisse, 
					n.zustandsstufe, 
					n.bodenstufe, 
					n.sonstigeangaben 
				FROM 
					(SELECT
						ST_CollectionExtract(i.intersection, 3) AS intersection,
						st_area(f.wkb_geometry) as flstflaeche,
						amtlicheflaeche
					FROM 
						alkis.ax_flurstueck f, 
						alkis.ax_bewertung be, 
						st_intersection(be.wkb_geometry,f.wkb_geometry) as i (intersection)
					WHERE 						
						st_intersects(be.wkb_geometry,f.wkb_geometry) AND 
						ST_Area(i.intersection) > 0.01 AND 
						f.flurstueckskennzeichen = '" . $this->FlurstKennz . "'
						" .	$this->database->build_temporal_filter(array('f', 'be')) . "
					) as fbe,
					alkis.ax_bodenschaetzung n ,
					st_area(st_intersection(n.wkb_geometry, fbe.intersection)) as a (flaeche)
				WHERE 
					st_intersects(n.wkb_geometry, fbe.intersection) AND 
					a.flaeche > 0.01 
					" .	$this->database->build_temporal_filter(array('n')) . "
				) as n 
				LEFT JOIN alkis.ax_nutzungsart_bodenschaetzung k ON k.wert = n.nutzungsart 
				LEFT JOIN alkis.ax_bodenart_bodenschaetzung b ON b.wert = n.bodenart 
				LEFT JOIN alkis.ax_entstehungsart e1 ON e1.wert = n.entstehungsart[1] 
				LEFT JOIN alkis.ax_klimastufe e2 ON e2.wert = n.klimastufe
				LEFT JOIN alkis.ax_wasserverhaeltnisse e3 ON e3.wert = n.wasserverhaeltnisse
				LEFT JOIN alkis.ax_zustandsstufe z ON z.wert = n.zustandsstufe
				LEFT JOIN alkis.ax_bodenstufe bs ON bs.wert = n.bodenstufe 
				LEFT JOIN alkis.ax_sonstigeangaben_bodenschaetzung s ON s.wert=n.sonstigeangaben[1]
				GROUP BY amtlicheflaeche, flstflaeche, n.wert, objart, label
			";
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1]) > 0) {
			$summe_amt = 0;
			$summe_geom = 0;
			$groesste = 0;
			$i = 0;
      while($rs=pg_fetch_assoc($ret[1])){
				$summe_amt += $rs['flaeche'];
				$summe_geom += $rs['fl_geom'];
				if($groesste < $rs['fl_geom']){
					$groesste = $rs['fl_geom'];
					$index = $i;
				}
        $Klassifizierung[]=$rs;
				$i++;
      }
			$Klassifizierung['nicht_geschaetzt'] = round(($Klassifizierung[$i-1]['flstflaeche'] - $summe_geom) * $Klassifizierung[$i-1]['amtlicheflaeche'] / $Klassifizierung[$i-1]['flstflaeche']);			
			$summe_amt += $Klassifizierung['nicht_geschaetzt'];
			$diff = $Klassifizierung[$i-1]['amtlicheflaeche'] - $summe_amt;
			$Klassifizierung[$index]['flaeche'] += $diff;
    }
    return $Klassifizierung;
  }
	
	function getKlassifizierungAequivalenz() {
    $sql = "
			SELECT 
				amtlicheflaeche, 
				round(sum(fl_geom / flstflaeche * amtlicheflaeche)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END) AS flaeche, 
				sum(fl_geom) as fl_geom, 
				flstflaeche, 
				n.wert, 
				objart, 
				ARRAY_TO_STRING(ARRAY[ split_part(split_part(k.beschreibung, '(', 2), ')', 1), split_part(split_part(b.beschreibung, '(', 2), ')', 1), split_part(split_part(z.beschreibung, '(', 2), ')', 1), split_part(split_part(e1.beschreibung, '(', 2), ')', 1), split_part(split_part(e2.beschreibung, '(', 2), ')', 1), split_part(split_part(s.beschreibung, '(', 2), ')', 1), n.bodenzahlodergruenlandgrundzahl || '/' || n.wert], ' ') as label 
			FROM (
				SELECT 
					amtlicheflaeche, 
					a.flaeche as fl_geom, 
					flstflaeche, 
					ltrim(n.bodenzahlodergruenlandgrundzahl, '0') as bodenzahlodergruenlandgrundzahl, 
					ltrim(n.ackerzahlodergruenlandzahl, '0') as wert, 
					n.nutzungsart as objart, 
					n.nutzungsart, 
					n.bodenart, 
					n.entstehungsart, 
					n.klimastufe, 
					n.wasserverhaeltnisse, 
					n.zustandsstufe, 
					n.bodenstufe, 
					n.sonstigeangaben 
				FROM 
					(SELECT
						--CASE
						--	WHEN (nu.nutzungsartengruppe, nu.werteart1) IN ( (18, 4460), (31, 1010), (31, 1012), (31, 1013) )	THEN ARRAY[1000, 2000]
						--	WHEN (nu.nutzungsartengruppe, nu.werteart1) = (31, 1020) THEN ARRAY[3000, 4000]
						--	ELSE ARRAY[1000, 2000, 3000, 4000]
						--END::int[] AS ableitung_emz,
						ST_CollectionExtract(i.intersection, 3) AS intersection,
						st_area(f.wkb_geometry) as flstflaeche,
						amtlicheflaeche
					FROM 
						alkis.ax_flurstueck f, 
						alkis.n_nutzung nu,
						ST_CollectionExtract(st_intersection(nu.wkb_geometry, f.wkb_geometry), 3) as i (intersection)
					WHERE
						(
							(nu.nutzungsartengruppe, nu.werteart1) IN (
								(18, 4460), (31, 1010), (31, 1011), (31, 1012),	(31, 1013), (31, 1020), (31, 1021), (31, 1030),
								(31, 1031), (31, 1040), (31, 1050), (31, 1051),	(31, 1052), (31, 1200)
							) OR nu.nutzungsartengruppe BETWEEN 32 AND 37
						) AND
						st_intersects(nu.wkb_geometry, f.wkb_geometry) = true AND 
						ST_Area(i.intersection) > 0.01 AND 
						f.flurstueckskennzeichen = '" . $this->FlurstKennz . "' 
						" . $this->database->build_temporal_filter(array('f', 'nu')) . "
					) as fnu,
					alkis.ax_bodenschaetzung n,
					ST_Area(ST_CollectionExtract(ST_Intersection(n.wkb_geometry, fnu.intersection), 3)) as a (flaeche)
				WHERE 
					--n.nutzungsart = ANY(fnu.ableitung_emz) and 
					st_intersects(n.wkb_geometry, fnu.intersection) AND 
					a.flaeche > 0.01 
					" . $this->database->build_temporal_filter(array('n')) . " 
			) as n 
			LEFT JOIN alkis.ax_nutzungsart_bodenschaetzung k ON k.wert = n.nutzungsart 
			LEFT JOIN alkis.ax_bodenart_bodenschaetzung b ON b.wert = n.bodenart 
			LEFT JOIN alkis.ax_entstehungsart e1 ON e1.wert = n.entstehungsart[1] 
			LEFT JOIN alkis.ax_klimastufe e2 ON e2.wert = n.klimastufe
			LEFT JOIN alkis.ax_wasserverhaeltnisse e3 ON e3.wert = n.wasserverhaeltnisse
			LEFT JOIN alkis.ax_zustandsstufe z ON z.wert = n.zustandsstufe
			LEFT JOIN alkis.ax_bodenstufe bs ON bs.wert = n.bodenstufe 
			LEFT JOIN alkis.ax_sonstigeangaben_bodenschaetzung s ON s.wert=n.sonstigeangaben[1]
			GROUP BY amtlicheflaeche, flstflaeche, n.wert, objart, label
		";
		#echo $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return $ret; }
    if (pg_num_rows($ret[1])>0) {
			$summe_amt = 0;
			$summe_geom = 0;
			$groesste = 0;
			$i = 0;
      while($rs=pg_fetch_assoc($ret[1])){
				$summe_amt += $rs['flaeche'];
				$summe_geom += $rs['fl_geom'];
				if($groesste < $rs['fl_geom']){
					$groesste = $rs['fl_geom'];
					$index = $i;
				}
        $Klassifizierung[]=$rs;
				$i++;
      }
			$Klassifizierung['nicht_geschaetzt'] = round(($Klassifizierung[$i-1]['flstflaeche'] - $summe_geom) * $Klassifizierung[$i-1]['amtlicheflaeche'] / $Klassifizierung[$i-1]['flstflaeche']);			
			$summe_amt += $Klassifizierung['nicht_geschaetzt'];
			$diff = $Klassifizierung[$i-1]['amtlicheflaeche'] - $summe_amt;
			$Klassifizierung[$index]['flaeche'] += $diff;
    }
    return $Klassifizierung;
  }

  function getBuchungen($Bezirk,$Blatt,$hist_alb = false, $without_temporal_filter = false){
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getBuchungen Abfrage der Buchungen zum Flurstück auf dem Grundbuch<br>",4);
    #$ret=$this->database->getBuchungen($this->FlurstKennz);
    $ret=$this->database->getBuchungenFromGrundbuch($this->FlurstKennz,$Bezirk,$Blatt,$hist_alb, $this->fiktiv, NULL, $without_temporal_filter);
    return $ret[1];
  }

  function getGrundbuecher($without_temporal_filter = false, $fiktiv = false) {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getGrundbuecher Abfrage der Angaben zum Grundbuch auf dem das Flurstück gebucht ist<br>",4);
		if (rolle::$hist_timestamp != '') {
			$sql = 'SET enable_mergejoin = OFF;';
		}
    $sql.="SELECT distinct g.land || g.bezirk as bezirk, g.buchungsblattnummermitbuchstabenerweiterung AS blatt, g.blattart ";
		if ($this->hist_alb) {
			$sql.="FROM alkis.ax_historischesflurstueckohneraumbezug f ";
			$istgebucht = 'isthistgebucht';
		}
		else {
			$sql.="FROM alkis.ax_flurstueck f ";
			$istgebucht = 'istgebucht';
		}
		if($fiktiv){
			$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON ARRAY[f." . $istgebucht . "] <@ s.an ";
		}
		else{
			$sql.="LEFT JOIN alkis.ax_buchungsstelle s ON f." . $istgebucht . " = s.gml_id OR ARRAY[f.gml_id] <@ s.verweistauf OR ARRAY[f." . $istgebucht . "] <@ s.an ";
		}
		$sql.="LEFT JOIN alkis.ax_buchungsblatt g ON s.istbestandteilvon = g.gml_id ";
		$sql.="WHERE f.flurstueckskennzeichen = '" . $this->FlurstKennz . "' ";
		#$sql.="AND (g.blattart = 1000 OR g.blattart = 2000 OR g.blattart = 3000) ";
		if(!$without_temporal_filter) $sql.= $this->database->build_temporal_filter(array('f', 's', 'g'));
		$sql.="ORDER BY blatt";
		#echo '<br>getGrundbuecher: ' . $sql;
    $ret=$this->database->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_assoc($ret[1])) {
      $Grundbuch[]=$rs;
    }
    $ret[1]=$Grundbuch;
		if($Grundbuch[0]['blattart'] == 5000){			# wenn es ein fiktives Blatt ist, die untergeordneten Buchungsstellen abfragen
			$ret = $this->getGrundbuecher(false, true);
			$this->fiktiv = true;
		}
    return $ret[1];
  }

  function getEigentuemerliste($Bezirk,$Blatt,$BVNR,$without_temporal_filter = false) {
    if ($this->FlurstKennz=="") {
      $Grundbuch = new grundbuch("","",$this->debug);
      $Eigentuemerliste[0] = new eigentuemer($Grundbuch,"");
      return $Eigentuemerliste;
    }
    $this->debug->write("<p>kataster flurstueck->getEigentuemerliste Abfragen der Flurstücksdaten aus dem ALK Bestand:<br>",4);
    $ret=$this->database->getEigentuemerliste($this->FlurstKennz,$Bezirk,$Blatt,$BVNR,$without_temporal_filter);
    if ($ret[0] AND DBWRITE) {
      $Grundbuch = new grundbuch("","",$this->debug);
      $Eigentuemerliste[0] = new eigentuemer($Grundbuch,"");
      return $Eigentuemerliste;
    }
    return $ret[1];
  }

  function getGrundbuchbezirke() {
    if ($this->FlurstKennz=="") { return 0; }
    $ret=$this->database->getGrundbuchbezirke($this->FlurstKennz, $this->hist_alb);
    return $ret;
  }


  function getForstamt() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php flurstuecke->getForstamt: ".$sql,4);
    $ret=$this->database->getForstamt($this->FlurstKennz);
    return $ret[1];
  }

  function getFinanzamt() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("kataster.php->flurstueck->getFinanzamt Abfrage des Finanzamtes zum Flurstück<br>".$sql,4);
    $ret=$this->database->getFinanzamt($this->FlurstKennz);
    return $ret[1];
  }

  function getAmtsgerichte() {
    $blattnummer = strval($this->Buchungen[0]['blatt']);
    if ($blattnummer >= 90000 and $blattnummer <= 99999 OR $this->Grundbuchbezirke == '') {
      $ret[1]=array("schluessel"=>"", "name"=>"Im Grundbuch nicht gebucht");
    }
    else {
      $ret=$this->database->getAmtsgerichtby($this->FlurstKennz, $this->Grundbuchbezirke);
    }  
    return $ret[1];
  }

  function getGemarkung($id) {
    $Gemarkung=new gemarkung($id,$this->database);
    return $Gemarkung;
  }

  function getGemkgSchl() {
    return substr($this->FlurstKennz,0,6);
  }

  function getFlurID() {
  	return substr($this->FlurstKennz,6,3);
  }


  function getLage() {
    $ret=$this->database->getLage($this->FlurstKennz);
    $this->debug->write("<br>kataster.php flurstueck->getLage() Abfrage der Lagebezeichnung zum Flurstück:",4);
    return $ret[1];
  }

  function getAdresse() {
    $this->debug->write("<br>kataster.php flurstueck->getAdresse() Abfragen der Strassen zum Flurstück:",4);
		$ret=$this->database->getStrassen($this->FlurstKennz);
    $Strassen=$ret[1];
    for ($i=0; $i < count_or_0($Strassen);$i++) {
      $this->debug->write("<br>kataster.php flurstueck->getAdresse() Abfragen der Hausnummern zu den Strassen zum Flurstück:",4);
      $ret=$this->database->getHausNummern($this->FlurstKennz,$Strassen[$i]['strasse']);
      $HausNr=$ret[1];
      natsort($HausNr);
      $HausNr = array_values($HausNr);
      $Strassen[$i]['hausnr']=trim($HausNr[0]);
      for ($j=1;$j<count($HausNr);$j++) {
        $Strassen[$i]['hausnr'].=', '.trim($HausNr[$j]);
      } # ende der Schleife  zum Auslesen der Hausnummern der Strasse
    } # ende schleife zum Auslesen der Strassen
    return $Strassen;
  }
  
  function getNutzung() {
    if ($this->FlurstKennz=="") { return 0; }		
		$sql = "
			SELECT 
				round(
					(
						a.flaeche::numeric * amtlicheflaeche / 
						st_area(f.wkb_geometry)
					)::numeric, CASE WHEN amtlicheflaeche > 0.5 THEN 0 ELSE 2 END
				) AS flaeche, 
				nas.nutzungsartengruppe::text || lpad(nas.nutzungsart::text, 2, '0') || lpad(nas.untergliederung1::text, 2, '0') || lpad(nas.untergliederung2::text, 2, '0') as nutzungskennz, 
				nag.gruppe || ' ' || coalesce(na.nutzungsart, '') || ' '||coalesce(nu1.untergliederung1, '') || ' ' || coalesce(nu2.untergliederung2, '') as bezeichnung, 
				nag.bereich, 
				nag.gruppe, 
				na.nutzungsart, 
				nu1.untergliederung1, 
				nu2.untergliederung2, 
				n.info, 
				n.zustand, 
				n.name, 
				amtlicheflaeche
			FROM 
				alkis.ax_flurstueck f, 
				alkis.n_nutzung n 
				join st_area(st_intersection(n.wkb_geometry,f.wkb_geometry)) as a (flaeche) ON true
				left join alkis.n_nutzungsartenschluessel nas on n.objektart = nas.objektart and n.werteart1 = nas.werteart1 and n.werteart2 = nas.werteart2
				left join alkis.n_nutzungsartengruppe nag on nas.nutzungsartengruppe = nag.schluessel 
				left join alkis.n_nutzungsart na on nas.nutzungsartengruppe = na.nutzungsartengruppe and nas.nutzungsart = na.schluessel 
				left join alkis.n_untergliederung1 nu1 on nas.nutzungsartengruppe = nu1.nutzungsartengruppe and nas.nutzungsart = nu1.nutzungsart and nas.untergliederung1 = nu1.schluessel 
				left join alkis.n_untergliederung2 nu2 on nas.nutzungsartengruppe = nu2.nutzungsartengruppe and nas.nutzungsart = nu2.nutzungsart and nas.untergliederung1 = nu2.untergliederung1 and nas.untergliederung2 = nu2.schluessel
			WHERE 
				st_intersects(n.wkb_geometry,f.wkb_geometry) = true AND 
				a.flaeche >= 0.5 AND 
				f.flurstueckskennzeichen = '" . $this->FlurstKennz . "'" .
				$this->database->build_temporal_filter(array('f','n')) . "
			ORDER BY nutzungskennz";
		#echo $sql;
    $queryret = $this->database->execSQL($sql, 4, 0);
    if ($queryret[0] OR pg_num_rows($queryret[1])==0) {
      # keine Eintragungen zu Nutzungen gefunden
      return NULL;
    }
    $summe = 0;
		$groesste = 0;
		$i = 0;
    while($rs=pg_fetch_assoc($queryret[1])) {
			$summe += $rs['flaeche'];
			if($groesste < $rs['flaeche']){
				$groesste = $rs['flaeche'];
				$index = $i;
			}
      $Nutzungen[]=$rs;
			$i++;
    }
		$diff = $Nutzungen[$i-1]['amtlicheflaeche'] - $summe;
		$Nutzungen[$index]['flaeche'] += $diff;		
    return $Nutzungen;
  }

	static function getNutzungBegriffsbestimmungen($database) {	
		$sql = "
			SELECT 
				*
			FROM 
				alkis.n_begriffsbestimmungen";
		#echo $sql;
    $query = $database->execSQL($sql, 4, 0);
    while ($rs = pg_fetch_assoc($query[1])) {
			$result[$rs['kennung']] = $rs;
		}
		return $result;
  }

  function vermessungsrunden($zahl, $stellen){
  	// Die Funktion rundet eine Gleitkommazahl auf $stellen Stellen nach dem Komma,
  	// wobei bei einer 5 nicht immer aufgerundet, sondern immer zur geraden Zahl gerundet wird.
  	$teil = explode('.', $zahl);
  	$vorkomma = $teil[0];
  	$nachkomma = $teil[1];
  	$newnachkomma = substr($nachkomma, 0, $stellen);
  	$newzahl = $vorkomma.'.'.$newnachkomma;
  	$round = str_pad(substr($nachkomma, 0, $stellen), strlen($nachkomma), '5000000000000000000');
  	if($nachkomma > $round){
  		$newzahl = $newzahl + 1/pow(10, $stellen);
  	}
  	elseif($nachkomma == $round){
  		if($newnachkomma % 2 == 1){
  			$newzahl = $newzahl + 1/pow(10, $stellen);
  		}
  	}
  	if(strpos($newzahl, '.') == false){
  		$newzahl .= '.'.str_repeat('0', $stellen);;
  	}
  	return $newzahl;
  }
	
	function getVersionen() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getVersionen (vom Flurstück):<br>",4);
		$this->readALB_Data($this->FlurstKennz, true, 'ogc_fid');
		$Grundbuecher=$this->getGrundbuecher(true);							# die Grundbücher ohne zeitlichen Filter abfragen
		$Buchungen=$this->getBuchungen(NULL,NULL,false, true);	# die Buchungen ohne zeitlichen Filter abfragen
		for($b=0; $b < count($Buchungen); $b++){
			$buchungsstelle_gml_ids[] = $Buchungen[$b]['gml_id'];
			$Eigentuemerliste = $this->getEigentuemerliste($Buchungen[$b]['bezirk'],$Buchungen[$b]['blatt'],$Buchungen[$b]['bvnr'], true);		# die Eigentümer ohne zeitlichen Filter abfragen
			foreach($Eigentuemerliste as $eigentuemer){
				$namensnummer_gml_ids[] = $eigentuemer->n_gml_id;
				$person_gml_ids[] = $eigentuemer->gml_id;
			}
		}
		$versionen= $this->database->getVersionen('ax_flurstueck', array($this->gml_id), NULL);
		$flst_beginnt = $versionen[0]['beginnt'];
		$versionen= array_merge($versionen, $this->database->getVersionen('ax_buchungsstelle', $buchungsstelle_gml_ids, $flst_beginnt));
		$versionen= array_merge($versionen, $this->database->getVersionen('ax_namensnummer', $namensnummer_gml_ids, $flst_beginnt));
		$versionen= array_merge($versionen, $this->database->getVersionen('ax_person', $person_gml_ids, $flst_beginnt));
		# sortieren
		usort($versionen, function($a, $b){return DateTime::createFromFormat('d.m.Y H:i:s', $a['beginnt']) > DateTime::createFromFormat('d.m.Y H:i:s', $b['beginnt']);});
		# gleiche beginnts rausnehmen, Anlässe zusammenfassen
		for($i = 0; $i < count($versionen); $i++){
			if($unique_versionen[$versionen[$i]['beginnt']]['endet'] == '' OR $unique_versionen[$versionen[$i]['beginnt']]['endet'] > $versionen[$i]['endet'])$unique_versionen[$versionen[$i]['beginnt']]['endet'] = $versionen[$i]['endet'];
			$unique_versionen[$versionen[$i]['beginnt']]['anlass'][] = $versionen[$i]['anlass'];
			$unique_versionen[$versionen[$i]['beginnt']]['anlass'] = array_unique($unique_versionen[$versionen[$i]['beginnt']]['anlass']);
			$unique_versionen[$versionen[$i]['beginnt']]['table'][] = $versionen[$i]['table'];
			$unique_versionen[$versionen[$i]['beginnt']]['table'] = array_unique($unique_versionen[$versionen[$i]['beginnt']]['table']);
		}
    return $unique_versionen;
  }
	
	function getNachfolger() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getNachfolger (vom Flurstück):<br>",4);
		$sql = "
			SELECT 
				DISTINCT ON (nachfolger) nachfolger, c.endet 
			FROM (
				SELECT 
					unnest(coalesce(zeigtaufneuesflurstueck, ARRAY['BOV'])) as nachfolger 
				FROM 
					alkis.ax_fortfuehrungsfall 
				WHERE ARRAY['" . $this->FlurstKennz . "'::varchar] <@ zeigtaufaltesflurstueck 
				AND (NOT ARRAY['" . $this->FlurstKennz . "'::varchar] <@ zeigtaufneuesflurstueck OR zeigtaufneuesflurstueck IS NULL)
			)	as foo
			LEFT JOIN alkis.ax_flurstueck c ON c.flurstueckskennzeichen = nachfolger 
			ORDER BY nachfolger, c.endet DESC";
    $queryret=$this->database->execSQL($sql, 4, 0);  
		if (pg_num_rows($queryret[1]) == 0) {
			$sql = "
				SELECT 
					DISTINCT ON (nachfolger) nachfolger, c.endet 
				FROM (
					SELECT 
						unnest(coalesce(nachfolgerflurstueckskennzeichen, ARRAY['BOV'])) as nachfolger 
					FROM 
						alkis.ax_historischesflurstueck 
					WHERE flurstueckskennzeichen = '" . $this->FlurstKennz . "'
				)	as foo
				LEFT JOIN alkis.ax_flurstueck c ON c.flurstueckskennzeichen = nachfolger 
				ORDER BY nachfolger, c.endet DESC";
			$queryret=$this->database->execSQL($sql, 4, 0); 
			if (pg_num_rows($queryret[1]) == 0) {		# kein Fortführungsfall unter ALKIS -> Suche in ALB-Historie
				$sql = "
					SELECT 
						nachfolger, 
						bool_and(CASE WHEN b.flurstueckskennzeichen IS NULL THEN NULL ELSE TRUE END) as hist_alb, 
						CASE WHEN min(coalesce(c.endet::text, '')) = '' THEN '' ELSE max(coalesce(c.endet::text, '')) END as endet -- der CASE ist dazu da, damit immer nur die jüngste Version eines Flurstücks gefunden wird
					FROM (
						SELECT 
							unnest(a.nachfolgerflurstueckskennzeichen) as nachfolger 
						FROM 
							alkis.ax_historischesflurstueckohneraumbezug as a 
						WHERE 
							a.flurstueckskennzeichen = '" . $this->FlurstKennz . "'
						) as foo 
						LEFT JOIN alkis.ax_historischesflurstueckohneraumbezug b ON b.flurstueckskennzeichen = nachfolger 	
						LEFT JOIN alkis.ax_flurstueck c ON c.flurstueckskennzeichen = nachfolger 			-- falls ein Nachfolger in ALKIS historisch ist (endet IS NOT NULL)
					GROUP BY 
						nachfolger 
					ORDER BY 
						nachfolger";
				$queryret=$this->database->execSQL($sql, 4, 0);	
			}
    }
		while ($rs=pg_fetch_assoc($queryret[1])) {
			$Nachfolger[]=$rs;
		}
    return $Nachfolger;
  }

	function getVorgaenger() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getVorgaenger (vom Flurstück):<br>",4);
		$sql = "
			SELECT 
				unnest(zeigtaufaltesflurstueck) as vorgaenger, 
				array_to_string(array_agg(value), ';') as anlass 
			FROM 
				alkis.ax_fortfuehrungsfall, 
				alkis.aa_anlassart 
			WHERE 
				ARRAY['" . $this->FlurstKennz . "'::varchar] <@ zeigtaufneuesflurstueck AND 
				NOT ARRAY['" . $this->FlurstKennz . "'::varchar] <@ zeigtaufaltesflurstueck AND 
				id = ANY(ueberschriftimfortfuehrungsnachweis) 
			GROUP BY 
				zeigtaufaltesflurstueck 
			ORDER BY 
				vorgaenger";
    $queryret=$this->database->execSQL($sql, 4, 0);
    if(pg_num_rows($queryret[1]) == 0){
			$sql = "
				SELECT 
					flurstueckskennzeichen as vorgaenger, 
					array_to_string(array_agg(value), ';') as anlass 
				FROM 
					alkis.ax_historischesflurstueck, 
					alkis.aa_anlassart 
				WHERE 
					ARRAY['" . $this->FlurstKennz . "'::varchar] <@ nachfolgerflurstueckskennzeichen and
					id = ANY(anlass) 
				GROUP BY
					flurstueckskennzeichen
				ORDER BY 
					vorgaenger";
    	$queryret=$this->database->execSQL($sql, 4, 0);
			if(pg_num_rows($queryret[1]) == 0){			# kein Vorgänger unter ALKIS -> Suche in ALB-Historie
				$sql = "
					SELECT 
						flurstueckskennzeichen as vorgaenger, 
						TRUE as hist_alb 
					FROM 
						alkis.ax_historischesflurstueckohneraumbezug f 
					WHERE 
						ARRAY['" . $this->FlurstKennz . "'::varchar] <@ nachfolgerflurstueckskennzeichen 
						" . $this->database->build_temporal_filter(array('f')) . "
					ORDER BY 
						vorgaenger";
				$queryret=$this->database->execSQL($sql, 4, 0);
			}
    }
		while($rs=pg_fetch_assoc($queryret[1])) {
			$Vorgaenger[]=$rs;
		}
    return $Vorgaenger;
  }

  function readALB_Data($FlurstKennz, $without_temporal_filter = false, $oid_column, $eigentuemer_vcheck = NULL) {
    $this->debug->write("<p>kataster.php flurstueck->readALB_Data (vom Flurstück)",4);
    $ret=$this->database->getALBData($FlurstKennz, $without_temporal_filter, $oid_column, $eigentuemer_vcheck);
    if ($ret[0] AND DBWRITE) {
      $errmsg ='<p>kvwmap readALB_Data Abfragen der ALB-Flurstücksdaten';
      $errmsg.='in line: '.__LINE__.'<br>'.$ret[1];
      return $errmsg;
    }
		if($without_temporal_filter){
			if($ret[1]['endet'] != '' OR $ret[1]['hist_alb'])rolle::$hist_timestamp = DateTime::createFromFormat('d.m.Y H:i:s', $ret[1]['beginnt'])->format('Y-m-d\TH:i:s\Z');			
			else rolle::$hist_timestamp = '';
		}
    $rs=$ret[1];
		$this->oid = $rs['oid'];
		$this->gml_id=$rs['gml_id'];
    $this->Zaehler=intval($rs['zaehler']);
    $this->Nenner=intval($rs['nenner']);
    $this->FlurstNr=$this->Zaehler;
    $this->Flurstkennz_alt = $rs['gemkgschl'].'-'.$rs['flurnr'].'-'.str_pad($rs['zaehler'], 5, '0', STR_PAD_LEFT).'/'.str_pad($rs['nenner'], 3, '0', STR_PAD_LEFT);
    if ($this->Nenner!='') { $this->FlurstNr.="/".$this->Nenner; }
    $this->KreisID=$rs['kreisid'];
    $this->KreisName=$rs['kreisname'];
    $this->GemeindeID=$rs['gemeinde'];
    $this->GemeindeName=$rs['gemeindename'];
    $this->GemkgSchl=$rs['gemkgschl'];
    $this->GemkgName=$rs['gemkgname'];
    $this->FlurID=$rs['flurnr'];
    $this->FlurNr=intval($rs['flurnr']);
    $this->FinanzamtName=$rs['finanzamtname'];
    $this->FinanzamtSchl=$rs['finanzamt'];
    if($rs['entsteh'] == '/     -')$rs['entsteh'] = '';
    $this->Entstehung=$rs['entsteh'];
    $this->LetzteFF=$rs['letzff'];
    $this->Flurkarte=$rs['karte'];
    $this->ALB_Flaeche=$rs['flaeche'];
		$this->abweichenderrechtszustand=$rs['abweichenderrechtszustand'];
		$this->zweifelhafterflurstuecksnachweis=$rs['zweifelhafterflurstuecksnachweis'];
		$this->antragsnummer=$rs['antragsnummer'];
    $this->endet=$rs['endet'];
		$this->beginnt=$rs['beginnt'];
		$this->hist_alb=$rs['hist_alb'];
    $this->Pruefzeichen=$rs['pruefzeichen'];
    $this->Forstamt=$this->getForstamt();	
    #$this->AktualitaetsNr=$this->getAktualitaetsNr();			# ALKIS TODO
    $this->Adresse=$this->getAdresse();
    $this->Lage=$this->getLage();
    $this->Grundbuchbezirke = $this->getGrundbuchbezirke();
		if (!$this->Klassifizierung = $this->getKlassifizierung()) {
			$this->Klassifizierung = $this->getKlassifizierungAequivalenz();
		}
		$this->Forstrecht=$this->getForstrecht();
		$this->Strassenrecht=$this->getStrassenrecht();
		$this->Wasserrecht=$this->getWasserrecht();
		$this->Schutzgebiet=$this->getSchutzgebiet();		
		$this->NaturUmweltrecht=$this->getNaturUmweltrecht();
		$this->BauBodenrecht=$this->getBauBodenrecht();
		$this->Denkmalschutzrecht=$this->getDenkmalschutzrecht();
		$this->Sonstigesrecht=$this->getSonstigesrecht();				
		$this->strittigeGrenze=$this->getStrittigeGrenze();
    //$this->Grundbuecher=$this->getGrundbuecher();							# steht im Snippet
    //$this->Buchungen=$this->getBuchungen($Bezirk,$Blatt,1);		# steht im Snippet
    $this->Amtsgerichte = $this->getAmtsgerichte(); 
    $this->Vorgaenger=$this->getVorgaenger();	
    $this->Nachfolger=$this->getNachfolger();
		if ($this->Nachfolger != '' OR $this->hist_alb) {
			$this->Status = 'H';
		}
    # Abfragen der Nutzungen
    $this->Nutzung=$this->getNutzung();
		if ($eigentuemer_vcheck) {
			$this->eigentuemer_vcheck_value = $rs[$eigentuemer_vcheck['attribute']];
		}
  }

  function getFlstListe($GemID, $GemkgID, $FlurID, $FlstID, $history_mode = 'aktuell') {
    $Liste=$this->database->getFlurstuecksListe($GemID, $GemkgID, $FlurID, $FlstID, $history_mode);
    return $Liste;
  }
  
  function getFlurstByGrundbuecher($gbarray) {
  	$Flurstuecke = array();
  	for($i = 0; $i < count($gbarray); $i++){
  		$gb = explode('-', $gbarray[$i]);
  		$Flurst = $this->database->getFlurstueckeByGrundbuchblatt($gb[0], $gb[1]);
  		if($Flurst != NULL)$Flurstuecke = array_unique(array_merge($Flurstuecke, $Flurst));
  	}
    return $Flurstuecke;
  }

  function getNamen($formvars,$ganze_gemkg_ids, $eingeschr_gemkg_ids, $ganze_flur_ids, $eingeschr_flur_ids) {
    if ($formvars['name1']=='' AND $formvars['name2']=='' AND $formvars['name3']=='' AND $formvars['name4']=='' AND $formvars['name5']=='' AND $formvars['name6']=='' AND $formvars['name7']=='' AND $formvars['name8']=='' AND $formvars['gml_id']=='') {
      $ret[0]=1;
      $ret[1]='<br>Geben Sie mindestens einen Suchbegriff ein!';
    }
    else {
    	if($blatt != ''){
    		$blatt = str_pad($blatt, 5, '0', STR_PAD_LEFT);
    	}
      $ret=$this->database->getNamen($formvars, $ganze_gemkg_ids, $eingeschr_gemkg_ids, $ganze_flur_ids, $eingeschr_flur_ids);
      if ($ret[0]) {
        $ret[1]='<br>Fehler bei der Abfrage der Eigentümernamen.'.$ret[1];
      }
    }
    return $ret;
  }
	
	function getFlurstByLatLng($latitude, $longitude) {
		$rs = Array();
		if ($latitude != '' or $longitude != '') {
			$rs = $this->database->getFlurstueckByLatLng($latitude, $longitude);
	    $this->Zaehler = $rs['zaehler'];
	    $this->Nenner = $rs['nenner'];
	    $this->FlurstNr = $this->Zaehler;
	    $this->Flurstkennz_alt = $rs['flurstkennz'];
	    if ($this->Nenner != '') { $this->FlurstNr .= "/".$this->Nenner; }
	    $this->KreisID = $rs['kreis'];
	#    $this->KreisName=$rs['kreisname'];
	    $this->GemeindeID = $rs['gemeinde'];
	#    $this->GemeindeName=$rs['gemeindename'];
	    $this->GemkgSchl = $rs['gemarkungsnummer'];
	#    $this->GemkgName=$rs['gemkgname'];
	    $this->FlurID = $rs['flurnummer'];
	#    $this->FlurNr = $rs['flurnr'];
		}
		return $rs;
	}

}# end of class Flurstueck

?>