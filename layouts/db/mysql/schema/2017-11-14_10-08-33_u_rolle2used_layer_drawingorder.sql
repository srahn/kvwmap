BEGIN;

ALTER TABLE `u_rolle2used_layer` ADD `drawingorder` INT(11) NULL DEFAULT NULL AFTER `transparency`;

COMMIT;
