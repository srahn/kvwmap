BEGIN;

	ALTER TABLE `stelle` ADD `protected` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `ows_srs`;

COMMIT;
