<?
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
# pkorduan@gmx.de peter.korduan@gdi-service.de                    #
###################################################################
##################################################
# Klasse Datenbank für PostgreSQL                #
##################################################

class pgdatabase extends pgdatabase_alkis{

	function getForstamt($FlurstKennz) {
    $sql ="SELECT a.forstamt AS schluessel,a.name FROM alb_flurstuecke AS f,alb_v_forstaemter AS a";
    $sql.=" WHERE f.forstamt=a.forstamt AND f.flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      if (pg_num_rows($queryret[1])>0) {
        $rs=pg_fetch_array($queryret[1]);
        $Forstamt=$rs;
      }
      else {
        $Forstamt['name']='ungebucht';
      }
      $ret[1]=$Forstamt;
    }
    return $ret;
  }

  function getGemeindeListeByKreisGemeinden($Gemeinden){
    $sql ="SELECT DISTINCT g.gemeinde AS id,g.gemeindename AS name";
    $sql.=" FROM alb_v_gemeinden AS g WHERE 1=1";
    if (is_array($Gemeinden)) {
      if ($Gemeinden[0]['ID']!=0 AND $Gemeinden[0]['ID']!='') {
        $sql.=" AND g.gemeinde IN (".$Gemeinden[0]['ID'];
        for ($i=1;$i<count($Gemeinden);$i++) {
          $sql.=",".$Gemeinden[$i]['ID'];
        }
        $sql.=")";
      }
    }
    $sql.=" ORDER BY GemeindeName";
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_array($ret[1])) {
      $GemeindeListe['ID'][]=$rs['id'];
      $GemeindeListe['Name'][]=$rs['name'];
    }
    return $GemeindeListe;
  }
	
	function getGemeindeListeByGemIDByGemkgSchl($GemID,$GemkgID){
    $sql ="SELECT DISTINCT gmk.gemkgschl AS GemkgID,gmk.gemkgname AS Name,gem.gemeindename, gem.gemeinde";
    $sql.=" FROM alb_v_gemarkungen AS gmk,alb_v_gemeinden AS gem WHERE gmk.gemeinde=gem.gemeinde";
    if ($GemID[0]!='') {
      $sql.=" AND gmk.Gemeinde IN (".$GemID[0];
      for ($i=1;$i<count($GemID);$i++) {
        $sql.=",".$GemID[$i];
      }
      $sql.=")";
    }
    if ($GemkgID[0]!='') {
      $sql.=" AND gmk.GemkgSchl IN (".$GemkgID[0];
      for ($i=1;$i<count($GemkgID);$i++) {
        $sql.=",".$GemkgID[$i];
      }
      $sql.=")";
    }
    $sql.=" ORDER BY gmk.GemkgName";
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['GemkgID'][]=$rs['gemkgid'];
      $Liste['Name'][]=$rs['name'];
      $Liste['gemeinde'][]=$rs['gemeinde'];
      $Liste['Bezeichnung'][]=$rs['name']." (".$rs['gemkgid'].") ".$rs['gemeindename'];
    }
    return $Liste;
  }
	
	function getNamen($formvars,$gemkgschl) {
    # pg unterstützt mit LIKE CASE SENSITIVEN Stringvergleiche
    $name[1] = '%'.$formvars['name1'].'%';
		$name[2] = '%'.$formvars['name2'].'%';
		$name[3] = '%'.$formvars['name3'].'%';
		$name[4] = '%'.$formvars['name4'].'%';
		$bezirk = $formvars['bezirk'];
		$blatt = $formvars['blatt'];
		$flur = $formvars['FlurID'];
		$limitAnzahl = $formvars['anzahl'];
		$limitStart = $formvars['offset'];
		$caseSensitive = $formvars['caseSensitive'];
		$order = $formvars['order'];
    $sql ="SELECT DISTINCT n.lfd_nr_name,name1,name2,name3,name4,e.bezirk,e.blatt,e.namensnr";
    $sql.=" FROM alb_g_namen AS n,alb_g_eigentuemer AS e, alb_grundbuecher as gb";
    if ($gemkgschl>0) {
      $sql.=",alb_g_buchungen AS b";
    }
    $sql.=" WHERE n.lfd_nr_name=e.lfd_nr_name";
    $sql.=" AND gb.bezirk = e.bezirk"; 
		$sql.=" AND gb.blatt = e.blatt";
		$sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    for ($i=1;$i<5;$i++) {
      if ($name[$i]!='%%') {
      	$name[$i]=$name[$i];
        if ($caseSensitive) {
          $sql.=" AND name".$i." LIKE '".$name[$i]."'";
        }
        else {
          $sql.=" AND lower(name".$i.") LIKE lower('".$name[$i]."')";
        }
      }
    }
    if($bezirk!='') {
      $sql.=" AND e.bezirk = ".$bezirk;
    }
    if($blatt != ''){
      $sql.=" AND e.blatt = '".$blatt."'";
    }   
    if ($gemkgschl>0) {
      $sql.=" AND e.bezirk=b.bezirk AND e.blatt=b.blatt";
      $sql.=" AND (b.flurstkennz LIKE ";
      $sql.=" '".$gemkgschl[0]."%'";
      for($i = 1; $i < count($gemkgschl); $i++){
        $sql.=" OR b.flurstkennz LIKE '".$gemkgschl[$i]."%'";
      }
      $sql.=")";
    }    
    if ($flur>0) {
      $sql.=" AND e.bezirk=b.bezirk AND e.blatt=b.blatt";
      $sql.=" AND substring(TRIM(b.flurstkennz) from 8 for 3) = '".$flur."'";
    }
    if($order != ''){
    	$sql.=" ORDER BY ".$order;
    }
    if ($limitStart!='' OR $limitAnzahl!='') {
      $sql.=" LIMIT ";
      if ($limitStart!='' AND $limitAnzahl!='') {
        $sql.=$limitAnzahl." OFFSET ".$limitStart;
      }
      if ($limitStart!='' AND $limitAnzahl=='') {
        $sql.=" ALL OFFSET ".$limitStart;
      }
      if ($limitStart=='' AND $limitAnzahl!='') {
        $sql.=$limitAnzahl;
      }
    }
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
        $namen[]=$rs;
      }
      $ret[1]=$namen;
    }
    return $ret;
  }
	
	 function getALBData($FlurstKennz) {
    $sql ="SELECT f.flurnr,f.pruefzeichen,f.status,f.entsteh,f.letzff,f.flaeche,f.aktunr,f.karte,f.baublock";
	  $sql.=",f.koorrw,f.koorhw,f.forstamt,SUBSTRING(f.flurstkennz FROM 12 FOR 5) AS zaehler,SUBSTRING(f.flurstkennz FROM 18 FOR 3) AS nenner";
	  $sql.=",k.kreis AS kreisid,k.kreisname AS kreisname,gk.gemkgschl,gk.gemkgname,g.gemeinde,g.gemeindename";
	  $sql.=",fa.finanzamt,fa.name AS finanzamtname FROM alb_v_kreise AS k,alb_v_gemeinden AS g,alb_v_gemarkungen AS gk";
	  $sql.=",alb_flurstuecke AS f LEFT JOIN alb_v_finanzaemter AS fa ON f.finanzamt=fa.finanzamt";
	  $sql.=" WHERE CAST(f.gemkgschl AS text)=CAST(gk.gemkgschl AS text)";
	  $sql.=" AND SUBSTRING(CAST(gk.gemeinde AS text) FROM 1 FOR 5) = CAST(k.kreis AS text) AND gk.gemeinde=g.gemeinde";
	  $sql.=" AND f.flurstkennz='".$FlurstKennz."'";
    #echo $sql; 
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=0;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=$rs;
    }
    return $ret;
  }
	
	function getFlurenListeByGemkgIDByFlurID($GemkgID,$FlurID, $historical = false){
    $sql ="SELECT DISTINCT flurnr AS FlurID,flurnr AS Name";
    $sql.=",gemkgschl || flurnr AS GemFlurID FROM alb_flurstuecke ";
    if($historical == 1){
    	$sql.="WHERE status = 'H'";
    }
    else{
    	$sql.="WHERE status != 'H'";
    }
    if ($GemkgID>0) {
      $sql.=" AND gemkgschl=".(int)$GemkgID;
    }
    if ($FlurID[0]>0) {
      $sql.=" AND gemkgschl || flurnr IN (".$FlurID[0];
      for ($i=1;$i<count($FlurID);$i++) {
      $sql.=",".$FlurID[$i];
      }
      $sql.=")";
    }
    $sql.=" ORDER BY FlurNr";
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlurID'][]=$rs['flurid'];
      $Liste['Name'][]=intval($rs['name']);
      $Liste['GemFlurID'][]=$rs['gemflurid'];
    }
    return $Liste;
  }
	
	function getFlurenListeByGemkgIDByFlurIDALK($GemkgID, $historical = false){
    $sql ="SELECT DISTINCT SUBSTRING(flurstkennz,8,3) AS flurid";
    $sql.=",gemkgschl || SUBSTRING(flurstkennz,8,3) AS gemflurid FROM alknflst WHERE 1=1";
    if ($GemkgID>0) {
      $sql.=" AND gemkgschl= '".$GemkgID."'";
    }
    $sql.=" ORDER BY flurid";
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlurID'][]=$rs['flurid'];
      $Liste['Name'][]=intval($rs['flurid']);
      $Liste['GemFlurID'][]=$rs['gemflurid'];
    }
    return $Liste;
  }
	
	function getFlurstuecksListe($GemID,$GemkgID,$FlurID, $historical = false){
    $sql ="SELECT *,SUBSTRING(flurstkennz,12,5) AS zaehler,SUBSTRING(flurstkennz,18,3) AS nenner";
    if($historical == 1){
    	$sql.=" FROM alb_flurstuecke WHERE status = 'H'";
    }
    else{
    	$sql.=" FROM alb_flurstuecke WHERE status != 'H'";
    }
    if ($GemkgID>0) {
      $sql.=" AND gemkgschl= ".$GemkgID;
    }
    if ($FlurID!='-1') {
      $sql.=" AND flurnr='".$FlurID."'";
    }
    $sql.=" ORDER BY flurstkennz";
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlstID'][]=$rs['flurstkennz'];
      $FlstNr=intval($rs['zaehler']);
      if ($rs['nenner']!='000') { $FlstNr.="/".intval($rs['nenner']); }
      $Liste['FlstNr'][]=$FlstNr;
    }
    return $Liste;
  }
	
	function getFlurstuecksListeALK($GemID,$GemkgID,$FlurID, $historical = false){
		$sql ="SELECT *,SUBSTRING(flurstkennz,12,5) AS zaehler,SUBSTRING(flurstkennz,18,3) AS nenner";
    $sql.=" FROM alknflst WHERE 1=1";
    if ($GemkgID>0) {
      $sql.=" AND gemkgschl= '".$GemkgID."'";
    }
    if ($FlurID!='') {
      $sql.=" AND SUBSTRING(flurstkennz,8,3)='".$FlurID."'";
    }
    $sql.=" ORDER BY flurstkennz";
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['FlstID'][]=$rs['flurstkennz'];
      $FlstNr=intval($rs['zaehler']);
      if ($rs['nenner']!='000') { $FlstNr.="/".intval($rs['nenner']); }
      $Liste['FlstNr'][]=$FlstNr;
    }
    return $Liste;
	}
	
	function getGrundbuchbezirksliste(){
  	$sql ="SELECT * FROM alb_v_grundbuchbezirke";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
      	$liste['schluessel'][]=$rs['grundbuchbezschl'];
      	$liste['bezeichnung'][]=$rs['bezeichnung'];
      	$liste['beides'][]=$rs['bezeichnung'].' ('.$rs['grundbuchbezschl'].')';
    	}
    }
    return $liste;
  }
	
	function getGrundbuchblattliste($bezirk){
		$sql = "SELECT * FROM alb_grundbuecher WHERE bezirk = ".$bezirk." AND aktualitaetsnr != 'hist' ORDER BY blatt";
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
      	$liste['blatt'][]=$rs['blatt'];
    	}
    }
    return $liste;
	}
	
	function getBuchungenFromGrundbuch($FlurstKennz,$Bezirk,$Blatt,$keine_historischen) {
    # 28.11.2006 H.Riedel, AktualitaetsNr hinzugefuegt
    # $sql ="SELECT b.bezirk,b.blatt,gb.pruefzeichen,b.bvnr,g.buchungsart,ba.bezeichnung,b.flurstkennz";
    $sql ="SELECT b.bezirk,b.blatt,gb.pruefzeichen,b.bvnr,g.buchungsart,ba.bezeichnung,b.flurstkennz,gb.aktualitaetsnr, g.anteil, g.auftplannr, g.sondereigentum, b.erbbaurechtshinw ,gb.zusatz_eigentuemer";
    $sql.=" FROM alb_g_buchungen AS b,alb_g_grundstuecke AS g, alb_v_buchungsarten AS ba,alb_grundbuecher AS gb";
    $sql.=" WHERE b.bezirk=g.bezirk AND b.blatt=g.blatt AND b.bvnr=g.bvnr";
    $sql.=" AND b.bezirk=gb.bezirk AND b.blatt=gb.blatt AND g.buchungsart=ba.buchungsart";
    if ($keine_historischen) {
      $sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    }
    if ($Bezirk!='') {
      $sql.=" AND gb.bezirk='".$Bezirk."'";
    }
    if ($Blatt!='') {
      $sql.=" AND gb.blatt='".$Blatt."'";
    }
    if ($FlurstKennz!='') {
      $sql.=" AND b.flurstkennz='".$FlurstKennz."'";
    }
    $sql.=" ORDER BY b.bezirk,b.blatt,b.bvnr,b.flurstkennz";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Buchung[]=$rs;
    }
    $ret[1]=$Buchung;
    return $ret;
  }
	
	function getGemarkungName($GemkgSchl) {
    $sql ="SELECT gemkgname FROM alb_v_gemarkungen WHERE gemkgschl=".(int)$GemkgSchl;
    $this->debug->write("<p>postgres.sql getGemarkungName Abfragen des Gemarkungsnamen:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $ret[1]=$rs['gemkgname'];
    }
    return $ret;
  }
	
	function getStrassenListe($GemID,$GemkgID, $PolygonWKTString) {
    # 2006-01-30
    # Abfragen der Strassen
    $sql ="SELECT gemeinde,strasse,strassenname,gemkgname FROM (";
    # Subselect zum Zusammenfassen und Sortieren der Bestandteile der Abfrage
    $sql.="SELECT DISTINCT gemeinde,strasse,strassenname,ordertext,gemkgname FROM (";
    # Abfage zum Einfï¿½gen der leeren Auswahlzeile
    $sql.="SELECT -1 AS gemeinde,'-1' AS strasse,'--Auswahl--' AS strassenname, 'aaa' AS ordertext, '' as gemkgname";
    $sql.=" UNION";
    # Abfrage der Strassen aus dem ALB-Bestand
    $sql.=" SELECT s.gemeinde,s.strasse,s.strassenname,s.strassenname AS ordertext, g.gemkgname";
    $sql.=" FROM alb_f_adressen AS a,alb_v_strassen AS s, alb_flurstuecke as f, alb_v_gemarkungen as g";
    $sql.=" WHERE f.status != 'H' AND f.flurstkennz = a.flurstkennz AND g.gemkgschl = f.gemkgschl AND a.gemeinde=s.gemeinde AND a.strasse=s.strasse";
    if ($GemID!='') {
      $sql.=" AND a.gemeinde=".(int)$GemID;
    }
    $sql.=") AS foo ORDER BY gemeinde,ordertext";
    $sql.=") AS foofoo";
    #echo $sql;
    $this->debug->write("<p>postgres getStrassenListe Abfragen der Strassendaten:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    $i = 0;
    while ($rs=pg_fetch_array($queryret[1])) {
    	if($namen[$i-1] == $rs['strassenname'] AND $Liste['StrID'][$i-1] == $rs['strasse']){
    		# Strasse doppelt drin -> ï¿½berspringen
    		$i = $i-1;
    	}
    	else{
	      $Liste['Gemeinde'][]=$rs['gemeinde'];
	      $Liste['StrID'][]=$rs['strasse'];
	      $Liste['Gemarkung'][]=$rs['gemkgname'];
	      $namen[]=$rs['strassenname'];		# eigentlichen Strassennamen sichern
	      if($Liste['Name'][$i-1] == $rs['strassenname']){
	      	$Liste['Name'][$i-1]=$Liste['Name'][$i-1].' ('.$Liste['Gemarkung'][$i-1].')';
	      	$Liste['Name'][$i]=$rs['strassenname'].' ('.$rs['gemkgname'].')';
	      }
	      else{
	      	$Liste['Name'][]=$rs['strassenname'];
	      }
    	}
      $i++;
    }
    return $Liste;
  }
	
	function getStrassenListeByGemkg($GemkgID,$PolygonWKTString) {
    # 2006-01-30
    # Abfragen der Strassen
    $sql ="SELECT gemeinde,strasse,strassenname,gemkgname, gemkgschl FROM (";
    # Subselect zum Zusammenfassen und Sortieren der Bestandteile der Abfrage
    $sql.="SELECT DISTINCT gemeinde,strasse,strassenname,ordertext,gemkgname, gemkgschl FROM (";
    # Abfage zum Einfï¿½gen der leeren Auswahlzeile
    $sql.="SELECT -1 AS gemeinde,'-1' AS strasse,'--Auswahl--' AS strassenname, 'aaa' AS ordertext, '' as gemkgname, 0 as gemkgschl";
    $sql.=" UNION";
    # Abfrage der Strassen aus dem ALB-Bestand
    $sql.=" SELECT s.gemeinde,s.strasse,s.strassenname,s.strassenname AS ordertext, g.gemkgname, g.gemkgschl";
    $sql.=" FROM alb_f_adressen AS a,alb_v_strassen AS s, alb_flurstuecke as f, alb_v_gemarkungen as g";
    $sql.=" WHERE f.flurstkennz = a.flurstkennz AND g.gemkgschl = f.gemkgschl AND a.gemeinde=s.gemeinde AND a.strasse=s.strasse";
    if ($GemkgID!='') {
      $sql.=" AND g.gemkgschl=".(int)$GemkgID;
    }
    $sql.=") AS foo ORDER BY gemeinde,ordertext";
    $sql.=") AS foofoo";
    #echo $sql;
    $this->debug->write("<p>postgres getStrassenListe Abfragen der Strassendaten:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    $i = 0;
    while ($rs=pg_fetch_array($queryret[1])) {
    	if($namen[$i-1] == $rs['strassenname'] AND $Liste['StrID'][$i-1] == $rs['strasse']){
    		# Strasse doppelt drin -> ï¿½berspringen
    		$i = $i-1;
    	}
    	else{
	      $Liste['Gemeinde'][]=$rs['gemeinde'];
	      $Liste['StrID'][]=$rs['strasse'];
	      $Liste['Gemarkung'][]=$rs['gemkgname'];
	      $namen[]=$rs['strassenname'];		# eigentlichen Strassennamen sichern
	      if($Liste['Name'][$i-1] == $rs['strassenname']){
	      	$Liste['Name'][$i-1]=$Liste['Name'][$i-1].' ('.$Liste['Gemarkung'][$i-1].')';
	      	$Liste['Name'][$i]=$rs['strassenname'].' ('.$rs['gemkgname'].')';
	      }
	      else{
	      	$Liste['Name'][]=$rs['strassenname'];
	      }
    	}
      $i++;
    }
    return $Liste;
  }
	
	function getHausNrListe($GemID,$StrID,$HausNr,$PolygonWKTString,$order) {
    # 2006-01-31
    $order='ordernr';
    # Abfragen der Hausnummern
    $sql ="SELECT id,nrtext,ordernr FROM (";
    # Subselect zum Zusammenfassen und Sortieren der Bestandteile der Abfrage
    $sql.="SELECT DISTINCT CASE WHEN TRIM(nr)='' THEN 'ohne' ELSE LOWER(id) END AS id, CASE WHEN TRIM(nr)='' THEN 'ohne Nr' ELSE TRIM(nr) END AS nrtext";
    $sql.=",CAST (CASE WHEN TRIM(ordernr)='' THEN '0' ELSE SPLIT_PART(TRIM(ordernr),' ',1) END AS numeric) as ordernr FROM (";
    # Abfrage der leeren Auswahlzeile
    $sql.="SELECT '-1' AS id,'--Auswahl--' AS nr, '-1' AS ordernr";
    $sql.=" UNION";
    # Abfrage der Hausnummern aus dem ALK-Bestand
    $sql.=" SELECT '".$GemID."-".$StrID."-'||TRIM(".HAUSNUMMER_TYPE."(alk.hausnr)) AS id,".HAUSNUMMER_TYPE."(alk.hausnr) AS nrtext,alk.hausnr AS ordernr";
    $sql.=" FROM alknhaus AS alk,alkobj_e_fla AS alkfl,alb_v_strassen AS s";
    $sql.=" WHERE alkfl.folie='011'";
    # $sql.=" AND alkfl.the_geom && st_geometryfromtext('".$PolygonWKTString."',".EPSGCODE.")";
    if ($PolygonWKTString!='') {
      $sql.=" AND st_intersects(alkfl.the_geom,st_geometryfromtext('".$PolygonWKTString."',".EPSGCODE."))";
    }
    $sql.=" AND alk.objnr=alkfl.objnr AND alk.gemeinde=s.gemeinde AND alk.strasse=s.strasse";
    if ($GemID!='') {
      $sql.=" AND alk.gemeinde=".(int)$GemID;
    }
    if ($StrID!='') {
      $sql.=" AND alk.strasse='".$StrID."'";
    }
    # Ende
    $sql.=" UNION";
    # Abfrage der Hausnummern aus dem ALB-Bestand
    # Anfang
    $sql.=" SELECT '".$GemID."-".$StrID."-'||alb.hausnr AS id, ".HAUSNUMMER_TYPE."(alb.hausnr) AS nrtext,alb.hausnr AS ordernr FROM alb_f_adressen AS alb, alb_flurstuecke as f WHERE (1=1) AND f.flurstkennz = alb.flurstkennz AND f.status != 'H' ";
    if ($GemID!='') {
      $sql.=" AND alb.gemeinde=".(int)$GemID;
    }
    if ($StrID!='') {
      $sql.=" AND alb.strasse='".$StrID."'";
    }
    # ende
    $sql.=") AS foo ORDER BY ".$order;
    $sql.=") AS foofoo";
    #echo $sql;
    $this->debug->write("<p>postgres getHausNrListe Abfragen der Strassendaten:<br>".$sql,4);
    $queryret=$this->execSQL($sql, 4, 0);
    while ($rs=pg_fetch_array($queryret[1])) {
      $Liste['HausID'][]=$rs['id'];
      $Liste['HausNr'][]=$rs['nrtext'];
    }
    return $Liste;
  }
	
	function getIntersectedFlurstWithJagdbezirke($oids){
		$sql = "SELECT alb.gemkgschl, gemkgname, alb.flurstkennz, alb.flurnr as flur, substring(alb.flurstkennz from 12 for 5) as zaehler, substring(alb.flurstkennz from 18 for 3) as nenner, st_area(alkobj_e_fla.the_geom) AS flurstflaeche, round(st_area(st_intersection(alkobj_e_fla.the_geom, jagdbezirke.the_geom)) * (alb.flaeche/st_area(alkobj_e_fla.the_geom))) AS schnittflaeche, jagdbezirke.name, jagdbezirke.art, alb.flaeche AS albflaeche";
		$sql.= " FROM alb_v_gemarkungen, alknflst, alkobj_e_fla, jagdbezirke, alb_flurstuecke AS alb";
		$sql.= " WHERE alb_v_gemarkungen.gemkgschl = CAST(alknflst.gemkgschl AS integer) AND alknflst.objnr = alkobj_e_fla.objnr";
		$sql.= " AND jagdbezirke.oid IN (".implode(',', $oids).")";
		$sql.= " AND alkobj_e_fla.the_geom && jagdbezirke.the_geom AND st_intersects(alkobj_e_fla.the_geom, jagdbezirke.the_geom)";
		$sql.= " AND st_area(st_intersection(alkobj_e_fla.the_geom, jagdbezirke.the_geom)) > 1";
		$sql.= " AND alb.flurstkennz = alknflst.flurstkennz ORDER BY jagdbezirke.name";
		return $this->execSQL($sql, 4, 0);
	}
	
	function getEigentuemerListeFromJagdbezirke($oids){
		$sql = "SELECT round((st_area(st_memunion(the_geom_inter))*100/j_flaeche)::numeric, 2) as anteil_alk, round((sum(flaeche)*(st_area(st_memunion(the_geom_inter))/st_area(st_memunion(the_geom))))::numeric, 2) AS albflaeche, eigentuemer";
		$sql.= " FROM(SELECT distinct st_area(jagdbezirke.the_geom) as j_flaeche, alb.flaeche, array_to_string(array(";
		$sql.= " select rtrim(name1,',') from alb_g_eigentuemer ee, alb_g_namen nn";
		$sql.= " where ee.lfd_nr_name=nn.lfd_nr_name and ee.bezirk=e.bezirk and ee.blatt=e.blatt";
		$sql.= " order by rtrim(name1,',')),' || ') as eigentuemer, st_intersection(alkobj_e_fla.the_geom, jagdbezirke.the_geom) as the_geom_inter, alkobj_e_fla.the_geom";
		$sql.= " FROM alknflst, alkobj_e_fla, jagdbezirke, alb_flurstuecke AS alb, alb_g_namen n, alb_g_eigentuemer e, alb_g_buchungen b";
		$sql.= " WHERE alknflst.objnr = alkobj_e_fla.objnr AND jagdbezirke.oid IN (".implode(',', $oids).") AND alkobj_e_fla.the_geom && jagdbezirke.the_geom";
		$sql.= " AND intersects(alkobj_e_fla.the_geom, jagdbezirke.the_geom) AND st_area(st_intersection(alkobj_e_fla.the_geom, jagdbezirke.the_geom)) > 1";
		$sql.= " AND alb.flurstkennz = alknflst.flurstkennz AND e.lfd_nr_name=n.lfd_nr_name AND e.bezirk=b.bezirk";
		$sql.= " AND e.blatt=b.blatt AND b.flurstkennz=alb.flurstkennz) as foo";
		$sql.= " group by eigentuemer, j_flaeche";
		return $this->execSQL($sql, 4, 0);
	}
	
	function check_poly_in_flur($polygon, $epsg){
		$sql = "SELECT alknflur.gemkgschl, alknflur.flur FROM alkobj_e_fla, alknflur WHERE alknflur.objnr = alkobj_e_fla.objnr AND st_intersects(the_geom, st_transform(st_geomfromtext('".$polygon."', ".$epsg."), ".EPSGCODE."))";
		return $this->execSQL($sql,4, 1);
	}
	
	function getFlurstuecksKennzByGemeindeIDs($Gemeinde_ID, $FlurstKennz){
    $sql ="SELECT f.flurstkennz FROM alb_flurstuecke AS f, alb_v_gemarkungen AS gk";
    $sql.=" WHERE f.gemkgschl=gk.gemkgschl AND gk.gemeinde IN ('".$Gemeinde_ID[0]['ID']."'";
    for($i = 1; $i < count($Gemeinde_ID); $i++){
      $sql .= ", '".$Gemeinde_ID[$i]['ID']."'";
    }
    $sql .= ")";
    $sql.=" AND f.flurstkennz IN ('".$FlurstKennz[0]."'";
    for ($i=1;$i<count($FlurstKennz);$i++) {
      $sql.=", '".$FlurstKennz[$i]."'";
    }
    $sql.=")";
    $this->debug->write("<p>postgresql.php getFlurstuecksKennzByGemeindeIDs() Abfragen erlaubten Flurstï¿½ckskennzeichen nach Gemeindeids:<br>".$sql,4);
    $query=pg_query($sql);
    if ($query==0) {
      $ret[0]=1; $ret[1]="Fehler bei der Abfrage der zur Anzeige erlaubten Flurstï¿½cke";
      $this->debug->write("<br>Abbruch in postgresql.php getFlurstuecksKennzByGemeindeIDs Zeile: ".__LINE__."<br>sql: ".$sql,4);
      return $ret;
    }
    while($rs=pg_fetch_array($query)) {
      $ret[1][]=$rs["flurstkennz"];
    }
    return $ret;
  }
	
	function getFlurstKennzListeByGemSchlByStrSchl($GemeindeSchl,$StrassenSchl,$HausNr) {
    $sql ="SELECT alb_f_adressen.flurstkennz FROM alb_f_adressen, alb_flurstuecke";
    $sql.=" WHERE alb_flurstuecke.flurstkennz = alb_f_adressen.flurstkennz";
    $sql.=" AND status != 'H'";
    if ($HausNr!='') {
    	if($HausNr == 'ohne'){
    		$HausNr = '';
    	}
    	if(strpos($HausNr, ', ') !== false){							# wenn mehrere Hausnummern:					1, 2, 3a, 4
    		$HausNr = str_replace(", ", "','", $HausNr);		# Hochkommas dazwischen hinzufï¿½gen: 1','2','3a','4
    		$sql.=" AND gemeinde||'-'||strasse||'-'||TRIM(".HAUSNUMMER_TYPE."(hausnr)) IN ('".$HausNr."')";		# und noch die ï¿½uï¿½eren:      			 '1','2','3a','4'
    	}
    	else{
      	$sql.=" AND gemeinde||'-'||strasse||'-'||TRIM(".HAUSNUMMER_TYPE."(hausnr))='".$HausNr."'";
    	}
    }
    else{
    	$sql.=" AND gemeinde=".(int)$GemeindeSchl;
    	$sql.=" AND strasse='".$StrassenSchl."'";
    }
    #echo $sql;
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
        $FlurstKennz[]=$rs['flurstkennz'];
      }
      $ret[1]=$FlurstKennz;
    }
    return $ret;
  }
	
	function getStrNameByID($GemID,$StrID) {
    $sql ="SELECT DISTINCT strassenname FROM alb_v_strassen WHERE gemeinde= ".$GemID;
    $sql.=" AND strasse = '".$StrID."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $rs=pg_fetch_array($queryret[1]);
      $StrID=$rs[0];
      $ret[1]=$StrID;
    }
    return $ret;
  }
	
	function getGrundbuchbezirkslisteByGemkgIDs($gemkg_ids){
  	$sql = "SELECT DISTINCT g.* FROM alb_v_grundbuchbezirke as g, alb_g_buchungen as b, alb_flurstuecke AS f ";
		$sql.= "WHERE b.bezirk = g.grundbuchbezschl ";
		$sql.= "AND f.flurstkennz = b.flurstkennz ";
		$sql.= "AND f.gemkgschl IN (".$gemkg_ids[0];
		for($i = 1; $i < count($gemkg_ids); $i++){
			$sql.= ",".$gemkg_ids[$i];
		}
		$sql.= ")";
		#echo $sql;
		$ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
    	while($rs=pg_fetch_array($ret[1])){
      	$liste['schluessel'][]=$rs['grundbuchbezschl'];
      	$liste['bezeichnung'][]=$rs['bezeichnung'];
      	$liste['beides'][]=$rs['bezeichnung'].' ('.$rs['grundbuchbezschl'].')';
    	}
    }
    return $liste;
  }
	
	function getGrundbuecher($FlurstKennz) {
    $sql ="SELECT DISTINCT gb.bezirk,gb.blatt,gb.zusatz_eigentuemer";
    $sql.=" FROM alb_g_buchungen AS b,alb_grundbuecher AS gb";
    $sql.=" WHERE b.bezirk=gb.bezirk AND b.blatt=gb.blatt";
    $sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    $sql.=" AND b.flurstkennz='".$FlurstKennz."' ORDER BY gb.bezirk,gb.blatt";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while($rs=pg_fetch_array($ret[1])) {
      $Grundbuch[]=$rs;
    }
    $ret[1]=$Grundbuch;
    return $ret;
  }
	
	function getEigentuemerliste($FlurstKennz,$Bezirk,$Blatt,$BVNR) {
    # 28.11.2006 H.Riedel, Eigentï¿½merart hinzugefï¿½gt
    # $sql = "SELECT e.namensnr,n.name1,n.name2,n.name3,n.name4,e.anteilsverhaeltnis AS Anteil";
    $sql = "SELECT e.namensnr,n.lfd_nr_name,n.name1,n.name2,n.name3,n.name4,e.anteilsverhaeltnis AS anteil,e.eigentuemerart AS Art";
    $sql.= " FROM alb_g_eigentuemer AS e,alb_g_namen AS n,alb_g_grundstuecke AS g,alb_g_buchungen AS b";
    $sql.= " WHERE e.lfd_nr_name=n.lfd_nr_name AND e.bezirk = g.bezirk AND e.blatt=g.blatt";
    $sql.= " AND g.bezirk=b.bezirk AND g.blatt=b.blatt AND g.bvnr=b.bvnr";
    if ($Bezirk!="") {
      $sql.=" AND b.bezirk=".(int)$Bezirk;
    }
    if ($Blatt!="") {
      $sql.=" AND b.blatt='".$Blatt."'";
    }
    if ($BVNR!="") {
      $sql.=" AND b.bvnr='".$BVNR."'";
    }
    $sql.= " AND b.flurstkennz = '".$FlurstKennz."' ORDER BY namensnr;";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0] OR pg_num_rows($ret[1])==0) { return $ret; }
    while ($rs=pg_fetch_array($ret[1])) {
      $Grundbuch = new grundbuch("","",$this->debug);
      
      $part = explode('.', $rs['namensnr']);
      if($part[1] != ''){
      	$part[1] = intval($part[1]);
      }
      $rs['namensnr'] = implode('.', $part);
      
      $Eigentuemer = new eigentuemer($Grundbuch,$rs['namensnr']);

      $Eigentuemer->lfd_nr=$rs['lfd_nr_name'];

      $plztest = explode('     ', $rs['name3']);
      if($plztest[1] != ''){
        $plztest[1] = '  PLZPF '.trim($plztest[1]);
        $plztest[2] = 'PF '.trim($plztest[2]);
        $rs['name3'] = implode('   ', $plztest);
      }

      $Eigentuemer->Name[]=$rs['name1'];
      $Eigentuemer->Name[] = $rs['name2'];
      $Eigentuemer->Name[] = $rs['name3'];
      $Eigentuemer->Name[] = $rs['name4'];
      $Eigentuemer->Anteil=$rs['anteil'];
      $Eigentuemerliste[]=$Eigentuemer;
    }
    $retListe[0]=0;
    $retListe[1]=$Eigentuemerliste;
    return $retListe;
  }
	
	function getGrundbuchbezirke($FlurstKennz) {
		$sql ="SELECT b.bezirk AS Schluessel,g.bezeichnung AS Name FROM alb_g_buchungen AS b,alb_v_grundbuchbezirke AS g, alb_grundbuecher AS gb";
    $sql.=" WHERE b.bezirk=g.grundbuchbezschl AND b.flurstkennz = '".$FlurstKennz."'";
    $sql.=" AND b.bezirk=gb.bezirk AND b.blatt=gb.blatt";
    $sql.=" AND gb.aktualitaetsnr NOT LIKE 'hist'";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]) {
      $Bezirk['Name']="nicht gefunden";
      $Bezirk['Schluessel']="0";
    }
    else {
      if (pg_num_rows($ret[1])==0) {
        $Bezirk['Name']="nicht gefunden";
        $Bezirk['Schluessel']="0";
      }
      else {
        $Bezirk=pg_fetch_array($ret[1]);
      }
    }
    return $Bezirk;
  }
	
	function getAmtsgerichtby($flurstkennz, $bezirk){
		$sql ="SELECT DISTINCT gb.amtsgericht AS schluessel,a.name FROM alb_g_buchungen AS b,alb_flurstuecke AS f";
    $sql.=",alb_v_grundbuchbezirke AS gb,alb_v_amtsgerichte AS a";
    $sql.=" WHERE gb.grundbuchbezschl=b.bezirk AND b.flurstkennz=f.flurstkennz";
    $sql.=" AND gb.amtsgericht=a.amtsgericht AND f.flurstkennz='".$flurstkennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      $ret[1]=pg_fetch_array($queryret[1]);
    }
    return $ret;
	}
	
	function getLage($FlurstKennz) {
    # liefert die Lage des Flurstï¿½ckes
    $sql = "SELECT lagebezeichnung FROM alb_f_lage WHERE flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      if (pg_num_rows($queryret[1])>0) {
        while($rs=pg_fetch_array($queryret[1])) {
          $Lage[]= $rs['lagebezeichnung'];
        }
      }
      $ret[1]=$Lage;
    }
    return $ret;
  }
	
	function getStrassen($FlurstKennz) {
    # Abfrage der Adressenangabe zum Flurstï¿½ck
    # 1. Abfragen der Strassen die am Flurstï¿½ck liegen
    $sql ="SELECT DISTINCT gem.gemeindename,str.strasse,str.strassenname";
    $sql.=" FROM alb_f_adressen AS adr,alb_v_gemeinden AS gem,alb_v_strassen AS str";
    $sql.=" WHERE adr.gemeinde=gem.gemeinde AND adr.strasse=str.strasse";
    $sql.=" AND str.gemeinde=gem.gemeinde";
    $sql.=" AND adr.flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      while($rs=pg_fetch_array($queryret[1])) {
        $Strassen[]=$rs;
      }
      $ret[1]=$Strassen;
    }
    return $ret;
  }
	
	function getHausNummern($FlurstKennz,$Strasse) {
    # Abfragen der Hausnummern zu den jeweiligen Strassen
    $sql ="SELECT DISTINCT ".HAUSNUMMER_TYPE."(adr.hausnr) AS hausnr";
    $sql.=" FROM alb_f_adressen AS adr,alb_v_gemeinden AS gem,alb_v_strassen AS str";
    $sql.=" WHERE adr.gemeinde=gem.gemeinde AND adr.strasse=str.strasse";
    $sql.=" AND str.strasse = '".$Strasse."'";
    $sql.=" AND adr.flurstkennz = '".$FlurstKennz."'";
    #echo $sql;
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0]) {
      $ret[0]=1;
      $ret[1]=$queryret[1];
    }
    else {
      $ret[0]=0;
      while($rs=pg_fetch_array($queryret[1])) {
        $HausNr[]=$rs[0];
      }
      $ret[1]=$HausNr;
    }
    return $ret;
  }
	
	function getNutzung($FlurstKennz) {
    $sql ="SELECT nutz.flaeche AS flaeche,'21-' || nutz.nutzungsart AS nutzungskennz";
    $sql.=",art.bezeichnung,art.abkuerzung FROM";
    $sql.=" alb_f_nutzungen AS nutz,alb_v_nutzungsarten AS art WHERE nutz.nutzungsart=art.nutzungsart";
    $sql.=" AND flurstkennz = '".$FlurstKennz."'";
    $queryret=$this->execSQL($sql, 4, 0);
    if ($queryret[0] OR pg_num_rows($queryret[1])==0) {
      # keine Eintragungen zu Nutzungen gefunden
      return $queryret;
    }
    # Nutzungen zum Flurstï¿½ck wurden erfolgreich abgefragt
    while($rs=pg_fetch_array($queryret[1])) {
      $Nutzungen[]=$rs;
    }
    $ret[0]=0;
    $ret[1]=$Nutzungen;
    return $ret;
  }
	
	function getFlurstueckeByGrundbuchblatt($bezirk, $blatt) {
    $sql ="SELECT DISTINCT b.flurstkennz FROM alb_g_buchungen AS b";
    $sql.=" WHERE b.bezirk = ".$bezirk." AND b.blatt = '".$blatt."'";
    $ret=$this->execSQL($sql, 4, 0);
    if ($ret[0]==0) {
      while($rs=pg_fetch_array($ret[1])) {
        $FlurstKennz[]=$rs['flurstkennz'];
      }
      $ret[1]=$FlurstKennz;
    }
    return $FlurstKennz;
  }
	
}

?>