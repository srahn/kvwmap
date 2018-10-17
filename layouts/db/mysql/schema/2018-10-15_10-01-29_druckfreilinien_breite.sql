BEGIN;

ALTER TABLE `druckfreilinien` CHANGE `breite` `breite` FLOAT NOT NULL;

COMMIT;
