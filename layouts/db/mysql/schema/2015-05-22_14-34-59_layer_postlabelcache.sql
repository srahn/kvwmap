BEGIN;

ALTER TABLE `layer` ADD `postlabelcache` BOOLEAN NULL DEFAULT '0' AFTER `labelrequires`;

COMMIT;
