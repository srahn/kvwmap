<?php
#############################
# Klasse TypeInfo #
#############################

class TypeInfo {

  static $schema = 'xplan_gml';//CONTENT_SCHEME;

  function __construct($database) {
    $this->_database=$database;
    $this->_typeInfoLUT = array();
  }

  function getInfo($typename, $include_inherited=true) {
    // schon vorhanden?
    if (!$_typeInfoLUT[$typename]) {
      // fetch list of attributes and their properties from UML-structure
      $uml_attributes = $this->fetchUmlAttributesForType($typename, $include_inherited);

      // fetch attribute information for all attributes of the type from persitence layer
      $attribInfo = $this->_database->get_attribute_information(TypeInfo::$schema, strtolower($typename));
      $attribInfo = XP_Plan::convert_xp_spezexternereferenzauslegung($attribInfo);

      // build look-up table
      $attribInfo = array_combine(array_column($attribInfo,'name'), $attribInfo);
      $objekt_attribs = array();
      $sequence = 0;
      foreach ($uml_attributes as $uml_attrib) {
        $lowercaseName = strtolower($uml_attrib['name']);
        $objekt_attribs[] = array(
            'uml_name'    => $uml_attrib['name'],
            'col_name'    => $attribInfo[$lowercaseName]['name'],
            'type'        => $attribInfo[$lowercaseName]['type'],
            'type_type'   => $attribInfo[$lowercaseName]['type_type'],
            'type_schema' => $attribInfo[$lowercaseName]['type_schema'],
            'is_array'    => $attribInfo[$lowercaseName]['is_array'] == 't',
            'stereotype'  => $this->getStereotype($attribInfo[$lowercaseName]['type']),
            'sequence'    => $sequence,
            'origin'      => $uml_attrib['origin'],
						'uml_dtype'   => $uml_attrib['dtype']
        );
        $sequence++;
      }
      $_typeInfoLUT[$typename] = $objekt_attribs;

      #echo $typename."\n";
#var_dump($_typeInfoLUT[$typename]);
    }
    return $_typeInfoLUT[$typename];
  }

  function fetchUmlAttributesForType($typename, $include_inherited=true) {
    $structure_schema = 'xplan_uml';
    $sql = "
        WITH " . ($include_inherited ? "RECURSIVE " : "") . "inheritance AS (
          SELECT
						xmi_id::text AS xmi_id,
						-1 AS inheritance_order
					FROM
						$structure_schema.uml_classes
					WHERE
						name ILIKE '$typename'".
        ($include_inherited
        ? "
          UNION
          SELECT
						inner_inh.parent_id AS xmi_id,
						inner_inh.inheritance_order
          FROM
						$structure_schema.class_generalizations inner_inh INNER JOIN
						inheritance ON inheritance.xmi_id = inner_inh.child_id"
        : "") . "
        )
        SELECT
					uc.name AS origin,
					ua.name AS name, ua.datatype AS stype,
					ua.multiplicity_range_upper = '*' as is_array,
					uc2.name AS ctype, dt.name AS dtype,
					tv.datavalue AS sequence,
					inheritance.order
        FROM
					(
						SELECT
							*,
				      case
				        when inheritance_order IS NULL then row_number() OVER () + 100
				      ELSE
				        inheritance_order
				      END AS order
						FROM
							inheritance
					) AS inheritance INNER JOIN
					$structure_schema.uml_classes uc ON uc.xmi_id = inheritance.xmi_id
        	INNER JOIN $structure_schema.uml_attributes ua ON ua.uml_class_id = uc.id
        	INNER JOIN $structure_schema.taggedvalues tv ON ua.id = tv.attribute_id
        	INNER JOIN $structure_schema.tagdefinitions td ON td.xmi_id = tv.type
        	LEFT JOIN $structure_schema.uml_classes uc2 ON ua.classifier = uc2.xmi_id
        	LEFT JOIN $structure_schema.datatypes dt ON ua.classifier = dt.xmi_id
        WHERE
					td.name = 'sequenceNumber'
        ORDER BY
					inheritance.order DESC,
					tv.datavalue::int ASC
      ";
    #echo '<p>' . $sql . ";";
    $uml_attributes = pg_query($this->_database->dbConn, $sql);
    return pg_fetch_all($uml_attributes);
  }

  function getStereotype($attributName){
    $sql = "
    SELECT st.name
    FROM xplan_uml.uml_classes c
    JOIN xplan_uml.stereotypes st ON c. stereotype_id = st.xmi_id
    WHERE c.name ILIKE '$attributName';
    ";
    $result = pg_query($this->_database->dbConn, $sql);
    return pg_fetch_array($result, NULL, PGSQL_ASSOC)['name'];
  }
}

?>
