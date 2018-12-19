BEGIN;

ALTER TABLE `styles` CHANGE `symbolname` `symbolname` TEXT NULL DEFAULT NULL;

COMMIT;
