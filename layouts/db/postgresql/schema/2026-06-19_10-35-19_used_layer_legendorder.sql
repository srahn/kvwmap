BEGIN;

ALTER TABLE kvwmap.used_layer ADD legendorder int4 NULL;

COMMIT;
