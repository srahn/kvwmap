<?php
#############################
# Klasse Konvertierung #
#############################

class ShapeFile extends PgObject {

  function ShapeFile($pgDatabase, $schema, $tableName) {
    $this->PgObject($pgDatabase, $schema, $tableName);
  }

  function deleteShape() { 
    echo 'deleteShape()';
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
    echo '<p>Delte Layer';
  }

  /*
  * Delete the table with the data
  * of the shapefile
  */
  function deleteDataTable() {
    echo '<p>Delete Table';
    $sql = "
      DROP TABLE
        " . $this->qualifiedTableName . "
    ";
    echo '<p>sql: ' . $sql;
#    $result = pg_query($this->pgDatabase->dbConn, $sql);
    return $result;
  }

  /*
  * Delete the shape files in the upload folder
  */
  function deleteUploadFiles() {
    echo '<p>Delete Upload Files';
    $konvertierung_id = $this->get('konvertierung_id');
    if ($this->get('konvertierung_id') == '' or $this->get('fileName') == '')
      $this->find_by_id($this->get('id'));

    foreach(array('shp', 'shx', 'dbf') AS $extension) {
      unlink(XPLANKONVERTER_SHAPE_PATH . $this->get('konvertierung_id') . '/' . $this->get('filename') . '.' . $extension);
    }
  }
}
  
?>
