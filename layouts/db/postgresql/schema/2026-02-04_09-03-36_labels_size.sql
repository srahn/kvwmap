BEGIN;

ALTER TABLE kvwmap.labels ALTER COLUMN size TYPE varchar(50) USING size::varchar(50);

COMMIT;
