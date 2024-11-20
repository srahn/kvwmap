BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml 
SET regel = 'ROW(gmlas.wert)::xplan_gml.measure AS wert'
WHERE regel = 'gmlas.wert AS wert'
AND o_table = 'bp_abstandsmass'
AND o_column = 'wert';
COMMIT;