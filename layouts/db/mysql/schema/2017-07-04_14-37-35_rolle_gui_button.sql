BEGIN;

UPDATE `rolle` SET gui = 'gui.php' WHERE gui = 'gui_button.php';

COMMIT;
