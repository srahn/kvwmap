BEGIN;

  -- Alter column type krs_schl
  ALTER TABLE IF EXISTS gebietseinheiten.gemeindeverbaende
    DROP CONSTRAINT kreis_fkey;

  ALTER TABLE gebietseinheiten.kreise ALTER COLUMN krs_schl TYPE character(5) using LEFT(krs_schl, 5);
  ALTER TABLE gebietseinheiten.gemeindeverbaende ALTER COLUMN krs_schl TYPE character(5) using LEFT(krs_schl, 5);

  ALTER TABLE IF EXISTS gebietseinheiten.gemeindeverbaende
    ADD CONSTRAINT kreis_fkey FOREIGN KEY (krs_schl)
    REFERENCES gebietseinheiten.kreise (krs_schl) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;

  -- Alter column type gvb_schl
  ALTER TABLE IF EXISTS gebietseinheiten.gemeinden
    DROP CONSTRAINT gemeindeverbaende_fkey;

  ALTER TABLE gebietseinheiten.gemeindeverbaende ALTER COLUMN gvb_schl TYPE character(9) using gvb_schl::character(9);
  ALTER TABLE gebietseinheiten.gemeindeverbaende ALTER COLUMN gvb_laiv_schl TYPE character varying using gvb_laiv_schl::character varying;
  ALTER TABLE gebietseinheiten.gemeinden ALTER COLUMN gvb_schl TYPE character(9) using gvb_schl::character(9);

  ALTER TABLE IF EXISTS gebietseinheiten.gemeinden
    ADD CONSTRAINT gemeindeverbaende_fkey FOREIGN KEY (gvb_schl)
    REFERENCES gebietseinheiten.gemeindeverbaende (gvb_schl) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;


  -- Alter column type gem_schl
  ALTER TABLE IF EXISTS gebietseinheiten.gemeindeteile DROP CONSTRAINT IF EXISTS gemeinden_fkey;

  ALTER TABLE gebietseinheiten.gemeinden ALTER COLUMN gem_schl TYPE character(12) using gem_schl::character(12);
  ALTER TABLE gebietseinheiten.gemeindeteile ALTER COLUMN gem_schl TYPE character(12) using gem_schl::character(12);

  ALTER TABLE IF EXISTS gebietseinheiten.gemeindeteile
    ADD CONSTRAINT gemeinden_fkey FOREIGN KEY (gem_schl)
    REFERENCES gebietseinheiten.gemeinden (gem_schl) MATCH SIMPLE
    ON UPDATE CASCADE
    ON DELETE CASCADE;

  -- Alter column type gtl_schl
  ALTER TABLE gebietseinheiten.gemeindeteile ALTER COLUMN gtl_schl TYPE character(16) using gtl_schl::character(16);

  ALTER TABLE gebietseinheiten.kreise ADD COLUMN IF NOT EXISTS geom_25832 geometry('MultiPolygon', 25832);
  ALTER TABLE gebietseinheiten.gemeindeverbaende ADD COLUMN IF NOT EXISTS geom_25832 geometry('MultiPolygon', 25832);
  ALTER TABLE gebietseinheiten.gemeindeverbaende ADD COLUMN IF NOT EXISTS gvb_art character(1);
  ALTER TABLE gebietseinheiten.gemeinden ADD COLUMN IF NOT EXISTS geom_25832 geometry('MultiPolygon', 25832);
  ALTER TABLE gebietseinheiten.gemeinden ADD COLUMN IF NOT EXISTS gem_art character(1);

COMMIT;
