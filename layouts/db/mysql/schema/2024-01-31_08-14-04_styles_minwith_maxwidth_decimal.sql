BEGIN;

ALTER TABLE `styles` CHANGE `minwidth` `minwidth` DECIMAL(5,2) NULL DEFAULT NULL, CHANGE `maxwidth` `maxwidth` DECIMAL(5,2) NULL DEFAULT NULL;

COMMIT;
