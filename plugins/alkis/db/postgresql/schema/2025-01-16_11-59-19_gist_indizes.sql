BEGIN;

CREATE INDEX IF NOT EXISTS pp_amt_gidx
  ON alkis.pp_amt
  USING gist
  (the_geom);

CREATE INDEX IF NOT EXISTS pp_amt_sgidx
  ON alkis.pp_amt
  USING gist
  (simple_geom);

CREATE INDEX IF NOT EXISTS pp_flur_gidx
  ON alkis.pp_flur
  USING gist
  (the_geom);

CREATE INDEX IF NOT EXISTS pp_flur_sgidx
  ON alkis.pp_flur
  USING gist
  (simple_geom);

CREATE INDEX IF NOT EXISTS pp_gemarkung_gidx
  ON alkis.pp_gemarkung
  USING gist
  (the_geom);

CREATE INDEX IF NOT EXISTS pp_gemarkung_sgidx
  ON alkis.pp_gemarkung
  USING gist
  (simple_geom);

CREATE INDEX IF NOT EXISTS pp_gemeinde_gidx
  ON alkis.pp_gemeinde
  USING gist
  (the_geom);

CREATE INDEX IF NOT EXISTS pp_gemeinde_sgidx
  ON alkis.pp_gemeinde
  USING gist
  (simple_geom);  

CREATE INDEX IF NOT EXISTS pp_kreis_gidx
  ON alkis.pp_kreis
  USING gist
  (the_geom);

CREATE INDEX IF NOT EXISTS pp_kreis_sgidx
  ON alkis.pp_kreis
  USING gist
  (simple_geom);  

COMMIT;
