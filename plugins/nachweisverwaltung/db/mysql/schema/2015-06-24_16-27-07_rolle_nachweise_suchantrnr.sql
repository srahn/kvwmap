BEGIN;

ALTER TABLE `rolle_nachweise` CHANGE `suchantrnr` `suchantrnr` VARCHAR( 23 ) NOT NULL DEFAULT '';

COMMIT;
