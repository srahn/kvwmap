BEGIN;

ALTER TABLE lenris.clients ADD COLUMN email character varying;

COMMIT;
