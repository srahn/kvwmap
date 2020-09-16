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
		return $this->database->getBezeichnungFromPosition($position, $epsgcode);
  }

  function getFlurListe($GemkgID,$FlurID, $historical = false) {
    # Abfragen der Fluren
    $Liste=$this->database->getFlurenListeByGemkgIDByFlurID($GemkgID,$FlurID, $historical);
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
	
  function getFlurstKennzListe() {
    # liefert FlurstKennz zur Adressangaben aus dem ALB Bestand
    $ret=$this->database->getFlurstKennzListeByGemSchlByStrSchl($this->GemeindeSchl,$this->StrassenSchl,$this->HausNr);
    if ($ret[0]) {
      $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4);
      return 0;
    }
    else {
      return $ret[1];
    }
  }

  function getStrassenListe($GemID,$GemkgID,$extent) {
    # Funktion liefert eine Liste der Strassen innerhalb der GemID und ggf. des extent
    $PolygonWKTString=rectObj2WKTPolygon($extent);
    $StrassenListe=$this->database->getStrassenListe($GemID, $GemkgID, $PolygonWKTString);
    # liefert Array mit Arrays mit StrID und Name zurück
    return $StrassenListe;
  }
  
  function getHausNrListe($GemID,$StrID,$HausNr,$extent,$order) {
    # 2006-01-11 pk
    # Funktion liefert die Hausnummern zu einer GemID, StrID Kombination
    # und bei Bedarf auch im angegebenen extent zurück
    $PolygonWKTString=rectObj2WKTPolygon($extent);
    $HausNrListe=$this->database->getHausNrListe($GemID,$StrID,$HausNr,$PolygonWKTString,$order);
    # liefert ein Array mit HausNr und Nr_Quelle jeweils mit einem Array für die Listen zurück
    return $HausNrListe;
  }
  
	function getStrNamefromID($GemID,$StrID) {
    $ret=$this->database->getStrNameByID($GemID,$StrID);
    if ($ret[0]==0 AND @count($ret[1])>0) {
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
    # Abfragen der Gemarkungen mit seinen GemeindeNamen
    $Liste=$this->database->getGemeindeListeByGemIDByGemkgSchl($ganzeGemID, $GemkgID);
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
    $query=pg_query($sql);
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
	
	function getGrundbuchblattlisteByGemkgIDs($bezirk, $ganze_gemkg_ids, $eingeschr_gemkg_ids){
  	return $this->database->getGrundbuchblattlisteByGemkgIDs($bezirk, $ganze_gemkg_ids, $eingeschr_gemkg_ids);
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
		$Eigentuemer .= '<a href="index.php?go=Namen_Auswaehlen_Suchen&gml_id='.$eigentuemer->gml_id.'&withflurst=on&anzahl='.MAXQUERYROWS.'">'.$eigentuemer->vorname.' '.$eigentuemer->nachnameoderfirma;
		if($eigentuemer->namensbestandteil != '')$Eigentuemer .= ', '.$eigentuemer->namensbestandteil;
		if($eigentuemer->akademischergrad != '')$Eigentuemer .= ', '.$eigentuemer->akademischergrad;
		$Eigentuemer .= ' ';
		if($eigentuemer->geburtsname != '')$Eigentuemer .= 'geb. '.$eigentuemer->geburtsname.' ';
		$Eigentuemer .= $eigentuemer->geburtsdatum;
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
																			<a href="index.php?go=Namen_Auswaehlen_Suchen&gml_id='.$eigentuemer->gml_id.'&withflurst=on&anzahl='.MAXQUERYROWS.'">';
			if($eigentuemer->vorname != '')$Eigentuemer .= $eigentuemer->vorname.' ';
			$Eigentuemer .= $eigentuemer->nachnameoderfirma;
			if($eigentuemer->namensbestandteil != '')$Eigentuemer .= ', '.$eigentuemer->namensbestandteil;
			if($eigentuemer->akademischergrad != '')$Eigentuemer .= ', '.$eigentuemer->akademischergrad;
			$Eigentuemer .= '</a><br>';
			if($eigentuemer->geburtsname != '')$Eigentuemer .= 'geb. '.$eigentuemer->geburtsname.' ';
			$Eigentuemer .= $eigentuemer->geburtsdatum;
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
    $ret=$this->database->getSonstigesrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Sonstigesrecht=$ret[1];
    return $Sonstigesrecht;
  }
	
	function getDenkmalschutzrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getDenkmalschutzrecht Abfrage des Denkmalschutzrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getDenkmalschutzrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Denkmalschutzrecht=$ret[1];
    return $Denkmalschutzrecht;
  }
	
	function getBauBodenrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getBauBodenrecht Abfrage des BauBodenrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getBauBodenrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $BauBodenrecht=$ret[1];
    return $BauBodenrecht;
  }
		
	function getNaturUmweltrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getNaturUmweltrecht Abfrage des NaturUmweltrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getNaturUmweltrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $NaturUmweltrecht=$ret[1];
    return $NaturUmweltrecht;
  }
	
	function getSchutzgebiet() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getSchutzgebiet Abfrage des Schutzgebiets zum Flurstück<br>".$sql,4);
    $ret=$this->database->getSchutzgebiet($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Schutzgebiet=$ret[1];
    return $Schutzgebiet;
  }
	
	function getWasserrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getWasserrecht Abfrage des Wasserrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getWasserrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Wasserrecht=$ret[1];
    return $Wasserrecht;
  }
	
	function getStrassenrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getStrassenrecht Abfrage des Strassenrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getStrassenrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Strassenrecht=$ret[1];
    return $Strassenrecht;
  }
		
	function getForstrecht() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getForstrecht Abfrage des Forstrechts zum Flurstück<br>".$sql,4);
    $ret=$this->database->getForstrecht($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Forstrecht=$ret[1];
    return $Forstrecht;
  }
	
	function getStrittigeGrenze() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getStrittigeGrenze Abfrage der strittigen Grenzen zum Flurstück<br>".$sql,4);
    $ret=$this->database->getStrittigeGrenze($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $strittigeGrenze=$ret[1];
    return $strittigeGrenze;
  }	

  function getKlassifizierung() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getKlassifizierung Abfrage der Klassifizierungen zum Flurstück<br>".$sql,4);
    $ret=$this->database->getKlassifizierung($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $Klassifizierung=$ret[1];
    return $Klassifizierung;
  }

  function getBuchungen($Bezirk,$Blatt,$hist_alb = false, $without_temporal_filter = false){
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getBuchungen Abfrage der Buchungen zum Flurstück auf dem Grundbuch<br>",4);
    #$ret=$this->database->getBuchungen($this->FlurstKennz);
    $ret=$this->database->getBuchungenFromGrundbuch($this->FlurstKennz,$Bezirk,$Blatt,$hist_alb, $this->fiktiv, NULL, $without_temporal_filter);
    return $ret[1];
  }

  function getGrundbuecher($without_temporal_filter = false) {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<br>kataster.php->flurstueck->getGrundbuecher Abfrage der Angaben zum Grundbuch auf dem das Flurstück gebucht ist<br>",4);
    $ret=$this->database->getGrundbuecher($this->FlurstKennz, $this->hist_alb, false, $without_temporal_filter);
		if($ret['fiktiv'])$this->fiktiv = true;
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

  function getGrundbuchbezirk() {
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

  function getAmtsgericht() {
    $blattnummer = strval($this->Buchungen[0]['blatt']);
    if ($blattnummer >= 90000 and $blattnummer <= 99999) {
      $ret[1]=array("schluessel"=>"", "name"=>"Im Grundbuch nicht gebucht");
    }
    else {
      $ret=$this->database->getAmtsgerichtby($this->FlurstKennz, $this->Grundbuchbezirk);
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
    for ($i=0; $i < @count($Strassen);$i++) {
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
    $ret=$this->database->getNutzung($this->FlurstKennz);
    if ($ret[0] AND DBWRITE) { return 0; }
    return $ret[1];
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
		$this->readALB_Data($this->FlurstKennz, true);
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
    $ret=$this->database->getNachfolger($this->FlurstKennz);
    return $ret[1];
  }

  function getVorgaenger() {
    if ($this->FlurstKennz=="") { return 0; }
    $this->debug->write("<p>kataster flurstueck->getVorgaenger (vom Flurstück):<br>",4);
		$ret=$this->database->getVorgaenger($this->FlurstKennz);
    return $ret[1];
  }

  function readALB_Data($FlurstKennz, $without_temporal_filter = false) {
    $this->debug->write("<p>kataster.php flurstueck->readALB_Data (vom Flurstück)",4);
    $ret=$this->database->getALBData($FlurstKennz, $without_temporal_filter);
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
		$this->oid=$rs['oid'];
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
    $this->endet=$rs['endet'];
		$this->beginnt=$rs['beginnt'];
		$this->hist_alb=$rs['hist_alb'];
    $this->Pruefzeichen=$rs['pruefzeichen'];
    $this->Forstamt=$this->getForstamt();	
    #$this->AktualitaetsNr=$this->getAktualitaetsNr();			# ALKIS TODO
    $this->Adresse=$this->getAdresse();
    $this->Lage=$this->getLage();
    $this->Grundbuchbezirk=$this->getGrundbuchbezirk();
    $this->Klassifizierung=$this->getKlassifizierung();
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
    $this->Amtsgericht=$this->getAmtsgericht(); 
    $this->Vorgaenger=$this->getVorgaenger();	
    $this->Nachfolger=$this->getNachfolger();
		if($this->Nachfolger != '')$this->Status = 'H';
    # Abfragen der Nutzungen
    $this->Nutzung=$this->getNutzung();
  }

  function getFlstListe($GemID,$GemkgID,$FlurID, $historical = false) {
    $Liste=$this->database->getFlurstuecksListe($GemID,$GemkgID,$FlurID, $historical);
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

  function getNamen($formvars,$ganze_gemkg_ids, $eingeschr_gemkg_ids) {
    if ($formvars['name1']=='' AND $formvars['name2']=='' AND $formvars['name3']=='' AND $formvars['name4']=='' AND $formvars['name5']=='' AND $formvars['name6']=='' AND $formvars['name7']=='' AND $formvars['name8']=='' AND $formvars['gml_id']=='') {
      $ret[0]=1;
      $ret[1]='<br>Geben Sie mindestens einen Suchbegriff ein!';
    }
    else {
    	if($blatt != ''){
    		$blatt = str_pad($blatt, 5, '0', STR_PAD_LEFT);
    	}
      $ret=$this->database->getNamen($formvars, $ganze_gemkg_ids, $eingeschr_gemkg_ids);
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