BEGIN;

ALTER TABLE `styles` ADD `polaroffset` VARCHAR(255) NULL AFTER `offsety`;

COMMIT;
