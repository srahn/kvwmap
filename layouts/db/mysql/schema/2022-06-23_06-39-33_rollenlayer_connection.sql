BEGIN;

ALTER TABLE `rollenlayer` CHANGE `connection` `connection` VARCHAR(255) NULL DEFAULT NULL;

COMMIT;
