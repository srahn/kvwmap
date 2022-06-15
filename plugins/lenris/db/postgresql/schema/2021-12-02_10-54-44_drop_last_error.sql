BEGIN;

ALTER TABLE lenris.clients DROP COLUMN last_error;

COMMIT;
