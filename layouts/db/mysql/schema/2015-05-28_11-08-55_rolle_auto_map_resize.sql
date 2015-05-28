BEGIN;

ALTER TABLE `rolle` ADD `auto_map_resize` BOOLEAN NOT NULL DEFAULT '1' AFTER `nImageHeight`;

COMMIT;
