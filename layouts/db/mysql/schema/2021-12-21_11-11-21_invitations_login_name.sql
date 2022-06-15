BEGIN;

ALTER TABLE `invitations` ADD `loginname` VARCHAR(100) NOT NULL AFTER `vorname`;

COMMIT;
