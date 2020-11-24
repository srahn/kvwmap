BEGIN;
ALTER TABLE xplan_gml.so_plan
ALTER COLUMN planaufstellendegemeinde TYPE xplan_gml.xp_gemeinde[]
USING planaufstellendegemeinde::xplan_gml.xp_gemeinde[];
COMMIT;
