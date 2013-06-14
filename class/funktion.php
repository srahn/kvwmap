<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2008  Peter Korduan                               #
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
# users.php Klassenbibliothek zu Nutzern und Stellen #
######################################################
# Copyright (C) 2008  Peter Korduan
# This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
#
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de
#
############################################################################################
#########################
# class_user #
class funktion {

  function funktion($database) {
    global $debug;
    $this->debug=$debug;
    $this->database=$database;
  }
  
  function getFunktionen($id, $order) {
    $sql ='SELECT * FROM u_funktionen WHERE 1=1';
    if ($id>0) {
      $sql.=' AND id='.$id;
    }
    if ($order!='') {
      $sql.=' ORDER BY '.$order;
    }
    $this->debug->write("<p>file:users.php class:funktion->getFunktionen - Abfragen einer oder aller Funktionen:<br>".$sql,4);
    $query=mysql_query($sql,$this->database->dbConn);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $funktionen[]=$rs;
    }
    return $funktionen;
  }
  
  function NeuAnlegen($formvars){
  	$sql = "INSERT INTO u_funktionen SET ";
  	if($formvars['id']){
  		$sql.= "id = ".(int)$formvars['id']."," ;
  	}
  	$sql.= "bezeichnung = '".$formvars['bezeichnung']."'";
  	$ret=$this->database->execSQL($sql,4, 1);
  	$ret[1] = mysql_insert_id();
  	return $ret;
  }
  
  function Aendern($formvars){
  	$sql = "UPDATE u_funktionen SET id = ".(int)$formvars['id'].", bezeichnung = '".$formvars['bezeichnung']."' ";
  	$sql.= "WHERE id = ".(int)$formvars['selected_function_id'];
  	$ret=$this->database->execSQL($sql,4, 1);
  }
  
  function Loeschen($formvars){
  	$sql = "DELETE FROM u_funktionen ";
  	$sql.= "WHERE id = ".(int)$formvars['selected_function_id'];
  	$ret=$this->database->execSQL($sql,4, 1);
  	$sql = "DELETE FROM u_funktion2stelle ";
  	$sql.= "WHERE funktion_id = ".(int)$formvars['selected_function_id'];
  	$ret=$this->database->execSQL($sql,4, 1);
  }
    
}

?>
