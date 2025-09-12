BEGIN;
  ALTER TABLE IF EXISTS xplan_gml.bp_objekt ALTER COLUMN reftextinhalt TYPE text[] USING ARRAY[reftextinhalt];
  DROP INDEX IF EXISTS xplan_gml.xp_ppo_dientzurdarstellungvon_idx;
  ALTER TABLE IF EXISTS xplan_gml.xp_abstraktespraesentationsobjekt ALTER COLUMN dientzurdarstellungvon TYPE text[] USING ARRAY[dientzurdarstellungvon];
  ALTER TABLE IF EXISTS xplan_gml.xp_textabschnitt ALTER COLUMN inverszu_reftextinhalt_bp_objekt TYPE text[] USING ARRAY[inverszu_reftextinhalt_bp_objekt::text];
  CREATE OR REPLACE function gdi_array_unique (a text[]) RETURNS text[] AS
  $$
    SELECT ARRAY (
      SELECT DISTINCT v FROM unnest(a) AS b(v)
    )
  $$ language sql;
  ALTER TABLE IF EXISTS xplankonverter.konvertierungen ADD COLUMN xsd_version character varying;

  INSERT INTO xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, created_at, "isAbstract", stereotype_id, general_id)
  SELECT
    'EAID_1D9258EC-D1C5-11EF-A34A-6F300EB9F1B4' AS xmi_id,
    'BP_EmissionskontingentLaerm' AS name,
    'public' AS visibility,
    false AS "isSpecification",
    false AS "isRoot",
    false AS "isLeaf",
    9 AS package_id,
    now() AS created_at,
    false AS "isAbstract",
    (SELECT xmi_id FROM xplan_uml.stereotypes WHERE name LIKE 'DataType') AS stereotype_id,
    -1 AS general_id
  WHERE
    NOT EXISTS (
      SELECT xmi_id FROM xplan_uml.uml_classes WHERE name = 'BP_EmissionskontingentLaerm'
    )
  RETURNING id;

  INSERT INTO xplan_uml.uml_classes (xmi_id, name, visibility, "isSpecification", "isRoot", "isLeaf", package_id, created_at, "isAbstract", stereotype_id, general_id)
  SELECT
    'EAID_655073DA-D1C5-11EF-A34A-7355B6A56137' AS xmi_id,
    'BP_EmissionskontingentLaermGebiet' AS name,
    'public' AS visibility,
    false AS "isSpecification",
    false AS "isRoot",
    false AS "isLeaf",
    9 AS package_id,
    now() AS created_at,
    false AS "isAbstract",
    (SELECT xmi_id FROM xplan_uml.stereotypes WHERE name LIKE 'DataType') AS stereotype_id,
    -1 AS general_id
  WHERE
    NOT EXISTS (
      SELECT xmi_id FROM xplan_uml.uml_classes WHERE name = 'BP_EmissionskontingentLaermGebiet'
    )
  RETURNING id;
COMMIT;