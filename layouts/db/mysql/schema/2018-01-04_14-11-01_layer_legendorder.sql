BEGIN;

ALTER TABLE `layer` ADD `legendorder` INT(11) NULL AFTER `drawingorder`;

ALTER TABLE `used_layer` ADD `legendorder` INT(11) NULL AFTER `drawingorder`;

COMMIT;
