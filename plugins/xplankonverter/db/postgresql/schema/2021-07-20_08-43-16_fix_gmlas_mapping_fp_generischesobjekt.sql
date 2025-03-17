BEGIN;
DELETE FROM
	xplankonverter.mappingtable_gmlas_to_gml
WHERE
	o_table = 'fp_generischesobjekt' AND t_table = 'fp_generischesobjekt' AND o_column = 'zweckbestimmung' AND t_column = 'zweckbestimmung';

DELETE FROM
	xplankonverter.mappingtable_gmlas_to_gml
WHERE
	o_table = 'bp_generischesobjekt' AND t_table = 'bp_generischesobjekt' AND o_column = 'zweckbestimmung' AND t_column = 'zweckbestimmung';

DELETE FROM
	xplankonverter.mappingtable_gmlas_to_gml
WHERE
	o_table = 'lp_generischesobjekt' AND t_table = 'lp_generischesobjekt' AND o_column = 'zweckbestimmung' AND t_column = 'zweckbestimmung';


DELETE FROM xplankonverter.mappingtable_gmlas_to_gml
WHERE o_table = 'fp_verentsorgung_detailliertezweckbestimmung'
AND o_column = 'value'
AND t_data_type = 'fp_detailzweckbestverentsorgung';

COMMIT;
