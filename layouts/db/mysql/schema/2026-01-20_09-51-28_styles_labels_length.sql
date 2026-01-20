BEGIN;

ALTER TABLE `styles` CHANGE `outlinecolor` `outlinecolor` VARCHAR(50) NULL DEFAULT NULL;

ALTER TABLE `labels` CHANGE `color` `color` VARCHAR(50) NOT NULL;

ALTER TABLE `labels` CHANGE `outlinecolor` `outlinecolor` VARCHAR(50) NULL DEFAULT NULL;

ALTER TABLE `labels` CHANGE `backgroundcolor` `backgroundcolor` VARCHAR(50) NULL DEFAULT NULL;

COMMIT;
