BEGIN;

DELETE FROM nachweisverwaltung.fp_punkte2antraege WHERE antrag_nr IN (SELECT DISTINCT a.antrag_nr FROM nachweisverwaltung.fp_punkte2antraege as a LEFT JOIN nachweisverwaltung.n_antraege as b ON (a.antrag_nr=b.antr_nr) WHERE b.antr_nr IS NULL);
DELETE FROM nachweisverwaltung.n_nachweise2antraege WHERE antrag_id IN (SELECT DISTINCT a.antrag_id FROM nachweisverwaltung.n_nachweise2antraege as a LEFT JOIN nachweisverwaltung.n_antraege as b ON (a.antrag_id=b.antr_nr) WHERE b.antr_nr IS NULL);

ALTER TABLE nachweisverwaltung.fp_punkte2antraege
  ADD CONSTRAINT p2a_a_fkey FOREIGN KEY (antrag_nr)
      REFERENCES nachweisverwaltung.n_antraege (antr_nr) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE nachweisverwaltung.n_nachweise2antraege
  ADD CONSTRAINT n2a_a_fkey FOREIGN KEY (antrag_id)
      REFERENCES nachweisverwaltung.n_antraege (antr_nr) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;


COMMIT;
