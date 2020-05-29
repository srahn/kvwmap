BEGIN;

	ALTER TABLE `layer` ADD `duplicate_from_layer_id` integer, ADD `duplicate_criterion` varchar(255);
	ALTER TABLE `rollenlayer` ADD `duplicate_from_layer_id` integer, ADD `duplicate_criterion` varchar(255);

COMMIT;
