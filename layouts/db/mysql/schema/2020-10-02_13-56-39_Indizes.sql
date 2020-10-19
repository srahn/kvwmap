BEGIN;

ALTER TABLE `rolle` ADD INDEX `user_id_idx` (`user_id`);

ALTER TABLE `used_layer` ADD INDEX `layer_id_idx` (`Layer_ID`);

COMMIT;
