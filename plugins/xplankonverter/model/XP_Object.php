<?php
#############################
# Klasse XP_Object #
#############################

class XP_Object extends PgObject {

	static $schema = 'xplan_gml';

	function __construct($konvertierung, $class_name) {
		parent::__construct($konvertierung->gui, XP_Object::$schema, strtolower($class_name));
		$this->class_name = $class_name;
		$this->konvertierung = $konvertierung;
		$this->identifier = 'gml_id';
		$this->identifier_type = 'text';
		$this->konformitaetsbedingungen = array();
	}

	public function get_sub_classes() {
		$sql = "
			SELECT
			  c.name
			FROM
			  xplan_uml.class_generalizations cg JOIN
			  xplan_uml.uml_classes p ON cg.parent_id = p.xmi_id JOIN
			  xplan_uml.uml_classes c ON cg.child_id = c.xmi_id
			WHERE
			  p.name = '{$this->class_name}'
		";
		$this->debug->show('sql to find sub classes for class ' . $this->tableName . ': ' . $sql, false);
		$query = pg_query($this->database->dbConn, $sql);
		$rows = pg_fetch_all($query);
		if (empty($rows)) {
			$sub_classes = array();
		}
		else {
			$sub_classes = array_map(
				function ($row) {
					return $row['name'];
				},
				$rows
			);
		}
		return $sub_classes;
	}

	# Order by gml_id to always get same order if a plan is reloaded with GMLAS
	public function get_object_rows() {
		$sub_classes = $this->get_sub_classes();
		$sql = "
			SELECT
				{$this->tableName}.*,
				{$this->tableName}.gehoertzubereich AS bereiche_gml_ids,
				ST_AsGML(
					3,
					CASE WHEN ST_GeometryType({$this->tableName}.position) LIKE 'ST_CurvePolygon' THEN
						ST_Transform(
							CASE WHEN ST_NumGeometries({$this->tableName}.position) = 1 THEN ST_GeometryN({$this->tableName}.position, 1) ELSE {$this->tableName}.position END,
							{$this->konvertierung->get('output_epsg')}
						)
					ELSE
						ST_Reverse(
							ST_Transform(
								CASE WHEN ST_NumGeometries({$this->tableName}.position) = 1 THEN ST_GeometryN({$this->tableName}.position, 1) ELSE {$this->tableName}.position END,
								{$this->konvertierung->get('output_epsg')}
							)
						)
					END,
					{$this->konvertierung->get('geom_precision')},
					0,
					null,
					'GML_' || {$this->tableName}.gml_id::text || '_geom'
				) AS gml_position,
				ST_AsGML(
					3,
					ST_Transform(
						{$this->tableName}.position,
						{$this->konvertierung->get('output_epsg')}
					),
					{$this->konvertierung->get('geom_precision')},
					32,
					null,
					'GML_' || {$this->tableName}.gml_id::text || '_envelope'
				) AS envelope
			FROM
				{$this->schema}.{$this->tableName}" .
				implode(
					'',
					array_map(
						function($sub_class) {
							$sub_class_table_name = strtolower($sub_class);
							$table_sql = " LEFT JOIN
				{$this->schema}.{$sub_class_table_name} ON {$this->tableName}.gml_id = {$sub_class_table_name}.gml_id";
							return $table_sql;
						},
						$sub_classes
					)
				) . "
			WHERE
				{$this->tableName}.konvertierung_id = {$this->konvertierung->get('id')}" .
				implode(
					'',
					array_map(
						function($sub_class) {
							$sub_class_table_name = strtolower($sub_class);
							$where_sql = " AND
				{$sub_class_table_name}.gml_id IS NULL";
							return $where_sql;
						},
						$sub_classes
					)
				) . "
			ORDER BY
				gml_id
		";

		$this->debug->show('sql to find objects for class ' . $this->tableName . ': ' . $sql, false);

		$query = pg_query($this->database->dbConn, $sql);

		return pg_fetch_all($query);
	}
	
	# Order by gml_id to always get same order if a plan is reloaded with GMLAS
	# textabschnitte do not have gehoertzubereich and no geometry
	public function get_textabschnitt_rows() {
		$sub_classes = $this->get_sub_classes();
		$sql_ta = "
			SELECT
				{$this->tableName}.*
			FROM
				{$this->schema}.{$this->tableName}" .
				implode(
					'',
					array_map(
						function($sub_class) {
							$sub_class_table_name = strtolower($sub_class);
							$table_sql = " LEFT JOIN
				{$this->schema}.{$sub_class_table_name} ON {$this->tableName}.gml_id = {$sub_class_table_name}.gml_id";
							return $table_sql;
						},
						$sub_classes
					)
				) . "
			WHERE
				{$this->tableName}.konvertierung_id = {$this->konvertierung->get('id')}" .
				implode(
					'',
					array_map(
						function($sub_class) {
							$sub_class_table_name = strtolower($sub_class);
							$where_sql = " AND
				{$sub_class_table_name}.gml_id IS NULL";
							return $where_sql;
						},
						$sub_classes
					)
				) . "
			ORDER BY
				gml_id
		";

		$this->debug->show('sql to find textabschnitt-objects for class ' . $this->tableName . ': ' . $sql_ta, false);

		$query = pg_query($this->database->dbConn, $sql_ta);

		return pg_fetch_all($query);
	}

}

?>
