BEGIN;

ALTER TABLE lenris.clients ALTER COLUMN last_sync TYPE timestamp without time zone;

COMMIT;
