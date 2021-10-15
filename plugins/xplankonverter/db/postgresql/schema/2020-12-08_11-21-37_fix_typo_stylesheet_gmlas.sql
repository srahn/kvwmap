BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET t_data_type = 'xp_stylesheetliste'
WHERE t_data_type = 'xp_stylsheetliste';

UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET regel = '(gmlas.stylesheetid_codespace,gmlas.stylesheetid,NULL)::xplan_gml.xp_stylesheetliste AS stylesheetid'
WHERE regel = '(gmlas.stylesheetid_codespace,gmlas.stylesheetid,NULL)::xplan_gml.xp_stylsheetliste AS stylesheetid';
COMMIT;