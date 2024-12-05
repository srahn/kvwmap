BEGIN;

  ALTER TABLE metadata.ressources ADD COLUMN von_eneka boolean;
  ALTER TABLE metadata.ressources RENAME COLUMN last_update TO last_updated_at;
  ALTER TABLE metadata.ressources ADD COLUMN documents character varying[];
  ALTER TABLE metadata.ressources ADD COLUMN import_layer character varying;
  ALTER TABLE metadata.ressources ADD COLUMN import_schema character varying;
  ALTER TABLE metadata.ressources ADD COLUMN import_table character varying;
  ALTER TABLE metadata.ressources ADD COLUMN layer_id integer;
  ALTER TABLE metadata.ressources ADD COLUMN update_time time without time zone;
  ALTER TABLE metadata.ressources ADD COLUMN import_filter text;

  CREATE TABLE metadata.pack_status (
    id serial Primary Key,
    status character varying,
    beschreibung text,
    reihenfolge integer
  );

  INSERT INTO metadata.pack_status VALUES
  (1, '', 'Das Paket wurde noch nie erstellt oder ist nachdem es gelöscht wurde noch nicht wieder erstellt worden und es wurde noch keine Erstellung beauftragt.', 1),
  (2, 'beauftragt', 'Die Erstellung des Paketes wurde beauftragt, sie hat aber noch nicht begonnen. Der Cron-Job hat den Auftrag noch nicht gefunden, bzw. war noch nicht dran. Die Paketerstellung beginnt nur wenn die Ressourcen die für das Paket verwendet werden nicht gerade aktualisiert werden. ', 2),
  (3, 'in Arbeit', 'Die Paketerstellung hat begonnen. In diesem Status findet keine Aktualisierung von Ressourcen statt auch nicht der die von diesem Paket als Quelle genutzt werden.', 3),
  (4, 'fertig', 'Das Paket ist fertiggestellt und steht zum Download bereit. Der Status wechselt automatisch nach Ablauf der definierten Zeit für das Löschen der Pakete auf "Paket noch nicht erstellt."', 4),
  (-1, 'Fehler', 'Beim Packen ist ein Fehler aufgetreten. Dieser wurde geloggt und wird durch den Support behoben.', -1);

  CREATE TABLE IF NOT EXISTS metadata.data_packages (
    id serial Primary Key,
    stelle_id integer,
    ressource_id integer,
    pack_status_id integer,
    created_at timestamp without time zone,
    created_from character varying
  );

  ALTER TABLE metadata.data_packages ADD CONSTRAINT pack_status_fk FOREIGN KEY (pack_status_id)
  REFERENCES metadata.pack_status (id) MATCH SIMPLE
  ON UPDATE NO ACTION
  ON DELETE NO ACTION;

  INSERT INFO metadata.unpack_methods (name, beschreibung, reihenfolge) VALUES
  ('manual_copy', 'Manuell kopieren in Zielverzeichnis', 5);

  CREATE TABLE IF NOT EXISTS metadata.pack_logs(
    id serial PRIMARY KEY,
    packed_at timestamp without time zone NOT NULL DEFAULT now(),
    msg text,
    package_id integer NOT NULL,
    ressource_id integer NOT NULL
  );

COMMIT;