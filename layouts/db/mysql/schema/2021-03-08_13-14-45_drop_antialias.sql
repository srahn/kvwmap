BEGIN;

ALTER TABLE `labels` DROP `antialias`;

ALTER TABLE `styles` DROP `antialias`;

COMMIT;
