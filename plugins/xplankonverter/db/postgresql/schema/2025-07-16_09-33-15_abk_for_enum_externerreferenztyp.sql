BEGIN;
  ALTER TABLE xplan_gml.enum_xp_externereferenztyp ADD abk varchar NULL;
  UPDATE xplan_gml.enum_xp_externereferenztyp SET abk = beschreibung WHERE abk IS NULL;
COMMIT;