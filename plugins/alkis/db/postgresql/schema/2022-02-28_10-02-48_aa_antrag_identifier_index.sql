BEGIN;

CREATE INDEX aa_antrag_identifier
  ON alkis.aa_antrag
  USING btree
  (identifier);

COMMIT;
