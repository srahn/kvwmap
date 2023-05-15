BEGIN;

ALTER TABLE ddl2stelle ENGINE=InnoDB;
ALTER TABLE druckrahmen2stelle ENGINE=InnoDB;
ALTER TABLE stelle_gemeinden ENGINE=InnoDB;
ALTER TABLE used_layer ENGINE=InnoDB;
ALTER TABLE u_attributfilter2used_layer ENGINE=InnoDB;
ALTER TABLE u_funktion2stelle ENGINE=InnoDB;


ALTER TABLE `ddl2stelle` ADD CONSTRAINT `ddl2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

delete from `druckrahmen2stelle` where `stelle_id` not in (select ID from stelle);
ALTER TABLE `druckrahmen2stelle` ADD CONSTRAINT `druckrahmen2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `invitations` ADD CONSTRAINT `invitations_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM `layer_attributes2stelle` WHERE `stelle_id` NOT IN (SELECT `ID` FROM stelle);

ALTER TABLE `layer_attributes2stelle` ADD CONSTRAINT `layer_attributes2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

delete from `stellen_hierarchie` where `parent_id` not in (select ID from stelle);
delete from `stellen_hierarchie` where `child_id` not in (select ID from stelle);
ALTER TABLE `stellen_hierarchie` ADD CONSTRAINT `stellen_hierarchie_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `stellen_hierarchie` ADD CONSTRAINT `stellen_hierarchie_ibfk_2` FOREIGN KEY (`child_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

delete from `stelle_gemeinden` where `Stelle_ID` not in (select ID from stelle);
ALTER TABLE `stelle_gemeinden` ADD CONSTRAINT `stelle_gemeinden_ibfk_1` FOREIGN KEY (`Stelle_ID`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

delete from `used_layer` where `Stelle_ID` not in (select ID from stelle);
ALTER TABLE `used_layer` ADD CONSTRAINT `used_layer_ibfk_1` FOREIGN KEY (`Stelle_ID`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;


DELETE FROM `u_attributfilter2used_layer` WHERE concat(`Stelle_ID`, '_', `Layer_ID`) NOT in (select concat(`Stelle_ID`, '_', `Layer_ID`) from used_layer);
ALTER TABLE `u_attributfilter2used_layer` ADD CONSTRAINT `u_attributfilter2used_layer_ibfk_1` FOREIGN KEY (`Stelle_ID`, `Layer_ID`) REFERENCES `used_layer`(`Stelle_ID`, `Layer_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

delete from `u_funktion2stelle` where stelle_id not in (select ID from stelle);
ALTER TABLE `u_funktion2stelle` ADD CONSTRAINT `u_funktion2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `u_menue2stelle` DROP FOREIGN KEY `u_menue2stelle_ibfk_1`;
ALTER TABLE `u_menue2stelle` ADD CONSTRAINT `u_menue2stelle_ibfk_1` FOREIGN KEY (`stelle_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

delete from `rolle` where stelle_id not in (select ID from stelle);
ALTER TABLE `rolle` DROP FOREIGN KEY `rolle_ibfk_1`;
ALTER TABLE `rolle` ADD CONSTRAINT `rolle_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `rolle` ADD CONSTRAINT `rolle_ibfk_2` FOREIGN KEY (`stelle_id`) REFERENCES `stelle`(`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `layer_attributes2rolle` DROP FOREIGN KEY `layer_attributes2rolle_ibfk_1`;
ALTER TABLE `layer_attributes2rolle` ADD CONSTRAINT `layer_attributes2rolle_ibfk_1` FOREIGN KEY (`layer_id`, `attributename`, `stelle_id`) REFERENCES `layer_attributes2stelle`(`layer_id`, `attributename`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `layer_attributes2rolle` DROP FOREIGN KEY `layer_attributes2rolle_ibfk_2`;
ALTER TABLE `layer_attributes2rolle` ADD CONSTRAINT `layer_attributes2rolle_ibfk_2` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rollenlayer` DROP FOREIGN KEY `rollenlayer_ibfk_1`;
ALTER TABLE `rollenlayer` ADD CONSTRAINT `rollenlayer_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rolle_csv_attributes` DROP FOREIGN KEY `rolle_csv_attributes_ibfk_1`;
ALTER TABLE `rolle_csv_attributes` ADD CONSTRAINT `rolle_csv_attributes_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

DELETE FROM `rolle_export_settings` WHERE concat(`stelle_id`, '_', `user_id`) NOT in (select concat(`stelle_id`, '_', `user_id`) from rolle);
ALTER TABLE `rolle_export_settings` ADD CONSTRAINT `rolle_export_settings_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rolle_last_query` DROP FOREIGN KEY `rolle_last_query_ibfk_1`;
ALTER TABLE `rolle_last_query` ADD CONSTRAINT `rolle_last_query_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rolle_saved_layers` DROP FOREIGN KEY `rolle_saved_layers_ibfk_1`;
ALTER TABLE `rolle_saved_layers` ADD CONSTRAINT `rolle_saved_layers_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `search_attributes2rolle` DROP FOREIGN KEY `search_attributes2rolle_ibfk_1`;
ALTER TABLE `search_attributes2rolle` ADD CONSTRAINT `search_attributes2rolle_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `u_groups2rolle` DROP FOREIGN KEY `u_groups2rolle_ibfk_1`;
ALTER TABLE `u_groups2rolle` ADD CONSTRAINT `u_groups2rolle_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `u_menue2rolle` DROP FOREIGN KEY `u_menue2rolle_ibfk_1`;
ALTER TABLE `u_menue2rolle` ADD CONSTRAINT `u_menue2rolle_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `u_rolle2used_class` DROP FOREIGN KEY `u_rolle2used_class_ibfk_1`;
ALTER TABLE `u_rolle2used_class` ADD CONSTRAINT `u_rolle2used_class_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `u_rolle2used_layer` DROP FOREIGN KEY `u_rolle2used_layer_ibfk_1`;
ALTER TABLE `u_rolle2used_layer` ADD CONSTRAINT `u_rolle2used_layer_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `zwischenablage` DROP FOREIGN KEY `zwischenablage_ibfk_1`;
ALTER TABLE `zwischenablage` ADD CONSTRAINT `zwischenablage_ibfk_1` FOREIGN KEY (`user_id`, `stelle_id`) REFERENCES `rolle`(`user_id`, `stelle_id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
