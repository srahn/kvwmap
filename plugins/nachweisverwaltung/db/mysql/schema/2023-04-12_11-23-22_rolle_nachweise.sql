BEGIN;

ALTER TABLE `rolle_nachweise` 
CHANGE `suchflur` `suchflur` VARCHAR(3) NULL DEFAULT NULL, 
CHANGE `suchstammnr` `suchstammnr` VARCHAR(15) NULL DEFAULT NULL, 
CHANGE `suchrissnummer` `suchrissnummer` VARCHAR(20) NULL DEFAULT NULL, 
CHANGE `suchpolygon` `suchpolygon` LONGTEXT NULL DEFAULT NULL, 
CHANGE `flur_thematisch` `flur_thematisch` TINYINT(1) NOT NULL DEFAULT '0', 
CHANGE `alle_der_messung` `alle_der_messung` TINYINT(1) NOT NULL DEFAULT '0';

COMMIT;
