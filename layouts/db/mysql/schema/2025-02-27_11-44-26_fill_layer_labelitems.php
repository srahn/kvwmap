<?

if (isset($this) AND is_object($this) AND get_class($this) == 'administration') {
  global $GUI;
  $mapDB = new db_mapObj($GUI->Stelle->id, $GUI->user->id);
  include_once(CLASSPATH . 'Layer.php');
	$layers = Layer::find($GUI, "labelitem != '' AND Data != ''");

  $sql = '';
  foreach ($layers as $layer){
    try {
      echo '<br>Layer-ID: ' . $layer->get('layer_id') . ':';
      $layerdb = $mapDB->getlayerdatabase($layer->get('layer_id'), $GUI->Stelle->pgdbhost);
      if ($layerdb) {
        $query_attributes = $mapDB->read_layer_attributes($layer->get('layer_id'), $layerdb, NULL, false, false, false);
        try {
          $attributes = $mapDB->getDataAttributes($layerdb, $layer->get('layer_id'));
          $order = 2;
          foreach($attributes as $attribute){
            if(is_array($attribute) AND $attributes['the_geom'] != $attribute['name']){
              $index = $query_attributes['indizes'][$attribute['name']];
              $sql .= "
                INSERT INTO 
                  layer_labelitems
                VALUES (
                  " . $layer->get('layer_id') . ",
                  '" . $attribute['name'] . "',
                  '" . $query_attributes['alias'][$index] . "',
                  " . (($layer->get('labelitem') == $attribute['name']) ? '1' : $order) . ");";
              if ($layer->get('labelitem') != $attribute['name']) {
                $order++;
              }
            }
          }
          if (is_array($attributes)) {
            echo ' Labelitems eingetragen.';
          }
        }
        catch (Exception $ex) {
          echo ' Labelitems nicht aus Data ermittelbar.';exit;
        }
      }
    }
    catch (Exception $ex) {
      exit;
    }
  }
  if ($sql != ''){
    $result = $this->database->exec_commands($sql, NULL, NULL, false, true);
  }
}

?>
