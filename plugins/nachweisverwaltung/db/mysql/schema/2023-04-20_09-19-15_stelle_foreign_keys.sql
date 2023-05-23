BEGIN;

ALTER TABLE `rolle_nachweise` DROP FOREIGN KEY `rolle_nachweise_ibfk_1`;
ALTER TABLE `rolle_nachweise` ADD CONSTRAINT `rolle_nachweise_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rolle_nachweise_dokumentauswahl` DROP FOREIGN KEY `rolle_nachweise_dokumentauswahl_ibfk_1`;
ALTER TABLE `rolle_nachweise_dokumentauswahl` ADD CONSTRAINT `rolle_nachweise_dokumentauswahl_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rolle_nachweise_rechercheauswahl` ADD CONSTRAINT `rolle_nachweise_rechercheauswahl_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
