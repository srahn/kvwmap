BEGIN;

	CREATE TABLE	`layer_parameter` (
		`id`,
		`key` VARCHAR( 255 ) NOT NULL ,
		`alias` VARCHAR( 255 ) NOT NULL,
		`default_value` VARCHAR( 255 ) NOT NULL ,
		`options_sql` TEXT NOT NULL
	) ENGINE = MYISAM ;

	ALTER TABLE `rolle`
		ADD `layer_params` text NULL DEFAULT NULL;

	ALTER TABLE `stelle`
		ADD `selectable_layer_params` text NULL DEFAULT NULL;

	ALTER TABLE `classes`
		ADD `class_item` varchar(50) NULL DEFAULT NULL;

COMMIT;