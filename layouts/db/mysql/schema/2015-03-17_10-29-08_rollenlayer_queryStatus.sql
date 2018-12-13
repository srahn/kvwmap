BEGIN;

ALTER TABLE `rollenlayer` ADD `queryStatus` ENUM('0', '1', '2') NOT NULL AFTER `aktivStatus`;

COMMIT;
