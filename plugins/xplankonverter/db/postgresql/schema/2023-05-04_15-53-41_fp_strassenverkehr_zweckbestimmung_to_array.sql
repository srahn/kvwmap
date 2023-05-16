BEGIN;

ALTER TABLE
	xplan_gml.fp_strassenverkehr
ALTER COLUMN
	zweckbestimmung
SET
	DATA TYPE xplan_gml.fp_zweckbestimmungstrassenverkehr[]
USING
	NULLIF(ARRAY[zweckbestimmung]::xplan_gml.fp_zweckbestimmungstrassenverkehr[], '{NULL}');

UPDATE
	xplankonverter.mappingtable_gmlas_to_gml
SET
	o_data_type = 'ARRAY character varying',
	t_data_type = 'fp_zweckbestimmungstrassenverkehr[]',
	regel = 'CASE
	WHEN pg_typeof(gmlas.zweckbestimmung)::text = ''character varying'' THEN NULLIF(ARRAY[zweckbestimmung]::xplan_gml.fp_zweckbestimmungstrassenverkehr[], ''{NULL}'')
	ELSE NULLIF(gmlas.zweckbestimmung::xplan_gml.fp_zweckbestimmungstrassenverkehr[], ''{NULL}'')
	END AS zweckbestimmung'
WHERE
	o_table = 'fp_strassenverkehr'
AND
	o_column = 'zweckbestimmung';

UPDATE
	xplan_uml.uml_attributes
SET
	multiplicity_range_upper = '*',
	ordering = 'ordered'
WHERE
	name = 'zweckbestimmung'
AND
	uml_class_id = 228;

UPDATE xplankonverter.regeln
SET
	sql = REPLACE(
		sql, 
		'gmlas.zweckbestimmung::xplan_gml.fp_zweckbestimmungstrassenverkehr AS zweckbestimmung',
		'CASE WHEN pg_typeof(gmlas.zweckbestimmung)::text = ''character varying'' THEN NULLIF(ARRAY[zweckbestimmung]::xplan_gml.fp_zweckbestimmungstrassenverkehr[], ''{NULL}'') ELSE NULLIF(gmlas.zweckbestimmung::xplan_gml.fp_zweckbestimmungstrassenverkehr[], ''{NULL}'') END AS zweckbestimmung'
	)
WHERE sql LIKE '%gmlas.zweckbestimmung::xplan_gml.fp_zweckbestimmungstrassenverkehr AS zweckbestimmung%'
AND class_name = 'FP_Strassenverkehr';

COMMIT;