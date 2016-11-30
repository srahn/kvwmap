BEGIN;

ALTER TABLE `styles` ADD `minscale` INT(11) UNSIGNED NULL AFTER `maxsize`, ADD `maxscale` INT(11) UNSIGNED NULL AFTER `minscale`;

COMMIT;
