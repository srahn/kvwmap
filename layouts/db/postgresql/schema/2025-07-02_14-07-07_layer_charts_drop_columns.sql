BEGIN;

ALTER TABLE kvwmap.layer_charts DROP COLUMN aggregate_function;
ALTER TABLE kvwmap.layer_charts DROP COLUMN value_attribute_label;

COMMIT;
