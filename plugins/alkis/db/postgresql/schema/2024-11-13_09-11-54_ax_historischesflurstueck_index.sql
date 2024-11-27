BEGIN;

DROP INDEX alkis.idx_histfs_nach;

CREATE INDEX idx_histfs_nach
  ON alkis.ax_historischesflurstueck
  USING gin
  (nachfolgerflurstueckskennzeichen COLLATE pg_catalog."default");

COMMIT;
