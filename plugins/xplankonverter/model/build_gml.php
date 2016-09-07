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

    $contentScheme   = "xplan_gml";
    $structureScheme = "xplan_uml";

    // plan muss nochmal abgerufen werden, weil die select-Anweisung nochmal ergänzt werden musste
    $plan->select = "
      *,
      ST_AsGML(
          ST_Reverse(ST_Transform(
            COALESCE((raeumlichergeltungsbereich).flaeche, (raeumlichergeltungsbereich).multiflaeche),
            {$konvertierung->get('output_epsg')})),
          {$konvertierung->get('geom_precision')}) AS gml_raeumlichergeltungsbereich,
      ST_AsGML(
        3,
        ST_Transform(
            COALESCE((raeumlichergeltungsbereich).flaeche, (raeumlichergeltungsbereich).multiflaeche),
          {$konvertierung->get('output_epsg')}),
        {$konvertierung->get('geom_precision')},
        32) AS envelope";
    $plan->find_by('konvertierung_id',$konvertierung->get('id'));

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
      $this->formatXML($this->wrapWithElement('gml:boundedBy', $plan->get('envelope'))));

    // alle RP_Objekt-Namen holen, die zur Konvertierung gehören
    $sql =
      "SELECT DISTINCT r.class_name
       FROM xplankonverter.regeln r
       WHERE r.konvertierung_id = " . $konvertierung->get('id');
    $classNameSet = pg_query($this->database->dbConn, $sql);

    // das RP_Plan Element anlegen
    $gmlElemInner = "";
    $gmlElemOpenTag = "<RP_Plan";
    $gmlElemOpenTag .= " gml:id=\"GML_{$plan->data['gml_id']}\"";

    // fetch complete list of attributes and their properties from UML-structure
    $uml_attributes = $this->fetchAttributesForFeatureMember('rp_plan');

    // alle Attribute von RP_Plan ausgeben
    $gmlElemInner .= $this->generateGmlForAttributes($plan->data, $uml_attributes);

    $rp_plan = $gmlElemOpenTag . ">" . $gmlElemInner;

    // alle Bereiche suchen, die zum RP-Plan gehören
    $sql = "
        SELECT
          b.*,
          ST_AsGML(
            ST_Reverse(ST_Transform(
              COALESCE ((b.geltungsbereich).flaeche,(b.geltungsbereich).multiflaeche),
              {$konvertierung->get('output_epsg')})),
              {$konvertierung->get('geom_precision')}) AS gml_geltungsbereich,
          ST_AsGML(
            3,
            ST_Transform(
              COALESCE ((b.geltungsbereich).flaeche,(b.geltungsbereich).multiflaeche),
              {$konvertierung->get('output_epsg')}),
              {$konvertierung->get('geom_precision')},
              32) AS envelope
        FROM $contentScheme.rp_bereich b
        WHERE b.gehoertzuplan = '" . $plan->get('gml_id') . "'";
    #echo $sql."<br>";
    $bereiche = pg_query($this->database->dbConn, $sql);

    // fetch complete list of attributes and their properties from UML-structure
    $uml_attributes = $this->fetchAttributesForFeatureMember('rp_bereich');

    # iterating bereiche in two passes:
    # first pass: complete RP_Plan element by iteratively inserting
    # xlink-references to each RP_Bereich element
    while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
      $rp_plan .= "<xplan:bereich xlink:href=\"#GML_" . $bereich['gml_id'] . "\"/>";
    }
    # close and write RP_Plan element
    fwrite($this->tmpFile, $this->formatXML($this->wrapWithFeatureMember($rp_plan . "</RP_Plan>")));
    # second pass: iteratively building and writing gml for each RP_Bereich element
    pg_result_seek($bereiche, 0);
    while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
      $gmlElemOpenTag = "<RP_Bereich";
      $gmlElemOpenTag .= " gml:id=\"GML_{$bereich['gml_id']}\"";

      // Rueckbezug zu RP_Plan
      $gmlElemInner = "<xplan:gehoertZuPlan xlink:href=\"#GML_{$plan->data['gml_id']}\"/>";

      // alle uebrigen Attribute ausgeben
      $gmlElemInner .= $this->generateGmlForAttributes($bereich, $uml_attributes);

      $rp_bereich = $gmlElemOpenTag . ">" . $gmlElemInner;

      // alle gml_ids von RP_Objekten finden die mit dem Bereich verknüpft sind
      // TODO: ein RP-Objekt kann lt. Modell mit mehreren RP_Bereichen assoziiert
      // sein, so dass eine Abfrage der RP-Objekte ueber die Bereiche dazu fuehren
      // wuerde, dass solche Objekte fuer jeden der assoziierten Bereiche (also
      // mehrfach) geschrieben werden...
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
        $rp_bereich .= "<xplan:inhaltRPlan xlink:href=\"#GML_" . $rp_objekt['gml_id'] . "\"/>";
      }
      # close and write RP_Bereich element
      fwrite($this->tmpFile, "\n" . $this->formatXML($this->wrapWithFeatureMember($rp_bereich . "</RP_Bereich>")));
      // alle RP_Objekte finden die mit dem Bereich und der Konvertierung verknüpft sind
      while ($gml_className = pg_fetch_array($classNameSet)[0]) {
        $sql = "
            SELECT
              cl.*,
              ST_AsGML(
                  ST_Reverse(ST_Transform(
                    COALESCE(
                      (cl.position).punkt,
                      (cl.position).multipunkt,
                      (cl.position).linie,
                      (cl.position).multilinie,
                      (cl.position).flaeche,
                      (cl.position).multiflaeche
                      ),
                    {$konvertierung->get('output_epsg')})),
                  {$konvertierung->get('geom_precision')}) AS gml_position,
              ST_AsGML(
                3,
                ST_Transform(
                    COALESCE(
                      (cl.position).punkt,
                      (cl.position).multipunkt,
                      (cl.position).linie,
                      (cl.position).multilinie,
                      (cl.position).flaeche,
                      (cl.position).multiflaeche
                      ),
                  {$konvertierung->get('output_epsg')}),
                {$konvertierung->get('geom_precision')},
                32) AS envelope
            FROM
              $contentScheme.rp_bereich AS b JOIN
              $contentScheme.rp_bereich_zu_rp_objekt AS b2o ON b.gml_id = b2o.rp_bereich_gml_id JOIN
              $contentScheme.$gml_className AS cl ON b2o.rp_objekt_gml_id = cl.gml_id
            WHERE b.gml_id = '" . $bereich['gml_id'] ."'";
        $gml_objects = pg_query($this->database->dbConn, $sql);

        // fetch complete list of attributes and their properties from UML-structure
        $uml_attributes = $this->fetchAttributesForFeatureMember($gml_className);

        while ($gml_object = pg_fetch_array($gml_objects, NULL, PGSQL_ASSOC)) {
          // elment anlegen und gml_id als attribut eintragen
          $gmlElemOpenTag = "<$gml_className";
          $gmlElemOpenTag .= " gml:id=\"GML_{$gml_object['gml_id']}\"";

          // Rueckverweis zum Bereich hinzufügen
          $gmlElemInner = "<xplan:gehoertZuRP_Bereich xlink:href=\"#GML_{$bereich['gml_id']}\"/>";

          // alle uebrigen Attribute ausgeben
          $gmlElemInner .= $this->generateGmlForAttributes($gml_object, $uml_attributes);

          # close and write FeatureMember
          $gmlElemOpenTag .= ">";
          $objekt_gml = $gmlElemOpenTag . $gmlElemInner . "</" . $gml_className . ">";

          fwrite($this->tmpFile, "\n" . $this->formatXML($this->wrapWithFeatureMember($objekt_gml)));
        }
      }
    }
    # close XPlan
    fwrite($this->tmpFile, "\n</XPlanAuszug>");

    # reset internal string encoding
    mb_internal_encoding($old_encoding);
  }

  function wrapWithElement($tag,$inner) {
    return "<$tag>$inner</$tag>";
  }

  function wrapWithFeatureMember($inner) {
    return $this->wrapWithElement("gml:featureMember",$inner);
  }

  function fetchAttributesForFeatureMember($featureMember) {
    $structure_schema = Gml_builder::$STRUCTURE_SCHEMA;
    $sql = "
      WITH RECURSIVE inheritance AS (
          SELECT xmi_id::text AS xmi_id FROM $structure_schema.uml_classes WHERE name ILIKE '$featureMember'
          UNION
          SELECT inner_inh.parent_id AS xmi_id
            FROM $structure_schema.class_generalizations inner_inh
            INNER JOIN inheritance ON inheritance.xmi_id = inner_inh.child_id
      )
      SELECT uc.name AS origin, ua.name AS name, ua.datatype AS stype, uc2.name AS ctype, dt.name AS dtype, tv.datavalue AS sequence, inheritance.order
          FROM (SELECT *, row_number() OVER () AS order FROM inheritance) AS inheritance
            INNER JOIN $structure_schema.uml_classes uc ON uc.xmi_id = inheritance.xmi_id
            INNER JOIN $structure_schema.uml_attributes ua ON ua.uml_class_id = uc.id
            INNER JOIN $structure_schema.taggedvalues tv ON ua.id = tv.attribute_id
            INNER JOIN $structure_schema.tagdefinitions td ON td.xmi_id = tv.type
            LEFT JOIN $structure_schema.uml_classes uc2 ON ua.classifier = uc2.xmi_id
            LEFT JOIN $structure_schema.datatypes dt ON ua.classifier = dt.xmi_id
          WHERE td.name = 'sequenceNumber'
          ORDER BY inheritance.order DESC, tv.datavalue ASC
    ";
    $uml_attributes = pg_query($this->database->dbConn, $sql);
    return pg_fetch_all($uml_attributes);
  }
  function generateGmlForAttributes($gml_object, $uml_attributes) {
    $gmlStr = '';
    foreach ($uml_attributes as  $uml_attribute) {
      $lowercaseName = strtolower($uml_attribute['name']);
      switch ($uml_attribute['ctype']) {
        case "XP_Liniengeometrie":
        case "XP_Punktgeometrie":
        case "XP_Flaechengeometrie":
        case "XP_VariableGeometrie":
          $gml_value = $gml_object['gml_'.$lowercaseName];
          // leere Felder auslassen
          if (strlen($gml_value) == 0) continue;
          $gmlStr .= $this->wrapWithElement("xplan:{$uml_attribute['name']}", $gml_object['gml_'.$lowercaseName]);
          break;
        default:
          // TODO: Datumsangaben formatieren nach ISO 8601
          $gml_value = trim($gml_object[$lowercaseName]);
          // leere Felder auslassen
          if (strlen($gml_value) == 0) continue;
          // check for array values
          if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
            $gml_value_array = explode(',',substr($gml_value, 1, -1));
            for ($j = 0; $j < count($gml_value_array); $j++){
              $gmlStr .= $this->wrapWithElement("xplan:".$uml_attribute['name'],htmlentities($gml_value_array[$j]));
            }
          } else
          $gmlStr .= $this->wrapWithElement("xplan:".$uml_attribute['name'],htmlentities($gml_value));
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
