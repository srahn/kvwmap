BEGIN;

DELETE FROM xplankonverter.mappingtable_gmlas_to_gml
WHERE id IN (4496,4503)
AND t_table = 'so_gewaesser'
AND t_column IN ('aufschrift','nummer');

COMMIT;
