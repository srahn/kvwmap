BEGIN;

ALTER TABLE nachweisverwaltung.n_antraege ADD COLUMN stelle_id integer;

ALTER TABLE nachweisverwaltung.fp_punkte2antraege ADD COLUMN stelle_id integer;

ALTER TABLE nachweisverwaltung.n_nachweise2antraege ADD COLUMN stelle_id integer;

ALTER TABLE nachweisverwaltung.fp_punkte2antraege DROP CONSTRAINT fp_punkte2antraege_pkey;

ALTER TABLE nachweisverwaltung.fp_punkte2antraege ADD CONSTRAINT fp_punkte2antraege_pkey UNIQUE(pkz, antrag_nr, stelle_id);

ALTER TABLE nachweisverwaltung.fp_punkte2antraege DROP CONSTRAINT p2a_a_fkey;
			
ALTER TABLE nachweisverwaltung.n_nachweise2antraege DROP CONSTRAINT n_nachweise2antraege_pkey;

ALTER TABLE nachweisverwaltung.n_nachweise2antraege ADD CONSTRAINT n_nachweise2antraege_pkey UNIQUE(nachweis_id, antrag_id, stelle_id);

ALTER TABLE nachweisverwaltung.n_nachweise2antraege DROP CONSTRAINT n2a_a_fkey;

ALTER TABLE nachweisverwaltung.n_antraege DROP CONSTRAINT n_antraege_pkey;

ALTER TABLE nachweisverwaltung.n_antraege ADD CONSTRAINT n_antraege_pkey UNIQUE(antr_nr, stelle_id);

ALTER TABLE nachweisverwaltung.fp_punkte2antraege
  ADD CONSTRAINT p2a_a_fkey FOREIGN KEY (antrag_nr, stelle_id)
      REFERENCES nachweisverwaltung.n_antraege (antr_nr, stelle_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
			
ALTER TABLE nachweisverwaltung.n_nachweise2antraege
  ADD CONSTRAINT n2a_a_fkey FOREIGN KEY (antrag_id, stelle_id)
      REFERENCES nachweisverwaltung.n_antraege (antr_nr, stelle_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;

COMMIT;
