BEGIN;

ALTER TABLE `u_rolle2used_layer` ADD `gle_view` BOOLEAN NULL DEFAULT NULL AFTER `queryStatus`;

COMMIT;
