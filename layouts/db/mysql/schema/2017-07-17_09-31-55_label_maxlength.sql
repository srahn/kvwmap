BEGIN;

ALTER TABLE `labels` ADD `maxlength` INT(3) NULL AFTER `partials`;

COMMIT;
