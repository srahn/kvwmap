BEGIN;

ALTER TABLE bodenrichtwerte.bw_zonen ADD COLUMN qualitaetsstichtag date;
ALTER TABLE bodenrichtwerte.bw_zonen ADD COLUMN bodenrichtwert_qualitaetsstichtag real;
ALTER TABLE bodenrichtwerte.bw_zonen ADD COLUMN ogeschosszahl varchar(9);
ALTER TABLE bodenrichtwerte.bw_zonen RENAME geschossflaechenzahl TO wgeschossflaechenzahl;
ALTER TABLE bodenrichtwerte.bw_zonen ADD COLUMN geschossflaechenzahl varchar(11);


COMMIT;
