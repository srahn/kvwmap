BEGIN;

ALTER TABLE lenris.clients ALTER COLUMN doc_download TYPE integer USING doc_download::integer;

COMMIT;
