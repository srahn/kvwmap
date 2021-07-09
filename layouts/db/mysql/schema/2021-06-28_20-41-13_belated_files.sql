BEGIN;

	ALTER TABLE `rolle` ADD COLUMN `upload_only_file_metadata` BOOLEAN DEFAULT false;

	CREATE TABLE IF NOT EXISTS `belated_files` (
		id int(11) NOT NULL AUTO_INCREMENT,
		user_id integer NOT NULL,
		layer_id integer NOT NULL,
		dataset_id integer NOT NULL,
		attribute_name varchar(70) NOT NULL,
		name varchar(150) NOT NULL,
		size integer NOT NULL,
		lastmodified integer NOT NULL,
		file text NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;

COMMIT;