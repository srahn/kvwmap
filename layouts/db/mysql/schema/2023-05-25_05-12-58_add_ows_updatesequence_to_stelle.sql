BEGIN;

	ALTER TABLE `stelle` ADD `ows_updatesequence` VARCHAR(100) NULL AFTER `ows_distributionperson`;

COMMIT;
