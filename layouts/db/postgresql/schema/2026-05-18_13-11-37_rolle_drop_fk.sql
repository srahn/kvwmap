BEGIN;

ALTER TABLE kvwmap.rolle DROP CONSTRAINT rolle_fk_saved_layers;

COMMIT;
