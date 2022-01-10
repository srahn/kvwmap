BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET o_table = 'fp_landwirtschaft_hoehenangabe_hoehenangabe'
WHERE id = 4335
AND o_table = 'fp_landwirtschaft';

UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET o_table = 'fp_landwirtschaft_externereferenz_externereferenz'
WHERE id = 4336
AND o_table = 'fp_landwirtschaft';

UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET o_table = 'fp_landwirtschaft_refbegruendunginhalt'
WHERE id = 4340
AND o_table = 'fp_landwirtschaft';

UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET o_table = 'fp_landwirtschaft_reftextinhalt'
WHERE id = 4341
AND o_table = 'fp_landwirtschaft';

UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET o_table = 'fp_landwirtschaft_wirdausgeglichendurchflaeche'
WHERE id = 4345
AND o_table = 'fp_landwirtschaft';

UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET o_table = 'fp_landwirtschaft_wirdausgeglichendurchspe'
WHERE id = 4346
AND o_table = 'fp_landwirtschaft';

UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET o_table = 'fp_landwirtschaft_wirddargestelltdurch'
WHERE id = 4347
AND o_table = 'fp_landwirtschaft';
COMMIT;
