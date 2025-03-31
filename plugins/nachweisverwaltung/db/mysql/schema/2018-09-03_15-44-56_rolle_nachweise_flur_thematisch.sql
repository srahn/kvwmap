BEGIN;

ALTER TABLE `rolle_nachweise` ADD `flur_thematisch` BOOLEAN NOT NULL;

COMMIT;
