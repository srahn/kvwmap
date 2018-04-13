BEGIN;

ALTER TABLE `rolle` ADD `menue_buttons` BOOLEAN NOT NULL DEFAULT FALSE AFTER `layer_params`;

UPDATE `rolle` SET menue_buttons = 1 WHERE gui = 'gui_button.php';

COMMIT;
