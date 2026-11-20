BEGIN;

ALTER TABLE kvwmap.u_consumealb ALTER COLUMN vorgang TYPE varchar(255) USING vorgang::varchar(255);

COMMIT;
