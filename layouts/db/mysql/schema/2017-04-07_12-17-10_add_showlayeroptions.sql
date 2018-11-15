BEGIN;

ALTER TABLE `rolle` ADD `showlayeroptions` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Schaltet die Layeroptionen in der Legende ein oder aus.' AFTER `showmapfunctions`;

COMMIT;