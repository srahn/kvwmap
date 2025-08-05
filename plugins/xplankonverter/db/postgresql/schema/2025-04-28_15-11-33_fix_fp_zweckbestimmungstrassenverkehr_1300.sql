
BEGIN;
	INSERT INTO xplan_gml.enum_fp_zweckbestimmungstrassenverkehr (wert,abkuerzung,beschreibung)
	VALUES
	(
		1300,
		'Ortsdurchfahrt',
		'Ortsdurchfahrt'
	)
	ON CONFLICT DO NOTHING;

	INSERT INTO pg_enum (enumtypid, enumlabel, enumsortorder)
	SELECT
		'xplan_gml.fp_zweckbestimmungstrassenverkehr'::regtype::oid, '1300', 
	CASE
		WHEN (SELECT enumsortorder + 0.001 FROM pg_enum WHERE enumtypid = 'xplan_gml.fp_zweckbestimmungstrassenverkehr'::regtype AND enumlabel = '1200') IS NOT NULL
		THEN (SELECT enumsortorder + 0.001 FROM pg_enum WHERE enumtypid = 'xplan_gml.fp_zweckbestimmungstrassenverkehr'::regtype AND enumlabel = '1200')
		ELSE 1 END
	WHERE NOT EXISTS(SELECT 1 FROM pg_enum WHERE enumtypid = 'xplan_gml.fp_zweckbestimmungstrassenverkehr'::regtype AND enumlabel = '1300' );	

  --ALTER TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr ADD VALUE '1300' AFTER '1200';
COMMIT;
