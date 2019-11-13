BEGIN;

ALTER TABLE `datendrucklayouts` ADD `columns` TINYINT NOT NULL DEFAULT 0 AFTER `no_record_splitting`;

COMMIT;
