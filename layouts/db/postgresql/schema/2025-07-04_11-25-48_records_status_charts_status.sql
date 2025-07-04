BEGIN;

ALTER TABLE kvwmap.rollenlayer ADD COLUMN records_status smallint DEFAULT 2;
ALTER TABLE kvwmap.rollenlayer ADD COLUMN charts_status smallint DEFAULT 1;

COMMIT;
