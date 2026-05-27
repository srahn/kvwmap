BEGIN;
-- remove mapping fp_landwirtschaftsflaeche (marked as deprecated in xplan 5.4 and disappearing in xplan 6) onto fp_landwirtschaft
-- instead mapping will happen in extract_gml.php
UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET t_table = 'fp_landwirtschaft'
WHERE o_table = 'fp_landwirtschaftsflaeche';
COMMIT;