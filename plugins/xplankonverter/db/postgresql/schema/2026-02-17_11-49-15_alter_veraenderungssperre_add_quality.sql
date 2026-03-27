BEGIN;
  UPDATE xplan_gml.enum_xp_verlaengerungveraenderungssperre SET abkuerzung = 'Keine Verlängerung' WHERE wert = 1000;
  UPDATE xplan_gml.enum_xp_verlaengerungveraenderungssperre SET abkuerzung = 'Erste Verlängerung' WHERE wert = 2000;
  UPDATE xplan_gml.enum_xp_verlaengerungveraenderungssperre SET abkuerzung = 'Zweite Verlängerung' WHERE wert = 3000;
  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN IF NOT EXISTS qualitaet_document character varying(100);
COMMIT;