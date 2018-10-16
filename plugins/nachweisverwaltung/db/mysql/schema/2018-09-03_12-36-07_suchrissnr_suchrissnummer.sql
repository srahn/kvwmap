BEGIN;

ALTER TABLE `rolle_nachweise` CHANGE `suchrissnr` `suchrissnummer` VARCHAR(20) NOT NULL, CHANGE `suchrissnr2` `suchrissnummer2` VARCHAR(20) NULL DEFAULT NULL, CHANGE `suchfortf` `suchfortfuehrung` INT(4) NULL DEFAULT NULL;

COMMIT;
