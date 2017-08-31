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
#####################################
# Klassen zur Metadatenverarbeitung #
#####################################

########################
# Klasse Metadatensatz #
########################
class metadatensatz {
  var $MD_id;
  var $debug;
    
  ################### Liste der Funktionen #######################
  #
  ################################################################

  function metadatensatz($MD_id,$db) {
    global $debug;
    $this->debug=$debug;
    if ($MD_id!='') {
      $this->MD_id=$MD_id;
    }
    $this->database=$db;
  }
  
  function getMetadaten($md) {
    # Liesst Metadatenwerte zu einer übergegebenen Metadatensatz_id
    $ret=$this->database->getMetadata($md);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen der Datenbank'.$ret[1];
    }
    else {
      $this->anzMetadatensaetze=count($ret[1]);
    }
    return $ret;
  }
  
  function getMetadatenQuickSearch($md) {
    # Durchsucht die Datenbank mit der Schnellsuche nach Metadatensätzen
    $ret=$this->database->getMetadataQuickSearch($md);
    if ($ret[0]) {
      $ret[1]='Fehler beim Abfragen der Datenbank'.$ret[1];
    }
    else {
      $this->anzMetadatensaetze=count($ret[1]);
    }
    return $ret;
  }
  
  function readDefaultValues($user) {
    #2005-11-29_pk
    # Weißt Standardwerte zu den Metadatenfeldern zu und liefert diese als Array zurück
    $md['mdfileid']=rand();
    $md['mddatest']=date('Y-m-d');
    $md['reseddate']=date('Y-m-d');
    $md['validfrom']=date('Y-m-d');
    $md['validtill']=date('Y-m-d',mktime(0, 0, 0, date('m'),  date('d'),  date('Y')+1));
    $md['westbl']=round($user->rolle->oGeorefExt->minx);
    $md['eastbl']=round($user->rolle->oGeorefExt->maxx);
    $md['southbl']=round($user->rolle->oGeorefExt->miny);
    $md['northbl']=round($user->rolle->oGeorefExt->maxy);
    $md['serviceversion']='1.0.0';
    return $md;
  }
  
  function getKeywords($id,$keyword,$keytyp,$thesaname,$metadata_id,$order) {
    $ret=$this->database->getKeywords($id,$keyword,$keytyp,$thesaname,$metadata_id,$order);
    return $ret;
  }
  
  function speichern($metadaten) {
    #2005-11-29_pk
    # Prüfen der Metadaten
    if ($ret[0]) {
      $ret[1]='<br>Fehler beim Prüfen der Metadaten.'.$ret[1];
    }
    else {
      if ($metadaten['id']) {
        # Es handelt sich um schon vorhandene Metadaten, die aktualisiert werden sollen
        # UPDATE
      }
      else {
        # Neuer Metadatensatz INSERT
        $ret=$this->database->insertMetadata($metadaten);
      }
    }
    return $ret;
  }
  
  function checkMetadata($md) {
    #2005-11-29_pk
    # Prüft die eingegebenen Metadaten auf Richtigkeit und formt gegebenenfalls Datentypen um
    if ($md['restitle']=='') { $errmsg='<br>Geben Sie einen Titel an.'; }
    if ($md['mdfileid']=='') {
      $errmsg='<br>Geben Sie eine eindeutige Metadatenid an.';
    }
    else {
      # Abfragen ob es diese eindeutige Kennung schon in der Datenbank gibt
      $ret=$this->database->getMetadataByMdFileID($md['mdfileid']);
      var_dump($ret);
      if ($ret[0]) {
        $errmsg.='<br>Fehler beim Abfragen der neuen Metadatenid.<br>'.$ret[1];
      }
      else {
        if ($ret[1]['mdfileid']==$md['mdfileid']) {
          $errmsg.='<br>Die angegebene Identifikation ist schon vorhanden.';
        }
      }
    }

    if ($md['postcode']=='') { $md['postcode']='NULL'; }
    if ($md['vector_scale']=='') { $md['vector_scale']='NULL'; }
    if ($md['mdcontact']=='') { $md['mdcontact']='NULL'; }
    if ($md['spatrepinfo']=='') { $md['spatrepinfo']='NULL'; }
    if ($md['refsysinfo']=='') { $md['refsysinfo']='NULL'; }
    if ($md['mdextinfo']=='') { $md['mdextinfo']='NULL'; }
    if ($md['dataidinfo']=='') { $md['dataidinfo']='NULL'; }
    if ($md['continfo']=='') { $md['continfo']='NULL'; }
    if ($md['distinfo']=='') { $md['distinfo']='NULL'; }
    if ($md['databinding']=='') { $md['databinding']=0; }
    # Zusammenfassen der selectierten Schlagwörter
    if ($md['selectedthemekeywordids']=='') {
      $errmsg.='<br>Geben Sie thematische Schlagwörter ein.';
    }
    else {
      $keywords=array_unique(explode(", ",$md['selectedthemekeywordids']));
      $md['selectedthemekeywordids']=$keywords[0];
      for ($i=1;$i<count($keywords);$i++) {
        $md['selectedthemekeywordids'].=", ".$keywords[$i];
      }
    }
    if ($md['selectedplacekeywordids']=='') {
      $errmsg.='<br>Geben Sie räumliche Schlagwörter ein.';
    }
    else {
      $keywords=array_unique(explode(", ",$md['selectedplacekeywordids']));
      $md['selectedplacekeywordids']=$keywords[0];
      for ($i=1;$i<count($keywords);$i++) {
        $md['selectedthemekeywordids'].=", ".$keywords[$i];
      }
    }
    $md['umring'] ='POLYGON(('.$md['eastbl'].' '.$md['southbl'].','.$md['westbl'].' '.$md['southbl'];
    $md['umring'].=','.$md['westbl'].' '.$md['northbl'].','.$md['eastbl'].' '.$md['northbl'];
    $md['umring'].=','.$md['eastbl'].' '.$md['southbl'].'))';
    if ($errmsg!='') {
      $ret[0]=1; $ret[1]=$errmsg;
    }
    else {
      $ret[0]=0; # fehlerfrei
      $ret[1]=$md;
    }
    return $ret;
  }
} # Ende Klasse Metadaten

?>