BEGIN;

ALTER TABLE `layer` ADD `trigger_function` varchar(255) COMMENT 'Wie heist die Trigger Funktion, die ausgelöst werden soll.';

COMMIT;
