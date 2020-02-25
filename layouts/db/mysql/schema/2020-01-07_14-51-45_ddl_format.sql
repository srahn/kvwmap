BEGIN;

ALTER TABLE `datendrucklayouts` ADD `format` VARCHAR(10) NOT NULL DEFAULT 'A4 hoch' AFTER `layer_id`;

COMMIT;
