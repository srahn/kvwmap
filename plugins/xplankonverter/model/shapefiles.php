<?php
#############################
# Klasse Konvertierung #
#############################

class ShapeFile extends PgObject {

  function ShapeFile($myDatabase, $pgDatabase, $stelle, $user, $schema, $tableName, $epsg) {
    $this->PgObject($pgDatabase, $schema, $tableName, $epsg);
    $this->myDatabase = $myDatabase;
    $this->stelle = $stelle;
    $this->user = $user;
    $this->epsg = $epsg;
    $this->importer = new data_import_export();
    $this->debug = false;
  }

  function dataSchemaName() {
    return 'xplan_shapes_' . $this->get('konvertierung_id');
  }

  function qualifiedDataTableName() {
    return '"' . $this->dataSchemaName() . '"."' . $this->get('filename') . '"';
  }

  function dataTableName() {
    $this->debug('Wandel ' . $this->get('filename') . ' to ' . 'shp_'. strtolower(umlaute_umwandeln($this->get('filename'))));
    return 'shp_'. strtolower(umlaute_umwandeln($this->get('filename')));
  }

  function uploadShapePath() {
    return XPLANKONVERTER_SHAPE_PATH . $this->get('konvertierung_id') . '/';
  }

  function uploadShapeFileName() {
    return  $this->uploadShapePath() . $this->get('filename');
  }

  function deleteShape() { 
    $this->deleteLayer();
    $this->deleteDataTable();
    $this->deleteUploadFiles();
    $this->delete(); # function in supper class
  }

  /*
  * Delete the Layer in mySQL tables
  * representing this shape file
  */
  function deleteLayer() {
    $this->debug('<p>Delete Layer: ' . $this->dataTableName());
  }

  /*
  * Delete the table with the data
  * of the shapefile
  */
  function deleteDataTable() {
    $this->debug('<p>Delete Table: ' . $this->qualifiedDataTableName());
    $sql = "
      DROP TABLE IF EXISTS
        " . $this->qualifiedDataTableName() . "
    ";
    $this->debug('<p>sql: ' . $sql);
    $result = pg_query($this->pgDatabase->dbConn, $sql);
    return $result;
  }

  /*
  * Delete the shape files in the upload folder
  */
  function deleteUploadFiles() {
    $this->debug('<p>Delete Upload Files');
    $konvertierung_id = $this->get('konvertierung_id');
    if ($this->get('konvertierung_id') == '' or $this->get('fileName') == '')
      $this->find_by_id($this->get('id'));

    foreach(array('shp', 'shx', 'dbf', 'sql') AS $extension) {
      $this->debug('<br>' . $this->uploadShapeFileName() . '.' . $extension);
      unlink(XPLANKONVERTER_SHAPE_PATH . $this->get('konvertierung_id') . '/' . $this->get('filename') . '.' . $extension);
    }
  }

  function create($params) {
    # create record in shapefiles table
    foreach($params AS $key => $value) {
      $this->set($key, $value);
    }
    $this->save();

    # Create schema for data table if not exists
    $this->createDataTableSchema();

    # load into database table
    $created_tables = $this->loadIntoDataTable();

    # create rollen layer
    $this->debug('Create layer');
#    $this->importer->create_layer($this->myDatabase, $this->pgDatabase, $this->stelle, $this->user, $this->dataTableName(), $this->dataSchemaName(), $created_tables[0], $this->epsg);
  }

  function createDataTableSchema() {
    $this->debug('<p>Create shapes schema ' . $this->dataSchemaName() . ' if not exists.');
    $sql = "
      CREATE SCHEMA IF NOT EXISTS " . $this->dataSchemaName() . "
    ";
    $this->debug('<p>sql: ' . $sql);
    $result = pg_query($this->pgDatabase->dbConn, $sql);
    return $result;
  }

  function loadIntoDataTable() {
    $this->debug('<p>Lade Daten in die Tabelle: ' . $this->qualifiedDataTableName());
    $this->deleteDataTable();

    return $this->importer->load_shp_into_pgsql(
      $this->pgDatabase,
      $this->uploadShapePath(),
      $this->get('filename'),
      '25832',
      $this->dataSchemaName(),
      $this->dataTableName()
    );
  }
}
  
?>
