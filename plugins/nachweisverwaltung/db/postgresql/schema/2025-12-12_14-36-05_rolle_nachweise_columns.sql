BEGIN;

ALTER TABLE kvwmap.rolle_nachweise ADD COLUMN columns jsonb;

UPDATE kvwmap.rolle_nachweise SET columns = '["id", "gemarkung", "flur", "stammnr", "blattnummer", "rissnummer", "art", "datum", "fortfuehrung", "vermst", "gueltigkeit", "geprueft", "format"]';

COMMIT;
