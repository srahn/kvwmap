BEGIN;

ALTER TABLE `layer` ADD `requires` INT( 11 ) NULL AFTER `offsite`;

COMMIT;
