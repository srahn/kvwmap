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
}

?>
