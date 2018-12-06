BEGIN;

ALTER TABLE `layer_attributes` ADD `dont_use_for_new` BOOLEAN AFTER `raster_visibility`;

COMMIT;
