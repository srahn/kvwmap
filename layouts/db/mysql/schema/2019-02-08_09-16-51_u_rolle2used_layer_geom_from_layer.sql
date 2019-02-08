BEGIN;

ALTER TABLE `u_rolle2used_layer` ADD `geom_from_layer` INT NOT NULL;

UPDATE `u_rolle2used_layer` SET `geom_from_layer` = `layer_id`;

COMMIT;
