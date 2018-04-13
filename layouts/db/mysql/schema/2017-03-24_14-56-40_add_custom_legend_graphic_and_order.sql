BEGIN;

ALTER TABLE `classes` ADD `legendgraphic` varchar(255);
ALTER TABLE `classes` ADD `legendorder` integer after `drawingorder`;
ALTER TABLE `used_layer` ADD `legendorder` integer after `drawingorder`;
ALTER TABLE `u_groups` ADD `legendorder` integer after `order`;

COMMIT;

