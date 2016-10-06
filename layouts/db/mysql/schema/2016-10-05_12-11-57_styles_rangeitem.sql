BEGIN;

ALTER TABLE `styles` ADD `rangeitem` VARCHAR(50) NULL AFTER `datarange`;

COMMIT;
