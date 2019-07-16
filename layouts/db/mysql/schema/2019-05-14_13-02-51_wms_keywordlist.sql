BEGIN;

ALTER TABLE `layer` ADD `wms_keywordlist` TEXT NULL AFTER `wms_name`;

COMMIT;