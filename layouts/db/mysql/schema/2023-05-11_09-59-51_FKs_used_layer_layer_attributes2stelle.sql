BEGIN;

DELETE FROM `used_layer` WHERE `Layer_ID` NOT IN (SELECT Layer_ID FROM `layer`);
ALTER TABLE `used_layer` ADD CONSTRAINT `used_layer_ibfk_2` FOREIGN KEY (`Layer_ID`) REFERENCES `layer`(`Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM `layer_attributes2stelle` WHERE `layer_id` NOT IN (SELECT Layer_ID FROM `layer`);
ALTER TABLE `layer_attributes2stelle` ADD CONSTRAINT `layer_attributes2stelle_ibfk_2` FOREIGN KEY (`layer_id`) REFERENCES `layer`(`Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM `u_rolle2used_layer` WHERE `layer_id` NOT IN (SELECT Layer_ID FROM `layer`);
ALTER TABLE `u_rolle2used_layer` ADD CONSTRAINT `u_rolle2used_layer_ibfk_2` FOREIGN KEY (`layer_id`) REFERENCES `layer`(`Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
