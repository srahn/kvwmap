BEGIN;

ALTER TABLE lenris.client_dokumentarten ADD COLUMN id serial;

COMMIT;
