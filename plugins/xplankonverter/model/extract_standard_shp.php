<?php
class Standard_shp_extractor {
	function __construct($database, $konvertierung_id, $bereich_gml_id, $stelle_id) {
		global $debug;
		$this->debug = $debug;
		$this->pgdatabase = $database;
		$this->konvertierung_id = $konvertierung_id;
		$this->bereich_gml_id = $bereich_gml_id;
		$this->stelle_id = $stelle_id;
	}

	/*
	* Creates and inserts regeln for all uploaded standardized shape in a schema
	*/
	function create_regeln_for_standard_shps() { 
		$shape_schema = 'xplan_shapes_' . $this->konvertierung_id;
		$shapes = $this->get_all_tables_of_schema($shape_schema);
		$sql = "INSERT INTO xplankonverter.regeln(class_name, factory, sql, beschreibung, geometrietyp, name, konvertierung_id, stelle_id, bereich_gml_id) ";
		if(!empty($shapes)){
			$sql .= "VALUES";
		}
		foreach($shapes as $shape) {
			$sql .= "(";
			# class_name from UML
			# TODO could be made more generic (UML-Classname function is e.g. also in extract_gml
			$cut_shapename = (substr($shape, 0, 4) == 'shp_')? substr($shape, 4) : $shape;
			if((substr($cut_shapename , -5) == '_poin') or
				 (substr($cut_shapename , -5) == '_line') or
				 (substr($cut_shapename , -5) == '_poly')){
				$cut_shapename = substr($cut_shapename, 0, -5);
			}
			$uml_class = $this->get_uml_class($cut_shapename);
			$sql .= "'" . $uml_class . "', ";
			# factory
			$sql .= "'sql'::xplankonverter.enum_factory, ";
			# sql
			$sql .= "'";
			$sql_regel = $this->standard_shape_to_regel_sql($shape, $shape_schema);
			$sql_regel = str_replace("'", "''", $sql_regel); # Replaces all single commas with 2x single commas to escape them in SQL
			$sql .= $sql_regel;
			$sql .= "',";

			# beschreibung
			$sql .= "'regel created automatically from standard shape', ";
			# geometrietyp und name
			$geom_type = $this->get_geom_type_of_shp($shape_schema, $shape);
			// fallthrough, use as is so these values wont be filled with emtpy shapes (no default case)
			switch($geom_type) {
					case "ST_Point":
					case "ST_MultiPoint":
						$sql .= " 'Punkte'::xplankonverter.enum_geometrie_typ, ";
						$sql .= "'" . $uml_class . "_standard_shp_" . "Punkte', ";
						break;
					case "ST_LineString":
					case "ST_MultiLineString":
						$sql .= " 'Linien'::xplankonverter.enum_geometrie_typ, ";
						$sql .= "'" . $uml_class . "_standard_shp_" . "Linien', ";
						break;
					case "ST_Polygon":
					case "ST_MultiPolygon":
						$sql .= " 'Flächen'::xplankonverter.enum_geometrie_typ, ";
						$sql .= "'" . $uml_class . "_standard_shp_" . "Flaechen', ";
						break;
				}
			# konvertierung_id
			$sql .= $this->konvertierung_id . ", ";
			$sql .= $this->stelle_id . ",";
			$sql .= "'" . $this->bereich_gml_id . "'::text::uuid";
			$sql .= "),";
		}
		# Remove last comma
		if(substr($sql, -1, 1) == ",") {
			$sql = substr($sql, 0, -1);
		}
		# echo $sql;
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
	}

	/*
	* Creates the sql of a regel for standard shapes
	*/
	function standard_shape_to_regel_sql($shape, $schema) {
		# removes shp_ , _line, _poin, _poly if exists, otherwise use real name
		# TODO Findother ways to find shape name as it could possibly change from actual xplan-classname?
		$target_table = (substr($shape, 0, 4) == 'shp_')? substr($shape, 4) : $shape;
			if((substr($target_table , -5) == '_poin') or
				 (substr($target_table , -5) == '_line') or
				 (substr($target_table , -5) == '_poly')){
				$target_table = substr($target_table, 0, -5);
			}
		$mapping_table = $this->mapping_table_shp_to_db($target_table);

		$regel = "INSERT INTO " . XPLANKONVERTER_CONTENT_SCHEMA . "." . $target_table;
		$shape_attributes = $this->get_attributes_for_shape($shape, $schema);

		$select_regel = 'SELECT ';
		$db_attributes = [];
		foreach($shape_attributes as $shp_a) {
			if($shp_a == 'the_geom') {
				continue; # skip
			}
			# attributes with 10 or less signs will map 1 to 1 as is but for consistency will be read from table
			# counts first dimension of multiarray
			for($i = 0; $i < count($mapping_table); $i++) {
				if($mapping_table[$i]['shp_attribute'] == $shp_a) {
					$select_regel .= $mapping_table[$i]['regel'] . ", ";
					$db_attributes[] = $mapping_table[$i]['db_attribute'];
				}
			}
		}
		# NOTE: position as attribute only works for objects that inherit from xp_objekt
		# If this is implemented e.g. for an exported plan, it will not work (as the geometry column is raeumlichergeltungsbereich)
		$db_attributes[] = "position";
		$select_regel .= "shp.the_geom AS position ";

		# enter all attributes here
		$regel .= "(";
		$regel .= implode(",", $db_attributes);
		$regel .= ") ";

		$regel .= $select_regel;
		$regel .= "FROM " . $schema . "." . $shape . " shp ";

		return $regel;
	}

	/*
	*	Returns an array of all tables of a schema according to the informationschema
	*/
	function get_all_tables_of_schema($shape_schema) {
	$sql = "
		SELECT
			table_name
		FROM
			information_schema.tables
		WHERE
			table_schema  = '". $shape_schema . "';
	";
	$ret = $this->pgdatabase->execSQL($sql, 4, 0);
	$result = pg_fetch_all_columns($ret[1]);
	return $result;
	}

	/*
	* Returns UML capitalization of classname
	* TODO This function also exists e.g. in gml_extractor -> make more generic
	*/
	function get_uml_class($class) {
		$sql = "SELECT DISTINCT name FROM xplan_uml.uml_classes WHERE LOWER(name) = '" . $class . "'";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$uml_class = pg_fetch_row($ret[1]);
		return $uml_class[0];
	}

	/*
	* Returns an array of arrays of mapping rules for a standard-shape class
	* from an existing mapping-table for all shapes
	*/
	function mapping_table_shp_to_db($table) {
		$sql = "
			SELECT
				tabelle, shp_attribute, db_attribute, ambiguous_fields, data_type, regel
			FROM
				xplankonverter.mappingtable_standard_shp_to_db
			WHERE
				tabelle = '" . $table . "'
			";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_all($ret[1]);
		return $result;
	}

	/*
	* Returns an array of all attributs of a specified shape in a specified schema
	*/
	function get_attributes_for_shape($shape, $shape_schema) {
		$sql = "
			SELECT
				column_name
			FROM
				information_schema.columns
			WHERE
				table_schema  = '" . $shape_schema . "' AND
				table_name    = '" . $shape . "'
			";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_all_columns($ret[1]);
		return $result;
	}

	/*
	* Returns the geom_type of a specified shape in a specified schema
	*/
	function get_geom_type_of_shp($schema, $shape) {
		# The first found geom will be returned, multiple geoms should not occur for standardized shapes
		# as geom-types are usually marked with _line, _poin, _poly (when multiple occur)
		$sql = "
			SELECT
				DISTINCT ST_GeometryType(the_geom)
			FROM
				" . $schema ."." . $shape . "
			";
		$ret = $this->pgdatabase->execSQL($sql, 4, 0);
		$result = pg_fetch_row($ret[1]);
		return $result[0];
	}
}
?>