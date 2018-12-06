BEGIN;

ALTER TABLE `rolle` ADD `legendtype` BOOLEAN NOT NULL DEFAULT FALSE AFTER `menue_buttons`;

COMMIT;
