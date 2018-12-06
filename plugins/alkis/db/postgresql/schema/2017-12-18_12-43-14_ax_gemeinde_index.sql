BEGIN;

CREATE INDEX ax_gemeinde_kreis_gemeinde
  ON alkis.ax_gemeinde
  USING btree
  (kreis, gemeinde);

COMMIT;
