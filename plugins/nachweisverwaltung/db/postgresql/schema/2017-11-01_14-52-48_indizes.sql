BEGIN;

CREATE INDEX ix_nachweisid_n2d
  ON nachweisverwaltung.n_nachweise2dokumentarten
  USING btree
  (nachweis_id);
	
	CREATE INDEX ix_antragid_stelleid
  ON nachweisverwaltung.n_nachweise2antraege
  USING btree
  (antrag_id, stelle_id);

COMMIT;
