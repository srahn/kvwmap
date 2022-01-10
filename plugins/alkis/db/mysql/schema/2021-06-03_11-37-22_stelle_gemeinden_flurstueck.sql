BEGIN;

ALTER TABLE `stelle_gemeinden` ADD `Flurstueck` VARCHAR(10) NULL AFTER `Flur`;

COMMIT;
