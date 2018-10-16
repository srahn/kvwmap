BEGIN;

ALTER TABLE `druckfreilinien` CHANGE `offset_attribute` `offset_attribute_start` VARCHAR(255) NULL DEFAULT NULL;

ALTER TABLE `druckfreilinien` ADD COLUMN `offset_attribute_end` VARCHAR(255) NULL DEFAULT NULL AFTER `offset_attribute_start`;

UPDATE `druckfreilinien` SET `offset_attribute_end` = `offset_attribute_start`;

COMMIT;
