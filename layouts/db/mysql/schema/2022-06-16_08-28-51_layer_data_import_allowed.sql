BEGIN;

ALTER TABLE `user` ADD `layer_data_import_allowed` BOOLEAN NULL AFTER `share_rollenlayer_allowed`;

COMMIT;
