BEGIN;

ALTER TABLE `rolle` ADD `showmapfunctions` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Schaltet die Menüleiste mit den Kartenfunktionen unter der Karte ein oder aus.' AFTER `runningcoords`;

COMMIT;

