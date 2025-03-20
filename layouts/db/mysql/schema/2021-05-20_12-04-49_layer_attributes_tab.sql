BEGIN;

ALTER TABLE `layer_attributes` ADD `tab` VARCHAR(255) NULL AFTER `group`;

COMMIT;
