BEGIN;

ALTER TABLE anliegerbeitraege.anliegerbeitraege_bereiche ADD COLUMN flurstueckskennzeichen varchar;

COMMIT;
