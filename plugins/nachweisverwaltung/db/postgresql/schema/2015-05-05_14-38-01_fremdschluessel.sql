BEGIN;


ALTER TABLE nachweisverwaltung.fp_punkte2antraege
  ADD CONSTRAINT p2a_a_fkey FOREIGN KEY (antrag_nr)
      REFERENCES nachweisverwaltung.n_antraege (antr_nr) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;


ALTER TABLE nachweisverwaltung.n_nachweise2antraege
  ADD CONSTRAINT n2a_a_fkey FOREIGN KEY (antrag_id)
      REFERENCES nachweisverwaltung.n_antraege (antr_nr) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;


COMMIT;
