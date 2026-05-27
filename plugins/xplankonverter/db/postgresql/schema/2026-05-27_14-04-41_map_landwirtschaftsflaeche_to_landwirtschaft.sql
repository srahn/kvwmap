BEGIN;
-- map fp_landwirtschaftsflaeche (marked as deprecated in xplan 5.4 and disappearing in xplan 6) onto fp_landwirtschaft
UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET t_table = 'fp_landwirtschaft'
WHERE t_table = 'fp_landwirtschaftsflaeche';
COMMIT;