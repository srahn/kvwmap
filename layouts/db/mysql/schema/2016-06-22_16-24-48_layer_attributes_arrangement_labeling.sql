BEGIN;

ALTER TABLE `layer_attributes` ADD `arrangement` TINYINT(1) NOT NULL DEFAULT '0' AFTER `group` ,
ADD `labeling` TINYINT(1) NOT NULL DEFAULT '0' AFTER `arrangement`;

COMMIT;
