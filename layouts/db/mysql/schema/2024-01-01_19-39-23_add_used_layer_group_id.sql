BEGIN;
  ALTER TABLE `used_layer` ADD `group_id` INTEGER NULL AFTER `Layer_ID`;
COMMIT;