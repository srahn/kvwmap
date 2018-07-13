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
# Klasse Converter #
#############################

class Converter {

  public static	function find_by_id($gui, $by, $id) {
    #echo '<br>find konvertierung by ' . $by . ' = ' . $id;
    $konvertierung = new Konvertierung($gui);
    $konvertierung->find_by($by, $id);
    return $konvertierung;
  }

   function save($path){
    rewind($this->tmpFile);
    $file = fopen($path,'w');

    stream_copy_to_stream($this->tmpFile,$file);

    fclose($file);
  }
  
  function Converter($structDb, $contentDb) {
    $this->structDb = $structDb;
    $this->contentDb = (empty($contentDb)) ? $structDb : $contentDb;
  }

  function findRPPlanByKonvertierung($konvertierung) {
    $sql = "SELECT gml_id FROM gml_classes.rp_plan WHERE konvertierung_id == " . $konvertierung->get('id');
    $result = pg_query($this->contentDb->dbConn, $sql);
    return pg_fetch_assoc($result)['gml_id'];
  }

  function convert($konvertierung) {
    $konv_id = $konvertierung->get('id');
    $sql = "
        SELECT
          oid AS id,
          nspname AS name,
          'Ruleset A' AS ruleset,
          0 AS status
        FROM pg_namespace WHERE nspowner = 16385
    ";
    $result = pg_query($this->contentDb->dbConn, $sql);
    $data = array();
    while ($konvertierung = pg_fetch_assoc($result)) {
      $data[] = $konvertierung;
    }
    return $data;
  }

  function regeln_anwenden($konvertierung_id) {
    #echo '<h1>Wende Regeln für Konvertierung ' . $konvertierung_id . ' an<br></h1>';
    
    # Lösche alle vorher existierenden gml_classes für eine Konvertierung
    # Regeln der Konvertierung abfragen
    #Wählt alle Regeln aus, die entweder eine Konvertierungsid besitzen oder 
    #ansonsten Konvertierung nach Plan abfragen
    # $Konvertierung_id entspricht Spalte konvertierung_id in gml_classes.rp_plan
    # gml_id in gml_classes.rp_plan entspricht gehoertzuplan in gml_classes.rp_bereich
    # gml_id in rp_bereich entspricht bereich_gml_id in xplankonverter.regeln
    $sql = "
      SELECT
        r.sql
      FROM
        xplankonverter.regeln r
      INNER JOIN
        gml_classes.rp_bereich b
      ON 
        r.bereich_gml_id = b.gml_id
      INNER JOIN
        gml_classes.rp_plan p 
      ON
        b.gehoertzuplan = p.gml_id
      WHERE
        p.konvertierung_id = " . $konvertierung_id . "
      OR
        r.konvertierung_id = " . $konvertierung_id . "
    ";
    $result = pg_query($this->contentDb->dbConn, $sql);
     /*while ($row_sql = pg_fetch_row($result)){
      #Regel Ausführen:
     
      $result_regel_anwenden = pg_query($conn,  $row_sql[0]);
      if(!$result_regel_awenden){
        echo "Ein Fehler ist in der Regel" . $row_sql[0] . "aufgetreten!<br><br>";
      }
    }*/
  }
  
  function gmlfeatures_loeschen($konvertierung_id){
    # FUNKTIONIERT NOCH NICHT
    /*
    # Löscht alle Einträge, die mit der spezifischen $konvertierung_id assoziiert sind in allen Tabellen von gmlclasses
    
    # Selektiert die gml_ids aller Objekte mit der KonvertierungsId
    $sql ="
    SELECT
      rp_objekt_gml_id
    FROM
      gml_classes.rp_bereich2rp_objekt a
    INNER JOIN
      gml_classes.rp_bereich b
    ON 
      a.rp_bereich_gml_id = b.gml_id
    INNER JOIN
      gml_classes.rp_plan p 
    ON
      b.gehoertzuplan = p.gml_id
    WHERE
      p.konvertierung_id = ". $konvertierung_id . "
    ";
    $result = pg_query($this->contentDb->dbConn, $sql);
    while ($row_sql = pg_fetch_row($result)){
      $gmlfeature_gmlid[] = $row_sql[0];
      # echo $row_sql[0] . '<br><br>';
    }
    
    #Selektiert alle vorkommenden Featuretypen aus einer Liste (inklusive XP_Objekte, RP_Plan, RP_Bereiche etc)
    $sql = "
      SELECT
        table_name
      FROM
        information_schema.tables
      WHERE
        table_schema = 'gml_classes'
    ";
    $result = pg_query($this->contentDb->dbConn, $sql);
    while ($row_sql = pg_fetch_row($result)){
      $gmlclasses = $row_sql[0];
    }
    # Nun für jedes relevante Schema überprüfen, ob GML_ID vorkommt
    */
  }
  
}

?>
