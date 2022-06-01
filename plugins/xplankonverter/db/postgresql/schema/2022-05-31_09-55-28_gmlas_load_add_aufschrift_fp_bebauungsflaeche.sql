BEGIN;
INSERT INTO xplankonverter.mappingtable_gmlas_to_gml(
	id,
	feature_class, o_table, o_column, o_data_type, t_table, t_column, t_data_type, codespace, complex_type, regel)
SELECT 
	(SELECT MAX(id) +1 FROM xplankonverter.mappingtable_gmlas_to_gml),
	true,
	'fp_bebauungsflaeche',
	'aufschrift',
	'character varying',
	'fp_bebauungsflaeche',
	'aufschrift',
	'character varying',
	NULL,
	NULL,
	'gmlas.aufschrift AS aufschrift'
WHERE
NOT EXISTS (
	SELECT 1 FROM xplankonverter.mappingtable_gmlas_to_gml WHERE o_table = 'fp_bebauungsflaeche' AND o_column = 'aufschrift'
);
COMMIT;