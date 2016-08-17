<?php
#############################
# Klasse Konvertierung #
#############################

class ShapeFile extends PgObject {

  function ShapeFile($gui, $schema, $tableName) {
    $this->PgObject($gui, $schema, $tableName);
    $this->importer = new data_import_export();
    $this->debug = false;
  }

  function dataSchemaName() {
    return 'xplan_shapes_' . $this->get('konvertierung_id');
  }

  function qualifiedDataTableName() {
    return '"' . $this->dataSchemaName() . '"."' . $this->dataTableName() . '"';
  }

  function dataTableName() {
    #$this->debug('Wandel ' . $this->get('filename') . ' to ' . 'shp_'. strtolower(umlaute_umwandeln($this->get('filename'))));
    return 'shp_'. strtolower(umlaute_umwandeln($this->get('filename')));
  }

  function uploadShapePath() {
    return XPLANKONVERTER_SHAPE_PATH . $this->get('konvertierung_id') . '/';
  }

  function uploadShapeFileName() {
    return  $this->uploadShapePath() . $this->get('filename');
  }

  /*
  * Delete the Layer in mySQL tables
  * representing this shape file
  */
  function deleteLayer() {
    if ($this->get('layer_id') != '') {
      $this->debug('<p>Delete Layer in mysql db: ' . $this->dataTableName());
      $this->gui->formvars['selected_layer_id'] = $this->get('layer_id');
      $this->gui->LayerLoeschen();
    }
    else {
      $this->debug('<p>Shapefile hat keine Layer-ID');
    }
  }

  /*
  * Delete the table with the data
  * of the shapefile
  */
  function deleteDataTable() {
    $this->debug('<p>Delete data table in pgsql db: ' . $this->qualifiedDataTableName());
    $sql = "
      DROP TABLE IF EXISTS
        " . $this->qualifiedDataTableName() . "
    ";
    $this->debug('<p>sql: ' . $sql);
    $result = pg_query($this->database->dbConn, $sql);
    return $result;
  }

  /*
  * Delete the shape files in the upload folder
  */
  function deleteUploadFiles() {
    $this->debug('<p>Delete Upload Files');
    $konvertierung_id = $this->get('konvertierung_id');
    if ($this->get('konvertierung_id') == '' or $this->get('filename') == '')
      $this->find_by('id', $this->get('id'));

    foreach(array('shp', 'shx', 'dbf', 'sql') AS $extension) {
      $this->debug('<br>Delete file: ' . $this->uploadShapeFileName() . '.' . $extension);
      $shapefile = XPLANKONVERTER_SHAPE_PATH . $this->get('konvertierung_id') . '/' . $this->get('filename') . '.' . $extension;
      if (is_file($shapefile))
        unlink($shapefile);
    }
  }

  function createDataTableSchema() {
    $this->debug('<p>Create shapes schema ' . $this->dataSchemaName() . ' if not exists.');
    $sql = "
      CREATE SCHEMA IF NOT EXISTS " . $this->dataSchemaName() . "
    ";
    $this->debug('<p>sql: ' . $sql);
    $result = pg_query($this->database->dbConn, $sql);
    return $result;
  }

  function loadIntoDataTable() {
    $this->debug('<p>Lade Daten in die Tabelle: ' . $this->qualifiedDataTableName());

    return $this->importer->load_shp_into_pgsql(
      $this->database,
      $this->uploadShapePath(),
      $this->get('filename'),
      $this->get('epsg_code'),
      $this->dataSchemaName(),
      $this->dataTableName()
    );
  }
}
  
?>
