BEGIN;
  ALTER TABLE xplankonverter.konvertierungen ADD column uploaded_xplan_gml_file_name character varying;
  COMMENT ON COLUMN xplankonverter.konvertierungen.uploaded_xplan_gml_file_name
    IS 'Dateiname der XPlanGML-Datei, die den Plan enthält und beim Anlegen der Konvertierung hochgeladen wurde. Ist die Angabe leer, wird plan_file_name aus der config der Konvertierung Klasse entnommen.';
COMMIT;