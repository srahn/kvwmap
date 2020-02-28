BEGIN;

ALTER TABLE `layer_attributes` ADD `saveable` BOOLEAN NOT NULL AFTER `constraints`;

UPDATE `layer_attributes` SET `saveable` = 1 WHERE `type` != 'not_saveable';

UPDATE `layer_attributes` SET `saveable` = 0 WHERE `type` = 'not_saveable';

COMMIT;
