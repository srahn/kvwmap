BEGIN;

DELETE FROM `rolle_nachweise` WHERE concat(`stelle_id`, '_', `user_id`) NOT in (select concat(`stelle_id`, '_', `user_id`) from rolle);
ALTER TABLE `rolle_nachweise` DROP FOREIGN KEY `rolle_nachweise_ibfk_1`;
ALTER TABLE `rolle_nachweise` ADD CONSTRAINT `rolle_nachweise_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM `rolle_nachweise_dokumentauswahl` WHERE concat(`stelle_id`, '_', `user_id`) NOT in (select concat(`stelle_id`, '_', `user_id`) from rolle);
ALTER TABLE `rolle_nachweise_dokumentauswahl` DROP FOREIGN KEY `rolle_nachweise_dokumentauswahl_ibfk_1`;
ALTER TABLE `rolle_nachweise_dokumentauswahl` ADD CONSTRAINT `rolle_nachweise_dokumentauswahl_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM `rolle_nachweise_rechercheauswahl` WHERE concat(`stelle_id`, '_', `user_id`) NOT in (select concat(`stelle_id`, '_', `user_id`) from rolle);
ALTER TABLE `rolle_nachweise_rechercheauswahl` ADD CONSTRAINT `rolle_nachweise_rechercheauswahl_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
