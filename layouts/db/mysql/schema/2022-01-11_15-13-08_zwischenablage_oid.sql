BEGIN;

ALTER TABLE `zwischenablage` CHANGE `oid` `oid` VARCHAR(50) NOT NULL;

COMMIT;
