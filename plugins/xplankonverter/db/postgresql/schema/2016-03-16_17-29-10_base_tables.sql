BEGIN;

CREATE TYPE konvertierungsstatus AS ENUM ('erstellt','Angaben vollständig','validiert','in Arbeit','fertig');

CREATE TABLE xplankonverter.konvertierungen
(
  id serial NOT NULL,
  regel_id integer,
  bezeichnung character varying,
  status konvertierungsstatus DEFAULT 'erstellt',
  stelle_id integer,
  CONSTRAINT konvertierungen_id_pkey PRIMARY KEY (id)
) WITH ( OIDS=TRUE );
COMMENT ON COLUMN xplankonverter.konvertierungen.regel_id IS 'Id der in der Konvertierung angewendeten Regel.';
COMMENT ON COLUMN xplankonverter.konvertierungen.bezeichnung IS 'Bezeichnung der Konvertierung. (Freitext)';
COMMENT ON COLUMN xplankonverter.konvertierungen.status IS 'Status der Konvertierung. Enthält ein Wert vom Typ konvertierungsstatus.';
COMMENT ON COLUMN xplankonverter.konvertierungen.stelle_id IS 'Die Id der Stelle in der die Konvertierung angelegt wurde und genutzt wird.';

COMMIT;
