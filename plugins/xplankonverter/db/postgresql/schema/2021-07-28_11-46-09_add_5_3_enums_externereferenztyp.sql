BEGIN;
ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '3000' AFTER '2900';
ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '4000' AFTER '3000';
ALTER TYPE xplan_gml.xp_externereferenztyp ADD VALUE '5000' AFTER '4000';
COMMIT;