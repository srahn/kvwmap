-- refScan Modification
BEGIN;
ALTER TABLE xplan_gml.xp_bereich ALTER COLUMN refscan TYPE xplan_gml.xp_externereferenz[] USING refscan::xplan_gml.xp_externereferenz[];
COMMIT;