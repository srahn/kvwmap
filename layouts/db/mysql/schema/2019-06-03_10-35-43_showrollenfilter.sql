BEGIN;

ALTER TABLE `rolle` ADD `showrollenfilter` BOOLEAN NOT NULL DEFAULT FALSE AFTER `showlayeroptions`;

COMMIT;
