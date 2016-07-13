BEGIN;

ALTER TABLE `stelle_gemeinden` ADD `Gemarkung` INT( 6 ) NULL , ADD  `Flur` INT( 3 ) NULL;

ALTER TABLE `stelle_gemeinden` DROP PRIMARY KEY;

COMMIT;
