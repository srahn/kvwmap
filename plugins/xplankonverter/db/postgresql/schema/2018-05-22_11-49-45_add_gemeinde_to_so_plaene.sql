BEGIN;

	ALTER TABLE xplan_gml.so_plan ADD COLUMN gemeinde xplan_gml.xp_gemeinde[];

COMMIT;
