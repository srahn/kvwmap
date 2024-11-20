BEGIN;

ALTER TABLE `classes` CHANGE `classification` `classification` VARCHAR(255) NULL DEFAULT NULL;

COMMIT;
