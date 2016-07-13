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
# Klasse Gml_builder #
#############################

class Gml_builder {

  function Gml_builder($database) {
    global $debug;
    $this->debug = $debug;
    $this->database = $database;
    $this->tmpFile = tmpfile();
  }

  function __destruct() {
    fclose($this->tmpFile);
  }

  function findRPPlanByKonvertierung($konvertierung) {
    $sql = "SELECT gml_id FROM gml_classes.rp_plan WHERE konvertierung_id = " . $konvertierung->get('id');
    $result = pg_query($this->database->dbConn, $sql);
    return pg_fetch_assoc($result)['gml_id'];
  }

  function generate_gml($plan_id) {
    # XPlan XSD's sind derzeit unter: http://xplan-raumordnung.de/devk/model/2016-05-06_XSD hinterlegt
  $xplan_gml =
      "<XPlanAuszug
        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
        xmlns:wfs=\"http://www.opengis.net/wfs\"
        xmlns:gml=\"http://www.opengis.net/gml\"
        xmlns:xlink=\"http://www.w3.org/1999/xlink\"
        xmlns:xplan=\"http://xplan-raumordnung.de/devk/model/2016-05-06_XSD\"
        xmlns=\"http://xplan-raumordnung.de/devk/model/2016-05-06_XSD\"
        xsi:schemaLocation=\"http://www.xplanung.de/xplangml/3/0 ../../Schema/XPlanung-Operationen.xsd\">
        <gml:boundedBy>
          <gml:Envelope srsName=\"EPSG:31466\">
            <gml:pos>2490669.000 5576388.000</gml:pos>
            <gml:pos>2566284.000 5672835.000</gml:pos>
          </gml:Envelope>
        </gml:boundedBy>";
    $sql = "";
  }

  /*
  * Diese Funktion erzeugt einen gml-Text aus den
  * der Konvertierung und dem Plan zugeordneten Einträge
  * des GML-Mappings. Aufgrund der Größe der entstehenden
  * GML-Texte wird deren Inhalt in eine temporäre Datei
  * geschrieben. Die temporäre Datei wird vom Gml_builder
  * Objekt verwaltet.
  * Mit der Funktion save($path) kann die temporäre Datei in
  * eine Zieldatei gespeichert werden
  */
  function build_gml($konvertierung, $plan){
    # set encoding to UTF-8
    $old_encoding = mb_internal_encoding();
    mb_internal_encoding('UTF-8');
    $contentScheme   = "gml_classes";
    # clear tempfile
    ftruncate($this->tmpFile, 0);
    # XPlan XSD's sind derzeit unter: http://xplan-raumordnung.de/devk/model/2016-05-06_XSD/ hinterlegt
    fwrite($this->tmpFile,
      "<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>\n".
      "<XPlanAuszug\n".
      "  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n".
      "  xmlns:wfs=\"http://www.opengis.net/wfs\"\n".
      "  xmlns:gml=\"http://www.opengis.net/gml\"\n".
      "  xmlns:xlink=\"http://www.w3.org/1999/xlink\"\n".
      "  xmlns:xplan=\"http://xplan-raumordnung.de/model/xplangml/raumordnungsmodell\"\n".
      "  xmlns=\"http://xplan-raumordnung.de/model/xplangml/raumordnungsmodell\"\n".
      "  xsi:schemaLocation=\"http://xplan-raumordnung.de/model/xplangml/raumordnungsmodell/../../Schema/XPlanung-Operationen.xsd\"\n".
      ">\n".
      # TODO: <boundedBy> sollte für jeden Plan definiert oder festgehalten werden,
      # z.B. über Informationen in XP_Plan:raeumlicherGeltungsbereich oder eine
      # Erfassung vor der Konvertierung.
      "<gml:boundedBy>\n".
      "  <gml:Envelope srsName=\"EPSG:#####\">\n".
      "    <gml:pos>#######.### #######.###</gml:pos>\n".
      "    <gml:pos>#######.### #######.###</gml:pos>\n".
      "  </gml:Envelope>\n".
      "</gml:boundedBy>\n");
    // alle RP_Objekt-Namen holen, die zur Konvertierung gehören
    $sql =
      "SELECT DISTINCT r.class_name
       FROM xplankonverter.regeln r
       WHERE r.konvertierung_id = " . $konvertierung->get('id');
    $classNameSet = pg_query($this->database->dbConn, $sql);
    // das RP_Plan Element anlegen
    $gmlElemInner = "";
    $gmlElemOpenTag = "<RP_Plan";
    $gml_objectKeys = array_keys($plan->data);
    for ($i = 0; $i < count($gml_objectKeys); $i++){
      switch ($gml_objectKeys[$i]) {
        case "konvertierung_id":
          // do nothing
          break;
        case "gml_id":
          // gml_id is formated as element attribute
          $gmlElemOpenTag .= " gml:id=\"GML_{$plan->data['gml_id']}\"";
          break;
        default:
          $gml_value = trim($plan->data[$gml_objectKeys[$i]]);
          if (strlen($gml_value) == 0) continue;
          // all other fields go as a child element with inner text content
          // check for array values
          if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
            $gml_value_array = str_split(substr($gml_value, 1, -1));
            for ($j = 0; $j < count($gml_value_array); $j++){
              $gmlElemInner .= $this->buildSimpleElement("xplan:".$gml_objectKeys[$i],$gml_value_array[$j]);
            }
          } else
          $gmlElemInner .= $this->buildSimpleElement("xplan:".$gml_objectKeys[$i],$gml_value);
      }
    }
    $rp_plan = $gmlElemOpenTag . ">" . $gmlElemInner;
    // alle Bereiche suchen, die zum RP-Plan gehören
    $sql = "
        SELECT b.*
        FROM $contentScheme.rp_bereich b
        WHERE b.gehoertzuplan = '" . $plan->get('gml_id') . "'";
    $bereiche = pg_query($this->database->dbConn, $sql);
    # iterating bereiche in two passes:
    # first pass: complete RP_Plan element by iteratively inserting
    # xlink-references to each RP_Bereich element
    while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
      $rp_plan .= "<xplan:bereich xlink:href=\"#GML_" . $bereich['gml_id'] . "\"/>";
    }
    # close and write RP_Plan element
    fwrite($this->tmpFile, $this->wrapFeatureMember($rp_plan . "</RP_Plan>"));
    # second pass: iteratively building and writing gml for each RP_Bereich element
    pg_result_seek($bereiche, 0);
    while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
      $gmlElemInner = "";
      $gmlElemOpenTag = "<RP_Bereich";
      $gml_objectKeys = array_keys($bereich);
      for ($i = 0; $i < count($gml_objectKeys); $i++){
        switch ($gml_objectKeys[$i]) {
          case "gml_id":
            // format gml_id as an element attribute
            $gmlElemOpenTag .= " gml:id=\"GML_{$bereich['gml_id']}\"";
            break;
          case "gehoertzuplan":
            $gmlElemInner .= "<xplan:gehoertzuplan xlink:href=\"#GML_{$plan->data['gml_id']}\"/>";
            break;
          default:
            $gml_value = trim($bereich[$gml_objectKeys[$i]]);
            if (strlen($gml_value) == 0) continue;
            // all other fields go as a child element with inner text content
            // check for array values
            if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
              $gml_value_array = str_split(substr($gml_value, 1, -1));
              for ($j = 0; $j < count($gml_value_array); $j++){
                $gmlElemInner .= $this->buildSimpleElement("xplan:".$gml_objectKeys[$i],$gml_value_array[$j]);
              }
            } else
            $gmlElemInner .= $this->buildSimpleElement("xplan:".$gml_objectKeys[$i],$gml_value);
        }
      }
      $rp_bereich = $gmlElemOpenTag . ">" . $gmlElemInner;
      // alle gml_ids von RP_Objekten finden die mit dem Bereich verknüpft sind
      $sql = "
          SELECT b2o.rp_objekt_gml_id AS gml_id
          FROM
            $contentScheme.rp_bereich AS b JOIN
            $contentScheme.rp_bereich2rp_objekt AS b2o ON b.gml_id = b2o.rp_bereich_gml_id
          WHERE b.gml_id = '" . $bereich['gml_id'] ."'";
      $rp_objekte = pg_query($this->database->dbConn, $sql);
      # complete RP_Bereich element by iteratively inserting
      # xlink-references to each associated RP_Objekt element
      while ($rp_objekt = pg_fetch_array($rp_objekte, NULL, PGSQL_ASSOC)) {
        $rp_bereich .= "<xplan:fachobjekt xlink:href=\"#GML_" . $rp_objekt['gml_id'] . "\"/>";
      }
      # close and write RP_Bereich element
      fwrite($this->tmpFile, "\n" . $this->wrapFeatureMember($rp_bereich . "</RP_Bereich>"));
      // alle RP_Objekte finden die mit dem Bereich und der Konvertierung verknüpft sind
      while ($gml_className = pg_fetch_array($classNameSet)[0]) {
        $sql = "
            SELECT cl.*, st_asgml(cl.position) AS position
            FROM
              $contentScheme.rp_bereich AS b JOIN
              $contentScheme.rp_bereich2rp_objekt AS b2o ON b.gml_id = b2o.rp_bereich_gml_id JOIN
              $contentScheme.$gml_className AS cl ON b2o.rp_objekt_gml_id = cl.gml_id
            WHERE b.gml_id = '" . $bereich['gml_id'] ."'";
        $gml_objects = pg_query($this->database->dbConn, $sql);
        while ($gml_object = pg_fetch_array($gml_objects, NULL, PGSQL_ASSOC)) {
          $gmlElemInner = "";
          $gmlElemOpenTag = "<$gml_className";
          $gml_objectKeys = array_keys($gml_object);
          // alle Felder ausgeben
          // TODO: leere Felder nicht ausgeben
          for ($i = 0; $i < count($gml_objectKeys); $i++){
            switch ($gml_objectKeys[$i]) {
              case "konvertierung_id":
                // do nothing
                break;
              case "gml_id":
                // Verweis zum Bereich hinzufügen
                // gml_id is formated as element attribute
                $gmlElemOpenTag .= " id=\"GML_{$gml_object['gml_id']}\"";
                break;
              case "position":
                $gmlElemInner .= "<xplan:position>";
                $gmlElemInner .= $gml_object[$gml_objectKeys[$i]];
                $gmlElemInner .= "</xplan:position>";
                break;
              default:
                $gml_value = trim($gml_object[$gml_objectKeys[$i]]);
              // leere Felder auslassen
              if (strlen($gml_value) == 0) continue;
                // all other fields go as a child element with inner text content
                // check for array values
                if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
                  $gml_value_array = str_split(substr($gml_value, 1, -1));
                  for ($j = 0; $j < count($gml_value_array); $j++){
                    $gmlElemInner .= $this->buildSimpleElement("xplan:".$gml_objectKeys[$i],$gml_value_array[$j]);
                  }
                } else
                $gmlElemInner .= $this->buildSimpleElement("xplan:".$gml_objectKeys[$i],$gml_value);
            }
          }
          # close and write FeatureMember
          $gmlElemOpenTag .= ">";
          $objekt_gml = $gmlElemOpenTag . $gmlElemInner . "</" . $gml_className . ">";
          fwrite($this->tmpFile, "\n" . $this->wrapFeatureMember($objekt_gml));
        }
      }
    }
    # close XPlan
    fwrite($this->tmpFile, "\n</XPlanAuszug>");
    # reset internal string encoding
    mb_internal_encoding($old_encoding);
  }

  function build_gml_alt($plan_id = "365208ec-c418-11e5-995f-93757a8c548c"){
    include('constants.php');
    // make constants available as variables (easier to use in double-quoted strings)
    $structureScheme = STRUCTURE_SCHEME;
    $contentScheme   = CONTENT_SCHEME;
    # XPlan XSD's sind derzeit unter: http://xplan-raumordnung.de/devk/model/2016-05-06_XSD/ hinterlegt
    $xplan_gml =
      "<XPlanAuszug
        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
        xmlns:wfs=\"http://www.opengis.net/wfs\"
        xmlns:gml=\"http://www.opengis.net/gml\"
        xmlns:xlink=\"http://www.w3.org/1999/xlink\"
        xmlns:xplan=\"http://xplan-raumordnung.de/devk/model/2016-05-06_XSD\"
        xmlns=\"http://xplan-raumordnung.de/devk/model/2016-05-06_XSD\"
        xsi:schemaLocation=\"http://www.xplanung.de/xplangml/3/0 ../../Schema/XPlanung-Operationen.xsd\">
        <gml:boundedBy>
          <gml:Envelope srsName=\"EPSG:31466\">
            <gml:pos>2490669.000 5576388.000</gml:pos>
            <gml:pos>2566284.000 5672835.000</gml:pos>
          </gml:Envelope>
        </gml:boundedBy>";
    $sql =
      "SELECT *
      FROM $structureScheme.packages p
        RIGHT JOIN $structureScheme.uml_classes uc ON p.id = uc.package_id
      WHERE
        uc.xmi_id NOT IN (SELECT parent_id FROM $structureScheme.class_generalizations)
      AND
        p.name IN (" . PACKAGES . ")";
    $result = pg_query($this->database->dbConn, $sql);
    while ($gml_class = pg_fetch_array($result)) {
      $gml_className = strtolower($gml_class['name']);
      // check if the class exists
      // TODO: kann entfallen, sobald Struktur-Schema und Inhalts-Schema konsistent sind
      $sql = "SELECT oid
        FROM pg_class
        WHERE relname = '$gml_className' AND relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = '$contentScheme')";
      if (!($cur_class = pg_fetch_row(pg_query($this->database->dbConn, $sql)))) continue;
      // check, if there is a gml_id field in that relation
      $sql = "SELECT 1 FROM pg_attribute WHERE attrelid = {$cur_class[0]} AND attname = 'gml_id'";
      if (!pg_fetch_row(pg_query($this->database->dbConn, $sql))) continue;
      // check, if there is a position field in that relation
      $sql = "SELECT 1 FROM pg_attribute WHERE attrelid = {$cur_class[0]} AND attname = 'position'";
      $position_attr = "";
      if (pg_fetch_row(pg_query($this->database->dbConn, $sql))) {
        // position field exists --> fetch transformed position
        $position_attr = ", st_asgml(position) AS position";
      }
      $sql = "SELECT cl.* $position_attr
        FROM
          $contentScheme.rp_plan AS p JOIN
          $contentScheme.rp_bereich AS b ON p.gml_id = b.gehoertzuplan JOIN -- verknuepft plan mit bereich
          $contentScheme.rp_bereich2rp_objekt AS b2o ON b.gml_id = b2o.rp_bereich_gml_id JOIN -- verknuepft bereicht mit bereich2objekt
          $contentScheme.$gml_className AS cl ON b2o.rp_objekt_gml_id = cl.gml_id -- verknuepft bereich2objekt mit leaf class
        WHERE
        p.gml_id = '$plan_id' -- die gml_id vom plan";
      $gml_objects = pg_query($this->database->dbConn, $sql);
//     $num_rows = pg_num_rows($gml_objects);
//     echo "$sql ==> $num_rows";
      while ($gml_object = pg_fetch_array($gml_objects, NULL, PGSQL_ASSOC)) {
        $xplan_gml .= "<gml:featureMember>";
        $gmlElemInner = "";
        $gmlElemOpenTag = "<$gml_className";
        $gml_objectKeys = array_keys($gml_object);
        for ($i = 0; $i < count($gml_objectKeys); $i++){
          switch ($gml_objectKeys[$i]) {
            case "position":
              $gmlElemInner .= "<position>";
              $gmlElemInner .= $gml_object[$gml_objectKeys[$i]];
              $gmlElemInner .= "</position>";
              break;
            case "gml_id":
              // gml_id is formated as element attribute
              $gmlElemOpenTag .= " id=\"{$gml_object[$gml_objectKeys[$i]]}\"";
              break;
            default:
              $gml_value = trim($gml_object[$gml_objectKeys[$i]]);
              // all other fields go as a child element with inner text content
              // check for array values
              if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
                $gml_value_array = str_split(substr($gml_value, 1, -1));
                for ($j = 0; $j < count($gml_value_array); $j++){
                  $gmlElemInner .= $this->buildSimpleElement($gml_objectKeys[$i],$gml_value_array[$j]);
                }
              } else
              $gmlElemInner .= $this->buildSimpleElement($gml_objectKeys[$i],$gml_value);
          }
        }
        $gmlElemOpenTag .= ">";
        $xplan_gml .= $gmlElemOpenTag . $gmlElemInner . "</" . $gml_className . ">";
        $xplan_gml .= "</gml:featureMember>";
      }
    }
    $xplan_gml .= "</XPlanAuszug>";
    return $xplan_gml;
  }

  function buildSimpleElement($tag,$inner) {
    return "<$tag>$inner</$tag>";
  }

  function wrapFeatureMember($inner) {
    return $this->buildSimpleElement("gml:featureMember",$inner);
  }

  /*
  * Diese Funktion speichert den Inhalt der temporären Datei
  * an einen gegebenen Pfad im Dateisystem. Die temporäre Datei
  * bleibt bestehen, und kann mehrfach gespeichert werden. Die
  * Freigabe/Löschung der temporären Date wird vom Gml_builder
  * Objekt verwaltet.
  */
  function save($path){
    rewind($this->tmpFile);
    $file = fopen($path,'w');

    stream_copy_to_stream($this->tmpFile,$file);

    fclose($file);
  }
    /*
  * Diese Funktion formatiert und speichert den Inhalt
  * der temporären Datei an einen gegebenen Pfad im Dateisystem.
  * Der Inhalt wird dazu in eine DOMDocumentObjekt gelesen und
  * formatiert wieder ausgegeben. Bei großen GML-Inhalten (>100MB)
  * treten Speicherproblene auf. Die temporäre Datei bleibt nach dem
  * Speichern bestehen, und kann mehrfach gespeichert werden. Die
  * Freigabe/Löschung der temporären Date wird vom Gml_builder
  * Objekt verwaltet.
  */
  function saveFormatted($path){
    // use the DOMDocument functionality to format XML output
    $dom = new DOMDocument('1.0', 'utf-8');
    // NOTE! preserveWhiteSpace propertiy must be set _before_
    // loading xml content into the DOMDocument in order to
    // take effect!
    $dom->preserveWhiteSpace = FALSE;
    fseek($this->tmpFile, 0, SEEK_END);
    $tmpFileSize = ftell($this->tmpFile);
    rewind($this->tmpFile);
    $dom->loadXML(fread($this->tmpFile,$tmpFileSize));
    $dom->formatOutput = TRUE;
    // NOTE! xmlStandalone & encoding properies must be set _after_
    // loading xml content into the DOMDocument in order to
    // take effect!
    $dom->encoding = 'utf-8';
    $dom->xmlStandalone = TRUE;
    $dom->save($path);
  }

  function saveGML($gmlStr, $path){
    // use the DOMDocument functionality to format XML output
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = FALSE;
    $dom->formatOutput = TRUE;
    $dom->loadXML($gmlStr);
    $dom->save($path);
  }
}

?>
