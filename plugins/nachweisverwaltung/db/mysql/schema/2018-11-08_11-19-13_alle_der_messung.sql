BEGIN;

ALTER TABLE `rolle_nachweise` ADD `alle_der_messung` BOOLEAN NOT NULL;

COMMIT;
