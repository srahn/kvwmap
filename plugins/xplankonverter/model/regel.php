<?php
#############################
# Klasse Konvertierung #
#############################

class Regel extends PgObject {

  function Regel($gui, $schema) {
    $this->PgObject($gui, $schema, 'regeln');
  }

  /*
  * Führt die in der Regel definierten SQL-Statements aus um
  * Daten aus Shapefiles in die Tabellen der XPlan GML Datentabellen
  * zu schreiben. Dabei wird jedem neu erzeugtem XPlan GML
  * Objekt die Id der Konvertierung mitgegeben.
  * Optional wird eine gml_id eines Bereiches mitgegeben, die in der
  * Tabelle rp_breich2rp_objekt zusammen mit den gml_id's der erzeugten
  * XPlan GML Objekte eingetragen wird.
  */
  function convert($konvertierung_id, $bereich_gml_id = null) {
    $sql = $this->get('sql');
    $features = $this->getSQLResults($sql);
    foreach($features AS $feature) {
      if ($bereich_gml_id != '') {
        $sql = "
          INSERT INTO
            gml_classes.rp_object2rp_bereich
          SET
            rp_object_gml_id = '" . $feature->get('gml_id') . "',
            rp_bereich_gml_id = '" . $bereich_gml_id . "'
        ";
      }
    }
  }

}
  
?>
