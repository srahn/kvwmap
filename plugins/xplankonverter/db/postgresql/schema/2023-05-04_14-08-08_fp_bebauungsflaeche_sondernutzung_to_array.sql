BEGIN;

ALTER TABLE
	xplan_gml.fp_bebauungsflaeche
ALTER COLUMN
	sondernutzung
SET
	DATA TYPE xplan_gml.xp_sondernutzungen[]
USING
	NULLIF(ARRAY[sondernutzung]::xplan_gml.xp_sondernutzungen[], '{NULL}');

UPDATE
	xplankonverter.mappingtable_gmlas_to_gml
SET
	o_data_type = 'ARRAY character varying',
	t_data_type = 'xp_sondernutzungen[]',
	regel = 'CASE
	WHEN pg_typeof(gmlas.sondernutzung)::text = ''character varying'' THEN NULLIF(ARRAY[sondernutzung]::xplan_gml.xp_sondernutzungen[], ''{NULL}'')
	ELSE NULLIF(gmlas.sondernutzung::xplan_gml.xp_sondernutzungen[], ''{NULL}'')
	END AS sondernutzung'
WHERE
	o_table = 'fp_bebauungsflaeche'
AND
	o_column = 'sondernutzung';


UPDATE
	xplan_uml.uml_attributes
SET
	multiplicity_range_upper = '*',
	ordering = 'ordered'
WHERE
	name = 'sonderNutzung'
AND
	uml_class_id = 198;

UPDATE xplankonverter.regeln
SET
	sql = REPLACE(
		sql, 
		'gmlas.sondernutzung::xplan_gml.xp_sondernutzungen AS sondernutzung',
		'CASE WHEN pg_typeof(gmlas.sondernutzung)::text = ''character varying'' THEN NULLIF(ARRAY[sondernutzung]::xplan_gml.xp_sondernutzungen[], ''{NULL}'') ELSE NULLIF(gmlas.sondernutzung::xplan_gml.xp_sondernutzungen[], ''{NULL}'') END AS sondernutzung'
	)
WHERE sql LIKE '%gmlas.sondernutzung::xplan_gml.xp_sondernutzungen AS sondernutzung%'
AND class_name = 'FP_BebauungsFlaeche';
COMMIT;