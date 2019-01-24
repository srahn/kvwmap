BEGIN;

ALTER TABLE `rolle_nachweise` ADD `suchfortfuehrung2` INT(4) NULL AFTER `suchfortfuehrung`;

COMMIT;
