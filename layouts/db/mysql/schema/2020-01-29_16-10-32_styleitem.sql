BEGIN;

ALTER TABLE `layer` ADD `styleitem` VARCHAR(100) NULL AFTER `classitem`;

COMMIT;
