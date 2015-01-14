BEGIN;

ALTER TABLE `styles` ADD `initialgap` DECIMAL( 5, 2 ) NULL DEFAULT NULL AFTER `gap`,
										 ADD `opacity` INTEGER(11) NULL DEFAULT NULL AFTER `outlinecolor`;

COMMIT;
