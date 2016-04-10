<?php
#############################
# Klasse Konvertierung #
#############################

class Konvertierung extends PgObject {

  static $STATUS = array('in Bearbeitung', 'Konvertierung offen', 'Konvertierung abgeschlossen');
  
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
