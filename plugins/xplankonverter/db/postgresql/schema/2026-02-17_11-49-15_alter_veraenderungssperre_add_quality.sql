BEGIN;
  UPDATE xplan_gml.enum_xp_verlaengerungveraenderungssperre SET abkuerzung = 'Keine Verlängerung' WHERE wert = 1000;
  ALTER TABLE xplankonverter.konvertierung ADD COLUMN IF NOT EXISTS qualitaet_document character varying(100);
COMMIT;