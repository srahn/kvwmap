BEGIN;

ALTER TABLE kvwmap.layer_attributes DROP COLUMN vcheck_attribute;
ALTER TABLE kvwmap.layer_attributes DROP COLUMN vcheck_operator;
ALTER TABLE kvwmap.layer_attributes DROP COLUMN vcheck_value;

ALTER TABLE kvwmap.used_layer DROP COLUMN legendorder;

COMMIT;
