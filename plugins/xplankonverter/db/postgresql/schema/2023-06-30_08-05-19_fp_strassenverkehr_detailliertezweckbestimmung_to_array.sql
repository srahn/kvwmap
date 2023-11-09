BEGIN;

ALTER TABLE
	xplan_gml.fp_strassenverkehr
ALTER COLUMN
	detailliertezweckbestimmung
SET
	DATA TYPE xplan_gml.fp_detailzweckbeststrassenverkehr[]
USING
	NULLIF(ARRAY[detailliertezweckbestimmung]::xplan_gml.fp_detailzweckbeststrassenverkehr[], '{NULL}');

UPDATE
	xplankonverter.mappingtable_gmlas_to_gml
SET
	o_data_type = 'ARRAY character varying',
	t_data_type = 'fp_detailzweckbeststrassenverkehr[]',
	regel = 'CASE
	WHEN (SELECT TRUE FROM xplan_gmlas_7479.fp_strassenverkehr_detailliertezweckbestimmung norm_table_4_4 WHERE norm_table_4_4.parent_id = gmlas.id LIMIT 1)
	THEN ARRAY[((SELECT DISTINCT codespace FROM xplan_gmlas_7479.fp_strassenverkehr_detailliertezweckbestimmung norm_table_4_4_4 WHERE gmlas.id = norm_table_4_4_4.parent_id LIMIT 1),(SELECT string_agg(value,'','') FROM xplan_gmlas_7479.fp_strassenverkehr_detailliertezweckbestimmung norm_table_4_4_4_4 WHERE gmlas.id = norm_table_4_4_4_4.parent_id),NULL)]::xplan_gml.fp_detailzweckbeststrassenverkehr[]
	ELSE NULL
	END AS detailliertezweckbestimmung'
WHERE
	o_table = 'fp_strassenverkehr'
AND
	o_column = 'detailliertezweckbestimmung';

UPDATE
	xplan_uml.uml_attributes
SET
	multiplicity_range_upper = '*',
	ordering = 'ordered'
WHERE
	name = 'detaillierteZweckbestimmung'
AND
	uml_class_id = 228;

UPDATE xplankonverter.regeln
SET
	sql = REPLACE(
		sql, 
		'(gmlas.detailliertezweckbestimmung_codespace,gmlas.detailliertezweckbestimmung,NULL)::xplan_gml.fp_detailzweckbeststrassenverkehr AS detailliertezweckbestimmung',
		'CASE WHEN (SELECT TRUE FROM xplan_gmlas_7479.fp_strassenverkehr_detailliertezweckbestimmung norm_table_4_4 WHERE norm_table_4_4.parent_id = gmlas.id LIMIT 1) THEN ARRAY[((SELECT DISTINCT codespace FROM xplan_gmlas_7479.fp_strassenverkehr_detailliertezweckbestimmung norm_table_4_4_4 WHERE gmlas.id = norm_table_4_4_4.parent_id LIMIT 1),(SELECT string_agg(value,'','') FROM xplan_gmlas_7479.fp_strassenverkehr_detailliertezweckbestimmung norm_table_4_4_4_4 WHERE gmlas.id = norm_table_4_4_4_4.parent_id),NULL)]::xplan_gml.fp_detailzweckbeststrassenverkehr[] ELSE NULL END AS detailliertezweckbestimmung'
	)
WHERE sql LIKE '%(gmlas.detailliertezweckbestimmung_codespace,gmlas.detailliertezweckbestimmung,NULL)::xplan_gml.fp_detailzweckbeststrassenverkehr AS detailliertezweckbestimmung%'
AND class_name = 'FP_Strassenverkehr';


COMMIT;
