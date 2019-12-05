BEGIN;

ALTER TABLE `layer_attributes` CHANGE `constraints` `constraints` TEXT NULL DEFAULT NULL;

COMMIT;
