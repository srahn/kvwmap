BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET regel = 'gmlas.grund::xplan_gml.bp_erhaltungsgrund AS grund'
WHERE regel = 'gmlas.grund::xplan_gml.bp_erhaltunggrund AS grund';
COMMIT;