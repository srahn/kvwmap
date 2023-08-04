BEGIN;

	ALTER TABLE `layer` CHANGE COLUMN `write_mapserver_templates` `write_mapserver_templates_backup` ENUM('0','1') NOT NULL DEFAULT '0';
	ALTER TABLE `layer` ADD COLUMN `write_mapserver_templates` ENUM('data', 'generic') AFTER `wfs_geom`;
	UPDATE `layer` SET `write_mapserver_templates` = 'data' WHERE `write_mapserver_templates_backup` = '1';
	ALTER TABLE `layer` DROP COLUMN `write_mapserver_templates_backup`;

COMMIT;
