BEGIN;

ALTER TABLE `rollenlayer` ADD `query` TEXT NULL AFTER `Data`;

COMMIT;
