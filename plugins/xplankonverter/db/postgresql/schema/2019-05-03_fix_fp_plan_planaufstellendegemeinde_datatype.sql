BEGIN;
	ALTER TABLE xplan_gml.fp_plan DROP COLUMN IF EXISTS planaufstellendegemeinde;
	ALTER TABLE xplan_gml.fp_plan ADD COLUMN planaufstellendegemeinde xplan_gml.xp_gemeinde[];
	COMMENT ON COLUMN xplan_gml.bp_plan.technischerplanersteller IS 'planaufstellendeGemeinde xplan_gml.xp_gemeinde 0..*';
ROLLBACK;