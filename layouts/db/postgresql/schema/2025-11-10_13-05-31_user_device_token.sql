BEGIN;

ALTER TABLE kvwmap.user 
ADD COLUMN device_token CHAR(64),
ADD COLUMN device_expires timestamp;

COMMIT;
