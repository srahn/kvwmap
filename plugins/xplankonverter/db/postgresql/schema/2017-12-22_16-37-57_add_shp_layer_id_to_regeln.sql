BEGIN;

ALTER TABLE xplankonverter.regeln ADD COLUMN shp_layer_id integer;

COMMIT;
