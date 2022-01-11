BEGIN;
UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET regel = '(gmlas.detaillierteartderbaulnutzung_codespace,gmlas.detaillierteartderbaulnutzung,NULL)::xplan_gml.fp_detailartderbaulnutzung AS detaillierteartderbaulnutzung'
WHERE regel = '(gmlas.detaillierteartderbaulnutzung_codespace,gmlas.detaillierteartderbaulnutzung,NULL,NULL)::xplan_gml.fp_detailartderbaulnutzung AS detaillierteartderbaulnutzung';

UPDATE xplankonverter.mappingtable_gmlas_to_gml
SET regel = '(gmlas.detailliertezweckbestimmung_codespace,gmlas.detailliertezweckbestimmung,NULL)::xplan_gml.fp_detailzweckbeststrassenverkehr AS detailliertezweckbestimmung'
WHERE regel = '(gmlas.detailliertezweckbestimmung_codespace,gmlas.detailliertezweckbestimmung,NULL,NULL)::xplan_gml.fp_detailzweckbeststrassenverkehr AS detailliertezweckbestimmung';
COMMIT;