BEGIN;
TRUNCATE TABLE xplan_gml.enum_xp_bedeutungenbereich;

INSERT INTO xplan_gml.enum_xp_bedeutungenbereich (wert, beschreibung)
  VALUES (1600, 'Teilbereich'),
  (1800, 'Kompensationsbereich'),
  (9999, 'Sonstiges');

COMMIT;
