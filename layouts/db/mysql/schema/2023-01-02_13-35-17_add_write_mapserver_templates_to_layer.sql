BEGIN;

	ALTER TABLE `layer` add `write_mapserver_templates` enum('0', '1') NOT NULL DEFAULT '0'AFTER `wfs_geom`;

COMMIT;
