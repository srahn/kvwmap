BEGIN;

  ALTER TABLE xplankonverter.konvertierungen ADD COLUMN veroeffentlichungsdatum date;
  UPDATE xplankonverter.konvertierungen SET veroeffentlichungsdatum = CASE WHEN updated_at IS NOT NULL THEN updated_at ELSE created_at END WHERE veroeffentlicht;

COMMIT;
