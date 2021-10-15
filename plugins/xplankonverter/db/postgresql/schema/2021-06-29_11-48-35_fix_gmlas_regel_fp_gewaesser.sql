BEGIN;

UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET regel = '(gmlas.detailliertezweckbestimmung_codespace,gmlas.detailliertezweckbestimmung,NULL)::xplan_gml.fp_detailzweckbestgewaesser AS detailliertezweckbestimmung'
WHERE regel = '(gmlas.detailliertezweckbestimmung_codespace,gmlas.detailliertezweckbestimmung,NULL,NULL)::xplan_gml.fp_detailzweckbestgewaesser AS detailliertezweckbestimmung';

COMMIT;
