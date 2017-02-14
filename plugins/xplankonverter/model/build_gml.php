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
  }

  function __destruct() {
    fclose($this->tmpFile);
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
				3,
				ST_Reverse(ST_Transform(
				raeumlichergeltungsbereich,
				{$konvertierung->get('output_epsg')})),
				{$konvertierung->get('geom_precision')},
				0,
				null,
				'GML_' || gml_id::text || '_geom') AS gml_raeumlichergeltungsbereich,
				ST_AsGML(
					3,
					ST_Transform(
						raeumlichergeltungsbereich,
						{$konvertierung->get('output_epsg')}),
						{$konvertierung->get('geom_precision')},
						32,
						null,
						'GML_' || gml_id::text || '_envelope' ) AS envelope";
						$plan->find_by('konvertierung_id',$konvertierung->get('id'));

		# XPlan XSD's sind derzeit unter: http://xplan-raumordnung.de/devk/model/2016-05-06_XSD/ hinterlegt
		fwrite(
		$this->tmpFile,
			"<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>\n" .
			"<XPlanAuszug gml:id=\"GML_{$plan->data['gml_id']}_auszug\"\n" .
			"  xmlns=\"".XPLAN_NS_URI."\"\n" .
			"  xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n" .
			"  xmlns:wfs=\"http://www.opengis.net/wfs\"\n" .
			"  xmlns:gml=\"http://www.opengis.net/gml/3.2\"\n" .
			"  xmlns:xlink=\"http://www.w3.org/1999/xlink\"\n" .
			(XPLAN_NS_PREFIX==""
				? ""
				: "  xmlns:".XPLAN_NS_PREFIX."=\"".XPLAN_NS_URI."\"\n").
					"  xsi:schemaLocation=\"".XPLAN_NS_URI." ".XPLAN_NS_SCHEMA_LOCATION."\"\n" .
			">\n" .

			# TODO: <boundedBy> sollte für jeden Plan definiert oder festgehalten werden,
			# z.B. über Informationen in XP_Plan:raeumlicherGeltungsbereich oder eine
			# Erfassung vor der Konvertierung.
			$this->formatXML($this->wrapWithElement('gml:boundedBy', $plan->get('envelope')))
		);

		// das RP_Plan Element anlegen
		$gmlElemInner = "";
		$gmlElemOpenTag = "<{$xplan_ns_prefix}RP_Plan";
		$gmlElemOpenTag .= " gml:id=\"GML_{$plan->data['gml_id']}\"";

		// fetch information about attributes and their properties
		//$typeInfo = new TypeInfo($this->database);
		$plan_attribs = $this->typeInfo->getInfo('rp_plan');

		// alle Attribute von RP_Plan ausgeben
		$gmlElemInner .= $this->generateGmlForAttributes($plan->data, $plan_attribs, XPLAN_MAX_NESTING_DEPTH);

		$rp_plan = $gmlElemOpenTag . ">" . $gmlElemInner;

		// alle Bereiche suchen, die zur Konvertierung gehören
		// TODO: Ist es sinnvoller die Bereiche abzufragen, die dem RP_Plan
		// zugeordnet sind, oder ist es sauberer die Bereiche nach Ihrer
		// Zugehörigkeit zur Konvertierung auszuwählen?
		$sql = "
			SELECT
		b.*,
		ST_AsGML(
			3,
			ST_Reverse(ST_Transform(
				b.geltungsbereich,
				{$konvertierung->get('output_epsg')})),
				{$konvertierung->get('geom_precision')},
				0,
				null,
				'GML_' || b.gml_id::text || '_geom' ) AS gml_geltungsbereich,
				ST_AsGML(
					3,
					ST_Transform(
						b.geltungsbereich,
						{$konvertierung->get('output_epsg')}),
						{$konvertierung->get('geom_precision')},
						32,
						null,
						'GML_' || b.gml_id::text || '_envelope' ) AS envelope
						FROM $contentScheme.rp_bereich b
						JOIN $contentScheme.rp_plan p ON b.gehoertzuplan = p.gml_id::text
						WHERE p.konvertierung_id = {$konvertierung->get('id')}";
		#echo $sql."\n";
		$bereiche = pg_query($this->database->dbConn, $sql);

		# iterating bereiche in two passes:
		# first pass: complete RP_Plan element by iteratively inserting
		# xlink-references to each RP_Bereich element
		while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
			$rp_plan .= "<{$xplan_ns_prefix}bereich xlink:href=\"#GML_" . $bereich['gml_id'] . "\"/>";
		}
		# close and write RP_Plan element
		fwrite($this->tmpFile, $this->formatXML($this->wrapWithFeatureMember($rp_plan . "</{$xplan_ns_prefix}RP_Plan>")));

		#
		# second pass: iteratively building and writing gml for each RP_Bereich element
		#
		// fetch information about attributes and their properties
		$rp_bereich_attribs = $this->typeInfo->getInfo('rp_bereich', false);
		$xp_bereich_attribs = $this->typeInfo->getInfo('xp_bereich', false);

		pg_result_seek($bereiche, 0);
		while ($bereich = pg_fetch_array($bereiche, NULL, PGSQL_ASSOC)) {
			$gmlElemOpenTag = "<{$xplan_ns_prefix}RP_Bereich";
			$gmlElemOpenTag .= " gml:id=\"GML_{$bereich['gml_id']}\"";
			$rp_bereich = $gmlElemOpenTag . ">";

			// alle von XP_Bereich geerbten Attribute ausgeben
			$rp_bereich .= $this->generateGmlForAttributes($bereich, $xp_bereich_attribs, XPLAN_MAX_NESTING_DEPTH);

			// alle gml_ids von RP_Objekten finden die mit dem Bereich verknüpft sind
			// und im RP_Bereich Element verlinken
			$sql = "
				SELECT gml_id
				FROM $contentScheme.xp_objekt
				WHERE '{$bereich['gml_id']}' = ANY (gehoertzubereich)";
			$_sql = "
				SELECT b2o.xp_objekt_gml_id AS gml_id
				FROM
					$contentScheme.xp_bereich AS b JOIN
					$contentScheme.xp_bereich_zu_xp_objekt AS b2o ON b.gml_id = b2o.xp_bereich_gml_id
				WHERE
					b.gml_id = '" . $bereich['gml_id'] . "'
			";
			$rp_objekte = pg_query($this->database->dbConn, $sql);
			# complete RP_Bereich element by iteratively inserting
			# xlink-references to each associated RP_Objekt element
			while ($rp_objekt = pg_fetch_array($rp_objekte, NULL, PGSQL_ASSOC)) {
				$rp_bereich .= "<{$xplan_ns_prefix}planinhalt xlink:href=\"#GML_" . $rp_objekt['gml_id'] . "\"/>";
			}
			// die übrigen in RP_Bereich definierten Attribute ausgeben
			$rp_bereich .= $this->generateGmlForAttributes($bereich, $rp_bereich_attribs, XPLAN_MAX_NESTING_DEPTH);

			// Rueckbezug zu RP_Plan
			$rp_bereich .= "<{$xplan_ns_prefix}gehoertZuPlan xlink:href=\"#GML_{$bereich['gehoertzuplan']}\"/>";

			# close and write RP_Bereich element
			fwrite($this->tmpFile, "\n" . $this->formatXML($this->wrapWithFeatureMember($rp_bereich . "</{$xplan_ns_prefix}RP_Bereich>")));
		}

		// und nun alle RP_Objekte generieren
		// dazu alle RP_Objektklassen finden die mit der Konvertierung verknüpft sind
		$sql = "
			SELECT DISTINCT
				r.class_name
			FROM
				xplankonverter.regeln r LEFT JOIN
				xplan_gml.rp_bereich b ON r.bereich_gml_id = b.gml_id left JOIN
				xplan_gml.rp_plan bp ON b.gehoertzuplan = bp.gml_id::text LEFT JOIN
				xplan_gml.rp_plan rp ON r.konvertierung_id = rp.konvertierung_id
			WHERE
				bp.konvertierung_id = " . $konvertierung->get('id') . " OR
				rp.konvertierung_id = " . $konvertierung->get('id') . "
		";
	#	echo '<br>sql: ' . $sql;
		$classNameSet = pg_query($this->database->dbConn, $sql);

		# zu jeder RP_Objektklassse die Objekte holen, die mit der Konvertierung verknüpft sind
		while ($gml_className = pg_fetch_array($classNameSet)[0]) {
			$rp_object = new RP_Object($konvertierung, $gml_className);
			$rp_object_rows = $rp_object->get_object_rows();

			// fetch information about attributes and their properties
			$objekt_attribs = $this->typeInfo->getInfo($gml_className);

			foreach ($rp_object_rows AS $rp_object_row) {
				# elment anlegen und gml_id als attribut eintragen
				$objekt_gml = "<{$xplan_ns_prefix}{$gml_className} gml:id=\"GML_{$rp_object_row['gml_id']}\">";

				# Rueckverweise auf etwaige Bereiche hinzufügen
				$aggregated_bereich_gml_ids = explode(',', substr($rp_object_row['bereiche_gml_ids'], 1, -1));
				foreach ($aggregated_bereich_gml_ids as $bereich_gml_id) {
					if (!empty($bereich_gml_id))
						$objekt_gml .= "<{$xplan_ns_prefix}gehoertZuBereich xlink:href=\"#GML_{$bereich_gml_id}\"/>";
				}

				# alle uebrigen Attribute ausgeben
				$objekt_gml .= $this->generateGmlForAttributes($rp_object_row, $objekt_attribs, XPLAN_MAX_NESTING_DEPTH);
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

  function generateGmlForAttributes($gml_object, $uml_attribute_info, $depth) {
    if (($depth) < 0) return '';
    $xplan_ns_prefix = XPLAN_NS_PREFIX ? XPLAN_NS_PREFIX.':' : '';
    $gmlStr = '';
    foreach ($uml_attribute_info as  $uml_attribute) {
      // leere Felder auslassen
      if (empty($gml_object[$uml_attribute['col_name']])) continue;

      $lowercaseName = strtolower($uml_attribute['name']);
      switch ($uml_attribute['type_type']) {
        case 'c': // custom datatype
          switch ($uml_attribute['stereotype']){
            case NULL:
              break;
            case "CodeList":
              $gml_value_array = is_array($gml_object[$uml_attribute['col_name']])
                ? $gml_object[$uml_attribute['col_name']]
                : explode(',',substr($gml_object[$uml_attribute['col_name']], 1, -1));
              $codeSpaceUri = $gml_value_array[0];
              $code_value = $gml_value_array[1];
              $gmlStr .= "<{$xplan_ns_prefix}{$uml_attribute['uml_name']} codeSpace=\"$codeSpaceUri\">$code_value</{$xplan_ns_prefix}{$uml_attribute['uml_name']}>";
              break;
            case "DataType":
              $gml_attrib_str = '';
              // check whether attribute value is already parsed into an array
              if (is_array($gml_object[$uml_attribute['col_name']]))
                $value_array = $gml_object[$uml_attribute['col_name']];
              else // parse attribute value if not yet done
                $value_array = $this->parseCompositeDataType($gml_object[$uml_attribute['col_name']]);

              // fetch information about attributes and their properties
              $datatype_attribs = $this->typeInfo->getInfo($uml_attribute['type']);
              // retrieve attribute names
              $value_array_keys = array_column($datatype_attribs,'col_name');

              // wrap singular values into an array in order to
              // unify the processing of singular and multiple values
              if (!$uml_attribute['is_array']) {
                $aux_array = array(0 => $value_array);
                $value_array = $aux_array;
              }
              // leere Datentypen auslassen
              if (!$value_array) break;
              // process composite data type
              foreach ($value_array as $single_value) {
                // associate values with attribute names
                $single_value = array_combine($value_array_keys, $single_value);
                // generate GML output (!!! recursive !!!)
                $gml_attrib_str .= $this->generateGmlForAttributes($single_value, $datatype_attribs,$depth-1);
              }

              // leere Datentypen auslassen
              if (strlen($gml_attrib_str) == 0) break;

              $typeElementName = end($datatype_attribs)['origin'];
              $gmlStr .= $this->wrapWithElement(
                "{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
                // wrap all data-types with their data-type-element-tag
                $this->wrapWithElement("{$xplan_ns_prefix}{$typeElementName}", $gml_attrib_str));
            default:
          }
          break;
        case 'b': // built-in datatype
          // geometrie attribute
          if ($uml_attribute['type'] == "geometry") {
            $gml_value = $gml_object['gml_'.$uml_attribute['col_name']];
            // unify gml_ids by appending a sequential number to the id
            $seq_number = 0; $unified_gml = "";
            $haystack = $gml_value;
            while (!empty($haystack)) {
              $currpos = strpos($haystack, "_geom");
              if (!$currpos) break;
              $unified_gml .= substr($haystack, 0, $currpos);
              $unified_gml .= "_geom_". $seq_number++;
              $currpos = strpos($haystack, '"', $currpos);
							if (substr($haystack, $currpos + 1, 1) == '"') {
								$currpos++;
							}
              if (!$currpos) { // should never happen!!!
                $currpos += 5;
                $unified_gml .= '"';
                continue;
              }
              $haystack = substr($haystack, $currpos);
            }
            $unified_gml .= $haystack;
            #echo $unified_str . "\n";
            $gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}", $unified_gml);
            break;
          }
          // date attribute
          if ($uml_attribute['type'] == "date") {
            $gml_value = $gml_object[$uml_attribute['col_name']];
            $timestamp = strtotime($gml_value);
            if (!$timestamp) {
              echo "Ungueltige Datumsangabe: " . $gml_value;
              break;
            }
            $iso_date_str = date("Y-m-d", $timestamp);
            $gmlStr .= $this->wrapWithElement("{$xplan_ns_prefix}{$uml_attribute['uml_name']}", $iso_date_str);
            break;
          }
        case 'e': // enum type
        default:
          $gml_value = trim($gml_object[$uml_attribute['col_name']]);
          // check for array values
          if ($gml_value[0] == '{' && substr($gml_value,-1) == '}') {
            $gml_value_array = explode(',',substr($gml_value, 1, -1));
            for ($j = 0; $j < count($gml_value_array); $j++){
              $gmlStr .= $this->wrapWithElement(
                  "{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
                  htmlspecialchars($gml_value_array[$j],ENT_QUOTES|ENT_XML1,"UTF-8"));
            }
          } else
          $gmlStr .= $this->wrapWithElement(
              "{$xplan_ns_prefix}{$uml_attribute['uml_name']}",
              htmlspecialchars($gml_value,ENT_QUOTES|ENT_XML1,"UTF-8"));
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

  /*
  * Diese Funktion parses den Inhalt des übergebenen
  * Query-Results für Spalten vom Typ Composite Datatype
  * und gibt die Werte in einem (ggf. verschachtelten)
  * Array zurück.
  */
  function parseCompositeDataType($data_string) {
    $value_array = array();
    $stack = array();
    $curr_value = '';
    // prepare data string
    $value_str = substr($data_string, 1, -1);
    $value_str = str_replace('\"(', '(', $value_str);
    $value_str = str_replace(')\"', ')', $value_str);
    $value_str = str_replace('\"', '&quot;', $value_str);
    $value_str = str_replace('"', '', $value_str);
    // return if no data
    if (strlen($value_str) == 0) return;
    // cast string to array
    $value_str_array = str_split($value_str);
    // parse character by character
    foreach ($value_str_array as $char) {
      switch ($char) {
        case '(':
          array_push($stack,$value_array);
          $value_array = array();
          break;
        case ')':
          $value_array[] = $curr_value;
          $curr_value = $value_array;
          $value_array = array_pop($stack);
          break;
        case ',':
          $value_array[] = $curr_value;
          $curr_value = '';
          break;
        default:
          $curr_value .= $char;
      }
    }
    $value_array[] = $curr_value;
    return $value_array;
  }
}
?>
