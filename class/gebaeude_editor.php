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

class gebaeude_editor {
    
  ################### Liste der Funktionen ########################################################################################################
  # gebaeude_editor($database)
  # eintragenNeueNummer()
  ##################################################################################################################################################

  function gebaeude_editor($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }
  
  function gebaeude_aendern($oid, $point, $gemeinde, $strasse, $nummer, $zusatz, $kommentar){
  	$sql = "SELECT astext(the_geom) FROM alkobj_e_fla AS o WHERE (o.folie = '011' OR o.folie = '084') AND within(st_geomfromtext('".$point."',2398), the_geom)";
  	$ret=$this->database->execSQL($sql,4, 1);
    if (!$ret[0]) {
    	$rs = pg_fetch_array($ret[1]);
    	$geom = $rs[0];
    }
    $sql ="UPDATE gebaeude_hausnummern SET ";
		$sql.="gemeinde = ".$gemeinde.", ";
		$sql.="strasse = ".$strasse.", ";
		$sql.="nummer = ".$nummer.", ";
		$sql.="zusatz = '".$zusatz."', ";
		$sql.="kommentar = '".$kommentar."', ";
		$sql.="rechtswert = x(st_geomfromtext('".$point."')), ";
		$sql.="hochwert = y(st_geomfromtext('".$point."')), ";
    $sql.="the_geom = st_geomfromtext('".$geom."', 2398) ";
    $sql.="WHERE oid = ".$oid;
    # echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
    	return 'Eintrag fehlgeschlagen.';
    }
    else{
    	return 'Eintrag erfolgreich.';
    }
  }
  
  function eintragenNeuesGebaeude($point, $gemeinde, $strasse, $nummer, $zusatz, $kommentar) {
  	$sql = "SELECT astext(the_geom) FROM alkobj_e_fla AS o WHERE (o.folie = '011' OR o.folie = '084') AND within(st_geomfromtext('".$point."',2398), the_geom)";
  	$ret=$this->database->execSQL($sql,4, 1);
    if (!$ret[0]) {
    	$rs = pg_fetch_array($ret[1]);
    	$geom = $rs[0];
    }
  	 
    $sql ="INSERT INTO gebaeude_hausnummern (gemeinde, strasse, nummer, zusatz, kommentar, rechtswert, hochwert, the_geom)";
    $sql.=" VALUES (".$gemeinde.", ".$strasse.", ".$nummer.", '".$zusatz."', '".$kommentar."', x(st_geomfromtext('".$point."')), y(st_geomfromtext('".$point."')), st_geomfromtext('".$geom."', 2398))";
    # echo $sql;
    $ret=$this->database->execSQL($sql,4, 1);
    if ($ret[0]) {
    	return 'Eintrag fehlgeschlagen.';
    }
    else{
    	return 'Eintrag erfolgreich.';
    } 
  }
  
  function load_gebaeude($oid){
  	$sql = "SELECT gemeinde, strasse, nummer, zusatz, kommentar, ";
  	$sql.= "assvg(snapline(linefrompoly(the_geom),st_geomfromtext('POINT('||rechtswert||' '||hochwert||')',2398)),0,5) AS segment FROM gebaeude_hausnummern AS gh WHERE gh.oid = ".$oid." AND within(st_geomfromtext('POINT('||rechtswert||' '||hochwert||')',2398), the_geom)";
  	#echo $sql;
  	$ret=$this->database->execSQL($sql,4, 0);
    if (!$ret[0]) {
    	$rs = pg_fetch_array($ret[1]);
    }
    return $rs;
  } 
}
?>
