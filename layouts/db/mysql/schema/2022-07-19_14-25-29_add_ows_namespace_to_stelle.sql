BEGIN;

	ALTER TABLE `stelle` ADD `ows_namespace` VARCHAR(100) NULL AFTER `logconsume`;

COMMIT;
