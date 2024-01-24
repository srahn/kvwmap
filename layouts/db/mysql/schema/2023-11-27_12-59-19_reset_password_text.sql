BEGIN;

ALTER TABLE `stelle` ADD `reset_password_text` TEXT NULL AFTER `version`;

COMMIT;
