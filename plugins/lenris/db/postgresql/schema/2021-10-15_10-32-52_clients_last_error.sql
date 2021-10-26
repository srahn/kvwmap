BEGIN;

ALTER TABLE lenris.clients ADD COLUMN last_error text;

COMMIT;
