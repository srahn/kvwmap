BEGIN;

ALTER TABLE kvwmap.rolle ADD hide_deactivated_layers bool DEFAULT false NOT NULL;

COMMIT;
