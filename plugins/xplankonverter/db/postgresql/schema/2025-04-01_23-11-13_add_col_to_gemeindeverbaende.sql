BEGIN;

  ALTER TABLE gebietseinheiten.gemeindeverbaende ADD COLUMN IF NOT EXISTS letzte_aktualisierung date;
  ALTER TABLE gebietseinheiten.gemeindeverbaende ADD COLUMN IF NOT EXISTS konvertierung_manuell_id integer;
  ALTER TABLE gebietseinheiten.gemeindeverbaende ADD COLUMN IF NOT EXISTS status_id integer;

COMMIT;
