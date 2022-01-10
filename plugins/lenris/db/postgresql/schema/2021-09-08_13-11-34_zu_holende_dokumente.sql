BEGIN;

ALTER TABLE lenris.zu_holende_dokumente RENAME dokument TO source_path;

ALTER TABLE lenris.zu_holende_dokumente ADD COLUMN dest_path character varying;

COMMIT;
