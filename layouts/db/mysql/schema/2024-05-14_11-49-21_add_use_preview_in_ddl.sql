BEGIN;

	ALTER TABLE `datendrucklayouts` ADD `use_previews` tinyint(1) NOT NULL DEFAULT 0;

COMMIT;