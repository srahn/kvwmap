BEGIN;

ALTER TABLE `labels` ADD `repeatdistance` INT(11) NULL AFTER `maxlength`;

COMMIT;
