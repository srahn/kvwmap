BEGIN;

ALTER TABLE `rolle_nachweise` ADD `suchhauptart` VARCHAR(50) NULL AFTER `stelle_id`;

ALTER TABLE `rolle_nachweise` ADD `suchunterart` VARCHAR(255) NULL AFTER `suchhauptart`;

ALTER TABLE `rolle_nachweise` ADD `showhauptart` VARCHAR(50) NULL AFTER `sVermStelle`;

ALTER TABLE `rolle_nachweise` ADD `markhauptart` VARCHAR(50) NULL AFTER `showhauptart`;

UPDATE `rolle_nachweise` SET suchhauptart = CONCAT_WS(',', IF(suchffr=1, '1', NULL), IF(suchkvz=1, '2', NULL), IF(suchgn=1, '3', NULL), IF(suchan=1, '4', NULL));

UPDATE `rolle_nachweise` SET showhauptart = CONCAT_WS(',', IF(showffr=1, '1', NULL), IF(showkvz=1, '2', NULL), IF(showgn=1, '3', NULL), IF(showan=1, '4', NULL));

UPDATE `rolle_nachweise` SET markhauptart = CONCAT_WS(',', IF(markffr=1, '1', NULL), IF(markkvz=1, '2', NULL), IF(markgn=1, '3', NULL));

ALTER TABLE `rolle_nachweise` DROP `suchffr`;

ALTER TABLE `rolle_nachweise` DROP `suchkvz`;

ALTER TABLE `rolle_nachweise` DROP `suchgn`;

ALTER TABLE `rolle_nachweise` DROP `suchan`;

ALTER TABLE `rolle_nachweise` DROP `showffr`;

ALTER TABLE `rolle_nachweise` DROP `showkvz`;

ALTER TABLE `rolle_nachweise` DROP `showgn`;

ALTER TABLE `rolle_nachweise` DROP `showan`;

ALTER TABLE `rolle_nachweise` DROP `markffr`;

ALTER TABLE `rolle_nachweise` DROP `markkvz`;

ALTER TABLE `rolle_nachweise` DROP `markgn`;

COMMIT;
