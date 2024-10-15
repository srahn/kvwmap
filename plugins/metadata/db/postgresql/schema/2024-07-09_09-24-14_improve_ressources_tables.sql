BEGIN;
  ALTER TABLE metadata.ressources RENAME COLUMN datenquelle TO bezeichnung;
  ALTER TABLE metadata.ressources ADD COLUMN download_path CHARACTER VARYING;
  ALTER TABLE metadata.ressources ADD COLUMN last_update DATE;
  ALTER TABLE metadata.ressources ADD COLUMN auto_update BOOLEAN;
  ALTER TABLE metadata.ressources ADD COLUMN update_interval INTERVAL;
  ALTER TABLE metadata.ressources ADD COLUMN import_epsg character varying;
  ALTER TABLE metadata.ressources ADD COLUMN error_msg text;
  ALTER TABLE metadata.ressources ADD COLUMN relevanz boolean;
  ALTER TABLE metadata.ressources ADD COLUMN digital boolean;
  ALTER TABLE metadata.ressources ADD COLUMN flaechendeckend boolean;
  ALTER TABLE metadata.ressources ADD COLUMN bemerkung_prioritaet text;
  ALTER TABLE metadata.ressources ADD COLUMN inquiries_required boolean;
  ALTER TABLE metadata.ressources ADD COLUMN inquiries text;
  ALTER TABLE metadata.ressources ADD COLUMN inquiries_responses text;
  ALTER TABLE metadata.ressources ADD COLUMN inquiries_responsible character varying;
  ALTER TABLE metadata.ressources ADD COLUMN inquiries_to character varying;
  ALTER TABLE metadata.ressources ADD COLUMN check_required boolean;
  ALTER TABLE metadata.ressources ADD COLUMN created_at timestamp without time zone;
  ALTER TABLE metadata.ressources ADD COLUMN created_from character varying;
  ALTER TABLE metadata.ressources ADD COLUMN updated_at timestamp without time zone; 
  ALTER TABLE metadata.ressources ADD COLUMN updated_from character varying;
  ALTER TABLE metadata.ressources ADD COLUMN use_for_datapackage boolean;
  ALTER TABLE metadata.ressources ADD COLUMN transform_command text;
  ALTER TABLE metadata.ressources RENAME COLUMN download_script TO download_method;
  ALTER TABLE metadata.ressources ADD COLUMN unpack_method character varying;
  ALTER TABLE metadata.ressources ADD COLUMN import_method character varying;
  ALTER TABLE metadata.ressources ADD COLUMN transform_method character varying;
  ALTER TABLE metadata.ressources ADD COLUMN status_id integer;

  UPDATE public.spatial_ref_sys_alias SET alias = concat_ws(':', 'EPSG', srid::text) WHERE alias IS NULL;

  ALTER TABLE metadata.gruppen ADD COLUMN beschreibung TEXT;

  --
  -- download methods
  --
  CREATE TABLE metadata.download_methods (
    name character varying NOT NULL PRIMARY KEY,
    beschreibung TEXT,
    reihenfolge INTEGER
  );
  INSERT INTO metadata.download_methods (name, beschreibung, reihenfolge) VALUES
  ('urls', 'Download Files von URLs', 1),
  ('wfs', 'Download GML von WFS', 2),
  ('atom', 'Download Files von Atom-Feeds', 2);

  --
  -- unpack methods
  --
  CREATE TABLE metadata.unpack_methods (
    name character varying NOT NULL PRIMARY KEY,
    beschreibung TEXT,
    reihenfolge INTEGER
  );
  INSERT INTO metadata.unpack_methods (name, beschreibung, reihenfolge) VALUES
  ('unzip', 'Unzip in Zielverzeichnis', 1),
  ('unzip_unzip', 'Unzip und Unzip im Zielverzeichnis', 2),
  ('unzip_filter_stelle_extent', 'Unzip und Filter Stellenausdehnung', 3),
  ('copy', 'Copy in Zielverzeichnis', 4);

  --
  -- import methods
  --
  CREATE TABLE metadata.import_methods (
    name character varying NOT NULL PRIMARY KEY,
    beschreibung TEXT,
    reihenfolge INTEGER
  );
  INSERT INTO metadata.import_methods (name, beschreibung, reihenfolge) VALUES
  ('ogr2ogr_shape', 'Import shape mit ogr2ogr in Postgres', 1),
  ('ogr2ogr_gml', 'Import GML mit ogr2ogr in Postgres', 2),
  ('gml_dictionary', 'Import GML-Dictionary in Postgres', 3),
  ('csv_by_import', 'CSV-Import mit Header in Postgres', 4),
  ('raster2pgsql', 'Import mit raster2pgsql in Postgres', 5);

  --
  -- Transform methods
  --
  CREATE TABLE metadata.transform_methods (
    name character varying NOT NULL PRIMARY KEY,
    beschreibung TEXT,
    reihenfolge INTEGER
  );
  INSERT INTO metadata.transform_methods (name, beschreibung, reihenfolge) VALUES
  ('replace_from_import', 'Vorhandenes komplett überschreiben vom Import.', 1),
  ('waermebedarf', 'Berechnung des Wärmebedarfes von Gebäuden.', 2),
  ('exec_sql', 'SQL aus Kommandofeld ausführen.', 3);

  --
  -- Update status
  --
  CREATE TABLE metadata.update_status (
    id serial NOT NULL Primary Key,
    status character varying NOT NULL,
    beschreibung TEXT,
    reihenfolge INTEGER
  );
  INSERT INTO metadata.update_status (id, status, reihenfolge) VALUES
  (-1, 'Abbruch wegem Fehler', 11),
  ( 0, 'Uptodate', 1),
  ( 1, 'Update gestartet', 2),
  ( 2, 'Download gestartet', 3),
  ( 3, 'Download fertig', 4),
  ( 4, 'Auspacken gestartet', 5),
  ( 5, 'Auspacken fertig', 6),
  ( 6, 'Import gestartet', 7),
  ( 7, 'Import fertig', 8),
  ( 8, 'Transformation gestartet', 9),
  ( 9, 'Transformation fertig', 10);

  CREATE TABLE metadata.lineages (
    id serial NOT NULL Primary Key,
    source_id integer,
    target_id integer
  );

  ALTER TABLE metadata.lineages ADD CONSTRAINT source_fk FOREIGN KEY (source_id)
    REFERENCES metadata.ressources (id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION;

  ALTER TABLE metadata.lineages ADD CONSTRAINT target_fk FOREIGN KEY (target_id)
    REFERENCES metadata.ressources (id) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION;

  CREATE TABLE metadata.attributes (
    id serial NOT NULL Primary Key,
    ressource_id integer NOT NULL,
    name character varying,
    bezeichnung character varying NOT NULL,
    beschreibung text,
    datentyp character varying,
    valuelist character varying[],
    minvalue character varying,
    maxvalue character varying,
    defaultvalue character varying,
    mandatory boolean
  );

  ALTER TABLE metadata.ressources ADD CONSTRAINT download_method_fk FOREIGN KEY (download_method)
    REFERENCES metadata.download_methods (name) MATCH SIMPLE
    ON UPDATE SET NULL
    ON DELETE SET NULL;

  ALTER TABLE metadata.ressources ADD CONSTRAINT import_method_fk FOREIGN KEY (import_method)
    REFERENCES metadata.import_methods (name) MATCH SIMPLE
    ON UPDATE SET NULL
    ON DELETE SET NULL;

  ALTER TABLE metadata.ressources ADD CONSTRAINT unpack_methods_fk FOREIGN KEY (unpack_method)
    REFERENCES metadata.unpack_methods (name) MATCH SIMPLE
    ON UPDATE SET NULL
    ON DELETE SET NULL;

  ALTER TABLE metadata.ressources ADD CONSTRAINT transform_method_fk FOREIGN KEY (transform_method)
    REFERENCES metadata.transform_methods (name) MATCH SIMPLE
    ON UPDATE SET NULL
    ON DELETE SET NULL;

  ALTER TABLE metadata.ressources ADD CONSTRAINT status_fk FOREIGN KEY (status_id)
    REFERENCES metadata.update_status (id) MATCH SIMPLE
    ON UPDATE SET NULL
    ON DELETE SET NULL;

  ALTER TABLE metadata.attributes ADD CONSTRAINT ressource_id_fk FOREIGN KEY (ressource_id)
    REFERENCES metadata.ressources (id) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;

  ALTER TABLE IF EXISTS metadata.attributes ADD COLUMN created_at timestamp without time zone NOT NULL DEFAULT NOW();
  ALTER TABLE IF EXISTS metadata.attributes ADD COLUMN created_from character varying;
  ALTER TABLE IF EXISTS metadata.attributes ADD COLUMN updated_at timestamp without time zone; 
  ALTER TABLE IF EXISTS metadata.attributes ADD COLUMN updated_from character varying;


CREATE TABLE IF NOT EXISTS metadata.update_log (
    id serial NOT NULL PRIMARY KEY,
    ressource_id integer NOT NULL,
    update_at timestamp without time zone NOT NULL DEFAULT now(),
    msg text,
    abbruch_status_id integer
);

ALTER TABLE metadata.update_log ADD CONSTRAINT abbruch_status_id_fk FOREIGN KEY (abbruch_status_id)
  REFERENCES metadata.update_status (id) MATCH SIMPLE
  ON UPDATE CASCADE
  ON DELETE NO ACTION;

ALTER TABLE metadata.update_log ADD CONSTRAINT ressource_id_fk FOREIGN KEY (ressource_id)
  REFERENCES metadata.ressources (id) MATCH SIMPLE
  ON UPDATE CASCADE
  ON DELETE CASCADE;
COMMIT;