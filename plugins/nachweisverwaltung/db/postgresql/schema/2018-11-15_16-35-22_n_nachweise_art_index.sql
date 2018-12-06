BEGIN;

CREATE INDEX n_nachweise_art
  ON nachweisverwaltung.n_nachweise
  USING btree
  (art);

COMMIT;
