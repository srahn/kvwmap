BEGIN;

ALTER TABLE `styles` ADD `colorrange` VARCHAR( 23 ) NULL AFTER `outlinecolor`,
ADD `datarange` VARCHAR( 255 ) NULL AFTER `colorrange`;

COMMIT;
