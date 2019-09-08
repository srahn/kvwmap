BEGIN;

ALTER TABLE `datendrucklayouts` ADD `margin_top` INT NOT NULL DEFAULT 40 AFTER `type`, ADD `margin_bottom` INT NOT NULL DEFAULT 30 AFTER `margin_top`, ADD `margin_left` INT NOT NULL DEFAULT 0 AFTER `margin_bottom`, ADD `margin_right` INT NOT NULL DEFAULT 0 AFTER `margin_left`;

COMMIT;
