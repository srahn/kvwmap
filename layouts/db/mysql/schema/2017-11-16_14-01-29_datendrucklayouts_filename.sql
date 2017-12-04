BEGIN;

ALTER TABLE `datendrucklayouts` ADD `filename` VARCHAR(255) NULL DEFAULT NULL AFTER `no_record_splitting`;

COMMIT;
