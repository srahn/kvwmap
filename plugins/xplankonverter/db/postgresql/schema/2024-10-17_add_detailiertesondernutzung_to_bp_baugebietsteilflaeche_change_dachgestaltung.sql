BEGIN;

  -- Erzeuge UML-Class BP_DetailSondernutzung als CodeListe
  INSERT INTO xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, created_at, "isAbstract", stereotype_id, general_id)
  SELECT
    'EAID_C2728C8E-CBD5-4BA0-8B2E-B6545C1D7A6D' AS xmi_id,
    'BP_DetailSondernutzung' AS name,
    'public' AS visibility,
    false AS "isSpecification",
    false AS "isRoot",
    false AS "isLeaf",
    9 AS package_id,
    now() AS created_at,
    false AS "isAbstract",
    (SELECT xmi_id FROM xplan_uml.stereotypes WHERE name LIKE 'CodeList') AS stereotype_id,
    -1 AS general_id
  WHERE
    NOT EXISTS (
      SELECT xmi_id FROM xplan_uml.uml_classes WHERE xmi_id = 'EAID_C2728C8E-CBD5-4BA0-8B2E-B6545C1D7A6D'
    )
  RETURNING id;

  -- Erzeuge UML-Attribut detailierteSondernutzung in Klasse BP_BaugebietsTeilFlaeche
  INSERT INTO xplan_uml.uml_attributes (xmi_id, name, uml_class_id, visibility, changeability, "targetScope", ordering, created_at, classifier, multiplicity_range_lower, multiplicity_range_upper, initialvalue_body)
  SELECT
    'EAID_1403910D-CB7E-4C1E-9B41-2391FCCAFD9F' AS xml_id,
    'detaillierteSondernutzung' AS name,
    (SELECT id FROM xplan_uml.uml_classes WHERE name LIKE 'BP_BaugebietsTeilFlaeche') AS uml_class_id,
    'public' AS visibility,
    'changeable' AS changeability,
    'instance' AS "targetScope",
    'unordered' AS ordering,
    now() AS created_at,
    (SELECT xmi_id FROM xplan_uml.uml_classes WHERE name LIKE 'BP_DetailSondernutzung') AS classifier,
    '0' AS multiplicity_range_lower,
    '*' AS multiplicity_range_upper,
    '' AS initialvalue_body
  WHERE
    NOT EXISTS (
      SELECT uml_class_id FROM xplan_uml.uml_attributes WHERE uml_class_id = 'EAID_1403910D-CB7E-4C1E-9B41-2391FCCAFD9F'
    );

  -- Ergänze eine Regel im gmlas_to_gml Mapping
  INSERT INTO xplankonverter.mappingtable_gmlas_to_gml (feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, regel)
  SELECT
    true AS feature_class,
    'bp_baugebietsteilflaeche' AS o_table,
    'detailliertesondernutzung' AS o_column,
    'character varying' AS o_data_type,
    'bp_baugebietsteilflaeche' AS t_table,
    'detailliertesondernutzung' AS t_column,
    'bp_detailsondernutzung' AS t_data_type,
    'gmlas.detailliertesondernutzung::xplan_gml.bp_detailsondernutzung[] AS detailliertesondernutzung' AS regel -- das geht nur, wenn detailliertesondernutzung im Schema gmlas auch schon ein array ist.
   WHERE
    NOT EXISTS (
      SELECT feature_class FROM xplankonverter.mappingtable_gmlas_to_gml WHERE feature_class = 'bp_baugebietsteilflaeche'
    );
 
  -- Zusätzliche Regeln für das Attribut detailliertesondernutzung für alle konvertierungen bei denen im gmlas.bp_baugebietsteilflaeche ein Attribut detailliertesondernutzung oder die Tabelle bp_baugebietsteilflaeche_detailliertesondernutzung gibt

  -- Regel für Dachgestaltung hinzufügen
  -- Ergänze eine Regel im gmlas_to_gml Mapping
  INSERT INTO xplankonverter.mappingtable_gmlas_to_gml (feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, complex_type, regel)
  SELECT
    true AS feature_class,
    'bp_baugebietsteilflaeche_dachgestaltung' AS o_table,
    'ogc_fid' AS o_column,
    'integer' AS o_data_type,
    'bp_baugebietsteilflaeche' AS t_table,
    'dachgestaltung' AS t_column,
    'bp_dachgestaltung' AS t_data_type,
    'bp_dachgestaltung[]' AS complex_type,
    'ARRAY[gmlas.dnmin,gmlas.dnmax,gmlas.dn,gmlas.dnzwingend,gmlas.dachform,gmlas.detailiertedachform]::xplan_gml.bp_dachgestaltung[] AS dachgestaltung' AS regel
  WHERE
    NOT EXISTS (
      SELECT feature_class FROM xplankonverter.mappingtable_gmlas_to_gml WHERE feature_class = 'bp_baugebietsteilflaeche_dachgestaltung'
    );

COMMIT;