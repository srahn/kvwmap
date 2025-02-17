BEGIN;

ALTER TABLE `layer` ADD `terms_of_use_link` VARCHAR(255) NULL DEFAULT NULL AFTER `metalink`;

COMMIT;
