BEGIN;

ALTER TABLE `layer` ADD `trigger_fired` ENUM('BEFORE', 'AFTER', 'INSTEAD OF') COMMENT 'Wann soll der Trigger ausgelöst werden.';
ALTER TABLE `layer` ADD `trigger_event` ENUM('INSERT', 'UPDATE', 'DELETE', 'TRUNCATE') COMMENT 'Bei welchem Ereignis soll der Trigger ausgelöst werden.';
ALTER TABLE `layer` ADD `trigger_function` varchar(255) COMMENT 'Wie heist die Trigger Funktion, die ausgelöst werden soll.';
ALTER TABLE `layer` ADD `trigger_function_params` text COMMENT 'Die Parameter die an die Trigger Funktion übergeben werden sollen, Symikolonsepariert, z.B.  1, "test", old.$name, new.$name';

COMMIT;
