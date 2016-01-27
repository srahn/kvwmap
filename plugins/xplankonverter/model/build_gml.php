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
# Klasse gml_builder #
#############################

class gml_builder {
    
  function gml_builder($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }

  function build_gml($plan_id = "365208ec-c418-11e5-995f-93757a8c548c"){
    include('constants.php');
    
    // make constants available as variables (easier to use in double-quoted strings)
    $structureScheme = STRUCTURE_SCHEME;
    $contentScheme   = CONTENT_SCHEME;

    // TODO: als Parameter an die Funktion übergeben
    $plan_id = "365208ec-c418-11e5-995f-93757a8c548c";

    $xplan_gml = 
      "<XPlanAuszug 
        xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
        xmlns:wfs=\"http://www.opengis.net/wfs\" 
        xmlns:gml=\"http://www.opengis.net/gml\" 
        xmlns:xlink=\"http://www.w3.org/1999/xlink\" 
        xmlns:xplan=\"http://www.xplanung.de/xplangml/3/0\" 
        xmlns=\"http://www.xplanung.de/xplangml/3/0\" 
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
      if (!($cur_class = pg_fetch_row(pg_query(PG_CONNECTION, $sql)))) continue;

      // check, if there is a position field in that relation
      $sql = "SELECT 1 FROM pg_attribute WHERE attrelid = {$cur_class[0]} AND attname = 'position'";
      $position_attr = "";
      if (pg_fetch_row(pg_query(PG_CONNECTION, $sql))) {
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
      $gml_objects = pg_query(PG_CONNECTION, $sql);
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
}
  
?>
