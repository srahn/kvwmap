BEGIN;
	ALTER TABLE `ddl_elemente` ADD `label` text NULL AFTER `fontsize`;
	ALTER TABLE `ddl_elemente` ADD `margin` text NULL AFTER `label`;
	ALTER TABLE `datendrucklayouts` ADD `dont_print_empty` tinyint(1) NULL AFTER `margin_right`;
COMMIT;