BEGIN;
	ALTER TABLE xplan_gml.so_plan ADD COLUMN IF NOT EXISTS versionbaugbdatum date;
	ALTER TABLE xplan_gml.so_plan ADD COLUMN IF NOT EXISTS versionbaugbtext character varying;
	ALTER TABLE xplan_gml.so_plan ADD COLUMN IF NOT EXISTS versionsonstrechtsgrundlagedatum date;
	ALTER TABLE xplan_gml.so_plan ADD COLUMN IF NOT EXISTS versionsonstrechtsgrundlagetext character varying;
	
	COMMENT ON COLUMN xplan_gml.so_plan.versionbaugbtext IS 'versionBauGBText  CharacterString 0..1';
	COMMENT ON COLUMN xplan_gml.so_plan.versionsonstrechtsgrundlagetext IS 'versionSonstRechtsgrundlageText  CharacterString 0..1';
	COMMENT ON COLUMN xplan_gml.so_plan.versionsonstrechtsgrundlagedatum IS 'versionSonstRechtsgrundlageDatum  Date 0..1';
	COMMENT ON COLUMN xplan_gml.so_plan.versionbaugbdatum IS 'versionBauGBDatum  Date 0..1';

COMMIT;
