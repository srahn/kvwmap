<?php
#############################
# Klasse Konvertierung #
#############################

class ShapeFile extends PgObject {

	static $schema = 'xplankonverter';
	static $tableName = 'shapefiles';
	static $write_debug = false;

	function ShapeFile($gui) {
		$this->PgObject($gui, ShapeFile::$schema, ShapeFile::$tableName);
		$this->importer = new data_import_export();
	}

	public static	function find_by_id($gui, $by, $id) {
		#echo '<br>find konvertierung by ' . $by . ' = ' . $id;
		$shapefile = new ShapeFile($gui);
		$shapefile->find_by($by, $id);
		return $shapefile;
	}

	function geometry_column_srid() {
		$sql = "
			SELECT
			  srid
			FROM
			  geometry_columns
			WHERE
				f_table_schema = '" . $this->dataSchemaName() . "' AND
				f_table_name = '" . $this->dataTableName() . "' AND
				f_geometry_column = 'the_geom'
		";
		$this->debug->show('<p>Get geometry_column_srid sql: ' . $sql, ShapeFile::$write_debug);
		$result = pg_query($this->database->dbConn, $sql);
		$row = pg_fetch_assoc($result);
		return $row['srid'];
	}

	function dataSchemaName() {
		return 'xplan_shapes_' . $this->get('konvertierung_id');
	}

	function qualifiedDataTableName() {
		return '"' . $this->dataSchemaName() . '"."' . $this->dataTableName() . '"';
	}

	function dataTableName() {
		#$this->debug->show('Wandel ' . $this->get('filename') . ' to ' . 'shp_'. strtolower(umlaute_umwandeln($this->get('filename'))));
		return 'shp_'. strtolower(umlaute_umwandeln($this->get('filename')));
	}

	function uploadShapePath() {
		$path = XPLANKONVERTER_FILE_PATH . $this->get('konvertierung_id') . '/uploaded_shapes/';
		return $path;
	}

	function uploadShapeFileName() {
		return	$this->uploadShapePath() . $this->get('filename');
	}

	/*
	* Delete the Layer in mySQL tables
	* representing this shape file
	*/
	function deleteLayer() {
		if ($this->get('layer_id') != '') {
			$this->debug->show('<p>Delete Layer in mysql db: ' . $this->dataTableName(), false);
			$this->gui->formvars['selected_layer_id'] = $this->get('layer_id');
			$this->gui->LayerLoeschen();
		}
		else {
			$this->debug->show('<p>Shapefile hat keine Layer-ID');
		}
	}

	/*
	* Delete the table with the data
	* of the shapefile
	*/
	function deleteDataTable() {
		$this->debug->show('<p>Delete data table in pgsql db: ' . $this->qualifiedDataTableName());
		$sql = "
			DROP TABLE IF EXISTS
				" . $this->qualifiedDataTableName() . "
		";
		$this->debug->show('<p>sql: ' . $sql, false);
		$result = pg_query($this->database->dbConn, $sql);
		return $result;
	}

	/*
	* Delete the shape files in the upload folder
	*/
	function deleteUploadFiles() {
		$this->debug->show('<p>Delete Upload Files');
		$konvertierung_id = $this->get('konvertierung_id');
		if ($this->get('konvertierung_id') == '' or $this->get('filename') == '')
			$this->find_by('id', $this->get('id'));

		foreach(array('shp', 'shx', 'dbf', 'sql') AS $extension) {
			$this->debug->show('<br>Delete file: ' . $this->uploadShapeFileName() . '.' . $extension);
			$shapefile = $this->uploadShapePath() . $this->get('filename') . '.' . $extension;
			if (is_file($shapefile))
				unlink($shapefile);
		}
	}

	function createDataTableSchema() {
		$this->debug->show('<p>Create shapes schema ' . $this->dataSchemaName() . ' if not exists.');
		$sql = "
			CREATE SCHEMA IF NOT EXISTS " . $this->dataSchemaName() . "
		";
		$this->debug->show('<p>sql: ' . $sql);
		$result = pg_query($this->database->dbConn, $sql);
		return $result;
	}

	function loadIntoDataTable() {
		$this->debug->show('<p>Lade Daten in die Tabelle: ' . $this->qualifiedDataTableName());

		#$encoding = $this->importer->getEncoding($this->uploadShapePath() . $this->get('filename') . '.dbf');
		/* wenn die dbf Datei eine Spalte vom Typ date beinhaltet, wirft die Konvertierung mit
		* ogr2ogr nach csv einen Fehler und das encoding wird als '' zurückgeliefert.
		* Dadurch wird enconding auf default LATIN1 gesetzt und das wirft beim Import Fehler wenn
		* die Daten doch in UTF-8 sind. Die Katze beißt sich in den Schwanz.
		* Sie auch Ansatz importer->getDbfEncoding
		* Wir gehen jetzt einfach erstmal davon aus, dass alle Shape-Files in UTF8 codiert sind.
		*/
		$encoding = 'UTF8';
		return $this->importer->ogr2ogr_import(
			$this->dataSchemaName(),
			$this->dataTableName(),
			$this->get('epsg_code'),
			$this->uploadShapePath() . $this->get('filename') . '.shp',
			$this->database,
			NULL,
			NULL,
			NULL,
			$encoding
		);
/*
		return $this->importer->load_shp_into_pgsql(
			$this->database,
			$this->uploadShapePath(),
			$this->get('filename'),
			$this->get('epsg_code'),
			$this->dataSchemaName(),
			$this->dataTableName()
		);
*/
	}

	function update_geometry_srid() {
		$sql = "
			SELECT
				UpdateGeometrySRID(
					'" . $this->dataSchemaName() . "',
					'" . $this->dataTableName() . "',
					'the_geom',
					" . $this->get('epsg_code') . "
				)
		";
		$this->debug->show('<p>Set geometry_column_srid sql: ' . $sql, ShapeFile::$write_debug);
		$result = pg_query($this->database->dbConn, $sql);
		return $result;
	}

	function gmlIdColumnExists() {
		$sql = "
			SELECT
				*
			FROM
  			information_schema.columns
			WHERE
  			table_schema = '" . $this->dataSchemaName() . "' AND
  			table_name = '" . $this->dataTableName() . "' AND
  			column_name = 'gml_id'
  		LIMIT 1
		";
		$this->debug->show('<p>Query for gml_id column sql: ' . $sql, ShapeFile::$write_debug);
		$result = pg_query($this->database->dbConn, $sql);
		return (pg_num_rows($result) == 1);
	}
	
	function addGmlIdColumn() {
		$sql = "
			ALTER TABLE " . $this->qualifiedDataTableName() . " ADD COLUMN gml_id character varying;
		";
		$this->debug->show('<p>Add column gml_id sql: ' . $sql, ShapeFile::$write_debug);
		$result = pg_query($this->database->dbConn, $sql);
	}
}
?>
