BEGIN;
/*
Inserts enum value 3000 after enum value 2900
Unlike ALTER TYPE ADD VALUE AFTER, this also works within a begin-commit transaction block (i.e. a kvwmap migration)
*/
INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.xp_externereferenztyp'::regtype::oid, '3000', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype 
			AND enumlabel = '2900'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype 
			AND enumlabel = '2900'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype AND enumlabel = '3000' )
;

INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.xp_externereferenztyp'::regtype::oid, '4000', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype 
			AND enumlabel = '3000'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype 
			AND enumlabel = '3000'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype AND enumlabel = '4000' )
;

INSERT INTO
	pg_enum (enumtypid, enumlabel, enumsortorder)
SELECT
	'xplan_gml.xp_externereferenztyp'::regtype::oid, '5000', 
	CASE
		WHEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype 
			AND enumlabel = '4000'
		) IS NOT NULL
		THEN (
			SELECT enumsortorder + 0.001 
			 FROM pg_enum 
			WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype 
			AND enumlabel = '4000'
		) ELSE 1
		END
WHERE
	NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.xp_externereferenztyp'::regtype AND enumlabel = '5000' )
;

COMMIT;