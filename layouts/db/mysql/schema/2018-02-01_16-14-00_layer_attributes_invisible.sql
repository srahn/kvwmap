BEGIN;

ALTER TABLE `layer_attributes` ADD `visible` BOOLEAN NOT NULL DEFAULT TRUE AFTER `quicksearch`;

COMMIT;
