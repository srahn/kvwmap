BEGIN;

ALTER TABLE `rolle_nachweise` CHANGE `suchpolygon` `suchpolygon` MEDIUMTEXT NOT NULL;

COMMIT;
