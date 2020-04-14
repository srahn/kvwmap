BEGIN;

ALTER TABLE `druckausschnitte` ADD `epsg_code` INT(6) NULL AFTER `name`;

COMMIT;
