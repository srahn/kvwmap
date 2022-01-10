BEGIN;

ALTER TABLE `layer_attributes` ADD `schema` VARCHAR(100) NULL AFTER `table_alias_name`;

COMMIT;
