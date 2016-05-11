BEGIN;

ALTER TABLE `layer` ADD `cluster_maxdistance` INT( 11 ) NULL AFTER `filteritem`;

COMMIT;
