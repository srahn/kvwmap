BEGIN;

ALTER TABLE xplankonverter.konvertierungen ADD COLUMN planart character varying NOT NULL DEFAULT 'RP-Plan';

COMMIT;
