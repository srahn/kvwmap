BEGIN;

ALTER TABLE anliegerbeitraege.anliegerbeitraege_bereiche ADD COLUMN stelle_id integer;
ALTER TABLE anliegerbeitraege.anliegerbeitraege_strassen ADD COLUMN stelle_id integer;

COMMIT;
