<?php
#############################
# Klasse Konvertierung #
#############################

class ShapeFile extends PgObject {

  function ShapeFile($pgDatabase, $schema, $tableName) {
    $this->PgObject($pgDatabase, $schema, $tableName);
    $this->debug = false;
  }

  function dataSchemaName() {
    return 'xplan_shapes_' . $this->get('konvertierung_id');
  }

  function qualifiedDataTableName() {
    return '"' . $this->dataSchemaName() . '"."' . $this->get('filename') . '"';
  }

  function uploadShapeFileName() {
    return XPLANKONVERTER_SHAPE_PATH . $this->get('konvertierung_id') . '/' . $this->get('filename');
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
    $this->debug('<p>Delete Layer: ' . $this->get('filename'));
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

    foreach(array('shp', 'shx', 'dbf') AS $extension) {
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
    $this->loadIntoDataTable();
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

    $command = POSTGRESBINPATH .
      'shp2pgsql -g geom -W LATIN1 -I ' .
      $this->uploadShapeFileName() . ' ' .
      $this->qualifiedDataTableName() . ' > ' .
      $this->uploadShapeFileName() . '.sql';
    $this->debug('<p>Exec command: ' . $command);
    exec($command);
	
  	$command = POSTGRESBINPATH .
      'psql' .
      ' -U ' . $this->pgDatabase->user .
      ' -f ' . $this->uploadShapeFileName() . '.sql' .
      ' ' . $this->pgDatabase->dbName;
  	if($this->pgDatabase->passwd != '')
      $command = 'export PGPASSWORD=' . $this->pgDatabase->passwd .'; ' . $command;
    $this->debug('<p>Exec command: ' . $command);
    exec($command);
  }
}
  
?>
