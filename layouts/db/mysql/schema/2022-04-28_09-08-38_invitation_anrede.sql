BEGIN;

ALTER TABLE `invitations` ADD `anrede` VARCHAR(10) NULL AFTER `stelle_id`;

COMMIT;
