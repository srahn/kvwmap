BEGIN;

ALTER TABLE `layer` ADD `maintable_is_view` BOOLEAN NOT NULL DEFAULT '0' AFTER `maintable`;

COMMIT;
