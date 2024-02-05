BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET regel = 'CASE WHEN (gmlas.detailliertezweckbestimmung_codespace IS NOT NULL OR gmlas.detailliertezweckbestimmung IS NOT NULL)
THEN 
ARRAY[(gmlas.detailliertezweckbestimmung_codespace,gmlas.detailliertezweckbestimmung,NULL)::xplan_gml.fp_detailzweckbeststrassenverkehr]::xplan_gml.fp_detailzweckbeststrassenverkehr[]
ELSE NULL
END AS detailliertezweckbestimmung'
WHERE o_table = 'fp_strassenverkehr' AND o_data_type = 'ARRAY character varying' AND regel ILIKE '%detail%';
COMMIT;
