BEGIN;
/*
Inserts enum value 3000 after enum value 2900
Unlike ALTER TYPE ADD VALUE AFTER, this also works within a begin-commit transaction block (i.e. a kvwmap migration)
*/
ALTER TYPE xplan_gml.rp_wasserwirtschafttypen ADD VALUE '8000' AFTER '7000';
/*
INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.rp_wasserwirtschafttypen'::regtype::oid, '8000', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.rp_wasserwirtschafttypen'::regtype 
			AND enumlabel = '7000'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.rp_wasserwirtschafttypen'::regtype 
			AND enumlabel = '7000'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.rp_wasserwirtschafttypen'::regtype AND enumlabel = '8000' )
;
*/
ALTER TYPE xplan_gml.rp_wasserwirtschafttypen ADD VALUE '8100' AFTER '8000';
/*
INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.rp_wasserwirtschafttypen'::regtype::oid, '8100', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.rp_wasserwirtschafttypen'::regtype 
			AND enumlabel = '8000'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.rp_wasserwirtschafttypen'::regtype 
			AND enumlabel = '8000'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.rp_wasserwirtschafttypen'::regtype AND enumlabel = '8100' );
*/
COMMIT;