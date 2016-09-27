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

  static $XPLANKONVERTER_NON_XPLAN_FIELDS = array(
      "konvertierung_id",
      "created_at",
      "updated_at"
  );

  static $STRUCTURE_SCHEMA = 'xplan_uml';
  static $CONTENT_SCHEMA = 'xplan_gml';

  function Gml_builder($database) {
    global $debug;
    $this->debug = $debug;
    $this->database = $database;
    $this->typeInfo = new TypeInfo($database);

    $this->tmpFile = tmpfile();
    // use the DOMDocument functionality to format XML output
    $this->formatter = new DOMDocument('1.0', 'utf-8');
  }

  function __destruct() {
    fclose($this->tmpFile);
  }

  function _findRPPlanByKonvertierung($konvertierung) {
    $sql = "SELECT gml_id FROM xplan_gml.rp_plan WHERE konvertierung_id = " . $konvertierung->get('id');
    $result = pg_query($this->database->dbConn, $sql);
    return pg_fetch_assoc($result)['gml_id'];
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

    # clear tempfile
    ftruncate($this->tmpFile, 0);

    $contentScheme   = CONTENT_SCHEME;
    $structureScheme = STRUCTURE_SCHEME;

    $xplan_ns_prefix = XPLAN_NS_PREFIX ? XPLAN_NS_PREFIX.':' : '';

    // plan muss nochmal abgerufen werden, weil die select-Anweisung nochmal ergänzt werden musste
    $plan->select = "
      *,
      ST_AsGML(
          ST_Reverse(ST_Transform(
            raeumlichergeltungsbereich,
--            COALESCE((raeumlichergeltungsbereich).flaeche, (raeumlichergeltungsbereich).multiflaeche),
            {$konvertierung->get('output_epsg')})),
          {$konvertierung->get('geom_precision')}) AS gml_raeumlichergeltungsbereich,
      ST_AsGML(
        3,
        ST_Transform(
          raeumlichergeltungsbereich,
--          COALESCE((raeumlichergeltungsbereich).flaeche, (raeumlichergeltungsbereich).multiflaeche),
          {$konvertierung->get('output_epsg')}),
        {$konvertierung->get('geom_precision')},
        32) AS envelope";
    $plan->find_by('konvertierung_id',$konvertierung->get('id'));

    # XPlan XSD's sind derzeit unter: http://xplan-raumordnung.de/devk/model/2016-05-06_XSD/ hinterlegt
    fwrite($this->tmpFile,
      "<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>\n".
      "<XPlanAuszug xmlns=\"{XPLAN_NS_URI}\"\n".
      "  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n".
      "  xmlns:wfs=\"http://www.opengis.net/wfs\"\n".
      "  xmlns:gml=\"http://www.opengis.net/gml\"\n".
      "  xmlns:xlink=\"http://www.w3.org/1999/xlink\"\n".
      XPLAN_NS_PREFIX==""?"":
      "  xmlns:".XPLAN_NS_PREFIX."=\"{XPLAN_NS_URI}\"\n".
      "  xmlns:".RPLAN_NS_PREFIX."=\"http://xplan-raumordnung.de/model/xplangml/raumordnungsmodell\"\n".
      "  xsi:schemaLocation=\"{XPLAN_NS_URI} {XPLAN_NS_SCHEMA_LOCATION}\"\n".
      ">\n".

    # TODO: <boundedBy> sollte für jeden Plan definiert oder festgehalten werden,
    # z.B. über Informationen in XP_Plan:raeumlicherGeltungsbereich oder eine
    # Erfassung vor der Konvertierung.
    $this->formatXML($this->wrapWithElement('gml:boundedBy', $plan->get('envelope'))));

    // das RP_Plan Element anlegen
    $gmlElemInner = "";
    $gmlElemOpenTag = "<{$xplan_ns_prefix}RP_Plan";
    $gmlElemOpenTag .= " gml:id=\"GML_{$plan->data['gml_id']}\"";

    // fetch information about attributes and their properties
    //$typeInfo = new TypeInfo($this->database);
    $plan_attribs = $this->typeInfo->getInfo('rp_plan');

    // alle Attribute von RP_Plan ausgeben
    $gmlElemInner .= $this->generateGmlForAttributes($plan->data, $plan_attribs);

    $rp_plan = $gmlElemOpenTag . ">" . $gmlElemInner;

    // alle Bereiche suchen, die zur Konvertierung gehören
    // TODO: Ist es sinnvoller die Bereiche abzufragen, die dem RP_Plan
    // zugeordnet sind, oder ist es sauberer die Bereiche nach Ihrer
    // Zugehörigkeit zur Konvertierung auszuwählen?
    $sql = "
        SELECT
          b.*,
          ST_AsGML(
            ST_Reverse(ST_Transform(
              b.geltungsbereich,
--              COALESCE ((b.geltungsbereich).flaeche,(b.geltungsbereich).multiflaeche),
              {$konvertierung->get('output_epsg')})),
              {$konvertierung->get('geom_precision')}) AS gml_geltungsbereich,
          ST_AsGML(
            3,
            ST_Transform(
              b.geltungsbereich,
--              COALESCE ((b.geltungsbereich).flaeche,(b.geltungsbereich).multiflaeche),
              {$konvertierung->get('output_epsg')}),
              {$konvertierung->get('geom_precision')},
              32) AS envelope
        FROM $contentScheme.rp_bereich b
        WHERE b.konvertierung_id = {$konvertierung->get('id')}";
    #echo $sql."<br>";
    $bereiche = pg_query($this->database->dbConn, $sql);

    // fetch information about attributes and their properties
    $bereich_attribs = $this->typeInfo->getInfo('rp_bereich');

    # iterating bereiche in two passes:
    # first pass: complete RP_Plan element by iteratively inserting
    # xlink-references to each RP_Bereich element
    while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
      $rp_plan .= "<{$xplan_ns_prefix}bereich xlink:href=\"#GML_" . $bereich['gml_id'] . "\"/>";
    }
    # close and write RP_Plan element
    fwrite($this->tmpFile, $this->formatXML($this->wrapWithFeatureMember($rp_plan . "</{$xplan_ns_prefix}RP_Plan>")));
    # second pass: iteratively building and writing gml for each RP_Bereich element
    pg_result_seek($bereiche, 0);
    while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
      $gmlElemOpenTag = "<{$xplan_ns_prefix}RP_Bereich";
      $gmlElemOpenTag .= " gml:id=\"GML_{$bereich['gml_id']}\"";

      // Rueckbezug zu RP_Plan
      $gmlElemInner = "<{$xplan_ns_prefix}gehoertZuPlan xlink:href=\"#GML_{$bereich['gehoertzuplan']}\"/>";

      // alle uebrigen Attribute ausgeben
      $gmlElemInner .= $this->generateGmlForAttributes($bereich, $bereich_attribs);

      $rp_bereich = $gmlElemOpenTag . ">" . $gmlElemInner;

      // alle gml_ids von RP_Objekten finden die mit dem Bereich verknüpft sind
      // und im RP_Bereich Element verlinken
      $_sql = "
          SELECT gml_id
          FROM $contentScheme.rp_bereich
          WHERE ANY(gehoertZuRP_Bereich) = {$bereich['gml_id']}";
      $sql = "
          SELECT b2o.rp_objekt_gml_id AS gml_id
          FROM
            $contentScheme.rp_bereich AS b JOIN
            $contentScheme.rp_bereich_zu_rp_objekt AS b2o ON b.gml_id = b2o.rp_bereich_gml_id
          WHERE b.gml_id = '" . $bereich['gml_id'] ."'";
      $rp_objekte = pg_query($this->database->dbConn, $sql);
      # complete RP_Bereich element by iteratively inserting
      # xlink-references to each associated RP_Objekt element
      while ($rp_objekt = pg_fetch_array($rp_objekte, NULL, PGSQL_ASSOC)) {
        $rp_bereich .= "<{$xplan_ns_prefix}inhaltRPlan xlink:href=\"#GML_" . $rp_objekt['gml_id'] . "\"/>";
      }
      # close and write RP_Bereich element
      fwrite($this->tmpFile, "\n" . $this->formatXML($this->wrapWithFeatureMember($rp_bereich . "</{$xplan_ns_prefix}RP_Bereich>")));
    }
    // und nun alle RP_Objekte generieren
    // dazu alle RP_Objektklassen finden die mit der Konvertierung verknüpft sind
    $sql =
      "SELECT DISTINCT r.class_name
       FROM xplankonverter.regeln r
       WHERE r.konvertierung_id = " . $konvertierung->get('id');
    $classNameSet = pg_query($this->database->dbConn, $sql);
    // zu jeder RP_Objektklassse die Objekte holen, die mit der Konvertierung verknüpft sind
    while ($gml_className = pg_fetch_array($classNameSet)[0]) {
      $_sql = "
          SELECT
            *,
            gehoertZuRP_Bereich AS bereiche_gml_ids,
            ST_AsGML(
                ST_Reverse(ST_Transform(
                  position,
                  {$konvertierung->get('output_epsg')})),
                {$konvertierung->get('geom_precision')}) AS gml_position,
            ST_AsGML(
              3,
              ST_Transform(
                position,
                {$konvertierung->get('output_epsg')}),
              {$konvertierung->get('geom_precision')},
              32) AS envelope
          FROM
            $contentScheme.$gml_className AS ft JOIN
          WHERE konvertierung_id = {$konvertierung->get('id')}";
      $sql = "
          SELECT
            bereiche_gml_ids,
            ft.*,
            ST_AsGML(
                ST_Reverse(ST_Transform(
                  ft.position,
--                  COALESCE(
--                    (ft.position).punkt,
--                    (ft.position).multipunkt,
--                    (ft.position).linie,
--                    (ft.position).multilinie,
--                    (ft.position).flaeche,
--                    (ft.position).multiflaeche
--                    ),
                  {$konvertierung->get('output_epsg')})),
                {$konvertierung->get('geom_precision')}) AS gml_position,
            ST_AsGML(
              3,
              ST_Transform(
                ft.position,
--                  COALESCE(
--                    (ft.position).punkt,
--                    (ft.position).multipunkt,
--                    (ft.position).linie,
--                    (ft.position).multilinie,
--                    (ft.position).flaeche,
--                    (ft.position).multiflaeche
--                    ),
                {$konvertierung->get('output_epsg')}),
              {$konvertierung->get('geom_precision')},
              32) AS envelope
          FROM
            $contentScheme.$gml_className AS ft JOIN
            (SELECT
              -- wenn mehrere bereiche
              array_agg(b.gml_id) as bereiche_gml_ids,
              o.gml_id
              FROM
                $contentScheme.rp_bereich AS b JOIN
                $contentScheme.rp_bereich_zu_rp_objekt AS b2o ON b.gml_id = b2o.rp_bereich_gml_id JOIN
                $contentScheme.rp_objekt AS o ON b2o.rp_objekt_gml_id = o.gml_id
              WHERE o.konvertierung_id = {$konvertierung->get('id')}
              GROUP BY o.gml_id
            ) AS agg ON ft.gml_id = agg.gml_id";
      $gml_objects = pg_query($this->database->dbConn, $sql);

      // fetch information about attributes and their properties
      $objekt_attribs = $this->typeInfo->getInfo($gml_className);

      while ($gml_object = pg_fetch_array($gml_objects, NULL, PGSQL_ASSOC)) {
        // elment anlegen und gml_id als attribut eintragen
        $objekt_gml = "<{$xplan_ns_prefix}{$gml_className} gml:id=\"GML_{$gml_object['gml_id']}\">";

        // Rueckverweise auf etwaige Bereiche hinzufügen
        $aggregated_bereich_gml_ids = explode(',',substr($gml_object['bereiche_gml_ids'], 1, -1));
        foreach ($aggregated_bereich_gml_ids as $bereich_gml_id){
          $objekt_gml .= "<{$xplan_ns_prefix}gehoertZuRP_Bereich xlink:href=\"#GML_{$bereich_gml_id}\"/>";
        }

        // alle uebrigen Attribute ausgeben
        $objekt_gml .= $this->generateGmlForAttributes($gml_object, $objekt_attribs);
        # close and write FeatureMember
        $objekt_gml .= "</{$xplan_ns_prefix}{$gml_className}>";

        fwrite($this->tmpFile, "\n" . $this->formatXML($this->wrapWithFeatureMember($objekt_gml)));
      }
    }
    # close XPlanAuszug
    fwrite($this->tmpFile, "\n</XPlanAuszug>");

    # reset internal string encoding
    mb_internal_encoding($old_encoding);
    return true;
  }

  function wrapWithElement($tag,$inner) {
    return "<$tag>$inner</$tag>";
  }

  function wrapWithFeatureMember($inner) {
    return $this->wrapWithElement("gml:featureMember",$inner);
  }

  function generateGmlForAttributes($gml_object, $uml_attribute_info) {
    $xplan_ns_prefix = XPLAN_NS_PREFIX ? XPLAN_NS_PREFIX.':' : '';
    $gmlStr = '';
    foreach ($uml_attribute_info as  $uml_attribute) {
      // leere Felder auslassen
      if (strlen($gml_object[$uml_attribute['col_name']]) == 0) continue;

      $lowercaseName = strtolower($uml_attribute['name']);
      switch ($uml_attribute['type_type']) {
        case 'c': // custom datatype
          // geometrie attribute
          if (in_array($uml_attribute['type'], array(
              "xp_liniengeometrie",
              "xp_punktgeometrie",
              "xp_flaechengeometrie",
              "xp_variablegeometrie"
          ))) {
            $gml_value = $gml_object['gml_'.$uml_attribute['col_name']];
            $gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}", $gml_value);
          } else {
            // andere custom type attribute
            switch ($uml_attribute['stereotype']){
              case NULL:
                break;
              case "CodeList":
                $gml_value = $gml_object[$uml_attribute['col_name']]['id'];
                $codeSpaceUri = $gml_object[$uml_attribute['col_name']]['codespace'];
                $gmlStr .= "<{$xplan_ns_prefix}{$uml_attribute['uml_name']} codeSpace=\"$codeSpaceUri\">$gml_value</{$xplan_ns_prefix}{$uml_attribute['uml_name']}>";
                break;
              case "DataType":
                // fetch information about attributes and their properties
                $datatype_attribs = $this->typeInfo->getInfo($uml_attribute['type']);
                $gmlStr .= "<{$xplan_ns_prefix}{$uml_attribute['uml_name']}>";
                $gml_value_array = explode(',',substr($gml_object[$uml_attribute['col_name']], 1, -1));
                foreach ($datatype_attribs as $dt_attrib){
                  $gmlStr .= $this->wrapWithElement(
                    "{$xplan_ns_prefix}{$dt_attrib['uml_name']}",
                    $gml_value_array[$dt_attrib['sequence']]);
                }
                $gmlStr .= "</{$xplan_ns_prefix}{$uml_attribute['uml_name']}>";
              default:
            }
          }
          break;
        case 'e': // enum type
        case 'b': // built-in datatype
        default:
          // TODO: Datumsangaben formatieren nach ISO 8601
          $gml_value = trim($gml_object[$uml_attribute['col_name']]);
          // check for array values
          if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
            $gml_value_array = explode(',',substr($gml_value, 1, -1));
            for ($j = 0; $j < count($gml_value_array); $j++){
              $gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}",htmlentities($gml_value_array[$j]));
            }
          } else
          $gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}",htmlentities($gml_value));
      }
    }
    return $gmlStr;
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
  * Diese Funktion formatiert den Inhalt des übergebenen XML-Strings
  * und gibt den formatierten String zurück.
  */
  function formatXML($unformattedXMLString){
    // Quelle: http://www.daveperrett.com/articles/2007/04/05/format-xml-with-php/
    $unformattedXMLString = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $unformattedXMLString);
    $token      = strtok($unformattedXMLString, "\n");
    $result     = '';
    $pad        = 0;
    $matches    = array();
    while ($token !== false) :
        if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
          $indent=0;
        elseif (preg_match('/^<\/\w/', $token, $matches)) :
          $pad-=2;
          $indent = 0;
        elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
          $indent=2;
        else :
          $indent = 0;
        endif;
        $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
        $result .= $line . "\n";
        $token   = strtok("\n");
        $pad    += $indent;
    endwhile;
    return $result;
  }
}
?>
