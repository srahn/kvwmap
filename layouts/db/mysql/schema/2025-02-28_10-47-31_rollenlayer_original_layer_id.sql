BEGIN;

ALTER TABLE `rollenlayer` ADD `original_layer_id` INT(11) NULL AFTER `id`;

COMMIT;
