BEGIN;

ALTER TABLE `rolle_nachweise` ADD `suchbemerkung` TEXT NULL AFTER `sVermStelle`;

COMMIT;
