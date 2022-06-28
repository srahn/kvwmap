BEGIN;

	ALTER TABLE `layer` ADD `identifier_text` VARCHAR(50) NULL AFTER `oid`;

COMMIT;
