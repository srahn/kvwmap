BEGIN;

ALTER TABLE `kvwmapdb`.`rolle` ADD INDEX `user_id_idx` (`user_id`);

ALTER TABLE `kvwmapdb`.`used_layer` ADD INDEX `layer_id_idx` (`Layer_ID`);

COMMIT;
