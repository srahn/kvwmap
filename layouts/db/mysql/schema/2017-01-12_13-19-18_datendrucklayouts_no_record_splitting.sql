BEGIN;

ALTER TABLE `datendrucklayouts` ADD `no_record_splitting` BOOLEAN NOT NULL DEFAULT FALSE AFTER `gap`;

COMMIT;
