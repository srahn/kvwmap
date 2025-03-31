BEGIN;

ALTER TABLE `rolle_nachweise_dokumentauswahl` ADD `suchhauptart` VARCHAR(50) NULL AFTER `name`;

ALTER TABLE `rolle_nachweise_dokumentauswahl` CHANGE `andere` `suchunterart` TEXT NOT NULL;

UPDATE `rolle_nachweise_dokumentauswahl` SET suchhauptart = CONCAT_WS(',', IF(ffr=1, '1', NULL), IF(kvz=1, '2', NULL), IF(gn=1, '3', NULL), IF(suchunterart!='', '4', NULL));

ALTER TABLE `rolle_nachweise_dokumentauswahl` DROP `ffr`;

ALTER TABLE `rolle_nachweise_dokumentauswahl` DROP `kvz`;

ALTER TABLE `rolle_nachweise_dokumentauswahl` DROP `gn`;

COMMIT;
