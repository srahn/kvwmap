BEGIN;

CREATE TYPE konvertierungsstatus AS ENUM ('fertig', 'noch nicht ausgeführt', 'in Arbeit');

CREATE TABLE xplankonverter.konvertierungen
(
  id serial NOT NULL,
  regel_id integer,
  status konvertierungsstatus,
  stelle_id integer,
  CONSTRAINT konvertierungen_id_pkey PRIMARY KEY (id)
) WITH ( OIDS=TRUE );
COMMENT ON COLUMN xplankonverter.konvertierungen.regel_id IS 'Id der in der Konvertierung angewendeten Regel.';
COMMENT ON COLUMN xplankonverter.konvertierungen.status IS 'Status der Konvertierung. Enthält ein Wert vom Typ konvertierungsstatus.';
COMMENT ON COLUMN xplankonverter.konvertierungen.stelle_id IS 'Die Id der Stelle in der die Konvertierung angelegt wurde und genutzt wird.';

COMMIT;
