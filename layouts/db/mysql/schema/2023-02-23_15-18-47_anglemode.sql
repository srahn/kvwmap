BEGIN;

ALTER TABLE `labels` CHANGE `autoangle` `anglemode` TINYINT(1) NULL DEFAULT NULL;

UPDATE `labels` SET `anglemode` = 110 WHERE `anglemode` = 1;

COMMIT;
