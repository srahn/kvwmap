BEGIN;

ALTER TABLE `layer` ADD `sizeunits` integer(2) NULL AFTER `toleranceunits`;

COMMIT;
