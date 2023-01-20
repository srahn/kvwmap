BEGIN;
UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmunggruen[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmunggruen[] AS zweckbestimmung'
AND tabelle IN ('bp_gruenflaeche', 'fp_gruen');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[dachform]::xplan_gml.bp_dachform[],''{NULL}'') AS dachform'
WHERE regel = 'ARRAY[dachform]::xplan_gml.bp_dachform[] AS dachform'
AND tabelle IN ('bp_ueberbaubaregrundstuecksflaeche', 'bp_besonderernutzungszweckflaeche','bp_baugebietsteilflaeche');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmunglandwirtschaft[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmunglandwirtschaft[] AS zweckbestimmung'
AND tabelle IN ('bp_landwirtschaftsflaeche', 'fp_landwirtschaftsflaeche','bp_landwirtschaft');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmungkennzeichnung[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmungkennzeichnung[] AS zweckbestimmung'
AND tabelle IN ('bp_kennzeichnungsflaeche', 'fp_kennzeichnung');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmunggemeinbedarf[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmunggemeinbedarf[] AS zweckbestimmung'
AND tabelle IN ('bp_gemeinbedarfsflaeche', 'fp_gemeinbedarf');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmungspielsportanlage[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmungspielsportanlage[] AS zweckbestimmung'
AND tabelle IN ('bp_spielsportanlagenflaeche', 'fp_spielsportanlage');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmungwald[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmungwald[] AS zweckbestimmung'
AND tabelle IN ('bp_waldflaeche', 'fp_waldflaeche');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmungverentsorgung[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.xp_zweckbestimmungverentsorgung[] AS zweckbestimmung'
AND tabelle IN ('bp_verentsorgung', 'fp_verentsorgung');

/* Error in data as it should have been "AS zweckbestimmung" already */
UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.bp_zweckbestimmunggenerischeobjekte[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.bp_zweckbestimmunggenerischeobjekte[]'
AND tabelle IN ('bp_generischesobjekt');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[typ]::xplan_gml.bp_wegerechttypen[],''{NULL}'') AS typ'
WHERE regel = 'ARRAY[typ]::xplan_gml.bp_wegerechttypen[] AS typ'
AND tabelle IN ('bp_wegerecht');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[gegenstand]::xplan_gml.xp_anpflanzungbindungerhaltungsgegenstand[],''{NULL}'') AS gegenstand'
WHERE regel = 'ARRAY[gegenstand]::xplan_gml.xp_anpflanzungbindungerhaltungsgegenstand[] AS gegenstand'
AND tabelle IN ('bp_anpflanzungbindungerhaltung');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.bp_zweckbestimmunggemeinschaftsanlagen[] AS zweckbestimmung'
AND tabelle IN ('bp_gemeinschaftsanlagenflaeche');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.fp_zweckbestimmungprivilegiertesvorhaben[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.fp_zweckbestimmungprivilegiertesvorhaben[] AS zweckbestimmung'
AND tabelle IN ('fp_privilegiertesvorhaben');

UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[zweckbesti]::xplan_gml.bp_zweckbestimmungnebenanlagen[],''{NULL}'') AS zweckbestimmung'
WHERE regel = 'ARRAY[zweckbesti]::xplan_gml.bp_zweckbestimmungnebenanlagen[] AS zweckbestimmung'
AND tabelle IN ('bp_nebenanlagenflaeche');

/* Updates all 92 classes where xp_hoehenangabe is relevant */
UPDATE xplankonverter.mappingtable_standard_shp_to_db
SET regel = 'NULLIF(ARRAY[hoehenanga]::xplan_gml.xp_hoehenangabe[],''{NULL}'') AS hoehenangabe'
WHERE regel = 'ARRAY[hoehenanga]::xplan_gml.xp_hoehenangabe[] AS hoehenangabe';

COMMIT;