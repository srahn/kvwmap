BEGIN;
  ALTER TABLE `used_layer` CHANGE `postlabelcache` `postlabelcache` BOOLEAN NOT NULL DEFAULT FALSE;
  ALTER TABLE `styles` CHANGE `minsize` `minsize` VARCHAR(50) NULL DEFAULT NULL;
  ALTER TABLE `styles` CHANGE `maxsize` `maxsize` VARCHAR(50) NULL DEFAULT NULL;
  ALTER TABLE `labels` CHANGE `angle` `angle` VARCHAR(50) NULL DEFAULT NULL;
  ALTER TABLE `layer_attributes` CHANGE `saveable` `saveable` TINYINT(1) NULL DEFAULT NULL;
COMMIT;
