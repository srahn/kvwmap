BEGIN;

  ALTER TABLE xplankonverter.konvertierungen
    ADD COLUMN upload_plan_data_at date,
    ADD COLUMN upload_plan_data_from character varying,
    ADD COLUMN update_plan_data_at date,
    ADD COLUMN update_plan_data_from character varying;

COMMIT;
