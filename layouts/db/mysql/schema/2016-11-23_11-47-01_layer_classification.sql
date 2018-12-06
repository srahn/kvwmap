BEGIN;

ALTER TABLE `layer` ADD `classification` VARCHAR(50) NULL AFTER `classitem`;

ALTER TABLE `classes` CHANGE `class_item` `classification` VARCHAR(50) NULL DEFAULT NULL;

COMMIT;
