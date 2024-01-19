BEGIN;

ALTER TABLE `stelle` ADD `invitation_text` TEXT NULL AFTER `reset_password_text`;

COMMIT;