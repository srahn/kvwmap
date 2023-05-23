BEGIN;

ALTER TABLE `labels` ADD `text` VARCHAR(50) NULL AFTER `the_force`;

COMMIT;
