BEGIN;

  CREATE TABLE IF NOT EXISTS xplankonverter.konvertierungen_log (
    id bigserial NOT NULL PRIMARY KEY, 
    konvertierung_id integer NOT NULL,
    zeitstempel timestamp without time zone NOT NULL DEFAULT now(),
    message text COLLATE pg_catalog."default",
    originator character varying COLLATE pg_catalog."default"
  );
  COMMENT ON TABLE xplankonverter.konvertierungen_log IS 'Log Einträge von Prozessen der Konvertierung.';

COMMIT;