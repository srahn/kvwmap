BEGIN;

ALTER TABLE bodenrichtwerte.bw_zonen ADD COLUMN gid serial;

COMMIT;
