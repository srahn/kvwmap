BEGIN;

ALTER TABLE `rolle` CHANGE `gui` `gui` VARCHAR(100) NOT NULL DEFAULT 'layouts/gui.php';

COMMIT;
