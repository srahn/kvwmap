BEGIN;
  ALTER TABLE xplankonverter.konvertierungen ADD column dokumente character varying[];
  COMMENT ON COLUMN xplankonverter.konvertierungen.dokumente
    IS 'Beliebige zur Konvertierung gehörigen Dokumente bzw. Dateien, z.B. Rasterdateien oder sonstige Erläuterungen in Dateiform.';
COMMIT;