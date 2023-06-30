BEGIN;

  CREATE TABLE xplankonverter.errors (
    error_id serial NOT NULL PRIMARY KEY,
    name character varying,
    beschreibung text
  );

  INSERT INTO xplankonverter.errors (
    error_id, name, beschreibung
  )
  VALUES
    (1, 'Importfehler', 'Fehler beim Einlesen der XPlan-GML-Datei in die Postgre-Datenbank mit ogr2ogr_gmlas'),
    (2, 'GML-ID-Fehler', 'Die GML-ID der Zusammenzeichnung wird schon von einem anderen Plan verwendet'),
    (3, 'Plananlagefehler', 'Fehler beim Anlegen des Plan und Bereichsobjektes'),
    (4, 'Schemarenamefehler', 'Fehler beim Umbenennen des Import-Schemas xplan_gmlas_tmp_user_id'),
    (5, 'Geltungsbereicheeinlesefehler', 'Fehler beim Einlesen der Geltungsbereiche der Änderungspläne');

  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN IF NOT EXISTS error_id integer;

  ALTER TABLE IF EXISTS xplankonverter.konvertierungen
    ADD CONSTRAINT fk_konvertierungen_errors FOREIGN KEY (error_id)
    REFERENCES xplankonverter.errors (error_id) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;

COMMIT;
