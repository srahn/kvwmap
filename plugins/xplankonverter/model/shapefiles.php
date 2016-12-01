<?php
#############################
# Klasse Konvertierung #
#############################

class ShapeFile extends PgObject {

	static $schema = 'xplankonverter';
	static $tableName = 'konvertierungen';
	static $write_debug = false;

	function ShapeFile($gui, $schema, $tableName) {
		$this->PgObject($gui, $schema, $tableName);
		$this->importer = new data_import_export();
	}

	public static	function find_by_id($gui, $by, $id) {
		#echo '<br>find konvertierung by ' . $by . ' = ' . $id;
		$shapefile = new ShapeFile($gui, 'xplankonverter', 'shapefiles');
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
		return XPLANKONVERTER_SHAPE_PATH . $this->get('konvertierung_id') . '/';
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
			$shapefile = XPLANKONVERTER_SHAPE_PATH . $this->get('konvertierung_id') . '/' . $this->get('filename') . '.' . $extension;
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

		return $this->importer->load_shp_into_pgsql(
			$this->database,
			$this->uploadShapePath(),
			$this->get('filename'),
			$this->get('epsg_code'),
			$this->dataSchemaName(),
			$this->dataTableName()
		);
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
}
	
?>
