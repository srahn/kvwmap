BEGIN;

ALTER TABLE kvwmap.layer RENAME status TO errorstatus;

ALTER TABLE kvwmap.layer ADD COLUMN status varchar(20);

COMMIT;
