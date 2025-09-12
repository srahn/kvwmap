BEGIN;

ALTER TABLE lenris.client_vermessungsstellen ADD COLUMN id serial;

COMMIT;
