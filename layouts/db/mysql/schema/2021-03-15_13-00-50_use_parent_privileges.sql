BEGIN;

	ALTER TABLE `used_layer` ADD `use_parent_privileges` BOOLEAN NOT NULL DEFAULT TRUE AFTER `export_privileg`;

COMMIT;
