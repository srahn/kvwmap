<?php
#############################
# Klasse Konvertierung #
#############################

class Konvertierung extends PgObject {

  static $STATUS = array('in Erstellung', 'erstellt', 'in Validierung', 'Validierung fehlgeschlagen' ,'validiert', 'in Konvertierung', 'Konvertierung abgeschlossen', 'Konvertierung abgebrochen');

  function Konvertierung($gui, $schema, $tableName) {
    $this->PgObject($gui, $schema, $tableName);
  }

  function createLayerGroup() {
    $layerGroup = new LayerGroup($this->gui->database);
    $layerGroup->create(array(
      'Gruppenname' => $this->get('bezeichnung')
    ));
    $this->set('layer_group_id', $layerGroup->get('id'));
    $this->update();
    return $this->get('layer_group_id');
  }

  /*
  * Diese Funktion löscht alle zuvor für diese Konvertierung angelegten
  * XPlan GML Datensätze und Beziehungen im Schema gml_classes
  */
  function resetMapping() {
#    $tables = get all table names of xplan gml feature types
    foreach($tables AS $table) {
      $sql = "
        DELETE FROM
          " . $table . "
        WHERE
          konvertierung_id = " . $this->get('id') . "
      ";
    }
  }

  /*
  * Diese Funktion führt das Mapping zwischen den Shape Dateien
  * und den in den Regeln definierten XPlan GML Features durch.
  * Jedes im Mapping erzeugte Feature bekommt eine eindeutige gml_id.
  * Darüber hinaus muss die Zuordnung zum überordneten Objekt
  * abgebildet werden. Das kann zu einem oder mehreren Bereichen
  * in n:m Beziehung sein rp_bereich2rp_object oder zur Konvertierung
  * (gml_id des documentes oder konvertierung_id)
  */
  function mapping() {
    # finde alle regeln, die direkt der Konvertierung zugeordnet wurden
    $regeln = $this->find_by('konvertierung_id', $this->get('id'));
    foreach($regeln AS $regel) {
      $regel->convert($this->get('id'));
    }
  }

}

?>
