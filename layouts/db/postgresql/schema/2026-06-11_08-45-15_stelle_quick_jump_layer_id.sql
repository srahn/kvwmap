BEGIN;

ALTER TABLE kvwmap.stelle ADD quick_jump_layer_id int4 NULL;

COMMIT;
