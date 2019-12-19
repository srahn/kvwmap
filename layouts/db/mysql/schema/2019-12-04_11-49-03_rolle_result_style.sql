BEGIN;

ALTER TABLE `rolle` ADD `result_hatching` BOOLEAN NOT NULL DEFAULT FALSE AFTER `result_color`, ADD `result_transparency` TINYINT NOT NULL DEFAULT '60' AFTER `result_hatching`;

COMMIT;
