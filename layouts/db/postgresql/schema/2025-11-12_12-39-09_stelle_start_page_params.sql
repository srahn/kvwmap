BEGIN;

ALTER TABLE kvwmap.stelle ADD COLUMN start_page_params text;

COMMIT;
