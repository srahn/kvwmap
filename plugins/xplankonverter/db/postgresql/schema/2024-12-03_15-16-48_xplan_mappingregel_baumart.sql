BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET regel = '(gmlas.baumart_codespace,gmlas.baumart,NULL)::xplan_gml.bp_vegetationsobjekttypen AS baumart'
WHERE regel = '(gmlas.baumart_codespace,gmlas.baumart,NULL)::xplan_gml.vegetationsobjekttypen AS baumart';
COMMIT;