 BEGIN;
	ALTER TABLE xplan_gml.rp_status ADD COLUMN value text;
	ALTER TABLE xplan_gml.rp_sonstplanart ADD COLUMN value text;
 COMMIT;