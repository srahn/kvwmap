BEGIN;

ALTER TABLE kvwmap.u_menue2stelle ALTER COLUMN menue_order DROP NOT NULL;
ALTER TABLE kvwmap.u_menue2stelle ALTER COLUMN menue_order DROP DEFAULT;

UPDATE
  kvwmap.u_menue2stelle 
SET 
  menue_order = NULL;

COMMIT;
