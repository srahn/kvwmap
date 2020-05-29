BEGIN;

ALTER TABLE `druckfreitexte` ADD `width` INT(11) NULL AFTER `size`, ADD `border` BOOLEAN NULL AFTER `width`;

COMMIT;
