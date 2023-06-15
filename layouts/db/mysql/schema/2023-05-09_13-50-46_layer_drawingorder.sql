BEGIN;

UPDATE `layer` SET drawingorder = 0 where drawingorder IS NULL;
ALTER TABLE `layer` CHANGE `drawingorder` `drawingorder` INT(11) NOT NULL DEFAULT '0';

UPDATE `layer` SET postlabelcache = 0 where postlabelcache IS NULL;
ALTER TABLE `layer` CHANGE `postlabelcache` `postlabelcache` TINYINT(1) NOT NULL DEFAULT '0';

COMMIT;
