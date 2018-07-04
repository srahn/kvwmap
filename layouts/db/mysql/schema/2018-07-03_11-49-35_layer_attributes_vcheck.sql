BEGIN;

ALTER TABLE `layer_attributes` ADD `vcheck_attribute` VARCHAR(255) NULL AFTER `visible`, ADD `vcheck_operator` VARCHAR(4) NULL AFTER `vcheck_attribute`, ADD `vcheck_value` TEXT NULL AFTER `vcheck_operator`;

ALTER TABLE `datatype_attributes` ADD `vcheck_attribute` VARCHAR(255) NULL AFTER `visible`, ADD `vcheck_operator` VARCHAR(4) NULL AFTER `vcheck_attribute`, ADD `vcheck_value` TEXT NULL AFTER `vcheck_operator`;

COMMIT;
